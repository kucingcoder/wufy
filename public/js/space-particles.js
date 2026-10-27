/**
 * Cosmic Space Particles & Shooting Stars Animation
 * Designed to harmonize with the stylized anime/sci-fi space background (background.webp)
 * Features:
 * - Ambient zero-gravity twinkling starlight & diamond sparkles
 * - Gentle drifting cosmic stardust with depth & parallax
 * - Graceful, occasional shooting stars (meteors) crossing the cosmos
 * - Smooth, subtle cursor gravitational wave
 * - High performance 60fps HTML5 Canvas with low CPU/battery overhead
 */
(function() {
    'use strict';

    // Respect user's motion preferences
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    let canvas, ctx;
    let width = 0, height = 0;
    let dpr = 1;
    let particles = [];
    let shootingStars = [];
    let animationFrameId = null;
    let lastTime = performance.now();
    let nextShootingStarTime = performance.now() + 1000 + Math.random() * 1200;

    // Interactive cursor coordinates
    const mouse = { x: -1000, y: -1000, active: false };
    let mouseTimeout = null;

    // Cosmic color palette matching website's sky-blue theme and background.webp
    const STAR_COLORS = [
        { r: 255, g: 255, b: 255 },  // Pure crystal white
        { r: 224, g: 242, b: 254 },  // Sky-100
        { r: 186, g: 230, b: 253 },  // Sky-200
        { r: 56,  g: 189, b: 248 },  // Sky-400
        { r: 125, g: 211, b: 252 }   // Sky-300
    ];

    /**
     * Stardust / Twinkling Star Particle
     */
    class SpaceParticle {
        constructor(isInitial = false) {
            this.reset(isInitial);
        }

        reset(isInitial = false) {
            this.x = Math.random() * width;
            this.y = isInitial ? Math.random() * height : (Math.random() < 0.5 ? -10 : Math.random() * height);

            // Depth layer (0: distant, 1: midground, 2: near floating mote)
            const rand = Math.random();
            if (rand < 0.5) {
                this.depth = 0;
                this.baseRadius = 0.8 + Math.random() * 0.8;
                this.baseAlpha = 0.25 + Math.random() * 0.35;
                this.vx = (Math.random() - 0.5) * 0.15;
                this.vy = 0.05 + Math.random() * 0.15;
                this.isSparkle = Math.random() < 0.25;
            } else if (rand < 0.85) {
                this.depth = 1;
                this.baseRadius = 1.4 + Math.random() * 1.0;
                this.baseAlpha = 0.45 + Math.random() * 0.35;
                this.vx = (Math.random() - 0.5) * 0.25;
                this.vy = 0.1 + Math.random() * 0.25;
                this.isSparkle = Math.random() < 0.4;
            } else {
                this.depth = 2;
                this.baseRadius = 2.2 + Math.random() * 1.2;
                this.baseAlpha = 0.6 + Math.random() * 0.35;
                this.vx = (Math.random() - 0.5) * 0.35;
                this.vy = 0.15 + Math.random() * 0.35;
                this.isSparkle = Math.random() < 0.5;
            }

            this.color = STAR_COLORS[Math.floor(Math.random() * STAR_COLORS.length)];
            
            // Twinkle / Pulse rhythm
            this.twinkleSpeed = 0.0015 + Math.random() * 0.003;
            this.twinklePhase = Math.random() * Math.PI * 2;
            this.sparkleSize = 4 + Math.random() * 5;
        }

        update(dt) {
            this.x += this.vx * dt;
            this.y += this.vy * dt;
            this.twinklePhase += this.twinkleSpeed * dt;

            // Interactive gentle cursor repulsion / gravity distortion
            if (mouse.active) {
                const dx = this.x - mouse.x;
                const dy = this.y - mouse.y;
                const distSq = dx * dx + dy * dy;
                const maxDist = 110 * (1 + this.depth * 0.4);
                if (distSq < maxDist * maxDist && distSq > 0) {
                    const dist = Math.sqrt(distSq);
                    const force = (1 - dist / maxDist) * 0.8;
                    this.x += (dx / dist) * force * dt;
                    this.y += (dy / dist) * force * dt;
                }
            }

            // Wrap around screen boundaries smoothly
            if (this.y > height + 20) {
                this.y = -20;
                this.x = Math.random() * width;
            } else if (this.y < -20) {
                this.y = height + 20;
                this.x = Math.random() * width;
            }
            if (this.x > width + 20) {
                this.x = -20;
            } else if (this.x < -20) {
                this.x = width + 20;
            }
        }

        draw(ctx) {
            // Calculate current pulsing opacity
            const twinkle = Math.sin(this.twinklePhase);
            const alpha = Math.max(0.1, Math.min(1.0, this.baseAlpha + twinkle * 0.35));
            const { r, g, b } = this.color;

            ctx.save();
            ctx.translate(this.x, this.y);

            // Draw soft circular glow
            const rad = this.baseRadius * (0.85 + twinkle * 0.25);
            const grad = ctx.createRadialGradient(0, 0, 0, 0, 0, rad * 2.5);
            grad.addColorStop(0, `rgba(${r}, ${g}, ${b}, ${alpha})`);
            grad.addColorStop(0.4, `rgba(${r}, ${g}, ${b}, ${alpha * 0.45})`);
            grad.addColorStop(1, `rgba(${r}, ${g}, ${b}, 0)`);

            ctx.fillStyle = grad;
            ctx.beginPath();
            ctx.arc(0, 0, rad * 2.5, 0, Math.PI * 2);
            ctx.fill();

            // Core bright point
            ctx.fillStyle = `rgba(255, 255, 255, ${Math.min(1, alpha + 0.2)})`;
            ctx.beginPath();
            ctx.arc(0, 0, rad * 0.6, 0, Math.PI * 2);
            ctx.fill();

            // For sparkle stars, draw subtle stylized 4-point anime diamond flare
            if (this.isSparkle && twinkle > 0.3) {
                const flareScale = (twinkle - 0.3) / 0.7;
                const flareLength = this.sparkleSize * flareScale;
                const flareWidth = 1 * flareScale;

                ctx.fillStyle = `rgba(${r}, ${g}, ${b}, ${alpha * 0.75})`;
                // Vertical spike
                ctx.beginPath();
                ctx.moveTo(0, -flareLength);
                ctx.quadraticCurveTo(flareWidth, 0, 0, flareLength);
                ctx.quadraticCurveTo(-flareWidth, 0, 0, -flareLength);
                ctx.fill();

                // Horizontal spike
                ctx.beginPath();
                ctx.moveTo(-flareLength, 0);
                ctx.quadraticCurveTo(0, flareWidth, flareLength, 0);
                ctx.quadraticCurveTo(0, -flareWidth, -flareLength, 0);
                ctx.fill();
            }

            ctx.restore();
        }
    }

    /**
     * Shooting Star (Meteor)
     */
    class ShootingStar {
        constructor() {
            // Natural spawn across upper sky and upper-left horizon
            if (Math.random() < 0.7) {
                this.x = Math.random() * (width * 0.85);
                this.y = Math.random() * (height * 0.35);
            } else {
                this.x = -40 - Math.random() * 60;
                this.y = Math.random() * (height * 0.45);
            }

            // Diagonal angle (around 30-42 degrees down and right)
            this.angle = (30 + Math.random() * 12) * (Math.PI / 180);
            this.speed = 750 + Math.random() * 400; // px per second
            this.length = 120 + Math.random() * 90;
            this.thickness = 1.6 + Math.random() * 0.8;

            this.vx = Math.cos(this.angle) * this.speed;
            this.vy = Math.sin(this.angle) * this.speed;

            this.life = 0;
            this.maxLife = 0.9 + Math.random() * 0.5; // duration in seconds
            this.dead = false;
        }

        update(dtSec) {
            this.life += dtSec;
            this.x += this.vx * dtSec;
            this.y += this.vy * dtSec;

            if (this.life >= this.maxLife || this.x > width + 100 || this.y > height + 100) {
                this.dead = true;
            }
        }

        draw(ctx) {
            // Fade in and out
            const progress = this.life / this.maxLife;
            let opacity = 1;
            if (progress < 0.25) {
                opacity = progress / 0.25;
            } else {
                opacity = 1 - (progress - 0.25) / 0.75;
            }

            const tailX = this.x - Math.cos(this.angle) * this.length;
            const tailY = this.y - Math.sin(this.angle) * this.length;

            const grad = ctx.createLinearGradient(this.x, this.y, tailX, tailY);
            grad.addColorStop(0, `rgba(255, 255, 255, ${opacity * 0.95})`);
            grad.addColorStop(0.15, `rgba(186, 230, 253, ${opacity * 0.8})`);
            grad.addColorStop(0.5, `rgba(56, 189, 248, ${opacity * 0.4})`);
            grad.addColorStop(1, 'rgba(56, 189, 248, 0)');

            ctx.save();
            ctx.lineWidth = this.thickness;
            ctx.lineCap = 'round';
            ctx.strokeStyle = grad;

            ctx.beginPath();
            ctx.moveTo(tailX, tailY);
            ctx.lineTo(this.x, this.y);
            ctx.stroke();

            // Head glow
            const headGlow = ctx.createRadialGradient(this.x, this.y, 0, this.x, this.y, 6);
            headGlow.addColorStop(0, `rgba(255, 255, 255, ${opacity})`);
            headGlow.addColorStop(0.5, `rgba(186, 230, 253, ${opacity * 0.6})`);
            headGlow.addColorStop(1, 'rgba(186, 230, 253, 0)');

            ctx.fillStyle = headGlow;
            ctx.beginPath();
            ctx.arc(this.x, this.y, 6, 0, Math.PI * 2);
            ctx.fill();

            ctx.restore();
        }
    }

    /**
     * Initialization & Resizing
     */
    function init() {
        canvas = document.getElementById('space-particles-canvas') || document.getElementById('sakura-canvas');
        if (!canvas) return;

        ctx = canvas.getContext('2d');
        resize();

        // Adjust particle density based on viewport size for calm, relaxed feel
        const isMobile = window.innerWidth < 768;
        const targetCount = isMobile ? 18 : 34;

        particles = [];
        for (let i = 0; i < targetCount; i++) {
            particles.push(new SpaceParticle(true));
        }

        // Event listeners
        window.addEventListener('resize', onResize, { passive: true });
        window.addEventListener('mousemove', onMouseMove, { passive: true });
        window.addEventListener('touchstart', onTouchMove, { passive: true });
        window.addEventListener('touchmove', onTouchMove, { passive: true });

        lastTime = performance.now();
        animationFrameId = requestAnimationFrame(renderLoop);
    }

    function resize() {
        if (!canvas) return;
        dpr = Math.min(window.devicePixelRatio || 1, 2);
        width = window.innerWidth;
        height = window.innerHeight;

        canvas.width = width * dpr;
        canvas.height = height * dpr;
        canvas.style.width = width + 'px';
        canvas.style.height = height + 'px';

        ctx.setTransform(1, 0, 0, 1, 0, 0);
        ctx.scale(dpr, dpr);
    }

    let resizeTimer = null;
    function onResize() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            resize();
            const isMobile = window.innerWidth < 768;
            const targetCount = isMobile ? 18 : 34;
            while (particles.length < targetCount) {
                particles.push(new SpaceParticle(true));
            }
            if (particles.length > targetCount) {
                particles.length = targetCount;
            }
        }, 150);
    }

    function onMouseMove(e) {
        mouse.x = e.clientX;
        mouse.y = e.clientY;
        mouse.active = true;
        clearTimeout(mouseTimeout);
        mouseTimeout = setTimeout(() => {
            mouse.active = false;
        }, 2000);
    }

    function onTouchMove(e) {
        if (e.touches && e.touches[0]) {
            mouse.x = e.touches[0].clientX;
            mouse.y = e.touches[0].clientY;
            mouse.active = true;
            clearTimeout(mouseTimeout);
            mouseTimeout = setTimeout(() => {
                mouse.active = false;
            }, 1500);
        }
    }

    /**
     * Main Render Loop
     */
    function renderLoop(currentTime) {
        const dtMs = Math.min(currentTime - lastTime, 100);
        const dtNorm = dtMs / 16.666;
        const dtSec = dtMs / 1000;
        lastTime = currentTime;

        // Clear canvas
        ctx.clearRect(0, 0, width, height);

        // Update and draw stardust particles
        for (let i = 0; i < particles.length; i++) {
            particles[i].update(dtNorm);
            particles[i].draw(ctx);
        }

        // Spawn shooting stars with balanced frequency (every ~2.5s - 4.5s, max 2 concurrent)
        if (currentTime >= nextShootingStarTime && shootingStars.length < 2) {
            shootingStars.push(new ShootingStar());
            // Randomize next spawn: usually 2.5s - 4.2s, with ~25% chance of a tandem star shortly after
            if (Math.random() < 0.25 && shootingStars.length < 2) {
                nextShootingStarTime = currentTime + 450 + Math.random() * 450;
            } else {
                nextShootingStarTime = currentTime + 2500 + Math.random() * 1800;
            }
        }

        // Update and draw shooting stars
        for (let i = shootingStars.length - 1; i >= 0; i--) {
            shootingStars[i].update(dtSec);
            shootingStars[i].draw(ctx);
            if (shootingStars[i].dead) {
                shootingStars.splice(i, 1);
            }
        }

        animationFrameId = requestAnimationFrame(renderLoop);
    }

    // Initialize on DOM ready or window load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
