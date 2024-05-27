<?php

/* ========================================================================
 * Open eClass 4.0
 * E-learning and Course Management System
 * ========================================================================
 * Copyright 2003-2019  Greek Universities Network - GUnet
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
 * @file index.php
 * @brief Sessions display module
 */

$require_login = true;
$require_current_course = true;
$require_help = TRUE;
$helpTopic = 'course_sessions';

require_once '../../include/baseTheme.php';
require_once 'include/lib/forcedownload.php';
require_once 'include/lib/fileDisplayLib.inc.php';
require_once 'include/lib/fileManageLib.inc.php';
require_once 'include/lib/fileUploadLib.inc.php';
require_once 'include/lib/modalboxhelper.class.php';
require_once 'include/lib/multimediahelper.class.php';
require_once 'include/lib/mediaresource.factory.php';
require_once 'insert_doc.php';
require_once 'insert_work.php';
require_once 'insert_tc.php';
require_once 'functions.php';

load_js('tools.js');

if(isset($_GET['session'])){
    $data['session_id'] = $session_id = $_GET['session'];
}
elseif(isset($_GET['id'])){
    $data['session_id'] = $session_id = $_GET['id'];
}
$pageName = title_session($course_id,$session_id);
$navigation[] = array('url' => 'index.php?course=' . $course_code, 'name' => $langSession);

$data['is_tutor_course'] = $is_tutor_course = is_tutor_course($course_id,$uid);
$data['is_consultant'] = $is_consultant = is_consultant($course_id,$uid);
$data['current_time'] = $current_time = date('Y-m-d H:i:s', strtotime('now'));

if(isset($_GET['del'])){
    Database::get()->query("DELETE FROM session_resources WHERE id = ?d",$_GET['del']);
    Session::flash('message',$langSessionResourseDeleted);
    Session::flash('alert-class', 'alert-success');
    redirect_to_home_page("modules/session/session_space.php?course=".$course_code."&session=".$session_id);
}

if(isset($_POST['submit_doc'])){
    insert_session_docs($session_id);
}elseif(isset($_POST['submit_tc'])){
    insert_session_tc($session_id);
}elseif(isset($_POST['submit_work'])){
    insert_session_work($session_id);
}

$type_resource = '';
if(isset($_GET['type'])){
    if($_GET['type'] == 'doc'){
        $type_resource = list_documents($session_id);
    }elseif($_GET['type'] == 'work'){
        $type_resource = list_assignment($session_id);
    }elseif($_GET['type'] == 'tc'){
        $type_resource = list_teleconferences($session_id);
    }
}
$data['type_resource'] = $type_resource;

$data['tool_content_sessions'] = show_session_resources($session_id);

// An consultant can create a session
if($is_editor){
    if($is_consultant){
        $data['all_session'] = Database::get()->queryArray("SELECT * FROM mod_session 
                                    WHERE course_id = ?d AND creator = ?d",$course_id,$uid);
    }else{
        $data['all_session'] = Database::get()->queryArray("SELECT * FROM mod_session 
                                                WHERE course_id = ?d",$session_id,$course_id);
    }
}else{// is simple user
    $data['all_session'] = Database::get()->queryArray("SELECT * FROM mod_session
                                                    WHERE visible = ?d
                                                    AND course_id = ?d
                                                    AND ( finish > NOW() OR start > NOW() )
                                                    AND id IN (SELECT session_id FROM mod_session_users
                                                                WHERE participants = ?d)",1,$course_id,$uid); 

    $data['action_bar'] = action_bar([
        [ 'title' => $langBack,
          'url' => $urlAppend . 'modules/session/index.php?course=' . $course_code,
          'icon' => 'fa-reply',
          'button-class' => 'btn-success',
          'level' => 'primary-label' ]
    ], false);
}


view('modules.session.session_space', $data);