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
 * Spanish language strings for local_onboarding.
 *
 * @package    local_onboarding
 * @copyright  2025 BixAgency.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Plugin name and general.
$string['pluginname'] = 'Incorporación';
$string['modulename'] = 'Incorporación';

// Settings.
$string['settings'] = 'Configuración de Incorporación';
$string['enabled'] = 'Habilitar incorporación';
$string['enabled_desc'] = 'Cuando está habilitado, los usuarios verán el flujo de incorporación después de iniciar sesión (si no lo han completado).';
$string['defaultvideocompletion'] = 'Porcentaje predeterminado de video';
$string['defaultvideocompletion_desc'] = 'El porcentaje predeterminado del video que debe verse antes de poder continuar (0-100).';
$string['showadmins'] = 'Mostrar a administradores';
$string['showadmins_desc'] = 'Si está habilitado, los administradores del sitio también verán el flujo de incorporación.';

// Capabilities.
$string['onboarding:manageflows'] = 'Gestionar flujos de incorporación';
$string['onboarding:viewreports'] = 'Ver informes de incorporación';
$string['onboarding:bypass'] = 'Omitir incorporación';

// Admin pages.
$string['manageflows'] = 'Gestionar Flujos de Incorporación';
$string['completionreport'] = 'Informe de Finalización';
$string['addnewflow'] = 'Agregar nuevo flujo';
$string['editflow'] = 'Editar flujo';
$string['deleteflow'] = 'Eliminar flujo';
$string['confirmdeleteflow'] = '¿Está seguro de que desea eliminar este flujo de incorporación? También se eliminarán todos los pasos.';

// Flow form.
$string['flowname'] = 'Nombre del flujo';
$string['flowname_help'] = 'Ingrese un nombre descriptivo para este flujo de incorporación (ej. "Bienvenida Nuevo Estudiante").';
$string['flowdescription'] = 'Descripción';
$string['flowenabled'] = 'Habilitado';
$string['flowmandatory'] = 'Obligatorio';
$string['flowmandatory_help'] = 'Si está habilitado, los usuarios deben completar este flujo antes de acceder al sitio.';
$string['targetroles'] = 'Roles objetivo';
$string['targetroles_help'] = 'Seleccione qué roles de usuario deben ver este flujo de incorporación.

**Dejar vacío = TODOS LOS USUARIOS** verán esta incorporación.';
$string['redirecturl'] = 'URL de redirección después de completar';
$string['redirecturl_help'] = 'URL para redirigir a los usuarios después de completar. Dejar vacío para el panel predeterminado.';

// Step management.
$string['managesteps'] = 'Gestionar Pasos';
$string['addnewstep'] = 'Agregar nuevo paso';
$string['editstep'] = 'Editar paso';
$string['deletestep'] = 'Eliminar paso';
$string['confirmdeletestep'] = '¿Está seguro de que desea eliminar este paso?';
$string['nosteps'] = 'No se han agregado pasos a este flujo todavía.';
$string['reordersteps'] = 'Arrastre para reordenar pasos';

// Step form.
$string['steptitle'] = 'Título del paso';
$string['stepcontent'] = 'Contenido';
$string['steptype'] = 'Tipo de paso';
$string['steptype_content'] = 'Solo contenido';
$string['steptype_video'] = 'Video';
$string['steptype_image'] = 'Imagen';
$string['steptype_mixed'] = 'Mixto (video + contenido)';
$string['videourl'] = 'URL del video';
$string['videourl_help'] = 'Ingrese la URL de un video de YouTube, Vimeo o autoalojado.';
$string['videorequired'] = 'El video debe verse';
$string['videorequired_help'] = 'Si está habilitado, los usuarios deben ver el porcentaje requerido del video antes de continuar.';
$string['videocompletion'] = 'Porcentaje requerido de visualización';
$string['videocompletion_help'] = 'El porcentaje del video que debe verse (0-100).';
$string['imageurl'] = 'URL de imagen';
$string['ctabutton'] = 'Texto del botón';
$string['ctabutton_help'] = 'Texto para mostrar en el botón de llamada a la acción (ej. "Suscribirse Ahora").';
$string['ctaurl'] = 'URL del botón';
$string['ctaurl_help'] = 'URL a la que debe enlazar el botón.';
$string['ctanewtab'] = 'Abrir en nueva pestaña';

// Onboarding UI.
$string['welcome'] = 'Bienvenido';
$string['next'] = 'Continuar';
$string['previous'] = 'Anterior';
$string['finish'] = 'Comenzar';
$string['skip'] = 'Omitir';
$string['stepof'] = 'Paso {$a->current} de {$a->total}';
$string['watchvideotocontinue'] = 'Este breve video te ayudará a aprovechar al máximo tu experiencia de aprendizaje';
$string['videoprogresstext'] = 'Progreso del video: {$a}%';
$string['completevideofirst'] = '¡Casi terminamos! Por favor, termina de ver el video para continuar.';

// Completion.
$string['completed'] = 'Completado';
$string['inprogress'] = 'En Progreso';
$string['pending'] = 'No Iniciado';
$string['completionstatus'] = 'Estado';
$string['completiondate'] = 'Completado el';
$string['resetcompletion'] = 'Restablecer finalización';
$string['resetcompletionconfirm'] = '¿Está seguro de que desea restablecer la finalización de incorporación para este usuario?';
$string['completionreset'] = 'La finalización de incorporación ha sido restablecida para el usuario.';

// Reports.
$string['totalusers'] = 'Total de usuarios';
$string['completedusers'] = 'Completados';
$string['inprogressusers'] = 'En progreso';
$string['completionrate'] = 'Tasa de finalización';
$string['averagetimetocomplete'] = 'Tiempo promedio para completar';
$string['nocompletiondata'] = 'No hay datos de finalización disponibles todavía.';

// Events.
$string['eventonboardingstarted'] = 'Incorporación iniciada';
$string['eventonboardingcompleted'] = 'Incorporación completada';
$string['eventstepcompleted'] = 'Paso de incorporación completado';

// Errors.
$string['errornoflow'] = 'No se encontró flujo de incorporación.';
$string['errornosteps'] = 'Este flujo de incorporación no tiene pasos configurados.';
$string['errorflowdisabled'] = 'Este flujo de incorporación está actualmente deshabilitado.';
$string['errorinvalidstep'] = 'Paso inválido.';
$string['errorpermission'] = 'No tiene permiso para realizar esta acción.';

// Reset completions.
$string['resetallcompletions'] = 'Restablecer Finalizaciones de Usuarios';
$string['resetwarning'] = 'Advertencia: Restablecer las finalizaciones obligará a los usuarios a pasar por el flujo de incorporación nuevamente.';
$string['currentstats'] = 'Estadísticas Actuales';
$string['totalcompletionrecords'] = 'Total de registros de finalización: {$a}';
$string['completedcount'] = 'Usuarios que completaron la incorporación: {$a}';
$string['inprogresscount'] = 'Usuarios en progreso: {$a}';
$string['resetoptions'] = 'Opciones de Restablecimiento';
$string['resetalldesc'] = 'Restablecer TODOS los registros de finalización - cada usuario verá la incorporación nuevamente.';
$string['resetallbutton'] = 'Restablecer Todas las Finalizaciones';
$string['resetspecificdesc'] = 'O restablecer finalizaciones para un flujo específico:';
$string['resetflow'] = 'Restablecer: {$a}';
$string['resetconfirm'] = '¿Está seguro? Esto hará que todos los usuarios afectados vean el flujo de incorporación nuevamente.';
$string['resetallcomplete'] = 'Todos los registros de finalización han sido restablecidos.';
$string['resetflowcomplete'] = 'Los registros de finalización para este flujo han sido restablecidos.';

// Privacy.
$string['privacy:metadata:local_onboarding_completion'] = 'Información sobre el estado de finalización de incorporación de los usuarios.';
$string['privacy:metadata:local_onboarding_completion:userid'] = 'El ID del usuario.';
$string['privacy:metadata:local_onboarding_completion:status'] = 'El estado de finalización del flujo de incorporación.';
$string['privacy:metadata:local_onboarding_completion:timestarted'] = 'Cuándo el usuario comenzó la incorporación.';
$string['privacy:metadata:local_onboarding_completion:timecompleted'] = 'Cuándo el usuario completó la incorporación.';
