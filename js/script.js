// script.js - basic JS interactions

// Placeholder for future carousel or filter interactions
console.log('San Francisco Demo loaded');

// Smooth scroll for Explore Products button
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.querySelector('.btn');
    if (btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector('#products');
            if (target) {
                window.scrollTo({
                    top: target.offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    }
});

// Auto-rotate product cards (image, name, description) with fade
document.addEventListener('DOMContentLoaded', function() {
    const endpoint = '/api/products.php';
    let products = window.__initialProducts || [];

    fetch(endpoint).then(r => r.json()).then(data => {
        products = data;
        startRotation(products);
    }).catch(()=>{
        // fallback to server-rendered products
        startRotation(products);
    });

    function startRotation(list) {
        if (!list || !list.length) return;
        const cards = Array.from(document.querySelectorAll('#products .product'));
        const interval = 5000;
        let idx = 0;
        let running = true;
        let timer = null;

        // add controls container
        let controls = document.querySelector('.rotation-controls');
        if (!controls) {
            controls = document.createElement('div');
            controls.className = 'rotation-controls';
            controls.innerHTML = '<button class="rot-btn" data-action="toggle">Pause</button>';
            document.body.appendChild(controls);
        }
        const toggleBtn = controls.querySelector('[data-action="toggle"]');

        // helper: preload image
        function preload(src) {
            if (!src) return;
            const i = new Image(); i.src = src;
        }

        // prepare CSS transition
        cards.forEach(c => {
            c.style.transition = 'opacity 400ms ease, transform 220ms ease';
            c.addEventListener('mouseenter', () => pause());
            c.addEventListener('mouseleave', () => resume());
        });

        function tick() {
            cards.forEach((card, i) => {
                const p = list[(i + idx) % list.length];
                if (!p) return;
                card.style.opacity = 0;
                card.style.transform = 'translateY(6px)';
                setTimeout(() => {
                    const img = card.querySelector('img');
                    const h3 = card.querySelector('h3');
                    const pEl = card.querySelector('p');
                    const src = p.image_url || ('/images/' + (p.image || ''));
                    if (img && img.src !== src) img.src = src;
                    if (h3) h3.textContent = p.name || '';
                    if (pEl) pEl.textContent = p.description || '';
                    card.style.opacity = 1;
                    card.style.transform = 'translateY(0)';
                }, 420);
            });
            idx = (idx + 1) % list.length;
            // preload next round images
            for (let j = 0; j < cards.length; j++) {
                const next = list[(j + idx) % list.length];
                if (next) preload(next.image_url || ('/images/' + (next.image || '')));
            }
        }

        function startTimer() { timer = setInterval(tick, interval); }
        function stopTimer() { if (timer) { clearInterval(timer); timer = null; } }

        function pause() { running = false; stopTimer(); if (toggleBtn) toggleBtn.textContent = 'Play'; }
        function resume() { if (!running) { running = true; startTimer(); if (toggleBtn) toggleBtn.textContent = 'Pause'; } }

        // toggle button
        toggleBtn.addEventListener('click', () => {
            if (running) { pause(); } else { resume(); }
        });

        // keyboard controls: left/right
        document.addEventListener('keydown', (ev) => {
            if (ev.key === 'ArrowLeft') { idx = (idx - 2 + list.length) % list.length; tick(); }
            if (ev.key === 'ArrowRight') { tick(); }
            if (ev.key === ' ') { ev.preventDefault(); if (running) pause(); else resume(); }
        });

        // start
        tick();
        startTimer();
    }
});