<?php
require 'db.php';
$result = $conn->query("SELECT * FROM places ORDER BY id ASC");

// استخراج التصنيفات الفريدة للقائمة المنسدلة
$cats_result = $conn->query("SELECT DISTINCT category FROM places ORDER BY category ASC");
$categories = [];
while ($row = $cats_result->fetch_assoc()) { $categories[] = $row['category']; }
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>معرض المناطق - اكتشف السعودية</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
    <header class="navbar">
        <div class="brand">اكتشف السعودية</div>
        <nav>
            <a href="index.php">الرئيسية</a>
            <a href="gallery.php" class="active">معرض المناطق</a>
            <a href="admin/login.php">دخول المشرف</a>
            <button id="nightBtn" class="night-btn" onclick="toggleNight()">الوضع الليلي</button>
        </nav>
    </header>

    <section class="gallery-header">
        <h1>معرض المناطق</h1>
        <p>ابحث أو صنّف النتائج ثم اضغط على أي منطقة للانتقال إلى صفحة التفاصيل.</p>
    </section>

    <div class="filters">
        <input type="text" id="searchInput" placeholder="ابحث عن منطقة أو معلم..." onkeyup="filterGallery()">
        <select id="categorySelect" onchange="filterGallery()">
            <option value="all">كل المناطق</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
            <?php endforeach; ?>
        </select>
        <div class="count" id="resultCount">عدد النتائج: <?= $result->num_rows ?></div>
    </div>

    <section class="gallery-grid">
        <?php while ($row = $result->fetch_assoc()): ?>
            <a class="place-card"
               href="details.php?id=<?= $row['id'] ?>"
               data-name="<?= htmlspecialchars($row['name']) ?>"
               data-desc="<?= htmlspecialchars($row['description']) ?>"
               data-category="<?= htmlspecialchars($row['category']) ?>">
                <img src="images/<?= htmlspecialchars($row['main_image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                <div class="place-info">
                    <div class="category"><?= htmlspecialchars($row['category']) ?></div>
                    <div class="name"><?= htmlspecialchars($row['name']) ?></div>
                    <div class="desc"><?= htmlspecialchars($row['description']) ?></div>
                </div>
            </a>
        <?php endwhile; ?>
    </section>

    <footer class="footer">
        © اكتشف السعودية - جامعة الملك سعود
    </footer>
</body>
</html>
<?php $conn->close(); ?>
