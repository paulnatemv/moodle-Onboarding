<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace local_onboarding;

/**
 * Event observer for local_onboarding.
 *
 * @package    local_onboarding
 * @copyright  2025 BixAgency.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class observer {

    /**
     * Handle user login event - redirect to onboarding if needed.
     *
     * @param \core\event\user_loggedin $event The event object.
     * @return void
     */
    public static function user_loggedin(\core\event\user_loggedin $event): void {
        global $SESSION, $CFG, $DB;

        $userid = $event->userid;

        // Check if plugin is enabled.
        if (!get_config('local_onboarding', 'enabled')) {
            return;
        }

        // Skip for admin users (unless showadmins is enabled).
        if (is_siteadmin($userid) && !get_config('local_onboarding', 'showadmins')) {
            return;
        }

        // Check if user has bypass capability.
        $context = \context_system::instance();
        if (has_capability('local/onboarding:bypass', $context, $userid)) {
            return;
        }

        // Get active flow for user.
        $flow = self::get_active_flow_for_user($userid);
        if (!$flow) {
            return;
        }

        // Check if user has already completed this flow.
        $completion = $DB->get_record('local_onboarding_completion', [
            'userid' => $userid,
            'flowid' => $flow->id,
        ]);

        if ($completion && $completion->status === 'completed') {
            return;
        }

        // Store original destination.
        if (!empty($SESSION->wantsurl)) {
            $SESSION->onboarding_returnurl = $SESSION->wantsurl;
        } else {
            $SESSION->onboarding_returnurl = $CFG->wwwroot . '/my/';
        }

        // Redirect to onboarding.
        $onboardingurl = new \moodle_url('/local/onboarding/index.php', ['flowid' => $flow->id]);
        $SESSION->wantsurl = $onboardingurl->out(false);
    }

    /**
     * Get the active onboarding flow for a user.
     *
     * @param int $userid The user ID.
     * @return \stdClass|null The flow record or null if none found.
     */
    private static function get_active_flow_for_user(int $userid): ?\stdClass {
        global $DB;

        // Get all enabled flows ordered by sortorder.
        $flows = $DB->get_records('local_onboarding_flows', ['enabled' => 1], 'sortorder ASC');

        if (empty($flows)) {
            return null;
        }

        // Get user's roles.
        $userroles = self::get_user_role_ids($userid);

        foreach ($flows as $flow) {
            // If no target roles specified, flow applies to all users.
            if (empty($flow->targetroles)) {
                return $flow;
            }

            // Check if user has any of the target roles.
            $targetroles = json_decode($flow->targetroles, true);
            if (!empty($targetroles) && is_array($targetroles)) {
                foreach ($targetroles as $roleid) {
                    if (in_array($roleid, $userroles)) {
                        return $flow;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Get all role IDs assigned to a user.
     *
     * @param int $userid The user ID.
     * @return array Array of role IDs.
     */
    private static function get_user_role_ids(int $userid): array {
        global $DB;

        $sql = "SELECT DISTINCT ra.roleid
                FROM {role_assignments} ra
                WHERE ra.userid = :userid";

        $roles = $DB->get_records_sql($sql, ['userid' => $userid]);

        return array_keys($roles);
    }
}
