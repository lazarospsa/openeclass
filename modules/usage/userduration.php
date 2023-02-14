<?php

/* ========================================================================
 * Open eClass 3.0
 * E-learning and Course Management System
 * ========================================================================
 * Copyright 2003-2014  Greek Universities Network - GUnet
 * A full copyright notice can be read in "/info/copyright.txt".
 * For a full list of contributors, see "credits.txt".
 *
 * Open eClass is an open platform distributed in the hope that it will
 * be useful (without any warranty), under the terms of the GNU (General
 * Public License) as published by the Free Software Foundation.
 * The full license can be read in "/info/license/license_gpl.txt".
 *
 * Contact address: GUnet Asynchronous eLearning Group,
 *                  Network Operations Center, University of Athens,
 *                  Panepistimiopolis Ilissia, 15784, Athens, Greece
 *                  e-mail: info@openeclass.org
 * ======================================================================== */

/**
 * @file userduration.php
 * @brief Shows logins made by a user or all users of a course, during a specific period.
 * Data from table 'logins'
 */

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$require_current_course = true;
$require_course_admin = true;
$require_help = true;
$helpTopic = 'course_stats';
$helpSubTopic = 'users_participation';
$require_login = true;

require_once '../../include/baseTheme.php';
require_once 'modules/group/group_functions.php';
require_once 'modules/usage/usage.lib.php';

if (isset($_GET['u'])) { //  stats per user

    $am_legend = $csv_am_legend = $grp_legend = $csv_grp_legend = '';
    $am = uid_to_am($_GET['u']);
    if (!empty($am)) {
        $am_legend = "<div><small>$langAmShort: " . $am . "</small></div>"; // user am
        $csv_am_legend = "$langAmShort: $am";
    }
    $grp_name = user_groups($course_id, $_GET['u'], false);
    if ($grp_name != '-') {
        $grp_legend = "<div><small>$langGroup: " . $grp_name . "</small></div>"; // user group
        $csv_grp_legend = "$langGroup: $grp_name";
    }

    $user_actions = Database::get()->queryArray("SELECT 
                            SUM(ABS(actions_daily.duration)) AS duration, 
                              module_id 
                            FROM actions_daily
                            WHERE course_id = ?d
                              AND user_id = ?d
                             AND module_id != " . MODULE_ID_TC . "
                             AND module_id != " . MODULE_ID_LP . "
                            GROUP BY module_id", $course_id, $_GET['u']);


    if (isset($_GET['format']) and $_GET['format'] == 'xls') { // xls output
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($langParticipate);
        $sheet->getDefaultColumnDimension()->setWidth(25);
        $filename = $course_code . "_user_duration.xlsx";

        $user_details = uid_to_name($_GET['u']) . " $csv_am_legend $csv_grp_legend";
        $data[] = [ $user_details ];

        $data[] = [ $langModule, $langDuration ];

        foreach ($user_actions as $ua) {
            $mod = which_module($ua->module_id);
            $dur = format_time_duration(0 + $ua->duration);
            $data[] = [ $mod, $dur ];
        }

        $sheet->mergeCells("A1:B1");
        $sheet->getCell('A1')->getStyle()->getFont()->setItalic(true);
        $sheet->getCell('A2')->getStyle()->getFont()->setBold(true);
        $sheet->getCell('B2')->getStyle()->getFont()->setBold(true);
        // create spreadsheet
        $sheet->fromArray($data, NULL);
        // file output
        $writer = new Xlsx($spreadsheet);
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename=$filename");
        $writer->save("php://output");
        exit;

    } else { // html output
        $toolName = "$langParticipate $langOfUser";
        $navigation[] = array('url' => 'index.php?course=' . $course_code, 'name' => $langUsage);
        $navigation[] = array('url' => 'userduration.php?course=' . $course_code, 'name' => $langUserDuration);

        $tool_content .= action_bar(array(
            array('title' => $langDumpUser,
                'url' => "$_SERVER[SCRIPT_NAME]?course=$course_code&amp;u=$_GET[u]&amp;format=xls",
                'icon' => 'fa-download',
                'level' => 'primary-label'),
            array('title' => $langBack,
                'url' => "$_SERVER[SCRIPT_NAME]?course=$course_code",
                'icon' => 'fa-reply',
                'level' => 'primary-label')
        ), false);

        $tool_content .= "<div class='col-sm-12 mt-3'><div class='alert alert-info'>" . uid_to_name($_GET['u']) . " $am_legend $grp_legend</div></div>";

        $tool_content .= "
        <div class='col-sm-12'>
        <div class='table-responsive'>
            <table class='table-default'>
            <tr class='list-header'>
              <th>$langModule</th>          
              <th>$langDuration</th>          
            </tr>";
        foreach ($user_actions as $ua) {
            $tool_content .= "<tr>";
            $tool_content .= "<td>" . which_module($ua->module_id) . "</td>";
            $tool_content .= "<td>" . format_time_duration(0 + $ua->duration) . "</td>";
            $tool_content .= "</tr>";
        }
        $tool_content .= "</table></div></div>";

        // user last logins
        $user_logins = Database::get()->queryArray("SELECT last_update
                      FROM actions_daily
                            WHERE course_id = ?d
                              AND user_id = ?d
                    AND module_id = ". MODULE_ID_UNITS . " 
                    ORDER BY last_update 
                    DESC ", $course_id, $_GET['u']);

        if (count($user_logins) > 0) {
            $tool_content .= "<div class='col-sm-12'><div class='table-responsive'><table class='table-default'>
            <tr class='list-header'>
                <th>$langLastUserVisits</th>
            </tr>";
            foreach ($user_logins as $ul) {
                $tool_content .= "<tr>";
                $tool_content .= "<td>" . format_locale_date(strtotime($ul->last_update)) . "</td>";
                $tool_content .= "</tr>";
            }
            $tool_content .= "</table></div></div>";
        }
    }
    draw($tool_content, 2);

} else if (isset($_GET['m']) and $_GET['m'] != -1) { // stats per module
    $module = $_GET['m'];
    $user_actions = Database::get()->queryArray("SELECT 
                            SUM(actions_daily.duration) AS duration, user_id, 
                              module_id 
                            FROM actions_daily
                            WHERE course_id = ?d
                              AND module_id = ?d
                            GROUP BY user_id", $course_id, $module);


    if (isset($_GET['format']) and $_GET['format'] == 'xls') { // csv output
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($langParticipate);
        $sheet->getDefaultColumnDimension()->setWidth(40);
        $filename = $course_code . "_user_duration.xlsx";
        $mod = which_module($module);

        $data[] = [ "$langModule: $mod" ];
        $data[] = [ $langUser, $langGroup, $langAm, $langDuration ];

        foreach ($user_actions as $um) {
            $grp_name = user_groups($course_id, $um->user_id, false);
            $user_am = uid_to_am($um->user_id);
            $user_details = uid_to_name($um->user_id);
            $data[] = [ $user_details, $grp_name, $user_am, format_time_duration(0 + $um->duration) ];
        }

        $sheet->getCell('A1')->getStyle()->getFont()->setItalic(true);
        for ($i=1; $i<=4; $i++) {
            $sheet->getCellByColumnAndRow($i, 2)->getStyle()->getFont()->setBold(true);
        }

        // create spreadsheet
        $sheet->fromArray($data, NULL);
        // file output
        $writer = new Xlsx($spreadsheet);
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename=$filename");
        $writer->save("php://output");
        exit;
    } else { // html output
        $toolName = "$langParticipate $langOfUser";
        $navigation[] = array('url' => 'index.php?course=' . $course_code, 'name' => $langUsage);
        $navigation[] = array('url' => '$_SERVER[SCRIPT_NAME]?course=' . $course_code, 'name' => $langUserDuration);

        $tool_content .= action_bar(array(
            array('title' => $langDumpUser,
                'url' => "$_SERVER[SCRIPT_NAME]?course=$course_code&amp;m=$module&amp;format=xls",
                'icon' => 'fa-download',
                'level' => 'primary-label'),
            array('title' => $langBack,
                'url' => "index.php?course=$course_code",
                'icon' => 'fa-reply',
                'level' => 'primary-label')
        ), false);

        $tool_content .=  selection_course_modules();

        $tool_content .= "<div class='col-sm-12 mt-3'><div class='alert alert-info'>" . which_module($module) . "</div></div>";

        $tool_content .= "
        <div class='col-sm-12'>
        <div class='table-responsive'>
            <table class='table-default'>
            <tr class='list-header'>
                <th class='ps-3'>$langUser</th>
                <th>$langAm</th>
                <th>$langGroup</th>
                <th>$langDuration</th>
            </tr>";
        foreach ($user_actions as $um) {
            $grp_name = user_groups($course_id, $um->user_id);
            $user_am = uid_to_am($um->user_id);
            $tool_content .= "<tr>";
            $tool_content .= "<td>" . display_user($um->user_id) . "</td>";
            $tool_content .= "<td>" . $grp_name . "</td>";
            $tool_content .= "<td>" . $user_am . "</td>";
            $tool_content .= "<td>" . format_time_duration(0 + $um->duration) . "</td>";
            $tool_content .= "</tr>";
        }
        $tool_content .= "</table></div></div>";
        draw($tool_content, 2);

    }
} else {
    if (isset($_GET['format']) and $_GET['format'] == 'xls') {
        $format = 'xls';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($langParticipate);
        $sheet->getDefaultColumnDimension()->setWidth(20);
        $filename = $course_code . "_users_duration.xlsx";
        $data[] = [ $langSurname, $langName, $langAm, $langGroup, $langDuration ];

        for ($i=1; $i<=5; $i++) { // format first row
            $sheet->getCellByColumnAndRow($i, 1)->getStyle()->getFont()->setBold(true);
        }

    } else {
        $format = 'html';
        $toolName = $langUserDuration;

        $navigation[] = array('url' => 'index.php?course=' . $course_code, 'name' => $langUsage);

        $tool_content .= action_bar(array(
            array('title' => $langStatsReportsLP,
                'url' => "../learnPath/detailsAll.php?course=$course_code",
                'icon' => 'fa-vcard-o',
                'level' => 'primary-label',
                'button-class' => 'btn-success'),
            array('title' => $langStatsReportsTC,
                'url' => "../tc/tcuserduration.php?course=$course_code",
                'icon' => 'fa-vcard-o',
                'level' => 'primary-label',
                'button-class' => 'btn-success'),
            array('title' => $langDumpUser,
                'url' => "$_SERVER[SCRIPT_NAME]?course=$course_code&amp;format=xls",
                'icon' => 'fa-download',
                'level' => 'primary-label',
                'button-class' => 'btn-success'),
            array('title' => $langBack,
                'url' => "index.php?course=$course_code",
                'icon' => 'fa-reply',
                'level' => 'primary-label')
        ), false);

        $tool_content .= selection_course_modules();

        $tool_content .= "
        <div class='col-sm-12'>
        <div class='table-responsive'>
        <table class='table-default'>
        <tr class='list-header'>
          <th class='ps-3'>$langSurnameName</th>
          <th>$langAm</th>
          <th>$langGroup</th>
          <th>$langDuration</th>
          <th class='text-center'>" . icon('fa-gears') . "</th>
        </tr>";
    }

    $result = user_duration_query($course_id);
    if (count($result) > 0) {
        foreach ($result as $row) {
            $grp_name = user_groups($course_id, $row->id, $format);
            if ($format == 'html') {
                $tool_content .= "<td class='bullet'>" . display_user($row->id) . "</td>
                                <td class='center'>$row->am</td>
                                <td class='center'>$grp_name</td>
                                <td class='center'>" . format_time_duration(0 + $row->duration) . "</td>
                                <td class='center'>" . icon('fa-line-chart', $langDetails, "$_SERVER[SCRIPT_NAME]?course=$course_code&amp;u=$row->id") ."</td>
                            </tr>";
            } elseif ($format == 'xls') {
                $data[] = [ $row->surname, $row->givenname, $row->am, $grp_name, format_time_duration(0 + $row->duration) ];
            }
        }
        if ($format == 'html') {
            $tool_content .= "</table></div></div>";
        }
    }
    if ($format == 'html') {
        draw($tool_content, 2);
    } elseif ($format == 'xls') {
        // create spreadsheet
        $sheet->fromArray($data, NULL);
        // file output
        $writer = new Xlsx($spreadsheet);
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename=$filename");
        $writer->save("php://output");
    }
}

/**
 * @brief Do the queries to calculate usage between timestamps $start and $end
 * @param type $course_id
 * @param type $start
 * @param type $end
 * @param type $group
 * @return returns a MySQL resource, where fetching rows results in duration, surname, givenname, user_id, am
 */
function user_duration_query($course_id, $start = false, $end = false, $group = false) {
    $terms = array();
    if ($start !== false AND $end !== false) {
        $date_where = 'AND actions_daily.day BETWEEN ?s AND ?s';
        $terms = array($start . ' 00:00:00',
                       $end . ' 23:59:59');
    } elseif ($start !== false) {
        $date_where = 'AND actions_daily.day > ?s';
        $terms[] = $start . ' 00:00:00';
    } elseif ($end !== false) {
        $date_where = 'AND actions_daily.day < ?s';
        $terms[] = $end . ' 23:59:59';
    } else {
        $date_where = '';
    }

    if ($group !== false) {
        $from = "`group_members` AS groups
                                LEFT JOIN user ON groups.user_id = user.id";
        $and = "AND groups.group_id = ?d";
        $terms[] = $group;
    } else {
        $from = " (SELECT
                            id, surname, givenname, username, password, email, parent_email, status, phone, am,
                            registered_at, expires_at, lang, description, has_icon, verified_mail, receive_mail, email_public,
                            phone_public, am_public, whitelist, last_passreminder
                          FROM user UNION (SELECT 0 as id,
                            '' as surname,
                            'Anonymous' as givenname,
                            null as username,
                            null as password,
                            null as email,
                            null as parent_email,
                            null as status,
                            null as phone,
                            null as am,
                            null as registered_at,
                            null as expires_at,
                            null as lang,
                            null as description,
                            null as has_icon,
                            null as verified_mail,
                            null as receive_mail,
                            null as email_public,
                            null as phone_public,
                            null as am_public,
                            null as whitelist,
                            null as last_passreminder)) as user ";
        $and = '';
    }

    return Database::get()->queryArray("SELECT SUM(actions_daily.duration) AS duration,
                                       user.surname AS surname,
                                       user.givenname AS givenname,
                                       user.id AS id,
                                       user.am AS am
                                FROM $from
                                LEFT JOIN actions_daily ON user.id = actions_daily.user_id
                                WHERE (actions_daily.course_id = ?d 
                                    AND actions_daily.module_id != " . MODULE_ID_TC . "
                                    AND actions_daily.module_id != " . MODULE_ID_LP . ")                                
                                $and
                                $date_where
                                GROUP BY user.id, surname, givenname, am                          
                                ORDER BY surname, givenname",  $course_id, $terms);
}


/**
 * @brief display selection box of course modules
 * @return string
 */
function selection_course_modules() {

    global $langAllModules, $langModule, $course_id, $modules, $course_code, $module;

    $mod_opts = "<option value='-1'>$langAllModules</option>";
    $result = Database::get()->queryArray("SELECT module_id FROM course_module 
                    WHERE course_id = ?d 
                    AND module_id != " . MODULE_ID_TC . " 
                    AND module_id != " . MODULE_ID_LP . "", $course_id);
    foreach ($result as $row) {
        $mid = $row->module_id;
        $extra = '';
        if ($module == $mid) {
            $extra = 'selected';
        }
        $mod_opts .= "<option value=" . $mid . " $extra>" . $modules[$mid]['title'] . "</option>";
    }

    $content = "
        <div class='col-12'>
            <div class='form-wrapper form-edit rounded'>
                <form class='form-horizontal' name='module_select' action='$_SERVER[SCRIPT_NAME]' method='get'>
                <input type='hidden' name='course' value='$course_code'>
                    <div class='form-group'>
                        <label class='col-sm-6 control-label-notes'>$langModule</label>
                        <div class='col-sm-4'>                            
                            <select name='m' id='m' class='form-select' onChange='document.module_select.submit();'>
                                $mod_opts
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>";

    return $content;
}
