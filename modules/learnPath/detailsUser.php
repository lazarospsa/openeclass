<?php

/* ========================================================================
 * Open eClass 2.4
 * E-learning and Course Management System
 * ========================================================================
 * Copyright 2003-2011  Greek Universities Network - GUnet
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


/* ===========================================================================
  detailsUser.php
  @authors list: Thanos Kyritsis <atkyritsis@upnet.gr>

  based on Claroline version 1.7 licensed under GPL
  copyright (c) 2001, 2006 Universite catholique de Louvain (UCL)

  original file: tracking/userLog.php Revision: 1.37

  Claroline authors: Thomas Depraetere <depraetere@ipm.ucl.ac.be>
  Hugues Peeters    <peeters@ipm.ucl.ac.be>
  Christophe Gesche <gesche@ipm.ucl.ac.be>
  Sebastien Piraux  <piraux_seb@hotmail.com>
  ==============================================================================
  @Description: This script presents the student's progress for all
  learning paths available in a course to the teacher.

  Only the Learning Path specific code was ported and
  modified from the original claroline file.

  @Comments:

  @todo:
  ==============================================================================
 */

$require_current_course = TRUE;
$require_editor = TRUE;
require_once '../../include/baseTheme.php';
require_once 'include/lib/learnPathLib.inc.php';

$navigation[] = array('url' => "index.php?course=$course_code", 'name' => $langLearningPaths);
$navigation[] = array('url' => "detailsAll.php?course=$course_code", 'name' => $langTrackAllPathExplanation);
$toolName = $langTrackUser;

// user info can not be empty, return to the list of details
if (empty($_REQUEST['uInfo'])) {
    header("Location: ./detailsAll.php?course=$course_code");
    exit();
}

// check if user is in this course
$rescnt = Database::get()->querySingle("SELECT COUNT(*) AS count
            FROM `course_user` as `cu` , `user` as `u`
            WHERE `cu`.`user_id` = `u`.`id`
            AND `cu`.`course_id` = ?d
            AND `u`.`id` = ?d", $course_id, $_REQUEST['uInfo'])->count;

if ($rescnt == 0) {
    header("Location: ./detailsAll.php?course=$course_code");
    exit();
}

//$trackedUser = $results[0];
//$nameTools = $trackedUser['surname'] . " " . $trackedUser['givenname'];
/*
  $tool_content .= ucfirst(strtolower($langUser)).': <br />'."\n"
  .'<ul>'."\n"
  .'<li>'.$langLastName.': '.$trackedUser['surname'].'</li>'."\n"
  .'<li>'.$langName.': '.$trackedUser['givenname'].'</li>'."\n"
  .'<li>'.$langEmail.': ';
  if( empty($trackedUser['email']) )	$tool_content .= $langNoEmail;
  else 			$tool_content .= $trackedUser['email'];

  $tool_content .= '</li>'."\n"
  .'</ul>'."\n"
  .'</p>'."\n";
 */

// get list of learning paths of this course
// list available learning paths
$lpList = Database::get()->queryArray("SELECT name, learnPath_id
            FROM lp_learnPath
            WHERE course_id = ?d
            ORDER BY `rank`", $course_id);

// get infos about the user
$uDetails = Database::get()->querySingle("SELECT surname, givenname, email 
    FROM `user`
    WHERE id = ?d", $_REQUEST['uInfo']);

$pageName = $langStudent . ": " . q($uDetails->surname) . " " . q($uDetails->givenname) . " (" . q($uDetails->email) . ")";

$tool_content .= action_bar(array(
    array('title' => $langBack,
        'url' => "$_SERVER[SCRIPT_NAME]?course=$course_code",
        'icon' => 'fa-reply',
        'level' => 'primary-label')));

$tool_content .= "<div class='alert alert-info'>
    $langSave <a href='dumpuserdetails.php?course=$course_code&amp;uInfo=" . $_GET['uInfo'] . "'>$langDumpUserDurationToFile</a>
        (<a href='dumpuserdetails.php?course=$course_code&amp;uInfo=" . $_GET['uInfo'] . "&amp;enc=UTF-8'>$langcsvenc2</a>)
    </div>";

// table header
$tool_content .= "<div class='table-responsive'><table class='table-default'>
                    <tr class='list-header text-left'>
                        <th>$langLearnPath</th>
                        <th>$langAttemptsNb</th>
                        <th>$langAttemptStarted</th>
                        <th>$langAttemptAccessed</th>
                        <th>$langTotalTimeSpent</th>
                        <th>$langLessonStatus</th>
                        <th>$langProgress</th>
                    </tr>";

if (count($lpList) == 0) {
    $tool_content .= "<tr>
                        <td colspan='7' class='text-center'>$langNoLearningPath</td>
                      </tr>";
} else {
    $totalProgress = 0;
    $totalTimeSpent = "0000:00:00";
    // display each learning path with the corresponding progression of the user
    foreach ($lpList as $lpDetails) {
        list($lpProgress, $lpTotalTime, $lpTotalStarted, $lpTotalAccessed, $lpTotalStatus, $lpAttemptsNb) = get_learnPath_progress_details($lpDetails->learnPath_id, $_GET['uInfo']);
        $totalProgress += $lpProgress;
        if (!empty($lpTotalTime)) {
            $totalTimeSpent = addScormTime($totalTimeSpent, $lpTotalTime);
        }
        $tool_content .= "<tr><td>
                            <a href='detailsUserPath.php?course=$course_code&amp;uInfo=" . $_GET['uInfo'] . "&amp;path_id=" . $lpDetails->learnPath_id . "'>" . htmlspecialchars($lpDetails->name) . "</a>
                         </td>
                         <td>" . q($lpAttemptsNb) ."</td>
                         <td>" . q($lpTotalStarted) . "</td>
                         <td>" . q($lpTotalAccessed) . "</td>
                         <td>" . q($lpTotalTime) . "</td>
                         <td>" . disp_lesson_status($lpTotalStatus) . "</td>
                         <td>"
                            . disp_progress_bar($lpProgress, 1) .
                        "</td></tr>";
    }
    $tool_content .= "<tr><td colspan='4'>$langTotal</td>
        <td>" . q($totalTimeSpent) . "</td>
        <td></td>
        <td>"
        . disp_progress_bar(round($totalProgress/count($lpList)), 1) .
        "</td></tr>";
}
$tool_content .= '      </table></div>' . "\n";

draw($tool_content, 2, null, $head_content);

