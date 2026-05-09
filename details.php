<?php
require 'db.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT * FROM places WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$place = $result->fetch_assoc();

if (!$place) {
    echo "<p style='text-align:center;padding:40px;'>المكان غير موجود.</p>";
    exit;
}

// تقسيم الحقول النصية الطويلة إلى نقاط لقوائم
function toList($text) {
    $parts = preg_split('/\s*[,،]\s*/u', $text, -1, PREG_SPLIT_NO_EMPTY);
    return $parts;
}

$features  = toList($place['features']);
$activities = toList($place['activities']);
$landmarks = toList($place['landmarks']);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($place['name']) ?> - اكتشف السعودية</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
    <header class="navbar">
        <div class="brand">اكتشف السعودية</div>
        <nav>
            <a href="index.php">الرئيسية</a>
            <a href="gallery.php">معرض المناطق</a>
            <a href="admin/login.php">دخول المشرف</a>
            <button id="nightBtn" class="night-btn" onclick="toggleNight()">الوضع الليلي</button>
        </nav>
    </header>

    <main class="details-container">
        <div class="details-hero">
            <img src="images/<?= htmlspecialchars($place['main_image']) ?>" alt="<?= htmlspecialchars($place['name']) ?>">
        </div>

        <div class="details-body">
            <h1><?= htmlspecialchars($place['name']) ?></h1>
            <p><?= htmlspecialchars($place['description']) ?></p>

            <div class="info-box">
                <h3>معلومات سريعة:</h3>
                <ul>
                    <li>الموقع: <?= htmlspecialchars($place['location']) ?></li>
                    <li>التصنيف: <?= htmlspecialchars($place['category']) ?></li>
                    <?php if (!empty($features)): ?>
                        <li><?= htmlspecialchars($features[0]) ?></li>
                    <?php endif; ?>
                </ul>
            </div>

            <?php if (!empty($activities)): ?>
            <div class="section-block">
                <h3>الأنشطة</h3>
                <ul>
                    <?php foreach ($activities as $a): ?>
                        <li><?= htmlspecialchars($a) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php if (!empty($landmarks)): ?>
            <div class="section-block">
                <h3>أبرز المعالم</h3>
                <ul>
                    <?php foreach ($landmarks as $l): ?>
                        <li><?= htmlspecialchars($l) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <div class="section-block">
                <h3>معرض الصور</h3>
                <div class="gallery-images">
                    <?php if (!empty($place['gallery1'])): ?>
                        <img src="images/<?= htmlspecialchars($place['gallery1']) ?>" alt="صورة 1">
                    <?php endif; ?>
                    <?php if (!empty($place['gallery2'])): ?>
                        <img src="images/<?= htmlspecialchars($place['gallery2']) ?>" alt="صورة 2">
                    <?php endif; ?>
                    <?php if (!empty($place['gallery3'])): ?>
                        <img src="images/<?= htmlspecialchars($place['gallery3']) ?>" alt="صورة 3">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        © اكتشف السعودية - جامعة الملك سعود
    </footer>
</body>
</html>
<?php $stmt->close(); $conn->close(); ?>
