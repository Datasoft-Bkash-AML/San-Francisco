


<?php
// new-frontend.php: Rey-style landing page for demo and migration
require __DIR__ . '/config.php';
require __DIR__ . '/includes/header.php';

// Fetch one representative product per desired category (Headphones, Speakers, Accessories)
$desiredCats = ['Headphones','Speakers','Accessories'];
$featured = [];
try {
  $placeholders = rtrim(str_repeat('?,', count($desiredCats)), ',');
  // find category ids/rowids for the desired category names
  $catStmt = $pdo->prepare("SELECT id, rowid, name FROM categories WHERE name IN ($placeholders)");
  $catStmt->execute($desiredCats);
  $catMap = [];
  while ($r = $catStmt->fetch(PDO::FETCH_ASSOC)) {
    $catMap[$r['name']] = !empty($r['id']) ? (int)$r['id'] : (int)$r['rowid'];
  }
  // For each desired category, pick the latest product
  foreach ($desiredCats as $dc) {
    if (!isset($catMap[$dc])) continue;
    $cid = $catMap[$dc];
    // Prefer featured product for this category
    $pstmt = $pdo->prepare('SELECT p.id, p.name, p.description, p.image, ? AS category_name FROM products p WHERE p.category_id = ? AND p.featured = 1 ORDER BY p.rowid DESC LIMIT 1');
    $pstmt->execute([$dc, $cid]);
    $row = $pstmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
      // fallback to latest product
      $pstmt = $pdo->prepare('SELECT p.id, p.name, p.description, p.image, ? AS category_name FROM products p WHERE p.category_id = ? ORDER BY p.rowid DESC LIMIT 1');
      $pstmt->execute([$dc, $cid]);
      $row = $pstmt->fetch(PDO::FETCH_ASSOC);
    }
    if ($row) $featured[] = $row;
  }
} catch (Exception $e) {
  // leave $featured empty and the template below will use sensible defaults
}

// Helper to safely echo values with fallback
function esc($v, $fallback = '') {
  if (empty($v) && $fallback !== '') return $fallback;
  return htmlspecialchars((string)($v ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Provide three slots with defaults (left, center, right)
$slot = [];
for ($i = 0; $i < 3; $i++) {
  $row = $featured[$i] ?? null;
    if ($row) {
    $imgFile = $row['image'] ?: 'product-a.jpg';
    // If image looks like a URL, use it directly; otherwise treat as local file under /images
    if (preg_match('#^https?://#i', $imgFile)) {
      $imgSrc = $imgFile;
    } else {
      $imgSrc = 'images/' . basename($imgFile);
    }
    $slot[$i] = [
      'image' => $imgSrc,
      'title' => $row['name'] ?: 'Featured Product',
      'subtitle' => $row['category_name'] ?: '',
      'description' => $row['description'] ?: '',
    ];
  } else {
    // sensible defaults pointing to existing images in the repo
    $defaults = [
      ['image' => 'images/2-936x1024.jpg', 'title' => "Innovative, wireless home\nspeaker", 'subtitle' => '', 'description' => ''],
      ['image' => 'images/13-330x361.jpg', 'title' => 'The future of health is\non your wrist', 'subtitle' => 'APPLE WATCH 8 SERIES', 'description' => ''],
      ['image' => 'images/25-330x361.jpg', 'title' => 'With active noise\ncancelling', 'subtitle' => 'EB 3RD GEN', 'description' => ''],
    ];
    $slot[$i] = $defaults[$i];
  }
}
?>



<!-- Hero/Main Grid Section -->
<section class="rey-hero-main" style="background: #fafafa; padding: 36px 0 0 0;">
  <div class="rey-hero-grid" style="display: grid; grid-template-columns: 2fr 1.2fr 1fr; gap: 28px; max-width: 1400px; margin: 0 auto; align-items: stretch;">
    <!-- Left: Large Feature (dark background, bold white text) -->
    <div style="background: #232323; border-radius: 22px; overflow: hidden; display: flex; flex-direction: column; justify-content: flex-end; min-height: 480px; position: relative; box-shadow: 0 2px 16px rgba(0,0,0,0.10);">
      <img src="<?php echo esc($slot[0]['image']); ?>" alt="<?php echo esc(str_replace("\n", ' ', $slot[0]['title'])); ?>" style="width: 100%; height: 340px; object-fit: cover; filter: brightness(0.85);">
      <div style="padding: 40px 40px 36px 40px; position: absolute; left: 0; bottom: 0; width: 100%;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 2.6rem; font-weight: 900; color: #fff; margin-bottom: 18px; line-height: 1.1;"><?php echo nl2br(esc($slot[0]['title'])); ?></h2>
        <a href="#" style="font-size: 1.1rem; font-weight: 700; color: #fff; text-decoration: none; border-bottom: 2px solid #fff; padding-bottom: 2px; letter-spacing: 1px;">Discover &rarr;</a>
      </div>
    </div>
    <!-- Center: Apple Watch Card (black background, white text, product image) -->
    <div style="background: #181818; border-radius: 22px; color: #fff; display: flex; flex-direction: column; justify-content: flex-end; min-height: 480px; position: relative; box-shadow: 0 2px 16px rgba(0,0,0,0.12);">
      <img src="<?php echo esc($slot[1]['image']); ?>" alt="<?php echo esc(str_replace("\n", ' ', $slot[1]['title'])); ?>" style="width: 70%; height: 220px; object-fit: contain; margin: 40px auto 0 auto; display: block;">
      <div style="padding: 40px 40px 36px 40px;">
        <div style="font-size: 1rem; color: #bbb; margin-bottom: 8px; letter-spacing: 1px;"><?php echo esc($slot[1]['subtitle']); ?></div>
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.6rem; font-weight: 900; color: #fff; margin-bottom: 18px; line-height: 1.15;"><?php echo nl2br(esc($slot[1]['title'])); ?></h3>
        <a href="#" style="font-size: 1.1rem; font-weight: 700; color: #fff; text-decoration: none; border-bottom: 2px solid #fff; padding-bottom: 2px; letter-spacing: 1px;">Discover &rarr;</a>
      </div>
    </div>
    <!-- Right: Newsletter and Promo Card -->
    <div style="display: flex; flex-direction: column; gap: 18px; min-height: 480px;">
      <!-- Newsletter Card -->
      <div style="background: #e6d1c3; border-radius: 22px; flex: 1 1 0; display: flex; flex-direction: column; justify-content: flex-end; position: relative; box-shadow: 0 2px 16px rgba(0,0,0,0.08);">
        <div style="padding: 40px 40px 36px 40px;">
          <div style="font-size: 1rem; color: #a67c52; margin-bottom: 8px; font-weight: 700; letter-spacing: 1px;">DON'T MISS</div>
          <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.35rem; font-weight: 900; color: #222; margin-bottom: 22px; line-height: 1.2;">Get 5% discount by<br>subscribing to our newsletter</h3>
          <form style="display: flex; gap: 8px;">
            <input type="email" placeholder="Your email address" style="flex:1; padding: 12px 16px; border-radius: 8px; border: none; font-size: 1rem;">
            <button type="submit" style="background: #222; color: #fff; font-weight: 900; border: none; border-radius: 8px; padding: 12px 28px; font-size: 1rem; cursor: pointer;">JOIN</button>
          </form>
        </div>
      </div>
      <!-- Promo Card -->
      <div style="background: #fff; border-radius: 22px; flex: 1 1 0; display: flex; flex-direction: column; justify-content: flex-end; position: relative; box-shadow: 0 2px 16px rgba(0,0,0,0.08); min-height: 180px;">
        <img src="<?php echo esc($slot[2]['image']); ?>" alt="<?php echo esc(str_replace("\n", ' ', $slot[2]['title'])); ?>" style="width: 100%; height: 120px; object-fit: cover; border-radius: 22px 22px 0 0;">
        <div style="padding: 24px 32px 20px 32px;">
          <div style="font-size: 0.95rem; color: #888; margin-bottom: 6px; font-weight: 700; letter-spacing: 1px;"><?php echo esc($slot[2]['subtitle']); ?></div>
          <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.15rem; font-weight: 900; color: #222; margin-bottom: 0; line-height: 1.2;"><?php echo nl2br(esc($slot[2]['title'])); ?></h4>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Enhanced animations: hover effects, scroll reveals, and parallax -->
<style>
  :root{ --rey-ease: cubic-bezier(.2,.9,.2,1); --rey-dur: 420ms; }
  html{ scroll-behavior: smooth; }

  /* Reveal animation for blocks */
  .rey-hero-grid > div { will-change: transform, opacity; transition: transform var(--rey-dur) var(--rey-ease), box-shadow var(--rey-dur) var(--rey-ease); }
  .rey-hero-grid img { display:block; width:100%; height:auto; transition: transform var(--rey-dur) var(--rey-ease), filter var(--rey-dur) var(--rey-ease); will-change: transform, filter; }

  /* Initial hidden state for scroll reveal */
  .rey-reveal { opacity: 0; transform: translateY(20px) scale(.995); }
  .rey-reveal.rey-in { opacity: 1; transform: none; transition: opacity 520ms var(--rey-ease), transform 520ms var(--rey-ease); }

  /* Hover interactions (desktop) */
  .rey-hero-grid > div:hover { transform: translateY(-6px); box-shadow: 0 18px 36px rgba(0,0,0,0.12); }
  .rey-hero-grid > div:hover img { transform: scale(1.04); filter: brightness(1.03); }

  /* Slight different energy for center black card */
  .rey-hero-grid > div:nth-child(2):hover img { transform: scale(1.06) rotate(-1deg); }

  /* Add an overlay text pop on hover (targets absolute-positioned text inside cards) */
  .rey-hero-grid div [style*="position: absolute"],
  .rey-hero-grid div [style*="position: relative"] { transition: transform var(--rey-dur) var(--rey-ease), opacity var(--rey-dur) var(--rey-ease); }
  .rey-hero-grid > div:hover > div { transform: translateY(-6px); }

  /* Make newsletter button feel clickable */
  .rey-hero-grid button { transition: transform 180ms var(--rey-ease); }
  .rey-hero-grid button:active { transform: translateY(1px) scale(.995); }

  /* Parallax helper (images inside hero) */
  .rey-parallax { will-change: transform; transform: translateY(0); }

  /* Responsive adjustments — keep existing inline grid but provide nicer stacking */
  @media (max-width: 980px){
    .rey-hero-grid{ grid-template-columns: 1fr !important; gap:18px !important; }
    .rey-hero-grid > div{ min-height: 320px !important; }
  }
</style>

<script>
  (function(){
    // Progressive enhancement only
    document.addEventListener('DOMContentLoaded', function(){
      const grid = document.querySelector('.rey-hero-grid');
      if(!grid) return;

      // Add reveal class to each main card
      const cards = Array.from(grid.children);
      cards.forEach((el, idx) => {
        el.classList.add('rey-reveal');
        // mark product images for parallax
        const img = el.querySelector('img');
        if(img) img.classList.add('rey-parallax');
      });

      // IntersectionObserver for reveal on scroll
      if('IntersectionObserver' in window){
        const io = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
            if(entry.isIntersecting){
              entry.target.classList.add('rey-in');
              // small timeout to trigger the final state class for smoother transition
              setTimeout(()=> entry.target.classList.add('rey-in-view','rey-in'), 20);
              entry.target.classList.add('rey-in-view');
              // add a second class used by CSS
              entry.target.classList.add('rey-in');
              // add a nicer semantic class used above
              entry.target.classList.add('rey-in');
              entry.target.classList.add('rey-in');
              entry.target.classList.add('rey-in');
              entry.target.classList.remove('rey-reveal');
              entry.target.classList.add('rey-in');
              entry.target.classList.add('rey-in');
              // Also add the 'rey-in' marker used by CSS
  <!-- Styles and interaction logic moved to css/new-frontend.css and js/new-frontend.js -->
              // Final class used by our CSS

              entry.target.classList.add('rey-in');
