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
 * German language strings for local_onboarding.
 *
 * @package    local_onboarding
 * @copyright  2025 BixAgency.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Plugin name and general.
$string['pluginname'] = 'Einführung';
$string['modulename'] = 'Einführung';

// Settings.
$string['settings'] = 'Einführungseinstellungen';
$string['enabled'] = 'Einführung aktivieren';
$string['enabled_desc'] = 'Wenn aktiviert, sehen Benutzer den Einführungsablauf nach der Anmeldung (wenn sie ihn noch nicht abgeschlossen haben).';
$string['defaultvideocompletion'] = 'Standard-Video-Abschlussprozentsatz';
$string['defaultvideocompletion_desc'] = 'Der Standardprozentsatz des Videos, der angesehen werden muss, bevor fortgefahren werden kann (0-100).';
$string['showadmins'] = 'Administratoren anzeigen';
$string['showadmins_desc'] = 'Wenn aktiviert, sehen auch Seitenadministratoren den Einführungsablauf.';

// Capabilities.
$string['onboarding:manageflows'] = 'Einführungsabläufe verwalten';
$string['onboarding:viewreports'] = 'Einführungsberichte ansehen';
$string['onboarding:bypass'] = 'Einführung überspringen';

// Admin pages.
$string['manageflows'] = 'Einführungsabläufe verwalten';
$string['completionreport'] = 'Abschlussbericht';
$string['addnewflow'] = 'Neuen Ablauf hinzufügen';
$string['editflow'] = 'Ablauf bearbeiten';
$string['deleteflow'] = 'Ablauf löschen';
$string['confirmdeleteflow'] = 'Sind Sie sicher, dass Sie diesen Einführungsablauf löschen möchten? Alle Schritte werden ebenfalls gelöscht.';

// Flow form.
$string['flowname'] = 'Ablaufname';
$string['flowname_help'] = 'Geben Sie einen beschreibenden Namen für diesen Einführungsablauf ein (z.B. "Willkommen Neuer Student").';
$string['flowdescription'] = 'Beschreibung';
$string['flowenabled'] = 'Aktiviert';
$string['flowmandatory'] = 'Obligatorisch';
$string['flowmandatory_help'] = 'Wenn aktiviert, müssen Benutzer diesen Ablauf abschließen, bevor sie auf die Seite zugreifen können.';
$string['targetroles'] = 'Zielrollen';
$string['targetroles_help'] = 'Wählen Sie, welche Benutzerrollen diesen Einführungsablauf sehen sollen.

**Leer lassen = ALLE BENUTZER** werden diese Einführung sehen.';
$string['redirecturl'] = 'Weiterleitungs-URL nach Abschluss';
$string['redirecturl_help'] = 'URL, zu der Benutzer nach Abschluss weitergeleitet werden. Leer lassen für Standard-Dashboard.';

// Step management.
$string['managesteps'] = 'Schritte verwalten';
$string['addnewstep'] = 'Neuen Schritt hinzufügen';
$string['editstep'] = 'Schritt bearbeiten';
$string['deletestep'] = 'Schritt löschen';
$string['confirmdeletestep'] = 'Sind Sie sicher, dass Sie diesen Schritt löschen möchten?';
$string['nosteps'] = 'Es wurden noch keine Schritte zu diesem Ablauf hinzugefügt.';
$string['reordersteps'] = 'Ziehen zum Neuordnen der Schritte';

// Step form.
$string['steptitle'] = 'Schritttitel';
$string['stepcontent'] = 'Inhalt';
$string['steptype'] = 'Schritttyp';
$string['steptype_content'] = 'Nur Inhalt';
$string['steptype_video'] = 'Video';
$string['steptype_image'] = 'Bild';
$string['steptype_mixed'] = 'Gemischt (Video + Inhalt)';
$string['videourl'] = 'Video-URL';
$string['videourl_help'] = 'Geben Sie die URL eines YouTube-, Vimeo- oder selbstgehosteten Videos ein.';
$string['videorequired'] = 'Video muss angesehen werden';
$string['videorequired_help'] = 'Wenn aktiviert, müssen Benutzer den erforderlichen Prozentsatz des Videos ansehen, bevor sie fortfahren können.';
$string['videocompletion'] = 'Erforderlicher Anschauungsprozentsatz';
$string['videocompletion_help'] = 'Der Prozentsatz des Videos, der angesehen werden muss (0-100).';
$string['imageurl'] = 'Bild-URL';
$string['ctabutton'] = 'Buttontext';
$string['ctabutton_help'] = 'Text für den Call-to-Action-Button (z.B. "Jetzt Abonnieren").';
$string['ctaurl'] = 'Button-URL';
$string['ctaurl_help'] = 'URL, zu der der Button führen soll.';
$string['ctanewtab'] = 'In neuem Tab öffnen';

// Onboarding UI.
$string['welcome'] = 'Willkommen';
$string['next'] = 'Weiter';
$string['previous'] = 'Zurück';
$string['finish'] = 'Loslegen';
$string['skip'] = 'Überspringen';
$string['stepof'] = 'Schritt {$a->current} von {$a->total}';
$string['watchvideotocontinue'] = 'Dieses kurze Video hilft Ihnen, das Beste aus Ihrer Lernerfahrung herauszuholen';
$string['videoprogresstext'] = 'Video-Fortschritt: {$a}%';
$string['completevideofirst'] = 'Fast geschafft! Bitte schauen Sie das Video zu Ende, um fortzufahren.';

// Completion.
$string['completed'] = 'Abgeschlossen';
$string['inprogress'] = 'In Bearbeitung';
$string['pending'] = 'Nicht gestartet';
$string['completionstatus'] = 'Status';
$string['completiondate'] = 'Abgeschlossen am';
$string['resetcompletion'] = 'Abschluss zurücksetzen';
$string['resetcompletionconfirm'] = 'Sind Sie sicher, dass Sie den Einführungsabschluss für diesen Benutzer zurücksetzen möchten?';
$string['completionreset'] = 'Der Einführungsabschluss wurde für den Benutzer zurückgesetzt.';

// Reports.
$string['totalusers'] = 'Gesamtbenutzer';
$string['completedusers'] = 'Abgeschlossen';
$string['inprogressusers'] = 'In Bearbeitung';
$string['completionrate'] = 'Abschlussrate';
$string['averagetimetocomplete'] = 'Durchschnittliche Zeit bis zum Abschluss';
$string['nocompletiondata'] = 'Noch keine Abschlussdaten verfügbar.';

// Events.
$string['eventonboardingstarted'] = 'Einführung gestartet';
$string['eventonboardingcompleted'] = 'Einführung abgeschlossen';
$string['eventstepcompleted'] = 'Einführungsschritt abgeschlossen';

// Errors.
$string['errornoflow'] = 'Kein Einführungsablauf gefunden.';
$string['errornosteps'] = 'Dieser Einführungsablauf hat keine konfigurierten Schritte.';
$string['errorflowdisabled'] = 'Dieser Einführungsablauf ist derzeit deaktiviert.';
$string['errorinvalidstep'] = 'Ungültiger Schritt.';
$string['errorpermission'] = 'Sie haben keine Berechtigung für diese Aktion.';

// Reset completions.
$string['resetallcompletions'] = 'Benutzerabschlüsse zurücksetzen';
$string['resetwarning'] = 'Warnung: Das Zurücksetzen der Abschlüsse zwingt Benutzer, den Einführungsablauf erneut zu durchlaufen.';
$string['currentstats'] = 'Aktuelle Statistiken';
$string['totalcompletionrecords'] = 'Gesamte Abschlussdatensätze: {$a}';
$string['completedcount'] = 'Benutzer, die die Einführung abgeschlossen haben: {$a}';
$string['inprogresscount'] = 'Benutzer in Bearbeitung: {$a}';
$string['resetoptions'] = 'Zurücksetzungsoptionen';
$string['resetalldesc'] = 'ALLE Abschlussdatensätze zurücksetzen - jeder Benutzer wird die Einführung erneut sehen.';
$string['resetallbutton'] = 'Alle Abschlüsse zurücksetzen';
$string['resetspecificdesc'] = 'Oder Abschlüsse für einen bestimmten Ablauf zurücksetzen:';
$string['resetflow'] = 'Zurücksetzen: {$a}';
$string['resetconfirm'] = 'Sind Sie sicher? Alle betroffenen Benutzer werden den Einführungsablauf erneut sehen.';
$string['resetallcomplete'] = 'Alle Abschlussdatensätze wurden zurückgesetzt.';
$string['resetflowcomplete'] = 'Die Abschlussdatensätze für diesen Ablauf wurden zurückgesetzt.';

// Privacy.
$string['privacy:metadata:local_onboarding_completion'] = 'Informationen über den Einführungsabschlussstatus der Benutzer.';
$string['privacy:metadata:local_onboarding_completion:userid'] = 'Die ID des Benutzers.';
$string['privacy:metadata:local_onboarding_completion:status'] = 'Der Abschlussstatus des Einführungsablaufs.';
$string['privacy:metadata:local_onboarding_completion:timestarted'] = 'Wann der Benutzer die Einführung begonnen hat.';
$string['privacy:metadata:local_onboarding_completion:timecompleted'] = 'Wann der Benutzer die Einführung abgeschlossen hat.';
