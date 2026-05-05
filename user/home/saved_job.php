<?php
require_once '../../DB/dbInfo.php';
require_once '../function/save_job.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}



try {
    // Kết nối database
    $conn = DbInfo::connectDB();
    if (!$conn) {
        throw new Exception('Can not connect to the database.');
    }

    // Xử lý phân trang
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $jobs_per_page = 5;
    $offset = ($page - 1) * $jobs_per_page;
    $user_id = $_SESSION['user_id'];

    // Đếm tổng số job đã lưu
    $stmt = $conn->prepare("SELECT COUNT(*) FROM SavedJobs WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $total_jobs = $stmt->fetchColumn();
    $total_pages = ceil($total_jobs / $jobs_per_page);

    // Lấy danh sách job đã lưu
    $stmt = $conn->prepare("
        SELECT j.job_id, j.title, j.salary, j.location, j.date_posted, c.company_name, c.logo_path
        FROM SavedJobs sj
        JOIN Jobs j ON sj.job_id = j.job_id
        JOIN Companies c ON j.company_id = c.company_id
        WHERE sj.user_id = :user_id
        LIMIT :offset, :limit
    ");
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $jobs_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $saved_jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $error = "Error: " . $e->getMessage();
    error_log("Saved_job error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Jobs - Leading Recruitment Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../asset/css/user/joblist.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include "../includes/header.php"; ?>

    <div class="search-container">
        <h1 class="text-center mb-4">Saved Jobs</h1>
        <div class="job-list">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php elseif (empty($saved_jobs)): ?>
                <p class="text-center">No saved jobs found.</p>
            <?php else: ?>
                <?php foreach ($saved_jobs as $job): ?>
                    <div class="job-card">
                        <a href="job_details.php?job_id=<?= htmlspecialchars($job['job_id']) ?>" class="job-details text-decoration-none">
                            <div class="company-logo">
                                <img src="<?= htmlspecialchars($job['logo_path']) ?>" class="company-logo" alt="Company Logo" />
                            </div>
                            <div>
                                <div class="job-header">
                                    <h1 class="job-title"><?= htmlspecialchars($job['title']) ?></h1>
                                </div>
                                <div class="company-info">
                                    <div>
                                        <div class="company-name"><?= htmlspecialchars($job['company_name']) ?></div>
                                        <div class="salary-location"><?= htmlspecialchars($job['salary'] . " | " . $job['location']) ?></div>
                                        <div class="post-date"><?= htmlspecialchars($job['date_posted']) ?></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="save-job">
                            <form method="POST" action="../function/save_job.php">
                                <input type="hidden" name="job_id" value="<?= htmlspecialchars($job['job_id']) ?>">
                                <input type="hidden" name="redirect_url" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                                <button class="save-button" type="submit" style="background: none; border: none; cursor: pointer;">
                                    <i class="fa-solid fa-heart save-icon" style="color: red;"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mt-4">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                                <span aria-hidden="true">Previous</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                                <span aria-hidden="true">Next</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>