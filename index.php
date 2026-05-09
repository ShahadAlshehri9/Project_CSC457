<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اكتشف السعودية - الرئيسية</title>
    <script>
    if (localStorage.getItem("nightMode") === "1") {
        document.documentElement.classList.add("night");
    }
     </script>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
    <header class="navbar">
        <div class="brand">اكتشف السعودية</div>
        <nav>
            <a href="index.php" class="active">الرئيسية</a>
            <a href="gallery.php">معرض المناطق</a>
            <a href="admin/login.php">دخول المشرف</a>
            <button id="nightBtn" class="night-btn" onclick="toggleNight()">الوضع الليلي</button>
        </nav>
    </header>

    <main class="home-container">
        <div class="home-grid">
            <div class="card">
                <h2>موقع ثقافي تفاعلي للتعريف بالمملكة</h2>
                <p>استكشف مناطق المملكة العربية السعودية وتعرف على أهم المعالم التاريخية والثقافية. اختر منطقة من المعرض للانتقال إلى صفحة التفاصيل.</p>
                <a href="gallery.php" class="btn">ابدأ الاستكشاف</a>
            </div>
            <div class="welcome-card">
                <h1>أهلاً بك</h1>
                <p>ابدأ رحلتك لاكتشاف مناطق المملكة</p>
            </div>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <h3>الهدف</h3>
                <p>يهدف هذا الموقع لتلخيص ابرز المدن السياحية في المملكة واستعراض اهم التفاصيل واشهر المعالم. نأخدكم لرؤية المملكة من زاويا مختلفة</p>
            </div>
            <div class="feature-card">
                <h3>المناطق</h3>
                <p>معرض تفاعلي ينقلكم بين المناطق مع صور توضيحة للمعالم. تتميز المملكة العربيه السعوديه باختلاف التضاريس والبيئات بين المدن التنقل يشبه الذهاب الى عالم اخر!</p>
            </div>
            <div class="feature-card">
                <h3>التفاصيل</h3>
                <p>صفحة تعرض وصفاً وصوراً ومعلومات تاريخية عن المكان المختاريمكنكم بالنقر على ايقونة المنطقة في صفحة المناطق قراءة نبذة تاريخيه قصيره ومشوقه حيث كل منطقه تروي قصتها</p>
            </div>
        </div>
    </main>

    <footer class="footer">
        © اكتشف السعودية - جامعة الملك سعود
    </footer>
</body>
</html>
