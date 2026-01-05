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
 * Library functions for local_onboarding.
 *
 * @package    local_onboarding
 * @copyright  2025 BixAgency.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Called before footer on every page - used to auto-redirect from confirm page to onboarding.
 *
 * When a user confirms their email, Moodle shows a "Thanks!" page with a Continue button.
 * This function detects that page and auto-redirects to onboarding for a seamless experience.
 */
function local_onboarding_before_footer() {
    global $PAGE, $USER, $SESSION, $DB;

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
            // Wait for page to fully load, then redirect.
            setTimeout(function() {
                window.location.href = " . json_encode($onboardingurl) . ";
            }, 1500);
        });
    ");
}
