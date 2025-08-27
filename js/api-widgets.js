/* Simple API helpers for demo interactions: cart, wishlist, search
   - Uses fetch() and updates simple data- attributes for minimal UI updates
*/
(function () {
  function jsonFetch(url, opts) {
    opts = opts || {};
    opts.headers = opts.headers || {};
    if (!(opts.body instanceof FormData)) {
      opts.headers['Content-Type'] = 'application/json';
      if (opts.body && typeof opts.body !== 'string') opts.body = JSON.stringify(opts.body);
    }
    return fetch(url, opts).then(function (r) { return r.json(); });
  }

  // Add to cart button helper
  window.addToCart = function (productId, qty) {
    qty = qty || 1;
    return jsonFetch('/api/cart.php', { method: 'POST', body: { product_id: productId, qty: qty } }).then(function (res) {
      // refresh header count
      refreshCartCount();
      return res;
    });
  };

  // Wishlist toggle
  window.addToWishlist = function (productId) {
    return jsonFetch('/api/wishlist.php', { method: 'POST', body: { product_id: productId } }).then(function (res) { refreshWishlistCount(); return res; });
  };
  window.removeFromWishlist = function (productId) {
    return jsonFetch('/api/wishlist.php', { method: 'DELETE', body: { product_id: productId } }).then(function (res) { refreshWishlistCount(); return res; });
  };

  // Quick search suggestions
  var searchTimer = null;
  window.searchSuggest = function (q, cb) {
    if (searchTimer) clearTimeout(searchTimer);
    searchTimer = setTimeout(function () {
      jsonFetch('/api/search.php?q=' + encodeURIComponent(q)).then(function (res) {
        if (cb) cb(res.suggestions || []);
      }).catch(function () { if (cb) cb([]); });
    }, 220);
  };

  // Auto wire add-to-cart buttons with data-product-id attribute
  document.addEventListener('click', function (ev) {
    var t = ev.target.closest && ev.target.closest('[data-add-to-cart]');
    if (!t) return;
    ev.preventDefault();
    var pid = parseInt(t.getAttribute('data-product-id'), 10);
    if (!pid) return;
    t.disabled = true;
    addToCart(pid).then(function (res) {
      // quick UI feedback
      t.textContent = res && res.success ? 'Added' : 'Error';
      setTimeout(function () { t.disabled = false; t.textContent = 'Add to cart'; }, 900);
    }).catch(function () { t.disabled = false; t.textContent = 'Add to cart'; });
  }, false);

  // Auto wire wishlist buttons
  document.addEventListener('click', function (ev) {
    var t = ev.target.closest && ev.target.closest('[data-wishlist]');
    if (!t) return;
    ev.preventDefault();
    var pid = parseInt(t.getAttribute('data-product-id'), 10);
    if (!pid) return;
    t.disabled = true;
    addToWishlist(pid).then(function (res) {
      t.classList.add('is-in-wishlist');
      t.disabled = false;
    }).catch(function () { t.disabled = false; });
  }, false);

  // Refresh header counters
  function refreshCartCount() {
    jsonFetch('/api/cart.php').then(function (res) {
      var el = document.getElementById('header-cart-count');
      if (!el) return;
      var c = res && res.count ? res.count : 0;
      el.textContent = c;
      el.style.display = c ? 'inline-block' : 'none';
    }).catch(function(){ });
  }

  function refreshWishlistCount() {
    jsonFetch('/api/wishlist.php').then(function (res) {
      var el = document.getElementById('header-wishlist-count');
      if (!el) return;
      var c = res && res.count ? res.count : 0;
      el.textContent = c;
      el.style.display = c ? 'inline-block' : 'none';
    }).catch(function(){ });
  }

  // Initial load
  document.addEventListener('DOMContentLoaded', function () { refreshCartCount(); refreshWishlistCount(); });

  // Panel rendering and open/close
  function renderCartPanel() {
    var body = document.getElementById('panel-cart-body');
    if (!body) return;
    body.textContent = 'Loading\u2026';
    jsonFetch('/api/cart.php').then(function (res) {
      if (!res || !res.items) { body.textContent = 'No items'; return; }
      if (!res.items.length) { body.textContent = 'Your cart is empty'; return; }
      var html = '<div style="display:flex;flex-direction:column;gap:12px">';
      res.items.forEach(function (it) {
        var img = it.image ? ('/images/' + it.image) : '';
        html += '<div style="display:flex;gap:12px;align-items:center">'
          + '<div style="width:56px;height:56px;background:#f6f6f6;border-radius:6px;overflow:hidden"><img src="' + img + '" style="width:100%;height:100%;object-fit:cover"></div>'
          + '<div style="flex:1">'
            + '<div style="font-weight:700">' + (it.name||'') + '</div>'
            + '<div style="font-size:0.9rem;color:#666">Price: $' + (typeof it.price !== 'undefined' ? (it.price.toFixed ? it.price.toFixed(2) : Number(it.price).toFixed(2)) : '0.00') + '</div>'
            + '<div style="font-size:0.9rem;color:#666">Qty: <input data-cart-qty style="width:52px;padding:4px;margin-left:6px" data-product-id="' + it.product_id + '" value="' + (it.qty||0) + '"> <button data-cart-update data-product-id="' + it.product_id + '" style="margin-left:8px">Update</button> <button data-cart-remove data-product-id="' + it.product_id + '" style="margin-left:6px">Remove</button></div>'
            + '</div>'
          + '<div style="font-weight:800">$' + (it.subtotal ? Number(it.subtotal).toFixed(2) : '0.00') + '</div>'
          + '</div>';
      });
      html += '<div style="border-top:1px solid #eee;padding-top:8px;display:flex;justify-content:space-between;align-items:center"><div style="font-weight:700">Total</div><div style="font-weight:900;font-size:1.1rem">$' + (res.total ? Number(res.total).toFixed(2) : '0.00') + '</div></div>';
      html += '</div>';
      body.innerHTML = html;
    }).catch(function () { body.textContent = 'Error fetching cart'; });
  }

  function renderWishlistPanel() {
    var body = document.getElementById('panel-wishlist-body');
    if (!body) return;
    body.textContent = 'Loading…';
    jsonFetch('/api/wishlist.php').then(function (res) {
      if (!res || !res.items || !res.items.length) { body.textContent = 'Your wishlist is empty'; return; }
      var html = '<div style="display:flex;flex-direction:column;gap:12px">';
      res.items.forEach(function (it) {
        html += '<div style="display:flex;gap:12px;align-items:center">'
          + '<div style="width:56px;height:56px;background:#f6f6f6;border-radius:6px;overflow:hidden"><img src="' + (it.image ? '/images/' + it.image : '') + '" style="width:100%;height:100%;object-fit:cover"></div>'
          + '<div style="flex:1"><div style="font-weight:700">' + (it.name||'') + '</div></div>'
          + '</div>';
      });
      html += '</div>';
      body.innerHTML = html;
    }).catch(function () { body.textContent = 'Error fetching wishlist'; });
  }

  document.addEventListener('click', function (ev) {
    var t = ev.target.closest && ev.target.closest('[data-open]');
    if (!t) return;
    var what = t.getAttribute('data-open');
    if (what === 'cart') {
      var panel = document.getElementById('panel-cart');
      panel.style.display = 'block'; renderCartPanel();
    }
    if (what === 'wishlist') {
      var panel = document.getElementById('panel-wishlist');
      panel.style.display = 'block'; renderWishlistPanel();
    }
  }, false);

  // Cart update and remove handlers (delegated)
  document.addEventListener('click', function (ev) {
    var up = ev.target.closest && ev.target.closest('[data-cart-update]');
    if (up) {
      var pid = parseInt(up.getAttribute('data-product-id'), 10);
      var input = document.querySelector('input[data-cart-qty][data-product-id="' + pid + '"]');
      if (!input) return;
      var qty = parseInt(input.value, 10) || 0;
      up.disabled = true;
      jsonFetch('/api/cart.php', { method: 'POST', body: { product_id: pid, qty: qty } }).then(function (res) {
        up.disabled = false; refreshCartCount(); renderCartPanel();
      }).catch(function () { up.disabled = false; });
      return;
    }
    var rm = ev.target.closest && ev.target.closest('[data-cart-remove]');
    if (rm) {
      var pid2 = parseInt(rm.getAttribute('data-product-id'), 10);
      rm.disabled = true;
      jsonFetch('/api/cart.php', { method: 'DELETE', body: { product_id: pid2 } }).then(function (res) {
        rm.disabled = false; refreshCartCount(); renderCartPanel();
      }).catch(function () { rm.disabled = false; });
      return;
    }
  }, false);

  document.getElementById && document.getElementById('panel-cart-close') && document.getElementById('panel-cart-close').addEventListener('click', function () { document.getElementById('panel-cart').style.display = 'none'; });
  document.getElementById && document.getElementById('panel-wishlist-close') && document.getElementById('panel-wishlist-close').addEventListener('click', function () { document.getElementById('panel-wishlist').style.display = 'none'; });

})();
