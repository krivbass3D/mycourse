<?php

$observers = [

    [
        'eventname'   => '\core\event\user_enrolment_created',
        'callback'    => 'block_msmycourses2_observer::user_enrolment_created',
    ],
    // array(
    //     'eventname'   => '\core\event\course_completed',
    //     'callback'    => 'block_msmycourses2_observer::course_completed',
    // ),
    [
        'eventname'   => '\core\event\course_module_completion_updated',
        'callback'    => 'block_msmycourses2_observer::course_module_completion_updated',
    ],
    [
        'eventname'   => '\core\event\user_enrolment_deleted',
        'callback'    => 'block_msmycourses2_observer::user_enrolment_deleted',
    ],

    [
        'eventname'   => '\core\event\groups_member_added',
        'callback'    => 'block_msmycourses2_observer::groups_member_added',
    ],

    [
        'eventname'   => '\core\event\groups_member_removed',
        'callback'    => 'block_msmycourses2_observer::groups_member_removed',
    ],
    [
        'eventname'   => '\core\event\course_deleted',
        'callback'    => 'block_msmycourses2_observer::course_deleted',
    ],
    [
        'eventname'   => '\core\event\course_updated',
        'callback'    => 'block_msmycourses2_observer::course_updated',
    ],

];
