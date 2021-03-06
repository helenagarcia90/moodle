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
 * This script allows the number of sections in a course to be increased
 * or decreased, redirecting to the course page.
 *
 * @package core_course
 * @copyright 2012 Dan Poltawski
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.3
 */
global $CFG;

<<<<<<< HEAD:local/template_course/changenumsections.php
require_once(dirname(__FILE__).'/../../config.php');
require_once('lib.php');
=======
require_once(dirname(__FILE__).'/../../../config.php');
require_once('../lib.php');
>>>>>>> f52fdeb23883ea29c31758e622405aa822a294d7:local/template_course/format/changenumsections.php
require_once($CFG->dirroot. '/calendar/lib.php');

$courseid = required_param('courseid', PARAM_INT);
$sectionid = required_param('sectionid', PARAM_INT);
$delete = optional_param('delete', 0, PARAM_INT);
$increase = optional_param('increase', false, PARAM_BOOL);
$activity = optional_param('activity', false, PARAM_BOOL);
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
$courseformatoptions = course_get_format($course)->get_format_options();

$PAGE->set_url('/local/template_course/changenumsections.php', array('courseid' => $courseid, 'sesskey' => sesskey()));

// Authorisation checks.
require_login($course);
require_capability('moodle/course:update', context_course::instance($course->id));
require_sesskey();

global $DB;

 if ($increase) {
        // Add an additional section.
        $courseformatoptions['numsections']++;
        $cw = new stdClass();
        $cw->course   = $courseid;
        $cw->section  = $sectionid;
        $cw->summary  = '';
        $cw->summaryformat = FORMAT_HTML;
        $cw->sequence = '';
<<<<<<< HEAD:local/template_course/changenumsections.php

        if($activity){
            $cw->name = "Évaluation";
            $cw->showavailability = 1;
        }

        $id = $DB->insert_record("course_sections", $cw);

=======
        if($activity){
            $cw->name = "&Eacute;valuation";
        }
        else {
            $event = new stdClass;
            $event->name         = 'Section '.$sectionid;
            $event->description  = '';
            $event->courseid     = $courseid;
            $event->groupid      = 0;
            $event->userid       = 0;
            $event->modulename   = '';
            $event->instance     = $sectionid;
            $event->eventtype    = 'due'; // For activity module's events, this can be used to set the alternative text of the event icon. Set it to 'pluginname' unless you have a better string.
            $date = usergetdate(time());
            list($d, $m, $y) = array($date['mday'], $date['mon'], $date['year']);            
            $event->timestart    = make_timestamp($y, $m, 1);
            $event->visible      = 1;
            $event->timeduration = 20000000;
            calendar_event::create($event);
        }
        $id = $DB->insert_record("course_sections", $cw);
>>>>>>> f52fdeb23883ea29c31758e622405aa822a294d7:local/template_course/format/changenumsections.php
 } else if($delete && isset($courseformatoptions['numsections'])){
        $DB->delete_records('course_sections',array('course' => $courseid, 'section' => $sectionid));
        // Remove a section.
        $courseformatoptions['numsections']--;
 }

 // Don't go less than 0, intentionally redirect silently (for the case of
 // double clicks).
 if ($courseformatoptions['numsections'] >= 0) {
     //var_dump( course_get_format($course));
     course_get_format($course)->update_course_format_options(
             array('numsections' => $courseformatoptions['numsections']));
 }

$url = new moodle_url("/local/template_course/view.php", array('id'=> $course->id, 'sesskey' => sesskey()));
// Redirect to where we were..
redirect($url);
