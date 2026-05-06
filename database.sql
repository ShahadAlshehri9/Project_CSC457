-- قاعدة بيانات: discover_saudi
CREATE DATABASE IF NOT EXISTS discover_saudi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE discover_saudi;

-- جدول المشرفين
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- بيانات المشرف الافتراضي
-- اسم المستخدم: admin   /   كلمة المرور: admin123
INSERT INTO admins (username, password) VALUES ('admin', 'admin123');

-- جدول الأماكن
CREATE TABLE IF NOT EXISTS places (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(150) NOT NULL,
    features TEXT NOT NULL,
    activities TEXT NOT NULL,
    landmarks TEXT NOT NULL,
    main_image VARCHAR(255) NOT NULL,
    gallery1 VARCHAR(255) DEFAULT NULL,
    gallery2 VARCHAR(255) DEFAULT NULL,
    gallery3 VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- بيانات تجريبية
INSERT INTO places (name, category, description, location, features, activities, landmarks, main_image, gallery1, gallery2, gallery3) VALUES
('الرياض', 'وسطى', 'الرياض هي عاصمة المملكة العربية السعودية ومركزها السياسي والاقتصادي، تعبر مزيج من الحداثة والتراث التاريخي.', 'وسط المملكة العربية السعودية', 'موقع المنطقة الوسطى, تجمع بين الحداثة والتراث, مركز اقتصادي رئيسي', 'زيارة المتاحف والأسواق, التسوق في المراكز الحديثة, استكشاف المعالم التاريخية', 'برج المملكة, قصر المصمك, الدرعية', 'riyadh.jpg', 'riyadh-1.jpg', 'riyadh-2.jpg', 'riyadh-3.jpg'),
('مكة المكرمة', 'غربية', 'مدينة دينية يقصدها المسلمون للحج والعمرة، وهي وجهة دينية وتاريخية عالمية.', 'غرب المملكة العربية السعودية', 'وجهة دينية عالمية, مهبط الوحي, مقصد الحجاج والمعتمرين', 'أداء العمرة والحج, زيارة المسجد الحرام, زيارة المعالم الإسلامية', 'المسجد الحرام, جبل النور, جبل ثور', 'makkah.jpg', 'makkah-1.jpg', 'makkah-2.jpg', 'makkah-3.jpg'),
('العلا', 'غربية', 'كنوز أثرية ومعالم طبيعية فريدة، مدينة تاريخية تجمع بين التراث والطبيعة.', 'شمال غرب المملكة العربية السعودية', 'مواقع أثرية مسجلة عالمياً, طبيعة جبلية خلابة, تاريخ يمتد لآلاف السنين', 'استكشاف مدائن صالح, ركوب المنطاد, جولات صحراوية', 'مدائن صالح, جبل الفيل, البلدة القديمة', 'alula.jpg', 'alula-1.jpg', 'alula-2.jpg', 'alula-3.jpg'),
('الخبر', 'شرقية', 'واجهة بحرية حديثة، مدينة عصرية على شاطئ الخليج العربي.', 'شرق المملكة العربية السعودية', 'إطلالة على الخليج العربي, مدينة حديثة ومتطورة, مركز تجاري مهم', 'التنزه على الكورنيش, زيارة المراكز التجارية, رياضات بحرية', 'كورنيش الخبر, جسر الملك فهد, الواجهة البحرية', 'khobar.jpg', 'khobar-1.jpg', 'khobar-2.jpg', 'khobar-3.jpg'),
('أبها', 'جنوبية', 'مدينة جبلية ذات طبيعة خلابة وأجواء معتدلة طوال العام.', 'جنوب غرب المملكة العربية السعودية', 'أجواء معتدلة, طبيعة جبلية خضراء, ضباب وأمطار صيفية', 'التنزه في المتنزهات, ركوب التلفريك, زيارة القرى التراثية', 'جبل السودة, قرية رجال ألمع, متنزه عسير', 'abha.jpg', 'abha-1.jpg', 'abha-2.jpg', 'abha-3.jpg'),
('تبوك', 'شمالية', 'مدينة تاريخية في شمال المملكة، تضم مواقع أثرية وطبيعة متنوعة.', 'شمال غرب المملكة العربية السعودية', 'مواقع تاريخية وأثرية, طبيعة متنوعة, مشاريع سياحية حديثة', 'زيارة قلعة تبوك, استكشاف نيوم, التنزه في الشواطئ', 'قلعة تبوك, محطة سكة حديد الحجاز, نيوم', 'tabuk.jpg', 'tabuk-1.jpg', 'tabuk-2.jpg', 'tabuk-3.jpg');
