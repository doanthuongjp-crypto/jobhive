<?php
require_once '../DB/dbInfo.php';
require_once '../db/Job_management.php';
require_once '../db/Company_management.php';

// Lấy danh sách job và company
$jobs = JobManagement::getJobs();
$companies = CompanyManagement::getCompanies(); // Bạn cần tạo hàm getCompanies để lấy danh sách công ty

// Xử lý Form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $result = JobManagement::addJob($_POST);
                $message = $result['message'];
                break;

            case 'delete':
                $result = JobManagement::deleteJob($_POST['job_id']);
                $message = $result['message'];
                break;
        }
    }

    // Redirect kèm message
    header("Location: " . $_SERVER['PHP_SELF'] . "?message=" . urlencode($message));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Management</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 5rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .delete-btn {
            background-color: #e74c3c;
            color: white;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        .form-section {
            margin-top: 40px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .submit-btn {
            background-color: #2ecc71;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #27ae60;
        }
    </style>
</head>

<body>
    <?php if (isset($_GET['message'])): ?>
        <script>
            alert("<?= htmlspecialchars($_GET['message']) ?>");
        </script>
    <?php endif; ?>

    <div class="container">
        <h1>Job Management</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Company</th>
                    <th>Location</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Experience</th>
                    <th>Remote</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jobs as $job): ?>
                    <tr>
                        <td><?= htmlspecialchars($job['job_id']) ?></td>
                        <td><?= htmlspecialchars($job['title']) ?></td>
                        <td><?= htmlspecialchars($job['company_name']) ?></td>
                        <td><?= htmlspecialchars($job['location']) ?></td>
                        <td><?= htmlspecialchars($job['category']) ?></td>
                        <td><?= htmlspecialchars($job['job_type']) ?></td>
                        <td><?= htmlspecialchars($job['experience_level']) ?></td>
                        <td><?= htmlspecialchars($job['remote_option']) ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="job_id" value="<?= $job['job_id'] ?>">
                                <button type="submit" class="action-btn delete-btn"
                                    onclick="return confirm('Are you sure you want to delete this job?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="form-section">
            <h2>Add New Job</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add">

                <div class="form-group">
                    <label for="title">Job Title</label>
                    <input type="text" name="title" required>
                </div>

                <div class="form-group">
                    <label for="company_id">Company</label>
                    <select name="company_id" required>
                        <?php foreach ($companies as $company): ?>
                            <option value="<?= $company['company_id'] ?>"><?= htmlspecialchars($company['company_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" required></textarea>
                </div>

                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" name="location" required>
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
                    <select name="category" required>
                        <option value="IT_Software">IT Software</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Finance">Finance</option>
                        <option value="Healthcare">Healthcare</option>
                        <option value="Government_Public_Sector">Government & Public Sector</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="job_type">Job Type</label>
                    <select name="job_type" required>
                        <option value="Full-time">Full-time</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Internship">Internship</option>
                        <option value="Contract">Contract</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="experience_level">Experience Level</label>
                    <select name="experience_level" required>
                        <option value="Entry">Entry</option>
                        <option value="Mid">Mid</option>
                        <option value="Senior">Senior</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="remote_option">Remote Option</label>
                    <select name="remote_option" required>
                        <option value="Remote">Remote</option>
                        <option value="Onsite">Onsite</option>
                        <option value="Hybrid">Hybrid</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="salary">Salary</label>
                    <input type="text" name="salary">
                </div>

                <div class="form-group">
                    <label for="qualifications">Qualifications</label>
                    <textarea name="qualifications"></textarea>
                </div>

                <div class="form-group">
                    <label for="perks">Perks</label>
                    <textarea name="perks"></textarea>
                </div>

                <div class="form-group">
                    <label for="application_deadline">Application Deadline</label>
                    <input type="date" name="application_deadline">
                </div>

                <button type="submit" class="submit-btn">Add Job</button>
            </form>
        </div>
    </div>
</body>

</html>
