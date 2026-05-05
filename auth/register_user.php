<?php
require_once './User_management.php';
require_once '../DB/dbInfo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['fullName'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = UserManagement::registerUser($name, $email, $password);

    if ($result['success']) {
        echo "<script>
                alert('Registration successful! Please log in.');
                window.location.href = './login.php';
              </script>";
        exit;
    } else {
        $error_message = $result['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - JobHive</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Style -->
    <style>
        .bg-color-blue {
            background-color: rgb(14, 102, 235);
            color: white;
        }

        .left-title {
            font-size: 2.5rem;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .left-title {
                font-size: 1.6rem;
                text-align: center;
            }

            .logo-img {
                max-height: 80px;
            }
        }

        .logo-img {
            max-height: 120px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row flex-column flex-md-row min-vh-100">
            <!-- Left Column: Always visible -->
            <div class="col-12 col-md-5 bg-color-blue d-flex flex-column align-items-center justify-content-center p-4 text-white">
                <h1 class="left-title text-center mb-4">Welcome to our<br />community</h1>
                <img src="../asset/img/vnw_empower_growth_logo_white.png" alt="JobHive Logo" class="img-fluid logo-img">
            </div>

            <!-- Right Column: Registration Form -->
            <div class="col-12 col-md-7 d-flex flex-column justify-content-center px-4 px-md-5 py-4">
                <div class="mb-3 mt-md-5">
                    <h1>Sign up!</h1>
                    <p class="mt-2">
                        Welcome to the candidate registration page - where you can start
                        <br class="d-none d-md-block" />
                        your career journey in just a few simple steps.
                    </p>
                </div>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger mt-2"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <form action="" method="POST" class="d-flex flex-column">
                    <div class="form-group">
                        <label for="fullName" class="mt-2 fw-bold">Full Name</label>
                        <input
                            type="text"
                            name="fullName"
                            class="form-control mt-1 p-3"
                            placeholder="Input Full Name"
                            required
                            maxlength="100">
                    </div>

                    <div class="form-group">
                        <label for="email" class="mt-3 fw-bold">Email Address</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control mt-1 p-3"
                            placeholder="Input Email Address"
                            required
                            maxlength="100">
                    </div>

                    <div class="form-group">
                        <label for="password" class="mt-3 fw-bold">Password</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control mt-1 p-3"
                            placeholder="Input Password"
                            required
                            minlength="8">
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary w-100 p-3 fw-bold" type="submit">Register</button>
                    </div>

                    <p class="mt-4 text-center">Already have an account? <a href="./login.php">Sign in here</a></p>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Optional for interactive components like alert dismiss) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>