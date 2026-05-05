DROP DATABASE IF EXISTS jobhive;
CREATE DATABASE jobhive;
USE jobhive;

-- 1. Users
CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('job_seeker', 'admin') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 2. Companies
CREATE TABLE Companies (
    company_id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(255) NOT NULL,
    description TEXT,
    location VARCHAR(255),
    logo_path VARCHAR(255)
);

-- 3. Jobs
CREATE TABLE Jobs (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(255) NOT NULL,
    category ENUM('IT_Software', 'Marketing', 'Finance', 'Healthcare', 'Government_Public_Sector') NOT NULL,
    job_type ENUM('Full-time', 'Part-time', 'Internship', 'Contract') NOT NULL,
    experience_level ENUM('Entry', 'Mid', 'Senior') NOT NULL,
    salary VARCHAR(100),
    remote_option ENUM('Remote', 'Onsite', 'Hybrid') NOT NULL,
    qualifications TEXT,
    perks TEXT,
    date_posted DATETIME DEFAULT CURRENT_TIMESTAMP,
    application_deadline DATE,
    FOREIGN KEY (company_id) REFERENCES Companies(company_id) ON DELETE CASCADE
);

-- 4. SavedJobs
CREATE TABLE SavedJobs (
    saved_job_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    job_id INT NOT NULL,
    saved_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (job_id) REFERENCES Jobs(job_id) ON DELETE CASCADE,
    UNIQUE (user_id, job_id)
);

-- 5. Feedback
CREATE TABLE Feedback (
    feedback_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comments TEXT NOT NULL,
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);