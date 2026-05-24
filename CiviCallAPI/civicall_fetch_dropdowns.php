<?php
require_once '../kurt_dbCon.php';

$response = array();

$campusQuery = "SELECT campusId, campusName FROM tbl_campus ORDER BY campusName ASC";
$campusResult = $db->query($campusQuery);
$campuses = array();
if ($campusResult) {
    while ($row = $campusResult->fetch_assoc()) {
        $campuses[] = array('id' => (int)$row['campusId'], 'name' => $row['campusName']);
    }
}

$deptQuery = "SELECT departmentId, departmentName FROM tbl_department ORDER BY departmentName ASC";
$deptResult = $db->query($deptQuery);
$departments = array();
if ($deptResult) {
    while ($row = $deptResult->fetch_assoc()) {
        $departments[] = array('id' => (int)$row['departmentId'], 'name' => $row['departmentName']);
    }
}

$courseQuery = "SELECT courseId, courseName FROM tbl_course ORDER BY courseName ASC";
$courseResult = $db->query($courseQuery);
$courses = array();
if ($courseResult) {
    while ($row = $courseResult->fetch_assoc()) {
        $courses[] = array('id' => (int)$row['courseId'], 'name' => $row['courseName']);
    }
}

$nstpQuery = "SELECT nstpId, nstpType FROM tbl_nstp ORDER BY nstpType ASC";
$nstpResult = $db->query($nstpQuery);
$nstpList = array();
if ($nstpResult) {
    while ($row = $nstpResult->fetch_assoc()) {
        $nstpList[] = array('id' => (int)$row['nstpId'], 'name' => $row['nstpType']);
    }
}

$response['success'] = true;
$response['campuses'] = $campuses;
$response['departments'] = $departments;
$response['courses'] = $courses;
$response['nstp'] = $nstpList;

header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>