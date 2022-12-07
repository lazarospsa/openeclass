<?php

/* ========================================================================
 * Open eClass 3.6
 * E-learning and Course Management System
 * ========================================================================
 * Copyright 2003-2017  Greek Universities Network - GUnet
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
 * @file perso_functions.php
 * @brief user course and course activity functions
 */
require_once 'include/lib/mediaresource.factory.php';
require_once 'main/personal_calendar/calendar_events.class.php';
require_once 'modules/message/class.mailbox.php';
require_once 'modules/message/class.msg.php';
/**
 * @brief display user courses
 * @param integer $uid
 * @return string
 */
function getUserLessonInfo($uid) {
    global $teacher_courses_count, $student_courses_count, $langCourse, $langActions;
    global $session, $lesson_ids, $courses, $urlServer, $langUnregCourse, $langAdm, $langFavorite;
    global $langNotEnrolledToLessons, $langWelcomeProfPerso, $langWelcomeStudPerso, $langWelcomeSelect, $langCode;

    $lesson_content = '';
    $lesson_ids = array();
    $myCourses = Database::get()->queryArray("SELECT course.id course_id,
                             course.code code,
                             course.public_code,
                             course.title title,
                             course.prof_names professor,
                             course.lang,
                             course.visible visible,
                             course_user.status status,
                             course_user.favorite favorite
                        FROM course JOIN course_user
                            ON course.id = course_user.course_id 
                            AND course_user.user_id = ?d 
                            AND (course.visible != " . COURSE_INACTIVE . " OR course_user.status = " . USER_TEACHER . ") 
                        ORDER BY favorite DESC, status ASC, visible ASC, title ASC", $uid);

    $courses = [];
    if ($myCourses) {
        foreach ($myCourses as $myCourse) {
            $courses[$myCourse->code] = $myCourse->status;
        }
    }
    $_SESSION['courses'] = $courses;

    //getting user's lesson info
    $teacher_courses_count = 0;
    $student_courses_count = 0;
    if ($myCourses) {
        $lesson_content .= "<table id='portfolio_lessons' class='table table-default portfolio-courses-table'>";
        $lesson_content .= "<thead class='sr-only'><tr><th>$langCourse</th><th>$langActions</th></tr></thead>";
        foreach ($myCourses as $data) {
            array_push($lesson_ids, $data->course_id);
            $visclass = '';
            if ($data->visible == COURSE_INACTIVE) {
                $visclass = "not_visible";
            }
            if (isset($data->favorite)) {
                $favorite_icon = 'fa-star';
                $fav_status = 0;
                $fav_message = '';
            } else {
                $favorite_icon = 'fa-bookmark-o';
                $fav_status = 1;
                $fav_message = $langFavorite;
            }
            $lesson_content .= "<tr class='$visclass'>
			  <td class='border-top-0 border-start-0 border-end-0 ps-0'>
			  <div class='d-inline-flex'>
                
                    <div class='TextSemiBold'><a class='fs-6' href='${urlServer}courses/$data->code/'>" . q(ellipsize($data->title, 64)) . "</a></div>
               
                    <div class='TextSemiBold text-md-end text-start'> <span class='blackBlueText'>(" . q($data->public_code) . ")</span></div>
                
              </div>
			  <div><small>" . q($data->professor) . "</small></div></td>";
            $lesson_content .= "<td class='border-top-0 border-start-0 border-end-0 text-end align-top pe-0'><div class='col-12'><div class='d-inline-flex'>";
            $lesson_content .= icon($favorite_icon, $fav_message, "course_favorite.php?course=" . $data->code . "&amp;fav=$fav_status");
            if ($data->status == USER_STUDENT) {
                $lesson_content .= icon('fa-minus-circle ms-3', $langUnregCourse, "${urlServer}main/unregcours.php?cid=$data->course_id&amp;uid=$uid");
                $student_courses_count++;
            } elseif ($data->status == USER_TEACHER) {
                $lesson_content .= icon('fa-wrench ms-3', $langAdm, "${urlServer}modules/course_info/index.php?from_home=true&amp;course=" . $data->code, '', true, true);
                $teacher_courses_count++;
            }
            $lesson_content .= "</div></div></td></tr>";
        }
        $lesson_content .= "</tbody></table>";
    } else { // if we are not registered to courses
        $lesson_content .= "<div class='col-sm-12'><div class='alert alert-warning'>$langNotEnrolledToLessons!</div></div>";
        if ($session->status == USER_TEACHER) {
            $lesson_content .= "<div class='col-sm-12'><div class='alert alert-info'>$langWelcomeSelect $langWelcomeProfPerso</div></div>";
        } else {
            $lesson_content .= "<div class='col-sm-12'><div class='alert alert-info'>$langWelcomeSelect $langWelcomeStudPerso</div></div>";
        }
    }
    return $lesson_content;
}

/**
 * @brief get last month course announcements
 * @param $lesson_id
 * @param string $type
 * @param false $to_ajax
 * @param string $filter
 * @return array|string
 */
function getUserAnnouncements($lesson_id, $type='', $to_ajax=false, $filter='') {

    global $urlAppend, $dateFormatLong, $langAdminAn, $langNoRecentAnnounce;

    if ($type == 'more') {
        $sql_append = '';
    } else {
        $sql_append = 'LIMIT 5';
    }

    if (!empty($filter)) {
        $admin_filter_sql = 'AND admin_announcement.title LIKE ?s';
        $course_filter_sql = 'AND announcement.title LIKE ?s';
        $filter_param = '%' . $filter . '%';
    } else {
        $admin_filter_sql = $course_filter_sql = '';
        $filter_param = array();
    }

    if (!count($lesson_id)) {
        $q = Database::get()->queryArray("
                                SELECT admin_announcement.title,
                                             admin_announcement.`date` AS an_date,
                                             admin_announcement.id
                                FROM admin_announcement
                                WHERE admin_announcement.visible = 1
                                        AND (admin_announcement.begin <= " . DBHelper::timeAfter() . " OR admin_announcement.begin IS NULL)
                                        AND (admin_announcement.end >= " . DBHelper::timeAfter() . " OR admin_announcement.end IS NULL)
                                        AND admin_announcement.`date` >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) $admin_filter_sql
                                 ORDER BY an_date DESC
                         $sql_append", $filter_param);
    } else {

        $course_id_sql = implode(', ', array_fill(0, count($lesson_id), '?d'));

        $q = Database::get()->queryArray("(SELECT announcement.title,
                                             announcement.`date` AS an_date,
                                             announcement.id,
                                             announcement.content,
                                             course.code,
                                             course.title course_title
                            FROM course, course_module, announcement
                            WHERE course.id IN ($course_id_sql)
                                    AND course.id = course_module.course_id
                                    AND course.id = announcement.course_id
                                    AND announcement.visible = 1
                                    AND (announcement.start_display <= " . DBHelper::timeAfter() . " OR announcement.start_display IS NULL)
                                    AND (announcement.stop_display >= " . DBHelper::timeAfter() . " OR announcement.stop_display IS NULL)
                                    AND announcement.`date` >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                                    AND course_module.module_id = " . MODULE_ID_ANNOUNCE . "
                                    AND course_module.visible = 1 $course_filter_sql)
                            UNION
                                (SELECT admin_announcement.title,
                                             admin_announcement.`date` AS admin_an_date,
                                             admin_announcement.id, admin_announcement.body AS content, '', ''
                                FROM admin_announcement
                                WHERE admin_announcement.visible = 1
                                      AND (admin_announcement.begin <= " . DBHelper::timeAfter() . " OR admin_announcement.begin IS NULL)
                                      AND (admin_announcement.end >= " . DBHelper::timeAfter() . " OR admin_announcement.end IS NULL)
                                      AND admin_announcement.`date` >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) $admin_filter_sql
                                ) ORDER BY an_date DESC
                         $sql_append", $lesson_id, $filter_param, $filter_param);
    }

    if ($to_ajax) {
        $arr_an = array();
        foreach ($q as $arr_q) {
            $arr_an[] = $arr_q;
        }
        return $arr_an;
    } else {
        //Προσθήκη μετρητή ώστε να εμφανίζονται μέχρι 2 ανακοινώσεις σαν pagination
        // Ολες οι τελευταιες ανακοινωσεις εμφανιζονται οταν πατησει ο χρηστης το κουμπι.
        $counterAn = 0;
        $ann_content = '';
        if($q){
           $ann_content .= "<ul class='list-group list-group-flush'>";
        }
        foreach ($q as $ann) {
            if ($counterAn <= 1){
                if (isset($ann->code) & $ann->code != '') {
                    $course_title = q(ellipsize($ann->course_title, 80));
                    $ann_url = $urlAppend . 'modules/announcements/index.php?course=' . $ann->code . '&amp;an_id=' . $ann->id;
                    $ann_date = format_locale_date(strtotime($ann->an_date));
                    $ann_content .= "
                        <li class='list-group-item pt-3 pb-3 ps-0 pe-0'>
                            <div class='item-wholeline text-center'>
                                    <div class='text-title TextSemiBold fs-6'>
                                        <a href='$ann_url'>" . q(ellipsize($ann->title, 60)) . "</a>
                                    </div><br>
                                <div class='blackBlueText TextBold'>$course_title</div>
                                <div class='blackBlueText TextRegular'>$ann_date</div>
                            </div>
                        </li>";
                } else {
                    $ann_url = $urlAppend . 'main/system_announcements.php?an_id=' . $ann->id;
                    $ann_date = format_locale_date(strtotime($ann->an_date));
                    $ann_content .= "
                    <li class='list-group-item pt-3 pb-3 ps-0 pe-0'>
                        <div class='item-wholeline text-center'>
                                <div class='text-title'>
                                    <a href='$ann_url'>" . q(ellipsize($ann->title, 60)) . "</a>
                                </div><br><br>

                            <div class='blackBlueText TextBold'>$langAdminAn&nbsp; <span class='fa fa-user text-danger'></span></div>

                            <div class='blackBlueText TextRegular'>$ann_date</div>
                        </div>
                    </li>";
                }
            }
            $counterAn++;
        }
        if($q){
            $ann_content .= "</ul>";
        }
        return $ann_content;
    }
}

/**
 * @brief get user personal messages
 * @return string
 */
function getUserMessages() {

    global $uid, $urlServer, $langFrom, $dateFormatLong, $langDropboxNoMessage;

    $message_content = '';

    $mbox = new Mailbox($uid, 0);
    $msgs = $mbox->getInboxMsgs('', 5);
    $counterMs = 0;
    $message_content .= "<ul class='list-group list-group-flush'>";
    foreach ($msgs as $message) {
        if($counterMs <= 1){
            if ($message->course_id > 0) {
                $course_title = q(ellipsize(course_id_to_title($message->course_id), 30));
            } else {
                $course_title = '';
            }
            $message_date = format_locale_date($message->timestamp);
            $message_content .= "<li class='list-group-item pt-3 pb-3 ps-0 pe-0'>
                                    <div class='item-wholeline text-center'>
                                        <div class='text-title TextSemiBold'><span>$langFrom: ".display_user($message->author_id, false, false)."<br><br>
                                            <a href='{$urlServer}modules/message/index.php?mid=$message->id'>" .q($message->subject)."</a>
                                        </div>
                                        <div class='blackBlueText TextBold'>$course_title</div><br>
                                        <div class='blackBlueText TextRegular'>$message_date</div>
                                    </div>
                                </li>";
        }
        $counterMs++;
    }
    if(!$msgs){
        $message_content .= "<li class='list-group-item pt-3 pb-3 ps-0 pb-0'>
            <div class='item-wholeline text-center'>
                <div class='text-title'>
                    $langDropboxNoMessage
                </div>
            </div>
        </li>";
    }
    $message_content .= "</ul'>";
    return $message_content;
}


/**
 * @brief check if user has accepted or rejected the current privacy policy
 * @global integer $uid
 * @return boolean
 */
function user_has_accepted_policy($uid) {
    $q = Database::get()->querySingle('SELECT ts FROM user_consent
        WHERE user_id = ?d', $uid);
    if ($q and $q->ts >= get_config('privacy_policy_timestamp')) {
        return true;
    } else {
        return false;
    }
}


/*
 * @brief update user consent
 * @param $uid
 * @param bool $accept
 */
function user_accept_policy($uid, $accept = true) {
    $accept = $accept? 1: 0;
    Database::get()->query('INSERT INTO user_consent
        (has_accepted, user_id, ts) VALUES (?d, ?d, NOW())
        ON DUPLICATE KEY UPDATE has_accepted = ?d, ts = NOW()', $accept, $uid, $accept);
}


/*
 * @DIKH MOY SYNARTHSH GIA NA FTIAKSW TO PAGINATION ME TIS EIKONES TWN MATHIMATWN STO PORTFOLIO BLADE ARXEIO
*/

function getUserCoursesPic($uid){

    global $session;
    if ($session->status == USER_TEACHER) {
        $myCourses = Database::get()->queryArray("SELECT course.id course_id,
                             course.code code,
                             course.public_code,
                             course.course_image course_image,
                             course.title title,
                             course.prof_names professor,
                             course.lang,
                             course.visible,
                             course_user.status status
                       FROM course, course_user, user
                       WHERE course.id = course_user.course_id AND
                             course_user.user_id = ?d AND
                             user.id = ?d
                       ORDER BY course_user.status, course.visible, course.created DESC", $uid, $uid);
    } else {
        $myCourses = Database::get()->queryArray('SELECT course.id course_id,
                             course.code code,
                             course.public_code,
                             course.course_image course_image,
                             course.title title,
                             course.prof_names professor,
                             course.lang,
                             course.visible,
                             course_user.status status
                       FROM course, course_user, user
                       WHERE course.id = course_user.course_id AND
                             course_user.user_id = ?d AND
                             user.id = ?d AND
                             (course.visible != ' . COURSE_INACTIVE . ' OR course_user.status = ' . USER_TEACHER . ')
                       ORDER BY course.title, course.prof_names', $uid, $uid);
    }

    return $myCourses;
}
