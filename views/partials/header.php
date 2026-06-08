<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Life-Rebuild | إعادة بناء الحياة</title>
    <link rel="stylesheet" href="assets/css/style.css">
<link rel="icon" href="data:image/svg+xml,
<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'>
  <rect x='5' y='5' width='90' height='90' rx='20' fill='%234A7DFF'/>
  <path d='M50 72 L44 66 C25 49 15 39 15 25 C15 15 23 8 33 8 C40 8 46 12 50 18 C54 12 60 8 67 8 C77 8 85 15 85 25 C85 39 75 49 56 66 Z' fill='white'/>
</svg>">    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* ========== تنسيقات الهيدر والفوتر الجديدة ========== */
        header {
            background: linear-gradient(135deg, #ffffff, #ffffff);
            color: #2c3e50;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .logo h1 {
            font-size: 1.8rem;
            margin-bottom: 0.2rem;
            letter-spacing: 1px;
            color: #1e3c2c;
        }
        .logo p {
            font-size: 0.8rem;
            opacity: 0.8;
            color: #5d6d7e;
        }
        nav ul {
            list-style: none;
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }
        nav ul li a {
            color: #2c3e50;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 30px;
            transition: all 0.3s ease;
        }
        nav ul li a:hover {
            background: rgba(30, 60, 44, 0.1);
            color: #1e3c2c;
            transform: translateY(-2px);
        }
        .logout-btn {
            background: #e74c3c;
            color: white !important;
            border-radius: 30px;
        }
        .logout-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        /* ========== تنسيقات الفوتر (ستؤثر على footer.php) ========== */
        footer {
            background: #f1f2f6;
            color: #2c3e50;
            margin-top: 3rem;
            border-top: 1px solid #dcdde1;
        }
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 2rem 1rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 2rem;
        }
        .footer-section {
            flex: 1;
            min-width: 200px;
        }
        .footer-section h3 {
            margin-bottom: 1rem;
            font-size: 1.2rem;
            border-right: 3px solid #f39c12;
            padding-right: 0.5rem;
            color: #1e3c2c;
        }
        .footer-section p,
        .footer-section ul {
            font-size: 0.9rem;
            line-height: 1.8;
            color: #5d6d7e;
        }
        .footer-section ul {
            list-style: none;
        }
        .footer-section ul li a {
            color: #5d6d7e;
            text-decoration: none;
            transition: color 0.3s;
        }
        .footer-section ul li a:hover {
            color: #f39c12;
        }
        .copyright {
            text-align: center;
            padding: 1rem;
            border-top: 1px solid #dcdde1;
            font-size: 0.8rem;
            background: #e9ecef;
            color: #5d6d7e;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">

<h1>
    <svg width="32" height="32" viewBox="0 0 100 100" style="vertical-align:middle; margin-left:8px;">
        <rect x="5" y="3" width="90" height="80" rx="20" fill="#4A7DFF"/>
        <path d="M50 72 L44 66 C25 49 15 39 15 25 C15 15 23 8 33 8 C40 8 46 12 50 18 C54 12 60 8 67 8 C77 8 85 15 85 25 C85 39 75 49 56 66 Z" fill="white"/>
    </svg>
    🇵🇸 Life-Rebuild
</h1>                <p>منصة دعم العائلات بعد الحرب</p>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">الرئيسية</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li><a href="index.php?page=dashboard">لوحة التحكم</a></li>
                        <li><a href="index.php?page=create_request">طلب مساعدة</a></li>
                        <li><a href="index.php?page=my_requests">طلباتي</a></li>
                        <li><a href="index.php?page=logout" class="logout-btn">تسجيل خروج</a></li>
                    <?php else: ?>
                        <li><a href="index.php?page=login">تسجيل الدخول</a></li>
                        <li><a href="index.php?page=register">إنشاء حساب</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main>
