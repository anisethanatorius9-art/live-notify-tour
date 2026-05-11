<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LTN - Live & Notify Tourism</title>

    <link rel="icon" href="/favicon.svg?v=3" type="image/svg+xml">
    <link rel="shortcut icon" href="/favicon.svg?v=3" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #020617;
            color: #ffffff;
            overflow-x: hidden;
        }

        /* Background Image Slider */
        .hero-slider {
            position: absolute;
            inset: 0;
            z-index: -1;
            overflow: hidden;
        }

        .slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transform: scale(1.1);
            transition: opacity 2s ease-in-out, transform 8s linear;
        }

        .slide.active {
            opacity: 1;
            transform: scale(1);
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(2, 6, 23, 0.3), rgba(2, 6, 23, 0.8));
            z-index: 0;
        }

        /* FIX KWA ILE ERROR YAKO YA CSS */
        .text-gradient {
            background: linear-gradient(to right, #60a5fa, #93c5fd, #ffffff);
            -webkit-background-clip: text;
            /* Safari/Chrome */
            background-clip: text;
            /* Standard property - Hapa ndipo palikuwa na error */
            -webkit-text-fill-color: transparent;
        }

        /* Buttons & Spacing */
        .hero-content {
            position: relative;
            z-index: 10;
            padding-top: 120px;
        }

        .btn-modern {
            padding: 1.2rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.4s ease;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            margin: 10px;
            /* Hii inazuia buttons kubanana */
        }

        .btn-primary-glow {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }

        .btn-primary-glow:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(59, 130, 246, 0.5);
        }

        .btn-outline-glass {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            color: white;
        }

        .btn-outline-glass:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: white;
        }

        .admin-link {
            display: block;
            margin-top: 30px;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.9rem;
            text-decoration: none;
            transition: 0.3s;
        }

        .admin-link:hover {
            color: #60a5fa;
        }

        /* Animations */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: 1s ease-out;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body>
    <div class="relative min-h-screen">

        <!-- Background Slider (Picha za Wanyama, Hotel, Chakula) -->
        <div class="hero-slider">
            <!-- Picha 1: Simba (Wildlife) -->
            <div class="slide active" style="background-image: url('https://images.unsplash.com/photo-1546182990-dffeafbe841d?q=80&w=2000')"></div>
            <!-- Picha 2: Hotel ya Kifahari Zanzibar -->
            <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=2000')"></div>
            <!-- Picha 3: Chakula cha Kitanzania/Mataunda -->
            <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=2000')"></div>
            <!-- Picha 4: Tembo Serengeti -->
            <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1557050543-4d5f4e07ef46?q=80&w=2000')"></div>
        </div>

        <div class="hero-overlay"></div>

        <!-- Navbar -->
        <nav class="relative z-50 px-10 py-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="/logo.svg" alt="LTN" class="w-10 h-10">
                <span class="text-xl font-bold tracking-widest">LTN</span>
            </div>
            <div class="hidden md:flex gap-8">
                <a href="{{ route('login') }}" class="hover:text-blue-400 transition">Sign In</a>
                <a href="{{ route('register') }}" class="bg-blue-600 px-6 py-2 rounded-full hover:bg-blue-700 transition">Join Now</a>
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="hero-content text-center px-6">
            <div class="max-w-4xl mx-auto">
                <div class="reveal active mb-4">
                    <span class="px-4 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-bold uppercase tracking-widest">
                        Experience Tanzania
                    </span>
                </div>

                <h1 class="reveal active text-5xl md:text-8xl font-bold mb-8" style="font-family: 'Playfair Display', serif; transition-delay: 0.2s">
                    Discover the <br> <span class="text-gradient">Untamed Beauty</span>
                </h1>

                <p class="reveal active text-lg md:text-xl text-white/80 mb-12 max-w-2xl mx-auto leading-relaxed" style="transition-delay: 0.4s">
                    Real-time updates on Tanzania's wildlife, luxury stays, and authentic flavors.
                    Your ultimate safari guide starts here.
                </p>


                <div class="reveal active" style="transition-delay: 0.6s">
                    <a href="{{ route('register') }}" class="btn-modern btn-primary-glow">
                        Get Started
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>

                    <a href="{{ route('login') }}" class="btn-modern btn-outline-glass">
                        Member Login
                    </a>

                    <a href="{{ route('admin.login') }}" class="admin-link">
                        Admin Portal →
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Slider Logic
        const slides = document.querySelectorAll('.slide');
        let current = 0;

        function nextSlide() {
            slides[current].classList.remove('active');
            current = (current + 1) % slides.length;
            slides[current].classList.add('active');
        }
        setInterval(nextSlide, 6000); // Badilisha kila sekunde 6

        // Reveal effect on load
        window.addEventListener('load', () => {
            document.querySelectorAll('.reveal').forEach(el => el.classList.add('active'));
        });
    </script>
</body>

</html>
