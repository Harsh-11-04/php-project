<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to R&D Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Add Mermaid.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mermaid/10.6.1/mermaid.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'quicksand': ['Quicksand', 'sans-serif'],
                    },
                    colors: {
                        'primary': '#4e54c8',
                        'primary-light': '#8f94fb',
                        'dark': '#070b24',
                        'dark-light': '#1e213a',
                    },
                    keyframes: {
                        fadeInUp: {
                            'from': { opacity: '0', transform: 'translateY(20px)' },
                            'to': { opacity: '1', transform: 'translateY(0)' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translate(0, 0) rotate(0deg) scale(1)' },
                            '50%': { transform: 'translate(20px, -20px) rotate(5deg) scale(1.05)' }
                        }
                    },
                    animation: {
                        'fadeInUp': 'fadeInUp 1s ease-out forwards',
                        'fadeInUp-delay-200': 'fadeInUp 1s ease-out 0.2s forwards',
                        'fadeInUp-delay-400': 'fadeInUp 1s ease-out 0.4s forwards',
                        'float': 'float 10s ease-in-out infinite'
                    },
                    backgroundImage: {
                        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                    }
                }
            }
        }
    </script>
    <style>
        .bubble-animation {
            animation: float 15s infinite ease-in-out;
            animation-delay: var(--delay);
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1) rotate(0deg); }
            33% { transform: translate(var(--x1), var(--y1)) scale(var(--scale1)) rotate(var(--rotate1)); }
            66% { transform: translate(var(--x2), var(--y2)) scale(var(--scale2)) rotate(var(--rotate2)); }
        }
        .mermaid {
            background-color: rgba(30, 33, 58, 0.8);
            border-radius: 1rem;
            padding: 2rem;
            max-width: 100%;
            margin: 0 auto;
            backdrop-filter: blur(4px);
            border: 1px solid rgba(78, 84, 200, 0.2);
        }
    </style>
</head>
<body class="min-h-screen bg-dark text-white font-quicksand overflow-x-hidden flex flex-col">
    <!-- Background with parallax effect -->
    <div class="fixed inset-0 w-full h-full overflow-hidden z-0">
        <div id="parallax-bg" class="absolute w-full h-full bg-cover bg-center bg-no-repeat scale-110 transform" style="background-image: url('/api/placeholder/1920/1080')"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-dark/95 via-[#23275080] to-dark/95"></div>
        <!-- Bubbles will be added by JS -->
    </div>
    
    <div class="relative z-10 flex flex-col min-h-screen w-full">
        <!-- Header with enhanced navigation -->
        <header id="header" class="bg-dark/80 backdrop-blur-lg sticky top-0 z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center">
                        <img src="./images/images.png" alt="R&D Dashboard Logo" class="h-10 w-12 mr-3 filter drop-shadow-lg transition-transform duration-300 hover:rotate-6">
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-primary to-primary-light bg-clip-text text-transparent">R&D Dashboard</h1>
                    </div>
                    
                    <button id="mobile-menu-btn" class="md:hidden text-white text-2xl focus:outline-none">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <nav id="nav-links" class="hidden md:flex items-center space-x-1">
                        <a href="welcome.php" class="relative px-4 py-2 rounded-lg text-white font-medium hover:bg-primary/15 hover:-translate-y-0.5 transition duration-300 after:content-[''] after:absolute after:bottom-1 after:left-1/2 after:-translate-x-1/2 after:w-0 after:h-0.5 after:bg-primary after:transition-all hover:after:w-2/3 bg-primary/20">Home</a>
                        <a href="index.php" class="relative px-4 py-2 rounded-lg text-white font-medium hover:bg-primary/15 hover:-translate-y-0.5 transition duration-300 after:content-[''] after:absolute after:bottom-1 after:left-1/2 after:-translate-x-1/2 after:w-0 after:h-0.5 after:bg-primary after:transition-all hover:after:w-2/3">Dashboard</a>
                        <a href="contact.php" class="relative px-4 py-2 rounded-lg text-white font-medium hover:bg-primary/15 hover:-translate-y-0.5 transition duration-300 after:content-[''] after:absolute after:bottom-1 after:left-1/2 after:-translate-x-1/2 after:w-0 after:h-0.5 after:bg-primary after:transition-all hover:after:w-2/3">Contact</a>
                        <a href="feedback.php" class="relative px-4 py-2 rounded-lg text-white font-medium hover:bg-primary/15 hover:-translate-y-0.5 transition duration-300 after:content-[''] after:absolute after:bottom-1 after:left-1/2 after:-translate-x-1/2 after:w-0 after:h-0.5 after:bg-primary after:transition-all hover:after:w-2/3">Feedback</a>
                        <?php if(isset($_SESSION['username'])): ?>
                            <a href="logout.php" class="ml-2 px-4 py-2 bg-gradient-to-r from-primary to-primary-light text-white rounded-lg font-medium shadow-lg hover:-translate-y-0.5 transition duration-300">Logout</a>
                        <?php else: ?>
                            <a href="login.php" class="ml-2 px-4 py-2 bg-gradient-to-r from-primary to-primary-light text-white rounded-lg font-medium shadow-lg hover:-translate-y-0.5 transition duration-300">Login</a>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div id="mobile-menu" class="hidden bg-dark-light/95 backdrop-blur-lg md:hidden">
                <div class="px-2 pt-2 pb-4 space-y-1">
                    <a href="welcome.php" class="block px-4 py-3 rounded-lg text-white font-medium hover:bg-primary/20 transition duration-300 border-l-4 border-primary">Home</a>
                    <a href="index.php" class="block px-4 py-3 rounded-lg text-white font-medium hover:bg-primary/20 transition duration-300">Dashboard</a>
                    <a href="contact.php" class="block px-4 py-3 rounded-lg text-white font-medium hover:bg-primary/20 transition duration-300">Contact</a>
                    <a href="feedback.php" class="block px-4 py-3 rounded-lg text-white font-medium hover:bg-primary/20 transition duration-300">Feedback</a>
                    <?php if(isset($_SESSION['username'])): ?>
                        <a href="logout.php" class="block px-4 py-3 rounded-lg text-white font-medium bg-gradient-to-r from-primary to-primary-light shadow-lg">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="block px-4 py-3 rounded-lg text-white font-medium bg-gradient-to-r from-primary to-primary-light shadow-lg">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </header>
        
        <!-- Hero Section -->
        <section class="relative min-h-[85vh] flex flex-col justify-center items-center text-center px-4 py-10">
            <div class="absolute inset-0 bg-gradient-radial from-primary/10 to-transparent pointer-events-none"></div>
            <h1 class="text-5xl md:text-6xl font-bold bg-gradient-to-r from-primary to-primary-light bg-clip-text text-transparent mb-6 opacity-0 animate-fadeInUp shadow-lg">Power Your Research & Development</h1>
            <p class="text-xl max-w-3xl mb-8 text-white/90 leading-relaxed opacity-0 animate-fadeInUp-delay-200">
                An intelligent platform designed to enhance your R&D activities through 
                <span class="text-primary-light font-semibold">advanced search capabilities</span>, 
                <span class="text-primary-light font-semibold">data analysis</span>, and 
                <span class="text-primary-light font-semibold">collaborative tools</span> that drive innovation.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 opacity-0 animate-fadeInUp-delay-400">
                <?php if(isset($_SESSION['username'])): ?>
                    <a href="index.php" class="px-8 py-4 bg-gradient-to-r from-primary to-primary-light text-white rounded-full font-semibold shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-xl">Go to Dashboard</a>
                <?php else: ?>
                    <a href="login.php" class="px-8 py-4 bg-gradient-to-r from-primary to-primary-light text-white rounded-full font-semibold shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-xl">Get Started</a>
                    <a href="signup.php" class="px-8 py-4 border-2 border-primary/70 text-white rounded-full font-semibold shadow-lg transition duration-300 hover:bg-primary/20 hover:-translate-y-1 hover:shadow-xl">Create Account</a>
                <?php endif; ?>
            </div>
        </section>
        
        <!-- Features Section -->
        <section class="bg-dark-light/70 backdrop-blur-lg py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center text-white mb-16 relative after:content-[''] after:absolute after:bottom-[-10px] after:left-1/2 after:-translate-x-1/2 after:w-20 after:h-1 after:bg-gradient-to-r after:from-primary after:to-primary-light">Our Core Features</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="feature-card bg-dark-light/80 backdrop-blur-md rounded-xl p-8 border border-primary/10 shadow-lg transition duration-500 hover:-translate-y-4 hover:shadow-xl hover:border-primary/30 relative overflow-hidden group">
                        <div class="absolute inset-0 bg-gradient-to-br from-primary/15 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <i class="fas fa-search text-4xl bg-gradient-to-r from-primary to-primary-light bg-clip-text text-transparent mb-6 inline-block transition duration-300 group-hover:scale-110"></i>
                        <h3 class="text-xl font-semibold text-white mb-4 relative pb-3 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-10 after:h-0.5 after:bg-primary after:transition-all duration-300 group-hover:after:w-16">Intelligent Search</h3>
                        <p class="text-white/80 leading-relaxed">Our semantic search engine understands the context of your research queries and delivers the most relevant results, saving time and increasing productivity.</p>
                    </div>
                    
                    <div class="feature-card bg-dark-light/80 backdrop-blur-md rounded-xl p-8 border border-primary/10 shadow-lg transition duration-500 hover:-translate-y-4 hover:shadow-xl hover:border-primary/30 relative overflow-hidden group">
                        <div class="absolute inset-0 bg-gradient-to-br from-primary/15 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <i class="fas fa-chart-line text-4xl bg-gradient-to-r from-primary to-primary-light bg-clip-text text-transparent mb-6 inline-block transition duration-300 group-hover:scale-110"></i>
                        <h3 class="text-xl font-semibold text-white mb-4 relative pb-3 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-10 after:h-0.5 after:bg-primary after:transition-all duration-300 group-hover:after:w-16">Data Analysis</h3>
                        <p class="text-white/80 leading-relaxed">Visualize research trends and patterns with interactive dashboards to gain actionable insights from your data collection and make informed decisions.</p>
                    </div>
                    
                    <div class="feature-card bg-dark-light/80 backdrop-blur-md rounded-xl p-8 border border-primary/10 shadow-lg transition duration-500 hover:-translate-y-4 hover:shadow-xl hover:border-primary/30 relative overflow-hidden group">
                        <div class="absolute inset-0 bg-gradient-to-br from-primary/15 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <i class="fas fa-users text-4xl bg-gradient-to-r from-primary to-primary-light bg-clip-text text-transparent mb-6 inline-block transition duration-300 group-hover:scale-110"></i>
                        <h3 class="text-xl font-semibold text-white mb-4 relative pb-3 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-10 after:h-0.5 after:bg-primary after:transition-all duration-300 group-hover:after:w-16">Collaboration</h3>
                        <p class="text-white/80 leading-relaxed">Share research findings and collaborate with team members across your organization with real-time updates and integrated communication tools.</p>
                    </div>
                    
                    <div class="feature-card bg-dark-light/80 backdrop-blur-md rounded-xl p-8 border border-primary/10 shadow-lg transition duration-500 hover:-translate-y-4 hover:shadow-xl hover:border-primary/30 relative overflow-hidden group">
                        <div class="absolute inset-0 bg-gradient-to-br from-primary/15 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <i class="fas fa-shield-alt text-4xl bg-gradient-to-r from-primary to-primary-light bg-clip-text text-transparent mb-6 inline-block transition duration-300 group-hover:scale-110"></i>
                        <h3 class="text-xl font-semibold text-white mb-4 relative pb-3 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-10 after:h-0.5 after:bg-primary after:transition-all duration-300 group-hover:after:w-16">Secure Access</h3>
                        <p class="text-white/80 leading-relaxed">Your research data is protected with enterprise-level security protocols, ensuring compliance with industry standards and regulations.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Project Flow Chart Section -->
        <section class="bg-dark/80 backdrop-blur-lg py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center text-white mb-16 relative after:content-[''] after:absolute after:bottom-[-10px] after:left-1/2 after:-translate-x-1/2 after:w-20 after:h-1 after:bg-gradient-to-r after:from-primary after:to-primary-light">Project Architecture</h2>
                
                <div class="flowchart-container mt-12 mb-8">
                    <div class="mermaid" id="flowchart">
                        flowchart TD
                            subgraph "Frontend Components"
                                A[Welcome Page] --> B[Login Page]
                                A --> C[Signup Page]
                                A --> D[Dashboard]
                                A --> E[Contact Us]
                                A --> F[Feedback Page]
                            end

                            subgraph "Authentication & Session Management"
                                B --> G[Session Start]
                                C --> G
                                G --> H{Authenticated?}
                                H -- Yes --> D
                                H -- No --> B
                            end

                            subgraph "Database Operations"
                                C --> I[(MySQL Database\nPHPMyAdmin)]
                                B --> J[Verify Credentials]
                                J --> I
                                E --> K[Store Contact Info]
                                K --> I
                                D --> L[Fetch Research Data]
                                L --> I
                            end

                            subgraph "Dashboard Functionality"
                                D --> M[Semantic Search Engine]
                                M --> N[Search Results]
                                D --> O[Data Visualization]
                                D --> P[Research Categories]
                            end

                            subgraph "External Systems"
                                F --> Q[Email Processing]
                                Q --> R[Admin Email Inbox]
                            end

                            subgraph "Data Management"
                                S[Manual Data Entry] --> I
                                I --> L
                            end

                            %% Additional connections for clarity
                            N --> D
                            O --> D
                            P --> D
                    </div>
                </div>
                
                <div class="text-center text-white/80 mt-12">
                    <p class="max-w-3xl mx-auto">This flowchart illustrates the complete architecture of our R&D Dashboard system, showing how data flows between different components and how user interactions are processed through the application.</p>
                </div>
            </div>
        </section>
        
        <!-- Footer -->
        <footer class="bg-dark/90 backdrop-blur-lg mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex items-center">
                        <img src="./images/images.png" alt="R&D Dashboard Logo" class="h-8 w-10 mr-3">
                        <h3 class="text-xl font-semibold text-primary">R&D Dashboard</h3>
                    </div>
                    
                    <div class="flex flex-wrap justify-center gap-6">
                        <a href="privacy.php" class="text-white/80 hover:text-primary transition duration-300">Privacy Policy</a>
                        <a href="terms.php" class="text-white/80 hover:text-primary transition duration-300">Terms of Service</a>
                        <a href="faq.php" class="text-white/80 hover:text-primary transition duration-300">FAQ</a>
                        <a href="contact.php" class="text-white/80 hover:text-primary transition duration-300">Contact</a>
                    </div>
                    
                    <div class="flex gap-4">
                        <a href="#" aria-label="LinkedIn" class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center text-white/80 transition duration-300 hover:bg-primary/30 hover:text-white hover:-translate-y-1">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" aria-label="Twitter" class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center text-white/80 transition duration-300 hover:bg-primary/30 hover:text-white hover:-translate-y-1">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" aria-label="GitHub" class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center text-white/80 transition duration-300 hover:bg-primary/30 hover:text-white hover:-translate-y-1">
                            <i class="fab fa-github"></i>
                        </a>
                    </div>
                </div>
                
                <div class="border-t border-white/10 mt-6 pt-6 text-center">
                    <p class="text-white/60 text-sm">© 2025 R&D AI Search. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
    
    <script>
        // Initialize Mermaid
        mermaid.initialize({
            startOnLoad: true,
            theme: 'dark',
            flowchart: {
                curve: 'basis',
                useMaxWidth: true,
                htmlLabels: true,
                nodeSpacing: 80,
                rankSpacing: 100
            },
            themeVariables: {
                primaryColor: '#4e54c8',
                primaryTextColor: '#ffffff',
                primaryBorderColor: '#8f94fb',
                lineColor: '#8f94fb',
                secondaryColor: '#252850',
                tertiaryColor: '#1e213a'
            }
        });

        // Create bubbles for background
        const background = document.querySelector('.fixed');
        const bubbleCount = 12;
        
        for (let i = 0; i < bubbleCount; i++) {
            const size = Math.random() * 200 + 50;
            const bubble = document.createElement('div');
            bubble.classList.add('absolute', 'rounded-full', 'z-10', 'bubble-animation');
            
            // Randomize bubble properties
            bubble.style.width = `${size}px`;
            bubble.style.height = `${size}px`;
            bubble.style.left = `${Math.random() * 100}%`;
            bubble.style.top = `${Math.random() * 100}%`;
            bubble.style.opacity = (Math.random() * 0.4 + 0.1).toString();
            bubble.style.background = 'radial-gradient(circle at 30% 30%, rgba(78, 84, 200, 0.6), rgba(78, 84, 200, 0.1))';
            bubble.style.boxShadow = '0 0 20px rgba(78, 84, 200, 0.3)';
            bubble.style.backdropFilter = 'blur(3px)';
            
            // Set animation variables
            bubble.style.setProperty('--delay', `${Math.random() * 5}s`);
            bubble.style.setProperty('--x1', `${Math.random() * 100 - 50}px`);
            bubble.style.setProperty('--y1', `${Math.random() * 100 - 50}px`);
            bubble.style.setProperty('--x2', `${Math.random() * 100 - 50}px`);
            bubble.style.setProperty('--y2', `${Math.random() * 100 - 50}px`);
            bubble.style.setProperty('--scale1', (Math.random() * 0.2 + 0.9).toString());
            bubble.style.setProperty('--scale2', (Math.random() * 0.2 + 0.9).toString());
            bubble.style.setProperty('--rotate1', `${Math.random() * 20}deg`);
            bubble.style.setProperty('--rotate2', `${Math.random() * 20}deg`);
            
            background.appendChild(bubble);
        }
        
        // Header scroll effect
        const header = document.getElementById('header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                header.classList.add('py-2');
                header.classList.add('bg-dark/95');
            } else {
                header.classList.remove('py-2');
                header.classList.remove('bg-dark/95');
            }
        });
        
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            mobileMenuBtn.innerHTML = mobileMenu.classList.contains('hidden') ? 
                '<i class="fas fa-bars"></i>' : '<i class="fas fa-times"></i>';
        });
        
        // Parallax effect
        const parallaxBg = document.getElementById('parallax-bg');
        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;
            parallaxBg.style.transform = `translateY(${scrolled * 0.25}px) scale(1.1)`;
        });
        
        // Intersection Observer for feature cards animation
        const animateOnScroll = (entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                    entry.target.classList.remove('opacity-0', 'translate-y-8');
                    observer.unobserve(entry.target);
                }
            });
        };
        
        const observer = new IntersectionObserver(animateOnScroll, {
            root: null,
            threshold: 0.15,
            rootMargin: '-50px'
        });
        
        document.querySelectorAll('.feature-card').forEach(card => {
            card.classList.add('opacity-0', 'translate-y-8');
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    </script>
</body>
</html>