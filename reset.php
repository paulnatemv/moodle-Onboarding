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
 * Reset all onboarding completions.
 *
 * @package    local_onboarding
 * @copyright  2025 BixAgency.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

$confirm = optional_param('confirm', 0, PARAM_BOOL);
$flowid = optional_param('flowid', 0, PARAM_INT);

admin_externalpage_setup('local_onboarding_reset');
$context = context_system::instance();
require_capability('local/onboarding:manageflows', $context);

$PAGE->set_url(new moodle_url('/local/onboarding/reset.php'));

if ($confirm && confirm_sesskey()) {
    if ($flowid > 0) {
        // Reset specific flow.
        $DB->delete_records('local_onboarding_completion', ['flowid' => $flowid]);
        $message = get_string('resetflowcomplete', 'local_onboarding');
    } else {
        // Reset all flows.
        $DB->delete_records('local_onboarding_completion');
        $message = get_string('resetallcomplete', 'local_onboarding');
    }

    redirect(
        new moodle_url('/local/onboarding/configure.php'),
        $message,
        null,
        \core\output\notification::NOTIFY_SUCCESS
    );
}

// Get flows for the form.
$flows = $DB->get_records('local_onboarding_flows', null, 'name ASC');

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('resetallcompletions', 'local_onboarding'));

// Warning message.
echo $OUTPUT->notification(get_string('resetwarning', 'local_onboarding'), 'warning');

// Stats.
$totalcompletions = $DB->count_records('local_onboarding_completion');
$completedcount = $DB->count_records('local_onboarding_completion', ['status' => 'completed']);
$inprogresscount = $DB->count_records('local_onboarding_completion', ['status' => 'inprogress']);

echo html_writer::start_div('card mb-4');
echo html_writer::start_div('card-body');
echo html_writer::tag('h5', get_string('currentstats', 'local_onboarding'), ['class' => 'card-title']);
echo html_writer::tag('p', get_string('totalcompletionrecords', 'local_onboarding', $totalcompletions));
echo html_writer::tag('p', get_string('completedcount', 'local_onboarding', $completedcount));
echo html_writer::tag('p', get_string('inprogresscount', 'local_onboarding', $inprogresscount));
echo html_writer::end_div();
echo html_writer::end_div();

// Reset options.
echo html_writer::start_div('card mb-4');
echo html_writer::start_div('card-body');
echo html_writer::tag('h5', get_string('resetoptions', 'local_onboarding'), ['class' => 'card-title']);

// Reset all button.
echo html_writer::start_tag('form', ['method' => 'post', 'class' => 'mb-3']);
echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'confirm', 'value' => 1]);
echo html_writer::tag('p', get_string('resetalldesc', 'local_onboarding'));
echo html_writer::tag('button', get_string('resetallbutton', 'local_onboarding'), [
    'type' => 'submit',
    'class' => 'btn btn-danger',
    'onclick' => 'return confirm("' . get_string('resetconfirm', 'local_onboarding') . '");'
]);
echo html_writer::end_tag('form');

// Reset specific flow.
if (!empty($flows)) {
    echo html_writer::tag('hr', '');
    echo html_writer::tag('p', get_string('resetspecificdesc', 'local_onboarding'));

    foreach ($flows as $flow) {
        $count = $DB->count_records('local_onboarding_completion', ['flowid' => $flow->id]);
        echo html_writer::start_tag('form', ['method' => 'post', 'class' => 'mb-2', 'style' => 'display:inline-block;']);
        echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
        echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'confirm', 'value' => 1]);
        echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'flowid', 'value' => $flow->id]);
        echo html_writer::tag('button', get_string('resetflow', 'local_onboarding', $flow->name) . " ($count users)", [
            'type' => 'submit',
            'class' => 'btn btn-warning btn-sm',
            'onclick' => 'return confirm("' . get_string('resetconfirm', 'local_onboarding') . '");'
        ]);
        echo html_writer::end_tag('form');
        echo ' ';
    }
}

echo html_writer::end_div();
echo html_writer::end_div();

// Back link.
echo html_writer::link(
    new moodle_url('/local/onboarding/configure.php'),
    get_string('back'),
    ['class' => 'btn btn-secondary']
);

echo $OUTPUT->footer();
