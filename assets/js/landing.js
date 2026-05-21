// ========== LANDING PAGE ANIMATIONS ==========
document.addEventListener('DOMContentLoaded', function() {

    // 1. تطبيق تأثير Fade-in على العناصر عند التمرير
    const fadeElements = document.querySelectorAll('.service-card, .hero, .cta-section');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-visible');
                // نوقف المراقبة بعد الظهور
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });

    fadeElements.forEach(el => {
        el.classList.add('fade-element');
        observer.observe(el);
    });

    // 2. تأثير ظهور تدريجي للخدمات مع تأخير
    const serviceCards = document.querySelectorAll('.service-card');
    serviceCards.forEach((card, index) => {
        card.style.transitionDelay = `${index * 0.1}s`;
        card.classList.add('fade-element');
        observer.observe(card);
    });

    // 3. تأثير تموج خفيف على الأزرار
    const buttons = document.querySelectorAll('.btn-primary, .btn-outline, .btn-cta, .service-btn');
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', function(e) {
            this.style.transform = 'scale(1.05)';
            this.style.transition = 'transform 0.2s';
        });
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });

    // 4. تأثير حركة دائري خفيف على الأيقونات عند hover
    const icons = document.querySelectorAll('.service-icon');
    icons.forEach(icon => {
        icon.addEventListener('mouseenter', function() {
            this.style.transform = 'rotate(10deg) scale(1.1)';
            this.style.transition = 'transform 0.2s';
        });
        icon.addEventListener('mouseleave', function() {
            this.style.transform = 'rotate(0deg) scale(1)';
        });
    });

    // 5. تأثير كتابة متحركة للعنوان (اختياري)
    const heroTitle = document.querySelector('.hero h1');
    if (heroTitle) {
        const originalText = heroTitle.innerText;
        heroTitle.innerText = '';
        let i = 0;
        function typeWriter() {
            if (i < originalText.length) {
                heroTitle.innerText += originalText.charAt(i);
                i++;
                setTimeout(typeWriter, 100);
            }
        }
        typeWriter();
    }

    // 6. إضافة خلفية متحركة (موجات أو دوائر) – تأثير بسيط
    const hero = document.querySelector('.hero');
    if (hero && !document.querySelector('.hero-bg-animation')) {
        const bgDiv = document.createElement('div');
        bgDiv.className = 'hero-bg-animation';
        bgDiv.innerHTML = '<div class="circle1"></div><div class="circle2"></div>';
        hero.style.position = 'relative';
        hero.style.overflow = 'hidden';
        hero.insertBefore(bgDiv, hero.firstChild);
    }
});
