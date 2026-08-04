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

$commentId = isset($_POST['commentId']) ? (int)$_POST['commentId'] : 0;

if ($commentId <= 0) {
    $response['success'] = false;
    $response['message'] = 'Comment ID is required.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$commentStmt = $db->prepare("SELECT userId, forumId FROM tbl_forumcomment WHERE commentId = ? AND isRemove = 0");
$commentStmt->bind_param("i", $commentId);
$commentStmt->execute();
$commentResult = $commentStmt->get_result();

if ($commentResult->num_rows === 0) {
    $response['success'] = false;
    $response['message'] = 'Comment not found.';
    $commentStmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$commentRow = $commentResult->fetch_assoc();
$commentStmt->close();

if ((int)$commentRow['userId'] !== (int)$userId) {
    $response['success'] = false;
    $response['message'] = 'You can only delete your own comment.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$forumId = (int)$commentRow['forumId'];

$removeStmt = $db->prepare("UPDATE tbl_forumcomment SET isRemove = 1 WHERE commentId = ? AND userId = ?");
$removeStmt->bind_param("ii", $commentId, $userId);

if (!$removeStmt->execute()) {
    $response['success'] = false;
    $response['message'] = 'Failed to delete comment: ' . $removeStmt->error;
    $removeStmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}
$removeStmt->close();

$updateCountStmt = $db->prepare("UPDATE tbl_forum SET commentCount = GREATEST(commentCount - 1, 0) WHERE forumId = ?");
$updateCountStmt->bind_param("i", $forumId);
$updateCountStmt->execute();
$updateCountStmt->close();

$countStmt = $db->prepare("SELECT commentCount FROM tbl_forum WHERE forumId = ?");
$countStmt->bind_param("i", $forumId);
$countStmt->execute();
$countResult = $countStmt->get_result();
$commentCount = 0;
if ($countResult->num_rows > 0) {
    $countRow = $countResult->fetch_assoc();
    $commentCount = (int)$countRow['commentCount'];
}
$countStmt->close();

$response['success'] = true;
$response['message'] = 'Comment deleted successfully.';
$response['commentId'] = $commentId;
$response['forumId'] = $forumId;
$response['commentCount'] = $commentCount;

header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>