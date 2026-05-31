<?php
require_once '../kurt_dbCon.php';

$response = array();

$query = "SELECT categoryId, categoryName FROM tbl_engagementcategory ORDER BY categoryName ASC";
$result = $db->query($query);

if ($result) {
    $categories = array();
    while ($row = $result->fetch_assoc()) {
        $categories[] = array(
            'categoryId'   => (int)$row['categoryId'],
            'categoryName' => $row['categoryName']
        );
    }
    $response['success']    = true;
    $response['categories'] = $categories;
} else {
    $response['success'] = false;
    $response['message'] = 'Failed to fetch categories.';
}

header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>