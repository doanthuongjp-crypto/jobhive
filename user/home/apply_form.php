<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply Now - SRE Position</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            color: #333;
        }

        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        h2 {
            color: #2980b9;
        }

        .job-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #3498db;
            margin-bottom: 20px;
        }

        .form-section {
            margin-bottom: 25px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            height: 150px;
            resize: vertical;
        }

        .file-upload {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            margin-bottom: 10px;
        }

        .submit-btn {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .submit-btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>
    <h1>Apply Now</h1>

    <div class="job-info">
        <h2>Application Information</h2>
        <p><strong>Position:</strong> SRE - Monitoring (Tool Built)</p>
        <p><strong>Salary:</strong> $25 million - 55 million / month</p>
        <p><strong>Location:</strong> Hanoi</p>
    </div>

    <?php
    // Form processing logic
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fullName = htmlspecialchars($_POST['full_name'] ?? '');
        $email = htmlspecialchars($_POST['email'] ?? '');
        $coverLetter = htmlspecialchars($_POST['cover_letter'] ?? '');

        // File upload handling
        $uploadStatus = '';
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            $maxSize = 5 * 1024 * 1024; // 5MB
            $fileType = $_FILES['resume']['type'];
            $fileSize = $_FILES['resume']['size'];

            if (in_array($fileType, $allowedTypes) && $fileSize <= $maxSize) {
                $uploadDir = 'uploads/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $fileName = uniqid() . '_' . basename($_FILES['resume']['name']);
                $targetPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['resume']['tmp_name'], $targetPath)) {
                    $uploadStatus = "Resume uploaded successfully!";
                } else {
                    $uploadStatus = "Error uploading file.";
                }
            } else {
                $uploadStatus = "Invalid file type or size too large (max 5MB).";
            }
        }

        // Here you would typically save the data to a database or send an email
        // For this example, we'll just display a success message

        echo '<div style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 4px;">';
        echo '<h3>Application Submitted Successfully!</h3>';
        echo '<p>Thank you, ' . $fullName . ', for your application.</p>';
        if (!empty($uploadStatus)) {
            echo '<p>' . $uploadStatus . '</p>';
        }
        echo '</div>';
    }
    ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-section">
            <h2>Full Name</h2>
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-section">
            <h2>Upload Resume (Optional)</h2>
            <div class="file-upload">
                <input type="file" id="resume" name="resume">
                <p>Supported formats: PDF, DOC, DOCX (Max 5MB)</p>
            </div>
        </div>

        <div class="form-section">
            <h2>Cover Letter</h2>
            <label for="cover_letter">Write your cover letter here...</label>
            <textarea id="cover_letter" name="cover_letter" required></textarea>
        </div>

        <button type="submit" class="submit-btn">Submit</button>
    </form>
</body>

</html>