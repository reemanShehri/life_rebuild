-- إنشاء قاعدة البيانات
CREATE DATABASE IF NOT EXISTS life_rebuild;
USE life_rebuild;

-- جدول المستخدمين
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    role ENUM('family', 'admin', 'organization') DEFAULT 'family',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);




--
-- جدول الأخصائيين
CREATE TABLE IF NOT EXISTS mental_specialists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    specialty VARCHAR(100) NOT NULL,
    avatar_letter VARCHAR(2) DEFAULT 'DR',
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- جدول الحجوزات
CREATE TABLE IF NOT EXISTS mental_appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    specialist_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    communication_method ENUM('call', 'video') DEFAULT 'video',
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (specialist_id) REFERENCES mental_specialists(id) ON DELETE CASCADE
);



--


CREATE TABLE IF NOT EXISTS volunteer_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    request_type VARCHAR(50) NOT NULL,
    details TEXT,
    contact_phone VARCHAR(20),
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

ALTER TABLE volunteer_requests
ADD COLUMN contact_method VARCHAR(20) DEFAULT 'phone',
ADD COLUMN contact_value VARCHAR(100);

--


CREATE TABLE IF NOT EXISTS support_team (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(100) NOT NULL,
    avatar_letters VARCHAR(2) NOT NULL,
    avatar_color VARCHAR(20) DEFAULT 'blue', -- blue, pink, green, etc.
    status ENUM('online', 'away', 'offline') DEFAULT 'online',
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE
);

-- إدخال بيانات أولية (يمكن تعديلها لاحقاً)
INSERT INTO support_team (name, role, avatar_letters, avatar_color, status, display_order) VALUES
('سارة حسن', 'مسؤولة الحالة الرئيسية', 'سح', 'blue', 'online', 1),
('د. رامي يوسف', 'مستشار الصحة النفسية', 'دي', 'pink', 'away', 2);


--
-- جدول المقالات
CREATE TABLE IF NOT EXISTS mental_articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    category VARCHAR(100) NOT NULL,
    read_time VARCHAR(50) NOT NULL,
    badge_text VARCHAR(50) NOT NULL,
    badge_color VARCHAR(30) DEFAULT 'pink',
    card_color VARCHAR(30) DEFAULT 'pink',
    content TEXT,
    link VARCHAR(255) DEFAULT '#',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- جدول الخدمات السريعة
CREATE TABLE IF NOT EXISTS mental_crisis_lines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL,
    action_text VARCHAR(100) NOT NULL,
    icon_class VARCHAR(50) NOT NULL,
    bg_color VARCHAR(50) DEFAULT 'pink',
    is_active BOOLEAN DEFAULT TRUE
);

-- جدول النصائح اليومية
CREATE TABLE IF NOT EXISTS mental_daily_tips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tip_text TEXT NOT NULL,
    display_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- إدراج بيانات أولية (يمكنك تعديلها أو إضافتها عبر لوحة الإدارة لاحقاً)
INSERT INTO mental_specialists (name, specialty, avatar_letter) VALUES
('د. ليلى أحمد', 'استشارات نفسية', 'LA'),
('د. كريم يوسف', 'علاج الصدمات', 'KY'),
('د. مريم حسن', 'استشارات عائلية', 'MH');

INSERT INTO mental_articles (title, category, read_time, badge_text, badge_color, card_color) VALUES
('كيفية التعامل مع التوتر في أوقات الأزمات', 'إدارة التوتر', '5 دقائق', 'إدارة التوتر', 'pink', 'pink'),
('دعم الأطفال نفسياً خلال النزوح', 'دعم الأطفال', '7 دقائق', 'دعم الأطفال', 'purple', 'purple'),
('تقنيات التنفس للتهدئة السريعة', 'تمارين التهدئة', '3 دقائق', 'تمارين التهدئة', 'blue', 'blue'),
('بناء الصلابة النفسية بعد الصدمة', 'التعافي', '10 دقائق', 'التعافي', 'green', 'green');

INSERT INTO mental_crisis_lines (title, description, action_text, icon_class, bg_color) VALUES
('خط الأزمات', 'متاح 24/7', 'SUPPORT-1800', 'fa-phone-alt', 'pink'),
('جلسات فورية', 'بدون موعد مسبق', 'ابدأ جلسة الآن', 'fa-video', 'purple'),
('مكتبة الموارد', 'أكثر من 50 مقال', 'مجانية', 'fa-book-open', 'blue');

INSERT INTO mental_daily_tips (tip_text, display_date) VALUES
('خذ بضع دقائق كل يوم للتنفس العميق والتأمل، هذا يساعد على تقليل التوتر وتحسين الصحة النفسية.', CURDATE());


--


-- جدول طلبات المساعدة
CREATE TABLE aid_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    request_type ENUM('document_recovery', 'housing', 'education', 'healthcare', 'psychosocial', 'other') NOT NULL,
    description TEXT NOT NULL,
    status ENUM('pending', 'approved', 'assigned', 'completed', 'rejected') DEFAULT 'pending',
    assigned_to VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- جدول الوثائق المرفوعة
CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (request_id) REFERENCES aid_requests(id) ON DELETE CASCADE
);

-- جدول الإشعارات
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- مستخدم تجريبي (admin/admin123)
INSERT INTO users (full_name, email, phone, password, role) VALUES
('Admin', 'admin@liferebuild.com', '123456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');


ALTER TABLE volunteer_requests ADD COLUMN contact_phone VARCHAR(100);


ALTER TABLE `mental_specialists`
ADD COLUMN `phone` VARCHAR(20) NULL AFTER `is_available`,
ADD COLUMN `whatsapp` VARCHAR(20) NULL AFTER `phone`;

UPDATE `mental_specialists` SET `phone` = '+970591112222', `whatsapp` = '+970591112222' WHERE `id` = 1;
UPDATE `mental_specialists` SET `phone` = '+970592223333', `whatsapp` = '+970592223333' WHERE `id` = 2;

ALTER TABLE `support_team`
ADD COLUMN `phone` VARCHAR(20) NULL AFTER `status`,
ADD COLUMN `whatsapp` VARCHAR(20) NULL AFTER `phone`;
