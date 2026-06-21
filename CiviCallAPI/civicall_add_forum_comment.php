<?php
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
$userId = $tokenRow['userId'];
$tokenStmt->close();

$userStmt = $db->prepare("SELECT isVerified FROM tbl_user WHERE userId = ?");
$userStmt->bind_param("i", $userId);
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
$isVerified = (int)$userRow['isVerified'];
$userStmt->close();

if ($isVerified !== 1) {
    $response['success'] = false;
    $response['message'] = 'Only verified users can comment.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$forumId = isset($_POST['forumId']) ? (int)$_POST['forumId'] : 0;
$commentText = isset($_POST['commentText']) ? trim($_POST['commentText']) : '';

if ($forumId <= 0) {
    $response['success'] = false;
    $response['message'] = 'Forum ID is required.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

if ($commentText === '') {
    $response['success'] = false;
    $response['message'] = 'Comment text is required.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$forumStmt = $db->prepare("SELECT forumId FROM tbl_forum WHERE forumId = ? AND isRemove = 0");
$forumStmt->bind_param("i", $forumId);
$forumStmt->execute();
$forumResult = $forumStmt->get_result();

if ($forumResult->num_rows === 0) {
    $response['success'] = false;
    $response['message'] = 'Forum post not found.';
    $forumStmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}
$forumStmt->close();

$insertStmt = $db->prepare("INSERT INTO tbl_forumcomment (forumId, userId, commentText, createdAt) VALUES (?, ?, ?, NOW())");
$insertStmt->bind_param("iis", $forumId, $userId, $commentText);

if (!$insertStmt->execute()) {
    $response['success'] = false;
    $response['message'] = 'Failed to add comment: ' . $insertStmt->error;
    $insertStmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$commentId = $insertStmt->insert_id;
$insertStmt->close();

$updateCountStmt = $db->prepare("UPDATE tbl_forum SET commentCount = commentCount + 1 WHERE forumId = ?");
$updateCountStmt->bind_param("i", $forumId);
$updateCountStmt->execute();
$updateCountStmt->close();

$countStmt = $db->prepare("SELECT commentCount FROM tbl_forum WHERE forumId = ?");
$countStmt->bind_param("i", $forumId);
$countStmt->execute();
$countResult = $countStmt->get_result();
$countRow = $countResult->fetch_assoc();
$commentCount = (int)$countRow['commentCount'];
$countStmt->close();

$commentStmt = $db->prepare("
    SELECT 
        fc.commentId,
        fc.forumId,
        fc.userId,
        fc.commentText,
        fc.createdAt,
        u.firstName,
        u.lastName,
        u.photo_url,
        c.campusName
    FROM tbl_forumcomment fc
    JOIN tbl_user u ON u.userId = fc.userId
    LEFT JOIN tbl_campus c ON c.campusId = u.campus
    WHERE fc.commentId = ?
");
$commentStmt->bind_param("i", $commentId);
$commentStmt->execute();
$commentResult = $commentStmt->get_result();
$commentRow = $commentResult->fetch_assoc();
$commentStmt->close();

$commentRow['commentId'] = (int)$commentRow['commentId'];
$commentRow['forumId'] = (int)$commentRow['forumId'];
$commentRow['userId'] = (int)$commentRow['userId'];

$response['success'] = true;
$response['message'] = 'Comment added successfully.';
$response['comment'] = $commentRow;
$response['commentCount'] = $commentCount;

header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>