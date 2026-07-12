<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LTN - Live & Notify Tourism</title>

    <meta name="google-site-verification" content="6oNiMI3leZqDcDNncGB-wJ8K5wQ62vr94OBrrnUYpFQ" />

    <link rel="icon" href="/favicon.svg?v=3" type="image/svg+xml">
    <link rel="shortcut icon" href="/favicon.svg?v=3" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            background-color: #020617;
            color: #ffffff;
            overflow-x: hidden;
        }

        *,
        *::before,
        *::after {
            box-sizing: inherit;
        }

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
            transform: scale(1.08);
            transition: opacity 1.6s ease-in-out, transform 8s linear;
        }

        .slide.active {
            opacity: 1;
            transform: scale(1);
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(2, 6, 23, 0.25), rgba(2, 6, 23, 0.88));
            z-index: 0;
        }

        .text-gradient {
            background: linear-gradient(90deg, #60a5fa, #93c5fd, #ffffff);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-content {
            position: relative;
            z-index: 10;
            padding: 120px 24px 100px;
            max-width: 980px;
            margin: 0 auto;
            text-align: center;
        }

        .hero-content h1 {
            margin-bottom: 1.5rem;
            line-height: 1.05;
        }

        .hero-content p {
            margin-bottom: 2rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.05rem;
            max-width: 42rem;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.8;
        }

        .cta-group {
            display: inline-flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
        }

        .btn-modern {
            padding: 1rem 2rem;
            border-radius: 999px;
            font-weight: 600;
            transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            margin: 0;
            min-height: 48px;
        }

        .btn-primary-glow {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 16px 30px rgba(59, 130, 246, 0.28);
        }

        .btn-primary-glow:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.32);
        }

        .btn-outline-glass {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.24);
            backdrop-filter: blur(12px);
            color: white;
        }

        .btn-outline-glass:hover {
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .admin-link {
            display: inline-block;
            margin-top: 1.75rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
            text-decoration: none;
            transition: color 0.25s ease;
        }

        .admin-link:hover {
            color: #60a5fa;
        }

        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.9s ease-out, transform 0.9s ease-out;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .hero-content {
                padding-top: 100px;
            }

            .hero-content h1 {
                font-size: 2.8rem;
            }

            .hero-content p {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="relative min-h-screen">
        <div class="hero-slider">
            <div class="slide active" style="background-image: url('https://images.unsplash.com/photo-1546182990-dffeafbe841d?q=80&w=2000')"></div>
            <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=2000')"></div>
            <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=2000')"></div>
            <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1557050543-4d5f4e07ef46?q=80&w=2000')"></div>
        </div>
        <div class="hero-overlay"></div>

        <nav class="relative z-50 px-10 py-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="/logo.svg" alt="LTN" class="w-10 h-10">
                <span class="text-xl font-bold tracking-widest">LTN</span>
            </div>
            <div class="hidden md:flex gap-8">
                <a href="https://live-notify-tour.onrender.com/login" class="hover:text-blue-400 transition">Sign In</a>
                <a href="https://live-notify-tour.onrender.com/register" class="bg-blue-600 px-6 py-2 rounded-full hover:bg-blue-700 transition">Join Now</a>
            </div>
        </nav>

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
                    Real-time updates on Tanzania's wildlife, luxury stays, and authentic flavors. Your ultimate safari guide starts here.
                </p>
                <div class="reveal active" style="transition-delay: 0.6s">
                    <a href="https://live-notify-tour.onrender.com/register" class="btn-modern btn-primary-glow">
                        Get Started
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    <a href="https://live-notify-tour.onrender.com/login" class="btn-modern btn-outline-glass">
                        Member Login
                    </a>
                    <a href="https://live-notify-tour.onrender.com/admin/login" class="admin-link">
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
        setInterval(nextSlide, 6000);

        // Reveal effect on load
        window.addEventListener('load', () => {
            document.querySelectorAll('.reveal').forEach(el => el.classList.add('active'));
        });
    </script>
</body>

</html>
