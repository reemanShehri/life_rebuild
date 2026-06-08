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

        case 'areas':
    $areas = $this->getAllAreas();
    $governorates = $this->getGovernorates(); // لازم نضيف هذي الدالة
    $content = 'views/admin/areas.php';
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

//

case 'book_appointment':
    if(!isset($_SESSION['user_id'])) header('Location: index.php?page=login');
    require_once 'controllers/MentalController.php';
    $mental = new MentalController($pdo);
    $mental->bookAppointment();
    break;

case 'cancel_appointment':
    if(!isset($_SESSION['user_id'])) header('Location: index.php?page=login');
    require_once 'controllers/MentalController.php';
    $mental = new MentalController($pdo);
    $mental->cancelAppointment();
    break;

    //


    case 'cancel_volunteer_request':
    if(!isset($_SESSION['user_id'])) header('Location: index.php?page=login');
    require_once 'controllers/VolunteerController.php';
    $vol = new VolunteerController($pdo);
    $vol->cancelRequest();
    break;

    //



    case 'admin':
    if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') header('Location: index.php?page=login');
    require_once 'controllers/AdminController.php';
    $admin = new AdminController($pdo);
    $admin->index();
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
            <section class="hero" style="background: linear-gradient(135deg, #ffffff, #e3f4ff); color: #000000;">
            <!-- <h2>دعم العائلات في غزة</h2> -->

            <h1>منصة Life-Rebuild</h1>
                <p>منصة ذكية لدعم العائلات النازحة في إعادة بناء حياتهم بكرامة</p>
                <p>تمكين العائلات النازحة من إعادة بناء حياتهم بكرامة من خلال التوثيق الشامل والتخطيط والدعم المجتمعي.</p>

                <br>
                <div class="hero-buttons">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="index.php?page=dashboard" class="btn-outline" style="color:black; background : #a7b6ff;">لوحة التحكم</a>
                    <?php else: ?>
                        <a href="index.php?page=register" class="btn-primary" style="color:black; background : #6c85ff;">ابدأ رحلتك</a>
                        <a href="index.php?page=login" class="btn-outline"style="color:black; background : #ffffff;">تسجيل مشاركات</a>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Services Section -->

        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
        <style>
            /* الأساسيات - نفس الثيم الأبيض والأسود */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            body {
                font-family: 'Tajawal', 'Segoe UI', sans-serif;
                background: #ffffff;
                color: #1e1e1e;
                line-height: 1.5;
            }
            .page-rebuild {
                max-width: 1200px;
                margin: 0 auto;
                padding: 2rem 1.5rem;
                text-align: center;
            }
            /* Hero section (حسب الصورة السابقة) */
            .page-rebuild h2 {
                font-size: 1.8rem;
                font-weight: 600;
                color: #2c3e50;
                margin-bottom: 0.5rem;
            }
            .page-rebuild h1 {
                font-size: 2.5rem;
                font-weight: 800;
                color: #111;
                margin-bottom: 1rem;
            }
            .page-rebuild .description {
                font-size: 1rem;
                color: #4a4a4a;
                max-width: 700px;
                margin: 0 auto 2rem;
            }
            .btn-group {
                display: flex;
                justify-content: center;
                gap: 2rem;
                flex-wrap: wrap;
                margin-bottom: 3rem;
            }
            .btn-link {
                text-decoration: none;
                font-size: 1.1rem;
                font-weight: 600;
                color: #111;
                border-bottom: 2px solid #111;
                padding-bottom: 4px;
                transition: 0.2s;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                background: none;
                cursor: pointer;
            }
            .btn-link:hover {
                color: #555;
                border-bottom-color: #555;
            }
            /* ---------- الخدمات الجديدة (services-container) ---------- */
            .services-container {
                max-width: 1200px;
                margin: 3rem auto;
                padding: 0 1rem;
            }
            .services-header {
                text-align: center;
                margin-bottom: 2.5rem;
            }
            .services-header h2 {
                font-size: 2rem;
                color: #111;
                margin-bottom: 0.5rem;
            }
            .services-header p {
                color: #4a4a4a;
                font-size: 1rem;
            }
            .services-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
                gap: 2rem;
            }
            .card {
                background: #fff;
                border-radius: 1.5rem;
                padding: 2rem 1.5rem;
                text-align: center;
                transition: transform 0.3s, box-shadow 0.3s;
                box-shadow: 0 4px 12px rgba(0,0,0,0.05);
                border: 1px solid #f0f0f0;
            }
            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 12px 24px rgba(0,0,0,0.1);
            }
            .icon-box-container {
                display: flex;
                justify-content: center;
                margin-bottom: 1.5rem;
            }
            .icon-box {
                width: 70px;
                height: 70px;
                border-radius: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #f5f5f5;
                transition: 0.3s;
            }
            .card-blue .icon-box { background: #e8f0fe; }
            .card-green .icon-box { background: #e6f7ec; }
            .card-purple .icon-box { background: #f0e8fe; }
            .card-pink .icon-box { background: #fce8f0; }
            .icon-box svg {
                width: 36px;
                height: 36px;
                stroke-width: 1.5;
            }
            .card-blue svg { stroke: #2c6e9e; color: #2c6e9e; }
            .card-green svg { stroke: #2e7d5e; color: #2e7d5e; }
            .card-purple svg { stroke: #7b4f9e; color: #7b4f9e; }
            .card-pink svg { stroke: #d45a7a; color: #d45a7a; fill: none; }
            .heart-icon { fill: none; }
            .card h3 {
                font-size: 1.4rem;
                margin: 1rem 0 0.5rem;
                color: #111;
            }
            .card p {
                color: #5a5a5a;
                font-size: 0.9rem;
                line-height: 1.5;
                margin-bottom: 1.5rem;
            }
            .link {
                text-decoration: none;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 0.3rem;
                font-size: 0.95rem;
                transition: 0.2s;
            }
            .card-blue .link { color: #2c6e9e; }
            .card-green .link { color: #2e7d5e; }
            .card-purple .link { color: #7b4f9e; }
            .card-pink .link { color: #d45a7a; }
            .link:hover { gap: 0.5rem; }
            .arrow {
                font-size: 1.1rem;
                transition: transform 0.2s;
            }
            .link:hover .arrow {
                transform: translateX(4px);
            }
            @media (max-width: 768px) {
                .services-grid { grid-template-columns: 1fr; }
                .page-rebuild h1 { font-size: 2rem; }
            }
        </style>

        <main class="page-rebuild">
            <!-- Hero Section (كما في الصورة) -->


            <!-- قسم الخدمات الأساسية الجديد -->
            <div class="services-container">
                <div class="services-header">
                    <h2>خدماتنا الأساسية</h2>
                    <p>دعم شامل مصمم لمساعدتك على إعادة البناء والتعافي بتعاطف وكفاءة</p>
                </div>
                <div class="services-grid">
                    <!-- توثيق الخسائر -->
                    <div class="card card-blue">
                        <div class="icon-box-container">
                            <div class="icon-box">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                            </div>
                        </div>
                        <h3>توثيق الخسائر</h3>
                        <p>وثق وتحقق من خسائرك بأمان مع عمليتنا البسيطة خطوة بخطوة.</p>
                        <a href="<?= isset($_SESSION['user_id']) ? 'index.php?page=losses' : 'index.php?page=login' ?>" class="link">ابدأ التوثيق <span class="arrow">➔</span></a>
                    </div>

                    <!-- تخطيط العودة -->
                    <div class="card card-green">
                        <div class="icon-box-container">
                            <div class="icon-box">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </div>
                        </div>
                        <h3>تخطيط العودة</h3>
                        <p>أنشئ خارطة طريق شخصية للعودة إلى المنزل وإعادة بناء حياتك خطوة بخطوة.</p>
                        <a href="<?= isset($_SESSION['user_id']) ? 'index.php?page=roadmap' : 'index.php?page=login' ?>" class="link">خطط لعودتك <span class="arrow">➔</span></a>
                    </div>

                    <!-- تنسيق المتطوعين -->
                    <div class="card card-purple">
                        <div class="icon-box-container">
                            <div class="icon-box">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 1 1 0 7.75"></path>
                                </svg>
                            </div>
                        </div>
                        <h3>تنسيق المتطوعين</h3>
                        <p>تواصل مع المتطوعين والمنظمات غير الحكومية المستعدة لتقديم المساعدة وخدمات الدعم.</p>
                        <a href="<?= isset($_SESSION['user_id']) ? 'index.php?page=volunteer' : 'index.php?page=login' ?>" class="link">ابحث عن الدعم <span class="arrow">➔</span></a>
                    </div>

                    <!-- الدعم النفسي -->
                    <div class="card card-pink">
                        <div class="icon-box-container">
                            <div class="icon-box">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="heart-icon">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                </svg>
                            </div>
                        </div>
                        <h3>الدعم النفسي</h3>
                        <p>احصل على موارد الصحة النفسية والاستشارة لمساعدتك على التأقلم والشفاء خلال هذا الوقت.</p>
                        <a href="<?= isset($_SESSION['user_id']) ? 'index.php?page=mental' : 'index.php?page=login' ?>" class="link">احصل على المساعدة <span class="arrow">➔</span></a>
                    </div>
                </div>
            </div>
        </main>


            <!-- CTA Section -->
            <section class="cta-section" style="background: linear-gradient(135deg, #fbffff, #0b5dbc); color: #000000;">
                <h2>نحن هنا لمساعدتك على إعادة البناء</h2>
                <p>انضم إلى آلاف العائلات الذين بدأوا بالفعل رحلة التعافي. منصتنا مصممة خصيصاً لك - آمنة وسهلة ومتطورة.</p>
                <?php if(!isset($_SESSION['user_id'])): ?>
                    <a href="index.php?page=register" class="btn-cta" style="color:black; background : #f5f7ff;">ابدأ اليوم </a>
                <?php else: ?>
                    <a href="index.php?page=create_request" class="btn-cta" style="color:black; background : #a7b6ff;">قدم طلب مساعدة</a>
                <?php endif; ?>
            </section>
        </main>
        <script src="assets/js/landing.js"></script>
        <?php
        include 'views/partials/footer.php';

}
?>
