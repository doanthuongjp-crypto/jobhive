<?php
require_once '../../DB/dbInfo.php';

function get_all_feedback($limit = 50) {
    try {
        $conn = DbInfo::connectDB();
        $sql = "SELECT `rating`, `comments` FROM feedback ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $feedbacks;
    } catch (Exception $e) {
        echo $e->getMessage();
        return [];
    }
}
?>
