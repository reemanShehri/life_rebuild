# استخدام صورة PHP رسمية مع Apache
FROM php:8.2-apache

# تحديث مدير الحزم وتثبيت أدوات مساعدة (اختياري لكن مفيد)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# ⭐ الأمر السحري: تثبيت PDO و PDO_MySQL (هذا هو حل مشكلتك الأساسي)
# قمنا بكتابة pdo أولاً ثم pdo_mysql لضمان التثبيت الصحيح
RUN docker-php-ext-install pdo pdo_mysql mysqli

# تمكين إعادة الكتابة (mod_rewrite)
RUN a2enmod rewrite

# نسخ جميع ملفات المشروع إلى مجلد الخادم
COPY . /var/www/html/

# منح الصلاحيات
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# (خطوة تشخيصية) أمر لعرض الإضافات المثبتة في سجل البناء للتأكد من وجود pdo_mysql
RUN php -m | grep pdo

# إبقاء الخدمة مشغلة
CMD ["apache2-foreground"]
