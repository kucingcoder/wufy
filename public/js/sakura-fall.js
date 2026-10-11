/**
 * Anime Japanese Style Sakura (Cherry Blossom) Falling Petals Animation
 * Inspired by classic anime cinema aesthetics (Makoto Shinkai / Your Name / 5cm/s)
 * High performance HTML5 Canvas implementation with 3D fluttering, ambient wind gusts & cursor interaction.
 */
(function() {
    'use strict';

    // Respect user's motion preferences
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReducedMotion) return;

    // Check localStorage preference
    let isEnabled = localStorage.getItem('sakura_fall_enabled') !== 'false';

    let canvas, ctx;
    let width, height;
    let particles = [];
    let animationFrameId = null;
    let lastTime = performance.now();
    let globalWind = 0.6;
    let windTime = 0;
    let gustIntensity = 0;
    let nextGustTime = performance.now() + 10000;

    // Mouse interaction coordinates
    let mouse = { x: -1000, y: -1000, active: false };
    let mouseTimer = null;

    // Palette variants for anime leaves/petals matching website sky-blue theme
    const PALETTES = [
        {
            // Luminous Sky Blue (primary website theme accent)
            frontStart: 'rgba(224, 242, 254, 0.95)', // sky-100 highlight
            frontEnd: 'rgba(56, 189, 248, 0.88)',   // sky-400
            back: 'rgba(14, 165, 233, 0.90)',       // sky-500
            crease: 'rgba(255, 255, 255, 0.6)'
        },
        {
            // Vibrant Cyan-Azure Leaf
            frontStart: 'rgba(240, 249, 255, 0.92)', // pure clean ice-blue
            frontEnd: 'rgba(14, 165, 233, 0.85)',   // sky-500
            back: 'rgba(2, 132, 199, 0.88)',        // sky-600
            crease: 'rgba(186, 230, 253, 0.55)'
        },
        {
            // Soft Light Sky Petal
            frontStart: 'rgba(255, 255, 255, 0.95)', // crystal white tip
            frontEnd: 'rgba(125, 211, 252, 0.82)',  // sky-300
            back: 'rgba(56, 189, 248, 0.85)',       // sky-400
            crease: 'rgba(255, 255, 255, 0.65)'
        },
        {
            // Deep Sky Breeze Leaf
            frontStart: 'rgba(186, 230, 253, 0.92)', // sky-200
            frontEnd: 'rgba(56, 189, 248, 0.85)',   // sky-400
            back: 'rgba(3, 105, 161, 0.85)',        // sky-700
            crease: 'rgba(224, 242, 254, 0.55)'
        }
    ];

    class SakuraPetal {
        constructor(isInitial = false) {
            this.reset(isInitial);
        }

        reset(isInitial = false) {
            // Depth layers: 0: background (small, subtle), 1: midground (standard), 2: foreground (large, fast)
            const depthRand = Math.random();
            if (depthRand < 0.45) {
                this.depth = 0; // background
                this.scale = 0.55 + Math.random() * 0.25;
                this.baseAlpha = 0.35 + Math.random() * 0.25;
                this.speedY = 0.6 + Math.random() * 0.6;
            } else if (depthRand < 0.85) {
                this.depth = 1; // midground
                this.scale = 0.85 + Math.random() * 0.3;
                this.baseAlpha = 0.65 + Math.random() * 0.25;
                this.speedY = 1.0 + Math.random() * 0.9;
            } else {
                this.depth = 2; // foreground
                this.scale = 1.2 + Math.random() * 0.45;
                this.baseAlpha = 0.8 + Math.random() * 0.18;
                this.speedY = 1.6 + Math.random() * 1.2;
            }

            this.size = (11 + Math.random() * 5) * this.scale;
            this.palette = PALETTES[Math.floor(Math.random() * PALETTES.length)];

            // Position: spawn across top and left margins with random distribution
            if (isInitial) {
                this.x = Math.random() * (width + 300) - 150;
                this.y = Math.random() * (height + 100) - 50;
            } else {
                // If wind is moving right, spawn mostly on top and left edge
                if (Math.random() < 0.75) {
                    this.x = Math.random() * (width + 200) - 100;
                    this.y = -30 - Math.random() * 60;
                } else {
                    this.x = -30 - Math.random() * 60;
                    this.y = Math.random() * (height * 0.75);
                }
            }

            // Sway & flutter dynamics
            this.swayAngle = Math.random() * Math.PI * 2;
            this.swaySpeed = 0.015 + Math.random() * 0.02;
            this.swayRadius = 0.8 + Math.random() * 1.2;

            // 3D rotation parameters
            this.rotation = Math.random() * Math.PI * 2;
            this.rotSpeed = (Math.random() - 0.5) * 0.025;

            // Flip (tumble end-over-end along X/Y axis)
            this.flipAngle = Math.random() * Math.PI * 2;
            this.flipSpeed = 0.02 + Math.random() * 0.035;

            // Roll (tilt side-to-side)
            this.rollAngle = Math.random() * Math.PI * 2;
            this.rollSpeed = 0.01 + Math.random() * 0.02;

            this.hasCrease = Math.random() > 0.35;
        }

        update(dt, currentWind, currentGust) {
            // Apply wind and gravity
            const windInfluence = (currentWind + currentGust) * (0.8 + this.depth * 0.4);
            const totalSpeedY = this.speedY * (1 + currentGust * 0.35);

            this.y += totalSpeedY * dt;
            this.x += (Math.cos(this.swayAngle) * this.swayRadius + windInfluence) * dt;

            // Update sway, tumble and rotation
            this.swayAngle += this.swaySpeed * dt;
            this.rotation += this.rotSpeed * dt;
            this.flipAngle += this.flipSpeed * (1 + currentGust * 0.5) * dt;
            this.rollAngle += this.rollSpeed * dt;

            // Interactive mouse aerodynamic nudge
            if (mouse.active) {
                const dx = this.x - mouse.x;
                const dy = this.y - mouse.y;
                const distSq = dx * dx + dy * dy;
                const maxDist = 130 * this.scale;
                if (distSq < maxDist * maxDist && distSq > 0) {
                    const dist = Math.sqrt(distSq);
                    const force = (1 - dist / maxDist) * 1.8;
                    this.x += (dx / dist) * force * dt;
                    this.y += (dy / dist) * force * 0.8 * dt;
                    this.flipAngle += force * 0.1;
                }
            }

            // Wrap when out of bounds
            if (this.y > height + 40 || this.x > width + 100 || this.x < -150) {
                this.reset(false);
            }
        }

        draw(ctx) {
            ctx.save();
            ctx.translate(this.x, this.y);

            // 3D rotation transform: rotation * 3D flip * roll
            ctx.rotate(this.rotation);

            // 3D flip compression: cos gives the visible height compression as it flips
            const flipCos = Math.cos(this.flipAngle);
            const rollCos = Math.cos(this.rollAngle);
            const scaleY = flipCos;
            const scaleX = rollCos * 0.3 + 0.7; // slight roll squish

            ctx.scale(scaleX, scaleY);

            // Determine if showing front or back side of the petal
            const isFront = flipCos >= 0;
            const s = this.size;

            // Create linear gradient
            const grad = ctx.createLinearGradient(0, s, 0, -s);
            if (isFront) {
                grad.addColorStop(0, this.palette.frontStart);
                grad.addColorStop(0.65, this.palette.frontEnd);
                grad.addColorStop(1, 'rgba(255, 255, 255, 0.98)'); // tip highlight
            } else {
                grad.addColorStop(0, this.palette.back);
                grad.addColorStop(0.7, this.palette.frontEnd);
                grad.addColorStop(1, 'rgba(224, 242, 254, 0.9)');
            }

            ctx.fillStyle = grad;
            ctx.globalAlpha = this.baseAlpha;

            // Draw authentic anime sakura petal geometry (with distinctive top notch)
            ctx.beginPath();
            // Bottom base (stem point)
            ctx.moveTo(0, s);
            // Left curve
            ctx.bezierCurveTo(-s * 0.72, s * 0.35, -s * 0.82, -s * 0.45, -s * 0.16, -s);
            // Top center cleft (sakura notch)
            ctx.lineTo(0, -s * 0.78);
            // Right notch peak
            ctx.lineTo(s * 0.16, -s);
            // Right curve down
            ctx.bezierCurveTo(s * 0.82, -s * 0.45, s * 0.72, s * 0.35, 0, s);
            ctx.closePath();
            ctx.fill();

            // Delicate center crease highlight for realism
            if (this.hasCrease && Math.abs(scaleY) > 0.3) {
                ctx.beginPath();
                ctx.moveTo(0, s * 0.75);
                ctx.quadraticCurveTo(-s * 0.04, 0, 0, -s * 0.7);
                ctx.strokeStyle = this.palette.crease;
                ctx.lineWidth = Math.max(0.5, s * 0.045);
                ctx.stroke();
            }

            ctx.restore();
        }
    }

    function init() {
        canvas = document.getElementById('sakura-canvas');
        if (!canvas) {
            canvas = document.createElement('canvas');
            canvas.id = 'sakura-canvas';
            canvas.className = 'fixed inset-0 w-full h-full pointer-events-none z-[1]';
            canvas.setAttribute('aria-hidden', 'true');
            document.body.appendChild(canvas);
        }

        ctx = canvas.getContext('2d', { alpha: true });
        handleResize();

        // Populate particles according to screen size (reduced for calmer ambiance)
        const isMobile = window.innerWidth < 768;
        const particleCount = isMobile ? 8 : 16;
        particles = [];
        for (let i = 0; i < particleCount; i++) {
            particles.push(new SakuraPetal(true));
        }

        // Event listeners
        window.addEventListener('resize', debounce(handleResize, 150));
        
        // Mouse / touch interaction
        window.addEventListener('mousemove', (e) => {
            mouse.x = e.clientX;
            mouse.y = e.clientY;
            mouse.active = true;
            clearTimeout(mouseTimer);
            mouseTimer = setTimeout(() => { mouse.active = false; }, 1500);
        }, { passive: true });

        window.addEventListener('touchmove', (e) => {
            if (e.touches && e.touches.length > 0) {
                mouse.x = e.touches[0].clientX;
                mouse.y = e.touches[0].clientY;
                mouse.active = true;
                clearTimeout(mouseTimer);
                mouseTimer = setTimeout(() => { mouse.active = false; }, 1500);
            }
        }, { passive: true });

        // Handle page visibility (pause when backgrounded to save CPU/battery)
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                if (animationFrameId) {
                    cancelAnimationFrame(animationFrameId);
                    animationFrameId = null;
                }
            } else if (isEnabled) {
                lastTime = performance.now();
                animate(lastTime);
            }
        });

        if (isEnabled) {
            lastTime = performance.now();
            animate(lastTime);
        }
    }

    function handleResize() {
        const dpr = Math.min(window.devicePixelRatio || 1, 2);
        width = window.innerWidth;
        height = window.innerHeight;
        canvas.width = width * dpr;
        canvas.height = height * dpr;
        canvas.style.width = width + 'px';
        canvas.style.height = height + 'px';
        ctx.setTransform(1, 0, 0, 1, 0, 0);
        ctx.scale(dpr, dpr);
    }

    function animate(currentTime) {
        if (!isEnabled) return;

        const delta = Math.min((currentTime - lastTime) / 16.666, 2.5); // normalized to 60fps
        lastTime = currentTime;

        // Evolving ambient anime wind
        windTime += 0.008 * delta;
        globalWind = 0.55 + Math.sin(windTime) * 0.45;

        // Periodic anime breeze gusts
        if (currentTime > nextGustTime) {
            gustIntensity = 1.2 + Math.random() * 0.8;
            nextGustTime = currentTime + 12000 + Math.random() * 8000;
        }
        if (gustIntensity > 0) {
            gustIntensity -= 0.008 * delta;
            if (gustIntensity < 0) gustIntensity = 0;
        }

        // Clear canvas
        ctx.clearRect(0, 0, width, height);

        // Update and draw petals
        for (let i = 0; i < particles.length; i++) {
            particles[i].update(delta, globalWind, gustIntensity);
            particles[i].draw(ctx);
        }

        animationFrameId = requestAnimationFrame(animate);
    }

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    // Auto initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
