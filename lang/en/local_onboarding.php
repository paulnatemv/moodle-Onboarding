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
 * Language strings for local_onboarding.
 *
 * @package    local_onboarding
 * @copyright  2025 BixAgency.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Plugin name and general.
$string['pluginname'] = 'Onboarding';
$string['modulename'] = 'Onboarding';

// Settings.
$string['settings'] = 'Onboarding Settings';
$string['enabled'] = 'Enable onboarding';
$string['enabled_desc'] = 'When enabled, users will see the onboarding flow after login (if they haven\'t completed it).';
$string['defaultvideocompletion'] = 'Default video completion percentage';
$string['defaultvideocompletion_desc'] = 'The default percentage of video that must be watched before users can proceed (0-100).';
$string['showadmins'] = 'Show to site administrators';
$string['showadmins_desc'] = 'If enabled, site administrators will also see the onboarding flow (normally they are skipped).';

// Capabilities.
$string['onboarding:manageflows'] = 'Manage onboarding flows';
$string['onboarding:viewreports'] = 'View onboarding reports';
$string['onboarding:bypass'] = 'Bypass onboarding';

// Admin pages.
$string['manageflows'] = 'Manage Onboarding Flows';
$string['completionreport'] = 'Completion Report';
$string['addnewflow'] = 'Add new flow';
$string['editflow'] = 'Edit flow';
$string['deleteflow'] = 'Delete flow';
$string['confirmdeleteflow'] = 'Are you sure you want to delete this onboarding flow? This will also delete all steps within it.';

// Flow form.
$string['flowname'] = 'Flow name';
$string['flowname_help'] = 'Enter a descriptive name for this onboarding flow (e.g., "New Student Welcome").';
$string['flowdescription'] = 'Description';
$string['flowenabled'] = 'Enabled';
$string['flowmandatory'] = 'Mandatory';
$string['flowmandatory_help'] = 'If enabled, users must complete this onboarding flow before accessing the site.';
$string['targetroles'] = 'Target roles';
$string['targetroles_help'] = 'Select which user roles should see this onboarding flow.

**Leave empty (no selection) = ALL USERS** will see this onboarding.

If you select specific roles (e.g., Student), only users with those roles will see the onboarding.

Common setup:
- For all new users: Leave empty
- For students only: Select "Student"
- For teachers only: Select "Teacher"';
$string['redirecturl'] = 'Redirect URL after completion';
$string['redirecturl_help'] = 'URL to redirect users after completing this flow. Leave empty for default dashboard.';

// Step management.
$string['managesteps'] = 'Manage Steps';
$string['addnewstep'] = 'Add new step';
$string['editstep'] = 'Edit step';
$string['deletestep'] = 'Delete step';
$string['confirmdeletestep'] = 'Are you sure you want to delete this step?';
$string['nosteps'] = 'No steps have been added to this flow yet.';
$string['reordersteps'] = 'Drag to reorder steps';

// Step form.
$string['steptitle'] = 'Step title';
$string['stepcontent'] = 'Content';
$string['steptype'] = 'Step type';
$string['steptype_content'] = 'Content only';
$string['steptype_video'] = 'Video';
$string['steptype_image'] = 'Image';
$string['steptype_mixed'] = 'Mixed (video + content)';
$string['videourl'] = 'Video URL';
$string['videourl_help'] = 'Enter the URL of a YouTube, Vimeo, or self-hosted video.';
$string['videorequired'] = 'Video must be watched';
$string['videorequired_help'] = 'If enabled, users must watch the required percentage of the video before they can proceed.';
$string['videocompletion'] = 'Required watch percentage';
$string['videocompletion_help'] = 'The percentage of the video that must be watched (0-100).';
$string['imageurl'] = 'Image URL';
$string['ctabutton'] = 'Button text';
$string['ctabutton_help'] = 'Text to display on the call-to-action button (e.g., "Subscribe Now").';
$string['ctaurl'] = 'Button URL';
$string['ctaurl_help'] = 'URL the button should link to.';
$string['ctanewtab'] = 'Open in new tab';

// Onboarding UI.
$string['welcome'] = 'Welcome';
$string['next'] = 'Continue';
$string['previous'] = 'Previous';
$string['finish'] = 'Get Started';
$string['skip'] = 'Skip';
$string['toggletheme'] = 'Toggle light/dark mode';
$string['stepof'] = 'Step {$a->current} of {$a->total}';
$string['watchvideotocontinue'] = 'This short video will help you get the most out of your learning experience';
$string['videoprogresstext'] = 'Video progress: {$a}%';
$string['completevideofirst'] = 'Almost there! Please finish watching the video to continue.';

// Completion.
$string['completed'] = 'Completed';
$string['inprogress'] = 'In Progress';
$string['pending'] = 'Not Started';
$string['completionstatus'] = 'Status';
$string['completiondate'] = 'Completed on';
$string['resetcompletion'] = 'Reset completion';
$string['resetcompletionconfirm'] = 'Are you sure you want to reset onboarding completion for this user? They will see the onboarding flow again.';
$string['completionreset'] = 'Onboarding completion has been reset for the user.';

// Reports.
$string['totalusers'] = 'Total users';
$string['completedusers'] = 'Completed';
$string['inprogressusers'] = 'In progress';
$string['completionrate'] = 'Completion rate';
$string['averagetimetocomplete'] = 'Average time to complete';
$string['nocompletiondata'] = 'No completion data available yet.';

// Events.
$string['eventonboardingstarted'] = 'Onboarding started';
$string['eventonboardingcompleted'] = 'Onboarding completed';
$string['eventstepcompleted'] = 'Onboarding step completed';

// Errors.
$string['errornoflow'] = 'No onboarding flow found.';
$string['errornosteps'] = 'This onboarding flow has no steps configured.';
$string['errorflowdisabled'] = 'This onboarding flow is currently disabled.';
$string['errorinvalidstep'] = 'Invalid step.';
$string['errorpermission'] = 'You do not have permission to perform this action.';

// Reset completions.
$string['resetallcompletions'] = 'Reset User Completions';
$string['resetwarning'] = 'Warning: Resetting completions will force users to go through the onboarding flow again on their next login.';
$string['currentstats'] = 'Current Statistics';
$string['totalcompletionrecords'] = 'Total completion records: {$a}';
$string['completedcount'] = 'Users who completed onboarding: {$a}';
$string['inprogresscount'] = 'Users in progress: {$a}';
$string['resetoptions'] = 'Reset Options';
$string['resetalldesc'] = 'Reset ALL completion records - every user will see the onboarding again.';
$string['resetallbutton'] = 'Reset All Completions';
$string['resetspecificdesc'] = 'Or reset completions for a specific flow:';
$string['resetflow'] = 'Reset: {$a}';
$string['resetconfirm'] = 'Are you sure? This will make all affected users see the onboarding flow again.';
$string['resetallcomplete'] = 'All onboarding completion records have been reset.';
$string['resetflowcomplete'] = 'Completion records for this flow have been reset.';

// Privacy.
$string['privacy:metadata:local_onboarding_completion'] = 'Information about users\' onboarding completion status.';
$string['privacy:metadata:local_onboarding_completion:userid'] = 'The ID of the user.';
$string['privacy:metadata:local_onboarding_completion:status'] = 'The completion status of the onboarding flow.';
$string['privacy:metadata:local_onboarding_completion:timestarted'] = 'When the user started the onboarding.';
$string['privacy:metadata:local_onboarding_completion:timecompleted'] = 'When the user completed the onboarding.';
