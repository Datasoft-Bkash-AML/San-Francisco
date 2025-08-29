/* Lightweight interactions for new-frontend.php: reveal, parallax, tilt */
(function(){
  document.addEventListener('DOMContentLoaded', function(){
    const grid = document.querySelector('.rey-hero-grid');
    if(!grid) return;

    const cards = Array.from(grid.children);
    cards.forEach((el) => {
      el.classList.add('rey-reveal');
      const img = el.querySelector('img');
      if(img) img.classList.add('rey-parallax');
    });

    if('IntersectionObserver' in window){
      const io = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if(entry.isIntersecting){
            entry.target.classList.add('rey-in');
            entry.target.classList.remove('rey-reveal');
          }
        });
      }, { threshold: 0.12 });
      cards.forEach(c => io.observe(c));
    } else {
      cards.forEach(c => c.classList.add('rey-in'));
    }

    const parallaxEls = Array.from(document.querySelectorAll('.rey-parallax'));
    let ticking = false;
    function onScroll(){
      if(!ticking){
        window.requestAnimationFrame(() => {
          parallaxEls.forEach(img => {
            const rect = img.getBoundingClientRect();
            const windowH = window.innerHeight;
            const offset = (rect.top + rect.height/2 - windowH/2) / (windowH/2);
            const translate = Math.max(-18, Math.min(18, -offset * 8));
            img.style.transform = `translateY(${translate}px) scale(${img.matches(':hover') ? 1.04 : 1})`;
          });
          ticking = false;
        });
        ticking = true;
      }
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    onScroll();

    if(window.matchMedia('(pointer: fine)').matches){
      cards.forEach(card => {
        card.addEventListener('mousemove', function(e){
          const r = card.getBoundingClientRect();
          const px = (e.clientX - (r.left + r.width/2)) / (r.width/2);
          const py = (e.clientY - (r.top + r.height/2)) / (r.height/2);
          const rotY = px * 3;
          const rotX = -py * 3;
          card.style.transform = `perspective(900px) rotateX(${rotX}deg) rotateY(${rotY}deg) translateY(-6px)`;
        });
        card.addEventListener('mouseleave', function(){ card.style.transform = ''; });
      });
    }
  });
})();
