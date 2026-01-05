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

use local_onboarding\step;

/**
 * Form for editing an onboarding step.
 *
 * @package    local_onboarding
 * @copyright  2025 BixAgency.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class editstep extends \moodleform {

    /**
     * Form definition.
     */
    protected function definition() {
        $mform = $this->_form;

        // Step title.
        $mform->addElement('text', 'title', get_string('steptitle', 'local_onboarding'), ['size' => 50]);
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', get_string('required'), 'required', null, 'client');

        // Step type.
        $steptypes = [
            step::TYPE_CONTENT => get_string('steptype_content', 'local_onboarding'),
            step::TYPE_VIDEO => get_string('steptype_video', 'local_onboarding'),
            step::TYPE_IMAGE => get_string('steptype_image', 'local_onboarding'),
            step::TYPE_MIXED => get_string('steptype_mixed', 'local_onboarding'),
        ];
        $mform->addElement('select', 'steptype', get_string('steptype', 'local_onboarding'), $steptypes);
        $mform->setDefault('steptype', step::TYPE_CONTENT);

        // Content (rich text editor).
        $mform->addElement('editor', 'content', get_string('stepcontent', 'local_onboarding'), null, [
            'maxfiles' => EDITOR_UNLIMITED_FILES,
            'noclean' => true,
            'subdirs' => true,
        ]);
        $mform->setType('content', PARAM_RAW);

        // Video section header.
        $mform->addElement('header', 'videoheader', get_string('steptype_video', 'local_onboarding'));

        // Video URL.
        $mform->addElement('text', 'videourl', get_string('videourl', 'local_onboarding'), ['size' => 80]);
        $mform->setType('videourl', PARAM_URL);
        $mform->addHelpButton('videourl', 'videourl', 'local_onboarding');
        $mform->hideIf('videourl', 'steptype', 'eq', step::TYPE_CONTENT);
        $mform->hideIf('videourl', 'steptype', 'eq', step::TYPE_IMAGE);

        // Video required.
        $mform->addElement('advcheckbox', 'videorequired', get_string('videorequired', 'local_onboarding'));
        $mform->addHelpButton('videorequired', 'videorequired', 'local_onboarding');
        $mform->hideIf('videorequired', 'steptype', 'eq', step::TYPE_CONTENT);
        $mform->hideIf('videorequired', 'steptype', 'eq', step::TYPE_IMAGE);

        // Video completion percentage.
        $mform->addElement('text', 'videocompletion', get_string('videocompletion', 'local_onboarding'), ['size' => 5]);
        $mform->setType('videocompletion', PARAM_INT);
        $mform->setDefault('videocompletion', 80);
        $mform->addHelpButton('videocompletion', 'videocompletion', 'local_onboarding');
        $mform->hideIf('videocompletion', 'steptype', 'eq', step::TYPE_CONTENT);
        $mform->hideIf('videocompletion', 'steptype', 'eq', step::TYPE_IMAGE);
        $mform->hideIf('videocompletion', 'videorequired', 'notchecked');

        // Image section header.
        $mform->addElement('header', 'imageheader', get_string('steptype_image', 'local_onboarding'));

        // Image URL.
        $mform->addElement('text', 'imageurl', get_string('imageurl', 'local_onboarding'), ['size' => 80]);
        $mform->setType('imageurl', PARAM_URL);
        $mform->hideIf('imageurl', 'steptype', 'eq', step::TYPE_CONTENT);
        $mform->hideIf('imageurl', 'steptype', 'eq', step::TYPE_VIDEO);

        // CTA section header.
        $mform->addElement('header', 'ctaheader', 'Call to Action Button');

        // CTA button text.
        $mform->addElement('text', 'ctabutton', get_string('ctabutton', 'local_onboarding'), ['size' => 30]);
        $mform->setType('ctabutton', PARAM_TEXT);
        $mform->addHelpButton('ctabutton', 'ctabutton', 'local_onboarding');

        // CTA URL.
        $mform->addElement('text', 'ctaurl', get_string('ctaurl', 'local_onboarding'), ['size' => 80]);
        $mform->setType('ctaurl', PARAM_URL);
        $mform->addHelpButton('ctaurl', 'ctaurl', 'local_onboarding');

        // CTA new tab.
        $mform->addElement('advcheckbox', 'ctanewtab', get_string('ctanewtab', 'local_onboarding'));

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

        if (empty(trim($data['title']))) {
            $errors['title'] = get_string('required');
        }

        // Validate video URL if step type requires it.
        if (in_array($data['steptype'], [step::TYPE_VIDEO, step::TYPE_MIXED])) {
            if (empty(trim($data['videourl']))) {
                $errors['videourl'] = get_string('required');
            }
        }

        // Validate video completion percentage.
        if (!empty($data['videorequired']) && isset($data['videocompletion'])) {
            $percent = (int) $data['videocompletion'];
            if ($percent < 0 || $percent > 100) {
                $errors['videocompletion'] = 'Must be between 0 and 100';
            }
        }

        return $errors;
    }
}
