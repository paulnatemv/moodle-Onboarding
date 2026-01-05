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
 * Settings for local_onboarding.
 *
 * @package    local_onboarding
 * @copyright  2025 BixAgency.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    // Create category.
    $ADMIN->add('localplugins', new admin_category(
        'local_onboarding_category',
        get_string('pluginname', 'local_onboarding')
    ));

    // General settings page.
    $settings = new admin_settingpage(
        'local_onboarding_settings',
        get_string('settings', 'local_onboarding')
    );

    // Enable/disable plugin.
    $settings->add(new admin_setting_configcheckbox(
        'local_onboarding/enabled',
        get_string('enabled', 'local_onboarding'),
        get_string('enabled_desc', 'local_onboarding'),
        1
    ));

    // Default video completion percentage.
    $settings->add(new admin_setting_configtext(
        'local_onboarding/defaultvideocompletion',
        get_string('defaultvideocompletion', 'local_onboarding'),
        get_string('defaultvideocompletion_desc', 'local_onboarding'),
        80,
        PARAM_INT
    ));

    // Show to site admins.
    $settings->add(new admin_setting_configcheckbox(
        'local_onboarding/showadmins',
        get_string('showadmins', 'local_onboarding'),
        get_string('showadmins_desc', 'local_onboarding'),
        0
    ));

    $ADMIN->add('local_onboarding_category', $settings);

    // Reset completions page.
    $ADMIN->add(
        'local_onboarding_category',
        new admin_externalpage(
            'local_onboarding_reset',
            get_string('resetallcompletions', 'local_onboarding'),
            new moodle_url('/local/onboarding/reset.php'),
            'local/onboarding:manageflows'
        )
    );

    // Manage flows page.
    $ADMIN->add(
        'local_onboarding_category',
        new admin_externalpage(
            'local_onboarding_manageflows',
            get_string('manageflows', 'local_onboarding'),
            new moodle_url('/local/onboarding/configure.php'),
            'local/onboarding:manageflows'
        )
    );

    // Completion report page.
    $ADMIN->add(
        'local_onboarding_category',
        new admin_externalpage(
            'local_onboarding_report',
            get_string('completionreport', 'local_onboarding'),
            new moodle_url('/local/onboarding/report.php'),
            'local/onboarding:viewreports'
        )
    );
}
