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
$currentUserId = $tokenRow['userId'];
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

$postStmt = $db->prepare("
    SELECT 
        f.forumId,
        f.userId,
        f.image,
        f.message,
        f.campus,
        f.upCount,
        f.downCount,
        f.commentCount,
        f.removeReason,
        f.isRemove,
        f.createdAt,
        u.firstName,
        u.lastName,
        u.photo_url,
        u.campus AS userCampus,
        c.campusName,
        v.voteType AS userVoteType
    FROM tbl_forum f
    JOIN tbl_user u ON u.userId = f.userId
    LEFT JOIN tbl_campus c ON c.campusId = u.campus
    LEFT JOIN tbl_forum_votes v ON v.forumId = f.forumId AND v.userId = ?
    WHERE f.forumId = ? AND f.isRemove = 0
");
$postStmt->bind_param("ii", $currentUserId, $forumId);
$postStmt->execute();
$postResult = $postStmt->get_result();

if ($postResult->num_rows === 0) {
    $response['success'] = false;
    $response['message'] = 'Forum post not found.';
    $postStmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$post = $postResult->fetch_assoc();
$postStmt->close();

$post['forumId'] = (int)$post['forumId'];
$post['userId'] = (int)$post['userId'];
$post['upCount'] = (int)$post['upCount'];
$post['downCount'] = (int)$post['downCount'];
$post['commentCount'] = (int)$post['commentCount'];
$post['isRemove'] = (int)$post['isRemove'];
$post['userCampus'] = (int)$post['userCampus'];
$post['userVoteType'] = $post['userVoteType'] !== null ? (int)$post['userVoteType'] : null;

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
    WHERE fc.forumId = ?
    ORDER BY fc.createdAt ASC
");
$commentStmt->bind_param("i", $forumId);
$commentStmt->execute();
$commentResult = $commentStmt->get_result();

$comments = array();
while ($row = $commentResult->fetch_assoc()) {
    $row['commentId'] = (int)$row['commentId'];
    $row['forumId'] = (int)$row['forumId'];
    $row['userId'] = (int)$row['userId'];
    $comments[] = $row;
}
$commentStmt->close();

$response['success'] = true;
$response['post'] = $post;
$response['comments'] = $comments;

header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>