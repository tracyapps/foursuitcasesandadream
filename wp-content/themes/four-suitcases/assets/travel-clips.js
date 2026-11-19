const motion = matchMedia('(prefers-reduced-motion: reduce)');
document.querySelectorAll('.travel-card:has(video), .travel-destination:has(video)').forEach(card => {
  const video = card.querySelector('video');
  function stop() {
    video.pause();
    video.classList.remove('playing');
  }
  function play() {
    if (motion.matches || navigator.connection?.saveData) return;
    if (!video.src) video.src = video.dataset.src;
    video.play().then(() => {
      if (!video.paused) video.classList.add('playing');
    }).catch(stop);
  }
  card.addEventListener('pointerenter', play);
  card.addEventListener('focusin', play);
  card.addEventListener('pointerleave', stop);
  card.addEventListener('focusout', stop);
  motion.addEventListener('change', stop);
});

// Map the pointer into image-space offsets. The link and text stay still.
const responsiveImages = matchMedia('(hover: hover) and (pointer: fine) and (prefers-reduced-motion: no-preference)');
document.querySelectorAll('.travel-card, .travel-destination').forEach(card => {
  let frame = 0;
  let x, y;
  function reset() {
    cancelAnimationFrame(frame);
    frame = 0;
    card.style.removeProperty('--pan-x');
    card.style.removeProperty('--pan-y');
  }
  card.addEventListener('pointermove', event => {
    if (!responsiveImages.matches || event.pointerType === 'touch') return;
    x = event.clientX;
    y = event.clientY;
    if (frame) return;
    frame = requestAnimationFrame(() => {
      frame = 0;
      const rect = card.getBoundingClientRect();
      card.style.setProperty('--pan-x', ((x - rect.left) / rect.width - .5) * 14 + 'px');
      card.style.setProperty('--pan-y', ((y - rect.top) / rect.height - .5) * 10 + 'px');
    });
  });
  card.addEventListener('pointerleave', reset);
  responsiveImages.addEventListener('change', reset);
});

// Update visible scenes together, reading layout before writing transforms.
const scenes = document.querySelectorAll('.has-parallax, .is-style-travel-city, .is-style-travel-beach');
const visibleScenes = new Set();
let scrollFrame = 0;
function paintScenes() {
  scrollFrame = 0;
  const positions = Array.from(visibleScenes, scene => {
    const bounds = scene.getBoundingClientRect();
    const hero = scene.classList.contains('travel-hero');
    const distance = hero ? -bounds.top : innerHeight * .65 - bounds.top;
    const range = Math.min(innerWidth * .14, 200);
    return [scene, Math.max(-range, Math.min(range, distance * .30))];
  });
  for (const [scene, offset] of positions) {
    scene.style.setProperty('--travel-depth', offset.toFixed(1) + 'px');
  }
}
function queueScenes() {
  if (!motion.matches && !scrollFrame && visibleScenes.size) scrollFrame = requestAnimationFrame(paintScenes);
}
if (scenes.length && 'IntersectionObserver' in window) {
  const observer = new IntersectionObserver(entries => {
    for (const entry of entries) {
      if (entry.isIntersecting) visibleScenes.add(entry.target);
      else visibleScenes.delete(entry.target);
    }
    queueScenes();
  }, {rootMargin:'200px'});
  scenes.forEach(scene => observer.observe(scene));
  addEventListener('scroll', queueScenes, {passive:true});
  addEventListener('resize', queueScenes);
  motion.addEventListener('change', queueScenes);
}
