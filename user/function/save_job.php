<?php
session_start();
require_once '../../DB/dbInfo.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Please log in to save jobs.";
        $redirect_url = $_POST['redirect_url'] ?? '../home/Joblist.php';
        header('Location: ' . $redirect_url);
        exit;
    }


    $user_id = $_SESSION['user_id'];
    $job_id = $_POST['job_id'] ?? null;

    if (!$job_id || !is_numeric($job_id)) {
        $_SESSION['error'] = "Invalid job ID.";
        $redirect_url = $_POST['redirect_url'] ?? '../home/Joblist.php';
        header('Location: ' . $redirect_url);
        exit;
    }

    try {
        $conn = DbInfo::connectDB();

        // Kiểm tra job đã lưu chưa
        $stmt = $conn->prepare("SELECT COUNT(*) FROM SavedJobs WHERE user_id = :user_id AND job_id = :job_id");
        $stmt->execute([
            'user_id' => $user_id,
            'job_id' => $job_id
        ]);
        $is_saved = $stmt->fetchColumn() > 0;

        if ($is_saved) {
            // Xóa job đã lưu
            $deleteStmt = $conn->prepare("DELETE FROM SavedJobs WHERE user_id = :user_id AND job_id = :job_id");
            $deleteStmt->execute([
                'user_id' => $user_id,
                'job_id' => $job_id
            ]);
        } else {
            // Lưu job mới
            $insertStmt = $conn->prepare("INSERT INTO SavedJobs (user_id, job_id) VALUES (:user_id, :job_id)");
            $insertStmt->execute([
                'user_id' => $user_id,
                'job_id' => $job_id
            ]);
        }

        // Chuyển hướng
        $redirect_url = $_POST['redirect_url'] ?? '../home/Joblist.php';
        header('Location: ' . $redirect_url);
        exit;

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>