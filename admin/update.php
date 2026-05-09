<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require '../db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error = "";

// دالة لرفع الصورة وإرجاع اسمها (أو "" إذا لم يتم رفع شيء)
function uploadImage($fileKey, &$error) {
    if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] === UPLOAD_ERR_NO_FILE) {
        return ""; // لم يتم رفع ملف جديد
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
    $id          = intval($_POST['id']);
    $name        = trim($_POST['name']);
    $category    = trim($_POST['category']);
    $description = trim($_POST['description']);
    $location    = trim($_POST['location']);
    $features    = trim($_POST['features']);
    $activities  = trim($_POST['activities']);
    $landmarks   = trim($_POST['landmarks']);

    // الصور القديمة (تستخدم إذا لم يرفع المشرف صورة جديدة)
    $current_main     = trim($_POST['current_main'] ?? '');
    $current_gallery1 = trim($_POST['current_gallery1'] ?? '');
    $current_gallery2 = trim($_POST['current_gallery2'] ?? '');
    $current_gallery3 = trim($_POST['current_gallery3'] ?? '');

    // محاولة رفع صور جديدة
    $newMain = uploadImage('main_image', $error);
    $newG1   = $error ? "" : uploadImage('gallery1', $error);
    $newG2   = $error ? "" : uploadImage('gallery2', $error);
    $newG3   = $error ? "" : uploadImage('gallery3', $error);

    // إذا لم يتم رفع صورة جديدة، نحتفظ بالقديمة
    $main_image = $newMain !== "" ? $newMain : $current_main;
    $gallery1   = $newG1 !== "" ? $newG1 : $current_gallery1;
    $gallery2   = $newG2 !== "" ? $newG2 : $current_gallery2;
    $gallery3   = $newG3 !== "" ? $newG3 : $current_gallery3;

    if (!$error) {
        $stmt = $conn->prepare("UPDATE places SET
            name = ?, category = ?, description = ?, location = ?,
            features = ?, activities = ?, landmarks = ?,
            main_image = ?, gallery1 = ?, gallery2 = ?, gallery3 = ?
            WHERE id = ?");
        $stmt->bind_param("sssssssssssi",
            $name, $category, $description, $location,
            $features, $activities, $landmarks,
            $main_image, $gallery1, $gallery2, $gallery3, $id);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: dashboard.php?msg=updated");
            exit;
        } else {
            $error = "تعذّر حفظ التعديلات في قاعدة البيانات.";
        }
    }
}

// جلب السجل الحالي للعرض
$stmt = $conn->prepare("SELECT * FROM places WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$place = $stmt->get_result()->fetch_assoc();

if (!$place) {
    echo "<p style='text-align:center;padding:40px;'>السجل غير موجود.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تحديث المحتوى</title>
    <script>
    if (localStorage.getItem("nightMode") === "1") {
        document.documentElement.classList.add("night");
    }
    </script>
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
        <h2 style="text-align:center;margin-top:24px;">تحديث مكان</h2>
        <p style="text-align:center;color:#5a6b62;margin-bottom:8px;">المعلومات الحالية</p>

        <?php if ($error): ?>
            <div class="error-msg" style="max-width:1100px;margin:10px auto;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="update-grid">
            <!-- لوحة عرض المعلومات الحالية -->
            <section class="panel">
                <h3>المكان المحدد للتحديث:</h3>
                <p style="color:#5a6b62;font-size:14px;margin-bottom:10px;">اسم المكان</p>
                <div class="read-field"><?= htmlspecialchars($place['name']) ?></div>

                <h3 style="margin-top:14px;">المعاينة</h3>
                <p style="color:#5a6b62;font-size:14px;margin-bottom:10px;">الصورة الرئيسية الحالية</p>
                <img src="../images/<?= htmlspecialchars($place['main_image']) ?>" alt="" style="width:100%;height:160px;object-fit:cover;border-radius:10px;background:#d6ddd8;">

                <p style="color:#5a6b62;font-size:14px;margin:14px 0 8px;">صور المعرض الحالية</p>
                <div class="current-images">
                    <?php if (!empty($place['gallery1'])): ?>
                        <img src="../images/<?= htmlspecialchars($place['gallery1']) ?>" alt="">
                    <?php endif; ?>
                    <?php if (!empty($place['gallery2'])): ?>
                        <img src="../images/<?= htmlspecialchars($place['gallery2']) ?>" alt="">
                    <?php endif; ?>
                    <?php if (!empty($place['gallery3'])): ?>
                        <img src="../images/<?= htmlspecialchars($place['gallery3']) ?>" alt="">
                    <?php endif; ?>
                </div>
            </section>

            <!-- لوحة تعديل البيانات -->
            <form class="panel" method="post" action="update.php" enctype="multipart/form-data">
                <h3>تعديل البيانات</h3>
                <input type="hidden" name="id" value="<?= $place['id'] ?>">
                <input type="hidden" name="current_main" value="<?= htmlspecialchars($place['main_image']) ?>">
                <input type="hidden" name="current_gallery1" value="<?= htmlspecialchars($place['gallery1']) ?>">
                <input type="hidden" name="current_gallery2" value="<?= htmlspecialchars($place['gallery2']) ?>">
                <input type="hidden" name="current_gallery3" value="<?= htmlspecialchars($place['gallery3']) ?>">

                <label>* اسم المكان</label>
                <input type="text" name="name" value="<?= htmlspecialchars($place['name']) ?>" required>

                <label>تحديث الصورة الرئيسية (اختياري)</label>
                <input type="file" name="main_image" accept="image/*">
                <p style="font-size:12px;color:#5a6b62;margin-top:4px;">اتركها فارغة للاحتفاظ بالصورة الحالية: <?= htmlspecialchars($place['main_image']) ?></p>

                <label>* الوصف</label>
                <textarea name="description" required><?= htmlspecialchars($place['description']) ?></textarea>

                <label>* الموقع</label>
                <input type="text" name="location" value="<?= htmlspecialchars($place['location']) ?>">

                <label>* التصنيف</label>
                <select name="category" required>
                    <?php $cats = ["وسطى","غربية","شرقية","جنوبية","شمالية"]; foreach ($cats as $c): ?>
                        <option value="<?= $c ?>" <?= $place['category'] === $c ? 'selected' : '' ?>><?= $c ?></option>
                    <?php endforeach; ?>
                </select>

                <label>* المميزات</label>
                <input type="text" name="features" value="<?= htmlspecialchars($place['features']) ?>">

                <label>* الأنشطة</label>
                <input type="text" name="activities" value="<?= htmlspecialchars($place['activities']) ?>">

                <label>* المعالم (افصل بينها بفاصلة)</label>
                <input type="text" name="landmarks" value="<?= htmlspecialchars($place['landmarks']) ?>">

                <div class="gallery-section">
                    <h3>تحديث صور المعرض (اختياري)</h3>
                    <p style="font-size:12px;color:#5a6b62;margin-bottom:8px;">اترك أي حقل فارغاً للاحتفاظ بالصورة الحالية.</p>

                    <label>صورة المعرض الأولى</label>
                    <input type="file" name="gallery1" accept="image/*">

                    <label>صورة المعرض الثانية</label>
                    <input type="file" name="gallery2" accept="image/*">

                    <label>صورة المعرض الثالثة</label>
                    <input type="file" name="gallery3" accept="image/*">
                </div>

                <button type="submit" class="btn submit-btn">حفظ التعديلات</button>
            </form>
        </div>
    </main>
</body>
</html>
<?php $stmt->close(); $conn->close(); ?>
