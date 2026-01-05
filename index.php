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
 * Main onboarding display page.
 *
 * @package    local_onboarding
 * @copyright  2025 BixAgency.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

use local_onboarding\flow;
use local_onboarding\step;
use local_onboarding\completion;

require_login();

$flowid = required_param('flowid', PARAM_INT);

// Get the flow.
try {
    $flow = flow::instance($flowid);
} catch (\dml_exception $e) {
    throw new \moodle_exception('errornoflow', 'local_onboarding');
}

// Check if flow is enabled.
if (!$flow->is_enabled()) {
    throw new \moodle_exception('errorflowdisabled', 'local_onboarding');
}

// Get or create completion record.
$completion = completion::get_or_create($USER->id, $flowid);

// If already completed, redirect.
if ($completion->is_completed()) {
    $returnurl = !empty($SESSION->onboarding_returnurl)
        ? $SESSION->onboarding_returnurl
        : ($flow->get_redirect_url() ?: $CFG->wwwroot . '/my/');
    unset($SESSION->onboarding_returnurl);
    redirect($returnurl);
}

// Start the onboarding if not already.
$completion->start();

// Get current step.
$currentstep = $completion->get_current_step();
if (!$currentstep) {
    throw new \moodle_exception('errornosteps', 'local_onboarding');
}

// Get all steps for progress indicator.
$steps = $flow->get_steps();
$totalsteps = count($steps);
$currentnumber = $currentstep->get_step_number();

// Determine return URL.
$returnurl = !empty($SESSION->onboarding_returnurl)
    ? $SESSION->onboarding_returnurl
    : ($flow->get_redirect_url() ?: $CFG->wwwroot . '/my/');

// Setup page.
$PAGE->set_url(new moodle_url('/local/onboarding/index.php', ['flowid' => $flowid]));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('embedded');
$PAGE->set_title($flow->get_name());
$PAGE->set_heading($flow->get_name());

// Add CSS.
$PAGE->requires->css('/local/onboarding/styles.css');

// Prepare template data.
$stepdata = $currentstep->export_for_template();

// Context for format_string.
$context = context_system::instance();

// Prepare progress steps for indicator.
$progresssteps = [];
foreach ($steps as $index => $step) {
    $stepnum = $index + 1;
    $progresssteps[] = [
        'stepnumber' => $stepnum,
        'title' => format_string($step->get_title(), true, ['context' => $context]),
        'completed' => $stepnum < $currentnumber,
        'current' => $stepnum === $currentnumber,
    ];
}

$templatedata = [
    'flowid' => $flowid,
    'flowname' => format_string($flow->get_name(), true, ['context' => $context]),
    'step' => $stepdata,
    'currentstep' => $currentnumber,
    'totalsteps' => $totalsteps,
    'progresspercent' => round((($currentnumber - 1) / $totalsteps) * 100),
    'progresssteps' => $progresssteps,
    'isfirst' => $currentstep->is_first_step(),
    'islast' => $currentstep->is_last_step(),
    'returnurl' => $returnurl,
    'completionid' => $completion->get_id(),
    'sesskey' => sesskey(),
];

// Handle form submission (non-JS fallback).
if ($_SERVER['REQUEST_METHOD'] === 'POST' && confirm_sesskey()) {
    $action = required_param('action', PARAM_ALPHA);

    if ($action === 'next') {
        $hasmore = $completion->advance();

        if (!$hasmore) {
            // Completed - redirect to destination.
            $redirecturl = $flow->get_redirect_url() ?: $returnurl;
            unset($SESSION->onboarding_returnurl);
            redirect($redirecturl, get_string('completed', 'local_onboarding'), null, \core\output\notification::NOTIFY_SUCCESS);
        }

        // Redirect to refresh with new step.
        redirect(new moodle_url('/local/onboarding/index.php', ['flowid' => $flowid]));
    }
}

// Output.
echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_onboarding/onboarding', $templatedata);
echo $OUTPUT->footer();
