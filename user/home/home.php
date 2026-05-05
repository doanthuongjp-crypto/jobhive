<?php
session_start();


// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

require_once '../../DB/dbInfo.php';
require_once '../function/get_job_list.php';
require_once '../function/save_feedback.php';
require_once '../function/get_all_feedback.php';

$job_list = get_job_list();
$list_feedback = get_all_feedback();
$group_feedback = array_chunk($list_feedback, 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Jobs - Leading Recruitment Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../asset/css/user/employer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="../../asset/js/feedback_submit.js"></script>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <!-- Carousel -->
    <div class="carousel-container hero-container pb-5">
        <div id="hero-carousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#hero-carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#hero-carousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#hero-carousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item hero-item active" data-bs-interval="10000">
                    <img src="../../asset/img/banner.webp" class="d-block w-100 hero-img" alt="Banner 1">
                </div>
                <div class="carousel-item hero-item" data-bs-interval="2000">
                    <img src="../../asset/img/banner_1.jpg" class="d-block w-100 hero-img" alt="Banner 2">
                </div>
                <div class="carousel-item hero-item">
                    <img src="../../asset/img/banner_2.jpg" class="d-block w-100 hero-img" alt="Banner 3">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#hero-carousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#hero-carousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!-- Job Category -->
    <div class="container-sm py-5">
        <div class="row py-5">
            <h1>Job Categories</h1>
        </div>
        <div class="row row-cols-1 row-cols-md-5 g-4">
            <div class="col">
                <a href="Joblist.php?category=IT_Software" class="text-decoration-none" aria-label="Explore IT & Software jobs">
                    <div class="card category-card text-center h-100">
                        <img src="../../asset/img/cate-IT.png" class="card-img-top mx-auto d-block mt-3 w-50 ratio ratio-1x1" alt="IT & Software category image" style="object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">IT & Software</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="Joblist.php?category=Marketing" class="text-decoration-none" aria-label="Explore Marketing jobs">
                    <div class="card category-card text-center h-100">
                        <img src="../../asset/img/cate-marketing.png" class="card-img-top mx-auto d-block p-2 mt-3 w-50 ratio ratio-1x1" alt="Marketing category image" style="object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">Marketing</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="Joblist.php?category=Finance" class="text-decoration-none" aria-label="Explore Finance jobs">
                    <div class="card category-card text-center h-100">
                        <img src="../../asset/img/cate-finance.png" class="card-img-top mx-auto d-block mt-3 w-50 ratio ratio-1x1" alt="Finance category image" style="object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">Finance</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="Joblist.php?category=Healthcare" class="text-decoration-none" aria-label="Explore Healthcare jobs">
                    <div class="card category-card text-center h-100">
                        <img src="../../asset/img/cate-health.png" class="card-img-top mx-auto d-block mt-3 w-50 ratio ratio-1x1" alt="Healthcare category image" style="object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">Healthcare</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="Joblist.php?category=Government_Public_Sector" class="text-decoration-none" aria-label="Explore Government & Public Sector jobs">
                    <div class="card category-card text-center h-100">
                        <img src="../../asset/img/cate-government.png" class="card-img-top mx-auto d-block mt-3 w-50 ratio ratio-1x1" alt="Government & Public Sector category image" style="object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">Government & Public Sector</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Job List -->
    <div class="container px-3 py-3 mx-auto bg-job-list border rounded mt-3">
        <div class="d-flex justify-content-between align-items-center px-3">
            <h5>Job Recommendations</h5>
            <a href="Joblist.php">
                <button class="btn btn-primary">See All</button>
            </a>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4 pt-2">
            <?php if (count($job_list) > 0): ?>
                <?php foreach ($job_list as $job): ?>
                <div class="col">
                    <a href="job_details.php?job_id=<?= htmlspecialchars($job['job_id']) ?>" class="text-decoration-none">
                        <div class="card job-card mb-3" style="max-width: 540px;">
                            <div class="row g-0 d-flex align-items-center">
                                <div class="col-md-4">
                                    <img src="<?= htmlspecialchars($job['logo_path']) ?>" class="img-fluid rounded-start" alt="<?= htmlspecialchars($job['company_name']) ?>">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title text-truncate"><?= htmlspecialchars($job['title']) ?></h5>
                                        <p class="card-text"><?= htmlspecialchars($job['company_name']) ?></p>
                                        <p class="card-text"><?= htmlspecialchars($job['salary']) ?></p>
                                        <p class="card-text"><?= htmlspecialchars($job['location']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">There are no jobs available right now.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Rating Display -->
    <div class="container carousel-container feedback-container my-5">
        <div id="feedbackCarousel" class="carousel carousel-dark slide">
            <div class="carousel-indicators feedback-indicators">
                <?php for($count = 0; $count < count($group_feedback); $count++): ?>
                    <button type="button" data-bs-target="#feedbackCarousel" 
                        data-bs-slide-to="<?= $count ?>" 
                        class="<?= $count == 0 ? 'active' : '' ?>" 
                        aria-current="true" 
                        aria-label="Slide <?= $count + 1 ?>">
                    </button>
                <?php endfor; ?>
            </div>
            <div class="carousel-inner">
                <?php foreach ($group_feedback as $index => $feedbacks): ?>
                    <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
                        <div class="row justify-content-center">
                            <?php foreach ($feedbacks as $feedback): ?>
                                <div class="col-md-4 col-12 d-flex justify-content-center">
                                    <div class="feedback-card card  mb-4">
                                        <div class="card-header">
                                            <div class="star-feedback ps-2">
                                                <?php for($filled = 0; $filled < $feedback["rating"]; $filled++): ?>
                                                    <i class="fas fa-star filled"></i>
                                                <?php endfor; ?>
                                                <?php for($empty = $feedback["rating"]; $empty < 5; $empty++): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php endfor; ?>                                      
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text feedback-text"><?= htmlspecialchars($feedback["comments"]) ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#feedbackCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#feedbackCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!-- Rating Form -->
    <div class="container d-flex justify-content-center">
        <div class="card p-4 shadow rounded" style="max-width: 600px; width: 100%;">
            <h2 class="text-center mb-4">Leave a Comment for Us</h2>
            <form id="ratingForm" class="text-center" method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
                <div class="star-rating mb-3">
                    <input type="radio" name="rating" id="star5" value="5">
                    <label for="star5" class="fas fa-star"></label>
                    <input type="radio" name="rating" id="star4" value="4">
                    <label for="star4" class="fas fa-star"></label>
                    <input type="radio" name="rating" id="star3" value="3">
                    <label for="star3" class="fas fa-star"></label>
                    <input type="radio" name="rating" id="star2" value="2">
                    <label for="star2" class="fas fa-star"></label>
                    <input type="radio" name="rating" id="star1" value="1">
                    <label for="star1" class="fas fa-star"></label>
                </div>
                <div class="mb-3">
                    <textarea class="form-control" name="comment" rows="4" placeholder="Enter your comment..." required></textarea>
                </div>
                <button type="button" class="btn btn-primary" onclick="check_input_rating()">Submit Feedback</button>
            </form>
            <div id="respond" class="mt-3"></div>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>