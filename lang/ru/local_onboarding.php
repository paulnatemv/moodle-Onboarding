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
 * Russian language strings for local_onboarding.
 *
 * @package    local_onboarding
 * @copyright  2025 BixAgency.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Plugin name and general.
$string['pluginname'] = 'Введение';
$string['modulename'] = 'Введение';

// Settings.
$string['settings'] = 'Настройки введения';
$string['enabled'] = 'Включить введение';
$string['enabled_desc'] = 'Если включено, пользователи увидят процесс введения после входа в систему (если они его ещё не прошли).';
$string['defaultvideocompletion'] = 'Процент просмотра видео по умолчанию';
$string['defaultvideocompletion_desc'] = 'Процент видео по умолчанию, который необходимо просмотреть для продолжения (0-100).';
$string['showadmins'] = 'Показывать администраторам';
$string['showadmins_desc'] = 'Если включено, администраторы сайта также увидят процесс введения.';

// Capabilities.
$string['onboarding:manageflows'] = 'Управление процессами введения';
$string['onboarding:viewreports'] = 'Просмотр отчётов введения';
$string['onboarding:bypass'] = 'Пропустить введение';

// Admin pages.
$string['manageflows'] = 'Управление процессами введения';
$string['completionreport'] = 'Отчёт о прохождении';
$string['addnewflow'] = 'Добавить новый процесс';
$string['editflow'] = 'Редактировать процесс';
$string['deleteflow'] = 'Удалить процесс';
$string['confirmdeleteflow'] = 'Вы уверены, что хотите удалить этот процесс введения? Все шаги также будут удалены.';

// Flow form.
$string['flowname'] = 'Название процесса';
$string['flowname_help'] = 'Введите описательное название для этого процесса введения (например, "Приветствие нового студента").';
$string['flowdescription'] = 'Описание';
$string['flowenabled'] = 'Включено';
$string['flowmandatory'] = 'Обязательно';
$string['flowmandatory_help'] = 'Если включено, пользователи должны завершить этот процесс, прежде чем получить доступ к сайту.';
$string['targetroles'] = 'Целевые роли';
$string['targetroles_help'] = 'Выберите, какие роли пользователей должны видеть этот процесс введения.

**Оставить пустым = ВСЕ ПОЛЬЗОВАТЕЛИ** увидят это введение.';
$string['redirecturl'] = 'URL перенаправления после завершения';
$string['redirecturl_help'] = 'URL для перенаправления пользователей после завершения. Оставьте пустым для панели по умолчанию.';

// Step management.
$string['managesteps'] = 'Управление шагами';
$string['addnewstep'] = 'Добавить новый шаг';
$string['editstep'] = 'Редактировать шаг';
$string['deletestep'] = 'Удалить шаг';
$string['confirmdeletestep'] = 'Вы уверены, что хотите удалить этот шаг?';
$string['nosteps'] = 'В этот процесс ещё не добавлено ни одного шага.';
$string['reordersteps'] = 'Перетащите для изменения порядка шагов';

// Step form.
$string['steptitle'] = 'Название шага';
$string['stepcontent'] = 'Содержимое';
$string['steptype'] = 'Тип шага';
$string['steptype_content'] = 'Только содержимое';
$string['steptype_video'] = 'Видео';
$string['steptype_image'] = 'Изображение';
$string['steptype_mixed'] = 'Смешанный (видео + содержимое)';
$string['videourl'] = 'URL видео';
$string['videourl_help'] = 'Введите URL видео YouTube, Vimeo или собственного хостинга.';
$string['videorequired'] = 'Видео должно быть просмотрено';
$string['videorequired_help'] = 'Если включено, пользователи должны просмотреть требуемый процент видео, прежде чем продолжить.';
$string['videocompletion'] = 'Требуемый процент просмотра';
$string['videocompletion_help'] = 'Процент видео, который должен быть просмотрен (0-100).';
$string['imageurl'] = 'URL изображения';
$string['ctabutton'] = 'Текст кнопки';
$string['ctabutton_help'] = 'Текст для отображения на кнопке призыва к действию (например, "Подписаться сейчас").';
$string['ctaurl'] = 'URL кнопки';
$string['ctaurl_help'] = 'URL, на который должна вести кнопка.';
$string['ctanewtab'] = 'Открыть в новой вкладке';

// Onboarding UI.
$string['welcome'] = 'Добро пожаловать';
$string['next'] = 'Продолжить';
$string['previous'] = 'Назад';
$string['finish'] = 'Начать';
$string['skip'] = 'Пропустить';
$string['stepof'] = 'Шаг {$a->current} из {$a->total}';
$string['watchvideotocontinue'] = 'Это короткое видео поможет вам получить максимум от обучения';
$string['videoprogresstext'] = 'Прогресс видео: {$a}%';
$string['completevideofirst'] = 'Почти готово! Пожалуйста, досмотрите видео, чтобы продолжить.';

// Completion.
$string['completed'] = 'Завершено';
$string['inprogress'] = 'В процессе';
$string['pending'] = 'Не начато';
$string['completionstatus'] = 'Статус';
$string['completiondate'] = 'Завершено';
$string['resetcompletion'] = 'Сбросить прохождение';
$string['resetcompletionconfirm'] = 'Вы уверены, что хотите сбросить прохождение введения для этого пользователя?';
$string['completionreset'] = 'Прохождение введения для пользователя сброшено.';

// Reports.
$string['totalusers'] = 'Всего пользователей';
$string['completedusers'] = 'Завершили';
$string['inprogressusers'] = 'В процессе';
$string['completionrate'] = 'Процент завершения';
$string['averagetimetocomplete'] = 'Среднее время завершения';
$string['nocompletiondata'] = 'Данные о прохождении пока отсутствуют.';

// Events.
$string['eventonboardingstarted'] = 'Введение начато';
$string['eventonboardingcompleted'] = 'Введение завершено';
$string['eventstepcompleted'] = 'Шаг введения завершён';

// Errors.
$string['errornoflow'] = 'Процесс введения не найден.';
$string['errornosteps'] = 'В этом процессе введения нет настроенных шагов.';
$string['errorflowdisabled'] = 'Этот процесс введения в данный момент отключён.';
$string['errorinvalidstep'] = 'Недопустимый шаг.';
$string['errorpermission'] = 'У вас нет разрешения на это действие.';

// Reset completions.
$string['resetallcompletions'] = 'Сбросить прохождения пользователей';
$string['resetwarning'] = 'Предупреждение: сброс прохождений заставит пользователей снова пройти процесс введения.';
$string['currentstats'] = 'Текущая статистика';
$string['totalcompletionrecords'] = 'Всего записей о прохождении: {$a}';
$string['completedcount'] = 'Пользователей, завершивших введение: {$a}';
$string['inprogresscount'] = 'Пользователей в процессе: {$a}';
$string['resetoptions'] = 'Параметры сброса';
$string['resetalldesc'] = 'Сбросить ВСЕ записи о прохождении - каждый пользователь увидит введение снова.';
$string['resetallbutton'] = 'Сбросить все прохождения';
$string['resetspecificdesc'] = 'Или сбросить прохождения для определённого процесса:';
$string['resetflow'] = 'Сбросить: {$a}';
$string['resetconfirm'] = 'Вы уверены? Все затронутые пользователи снова увидят процесс введения.';
$string['resetallcomplete'] = 'Все записи о прохождении сброшены.';
$string['resetflowcomplete'] = 'Записи о прохождении для этого процесса сброшены.';

// Privacy.
$string['privacy:metadata:local_onboarding_completion'] = 'Информация о статусе прохождения введения пользователями.';
$string['privacy:metadata:local_onboarding_completion:userid'] = 'ID пользователя.';
$string['privacy:metadata:local_onboarding_completion:status'] = 'Статус прохождения процесса введения.';
$string['privacy:metadata:local_onboarding_completion:timestarted'] = 'Когда пользователь начал введение.';
$string['privacy:metadata:local_onboarding_completion:timecompleted'] = 'Когда пользователь завершил введение.';
