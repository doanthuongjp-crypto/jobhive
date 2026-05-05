<?php
require_once '../../DB/dbInfo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn = DbInfo::connectDB();

        if (!isset($_SESSION['user_id'])) {
            $error = "You must be logged in to submit feedback.";
        } else {
            $user_id = $_SESSION['user_id'];
            $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
            $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';


                $sql = "INSERT INTO feedback (user_id, rating, comments) VALUES (:user_id, :rating, :comments)";
                $stmt = $conn->prepare(query: $sql);
                $stmt->execute([
                    ':user_id' => $user_id,
                    ':rating' => $rating,
                    ':comments' => $comment 
                ]);
                $success = "Thank you for your feedback!";
        }
    } catch (Exception $e) {
        $error = "An error occurred while saving your feedback.";
        error_log("Feedback Save Error: " . $e->getMessage(), 3, '../../logs/db_errors.log');
    }

    if (isset($success)) {
        echo "<script>alert('" . addslashes($success) . "');</script>";
        unset($_SESSION['alert']);
    }else{
        echo "<script>alert('" . addslashes($error) . "');</script>";
        unset($_SESSION['alert']); 
    }
}


?>
