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
$defaultCampus = (int)$userRow['campus'];
$userStmt->close();

$campusIdsParam = isset($_POST['campusIds']) ? trim($_POST['campusIds']) : '';
$selectedCampuses = array();
if ($campusIdsParam !== '') {
    $selectedCampuses = array_map('intval', explode(',', $campusIdsParam));
    $selectedCampuses = array_filter($selectedCampuses);
}
if (empty($selectedCampuses)) {
    $selectedCampuses = [$defaultCampus];
}

$campusPlaceholders = implode(',', array_fill(0, count($selectedCampuses), '?'));
$sql = "
    SELECT 
        u.userId,
        u.firstName,
        u.lastName,
        u.photo_url,
        u.campus,
        c.campusName,
        COALESCE(SUM(e.activityPoints), 0) AS totalPoints
    FROM tbl_participant p
    INNER JOIN tbl_engagement e ON p.engagementId = e.engagementId
    INNER JOIN tbl_user u ON u.userId = p.userId
    LEFT JOIN tbl_campus c ON c.campusId = u.campus
    WHERE p.isAttend = 1
        AND e.verificationStatus = 1
        AND u.campus IN ($campusPlaceholders)
    GROUP BY u.userId
    ORDER BY totalPoints DESC
";

$stmt = $db->prepare($sql);
if (!$stmt) {
    $response['success'] = false;
    $response['message'] = 'Database error: ' . $db->error;
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$types = str_repeat('i', count($selectedCampuses));
$stmt->bind_param($types, ...$selectedCampuses);
$stmt->execute();
$result = $stmt->get_result();

$leaderboard = array();
while ($row = $result->fetch_assoc()) {
    $row['userId'] = (int)$row['userId'];
    $row['campus'] = (int)$row['campus'];
    $row['totalPoints'] = (int)$row['totalPoints'];
    $leaderboard[] = $row;
}
$stmt->close();

$response['success'] = true;
$response['leaderboard'] = $leaderboard;
$response['currentUserCampus'] = $defaultCampus;

header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>