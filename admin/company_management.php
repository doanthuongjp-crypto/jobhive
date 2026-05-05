<?php
require_once '../DB/dbInfo.php';
require_once '../db/Company_management.php';

// フォーム処理（リクエストのハンドリング）
function handlePost()
{
    // POSTメソッド以外、またはアクションが未定義の場合は処理を終了
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) return;

    $message = '';
    switch ($_POST['action']) {
        // 会社情報の追加
        case 'add':
            $result = CompanyManagement::addCompany(
                $_POST['company_name'] ?? '',
                $_POST['description'] ?? '',
                $_POST['location'] ?? '',
                $_FILES['logo'] ?? null
            );
            $message = $result['message'];
            break;

        // 会社情報の削除
        case 'delete':
            $result = CompanyManagement::deleteCompany($_POST['company_id'] ?? '');
            $message = $result['message'];
            break;

        // 会社情報の更新
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

    // 処理完了後、メッセージを付与して自画面へリダイレクト
    header("Location: " . $_SERVER['PHP_SELF'] . "?message=" . urlencode($message));
    exit();
}
handlePost();

// 会社一覧を取得
$companies = CompanyManagement::getCompanies();

// 編集対象の情報を取得（edit_idが指定されている場合）
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
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>企業管理システム</title>
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
        <h1 class="mb-4">企業管理</h1>

        <!-- 処理結果メッセージの表示 -->
        <?php if (!empty($_GET['message'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['message']) ?></div>
        <?php endif; ?>

        <!-- 企業一覧テーブル -->
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>企業名</th>
                    <th>概要</th>
                    <th>所在地</th>
                    <th>ロゴ</th>
                    <th>操作</th>
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
                            <!-- 削除ボタン -->
                            <form method="POST" style="display:inline;" onsubmit="return confirm('この企業を削除しますか？')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="company_id" value="<?= $company['company_id'] ?>">
                                <button type="submit" class="delete-btn btn-sm">削除</button>
                            </form>
                            <!-- 編集ボタン -->
                            <form method="GET" style="display:inline;">
                                <input type="hidden" name="edit_id" value="<?= $company['company_id'] ?>">
                                <button type="submit" class="btn btn-warning btn-sm">編集</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- 登録・編集フォーム -->
        <h2 class="mt-4"><?= $editCompany ? '企業情報の編集' : '新規企業の登録' ?></h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?= $editCompany ? 'update' : 'add' ?>">
            <?php if ($editCompany): ?>
                <input type="hidden" name="company_id" value="<?= $editCompany['company_id'] ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label">企業名 *</label>
                <input type="text" name="company_name" class="form-control" required
                    value="<?= $editCompany ? htmlspecialchars($editCompany['company_name']) : '' ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">概要</label>
                <textarea name="description" class="form-control"><?= $editCompany ? htmlspecialchars($editCompany['description']) : '' ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">所在地</label>
                <input type="text" name="location" class="form-control"
                    value="<?= $editCompany ? htmlspecialchars($editCompany['location']) : '' ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">ロゴ画像</label>
                <input type="file" name="logo" class="form-control" accept="image/*">
                <?php if ($editCompany && $editCompany['logo_path']): ?>
                    <p class="mt-2">現在のロゴ:</p>
                    <img src="<?= htmlspecialchars($editCompany['logo_path']) ?>" class="logo-preview">
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-<?= $editCompany ? 'success' : 'primary' ?>">
                <?= $editCompany ? '更新する' : '登録する' ?>
            </button>

            <?php if ($editCompany): ?>
                <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary">キャンセル</a>
            <?php endif; ?>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
