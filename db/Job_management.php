<?php
require_once '../DB/dbInfo.php';

class JobManagement
{
    // Lấy danh sách tất cả jobs kèm tên công ty
    public static function getJobs()
    {
        try {
            $conn = DbInfo::connectDB();
            $stmt = $conn->query("SELECT j.*, c.company_name 
                                  FROM Jobs j
                                  JOIN Companies c ON j.company_id = c.company_id
                                  ORDER BY j.date_posted DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Thêm job mới
    public static function addJob($data)
    {
        try {
            $conn = DbInfo::connectDB();
            $stmt = $conn->prepare("INSERT INTO Jobs 
                (company_id, title, description, location, category, job_type, experience_level, salary, remote_option, qualifications, perks, application_deadline) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->execute([
                $data['company_id'],
                $data['title'],
                $data['description'],
                $data['location'],
                $data['category'],
                $data['job_type'],
                $data['experience_level'],
                $data['salary'],
                $data['remote_option'],
                $data['qualifications'],
                $data['perks'],
                $data['application_deadline']
            ]);

            return ['success' => true, 'message' => 'Job added successfully!'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    // Xóa job
    public static function deleteJob($job_id)
    {
        try {
            $conn = DbInfo::connectDB();
            $stmt = $conn->prepare("DELETE FROM Jobs WHERE job_id = ?");
            $stmt->execute([$job_id]);

            return ['success' => true, 'message' => 'Job deleted successfully!'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
}
?>
