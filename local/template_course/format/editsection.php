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
 * Edit the section basic information and availability
 *
 * @copyright 1999 Martin Dougiamas  http://dougiamas.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package course
 */

global $CFG, $PAGE, $DB;

require_once("../../../config.php");
require_once("../lib.php");
require_once($CFG->libdir . '/formslib.php');
require_once($CFG->libdir . '/conditionlib.php');
<<<<<<< HEAD
require_once($CFG->libdir.'/filelib.php');
=======
>>>>>>> f52fdeb23883ea29c31758e622405aa822a294d7

$id = required_param('id', PARAM_INT);    // course_sections.id
$sectionreturn = optional_param('sr', 0, PARAM_INT);

$PAGE->set_url('/local/template_course/format/editsection.php', array('id'=>$id, 'sr'=> $sectionreturn));

$section = $DB->get_record('course_sections', array('id' => $id), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id' => $section->course), '*', MUST_EXIST);
$sectionnum = $section->section;

require_login($course);
$context = context_course::instance($course->id);
require_capability('moodle/course:update', $context);

// Get section_info object with all availability options.
$sectioninfo = get_fast_modinfo($course)->get_section_info($sectionnum);

$editoroptions = array('context'=>$context ,'maxfiles' => EDITOR_UNLIMITED_FILES, 'maxbytes'=>$CFG->maxbytes, 'trusttext'=>false, 'noclean'=>true);

//section format editor
require_once('editsection_form.php');

$customdata = array('cs' => $sectioninfo, 'editoroptions' => $editoroptions);

if (!array_key_exists('course', $customdata)) {
            $customdata['course'] = $course;
}
$mform = new editsection_template_form($PAGE->url, $customdata);

// set current value, make an editable copy of section_info object
// this will retrieve all format-specific options as well
$mform->set_data(convert_to_array($sectioninfo));
<<<<<<< HEAD
=======
print 'fff';
>>>>>>> f52fdeb23883ea29c31758e622405aa822a294d7

if ($mform->is_cancelled()){
    // Form cancelled, return to course.
    redirect(course_get_url($course, $section, array('sr' => $sectionreturn)));
} else if ($data = $mform->get_data()) {
<<<<<<< HEAD
    // Data submitted and validated, update and return to course.  

=======
    // Data submitted and validated, update and return to course.
    print 'edit';
>>>>>>> f52fdeb23883ea29c31758e622405aa822a294d7
    $DB->update_record('course_sections', $data);
    rebuild_course_cache($course->id, true);
    if (isset($data->section)) {
        // Usually edit form does not change relative section number but just in case.
        $sectionnum = $data->section;
    }
    if (!empty($CFG->enableavailability)) {
        // Update grade and completion conditions.
        $sectioninfo = get_fast_modinfo($course)->get_section_info($sectionnum);
        condition_info_section::update_section_from_form($sectioninfo, $data);
        rebuild_course_cache($course->id, true);
    }
    course_get_format($course->id)->update_section_format_options($data);

    // Set section info, as this might not be present in form_data.
    if (!isset($data->section))  {
        $data->section = $sectionnum;
    }
    // Trigger an event for course section update.
<<<<<<< HEAD
    /*$event = \core\event\course_section_updated::create(
=======
    $event = \core\event\course_section_updated::create(
>>>>>>> f52fdeb23883ea29c31758e622405aa822a294d7
            array(
                'objectid' => $data->id,
                'courseid' => $course->id,
                'context' => $context,
                'other' => array('sectionnum' => $data->section)
            )
        );
<<<<<<< HEAD
    $event->trigger();*/
=======
    $event->trigger();
>>>>>>> f52fdeb23883ea29c31758e622405aa822a294d7
    
    //create or update event with section dates info
    require_once($CFG->dirroot. '/calendar/lib.php');

<<<<<<< HEAD
=======
    $event = new stdClass;
            $event->name         = 'Section '.$data->section;
            $event->description  = '';
            $event->courseid     = $course->id;
            $event->groupid      = 0;
            $event->userid       = 0;
            $event->modulename   = '';
            $event->instance     = $data->section;
            $event->eventtype    = 'course'; // For activity module's events, this can be used to set the alternative text of the event icon. Set it to 'pluginname' unless you have a better string.
            $date = usergetdate(time());
            list($d, $m, $y) = array($date['mday'], $date['mon'], $date['year']);            
            $event->timestart    = make_timestamp($y, $m, 1);
            $event->visible      = 1;
            $event->timeduration = $data->availablefrom*24*3600;
            if($existentevent = $DB->get_record('event', array('courseid' =>$course->id, 'instance' => $data->section))){
                $event->id = $existentevent->id;
                $DB->update_record('event', $event);
                print 'ttt';
            }else{
                calendar_event::create($event);
            }
>>>>>>> f52fdeb23883ea29c31758e622405aa822a294d7
    $PAGE->navigation->clear_cache();
    redirect(course_get_url($course, $section, array('sr' => $sectionreturn)));
}

// The edit form is displayed for the first time or if there was validation error on the previous step.
$sectionname  = get_section_name($course, $sectionnum);

$stredit      = get_string('edita', '', " $sectionname");
$strsummaryof = get_string('summaryof', '', " $sectionname");

$PAGE->set_title($stredit);
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add($stredit);
echo $OUTPUT->header();
echo $OUTPUT->heading($strsummaryof);
$mform->display();
echo $OUTPUT->footer();
