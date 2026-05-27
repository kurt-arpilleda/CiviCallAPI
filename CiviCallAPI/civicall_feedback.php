<?php
require_once '../kurt_dbCon.php';

$response = array();

$authToken = isset($_POST['authToken']) ? trim($_POST['authToken']) : '';
$action = isset($_POST['action']) ? trim($_POST['action']) : '';

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

if ($action === 'get') {
    $getStmt = $db->prepare("SELECT starNum, feedBack, dateTime FROM tbl_feedback WHERE userId = ? ORDER BY dateTime DESC LIMIT 1");
    $getStmt->bind_param("i", $userId);
    $getStmt->execute();
    $getResult = $getStmt->get_result();

    if ($getResult->num_rows > 0) {
        $row = $getResult->fetch_assoc();
        $response['success'] = true;
        $response['data'] = $row;
    } else {
        $response['success'] = true;
        $response['data'] = null;
    }
    $getStmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

if ($action === 'send') {
    $starNum = isset($_POST['starNum']) ? (float)$_POST['starNum'] : 0;
    $feedBack = isset($_POST['feedBack']) ? trim($_POST['feedBack']) : '';

    if ($starNum <= 0 || $starNum > 5) {
        $response['success'] = false;
        $response['message'] = 'Invalid star rating.';
        header('Content-Type: application/json');
        echo json_encode($response);
        $db->close();
        exit;
    }

    if ($feedBack === '') {
        $response['success'] = false;
        $response['message'] = 'Feedback text is required.';
        header('Content-Type: application/json');
        echo json_encode($response);
        $db->close();
        exit;
    }

    $checkStmt = $db->prepare("SELECT feedbackId, dateTime FROM tbl_feedback WHERE userId = ? ORDER BY dateTime DESC LIMIT 1");
    $checkStmt->bind_param("i", $userId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $existing = $checkResult->fetch_assoc();
        $lastDateTime = $existing['dateTime'];
        $checkStmt->close();

        $lastTimestamp = strtotime($lastDateTime);
        $oneWeekAgo = strtotime('-7 days');

        if ($lastTimestamp > $oneWeekAgo) {
            $response['success'] = false;
            $response['message'] = 'You can only send feedback once per week.';
            header('Content-Type: application/json');
            echo json_encode($response);
            $db->close();
            exit;
        }

        $updateStmt = $db->prepare("UPDATE tbl_feedback SET starNum = ?, feedBack = ?, dateTime = NOW() WHERE userId = ?");
        $updateStmt->bind_param("dsi", $starNum, $feedBack, $userId);

        if ($updateStmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Feedback updated successfully.';
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to update feedback.';
        }
        $updateStmt->close();
    } else {
        $checkStmt->close();

        $insertStmt = $db->prepare("INSERT INTO tbl_feedback (userId, starNum, feedBack, dateTime) VALUES (?, ?, ?, NOW())");
        $insertStmt->bind_param("ids", $userId, $starNum, $feedBack);

        if ($insertStmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Feedback submitted successfully.';
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to submit feedback.';
        }
        $insertStmt->close();
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$response['success'] = false;
$response['message'] = 'Invalid action.';
header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>