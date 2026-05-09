<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require '../db.php';

$message = "";
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'added')   $message = "تم إضافة السجل بنجاح";
    if ($_GET['msg'] === 'updated') $message = "تم تحديث السجل بنجاح";
    if ($_GET['msg'] === 'deleted') $message = "تم حذف السجل بنجاح";
}

$result = $conn->query("SELECT * FROM places ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المشرف</title>
    <script>
    if (localStorage.getItem("nightMode") === "1") {
        document.documentElement.classList.add("night");
    }
    </script>
    <link rel="stylesheet" href="admin.css">
    <script src="../script.js"></script>
</head>
<body>
    <header class="admin-navbar">
        <div class="brand">لوحة تحكم المشرف</div>
        <nav>
            <a href="../index.php">الموقع العام</a>
            <a href="logout.php" class="logout-btn">تسجيل الخروج</a>
        </nav>
    </header>

    <main class="dashboard-container">
        <?php if ($message): ?>
            <div class="success-msg"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <section class="section-card">
            <h2>إدارة المحتوى</h2>
            <p class="subtitle">استخدم هذه الصفحة لإدارة محتوى الموقع من خلال عرض السجلات وإضافة أو تعديل أو حذف المحتوى.</p>

            <div class="add-row">
                <a href="add.php" class="btn">إضافة محتوى جديد</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>المنطقة</th>
                        <th>التصنيف</th>
                        <th>الوصف</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['category']) ?></td>
                            <td><?= htmlspecialchars($row['description']) ?></td>
                            <td>
                                <div class="actions-cell">
                                    <a href="update.php?id=<?= $row['id'] ?>" class="btn btn-sm">تعديل</a>
                                    <a href="#" onclick="return confirmDelete(<?= $row['id'] ?>)" class="btn btn-sm btn-danger">حذف</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer class="footer" style="text-align:center;padding:18px;color:#5a6b62;font-size:14px;">
        © اكتشف السعودية - جامعة الملك سعود
    </footer>
</body>
</html>
<?php $conn->close(); ?>
