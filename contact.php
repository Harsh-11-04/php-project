<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - R&D Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Add Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: #080808;
            color: #fff;
            overflow-x: hidden;
        }
        
        .background {
            position: fixed;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            overflow: hidden;
        }
        
        .background .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        
        .background .particles span {
            position: absolute;
            display: block;
            background: #4e54c8;
            border-radius: 50%;
            opacity: 0;
            animation: fadeInOut 8s linear infinite;
        }
        
        @keyframes fadeInOut {
            0% {
                transform: translateY(0) scale(0);
                opacity: 0;
            }
            50% {
                opacity: 0.5;
            }
            100% {
                transform: translateY(-100vh) scale(1);
                opacity: 0;
            }
        }
        
        .glass-effect {
            background: rgba(20, 20, 25, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(78, 84, 200, 0.2);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .content-wrapper {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: 100%;
        }
        
        .header {
            position: sticky;
            top: 0;
            background: rgba(12, 12, 17, 0.85);
            backdrop-filter: blur(10px);
            padding: 15px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(78, 84, 200, 0.2);
            z-index: 100;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .logo img {
            height: 40px;
            filter: drop-shadow(0 0 5px rgba(78, 84, 200, 0.6));
        }
        
        .logo h1 {
            color: #fff;
            font-size: 1.6em;
            font-weight: 600;
        }
        
        .logo h1 span {
            color: #4e54c8;
        }
        
        .nav-links {
            display: flex;
            gap: 5px;
        }
        
        .nav-links a {
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }
        
        .nav-links a:hover {
            background: rgba(78, 84, 200, 0.2);
        }
        
        .nav-links a:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: #4e54c8;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-links a:hover:after {
            width: 80%;
        }
        
        .hamburger {
            display: none;
            cursor: pointer;
        }
        
        .hamburger div {
            width: 25px;
            height: 3px;
            background: #fff;
            margin: 5px;
            transition: all 0.3s ease;
        }
        
        .contact-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px 20px;
        }
        
        .contact-content {
            width: 100%;
            max-width: 1000px;
            position: relative;
        }
        
        .contact-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .contact-header h2 {
            font-size: 2.5em;
            font-weight: 700;
            margin-bottom: 15px;
            background: linear-gradient(to right, #4e54c8, #8f94fb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
            display: inline-block;
        }
        
        .contact-header h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(to right, #4e54c8, #8f94fb);
        }
        
        .contact-header p {
            color: #aaa;
            font-size: 1.1em;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .contact-card {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        
        .contact-top {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        
        .contact-method {
            padding: 30px 20px;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .contact-method:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(78, 84, 200, 0.2);
        }
        
        .contact-method .icon-wrapper {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(78, 84, 200, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .contact-method:hover .icon-wrapper {
            background: rgba(78, 84, 200, 0.2);
        }
        
        .contact-method i {
            font-size: 1.8em;
            color: #4e54c8;
        }
        
        .contact-method h3 {
            font-size: 1.3em;
            margin-bottom: 10px;
            color: #fff;
            font-weight: 600;
        }
        
        .contact-method p {
            color: #aaa;
            line-height: 1.6;
        }
        
        .contact-form {
            padding: 40px;
            margin-top: 20px;
        }
        
        .form-title {
            font-size: 1.5em;
            margin-bottom: 25px;
            color: #fff;
            position: relative;
            padding-bottom: 10px;
        }
        
        .form-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background: #4e54c8;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #ccc;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            background: rgba(30, 30, 35, 0.7);
            border: 1px solid rgba(78, 84, 200, 0.2);
            border-radius: 8px;
            color: #fff;
            font-size: 1em;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #4e54c8;
            box-shadow: 0 0 0 2px rgba(78, 84, 200, 0.2);
            outline: none;
        }
        
        .form-group textarea {
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
        
        .submit-btn {
            background: linear-gradient(to right, #4e54c8, #8f94fb);
            color: #fff;
            border: none;
            padding: 15px 30px;
            font-size: 1.1em;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            display: block;
            width: 100%;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(78, 84, 200, 0.4);
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(78, 84, 200, 0.4);
        }
        
        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }
        
        .submit-btn:hover::before {
            left: 100%;
        }
        
        footer {
            background: rgba(12, 12, 17, 0.85);
            backdrop-filter: blur(10px);
            text-align: center;
            padding: 25px;
            margin-top: auto;
            border-top: 1px solid rgba(78, 84, 200, 0.2);
        }
        
        footer p {
            color: #aaa;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert i {
            font-size: 1.2em;
        }
        
        .alert-success {
            background: rgba(76, 175, 80, 0.1);
            color: #4CAF50;
            border: 1px solid rgba(76, 175, 80, 0.3);
        }
        
        .alert-error {
            background: rgba(244, 67, 54, 0.1);
            color: #F44336;
            border: 1px solid rgba(244, 67, 54, 0.3);
        }
        
        @media (max-width: 992px) {
            .contact-top {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
            }
            
            .nav-links {
                position: absolute;
                top: 70px;
                right: -100%;
                flex-direction: column;
                background: rgba(12, 12, 17, 0.95);
                backdrop-filter: blur(10px);
                width: 70%;
                height: calc(100vh - 70px);
                padding: 20px;
                gap: 10px;
                transition: all 0.5s ease;
                z-index: 99;
                border-left: 1px solid rgba(78, 84, 200, 0.2);
            }
            
            .nav-links.active {
                right: 0;
            }
            
            .hamburger {
                display: block;
                z-index: 100;
            }
            
            .hamburger.active div:nth-child(1) {
                transform: rotate(-45deg) translate(-5px, 6px);
            }
            
            .hamburger.active div:nth-child(2) {
                opacity: 0;
            }
            
            .hamburger.active div:nth-child(3) {
                transform: rotate(45deg) translate(-5px, -6px);
            }
            
            .contact-top {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .contact-form {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="background">
        <div class="particles" id="particles">
            <!-- Particles will be created via JS -->
        </div>
    </div>
    
    <div class="content-wrapper">
        <header class="header">
            <div class="logo">
                <img src="./images/images.png" alt="Logo">
                <h1>R&D <span>Dashboard</span></h1>
            </div>
            
            <div class="hamburger">
                <div></div>
                <div></div>
                <div></div>
            </div>
            
            <nav class="nav-links">
                <a href="welcome.php"><i class="fas fa-home"></i> Home</a>
                <a href="index.php"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="contact.php"><i class="fas fa-envelope"></i> Contact</a>
                <a href="feedback.php"><i class="fas fa-comment"></i> Feedback</a>
                <?php if(isset($_SESSION['username'])): ?>
                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                <?php else: ?>
                    <a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                <?php endif; ?>
            </nav>
        </header>
        
        <div class="contact-container">
            <div class="contact-content">
                <div class="contact-header">
                    <h2>Contact Us</h2>
                    <p>Have questions or need assistance? Our team is ready to provide support and guidance for all your inquiries.</p>
                </div>
                
                <?php if(isset($_SESSION['contact_message'])): ?>
                    <div class="alert <?php echo $_SESSION['contact_status'] == 'success' ? 'alert-success' : 'alert-error'; ?>">
                        <i class="fas fa-<?php echo $_SESSION['contact_status'] == 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                        <?php echo $_SESSION['contact_message']; ?>
                    </div>
                    <?php unset($_SESSION['contact_message']); unset($_SESSION['contact_status']); ?>
                <?php endif; ?>
                
                <div class="contact-card">
                    <div class="contact-top">
                        <div class="contact-method glass-effect">
                            <div class="icon-wrapper">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h3>Email Us</h3>
                            <p>harshpawar458@gmail.com</p>
                            <p>We typically respond within 24 hours</p>
                        </div>
                        
                        <div class="contact-method glass-effect">
                            <div class="icon-wrapper">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <h3>Call Us</h3>
                            <p>+91 8923596155</p>
                            <p>Mon-Fri: 9am - 5pm EST</p>
                        </div>
                        
                        <div class="contact-method glass-effect">
                            <div class="icon-wrapper">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h3>Visit Us</h3>
                            <p>Research Drive</p>
                            <p>LOVELY PROFFESIONAL UNIVERSITY</p>
                        </div>
                    </div>
                    
                    <div class="contact-form glass-effect">
                        <h3 class="form-title">Send us a message</h3>
                        <form action="process_contact.php" method="POST">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" id="name" name="name" placeholder="Enter your name" required class="tw-focus:ring-indigo-500">
                                </div>
                                
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" id="email" name="email" placeholder="Enter your email" required class="tw-focus:ring-indigo-500">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <select id="subject" name="subject" required class="tw-focus:ring-indigo-500">
                                    <option value="">Select a subject</option>
                                    <option value="General Question">General Question</option>
                                    <option value="Technical Support">Technical Support</option>
                                    <option value="Account Issue">Account Issue</option>
                                    <option value="Feature Request">Feature Request</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Your Message</label>
                                <textarea id="message" name="message" placeholder="Type your message here..." required class="tw-focus:ring-indigo-500"></textarea>
                            </div>
                            
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-paper-plane"></i> Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <footer>
            <p>© 2025 R&D AI Search. All rights reserved.</p>
        </footer>
    </div>
    
    <script>
        // Configure Tailwind prefix to avoid conflicts with existing styles
        tailwind.config = {
            prefix: 'tw-',
            important: true,
            corePlugins: {
                preflight: false,
            }
        }
        
        // Mobile menu toggle
        const hamburger = document.querySelector('.hamburger');
        const navLinks = document.querySelector('.nav-links');
        
        if (hamburger) {
            hamburger.addEventListener('click', () => {
                hamburger.classList.toggle('active');
                navLinks.classList.toggle('active');
            });
        }
        
        // Create animated particles background
        const createParticles = () => {
            const particles = document.getElementById('particles');
            const numberOfParticles = 50;
            
            for (let i = 0; i < numberOfParticles; i++) {
                const particle = document.createElement('span');
                const size = Math.random() * 6 + 2;
                const posX = Math.random() * 100;
                
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${posX}%`;
                particle.style.bottom = '-100px';
                particle.style.animationDelay = `${Math.random() * 5}s`;
                particle.style.animationDuration = `${Math.random() * 10 + 8}s`;
                
                particles.appendChild(particle);
            }
        };
        
        // Execute when DOM is fully loaded
        document.addEventListener('DOMContentLoaded', () => {
            createParticles();
        });
    </script>
</body>
</html>