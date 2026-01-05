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
 * Onboarding completion report.
 *
 * @package    local_onboarding
 * @copyright  2025 BixAgency.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

use local_onboarding\flow;
use local_onboarding\completion;

$flowid = optional_param('flowid', 0, PARAM_INT);
$page = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', 50, PARAM_INT);

admin_externalpage_setup('local_onboarding_report');
$context = context_system::instance();
require_capability('local/onboarding:viewreports', $context);

$PAGE->set_url(new moodle_url('/local/onboarding/report.php', ['flowid' => $flowid]));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('completionreport', 'local_onboarding'));

// Get all flows for filter.
$flows = flow::get_all();

if (empty($flows)) {
    echo $OUTPUT->notification(get_string('errornoflow', 'local_onboarding'), 'info');
    echo $OUTPUT->footer();
    exit;
}

// Flow selector.
$flowoptions = [0 => get_string('all')];
foreach ($flows as $flow) {
    $flowoptions[$flow->get_id()] = $flow->get_name();
}

$select = new single_select(
    new moodle_url('/local/onboarding/report.php'),
    'flowid',
    $flowoptions,
    $flowid
);
$select->set_label(get_string('flowname', 'local_onboarding'));
echo $OUTPUT->render($select);

// Get statistics.
echo html_writer::start_div('card mb-4 mt-3');
echo html_writer::start_div('card-body');
echo html_writer::tag('h5', get_string('currentstats', 'local_onboarding'), ['class' => 'card-title']);

if ($flowid > 0) {
    $stats = completion::get_stats($flowid);
    $flow = flow::instance($flowid);
    echo html_writer::tag('p', '<strong>' . $flow->get_name() . '</strong>');
} else {
    // Aggregate stats for all flows.
    $stats = new stdClass();
    $stats->total = $DB->count_records('local_onboarding_completion');
    $stats->completed = $DB->count_records('local_onboarding_completion', ['status' => 'completed']);
    $stats->inprogress = $DB->count_records('local_onboarding_completion', ['status' => 'inprogress']);
    $stats->pending = $DB->count_records('local_onboarding_completion', ['status' => 'pending']);
    $stats->completionrate = $stats->total > 0 ? round(($stats->completed / $stats->total) * 100, 1) : 0;
}

echo html_writer::start_div('row');
echo html_writer::start_div('col-md-3');
echo html_writer::tag('div', '<h3>' . $stats->total . '</h3><p>' . get_string('totalusers', 'local_onboarding') . '</p>', ['class' => 'text-center']);
echo html_writer::end_div();
echo html_writer::start_div('col-md-3');
echo html_writer::tag('div', '<h3 class="text-success">' . $stats->completed . '</h3><p>' . get_string('completedusers', 'local_onboarding') . '</p>', ['class' => 'text-center']);
echo html_writer::end_div();
echo html_writer::start_div('col-md-3');
echo html_writer::tag('div', '<h3 class="text-warning">' . $stats->inprogress . '</h3><p>' . get_string('inprogressusers', 'local_onboarding') . '</p>', ['class' => 'text-center']);
echo html_writer::end_div();
echo html_writer::start_div('col-md-3');
echo html_writer::tag('div', '<h3 class="text-primary">' . $stats->completionrate . '%</h3><p>' . get_string('completionrate', 'local_onboarding') . '</p>', ['class' => 'text-center']);
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::end_div();
echo html_writer::end_div();

// User completion table.
// Include all user name fields required by fullname() function.
$userfields = \core_user\fields::for_name()->get_sql('u', false, '', '', false)->selects;
$sql = "SELECT c.*, {$userfields}, u.email, f.name as flowname
        FROM {local_onboarding_completion} c
        JOIN {user} u ON u.id = c.userid
        JOIN {local_onboarding_flows} f ON f.id = c.flowid";
$params = [];

if ($flowid > 0) {
    $sql .= " WHERE c.flowid = :flowid";
    $params['flowid'] = $flowid;
}

$sql .= " ORDER BY c.timemodified DESC";

$totalcount = $DB->count_records_sql("SELECT COUNT(*) FROM ($sql) t", $params);
$completions = $DB->get_records_sql($sql, $params, $page * $perpage, $perpage);

if (empty($completions)) {
    echo $OUTPUT->notification(get_string('nocompletiondata', 'local_onboarding'), 'info');
} else {
    $table = new html_table();
    $table->head = [
        get_string('user'),
        get_string('email'),
        get_string('flowname', 'local_onboarding'),
        get_string('completionstatus', 'local_onboarding'),
        get_string('completiondate', 'local_onboarding'),
        get_string('actions'),
    ];
    $table->attributes['class'] = 'generaltable';

    foreach ($completions as $c) {
        $statusbadge = '';
        switch ($c->status) {
            case 'completed':
                $statusbadge = html_writer::tag('span', get_string('completed', 'local_onboarding'), ['class' => 'badge badge-success']);
                break;
            case 'inprogress':
                $statusbadge = html_writer::tag('span', get_string('inprogress', 'local_onboarding'), ['class' => 'badge badge-warning']);
                break;
            default:
                $statusbadge = html_writer::tag('span', get_string('pending', 'local_onboarding'), ['class' => 'badge badge-secondary']);
        }

        $completeddate = $c->timecompleted ? userdate($c->timecompleted) : '-';

        $reseturl = new moodle_url('/local/onboarding/reset.php', [
            'flowid' => $c->flowid,
            'userid' => $c->userid,
        ]);

        $table->data[] = [
            fullname($c),
            $c->email,
            $c->flowname,
            $statusbadge,
            $completeddate,
            html_writer::link(
                new moodle_url('/local/onboarding/reset.php', ['flowid' => $c->flowid]),
                get_string('resetcompletion', 'local_onboarding'),
                ['class' => 'btn btn-sm btn-outline-secondary']
            ),
        ];
    }

    echo html_writer::table($table);

    // Pagination.
    echo $OUTPUT->paging_bar($totalcount, $page, $perpage, new moodle_url('/local/onboarding/report.php', ['flowid' => $flowid]));
}

echo $OUTPUT->footer();
