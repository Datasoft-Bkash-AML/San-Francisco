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

  // Feature toggle buttons: elements with data-feature-toggle and data-product-id
  document.addEventListener('click', function (ev) {
    var t = ev.target.closest && ev.target.closest('[data-feature-toggle]');
    if (!t) return;
    ev.preventDefault();
    var id = parseInt(t.getAttribute('data-product-id'), 10);
    var csrf = t.getAttribute('data-csrf');
    var currently = t.getAttribute('data-featured') === '1';
    t.disabled = true;
    jsonFetch('/admin/api/feature.php', { method: 'POST', body: { id: id, featured: currently ? 0 : 1, csrf: csrf } })
      .then(function (res) {
        if (res && res.success) {
          t.setAttribute('data-featured', res.featured ? '1' : '0');
          t.textContent = res.featured ? '★ Featured' : '☆ Feature';
        } else {
          alert('Failed to toggle featured');
        }
      }).catch(function () { alert('Error'); })
      .finally(function () { t.disabled = false; });
  }, false);

  // AJAX image upload: form with id #admin-upload-form containing file input name=image and an element data-upload-target to receive filename
  var uploadForm = document.getElementById('admin-upload-form');
  if (uploadForm) {
    uploadForm.addEventListener('submit', function (ev) {
      ev.preventDefault();
      var fd = new FormData(uploadForm);
      var btn = uploadForm.querySelector('[type=submit]');
      if (btn) btn.disabled = true;
      fetch('/admin/api/upload-image.php', { method: 'POST', body: fd }).then(function (r) { return r.json(); }).then(function (res) {
        if (res && res.success) {
          // populate existing_image select or an input
          var target = document.querySelector('[name=existing_image]');
          if (target) {
            var opt = document.createElement('option'); opt.value = res.filename; opt.textContent = res.filename; target.appendChild(opt); target.value = res.filename;
          }
          alert('Uploaded: ' + res.filename);
        } else {
          alert('Upload failed: ' + (res && res.error));
        }
      }).catch(function () { alert('Upload error'); }).finally(function () { if (btn) btn.disabled = false; });
    });
  }

})();
