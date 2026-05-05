
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Liên hệ với VietnamWorks</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    .contact-title {
      color: #004080;
      text-align: center;
      margin-top: 5rem;
    }
    .contact-container {
      display: flex;
      justify-content: space-between;
      border: 1px solid #ccc;
      padding: 30px;
      max-width: 1000px;
      margin: auto;
    }
    .left, .right {
      width: 48%;
    }
    .left h3 {
      margin-top: 0;
    }
    iframe {
      width: 100%;
      height: 200px;
      border: none;
      margin-top: 20px;
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    input[type="text"], input[type="email"], textarea {
      width: 100%;
      padding: 8px;
      box-sizing: border-box;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    textarea {
      height: 120px;
    }
    button {
      background-color: #004080;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    button:hover {
      background-color: #003366;
    }
  </style>
</head>
<body>
  <?php include '../includes/header.php' ?>
  <div class="contact-title">
    <h2>Liên hệ với VietnamWorks</h2>

  </div>
  <div class="contact-container">
    <div class="left">
      <h3>Văn phòng VietnamWorks</h3>
      <p><strong>TP.HCM</strong><br>
      Tầng 20, E.Town Central, 11 Đoàn Văn Bơ, Quận 4<br>
      Tel: (84 28) 3925 5000 / (84 28) 5404 1373</p>

      <p><strong>Hà Nội</strong><br>
      Tầng 7, V-Building, 125–127 Bà Triệu, Nguyễn Du, Hai Bà Trưng<br>
      Tel: (84 24) 3974 3033</p>

      <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.6720315029834!2d106.70175531428768!3d10.762622292327024!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f3c1d7b0b79%3A0xf9adcc4f4c347ed3!2sE.Town%20Central!5e0!3m2!1svi!2s!4v1717980000000" 
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>
    <div class="right">
      <form onsubmit="return confirmSubmit()">
        <div class="form-group">
          <label>Họ và tên</label>
          <input type="text" name="name">
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email">
        </div>
        <div class="form-group">
          <label>Tiêu đề</label>
          <input type="text" name="subject">
        </div>
        <div class="form-group">
          <label>Nội dung</label>
          <textarea name="message"></textarea>
        </div>
        <button type="submit">Gửi liên hệ</button>
      </form>
    </div>
  </div>
  <?php include '../includes/footer.php' ?>

  <script>
    function confirmSubmit() {
      return confirm("Bạn có chắc chắn muốn gửi liên hệ?");
    }
  </script>
</body>
</html>
