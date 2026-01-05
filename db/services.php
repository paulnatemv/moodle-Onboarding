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

/**
 * Web services definitions for local_onboarding.
 *
 * @package    local_onboarding
 * @copyright  2025 BixAgency.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    // Internal AJAX functions (used by onboarding UI).
    'local_onboarding_update_video_time' => [
        'classname' => 'local_onboarding\external\onboarding',
        'methodname' => 'update_video_time',
        'description' => 'Update video watch time for tracking',
        'type' => 'write',
        'ajax' => true,
        'loginrequired' => true,
    ],
    'local_onboarding_complete_step' => [
        'classname' => 'local_onboarding\external\onboarding',
        'methodname' => 'complete_step',
        'description' => 'Mark current step as complete and advance',
        'type' => 'write',
        'ajax' => true,
        'loginrequired' => true,
    ],
    'local_onboarding_get_step' => [
        'classname' => 'local_onboarding\external\onboarding',
        'methodname' => 'get_step',
        'description' => 'Get step data',
        'type' => 'read',
        'ajax' => true,
        'loginrequired' => true,
    ],

    // External API functions (for n8n, webhooks, and external integrations).
    'local_onboarding_get_user_status' => [
        'classname' => 'local_onboarding\external\onboarding',
        'methodname' => 'get_user_status',
        'description' => 'Get onboarding completion status for a user (by ID or email)',
        'type' => 'read',
        'ajax' => true,
        'capabilities' => 'local/onboarding:viewreports',
        'services' => ['local_onboarding_external'],
    ],
    'local_onboarding_reset_user_completion' => [
        'classname' => 'local_onboarding\external\onboarding',
        'methodname' => 'reset_user_completion',
        'description' => 'Reset onboarding completion for a user (forces re-onboarding)',
        'type' => 'write',
        'ajax' => true,
        'capabilities' => 'local/onboarding:manageflows',
        'services' => ['local_onboarding_external'],
    ],
    'local_onboarding_get_completion_report' => [
        'classname' => 'local_onboarding\external\onboarding',
        'methodname' => 'get_completion_report',
        'description' => 'Get completion report data with filtering and pagination',
        'type' => 'read',
        'ajax' => true,
        'capabilities' => 'local/onboarding:viewreports',
        'services' => ['local_onboarding_external'],
    ],
];

// Define external service for n8n and other integrations.
$services = [
    'Onboarding External API' => [
        'functions' => [
            'local_onboarding_get_user_status',
            'local_onboarding_reset_user_completion',
            'local_onboarding_get_completion_report',
        ],
        'restrictedusers' => 0,
        'enabled' => 1,
        'shortname' => 'local_onboarding_external',
        'downloadfiles' => 0,
        'uploadfiles' => 0,
    ],
];
