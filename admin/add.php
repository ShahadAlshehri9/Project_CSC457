<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require '../db.php';

$error = "";

// دالة لرفع الصورة وحفظها في مجلد images/
function uploadImage($fileKey, &$error) {
    if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] === UPLOAD_ERR_NO_FILE) {
        return ""; // لم يتم رفع ملف
    }

    $file = $_FILES[$fileKey];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = "حدث خطأ أثناء رفع الصورة.";
        return false;
    }

    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        $error = "نوع الصورة غير مدعوم. الأنواع المسموحة: jpg, jpeg, png, gif, webp.";
        return false;
    }

    // اسم آمن وفريد للصورة
    $base = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($file['name'], PATHINFO_FILENAME));
    if ($base === '') $base = 'img';
    $newName = $base . '_' . time() . '_' . rand(100, 999) . '.' . $ext;

    $uploadDir = __DIR__ . '/../images/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (!move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
        $error = "تعذّر حفظ الصورة في مجلد images.";
        return false;
    }

    return $newName;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $category    = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location    = trim($_POST['location'] ?? '');
    $features    = trim($_POST['features'] ?? '');
    $activities  = trim($_POST['activities'] ?? '');
    $landmarks   = trim($_POST['landmarks'] ?? '');

    // رفع الصور
    $main_image = uploadImage('main_image', $error);
    $gallery1   = $error ? "" : uploadImage('gallery1', $error);
    $gallery2   = $error ? "" : uploadImage('gallery2', $error);
    $gallery3   = $error ? "" : uploadImage('gallery3', $error);

    if ($main_image === "" && !$error) {
        $error = "الصورة الرئيسية مطلوبة.";
    }

    if (!$error && $name && $category && $description && $main_image) {
        $stmt = $conn->prepare("INSERT INTO places
            (name, category, description, location, features, activities, landmarks, main_image, gallery1, gallery2, gallery3)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssss",
            $name, $category, $description, $location,
            $features, $activities, $landmarks,
            $main_image, $gallery1, $gallery2, $gallery3);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: dashboard.php?msg=added");
            exit;
        } else {
            $error = "تعذّر حفظ السجل في قاعدة البيانات.";
        }
    } elseif (!$error) {
        $error = "يرجى تعبئة جميع الحقول المطلوبة.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
    if (localStorage.getItem("nightMode") === "1") {
        document.documentElement.classList.add("night");
    }
    </script>
    <title>إضافة محتوى</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header class="admin-navbar">
        <div class="brand">لوحة المشرف</div>
        <nav>
            <a href="dashboard.php">لوحة التحكم</a>
            <a href="../index.php">الموقع العام</a>
            <a href="logout.php" class="logout-btn">تسجيل الخروج</a>
        </nav>
    </header>

    <main>
        <form class="form-card" method="post" action="add.php" enctype="multipart/form-data">
            <h2>إضافة مكان جديد</h2>

            <?php if ($error): ?>
                <div class="error-msg"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <label>* اسم المكان</label>
            <input type="text" name="name" placeholder="مثال: أبها" required>

            <label>* الصورة الرئيسية للمكان</label>
            <input type="file" name="main_image" accept="image/*" required>

            <label>* الوصف</label>
            <textarea name="description" placeholder="اكتب وصفاً للمكان..." required></textarea>

            <label>* الموقع</label>
            <input type="text" name="location" placeholder="مثال: جنوب غرب المملكة العربية السعودية">

            <label>* التصنيف</label>
            <select name="category" required>
                <option value="">اختر التصنيف...</option>
                <option value="وسطى">وسطى</option>
                <option value="غربية">غربية</option>
                <option value="شرقية">شرقية</option>
                <option value="جنوبية">جنوبية</option>
                <option value="شمالية">شمالية</option>
            </select>

            <label>* المميزات</label>
            <input type="text" name="features" placeholder="مثال: موقع رئيسي، طبيعة جميلة">

            <label>* الأنشطة</label>
            <input type="text" name="activities" placeholder="مثال: جولات سياحية، تسوق">

            <label>* المعالم (افصل بينها بفاصلة)</label>
            <input type="text" name="landmarks" placeholder="مثال: مكان أول، المعبد الثاني، البلدة القديمة">

            <div class="gallery-section">
                <h3>صور المعرض</h3>

                <label>صورة المعرض الأولى</label>
                <input type="file" name="gallery1" accept="image/*">

                <label>صورة المعرض الثانية (اختياري)</label>
                <input type="file" name="gallery2" accept="image/*">

                <label>صورة المعرض الثالثة (اختياري)</label>
                <input type="file" name="gallery3" accept="image/*">
            </div>

            <button type="submit" class="btn submit-btn">إضافة المكان</button>
        </form>
    </main>
</body>
</html>
<?php $conn->close(); ?>
