<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LTN - Live & Notify Tourism</title>
    <link rel="icon" href="/favicon.svg?v=3" type="image/svg+xml">
    <link rel="shortcut icon" href="/favicon.svg?v=3" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/favicon.svg?v=3">
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
            overflow-x: hidden;
        }

        .hero-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .hero-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(59, 130, 246, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 50%, rgba(29, 78, 216, 0.15) 0%, transparent 50%);
            animation: gradientShift 15s ease-in-out infinite;
        }

        @keyframes gradientShift {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .logo-container {
            position: relative;
            display: inline-block;
        }

        .logo-container::before {
            content: '';
            position: absolute;
            inset: -10px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.4) 0%, transparent 70%);
            border-radius: 50%;
            animation: logoGlow 3s ease-in-out infinite;
        }

        @keyframes logoGlow {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.2);
                opacity: 0.8;
            }
        }

        .logo-spin {
            animation: logoSpin 20s linear infinite;
        }

        @keyframes logoSpin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .fade-in {
            opacity: 0;
            animation: fadeIn 1s ease forwards;
        }

        .fade-in-up {
            opacity: 0;
            transform: translateY(40px);
            animation: fadeInUp 1s ease forwards;
        }

        .slide-in-left {
            opacity: 0;
            transform: translateX(-50px);
            animation: slideInLeft 1s ease forwards;
        }

        .slide-in-right {
            opacity: 0;
            transform: translateX(50px);
            animation: slideInRight 1s ease forwards;
        }

        .scale-in {
            opacity: 0;
            transform: scale(0.8);
            animation: scaleIn 1s ease forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes scaleIn {
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

        .delay-400 {
            animation-delay: 0.4s;
        }

        .delay-500 {
            animation-delay: 0.5s;
        }

        .delay-600 {
            animation-delay: 0.6s;
        }

        .delay-700 {
            animation-delay: 0.7s;
        }

        .delay-800 {
            animation-delay: 0.8s;
        }

        .float-animation {
            animation: floatAnimation 3s ease-in-out infinite;
        }

        @keyframes floatAnimation {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        .pulse-glow {
            animation: pulseGlow 2s ease-in-out infinite;
        }

        @keyframes pulseGlow {

            0%,
            100% {
                box-shadow: 0 0 30px rgba(59, 130, 246, 0.4);
            }

            50% {
                box-shadow: 0 0 60px rgba(59, 130, 246, 0.8);
            }
        }

        .text-gradient {
            background: linear-gradient(90deg, #3b82f6, #60a5fa, #93c5fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientFlow 5s ease infinite;
            background-size: 200% auto;
        }

        @keyframes gradientFlow {
            0% {
                background-position: 0% center;
            }

            50% {
                background-position: 200% center;
            }

            100% {
                background-position: 0% center;
            }
        }

        .gold-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 50%, #1e40af 100%);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.4);
        }

        .hero-action-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5rem;
        }

        .btn-login {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: all 0.6s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-register {
            border: 2px solid rgba(59, 130, 246, 0.6);
            background: rgba(59, 130, 246, 0.1);
            backdrop-filter: blur(5px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-register:hover {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        }

        .btn-icon {
            transition: all 0.3s ease;
        }

        .btn-login:hover .btn-icon,
        .btn-register:hover .btn-icon {
            transform: translateX(6px);
        }

        .btn-nav-login {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-nav-login:hover {
            background: rgba(59, 130, 246, 0.2);
            border-color: #3b82f6;
        }

        .btn-nav-login::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: #3b82f6;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .btn-nav-login:hover::after {
            width: 80%;
        }

        .btn-nav-register {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            transition: all 0.3s ease;
        }

        .btn-nav-register:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.5);
        }

        @keyframes shimmer {
            0% {
                background-position: -200% center;
            }

            100% {
                background-position: 200% center;
            }
        }

        .btn-shimmer {
            background: linear-gradient(90deg, #3b82f6, #60a5fa, #3b82f6);
            background-size: 200% auto;
            animation: shimmer 3s linear infinite;
        }

        nav {
            transition: all 0.3s ease;
        }

        nav.scrolled {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(59, 130, 246, 0.6);
            border-radius: 50%;
            animation: particleFloat 15s linear infinite;
        }

        @keyframes particleFloat {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(-100vh) rotate(720deg);
                opacity: 0;
            }
        }

        .stats-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body>
    <div class="hero-bg">
        <!-- Animated particles -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
            <div class="particle" style="left: 20%; animation-delay: 2s;"></div>
            <div class="particle" style="left: 30%; animation-delay: 4s;"></div>
            <div class="particle" style="left: 40%; animation-delay: 6s;"></div>
            <div class="particle" style="left: 50%; animation-delay: 8s;"></div>
            <div class="particle" style="left: 60%; animation-delay: 10s;"></div>
            <div class="particle" style="left: 70%; animation-delay: 12s;"></div>
            <div class="particle" style="left: 80%; animation-delay: 14s;"></div>
            <div class="particle" style="left: 90%; animation-delay: 16s;"></div>
        </div>

        <!-- Glow orbs -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl float-animation"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl float-animation" style="animation-delay: 1.5s;"></div>

        <nav class="fixed top-0 left-0 right-0 z-50 px-6 py-4 flex items-center justify-between" id="navbar">
            <div class="flex items-center gap-3 slide-in-left">
                <div class="logo-container">
                    <div class="w-14 h-14 rounded-2xl gold-gradient flex items-center justify-center pulse-glow">
                        <img src="/logo.svg" alt="LTN Logo" class="w-10 h-10 logo-spin">
                    </div>
                </div>
                <span class="text-white font-bold text-xl fade-in" style="font-family: 'Playfair Display', serif;">
                    <span class="text-gradient">LTN</span> - Live & Notify
                </span>
            </div>
            <div class="hidden md:flex items-center gap-4 slide-in-right">
                <a href="{{ route('login') }}" class="btn-nav-login px-6 py-2.5 rounded-full text-white font-medium text-sm">
                    Login
                </a>
                <a href="{{ route('register') }}" class="btn-nav-register px-6 py-2.5 rounded-full text-white font-semibold text-sm shadow-lg">
                    Register
                </a>
            </div>
            <button class="md:hidden text-white hover:text-blue-400 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </nav>

        <div class="relative z-10 min-h-screen flex items-center justify-center px-6 pt-20">
            <div class="text-center max-w-5xl mx-auto">
                <div class="fade-in-up delay-100 mb-6">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-500/10 border border-blue-400/20 text-blue-300 text-sm backdrop-blur-sm">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                        Revolutionizing Tourism in Tanzania
                    </span>
                </div>

                <div class="fade-in-up delay-200 mb-8 flex justify-center">
                    <div class="logo-container">
                        <div class="w-28 h-28 md:w-36 md:h-36 rounded-3xl gold-gradient flex items-center justify-center pulse-glow shadow-2xl">
                            <img src="/logo.svg" alt="LTN Logo" class="w-20 h-20 md:w-24 md:h-24 logo-spin" style="animation-duration: 30s;">
                        </div>
                    </div>
                </div>

                <h1 class="fade-in-up delay-300 text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-6" style="font-family: 'Playfair Display', serif;">
                    Explore Tanzania with
                    <span class="block text-gradient text-5xl md:text-7xl lg:text-8xl mt-2">Live & Notify</span>
                </h1>

                <p class="fade-in-up delay-400 text-lg md:text-xl text-white/70 mb-10 max-w-3xl mx-auto leading-relaxed">
                    Experience the magic of Tanzania's most iconic destinations with real-time updates.
                    From the Serengeti to Zanzibar - your ultimate safari adventure starts here.
                </p>

                <div class="fade-in-up delay-500 flex flex-col items-center justify-center gap-6 mb-16">
                    <div class="hero-action-group">
                        <a href="{{ route('register') }}" class="btn-login px-10 py-4 rounded-full text-white font-bold text-lg flex items-center justify-center gap-3 shadow-2xl">
                            <svg class="w-6 h-6 btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Get Started
                        </a>
                        <a href="{{ route('login') }}" class="btn-register px-10 py-4 rounded-full text-white font-semibold text-lg flex items-center justify-center gap-3">
                            <svg class="w-6 h-6 btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Sign In
                        </a>
                    </div>
                </div>

                <!-- Stats -->
                <div class="fade-in-up delay-600 grid grid-cols-3 gap-6 max-w-3xl mx-auto">
                    <div class="stats-card rounded-2xl p-6 text-center">
                        <div class="text-3xl md:text-4xl font-bold text-gradient mb-2"></div>
                        <div class="text-white/60 text-sm">Tours Available</div>
                    </div>
                    <div class="stats-card rounded-2xl p-6 text-center">
                        <div class="text-3xl md:text-4xl font-bold text-gradient mb-2"></div>
                        <div class="text-white/60 text-sm">Destinations</div>
                    </div>
                    <div class="stats-card rounded-2xl p-6 text-center">
                        <div class="text-3xl md:text-4xl font-bold text-gradient mb-2"></div>
                        <div class="text-white/60 text-sm">Happy Travelers</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="py-16 px-6 bg-gray-900 relative z-20">
        <div class="max-w-6xl mx-auto">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-xl gold-gradient flex items-center justify-center">
                            <img src="/logo.svg" alt="LTN Logo" class="w-8 h-8">
                        </div>
                        <span class="text-white font-bold text-xl" style="font-family: 'Playfair Display', serif;">
                            <span class="text-gradient">LTN</span>
                        </span>
                    </div>
                    <p class="text-white/60 max-w-md leading-relaxed">
                        Revolutionizing tourism with real-time updates, seamless booking, and unforgettable
                        safari experiences across Tanzania's most iconic destinations.
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-3">
                        <li><a href="#destinations" class="text-white/60 hover:text-blue-400 transition-colors">Destinations</a></li>
                        <li><a href="#features" class="text-white/60 hover:text-blue-400 transition-colors">Features</a></li>
                        <li><a href="#about" class="text-white/60 hover:text-blue-400 transition-colors">About Us</a></li>
                        <li><a href="#contact" class="text-white/60 hover:text-blue-400 transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Contact</h4>
                    <ul class="space-y-3 text-white/60">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            +255 623 275 203
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            aanatorius@gmail.com
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Dar es Salaam, Tanzania
                        </li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-6">
                <p class="text-white/40 text-sm">
                    © 2026 LTN - Live & Notify Tourism. All rights reserved.
                </p>
                <div class="flex items-center gap-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white/60 hover:text-white hover:bg-blue-500/20 transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white/60 hover:text-white hover:bg-blue-500/20 transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.949.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.979-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white/60 hover:text-white hover:bg-blue-500/20 transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.675 0h-21.35c-.807 0-1.458.598-1.458 1.344v21.312c0 .746.651 1.344 1.458 1.344h11.495v-9.294h-3.128v-3.622h3.128v-2.742c0-3.08 1.922-4.698 4.615-4.698 1.312 0 2.703.235 2.703.235v2.973h-1.527c-1.503 0-1.954.931-1.954 1.884v2.262h3.296l-.527 3.622h-2.769v9.294h6.837c.73 0 1.323-.548 1.323-1.225v-21.38c0-.746-.593-1.344-1.344-1.344z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in-up, .slide-in-left, .slide-in-right, .scale-in').forEach(function(el) {
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });
    </script>
</body>

</html>
