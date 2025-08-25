const fs = require('fs');
const { JSDOM } = require('jsdom');
const html = fs.readFileSync('index.html', 'utf8');
const script = fs.readFileSync('js/script.js', 'utf8');

(async () => {
  const dom = new JSDOM(html, { runScripts: 'dangerously', resources: 'usable' });
  // inject script content
  const s = dom.window.document.createElement('script');
  s.textContent = script;
  dom.window.document.body.appendChild(s);
  // wait a short time to allow setInterval and listeners to register
  await new Promise(r => setTimeout(r, 500));
  console.log('OK: script executed, controls present=', !!dom.window.document.querySelector('.rotation-controls'));
})();
