<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | R&D Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Quicksand', sans-serif;
        }
        
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #070b24;
            overflow: hidden;
        }
        
        /* New Background Design */
        .background {
            position: fixed;
            width: 100vw;
            height: 100vh;
            top: 0;
            left: 0;
            z-index: 1;
            overflow: hidden;
        }
        
        .bubble {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, rgba(78, 84, 200, 0.8), rgba(78, 84, 200, 0.1));
            box-shadow: 0 0 20px rgba(78, 84, 200, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1;
            opacity: 0.6;
        }
        
        /* Main Content Container */
        .container {
            position: relative;
            display: flex;
            width: 900px;
            max-width: 90%;
            z-index: 10;
        }
        
        /* Login Box */
        .signin {
            position: relative;
            width: 400px;
            background: rgba(34, 34, 34, 0.9);
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
        }
        
        .signin .content {
            position: relative;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 40px;
        }
        
        .signin .content h2 {
            font-size: 2em;
            color: #4e54c8;
            text-transform: uppercase;
        }
        
        .signin .content .form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 25px;
        }
        
        .signin .content .form .inputBox {
            position: relative;
            width: 100%;
        }
        
        .signin .content .form .inputBox input {
            position: relative;
            width: 100%;
            background: #333;
            border: none;
            outline: none;
            padding: 25px 10px 7.5px;
            border-radius: 4px;
            color: #fff;
            font-weight: 500;
            font-size: 1em;
        }
        
        .signin .content .form .inputBox i {
            position: absolute;
            left: 0;
            padding: 15px 10px;
            font-style: normal;
            color: #aaa;
            transition: 0.5s;
            pointer-events: none;
        }
        
        .signin .content .form .inputBox input:focus ~ i,
        .signin .content .form .inputBox input:valid ~ i {
            transform: translateY(-7.5px);
            font-size: 0.8em;
            color: #fff;
        }
        
        .signin .content .form .links {
            position: relative;
            width: 100%;
            display: flex;
            justify-content: space-between;
        }
        
        .signin .content .form .links a {
            color: #fff;
            text-decoration: none;
        }
        
        .signin .content .form .links a:nth-child(2) {
            color: #4e54c8;
            font-weight: 600;
        }
        
        .signin .content .form .inputBox input[type="submit"] {
            padding: 10px;
            background: #4e54c8;
            color: #fff;
            font-weight: 600;
            font-size: 1.35em;
            letter-spacing: 0.05em;
            cursor: pointer;
        }
        
        input[type="submit"]:active {
            opacity: 0.6;
        }
        
        .error-message {
            background-color: rgba(255, 0, 0, 0.2);
            color: #ff6b6b;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            width: 100%;
        }
        
        /* Image Slider */
        .image-container {
            position: relative;
            width: 400px;
            height: 500px;
            margin-left: 40px;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            display: none; /* Hide on mobile initially */
        }
        
        .slider-images {
            position: absolute;
            width: 100%;
            height: 100%;
            transition: transform 0.8s ease-in-out;
        }
        
        .slider-image {
            position: absolute;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .slider-image.active {
            opacity: 1;
        }
        
        .slider-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(0,0,0,0.4), rgba(78, 84, 200, 0.2));
        }
        
        .slider-caption {
            position: absolute;
            bottom: 20px;
            left: 20px;
            color: white;
            z-index: 10;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }
        
        .slider-caption h3 {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }
        
        .slider-caption p {
            font-size: 0.9rem;
        }
        
        /* Responsive Design */
        @media (min-width: 768px) {
            .image-container {
                display: block;
            }
        }
        
        @media (max-width: 900px) {
            .container {
                flex-direction: column;
                align-items: center;
                gap: 20px;
            }
            
            .signin, .image-container {
                width: 100%;
                max-width: 400px;
            }
            
            .image-container {
                height: 300px;
                margin-left: 0;
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- New Background Animation -->
    <div class="background" id="background">
        <!-- Bubbles will be generated by JS -->
    </div>
    
    <div class="container">
        <!-- Login Form -->
        <div class="signin">
            <div class="content">
                <h2>R&D Dashboard Login</h2>
                
                <?php if (isset($_SESSION['flash_message'])): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($_SESSION['flash_message']); unset($_SESSION['flash_message']); ?>
                    </div>
                <?php endif; ?>
                
                <form class="form" action="auth.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <div class="inputBox">
                        <input type="text" name="username" required>
                        <i>Username</i>
                    </div>
                    
                    <div class="inputBox">
                        <input type="password" name="password" required>
                        <i>Password</i>
                    </div>
                    
                    <div class="links">
                        <a href="#">&nbsp;</a>
                        <a href="signup.php">Sign Up</a>
                    </div>
                    
                    <div class="inputBox">
                        <input type="submit" value="Login">
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Image Slider -->
        <div class="image-container">
            <div class="slider-images">
                <!-- Using placeholder images instead of real URLs -->
                <div class="slider-image active" style="background-image: url('./images/9.jpeg')">
                    <div class="slider-caption">
                        <h3>Research & Development</h3>
                        <p>Next-gen solutions for tomorrow's challenges</p>
                    </div>
                </div>
                <div class="slider-image" style="background-image: url('./images/5.jpeg')">
                    <div class="slider-caption">
                        <h3>Advanced Analytics</h3>
                        <p>Actionable insights through data</p>
                    </div>
                </div>
                <div class="slider-image" style="background-image: url('./images/4.jpeg')">
                    <div class="slider-caption">
                        <h3>Secure Infrastructure</h3>
                        <p>Protected systems for enterprise innovation</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Create bubbles for background
        const background = document.getElementById('background');
        const bubbleCount = 15;
        
        for (let i = 0; i < bubbleCount; i++) {
            const size = Math.random() * 200 + 50;
            const bubble = document.createElement('div');
            bubble.classList.add('bubble');
            bubble.style.width = `${size}px`;
            bubble.style.height = `${size}px`;
            bubble.style.left = `${Math.random() * 100}%`;
            bubble.style.top = `${Math.random() * 100}%`;
            bubble.style.animationDelay = `${Math.random() * 5}s`;
            
            // Set animation properties
            bubble.style.animation = `float ${Math.random() * (20 - 10) + 10}s infinite ease-in-out`;
            background.appendChild(bubble);
        }
        
        // Add keyframe animation dynamically
        const style = document.createElement('style');
        style.innerHTML = `
            @keyframes float {
                0% {
                    transform: translate(0, 0) rotate(0deg) scale(1);
                }
                33% {
                    transform: translate(${Math.random() * 100 - 50}px, ${Math.random() * 100 - 50}px) rotate(${Math.random() * 20}deg) scale(${Math.random() * 0.2 + 0.9});
                }
                66% {
                    transform: translate(${Math.random() * 100 - 50}px, ${Math.random() * 100 - 50}px) rotate(${Math.random() * 20}deg) scale(${Math.random() * 0.2 + 0.9});
                }
                100% {
                    transform: translate(0, 0) rotate(0deg) scale(1);
                }
            }
        `;
        document.head.appendChild(style);
        
        // Image slider functionality
        const images = document.querySelectorAll('.slider-image');
        let currentIndex = 0;
        
        function changeImage() {
            images.forEach(img => img.classList.remove('active'));
            currentIndex = (currentIndex + 1) % images.length;
            images[currentIndex].classList.add('active');
        }
        
        // Change image every 4 seconds
        setInterval(changeImage, 4000);
    </script>
</body>
</html>