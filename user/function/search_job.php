<?php
require_once '../../DB/dbInfo.php';

function search_job($keyword = '', $location = '', $category = '', $page = 1, $jobs_per_page = 5) {
    try {
        $pdo = DbInfo::connectDB();

        // Câu truy vấn cơ bản
        $sql = "SELECT Jobs.*, Companies.company_name, Companies.location AS company_location, Companies.logo_path 
                FROM Jobs 
                INNER JOIN Companies ON Jobs.company_id = Companies.company_id 
                WHERE 1";
        $params = [];

        // Thêm điều kiện tìm kiếm
        if (!empty($keyword)) {
            $sql .= " AND (Jobs.title LIKE :keyword1 OR Companies.company_name LIKE :keyword2)";
            $params['keyword1'] = '%' . $keyword . '%';
            $params['keyword2'] = '%' . $keyword . '%';
        }
        if (!empty($location) && $location !== 'All locations') {
            $sql .= " AND Jobs.location = :location";
            $params['location'] = $location;
        }
        if (!empty($category)) {
            $sql .= " AND Jobs.category = :category";
            $params['category'] = $category;
        }

        // Lấy tổng số job để tính phân trang
        $count_sql = str_replace('SELECT Jobs.*, Companies.company_name, Companies.location AS company_location, Companies.logo_path', 'SELECT COUNT(*)', $sql);
        $stmt = $pdo->prepare($count_sql);
        $stmt->execute($params);
        $total_jobs = $stmt->fetchColumn();
        $total_pages = ceil($total_jobs / $jobs_per_page);

        // Thêm phân trang
        $offset = ($page - 1) * $jobs_per_page;
        $sql .= " ORDER BY Jobs.date_posted DESC LIMIT :offset, :limit";
        $stmt = $pdo->prepare($sql);
        $params['offset'] = $offset;
        $params['limit'] = $jobs_per_page;

        // Thực thi
        $stmt->execute($params);
        $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'jobs' => $jobs,
            'total_pages' => $total_pages,
            'total_jobs' => $total_jobs
        ];

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return ['jobs' => [], 'total_pages' => 0, 'total_jobs' => 0];
    }
}
?>