<?php
/*
 * ========================================================================
 * Open eClass 3.11 - E-learning and Course Management System
 * ========================================================================
 * Copyright 2003-2021  Greek Universities Network - GUnet
 * A full copyright notice can be read in "/info/copyright.txt".
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
 *
 * For a full list of contributors, see "credits.txt".
 */

$require_current_course = true;

require_once '../../include/baseTheme.php';

$unit = isset($_GET['unit'])? intval($_GET['unit']): null;
$res_type = isset($_GET['res_type']);

// validate
$content_id = intval($_GET['id']);
$onlyEnabledWhere = ($is_editor) ? '' : " AND enabled = 1 ";
$content = Database::get()->queryArray("SELECT * FROM h5p_content WHERE id = ?d AND course_id = ?d $onlyEnabledWhere", $content_id, $course_id);
if (!$content) {
    redirect_to_home_page("modules/h5p/index.php?course=$course_code");
}

if (!$res_type) {
    $backUrl = $urlAppend . 'modules/h5p/?course=' . $course_code;
} else {
    $backUrl = $urlAppend . 'modules/units/?course=' . $course_code . '&id=' . $_REQUEST['unit'];
}

$toolName = $langImport;
$navigation[] = ['url' => $backUrl, 'name' => $langH5p];

$tool_content .= action_bar([[
    'title' => $langBack,
    'url' => $backUrl,
    'icon' => 'fa-reply',
    'level' => 'primary-label'
]], false);

$workspaceUrl = $urlAppend . 'courses/' . $course_code . '/h5p/content/' . $content_id . '/workspace';
$workspaceLibs = $urlAppend . 'courses/h5p/libraries';

$head_content .= "
    <link type='text/css' rel='stylesheet' media='all' href='$urlServer/js/h5p-standalone/styles/h5p.css' />
    <script type='text/javascript' src='$urlServer/js/h5p-standalone/main.bundle.js'></script>";

$tool_content .= "<div class='row'>
        <div class='col-xs-12'>
            <div id='h5p-container'></div>
        </div>
    </div>";

$head_content .= "
    <script type='text/javascript'>
        $(document).ready(function() {
            const el = document.getElementById('h5p-container');
            const options = {
              h5pJsonPath:  '$workspaceUrl',
              librariesPath: '$workspaceLibs',
              frameJs: '$urlServer/js/h5p-standalone/frame.bundle.js',
              frameCss: '$urlServer/js/h5p-standalone/styles/h5p.css',
              frame: true,
              copyright: true,
              icon: true,
              fullScreen: true
            };
            new H5PStandalone.H5P(el, options);
        });
    </script>";

draw($tool_content, 2, null, $head_content);
