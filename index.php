<?php
require_once 'config/database.php';

$page = $_GET['page'] ?? 'home';

switch($page) {
    case 'login':
        require_once 'controllers/AuthController.php';
        $auth = new AuthController($pdo);
        $auth->login();
        break;
    case 'register':
        require_once 'controllers/AuthController.php';
        $auth = new AuthController($pdo);
        $auth->register();
        break;
    case 'logout':
        require_once 'controllers/AuthController.php';
        $auth = new AuthController($pdo);
        $auth->logout();
        break;
    case 'dashboard':
        if(!isset($_SESSION['user_id'])) header('Location: index.php?page=login');
        if($_SESSION['role'] == 'admin') {
            require_once 'controllers/AdminController.php';
            $admin = new AdminController($pdo);
            $admin->index();
        } else {
            require_once 'controllers/RequestController.php';
            $req = new RequestController($pdo);
            $req->dashboard();
        }
        break;

        case 'my_volunteer_requests':
    if(!isset($_SESSION['user_id'])) header('Location: index.php?page=login');
    require_once 'controllers/VolunteerController.php';
    $volunteer = new VolunteerController($pdo);
    $volunteer->myRequests();
    break;

    
    case 'create_request':
        if(!isset($_SESSION['user_id'])) header('Location: index.php?page=login');
        require_once 'controllers/RequestController.php';
        $req = new RequestController($pdo);
        $req->create();
        break;
    case 'my_requests':
        if(!isset($_SESSION['user_id'])) header('Location: index.php?page=login');
        require_once 'controllers/RequestController.php';
        $req = new RequestController($pdo);
        $req->myRequests();
        break;

        case 'profile':
    if(!isset($_SESSION['user_id'])) header('Location: index.php?page=login');
    require_once 'controllers/ProfileController.php';
    $profile = new ProfileController($pdo);
    $profile->index();
    break;


    case 'roadmap':
    if(!isset($_SESSION['user_id'])) header('Location: index.php?page=login');
    require_once 'controllers/RoadmapController.php';
    $roadmap = new RoadmapController($pdo);
    $roadmap->index();
    break;



    case 'losses':
    if(!isset($_SESSION['user_id'])) header('Location: index.php?page=login');
    require_once 'controllers/LossController.php';
    $loss = new LossController($pdo);
    $loss->index();
    break;
case 'add_loss':
    if(!isset($_SESSION['user_id'])) header('Location: index.php?page=login');
    require_once 'controllers/LossController.php';
    $loss = new LossController($pdo);
    $loss->add();
    break;
case 'delete_loss':
    if(!isset($_SESSION['user_id'])) header('Location: index.php?page=login');
    require_once 'controllers/LossController.php';
    $loss = new LossController($pdo);
    $loss->delete();
    break;

    case 'volunteer_requests':
    $volunteerRequests = $this->getVolunteerRequests();
    $content = 'views/admin/volunteer_requests.php';
    break;

    case 'mental':
    require_once 'controllers/MentalController.php';
    $mental = new MentalController($pdo);
    $mental->index();
    break;


    case 'volunteer':
    require_once 'controllers/VolunteerController.php';
    $vol = new VolunteerController($pdo);
    $vol->index();
    break;
case 'volunteer_request':
    if(!isset($_SESSION['user_id'])) header('Location: index.php?page=login');
    require_once 'controllers/VolunteerController.php';
    $vol = new VolunteerController($pdo);
    $vol->request();
    break;

    default:
        // ========== الصفحة الرئيسية الجديدة ==========
        include 'views/partials/header.php';
        ?>
        <link rel="stylesheet" href="assets/css/landing.css">
        <main class="landing-main">
            <!-- Hero Section -->
            <section class="hero" style="background: linear-gradient(135deg, #e0f2f1, #5f9e4c); color: #1e3c2c;">
                <h1>منصة Life-Rebuild</h1>
                <p>منصة ذكية لدعم العائلات النازحة في إعادة بناء حياتهم بكرامة</p>
                <div class="hero-buttons">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="index.php?page=dashboard" class="btn-primary">لوحة التحكم</a>
                    <?php else: ?>
                        <a href="index.php?page=register" class="btn-primary">ابدأ رحلتك</a>
                        <a href="index.php?page=login" class="btn-outline">تسجيل مشاركات</a>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Services Section -->
            <section class="services">
                <h2 class="section-title">خدماتنا الأساسية</h2>
                <div class="services-grid">
                    <div class="service-card">
                        <div class="service-icon">🧠</div>
                        <h3>الدعم النفسي</h3>
                        <p>احصل على موارد الصحة النفسية والاستشارة للمساعدة على تحقيق الاستقرار النفسي.</p>
                        <button class="service-btn" onclick="window.location.href='<?= isset($_SESSION['user_id']) ? 'index.php?page=mental' : 'index.php?page=login' ?>'">اطلب الدعم</button>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">🤝</div>
                        <h3>تنسيق المتطوعين</h3>
                        <p>تواصل مع المتطوعين والمنظمات لتقديم المساعدة بناءً على احتياجاتك.</p>
                        <button class="service-btn" onclick="window.location.href='<?= isset($_SESSION['user_id']) ? 'index.php?page=volunteer' : 'index.php?page=login' ?>'">اطلب مساعدة</button>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">🗺️</div>
                        <h3>خريطة العودة</h3>
                        <p>افتح خارطة طريق شخصية لإعادة بناء حياتك خطوة بخطوة.</p>
                        <button class="service-btn" onclick="window.location.href='<?= isset($_SESSION['user_id']) ? 'index.php?page=roadmap' : 'index.php?page=login' ?>'">خطط لعودتك</button>
                    </div>
                   <div class="service-card">
    <div class="service-icon">📋</div>
    <h3>توثيق الخسائر</h3>
    <p>وثق ممتلكاتك وأضرارك بأمان لتسهيل عملية التعويض.</p>
    <button class="service-btn" onclick="window.location.href='<?= isset($_SESSION['user_id']) ? 'index.php?page=losses' : 'index.php?page=login' ?>'">ابدأ التوثيق</button>
</div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="cta-section" style="background: linear-gradient(135deg, #e0f2f1, #5f9e4c); color: #1e3c2c;">
                <h2>نحن هنا لمساعدتك على إعادة البناء</h2>
                <p>انضم إلى آلاف العائلات الذين بدأوا بالفعل رحلة التعافي. منصتنا مصممة خصيصاً لك - آمنة وسهلة ومتطورة.</p>
                <?php if(!isset($_SESSION['user_id'])): ?>
                    <a href="index.php?page=register" class="btn-cta">ابدأ اليوم</a>
                <?php else: ?>
                    <a href="index.php?page=create_request" class="btn-cta">قدم طلب مساعدة</a>
                <?php endif; ?>
            </section>
        </main>
        <script src="assets/js/landing.js"></script>
        <?php
        include 'views/partials/footer.php';

}
?>
