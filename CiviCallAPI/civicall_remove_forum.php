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

$forumId = isset($_POST['forumId']) ? (int)$_POST['forumId'] : 0;

if ($forumId <= 0) {
    $response['success'] = false;
    $response['message'] = 'Forum ID is required.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$forumStmt = $db->prepare("SELECT userId FROM tbl_forum WHERE forumId = ? AND isRemove = 0");
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

$forumRow = $forumResult->fetch_assoc();
$forumStmt->close();

if ((int)$forumRow['userId'] !== (int)$userId) {
    $response['success'] = false;
    $response['message'] = 'You can only delete your own post.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$removeStmt = $db->prepare("UPDATE tbl_forum SET isRemove = 1 WHERE forumId = ? AND userId = ?");
$removeStmt->bind_param("ii", $forumId, $userId);

if (!$removeStmt->execute()) {
    $response['success'] = false;
    $response['message'] = 'Failed to delete post: ' . $removeStmt->error;
    $removeStmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}
$removeStmt->close();

$response['success'] = true;
$response['message'] = 'Post deleted successfully.';
$response['forumId'] = $forumId;

header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>