# استخدام صورة PHP رسمية مع Apache
FROM php:8.2-apache

# تثبيت إضافات PHP المطلوبة (وهذا يحل مشكلة "could not find driver" نهائياً)
RUN docker-php-ext-install pdo pdo_mysql mysqli

# تمكين إعادة الكتابة (mod_rewrite) لدعم الروابط الجميلة (اختياري)
RUN a2enmod rewrite

# نسخ جميع ملفات المشروع إلى مجلد الخادم داخل الحاوية
COPY . /var/www/html/

# منح الصلاحيات اللازمة لملفات المشروع
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# إبقاء الخدمة مشغلة (الأمر الافتراضي في الصورة)
CMD ["apache2-foreground"]
