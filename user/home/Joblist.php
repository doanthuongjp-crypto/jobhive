<?php

require_once '../../DB/dbInfo.php';
require_once '../function/save_job.php';
require_once '../function/search_job.php';
try {
    // Xử lý tham số GET
    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
    $location = isset($_GET['location']) && $_GET['location'] !== '' ? trim($_GET['location']) : 'All locations';
    $category = isset($_GET['category']) ? trim($_GET['category']) : '';
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $jobs_per_page = 5;

    // Gọi hàm search_job
    $result = search_job($keyword, $location, $category, $page, $jobs_per_page);
    $job_list = $result['jobs'];
    $total_pages = $result['total_pages'];
    $total_jobs = $result['total_jobs'];

    // Lấy danh sách job đã lưu
    $saved_job_ids = [];
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $pdo = DbInfo::connectDB();
        $stmt = $pdo->prepare("SELECT job_id FROM savedJobs WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        $saved_job_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

} catch (Exception $e) {
    $search_error = "Error: " . $e->getMessage();
    error_log("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm Việc Làm - Nền Tảng Tuyển Dụng Hàng Đầu</title>
    <link rel="stylesheet" href="../../asset/css/user/joblist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <?php include "../includes/header.php"; ?>
    <div class="search-container">
        <form action="" method="GET" class="search-box">
            <input type="text" class="search-input" id="search-input" name="keyword" placeholder="Search for jobs, companies, skills" value="<?= htmlspecialchars($keyword) ?>" />
            <select class="location-select" name="location">
                <option value="All locations" <?= $location === 'All locations' ? 'selected' : '' ?>>All locations</option>
                <?php
                    $locations = ['Hà Nội', 'TP. Hồ Chí Minh', 'Đà Nẵng', 'Hải Phòng', 'Biên Hòa', 'Cần Thơ', 'Huế', 'Vũng Tàu', 'Buôn Ma Thuột', 'Quy Nhơn', 'Nha Trang'];                foreach ($locations as $loc) {
                    echo "<option value=\"$loc\" " . ($location === $loc ? 'selected' : '') . ">$loc</option>";
                }
                ?>
            </select>
            <?php if (!empty($category)): ?>
                <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
            <?php endif; ?>
            <button class="search-icon-wrapper" type="submit">
                <span class="search-icon">Search</span>
            </button>
        </form>

        <div class="job-list">
            <?php if (isset($search_error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($search_error); ?></div>
            <?php elseif (empty($job_list)): ?>
                <p>Không tìm thấy công việc phù hợp.</p>
            <?php else: ?>
                <?php foreach ($job_list as $job): ?>
                    <?php $is_saved = in_array($job['job_id'], $saved_job_ids); ?>
                    <a href="./job_details.php?job_id=<?= htmlspecialchars($job['job_id']) ?>" class="job-details text-decoration-none job-card">
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
                            <div class="save-job">
                                <form method="POST" action="../function/save_job.php">
                                    <input type="hidden" name="job_id" value="<?= htmlspecialchars($job['job_id']) ?>">
                                    <input type="hidden" name="redirect_url" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                                    <button class="save-button" type="submit" style="background: none; border: none; cursor: pointer;">
                                        <i class="<?= $is_saved ? 'fa-solid' : 'fa-regular' ?> fa-heart save-icon" style="color: <?= $is_saved ? 'red' : 'gray' ?>;"></i>
                                    </button>
                                </form>
                            </div>
                    </a>

                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mt-4">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>" aria-label="Previous">
                                <span aria-hidden="true">Previous</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>" aria-label="Next">
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