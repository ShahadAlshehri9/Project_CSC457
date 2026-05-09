<?php
session_start();
require '../db.php';

// إذا كان مسجلاً بالفعل
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = "الرجاء إدخال اسم المستخدم وكلمة المرور.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($row = $res->fetch_assoc()) {
            if ($password === $row['password']) {
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_username'] = $row['username'];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "كلمة المرور غير صحيحة.";
            }
        } else {
            $error = "اسم المستخدم غير موجود.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول المشرف</title>
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
            <a href="../index.php">زيارة الموقع</a>
        </nav>
    </header>

    <main class="login-wrap">
        <form class="login-card" method="post" action="login.php">
            <h2>تسجيل دخول المشرف</h2>

            <?php if ($error !== ""): ?>
                <div class="error-msg"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <label>اسم المستخدم</label>
            <input type="text" name="username" placeholder="مثال: admin" required>

            <label>كلمة المرور</label>
            <input type="password" name="password" placeholder="••••••••" required>

            <button type="submit" class="btn">دخول</button>
        </form>
    </main>
</body>
</html>
<?php $conn->close(); ?>
