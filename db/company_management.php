<?php
require_once '../DB/dbInfo.php';
require_once '../db/Company_management.php';

// Xử lý form
function handlePost()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) return;

    $message = '';
    switch ($_POST['action']) {
        case 'add':
            $result = CompanyManagement::addCompany(
                $_POST['company_name'] ?? '',
                $_POST['description'] ?? '',
                $_POST['location'] ?? '',
                $_FILES['logo'] ?? null
            );
            $message = $result['message'];
            break;

        case 'delete':
            $result = CompanyManagement::deleteCompany($_POST['company_id'] ?? '');
            $message = $result['message'];
            break;

        case 'update':
            $result = CompanyManagement::updateCompany(
                $_POST['company_id'],
                $_POST['company_name'],
                $_POST['description'],
                $_POST['location'],
                $_FILES['logo'] ?? null
            );
            $message = $result['message'];
            break;
    }

    header("Location: " . $_SERVER['PHP_SELF'] . "?message=" . urlencode($message));
    exit();
}
handlePost();

// Lấy danh sách công ty
$companies = CompanyManagement::getCompanies();

// Lấy thông tin đang sửa nếu có
$editCompany = null;
if (isset($_GET['edit_id'])) {
    foreach ($companies as $c) {
        if ($c['company_id'] == $_GET['edit_id']) {
            $editCompany = $c;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Company Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .logo-preview {
            max-width: 100px;
            max-height: 100px;
        }

        .delete-btn {
            background-color: #ff4444;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        textarea {
            resize: vertical;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h1 class="mb-4">Company Management</h1>

        <?php if (!empty($_GET['message'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['message']) ?></div>
        <?php endif; ?>

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Company Name</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Logo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($companies as $company): ?>
                    <tr>
                        <td><?= htmlspecialchars($company['company_id']) ?></td>
                        <td><?= htmlspecialchars($company['company_name']) ?></td>
                        <td><?= htmlspecialchars($company['description']) ?></td>
                        <td><?= htmlspecialchars($company['location']) ?></td>
                        <td>
                            <?php if (!empty($company['logo_path'])): ?>
                                <img src="<?= htmlspecialchars($company['logo_path']) ?>" class="logo-preview">
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this company?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="company_id" value="<?= $company['company_id'] ?>">
                                <button type="submit" class="delete-btn btn-sm">Delete</button>
                            </form>
                            <form method="GET" style="display:inline;">
                                <input type="hidden" name="edit_id" value="<?= $company['company_id'] ?>">
                                <button type="submit" class="btn btn-warning btn-sm">Edit</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2 class="mt-4"><?= $editCompany ? 'Edit Company' : 'Add New Company' ?></h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?= $editCompany ? 'update' : 'add' ?>">
            <?php if ($editCompany): ?>
                <input type="hidden" name="company_id" value="<?= $editCompany['company_id'] ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label">Company Name *</label>
                <input type="text" name="company_name" class="form-control" required
                    value="<?= $editCompany ? htmlspecialchars($editCompany['company_name']) : '' ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control"><?= $editCompany ? htmlspecialchars($editCompany['description']) : '' ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-control"
                    value="<?= $editCompany ? htmlspecialchars($editCompany['location']) : '' ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Logo</label>
                <input type="file" name="logo" class="form-control" accept="image/*">
                <?php if ($editCompany && $editCompany['logo_path']): ?>
                    <p>Current Logo:</p>
                    <img src="<?= htmlspecialchars($editCompany['logo_path']) ?>" class="logo-preview">
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-<?= $editCompany ? 'success' : 'primary' ?>">
                <?= $editCompany ? 'Update Company' : 'Add Company' ?>
            </button>

            <?php if ($editCompany): ?>
                <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary">Cancel</a>
            <?php endif; ?>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>