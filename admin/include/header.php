<?php
// Xác định trang hiện tại
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../asset/css/user/employer.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-header">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand" href="home.php">
                <img src="../../asset/img/vnw_empower_growth_logo_white.png" alt="Jobhive Logo" height="30">
            </a>
            <!-- Responsive toggle button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item px-4">
                        <a class="nav-link <?php echo $current_page === 'home.php' ? 'active' : ''; ?>" href="home.php">User Managenent</a>
                    </li>
                    <li class="nav-item px-4">
                        <a class="nav-link <?php echo $current_page === 'Joblist.php' ? 'active' : ''; ?>" href="Joblist.php">Job Management</a>
                    </li>        
                    <li class="nav-item px-4">
                        <a class="nav-link <?php echo $current_page === 'saved_job.php' ? 'active' : ''; ?>" href="saved_job.php">Saved Job</a>
                    </li>
                    <li class="nav-item px-4">
                        <a class="nav-link <?php echo $current_page === 'contact_us.php' ? 'active' : ''; ?>" href="contact_us.php">Contact Us</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-light" href="../../auth/logout.php">Log out</a>
                        </li>
                </ul>
            </div>
        </div>
    </nav>
</body>
</html>