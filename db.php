<?php
// إظهار الأخطاء أثناء التطوير
error_reporting(E_ALL);
ini_set('display_errors', '1');

// إعدادات الاتصال بقاعدة البيانات
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "discover_saudi";

// محاولة الاتصال بـ MySQL
$conn = @new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    // عرض رسالة خطأ واضحة بدلاً من شاشة بيضاء
    echo "<!DOCTYPE html><html lang='ar' dir='rtl'><head><meta charset='UTF-8'>";
    echo "<title>خطأ في الاتصال</title>";
    echo "<style>body{font-family:Tahoma,Arial;background:#f4f6f5;padding:40px;direction:rtl;}";
    echo ".box{max-width:700px;margin:auto;background:#fff;padding:28px;border-radius:12px;border:1px solid #e1e6e3;}";
    echo "h2{color:#c63838;margin-bottom:14px;} code{background:#f0f0f0;padding:2px 6px;border-radius:4px;}";
    echo "ol{padding-right:22px;line-height:2;}</style></head><body><div class='box'>";
    echo "<h2>تعذّر الاتصال بقاعدة البيانات</h2>";
    echo "<p><b>تفاصيل الخطأ:</b> " . htmlspecialchars($conn->connect_error) . "</p>";
    echo "<hr style='margin:18px 0;'>";
    echo "<p><b>تحقق من الخطوات التالية:</b></p>";
    echo "<ol>";
    echo "<li>افتح <b>XAMPP Control Panel</b> وتأكد أن خدمة <b>MySQL</b> تعمل (Start).</li>";
    echo "<li>افتح <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a></li>";
    echo "<li>اضغط على تبويب <b>Import</b> ثم اختر الملف <code>database.sql</code> من مجلد المشروع واضغط Go.</li>";
    echo "<li>تأكد أن قاعدة البيانات <code>discover_saudi</code> تظهر في القائمة اليسرى في phpMyAdmin.</li>";
    echo "<li>إذا كنت قد غيّرت كلمة مرور <code>root</code> في MySQL، عدّل ملف <code>db.php</code> وضع كلمة المرور في <code>\$pass</code>.</li>";
    echo "</ol>";
    echo "</div></body></html>";
    exit;
}

$conn->set_charset("utf8mb4");
?>
