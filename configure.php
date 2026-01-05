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
 * Admin configuration page for managing onboarding flows.
 *
 * @package    local_onboarding
 * @copyright  2025 BixAgency.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

use local_onboarding\flow;
use local_onboarding\step;

// Parameters.
$action = optional_param('action', 'list', PARAM_ALPHA);
$flowid = optional_param('flowid', 0, PARAM_INT);
$stepid = optional_param('stepid', 0, PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);

// Setup.
admin_externalpage_setup('local_onboarding_manageflows');
$context = context_system::instance();
require_capability('local/onboarding:manageflows', $context);

$PAGE->set_url(new moodle_url('/local/onboarding/configure.php'));
$PAGE->set_context($context);
$PAGE->set_title(get_string('manageflows', 'local_onboarding'));
$PAGE->set_heading(get_string('manageflows', 'local_onboarding'));

// Handle actions.
switch ($action) {
    case 'newflow':
        // Show form to create new flow.
        $flowform = new \local_onboarding\local\forms\editflow(
            new moodle_url('/local/onboarding/configure.php', ['action' => 'saveflow'])
        );

        if ($flowform->is_cancelled()) {
            redirect(new moodle_url('/local/onboarding/configure.php'));
        }

        if ($data = $flowform->get_data()) {
            $flow = flow::create();
            $flow->set_name($data->name);
            $flow->set_description($data->description ?? '');
            $flow->set_enabled(!empty($data->enabled));
            $flow->set_mandatory(!empty($data->mandatory));
            $flow->set_redirect_url($data->redirecturl ?? null);
            $flow->set_target_roles(!empty($data->targetroles) ? $data->targetroles : null);
            $flow->save();

            redirect(
                new moodle_url('/local/onboarding/configure.php', ['action' => 'editflow', 'flowid' => $flow->get_id()]),
                get_string('changessaved'),
                null,
                \core\output\notification::NOTIFY_SUCCESS
            );
        }

        echo $OUTPUT->header();
        echo $OUTPUT->heading(get_string('addnewflow', 'local_onboarding'));
        $flowform->display();
        echo $OUTPUT->footer();
        exit;

    case 'editflow':
        $flow = flow::instance($flowid);

        $flowform = new \local_onboarding\local\forms\editflow(
            new moodle_url('/local/onboarding/configure.php', ['action' => 'saveflow', 'flowid' => $flowid]),
            ['flow' => $flow]
        );

        // Set existing data.
        $flowform->set_data([
            'name' => $flow->get_name(),
            'description' => $flow->get_description(),
            'enabled' => $flow->is_enabled(),
            'mandatory' => $flow->is_mandatory(),
            'redirecturl' => $flow->get_redirect_url(),
            'targetroles' => $flow->get_target_roles(),
        ]);

        if ($flowform->is_cancelled()) {
            redirect(new moodle_url('/local/onboarding/configure.php'));
        }

        if ($data = $flowform->get_data()) {
            $flow->set_name($data->name);
            $flow->set_description($data->description ?? '');
            $flow->set_enabled(!empty($data->enabled));
            $flow->set_mandatory(!empty($data->mandatory));
            $flow->set_redirect_url($data->redirecturl ?? null);
            $flow->set_target_roles(!empty($data->targetroles) ? $data->targetroles : null);
            $flow->save();

            redirect(
                new moodle_url('/local/onboarding/configure.php', ['action' => 'editflow', 'flowid' => $flowid]),
                get_string('changessaved'),
                null,
                \core\output\notification::NOTIFY_SUCCESS
            );
        }

        echo $OUTPUT->header();
        echo $OUTPUT->heading(get_string('editflow', 'local_onboarding') . ': ' . $flow->get_name());

        // Flow form.
        $flowform->display();

        // Steps section.
        echo $OUTPUT->heading(get_string('managesteps', 'local_onboarding'), 3);

        $steps = $flow->get_steps();
        if (empty($steps)) {
            echo $OUTPUT->notification(get_string('nosteps', 'local_onboarding'), 'info');
        } else {
            // Display steps table.
            $table = new html_table();
            $table->head = ['#', get_string('steptitle', 'local_onboarding'), get_string('steptype', 'local_onboarding'), get_string('actions')];
            $table->attributes['class'] = 'generaltable';

            foreach ($steps as $step) {
                $actions = [];
                $actions[] = html_writer::link(
                    new moodle_url('/local/onboarding/configure.php', ['action' => 'editstep', 'flowid' => $flowid, 'stepid' => $step->get_id()]),
                    get_string('edit'),
                    ['class' => 'btn btn-sm btn-secondary']
                );
                $actions[] = html_writer::link(
                    new moodle_url('/local/onboarding/configure.php', ['action' => 'deletestep', 'flowid' => $flowid, 'stepid' => $step->get_id()]),
                    get_string('delete'),
                    ['class' => 'btn btn-sm btn-danger']
                );

                $table->data[] = [
                    $step->get_step_number(),
                    $step->get_title(),
                    get_string('steptype_' . $step->get_steptype(), 'local_onboarding'),
                    implode(' ', $actions),
                ];
            }

            echo html_writer::table($table);
        }

        // Add step button.
        echo html_writer::link(
            new moodle_url('/local/onboarding/configure.php', ['action' => 'newstep', 'flowid' => $flowid]),
            get_string('addnewstep', 'local_onboarding'),
            ['class' => 'btn btn-primary mt-3']
        );

        echo $OUTPUT->footer();
        exit;

    case 'deleteflow':
        $flow = flow::instance($flowid);

        if ($confirm) {
            require_sesskey();
            $flow->delete();
            redirect(
                new moodle_url('/local/onboarding/configure.php'),
                get_string('deleted'),
                null,
                \core\output\notification::NOTIFY_SUCCESS
            );
        }

        echo $OUTPUT->header();
        echo $OUTPUT->confirm(
            get_string('confirmdeleteflow', 'local_onboarding'),
            new moodle_url('/local/onboarding/configure.php', ['action' => 'deleteflow', 'flowid' => $flowid, 'confirm' => 1]),
            new moodle_url('/local/onboarding/configure.php')
        );
        echo $OUTPUT->footer();
        exit;

    case 'toggleflow':
        require_sesskey();
        $flow = flow::instance($flowid);
        $flow->set_enabled(!$flow->is_enabled());
        $flow->save();
        redirect(new moodle_url('/local/onboarding/configure.php'));
        exit;

    case 'newstep':
        $stepform = new \local_onboarding\local\forms\editstep(
            new moodle_url('/local/onboarding/configure.php', ['action' => 'savestep', 'flowid' => $flowid])
        );

        if ($stepform->is_cancelled()) {
            redirect(new moodle_url('/local/onboarding/configure.php', ['action' => 'editflow', 'flowid' => $flowid]));
        }

        if ($data = $stepform->get_data()) {
            $step = step::create($flowid);
            $step->set_title($data->title);
            $step->set_content($data->content['text'], $data->content['format']);
            $step->set_steptype($data->steptype);
            $step->set_videourl($data->videourl ?? null);
            $step->set_video_required(!empty($data->videorequired));
            $step->set_video_completion($data->videocompletion ?? 80);
            $step->set_imageurl($data->imageurl ?? null);
            $step->set_ctabutton($data->ctabutton ?? null);
            $step->set_ctaurl($data->ctaurl ?? null);
            $step->set_cta_newtab(!empty($data->ctanewtab));
            $step->save();

            redirect(
                new moodle_url('/local/onboarding/configure.php', ['action' => 'editflow', 'flowid' => $flowid]),
                get_string('changessaved'),
                null,
                \core\output\notification::NOTIFY_SUCCESS
            );
        }

        echo $OUTPUT->header();
        echo $OUTPUT->heading(get_string('addnewstep', 'local_onboarding'));
        $stepform->display();
        echo $OUTPUT->footer();
        exit;

    case 'editstep':
        $step = step::instance($stepid);

        $stepform = new \local_onboarding\local\forms\editstep(
            new moodle_url('/local/onboarding/configure.php', ['action' => 'savestep', 'flowid' => $flowid, 'stepid' => $stepid]),
            ['step' => $step]
        );

        $stepform->set_data([
            'title' => $step->get_title(),
            'content' => ['text' => $step->get_content(), 'format' => FORMAT_HTML],
            'steptype' => $step->get_steptype(),
            'videourl' => $step->get_videourl(),
            'videorequired' => $step->is_video_required(),
            'videocompletion' => $step->get_video_completion(),
            'imageurl' => $step->get_imageurl(),
            'ctabutton' => $step->get_ctabutton(),
            'ctaurl' => $step->get_ctaurl(),
            'ctanewtab' => $step->cta_opens_new_tab(),
        ]);

        if ($stepform->is_cancelled()) {
            redirect(new moodle_url('/local/onboarding/configure.php', ['action' => 'editflow', 'flowid' => $flowid]));
        }

        if ($data = $stepform->get_data()) {
            $step->set_title($data->title);
            $step->set_content($data->content['text'], $data->content['format']);
            $step->set_steptype($data->steptype);
            $step->set_videourl($data->videourl ?? null);
            $step->set_video_required(!empty($data->videorequired));
            $step->set_video_completion($data->videocompletion ?? 80);
            $step->set_imageurl($data->imageurl ?? null);
            $step->set_ctabutton($data->ctabutton ?? null);
            $step->set_ctaurl($data->ctaurl ?? null);
            $step->set_cta_newtab(!empty($data->ctanewtab));
            $step->save();

            redirect(
                new moodle_url('/local/onboarding/configure.php', ['action' => 'editflow', 'flowid' => $flowid]),
                get_string('changessaved'),
                null,
                \core\output\notification::NOTIFY_SUCCESS
            );
        }

        echo $OUTPUT->header();
        echo $OUTPUT->heading(get_string('editstep', 'local_onboarding') . ': ' . $step->get_title());
        $stepform->display();
        echo $OUTPUT->footer();
        exit;

    case 'deletestep':
        $step = step::instance($stepid);

        if ($confirm) {
            require_sesskey();
            $step->delete();
            redirect(
                new moodle_url('/local/onboarding/configure.php', ['action' => 'editflow', 'flowid' => $flowid]),
                get_string('deleted'),
                null,
                \core\output\notification::NOTIFY_SUCCESS
            );
        }

        echo $OUTPUT->header();
        echo $OUTPUT->confirm(
            get_string('confirmdeletestep', 'local_onboarding'),
            new moodle_url('/local/onboarding/configure.php', ['action' => 'deletestep', 'flowid' => $flowid, 'stepid' => $stepid, 'confirm' => 1]),
            new moodle_url('/local/onboarding/configure.php', ['action' => 'editflow', 'flowid' => $flowid])
        );
        echo $OUTPUT->footer();
        exit;

    case 'list':
    default:
        // List all flows.
        echo $OUTPUT->header();
        echo $OUTPUT->heading(get_string('manageflows', 'local_onboarding'));

        $flows = flow::get_all();

        if (empty($flows)) {
            echo $OUTPUT->notification(get_string('nosteps', 'local_onboarding'), 'info');
        } else {
            $table = new html_table();
            $table->head = [
                get_string('flowname', 'local_onboarding'),
                get_string('flowenabled', 'local_onboarding'),
                get_string('actions'),
            ];
            $table->attributes['class'] = 'generaltable';

            foreach ($flows as $flow) {
                $enabledicon = $flow->is_enabled()
                    ? $OUTPUT->pix_icon('t/hide', get_string('disable'))
                    : $OUTPUT->pix_icon('t/show', get_string('enable'));

                $toggleurl = new moodle_url('/local/onboarding/configure.php', [
                    'action' => 'toggleflow',
                    'flowid' => $flow->get_id(),
                    'sesskey' => sesskey(),
                ]);

                $actions = [];
                $actions[] = html_writer::link(
                    new moodle_url('/local/onboarding/configure.php', ['action' => 'editflow', 'flowid' => $flow->get_id()]),
                    get_string('edit'),
                    ['class' => 'btn btn-sm btn-secondary']
                );
                $actions[] = html_writer::link(
                    $toggleurl,
                    $flow->is_enabled() ? get_string('disable') : get_string('enable'),
                    ['class' => 'btn btn-sm btn-' . ($flow->is_enabled() ? 'warning' : 'success')]
                );
                $actions[] = html_writer::link(
                    new moodle_url('/local/onboarding/configure.php', ['action' => 'deleteflow', 'flowid' => $flow->get_id()]),
                    get_string('delete'),
                    ['class' => 'btn btn-sm btn-danger']
                );

                $table->data[] = [
                    $flow->get_name() . ' (' . $flow->count_steps() . ' steps)',
                    $flow->is_enabled() ? get_string('yes') : get_string('no'),
                    implode(' ', $actions),
                ];
            }

            echo html_writer::table($table);
        }

        // Add flow button.
        echo html_writer::link(
            new moodle_url('/local/onboarding/configure.php', ['action' => 'newflow']),
            get_string('addnewflow', 'local_onboarding'),
            ['class' => 'btn btn-primary mt-3']
        );

        echo $OUTPUT->footer();
        break;
}
