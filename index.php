<?php
session_start();
if (!isset($_SESSION['username'])) {
    $_SESSION['flash_message'] = "You must log in first.";
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>R&D Data Search Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'quicksand': ['Quicksand', 'sans-serif'],
                    },
                    colors: {
                        'primary': '#4e54c8',
                        'dark': '#181818',
                    },
                    animation: {
                        'pulse': 'pulse 1.5s infinite',
                        'animate': 'animate 5s linear infinite',
                    },
                    keyframes: {
                        pulse: {
                            '0%, 100%': { opacity: '0.6' },
                            '50%': { opacity: '1' },
                        },
                        animate: {
                            '0%': { transform: 'translateY(-100%)' },
                            '100%': { transform: 'translateY(100%)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .animate-background-span {
            animation: float 5s ease-in-out infinite;
            animation-delay: calc(0.2s * var(--i));
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        /* Account icon pulse animation */
        @keyframes account-pulse {
            0%, 100% { transform: scale(1);}
            50% { transform: scale(1.18);}
        }
        .animate-account-pulse {
            animation: account-pulse 1.6s infinite;
        }
        /* Logout icon bounce animation */
        @keyframes logout-bounce {
            0% { transform: translateX(0);}
            30% { transform: translateX(6px);}
            50% { transform: translateX(-3px);}
            70% { transform: translateX(3px);}
            100% { transform: translateX(0);}
        }
        .group:hover .animate-logout-bounce {
            animation: logout-bounce 0.6s;
        }
    </style>
</head>
<body class="min-h-screen bg-black text-white font-quicksand overflow-x-hidden flex flex-col">
    <!-- Animated Background -->
    <div class="fixed inset-0 flex justify-center items-center flex-wrap gap-0.5 overflow-hidden z-0">
        <div class="absolute inset-0 bg-gradient-to-b from-black via-primary to-black animate-animate"></div>
        <?php for($i = 0; $i < 100; $i++): ?>
            <span class="relative block w-6 h-6 md:w-16 md:h-16 lg:w-24 lg:h-24 bg-dark z-10 transition duration-1000 hover:bg-primary hover:duration-0 animate-background-span" style="--i:<?php echo $i; ?>"></span>
        <?php endfor; ?>
    </div>
    
    <!-- Content Wrapper -->
    <div class="relative z-10 flex flex-col min-h-screen w-full">
        <div class="flex flex-grow">
            <!-- Main Content (Full Width) -->
            <div id="mainContent" class="flex-grow w-full p-8 transition-all duration-300 bg-black/70">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-4xl font-bold text-primary">R&D Search</h1>
                    <div class="flex items-center space-x-4">
                        <!-- Account Icon and Username -->
                        <span class="flex items-center gap-2 px-3 py-1 rounded-full bg-gray-800/60 hover:bg-primary/20 transition group cursor-pointer">
                            <i class="fas fa-user-circle text-primary text-2xl animate-account-pulse group-hover:scale-110 group-hover:rotate-6 transition"></i>
                            <span class="font-semibold"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        </span>
                        <!-- Logout Icon and Link -->
                        <a href="logout.php" class="flex items-center gap-2 px-3 py-1 rounded-full bg-gray-800/60 hover:bg-red-600/80 transition group cursor-pointer">
                            <i class="fas fa-sign-out-alt text-red-400 text-xl animate-logout-bounce transition"></i>
                            <span class="text-primary group-hover:text-white font-semibold">Logout</span>
                        </a>
                    </div>
                </div>
                
                <p class="mb-5 text-gray-400">Search your research data intelligently using Natural Language Processing.</p>
                
                <div class="flex flex-col md:flex-row mb-2">
                    <input 
                        type="text" 
                        id="mainSearchInput" 
                        placeholder="Ask about R&D..." 
                        class="flex-grow p-4 bg-gray-800 border-none rounded-l text-white mb-2 md:mb-0 focus:outline-none"
                    >
                    <button 
                        id="mainSearchBtn"
                        onclick="searchData('mainSearchInput')" 
                        class="p-4 bg-primary text-white border-none rounded-r md:w-auto cursor-pointer transition hover:opacity-80"
                    >Search</button>
                </div>
                <!-- Highlighted purple searching text -->
                <div id="search-status" class="text-primary text-center font-semibold my-2 hidden">Searching...</div>
                <div id="results" class="bg-dark/90 p-5 rounded shadow-2xl"></div>
            </div>
        </div>
        
        <footer class="bg-dark/90 text-center p-4 text-gray-400">
            © 2025 R&D AI Search. All rights reserved.
        </footer>
    </div>
    
    <script>
        // Show highlighted searching status and trigger search on Enter
        document.getElementById('mainSearchInput').addEventListener('keydown', function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                searchData('mainSearchInput');
            }
        });

        // Show "Searching..." for at least 2 seconds
        function searchData(inputId) {
            const query = document.getElementById(inputId).value;
            const searchStatus = document.getElementById("search-status");
            const resultsContainer = document.getElementById("results");
            const MIN_SEARCH_TIME = 2000; // 2 seconds

            if (query.trim() === "") {
                alert("Please enter a search query.");
                return;
            }

            resultsContainer.innerHTML = "";
            searchStatus.classList.remove("hidden"); // Show "Searching..."
            const startTime = Date.now();

            fetch(`./search.php?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    const elapsed = Date.now() - startTime;
                    const remaining = MIN_SEARCH_TIME - elapsed;
                    setTimeout(() => {
                        searchStatus.classList.add("hidden"); // Hide after min time
                        if (data.error) {
                            resultsContainer.innerHTML = `<p class="text-red-400">${data.error}</p>`;
                            return;
                        }

                        let resultHTML = `<h2 class="text-2xl mb-5 text-primary">Search Results</h2>`;
                        data.forEach(result => {
                            resultHTML += `
                                <div class="bg-gray-800 p-4 mb-4 rounded transition duration-300 hover:scale-105 hover:shadow-lg">
                                    <h3 class="text-primary mb-2">${result.title}</h3>
                                    <p>${result.snippet}</p>
                                </div>
                            `;
                        });
                        resultsContainer.innerHTML = resultHTML;
                    }, remaining > 0 ? remaining : 0);
                })
                .catch(error => {
                    const elapsed = Date.now() - startTime;
                    const remaining = MIN_SEARCH_TIME - elapsed;
                    setTimeout(() => {
                        searchStatus.classList.add("hidden");
                        resultsContainer.innerHTML = `<p class="text-red-400">An error occurred while searching.</p>`;
                    }, remaining > 0 ? remaining : 0);
                });
        }

        // Initialize background animation
        const backgroundSpans = document.querySelectorAll('.animate-background-span');
        backgroundSpans.forEach((span, index) => {
            const delay = Math.random() * 5;
            const duration = Math.random() * 5 + 5;
            span.style.animationDelay = `${delay}s`;
            span.style.animationDuration = `${duration}s`;
        });
    </script>
</body>
</html>
