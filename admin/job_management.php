<?php
require_once '../DB/dbInfo.php';
require_once '../db/Job_management.php';
require_once '../db/Company_management.php';

// 求人一覧および企業一覧を取得
$jobs = JobManagement::getJobs();
$companies = CompanyManagement::getCompanies(); // 企業リスト取得用の関数が必要です

// フォーム処理（リクエストハンドリング）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            // 求人情報の追加
            case 'add':
                $result = JobManagement::addJob($_POST);
                $message = $result['message'];
                break;

            // 求人情報の削除
            case 'delete':
                $result = JobManagement::deleteJob($_POST['job_id']);
                $message = $result['message'];
                break;
        }
    }

    // メッセージを付与してリダイレクト（二重送信防止）
    header("Location: " . $_SERVER['PHP_SELF'] . "?message=" . urlencode($message));
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>求人管理システム</title>
    <style>
        /* スタイル設定 */
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
    <!-- アラートメッセージの表示 -->
    <?php if (isset($_GET['message'])): ?>
        <script>
            alert("<?= htmlspecialchars($_GET['message']) ?>");
        </script>
    <?php endif; ?>

    <div class="container">
        <h1>求人管理</h1>

        <!-- 求人一覧テーブル -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>職種名</th>
                    <th>企業名</th>
                    <th>勤務地</th>
                    <th>カテゴリー</th>
                    <th>雇用形態</th>
                    <th>経験レベル</th>
                    <th>リモート可否</th>
                    <th>操作</th>
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
                            <!-- 削除用フォーム -->
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="job_id" value="<?= $job['job_id'] ?>">
                                <button type="submit" class="action-btn delete-btn"
                                    onclick="return confirm('この求人を削除してもよろしいですか？')">削除</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- 求人追加フォームセクション -->
        <div class="form-section">
            <h2>新規求人登録</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add">

                <div class="form-group">
                    <label for="title">職種名</label>
                    <input type="text" name="title" required>
                </div>

                <div class="form-group">
                    <label for="company_id">企業名</label>
                    <select name="company_id" required>
                        <?php foreach ($companies as $company): ?>
                            <option value="<?= $company['company_id'] ?>"><?= htmlspecialchars($company['company_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">仕事内容</label>
                    <textarea name="description" required></textarea>
                </div>

                <div class="form-group">
                    <label for="location">勤務地</label>
                    <input type="text" name="location" required>
                </div>

                <div class="form-group">
                    <label for="category">カテゴリー</label>
                    <select name="category" required>
                        <option value="IT_Software">IT/ソフトウェア</option>
                        <option value="Marketing">マーケティング</option>
                        <option value="Finance">金融</option>
                        <option value="Healthcare">医療/ヘルスケア</option>
                        <option value="Government_Public_Sector">官公庁/公共セクター</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="job_type">雇用形態</label>
                    <select name="job_type" required>
                        <option value="Full-time">正社員</option>
                        <option value="Part-time">アルバイト/パート</option>
                        <option value="Internship">インターンシップ</option>
                        <option value="Contract">契約社員</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="experience_level">経験レベル</label>
                    <select name="experience_level" required>
                        <option value="Entry">初級 (Entry)</option>
                        <option value="Mid">中級 (Mid)</option>
                        <option value="Senior">上級 (Senior)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="remote_option">リモートワーク</label>
                    <select name="remote_option" required>
                        <option value="Remote">フルリモート</option>
                        <option value="Onsite">出社</option>
                        <option value="Hybrid">ハイブリッド</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="salary">給与</label>
                    <input type="text" name="salary">
                </div>

                <div class="form-group">
                    <label for="qualifications">応募資格</label>
                    <textarea name="qualifications"></textarea>
                </div>

                <div class="form-group">
                    <label for="perks">福利厚生</label>
                    <textarea name="perks"></textarea>
                </div>

                <div class="form-group">
                    <label for="application_deadline">応募締切日</label>
                    <input type="date" name="application_deadline">
                </div>

                <button type="submit" class="submit-btn">求人を追加</button>
            </form>
        </div>
    </div>
</body>

</html>
