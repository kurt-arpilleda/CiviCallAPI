<?php
// civicall_get_forum_posts.php
require_once '../kurt_dbCon.php';

$response = array();

$authToken = isset($_POST['authToken']) ? trim($_POST['authToken']) : '';
if ($authToken === '') {
    $response['success'] = false;
    $response['message'] = 'Auth token is required.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$tokenStmt = $db->prepare("SELECT userId FROM tbl_userdevice WHERE authToken = ? AND isActive = 1");
$tokenStmt->bind_param("s", $authToken);
$tokenStmt->execute();
$tokenResult = $tokenStmt->get_result();

if ($tokenResult->num_rows === 0) {
    $response['success'] = false;
    $response['message'] = 'Invalid or expired token.';
    $tokenStmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$tokenRow = $tokenResult->fetch_assoc();
$currentUserId = $tokenRow['userId'];
$tokenStmt->close();

$userStmt = $db->prepare("SELECT campus FROM tbl_user WHERE userId = ?");
$userStmt->bind_param("i", $currentUserId);
$userStmt->execute();
$userResult = $userStmt->get_result();

if ($userResult->num_rows === 0) {
    $response['success'] = false;
    $response['message'] = 'User not found.';
    $userStmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$userRow = $userResult->fetch_assoc();
$userCampus = (int)$userRow['campus'];
$userStmt->close();

$query = "
    SELECT 
        f.forumId,
        f.userId,
        f.image,
        f.message,
        f.upCount,
        f.downCount,
        f.removeReason,
        f.isRemove,
        f.createdAt,
        u.firstName,
        u.lastName,
        u.photo_url,
        u.campus AS userCampus
    FROM tbl_forum f
    JOIN tbl_user u ON u.userId = f.userId
    WHERE f.isRemove = 0 AND u.campus = ?
    ORDER BY f.createdAt DESC
";

$stmt = $db->prepare($query);
$stmt->bind_param("i", $userCampus);
$stmt->execute();
$result = $stmt->get_result();

$posts = array();
while ($row = $result->fetch_assoc()) {
    $row['forumId'] = (int)$row['forumId'];
    $row['userId'] = (int)$row['userId'];
    $row['upCount'] = (int)$row['upCount'];
    $row['downCount'] = (int)$row['downCount'];
    $row['isRemove'] = (int)$row['isRemove'];
    $row['userCampus'] = (int)$row['userCampus'];
    $posts[] = $row;
}
$stmt->close();

$response['success'] = true;
$response['posts'] = $posts;

header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>