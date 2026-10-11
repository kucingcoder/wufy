# Design Direction: Wufy Portfolio with Anime Japanese Aesthetic

## Identity & Mood
- **Context:** Developer & technology specialist personal portfolio.
- **Visual Mood:** Modern cyberpunk/minimalist tech merged with poetic Japanese anime ambiance (Makoto Shinkai / Your Name inspired spring wind with fluttering sakura petals).
- **Dials:** ENERGY 2 / RHYTHM 2 / MOTION 2
- **Motion Purpose:** Ambient falling sakura petals that bring life, warmth, and artistic depth to the night sky background, without distracting from content readability or interaction.

## Color System
- **Base Neutral:** Deep night slate (`#020617`, `slate-900`, `slate-950`).
- **Primary Accent:** Sky Blue (`#0ea5e9` / `sky-500`).
- **Petal Accent:** Sky Blue theme palette (`sky-100`, `sky-300`, `sky-400`, `sky-500`, `sky-600`).

## Craftsmanship & Accessibility
- Petals rendered with high-performance HTML5 Canvas or hardware-accelerated SVG/CSS with zero input latency.
- Completely non-blocking: `pointer-events: none` on the animation canvas/container.
- Respect `prefers-reduced-motion`: pauses or gently disables animation when reduced motion is preferred.
- Subtle wind variation, natural tumbling rotation (3D flutter), and varied depths (foreground, midground, background petals) to create true cinematic anime depth.
