<?php
// civicall_vote_forum.php
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
    $response['message'] = 'Only verified users can vote.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$forumId = isset($_POST['forumId']) ? (int)$_POST['forumId'] : 0;
$voteType = isset($_POST['voteType']) ? (int)$_POST['voteType'] : -1;

if ($forumId <= 0) {
    $response['success'] = false;
    $response['message'] = 'Forum ID is required.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

if ($voteType !== 0 && $voteType !== 1) {
    $response['success'] = false;
    $response['message'] = 'Invalid vote type.';
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

$checkStmt = $db->prepare("SELECT voteType FROM tbl_forum_votes WHERE forumId = ? AND userId = ?");
$checkStmt->bind_param("ii", $forumId, $userId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    $existing = $checkResult->fetch_assoc();
    $existingVote = (int)$existing['voteType'];
    $checkStmt->close();

    if ($existingVote === $voteType) {
        $deleteStmt = $db->prepare("DELETE FROM tbl_forum_votes WHERE forumId = ? AND userId = ?");
        $deleteStmt->bind_param("ii", $forumId, $userId);
        $deleteStmt->execute();
        $deleteStmt->close();
    } else {
        $updateStmt = $db->prepare("UPDATE tbl_forum_votes SET voteType = ? WHERE forumId = ? AND userId = ?");
        $updateStmt->bind_param("iii", $voteType, $forumId, $userId);
        $updateStmt->execute();
        $updateStmt->close();
    }
} else {
    $checkStmt->close();
    $insertStmt = $db->prepare("INSERT INTO tbl_forum_votes (forumId, userId, voteType) VALUES (?, ?, ?)");
    $insertStmt->bind_param("iii", $forumId, $userId, $voteType);
    $insertStmt->execute();
    $insertStmt->close();
}

$upStmt = $db->prepare("SELECT COUNT(*) as upCount FROM tbl_forum_votes WHERE forumId = ? AND voteType = 1");
$upStmt->bind_param("i", $forumId);
$upStmt->execute();
$upResult = $upStmt->get_result();
$upRow = $upResult->fetch_assoc();
$upCount = (int)$upRow['upCount'];
$upStmt->close();

$downStmt = $db->prepare("SELECT COUNT(*) as downCount FROM tbl_forum_votes WHERE forumId = ? AND voteType = 0");
$downStmt->bind_param("i", $forumId);
$downStmt->execute();
$downResult = $downStmt->get_result();
$downRow = $downResult->fetch_assoc();
$downCount = (int)$downRow['downCount'];
$downStmt->close();

$updateForumStmt = $db->prepare("UPDATE tbl_forum SET upCount = ?, downCount = ? WHERE forumId = ?");
$updateForumStmt->bind_param("iii", $upCount, $downCount, $forumId);
$updateForumStmt->execute();
$updateForumStmt->close();

$userVoteStmt = $db->prepare("SELECT voteType FROM tbl_forum_votes WHERE forumId = ? AND userId = ?");
$userVoteStmt->bind_param("ii", $forumId, $userId);
$userVoteStmt->execute();
$userVoteResult = $userVoteStmt->get_result();
$userVote = null;
if ($userVoteResult->num_rows > 0) {
    $userVoteRow = $userVoteResult->fetch_assoc();
    $userVote = (int)$userVoteRow['voteType'];
}
$userVoteStmt->close();

$response['success'] = true;
$response['message'] = 'Vote updated successfully.';
$response['upCount'] = $upCount;
$response['downCount'] = $downCount;
$response['userVote'] = $userVote;

header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>