<?php
require_once '../db/dbInfo.php';
require_once './login_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobhive Login</title>
    <link rel="stylesheet" href="../asset/css/user/login.css">
</head>
<body>
    <header class="site-header">
        <div class="header-text">
            <img src="../asset/img/vnw_empower_growth_logo_white.png" alt="Jobhive Logo">
        </div>
    </header>

    <div class="container">
        <div class="login-box">
            <h2>Log in to your account</h2>
            <p>Access your account to manage your profile and apply for the jobs you want.</p>

            <form action="login.php" method="POST">
                <input type="email" name="email" placeholder="Email Address" required />
                <input type="password" name="password" placeholder="Enter Password" required />
                
                <div class="options">
                    <label><input type="checkbox" name="remember" /> Remember me</label>
                    <a href="#">Forgot password?</a>
                </div>
                
                <button type="submit">Log In</button>
            </form>
            
            <p class="signup-link">Don't have an account? <a href="./register_user.php">Sign Up</a></p>
            
            <?php if (isset($error_message)): ?>
                <div class="alert"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
