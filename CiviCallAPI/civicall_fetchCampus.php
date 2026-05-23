<?php
require_once '../kurt_dbCon.php';

$response = array();

$query = "SELECT campusId, campusName FROM tbl_campus ORDER BY campusName ASC";
$result = $db->query($query);

if ($result) {
    $campuses = array();
    while ($row = $result->fetch_assoc()) {
        $campuses[] = array(
            'campusId' => (int)$row['campusId'],
            'campusName' => $row['campusName']
        );
    }
    $response['success'] = true;
    $response['campuses'] = $campuses;
} else {
    $response['success'] = false;
    $response['message'] = 'Failed to fetch campuses';
}

header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>