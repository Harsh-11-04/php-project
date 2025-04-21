<?php
session_start();
if (!isset($_SESSION['username'])) {
    $_SESSION['flash_message'] = "You must log in to provide feedback.";
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback | R&D Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Space Grotesk', sans-serif;
        }
        
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: #0a0a0a;
            color: #fff;
            overflow-x: hidden;
        }
        
        /* Background effect */
        .cyberpunk-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-color: #0a0a0a;
            overflow: hidden;
        }
        
        .grid {
            position: absolute;
            width: 200%;
            height: 200%;
            top: -50%;
            left: -50%;
            background-image: 
                linear-gradient(rgba(78, 84, 200, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(78, 84, 200, 0.1) 1px, transparent 1px);
            background-size: 40px 40px;
            transform: perspective(500px) rotateX(60deg);
            animation: grid-move 20s linear infinite;
        }
        
        @keyframes grid-move {
            0% {
                transform: perspective(500px) rotateX(60deg) translateY(0);
            }
            100% {
                transform: perspective(500px) rotateX(60deg) translateY(40px);
            }
        }
        
        .cyber-glow {
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 50%, rgba(78, 84, 200, 0.2), transparent 60%);
            pointer-events: none;
        }
        
        .content-wrapper {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: 100%;
        }
        
        /* Top navigation bar */
        .topnav {
            background: rgba(15, 15, 20, 0.8);
            backdrop-filter: blur(10px);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(78, 84, 200, 0.3);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo-section img {
            height: 40px;
            filter: drop-shadow(0 0 8px rgba(78, 84, 200, 0.6));
        }
        
        .logo-text {
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .logo-text span {
            color: #4e54c8;
        }
        
        .nav-menu {
            display: flex;
            gap: 8px;
        }
        
        .nav-link {
            color: #fff;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            font-weight: 500;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 100%;
            background: #4e54c8;
            transform: translateX(-4px);
            transition: transform 0.3s;
        }
        
        .nav-link:hover {
            background: rgba(78, 84, 200, 0.1);
        }
        
        .nav-link:hover::before {
            transform: translateX(0);
        }
        
        .nav-link.active {
            background: rgba(78, 84, 200, 0.2);
        }
        
        .nav-link.active::before {
            transform: translateX(0);
        }
        
        .user-panel {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .user-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(78, 84, 200, 0.1);
            padding: 8px 15px;
            border-radius: 50px;
            border: 1px solid rgba(78, 84, 200, 0.3);
        }
        
        .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #4e54c8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            text-transform: uppercase;
        }
        
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        /* Main content */
        .main-container {
            flex: 1;
            display: flex;
            padding: 20px;
        }
        
        .sidebar {
            width: 280px;
            background: rgba(20, 20, 25, 0.7);
            border-radius: 12px;
            padding: 25px;
            height: min-content;
            border: 1px solid rgba(78, 84, 200, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            transition: all 0.3s;
        }
        
        .sidebar h3 {
            color: #4e54c8;
            font-size: 1.2rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(78, 84, 200, 0.2);
        }
        
        .quick-stats {
            margin-bottom: 30px;
        }
        
        .stat-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 15px;
            background: rgba(30, 30, 35, 0.5);
            margin-bottom: 10px;
            border-radius: 8px;
            border-left: 3px solid #4e54c8;
            transition: all 0.3s;
        }
        
        .stat-item:hover {
            transform: translateX(5px);
            background: rgba(78, 84, 200, 0.1);
        }
        
        .stat-icon {
            width: 40px;
            height: 40px;
            background: rgba(78, 84, 200, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4e54c8;
            font-size: 1.2rem;
        }
        
        .stat-info h4 {
            font-size: 0.9rem;
            color: #aaa;
            margin-bottom: 3px;
        }
        
        .stat-info p {
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .nav-section {
            margin-bottom: 30px;
        }
        
        .side-nav {
            list-style: none;
        }
        
        .side-nav li {
            margin-bottom: 8px;
        }
        
        .side-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 8px;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .side-nav a:hover {
            background: rgba(78, 84, 200, 0.1);
        }
        
        .side-nav a.active {
            background: rgba(78, 84, 200, 0.2);
            border-left: 3px solid #4e54c8;
        }
        
        .side-nav i {
            width: 20px;
            text-align: center;
            color: #4e54c8;
        }
        
        .content-area {
            flex: 1;
            padding: 0 30px;
        }
        
        .page-header {
            margin-bottom: 30px;
            position: relative;
        }
        
        .page-header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 3px;
            background: #4e54c8;
        }
        
        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .page-header h1 i {
            color: #4e54c8;
        }
        
        .page-header p {
            color: #aaa;
            font-size: 1.1rem;
            max-width: 600px;
        }
        
        .success-notification {
            padding: 15px 20px;
            background: rgba(46, 204, 113, 0.1);
            border-left: 4px solid #2ecc71;
            border-radius: 4px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.5s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .success-notification.show {
            opacity: 1;
            transform: translateY(0);
        }
        
        .success-notification i {
            font-size: 1.5rem;
            color: #2ecc71;
        }
        
        .success-notification p {
            font-weight: 500;
        }
        
        .feedback-card {
            background: rgba(25, 25, 30, 0.7);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(78, 84, 200, 0.2);
        }
        
        .card-header {
            background: rgba(78, 84, 200, 0.1);
            padding: 20px 30px;
            border-bottom: 1px solid rgba(78, 84, 200, 0.2);
        }
        
        .card-header h2 {
            font-size: 1.5rem;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card-header h2 i {
            color: #4e54c8;
        }
        
        .card-body {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: #aaa;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .form-control {
            width: 100%;
            padding: 15px;
            background: rgba(30, 30, 35, 0.5);
            border: 1px solid rgba(78, 84, 200, 0.2);
            border-radius: 8px;
            color: #fff;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #4e54c8;
            outline: none;
            box-shadow: 0 0 0 2px rgba(78, 84, 200, 0.2);
        }
        
        .form-control::placeholder {
            color: #666;
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .rating-stars {
            display: flex;
            gap: 5px;
            margin-top: 10px;
        }
        
        .rating-stars i {
            font-size: 1.8rem;
            cursor: pointer;
            transition: all 0.2s;
            color: #555;
            filter: drop-shadow(0 0 2px rgba(0, 0, 0, 0.5));
        }
        
        .rating-stars i:hover,
        .rating-stars i.active {
            color: #FFD700;
            filter: drop-shadow(0 0 5px rgba(255, 215, 0, 0.5));
        }
        
        .rating-description {
            min-height: 20px;
            margin-top: 10px;
            font-style: italic;
            color: #aaa;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #4e54c8, #8f94fb);
            color: #fff;
            box-shadow: 0 5px 15px rgba(78, 84, 200, 0.4);
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(78, 84, 200, 0.6);
        }
        
        .btn-primary:hover::before {
            width: 100%;
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .card-footer {
            padding: 20px 30px;
            border-top: 1px solid rgba(78, 84, 200, 0.2);
            display: flex;
            justify-content: flex-end;
        }
        
        /* Footer */
        footer {
            background: rgba(15, 15, 20, 0.8);
            backdrop-filter: blur(10px);
            padding: 20px;
            text-align: center;
            border-top: 1px solid rgba(78, 84, 200, 0.3);
            margin-top: auto;
        }
        
        footer p {
            color: #aaa;
            font-size: 0.9rem;
        }
        
        /* Mobile styles */
        @media (max-width: 992px) {
            .main-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                margin-bottom: 30px;
            }
            
            .content-area {
                padding: 0;
            }
        }
        
        @media (max-width: 768px) {
            .topnav {
                padding: 15px 20px;
            }
            
            .logo-section img {
                height: 35px;
            }
            
            .nav-menu {
                position: fixed;
                top: 70px;
                left: -100%;
                width: 80%;
                height: calc(100vh - 70px);
                background: rgba(15, 15, 20, 0.95);
                flex-direction: column;
                padding: 20px;
                transition: all 0.5s;
                z-index: 1000;
                backdrop-filter: blur(10px);
                border-right: 1px solid rgba(78, 84, 200, 0.3);
            }
            
            .nav-menu.active {
                left: 0;
            }
            
            .menu-toggle {
                display: block;
            }
            
            .user-panel {
                display: none;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .page-header h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="cyberpunk-bg">
        <div class="cyber-glow"></div>
        <div class="grid"></div>
    </div>
    
    <div class="content-wrapper">
        <header class="topnav">
            <div class="logo-section">
                <img src="./images/images.png" alt="Logo">
                <div class="logo-text">R&D <span>Dashboard</span></div>
            </div>
            
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <nav class="nav-menu" id="navMenu">
                <a href="welcome.php" class="nav-link">
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="index.php" class="nav-link">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="contact.php" class="nav-link">
                    <i class="fas fa-envelope"></i> Contact
                </a>
                <a href="feedback.php" class="nav-link active">
                    <i class="fas fa-comment"></i> Feedback
                </a>
                <a href="logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
            
            <div class="user-panel">
                <div class="user-badge">
                    <div class="user-avatar">
                        <?php echo substr($_SESSION['username'], 0, 1); ?>
                    </div>
                    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </div>
            </div>
        </header>
        
        <div class="main-container">
            <aside class="sidebar">
                <div class="quick-stats">
                    <h3>User Stats</h3>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-comment"></i>
                        </div>
                        <div class="stat-info">
                            <h4>Feedback Submitted</h4>
                            <p>5</p>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="stat-info">
                            <h4>Member Since</h4>
                            <p>March 2025</p>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-info">
                            <h4>Avg. Rating</h4>
                            <p>4.2/5</p>
                        </div>
                    </div>
                </div>
                
                <div class="nav-section">
                    <h3>Navigation</h3>
                    <ul class="side-nav">
                        <li>
                            <a href="welcome.php">
                                <i class="fas fa-home"></i>
                                <span>Home</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php">
                                <i class="fas fa-chart-line"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="contact.php">
                                <i class="fas fa-envelope"></i>
                                <span>Contact</span>
                            </a>
                        </li>
                        <li>
                            <a href="feedback.php" class="active">
                                <i class="fas fa-comment"></i>
                                <span>Feedback</span>
                            </a>
                        </li>
                        <li>
                            <a href="logout.php">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </aside>
            
            <main class="content-area">
                <div class="page-header">
                    <h1><i class="fas fa-comment"></i> Share Your Feedback</h1>
                    <p>Your insights help us improve the R&D Dashboard experience. Let us know what's working and what could be better.</p>
                </div>
                
                <div id="successMessage" class="success-notification">
                    <i class="fas fa-check-circle"></i>
                    <p>Thank you for your feedback! We appreciate your input and will use it to improve our services.</p>
                </div>
                
                <div class="feedback-card">
                    <div class="card-header">
                        <h2><i class="fas fa-edit"></i> Feedback Form</h2>
                    </div>
                    
                    <div class="card-body">
                        <form id="feedbackForm">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="feedbackType">Feedback Type</label>
                                    <select class="form-control" id="feedbackType" name="feedbackType" required>
                                        <option value="">Select Type</option>
                                        <option value="bug">Bug Report</option>
                                        <option value="feature">Feature Request</option>
                                        <option value="improvement">Improvement Suggestion</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label" for="subject">Subject</label>
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Brief description of your feedback" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="message">Your Feedback</label>
                                <textarea class="form-control" id="message" name="message" placeholder="Please provide detailed information..." required></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Rate Your Experience</label>
                                <div class="rating-stars">
                                    <i class="far fa-star" data-rating="1"></i>
                                    <i class="far fa-star" data-rating="2"></i>
                                    <i class="far fa-star" data-rating="3"></i>
                                    <i class="far fa-star" data-rating="4"></i>
                                    <i class="far fa-star" data-rating="5"></i>
                                </div>
                                <div class="rating-description" id="ratingDescription"></div>
                                <input type="hidden" id="rating" name="rating" value="0">
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" form="feedbackForm">
                            <i class="fas fa-paper-plane"></i> Submit Feedback
                        </button>
                    </div>
                </div>
            </main>
        </div>
        
        <footer>
            <p>© 2025 R&D AI Search. All rights reserved.</p>
        </footer>
    </div>
    
    <script>
        // Mobile menu toggle
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');
        
        if (menuToggle) {
            menuToggle.addEventListener('click', () => {
                navMenu.classList.toggle('active');
                
                // Change icon
                const icon = menuToggle.querySelector('i');
                if (navMenu.classList.contains('active')) {
                    icon.className = 'fas fa-times';
                } else {
                    icon.className = 'fas fa-bars';
                }
            });
        }
        
        // Star rating functionality with descriptive text
        const stars = document.querySelectorAll('.rating-stars i');
        const ratingInput = document.getElementById('rating');
        const ratingDescription = document.getElementById('ratingDescription');
        
        const ratingTexts = [
            '',
            'Poor - Significant improvements needed',
            'Fair - Works but needs improvement',
            'Good - Meets expectations',
            'Very Good - Exceeds expectations',
            'Excellent - Outstanding experience'
        ];
        
        stars.forEach(star => {
            star.addEventListener('mouseover', () => {
                const rating = parseInt(star.getAttribute('data-rating'));
                
                // Reset all stars
                stars.forEach(s => {
                    s.className = 'far fa-star';
                });
                
                // Fill stars up to the hovered one
                for (let i = 0; i < rating; i++) {
                    stars[i].className = 'fas fa-star';
                }
                
                // Show description
                ratingDescription.textContent = ratingTexts[rating];
            });
            
            star.addEventListener('mouseout', () => {
                const currentRating = parseInt(ratingInput.value);
                
                // Reset all stars
                stars.forEach(s => {
                    s.className = 'far fa-star';
                });
                
                // Fill stars based on the current rating
                for (let i = 0; i < currentRating; i++) {
                    stars[i].className = 'fas fa-star active';
                }
                
                // Show description for current rating
                ratingDescription.textContent = ratingTexts[currentRating];
            });
            
            star.addEventListener('click', () => {
                const rating = parseInt(star.getAttribute('data-rating'));
                ratingInput.value = rating;
                
                // Reset all stars
                stars.forEach(s => {
                    s.className = 'far fa-star';
                });
                
                // Fill stars up to the clicked one
                for (let i = 0; i < rating; i++) {
                    stars[i].className = 'fas fa-star active';
                }
                
                // Show description
                ratingDescription.textContent = ratingTexts[rating];
            });
        });
        
        // Form submission
        document.getElementById('feedbackForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Here you would normally send data to server
            // For now, we'll just show success message
            const successMessage = document.getElementById('successMessage');
            successMessage.classList.add('show');
            
            // Scroll to top to show the message
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            // Reset the form
            this.reset();
            stars.forEach(s => {
                s.className = 'far fa-star';
            });
            ratingInput.value = '0';
            ratingDescription.textContent = '';
            
            // Hide success message after 5 seconds
            setTimeout(() => {
                successMessage.classList.remove('show');
            }, 5000);
        });
    </script>
</body>
</html>