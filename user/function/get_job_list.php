<?php
function get_job_list() {
    try {
        $conn = DbInfo::connectDB();
        $sql = "SELECT `job_id`,`title`, `company_name`, `salary`, jobs.`location`, `logo_path` FROM jobs
                LEFT JOIN companies
                ON jobs.company_id = companies.company_id
                ORDER BY jobs.date_posted DESC
                LIMIT 9";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $job_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $job_list;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return [];
    }
}

?>