<?php
function logAdminAction($db, $adminType, $logType) {
    $stmt = $db->prepare("INSERT INTO tbl_adminlog (adminType, logType, logStamp) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $adminType, $logType);
    $stmt->execute();
    $stmt->close();
}