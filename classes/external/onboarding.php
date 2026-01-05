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

namespace local_onboarding\external;

defined('MOODLE_INTERNAL') || die();

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_single_structure;
use core_external\external_value;
use local_onboarding\completion;
use local_onboarding\step;
use local_onboarding\flow;

/**
 * External API for onboarding AJAX calls.
 *
 * @package    local_onboarding
 * @copyright  2025 BixAgency.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class onboarding extends external_api {

    /**
     * Parameters for update_video_time.
     *
     * @return external_function_parameters
     */
    public static function update_video_time_parameters(): external_function_parameters {
        return new external_function_parameters([
            'flowid' => new external_value(PARAM_INT, 'The flow ID'),
            'stepid' => new external_value(PARAM_INT, 'The step ID'),
            'seconds' => new external_value(PARAM_INT, 'Seconds of video watched'),
        ]);
    }

    /**
     * Update video watch time.
     *
     * @param int $flowid The flow ID.
     * @param int $stepid The step ID.
     * @param int $seconds Seconds watched.
     * @return array
     */
    public static function update_video_time(int $flowid, int $stepid, int $seconds): array {
        global $USER;

        $params = self::validate_parameters(self::update_video_time_parameters(), [
            'flowid' => $flowid,
            'stepid' => $stepid,
            'seconds' => $seconds,
        ]);

        $context = \context_system::instance();
        self::validate_context($context);

        $completion = completion::get_or_create($USER->id, $params['flowid']);
        $completion->update_videotime($params['seconds']);

        return [
            'success' => true,
            'videotime' => $completion->get_videotime(),
        ];
    }

    /**
     * Returns for update_video_time.
     *
     * @return external_single_structure
     */
    public static function update_video_time_returns(): external_single_structure {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Whether the update was successful'),
            'videotime' => new external_value(PARAM_INT, 'Total video time recorded'),
        ]);
    }

    /**
     * Parameters for complete_step.
     *
     * @return external_function_parameters
     */
    public static function complete_step_parameters(): external_function_parameters {
        return new external_function_parameters([
            'flowid' => new external_value(PARAM_INT, 'The flow ID'),
            'stepid' => new external_value(PARAM_INT, 'The step ID'),
        ]);
    }

    /**
     * Complete current step and advance.
     *
     * @param int $flowid The flow ID.
     * @param int $stepid The step ID.
     * @return array
     */
    public static function complete_step(int $flowid, int $stepid): array {
        global $USER;

        $params = self::validate_parameters(self::complete_step_parameters(), [
            'flowid' => $flowid,
            'stepid' => $stepid,
        ]);

        $context = \context_system::instance();
        self::validate_context($context);

        $completion = completion::get_or_create($USER->id, $params['flowid']);
        $hasmore = $completion->advance();

        $result = [
            'success' => true,
            'flowcompleted' => !$hasmore,
            'nextstep' => null,
        ];

        if ($hasmore) {
            $nextstep = $completion->get_current_step();
            if ($nextstep) {
                $result['nextstep'] = $nextstep->export_for_template();
            }
        }

        return $result;
    }

    /**
     * Returns for complete_step.
     *
     * @return external_single_structure
     */
    public static function complete_step_returns(): external_single_structure {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Whether the step was completed'),
            'flowcompleted' => new external_value(PARAM_BOOL, 'Whether the entire flow is now complete'),
            'nextstep' => new external_single_structure([
                'id' => new external_value(PARAM_INT, 'Step ID'),
                'title' => new external_value(PARAM_TEXT, 'Step title'),
                'content' => new external_value(PARAM_RAW, 'Step content'),
                'steptype' => new external_value(PARAM_ALPHA, 'Step type'),
                'hasvideo' => new external_value(PARAM_BOOL, 'Has video'),
                'videoembedurl' => new external_value(PARAM_URL, 'Video embed URL', VALUE_OPTIONAL),
                'videorequired' => new external_value(PARAM_BOOL, 'Video required'),
                'videocompletion' => new external_value(PARAM_INT, 'Video completion percentage'),
                'hasimage' => new external_value(PARAM_BOOL, 'Has image'),
                'imageurl' => new external_value(PARAM_URL, 'Image URL', VALUE_OPTIONAL),
                'hascta' => new external_value(PARAM_BOOL, 'Has CTA'),
                'ctabutton' => new external_value(PARAM_TEXT, 'CTA button text', VALUE_OPTIONAL),
                'ctaurl' => new external_value(PARAM_URL, 'CTA URL', VALUE_OPTIONAL),
                'ctanewtab' => new external_value(PARAM_BOOL, 'CTA opens new tab'),
                'stepnumber' => new external_value(PARAM_INT, 'Step number'),
                'isfirst' => new external_value(PARAM_BOOL, 'Is first step'),
                'islast' => new external_value(PARAM_BOOL, 'Is last step'),
            ], 'Next step data', VALUE_OPTIONAL),
        ]);
    }

    /**
     * Parameters for get_step.
     *
     * @return external_function_parameters
     */
    public static function get_step_parameters(): external_function_parameters {
        return new external_function_parameters([
            'stepid' => new external_value(PARAM_INT, 'The step ID'),
        ]);
    }

    /**
     * Get step data.
     *
     * @param int $stepid The step ID.
     * @return array
     */
    public static function get_step(int $stepid): array {
        $params = self::validate_parameters(self::get_step_parameters(), [
            'stepid' => $stepid,
        ]);

        $context = \context_system::instance();
        self::validate_context($context);

        $step = step::instance($params['stepid']);

        return $step->export_for_template();
    }

    /**
     * Returns for get_step.
     *
     * @return external_single_structure
     */
    public static function get_step_returns(): external_single_structure {
        return new external_single_structure([
            'id' => new external_value(PARAM_INT, 'Step ID'),
            'flowid' => new external_value(PARAM_INT, 'Flow ID'),
            'title' => new external_value(PARAM_TEXT, 'Step title'),
            'content' => new external_value(PARAM_RAW, 'Step content'),
            'steptype' => new external_value(PARAM_ALPHA, 'Step type'),
            'hasvideo' => new external_value(PARAM_BOOL, 'Has video'),
            'videourl' => new external_value(PARAM_URL, 'Video URL', VALUE_OPTIONAL),
            'videoembedurl' => new external_value(PARAM_URL, 'Video embed URL', VALUE_OPTIONAL),
            'videorequired' => new external_value(PARAM_BOOL, 'Video required'),
            'videocompletion' => new external_value(PARAM_INT, 'Video completion percentage'),
            'hasimage' => new external_value(PARAM_BOOL, 'Has image'),
            'imageurl' => new external_value(PARAM_URL, 'Image URL', VALUE_OPTIONAL),
            'hascta' => new external_value(PARAM_BOOL, 'Has CTA'),
            'ctabutton' => new external_value(PARAM_TEXT, 'CTA button text', VALUE_OPTIONAL),
            'ctaurl' => new external_value(PARAM_URL, 'CTA URL', VALUE_OPTIONAL),
            'ctanewtab' => new external_value(PARAM_BOOL, 'CTA opens new tab'),
            'sortorder' => new external_value(PARAM_INT, 'Sort order'),
            'stepnumber' => new external_value(PARAM_INT, 'Step number'),
            'isfirst' => new external_value(PARAM_BOOL, 'Is first step'),
            'islast' => new external_value(PARAM_BOOL, 'Is last step'),
        ]);
    }

    // =========================================================================
    // n8n / External API Functions (Token-based authentication)
    // =========================================================================

    /**
     * Parameters for get_user_status.
     *
     * @return external_function_parameters
     */
    public static function get_user_status_parameters(): external_function_parameters {
        return new external_function_parameters([
            'userid' => new external_value(PARAM_INT, 'User ID (0 to use email)', VALUE_DEFAULT, 0),
            'email' => new external_value(PARAM_EMAIL, 'User email (used if userid is 0)', VALUE_DEFAULT, ''),
            'flowid' => new external_value(PARAM_INT, 'Flow ID (0 for all flows)', VALUE_DEFAULT, 0),
        ]);
    }

    /**
     * Get onboarding completion status for a user.
     * Useful for n8n workflows to check if user completed onboarding.
     *
     * @param int $userid User ID.
     * @param string $email User email (alternative to userid).
     * @param int $flowid Specific flow ID (0 for all).
     * @return array
     */
    public static function get_user_status(int $userid = 0, string $email = '', int $flowid = 0): array {
        global $DB;

        $params = self::validate_parameters(self::get_user_status_parameters(), [
            'userid' => $userid,
            'email' => $email,
            'flowid' => $flowid,
        ]);

        $context = \context_system::instance();
        self::validate_context($context);
        require_capability('local/onboarding:viewreports', $context);

        // Find user by ID or email.
        if ($params['userid'] > 0) {
            $user = $DB->get_record('user', ['id' => $params['userid']], '*', MUST_EXIST);
        } elseif (!empty($params['email'])) {
            $user = $DB->get_record('user', ['email' => $params['email']], '*', MUST_EXIST);
        } else {
            throw new \invalid_parameter_exception('Either userid or email must be provided');
        }

        // Get completion records.
        $conditions = ['userid' => $user->id];
        if ($params['flowid'] > 0) {
            $conditions['flowid'] = $params['flowid'];
        }

        $completions = $DB->get_records('local_onboarding_completion', $conditions);

        $result = [
            'userid' => $user->id,
            'email' => $user->email,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'completions' => [],
        ];

        foreach ($completions as $c) {
            $flow = $DB->get_record('local_onboarding_flows', ['id' => $c->flowid]);
            $result['completions'][] = [
                'flowid' => (int) $c->flowid,
                'flowname' => $flow ? $flow->name : 'Unknown',
                'status' => $c->status,
                'currentstep' => (int) $c->currentstep,
                'timestarted' => (int) $c->timestarted,
                'timecompleted' => (int) ($c->timecompleted ?? 0),
                'iscompleted' => $c->status === 'completed',
            ];
        }

        return $result;
    }

    /**
     * Returns for get_user_status.
     *
     * @return external_single_structure
     */
    public static function get_user_status_returns(): external_single_structure {
        return new external_single_structure([
            'userid' => new external_value(PARAM_INT, 'User ID'),
            'email' => new external_value(PARAM_EMAIL, 'User email'),
            'firstname' => new external_value(PARAM_TEXT, 'First name'),
            'lastname' => new external_value(PARAM_TEXT, 'Last name'),
            'completions' => new \core_external\external_multiple_structure(
                new external_single_structure([
                    'flowid' => new external_value(PARAM_INT, 'Flow ID'),
                    'flowname' => new external_value(PARAM_TEXT, 'Flow name'),
                    'status' => new external_value(PARAM_ALPHA, 'Status: pending, inprogress, completed'),
                    'currentstep' => new external_value(PARAM_INT, 'Current step number'),
                    'timestarted' => new external_value(PARAM_INT, 'Unix timestamp when started'),
                    'timecompleted' => new external_value(PARAM_INT, 'Unix timestamp when completed'),
                    'iscompleted' => new external_value(PARAM_BOOL, 'Whether flow is completed'),
                ])
            ),
        ]);
    }

    /**
     * Parameters for reset_user_completion.
     *
     * @return external_function_parameters
     */
    public static function reset_user_completion_parameters(): external_function_parameters {
        return new external_function_parameters([
            'userid' => new external_value(PARAM_INT, 'User ID (0 to use email)', VALUE_DEFAULT, 0),
            'email' => new external_value(PARAM_EMAIL, 'User email (used if userid is 0)', VALUE_DEFAULT, ''),
            'flowid' => new external_value(PARAM_INT, 'Flow ID (0 for all flows)', VALUE_DEFAULT, 0),
        ]);
    }

    /**
     * Reset onboarding completion for a user.
     * Useful for n8n workflows to force re-onboarding.
     *
     * @param int $userid User ID.
     * @param string $email User email (alternative to userid).
     * @param int $flowid Specific flow ID (0 for all).
     * @return array
     */
    public static function reset_user_completion(int $userid = 0, string $email = '', int $flowid = 0): array {
        global $DB;

        $params = self::validate_parameters(self::reset_user_completion_parameters(), [
            'userid' => $userid,
            'email' => $email,
            'flowid' => $flowid,
        ]);

        $context = \context_system::instance();
        self::validate_context($context);
        require_capability('local/onboarding:manageflows', $context);

        // Find user by ID or email.
        if ($params['userid'] > 0) {
            $user = $DB->get_record('user', ['id' => $params['userid']], '*', MUST_EXIST);
        } elseif (!empty($params['email'])) {
            $user = $DB->get_record('user', ['email' => $params['email']], '*', MUST_EXIST);
        } else {
            throw new \invalid_parameter_exception('Either userid or email must be provided');
        }

        // Delete completion records.
        $conditions = ['userid' => $user->id];
        if ($params['flowid'] > 0) {
            $conditions['flowid'] = $params['flowid'];
        }

        $deleted = $DB->count_records('local_onboarding_completion', $conditions);
        $DB->delete_records('local_onboarding_completion', $conditions);

        return [
            'success' => true,
            'userid' => $user->id,
            'email' => $user->email,
            'recordsdeleted' => $deleted,
            'message' => "Reset $deleted completion record(s) for user {$user->email}",
        ];
    }

    /**
     * Returns for reset_user_completion.
     *
     * @return external_single_structure
     */
    public static function reset_user_completion_returns(): external_single_structure {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Whether reset was successful'),
            'userid' => new external_value(PARAM_INT, 'User ID'),
            'email' => new external_value(PARAM_EMAIL, 'User email'),
            'recordsdeleted' => new external_value(PARAM_INT, 'Number of records deleted'),
            'message' => new external_value(PARAM_TEXT, 'Result message'),
        ]);
    }

    /**
     * Parameters for get_completion_report.
     *
     * @return external_function_parameters
     */
    public static function get_completion_report_parameters(): external_function_parameters {
        return new external_function_parameters([
            'flowid' => new external_value(PARAM_INT, 'Flow ID (0 for all)', VALUE_DEFAULT, 0),
            'status' => new external_value(PARAM_ALPHA, 'Filter by status: pending, inprogress, completed, or empty for all', VALUE_DEFAULT, ''),
            'limit' => new external_value(PARAM_INT, 'Maximum records to return', VALUE_DEFAULT, 100),
            'offset' => new external_value(PARAM_INT, 'Offset for pagination', VALUE_DEFAULT, 0),
        ]);
    }

    /**
     * Get completion report data for external systems.
     * Useful for n8n workflows to sync completion data.
     *
     * @param int $flowid Flow ID filter.
     * @param string $status Status filter.
     * @param int $limit Max records.
     * @param int $offset Pagination offset.
     * @return array
     */
    public static function get_completion_report(int $flowid = 0, string $status = '', int $limit = 100, int $offset = 0): array {
        global $DB;

        $params = self::validate_parameters(self::get_completion_report_parameters(), [
            'flowid' => $flowid,
            'status' => $status,
            'limit' => $limit,
            'offset' => $offset,
        ]);

        $context = \context_system::instance();
        self::validate_context($context);
        require_capability('local/onboarding:viewreports', $context);

        // Build query.
        $where = [];
        $sqlparams = [];

        if ($params['flowid'] > 0) {
            $where[] = 'c.flowid = :flowid';
            $sqlparams['flowid'] = $params['flowid'];
        }

        if (!empty($params['status'])) {
            $where[] = 'c.status = :status';
            $sqlparams['status'] = $params['status'];
        }

        $whereclause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        $sql = "SELECT c.*, u.email, u.firstname, u.lastname, f.name as flowname
                FROM {local_onboarding_completion} c
                JOIN {user} u ON u.id = c.userid
                JOIN {local_onboarding_flows} f ON f.id = c.flowid
                $whereclause
                ORDER BY c.timemodified DESC";

        $totalcount = $DB->count_records_sql(
            "SELECT COUNT(*) FROM {local_onboarding_completion} c $whereclause",
            $sqlparams
        );

        $records = $DB->get_records_sql($sql, $sqlparams, $params['offset'], $params['limit']);

        $completions = [];
        foreach ($records as $r) {
            $completions[] = [
                'userid' => (int) $r->userid,
                'email' => $r->email,
                'firstname' => $r->firstname,
                'lastname' => $r->lastname,
                'flowid' => (int) $r->flowid,
                'flowname' => $r->flowname,
                'status' => $r->status,
                'currentstep' => (int) $r->currentstep,
                'timestarted' => (int) $r->timestarted,
                'timecompleted' => (int) ($r->timecompleted ?? 0),
                'timemodified' => (int) $r->timemodified,
            ];
        }

        return [
            'totalcount' => $totalcount,
            'returnedcount' => count($completions),
            'completions' => $completions,
        ];
    }

    /**
     * Returns for get_completion_report.
     *
     * @return external_single_structure
     */
    public static function get_completion_report_returns(): external_single_structure {
        return new external_single_structure([
            'totalcount' => new external_value(PARAM_INT, 'Total matching records'),
            'returnedcount' => new external_value(PARAM_INT, 'Records in this response'),
            'completions' => new \core_external\external_multiple_structure(
                new external_single_structure([
                    'userid' => new external_value(PARAM_INT, 'User ID'),
                    'email' => new external_value(PARAM_EMAIL, 'User email'),
                    'firstname' => new external_value(PARAM_TEXT, 'First name'),
                    'lastname' => new external_value(PARAM_TEXT, 'Last name'),
                    'flowid' => new external_value(PARAM_INT, 'Flow ID'),
                    'flowname' => new external_value(PARAM_TEXT, 'Flow name'),
                    'status' => new external_value(PARAM_ALPHA, 'Completion status'),
                    'currentstep' => new external_value(PARAM_INT, 'Current step'),
                    'timestarted' => new external_value(PARAM_INT, 'Start timestamp'),
                    'timecompleted' => new external_value(PARAM_INT, 'Completion timestamp'),
                    'timemodified' => new external_value(PARAM_INT, 'Last modified timestamp'),
                ])
            ),
        ]);
    }
}
