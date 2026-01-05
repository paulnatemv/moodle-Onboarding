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
 * Hook callbacks for local_onboarding.
 *
 * @package    local_onboarding
 * @copyright  2025 BixAgency.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_callbacks {

    /**
     * Callback for before_footer_html_generation hook.
     *
     * Auto-redirects from email confirmation page to onboarding for seamless UX.
     *
     * @param \core\hook\output\before_footer_html_generation $hook
     */
    public static function before_footer(\core\hook\output\before_footer_html_generation $hook): void {
        global $PAGE, $USER, $SESSION;

        // Only run on the login confirm page.
        if ($PAGE->pagetype !== 'login-confirm') {
            return;
        }

        // Must be logged in.
        if (!isloggedin() || isguestuser()) {
            return;
        }

        // Check if plugin is enabled.
        if (!get_config('local_onboarding', 'enabled')) {
            return;
        }

        // Check if user needs onboarding (has wantsurl set to onboarding).
        if (empty($SESSION->wantsurl) || strpos($SESSION->wantsurl, '/local/onboarding/') === false) {
            return;
        }

        $onboardingurl = $SESSION->wantsurl;

        // Inject JavaScript to auto-redirect after a brief moment.
        $PAGE->requires->js_amd_inline("
            require([], function() {
                setTimeout(function() {
                    window.location.href = " . json_encode($onboardingurl) . ";
                }, 1500);
            });
        ");
    }
}
