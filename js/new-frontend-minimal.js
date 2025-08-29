document.addEventListener('DOMContentLoaded', function(){
  // Mobile toggle — simple class toggle to reveal nav (we keep markup simple)
  var toggle = document.querySelector('.min-mobile-toggle');
  var nav = document.querySelector('.minimal-main-nav');
  if(toggle && nav){
    toggle.addEventListener('click', function(){
      if(nav.style.display === 'flex') nav.style.display = 'none';
      else nav.style.display = 'flex';
      nav.style.flexDirection = 'column';
      nav.style.background = '#fff';
      nav.style.position = 'absolute';
      nav.style.top = '64px';
      nav.style.right = '20px';
      nav.style.padding = '12px';
      nav.style.boxShadow = '0 8px 24px rgba(0,0,0,0.08)';
    });
  }

  // Mock cart count: try to fetch current cart count from /cart.php if it exposes JSON (fallback to 0)
  var cartCountEl = document.querySelector('.min-cart-count');
  if(cartCountEl){
    try {
      fetch('/cart.php', {headers:{'Accept':'application/json'}}).then(function(r){
        if(!r.ok) throw new Error('no-json');
        return r.json();
      }).then(function(json){
        if(json && typeof json.count !== 'undefined') cartCountEl.textContent = json.count;
      }).catch(function(){ /* ignore, keep 0 */ });
    } catch(e){}
  }
});
