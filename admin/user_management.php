<?php
require_once '../DB/dbInfo.php';
require_once '../db/User_management.php';

// セッション管理および権限チェック（必要に応じて有効化）
// session_start();
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: ../../auth/login.php');
//     exit;
// }

// フォーム送信の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                // 新規ユーザーの追加
                $result = UserManagement::addUser(
                    $_POST['username'],
                    $_POST['email'],
                    $_POST['password'],
                    $_POST['role']
                ); 
                $message = $result['message'];
                break;

            case 'delete':
                // ユーザーの削除
                $result = UserManagement::deleteUser($_POST['id']);
                $message = $result['message'];
                break;
        }
    }

    // メッセージを付与してリダイレクト（二重送信防止）
    header("Location: " . $_SERVER['PHP_SELF'] . "?message=" . urlencode($message));
    exit();
}

// 全ユーザー情報の取得
$users = UserManagement::getUsers();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザーアカウント管理</title>
    <style>
        /* 基本スタイル設定 */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .user-table th,
        .user-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .user-table th {
            background-color: #f2f2f2;
            font-weight: 600;
        }

        .user-table tr:hover {
            background-color: #f9f9f9;
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .delete-btn {
            background-color: #e74c3c;
            color: white;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        .add-user-form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .submit-btn {
            background-color: #2ecc71;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #27ae60;
        }
    </style>
</head>

<body>
    <!-- アラートメッセージ表示 -->
    <?php if (isset($_GET['message'])): ?>
        <script>
            alert("<?= htmlspecialchars($_GET['message']) ?>");
        </script>
    <?php endif; ?>

    <?php include './include/header.php' ?>

    <div class="container">
        <h1>ユーザーアカウント管理</h1>

        <!-- ユーザー一覧テーブル -->
        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ユーザー名</th>
                    <th>メールアドレス</th>
                    <th>権限</th>
                    <th>作成日時</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['user_id']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $user['user_id'] ?>">
                                <button type="submit" class="action-btn delete-btn"
                                    onclick="return confirm('このユーザーを削除してもよろしいですか？')">
                                    削除
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- 新規ユーザー登録フォーム -->
        <div class="add-user-form">
            <h2>新規ユーザー登録</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label for="username">ユーザー名</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="role">権限ロール</label>
                    <select name="role" id="role">
                       <option value="job_seeker">求職者 (Job Seeker)</option>
                       <option value="admin">管理者 (Admin)</option>
                    </select>
                </div>
                <button type="submit" class="submit-btn">ユーザーを追加</button>
            </form>
        </div>
    </div>
</body>

</html>
