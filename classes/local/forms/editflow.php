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

namespace local_onboarding\local\forms;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Form for editing an onboarding flow.
 *
 * @package    local_onboarding
 * @copyright  2025 BixAgency.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class editflow extends \moodleform {

    /**
     * Form definition.
     */
    protected function definition() {
        $mform = $this->_form;

        // Flow name.
        $mform->addElement('text', 'name', get_string('flowname', 'local_onboarding'), ['size' => 50]);
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('name', 'flowname', 'local_onboarding');

        // Description.
        $mform->addElement('textarea', 'description', get_string('flowdescription', 'local_onboarding'), ['rows' => 3, 'cols' => 50]);
        $mform->setType('description', PARAM_TEXT);

        // Enabled.
        $mform->addElement('advcheckbox', 'enabled', get_string('flowenabled', 'local_onboarding'));
        $mform->setDefault('enabled', 0);

        // Mandatory.
        $mform->addElement('advcheckbox', 'mandatory', get_string('flowmandatory', 'local_onboarding'));
        $mform->setDefault('mandatory', 1);
        $mform->addHelpButton('mandatory', 'flowmandatory', 'local_onboarding');

        // Redirect URL.
        $mform->addElement('text', 'redirecturl', get_string('redirecturl', 'local_onboarding'), ['size' => 80]);
        $mform->setType('redirecturl', PARAM_URL);
        $mform->addHelpButton('redirecturl', 'redirecturl', 'local_onboarding');

        // Target roles.
        $roles = role_fix_names(get_all_roles(), \context_system::instance(), ROLENAME_ORIGINAL);
        $roleoptions = [];
        foreach ($roles as $role) {
            $roleoptions[$role->id] = $role->localname;
        }
        $mform->addElement('select', 'targetroles', get_string('targetroles', 'local_onboarding'), $roleoptions);
        $mform->getElement('targetroles')->setMultiple(true);
        $mform->addHelpButton('targetroles', 'targetroles', 'local_onboarding');

        // Buttons.
        $this->add_action_buttons();
    }

    /**
     * Validation.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        if (empty(trim($data['name']))) {
            $errors['name'] = get_string('required');
        }

        return $errors;
    }
}
