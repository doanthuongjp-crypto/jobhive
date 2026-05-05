<?php
// KẾT NỐI DATABASE
require_once '../../DB/dbInfo.php';
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login/login.php');
    exit;
}

try {
    $conn = DbInfo::connectDB();
    if (!$conn) {
        throw new PDOException('Can not connect to the database.');
    }
} catch (PDOException $e) {
    error_log("Connect Failed: " . $e->getMessage());
    exit("Connection failed: " . htmlspecialchars($e->getMessage()));
}

// HÀM TRỢ GIÚP
function fetchColumn($conn, $sql, $params = [])
{
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("fetchColumn error: " . $e->getMessage());
        return null;
    }
}

function fetchAllRows($conn, $sql, $params = [])
{
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("fetchAllRows error: " . $e->getMessage());
        return [];
    }
}

// XỬ LÝ JOB ID
$job_id = isset($_GET['job_id']) ? (int)$_GET['job_id'] : null;
if (!$job_id || $job_id <= 0) {
    exit("Invalid Job ID.");
}
// LẤY DỮ LIỆU
$job = fetchAllRows($conn, "SELECT j.*, c.company_name, c.logo_path 
    FROM jobs j 
    JOIN Companies c ON j.company_id = c.company_id 
    WHERE j.job_id = ?", [$job_id])[0] ?? null;
if (!$job) {
    exit("Job not found.");
}

// Kiểm tra trạng thái lưu công việc
$is_saved = fetchColumn($conn, "SELECT COUNT(*) FROM SavedJobs WHERE user_id = ? AND job_id = ?", 
    [$_SESSION['user_id'], $job_id]) > 0;

// Giả lập dữ liệu
$requirements = explode("\n", $job['qualifications'] ?? '');
$perks = array_filter(explode(",", $job['perks'] ?? '')); // Tách perks
$related_jobs = fetchAllRows($conn, "SELECT j.job_id, j.title, j.location, j.salary, j.job_type, j.date_posted, c.company_name 
    FROM jobs j 
    JOIN Companies c ON j.company_id = c.company_id 
    WHERE j.company_id = ? AND j.job_id != ? 
    LIMIT 3", [$job['company_id'], $job_id]);

function e($str)
{
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}


// Include header
require_once '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($job['title']) ?> - Job Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../css/employer/joblist.css">
    <style>
        .job-details {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        h1, h2 {
            color: #2c3e50;
        }
        ul {
            padding-left: 20px;
        }
        .tag {
            display: inline-block;
            background: #eee;
            padding: 5px 10px;
            margin: 3px;
            border-radius: 5px;
        }
        .job-card {
            margin-bottom: 20px;
            transition: transform 0.2s;
        }
        .job-card:hover {
            transform: translateY(-5px);
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="job-details">
        <div class="action-buttons">
            <a href="./Joblist.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Return
            </a>
            <div class="ms-auto">
                <form method="POST" action="../function/save_job.php" class="d-inline">
                    <input type="hidden" name="job_id" value="<?= e($job['job_id']) ?>">
                    <input type="hidden" name="redirect_url" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                    <button type="submit" class="btn btn-light" style="text-decoration: none;">
                        Save Job
                        <i class="fa-heart fa-lg <?= $is_saved ? 'fa-solid' : 'fa-regular' ?>" style="color: <?= $is_saved ? 'red' : 'grey' ?>;"></i>
                    </button>
                </form>
                <a href="./apply_form.php?>" class="btn btn-primary">
                    Apply Now
                </a>
            </div>
        </div>
        <h1><?= e($job['title']) ?></h1>
        <p><strong>Company:</strong> <?= e($job['company_name']) ?></p>
        <p><strong>Location:</strong> <?= e($job['location']) ?> | <strong>Salary:</strong> <?= e($job['salary']) ?></p>
        <p><strong>Industry:</strong> <?= e($job['category']) ?> | <strong>Job Type:</strong> <?= e($job['job_type']) ?></p>
        <p><strong>Level:</strong> <?= e($job['experience_level']) ?> | <strong>Work Mode:</strong> <?= e($job['remote_option']) ?></p>
        <p><strong>Posted Date:</strong> <?= e($job['date_posted']) ?> | <strong>Application Deadline:</strong> <?= e($job['application_deadline']) ?></p>

        <h2>Job Description</h2>
        <p><?= nl2br(e($job['description'])) ?></p>

        <h2>Requirements</h2>
        <ul>
            <?php foreach ($requirements as $r): ?>
                <?php if (trim($r)): ?>
                    <li><?= e($r) ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>

        <h2>Perks</h2>
        <ul>
            <?php foreach ($perks as $p): ?>
                <li><?= e($p) ?></li>
            <?php endforeach; ?>
        </ul>

        <h2>Related Jobs</h2>
        <div class="row">
            <?php foreach ($related_jobs as $rj): ?>
                <div class="col-md-4">
                    <div class="card job-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="job_details.php?job_id=<?= e($rj['job_id']) ?>">
                                    <?= e($rj['title']) ?>
                                </a>
                            </h5>
                            <p class="card-text"><strong>Company:</strong> <?= e($rj['company_name']) ?></p>
                            <p class="card-text"><strong>Location:</strong> <?= e($rj['location']) ?></p>
                            <p class="card-text"><strong>Salary:</strong> <?= e($rj['salary']) ?></p>
                            <p class="card-text"><strong>Job Type:</strong> <?= e($rj['job_type']) ?></p>
                            <p class="card-text"><strong>Posted:</strong> <?= e($rj['date_posted']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php require_once '../includes/footer.php'; ?>
</body>
</html>