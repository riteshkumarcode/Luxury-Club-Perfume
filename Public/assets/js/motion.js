/**
 * Luxury Club — Global Motion & Animations (motion.js)
 * Uses GSAP 3, ScrollTrigger, Lenis Smooth Scroll
 */

export function initMotion() {
  const isReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // 1. Lenis Smooth Scroll (if available and not reduced motion)
  const LenisClass = window.Lenis || (typeof Lenis !== 'undefined' ? Lenis : null);
  if (!isReducedMotion && LenisClass) {
    const lenis = new LenisClass({
      duration: 1.2,
      easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
      orientation: 'vertical',
      gestureOrientation: 'vertical',
      smoothWheel: true,
      wheelMultiplier: 1,
      smoothTouch: false,
    });

    window.lenis = lenis;

    // Link Lenis to GSAP ScrollTrigger if present, otherwise use standalone RAF
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
      lenis.on('scroll', ScrollTrigger.update);
      gsap.ticker.add((time) => {
        lenis.raf(time * 1000);
      });
      gsap.ticker.lagSmoothing(0);
    } else {
      function raf(time) {
        lenis.raf(time);
        requestAnimationFrame(raf);
      }
      requestAnimationFrame(raf);
    }

    // Smooth scroll for internal anchor links with sticky header offset
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
      anchor.addEventListener('click', (e) => {
        const targetId = anchor.getAttribute('href');
        if (targetId && targetId !== '#' && targetId.length > 1) {
          const targetEl = document.querySelector(targetId);
          if (targetEl) {
            e.preventDefault();
            lenis.scrollTo(targetEl, { offset: -80, duration: 1.2 });
          }
        }
      });
    });
  }

  // 2. Custom Cursor (Desktop only)
  if (!isReducedMotion && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
    initCustomCursor();
  }

  // 3. GSAP ScrollTrigger Reveals
  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined' && !isReducedMotion) {
    gsap.registerPlugin(ScrollTrigger);

    // Reveal elements with [data-reveal]
    const revealEls = document.querySelectorAll('[data-reveal]');
    revealEls.forEach((el) => {
      gsap.fromTo(
        el,
        { opacity: 0, y: 24 },
        {
          opacity: 1,
          y: 0,
          duration: 0.85,
          ease: 'power2.out',
          scrollTrigger: {
            trigger: el,
            start: 'top 88%',
            toggleActions: 'play none none none',
          },
        }
      );
    });

    // Staggered grid reveals
    const staggerGrids = document.querySelectorAll('[data-reveal-grid]');
    staggerGrids.forEach((grid) => {
      const items = grid.children;
      gsap.fromTo(
        items,
        { opacity: 0, y: 30 },
        {
          opacity: 1,
          y: 0,
          duration: 0.7,
          stagger: 0.1,
          ease: 'power2.out',
          scrollTrigger: {
            trigger: grid,
            start: 'top 85%',
            toggleActions: 'play none none none',
          },
        }
      );
    });
  }

  // 4. Magnetic Buttons
  if (!isReducedMotion && window.matchMedia('(hover: hover)').matches) {
    initMagneticButtons();
  }
}

function initCustomCursor() {
  const dot = document.createElement('div');
  dot.className = 'custom-cursor-dot';
  const ring = document.createElement('div');
  ring.className = 'custom-cursor-ring';
  document.body.appendChild(dot);
  document.body.appendChild(ring);

  let mouseX = window.innerWidth / 2;
  let mouseY = window.innerHeight / 2;
  let ringX = mouseX;
  let ringY = mouseY;

  window.addEventListener('mousemove', (e) => {
    mouseX = e.clientX;
    mouseY = e.clientY;
    dot.style.left = `${mouseX}px`;
    dot.style.top = `${mouseY}px`;
  });

  function renderCursor() {
    ringX += (mouseX - ringX) * 0.15;
    ringY += (mouseY - ringY) * 0.15;
    ring.style.left = `${ringX}px`;
    ring.style.top = `${ringY}px`;
    requestAnimationFrame(renderCursor);
  }
  requestAnimationFrame(renderCursor);

  // Hover states on links and interactive elements
  const interactives = document.querySelectorAll('a, button, .product-card, .btn, input, select');
  interactives.forEach((item) => {
    item.addEventListener('mouseenter', () => {
      ring.style.transform = 'translate(-50%, -50%) scale(1.6)';
      ring.style.borderColor = 'var(--gold)';
    });
    item.addEventListener('mouseleave', () => {
      ring.style.transform = 'translate(-50%, -50%) scale(1)';
      ring.style.borderColor = 'rgba(216, 178, 92, 0.45)';
    });
  });
}

function initMagneticButtons() {
  const magnets = document.querySelectorAll('[data-magnetic]');
  magnets.forEach((btn) => {
    btn.addEventListener('mousemove', (e) => {
      const rect = btn.getBoundingClientRect();
      const x = e.clientX - rect.left - rect.width / 2;
      const y = e.clientY - rect.top - rect.height / 2;
      btn.style.transform = `translate(${x * 0.25}px, ${y * 0.25}px)`;
    });
    btn.addEventListener('mouseleave', () => {
      btn.style.transform = 'translate(0px, 0px)';
    });
  });
}

/**
 * Add-to-bag fly animation
 */
export function animateFlyToBag(sourceEl) {
  const bagIcon = document.querySelector('.header-action-btn[data-drawer-open="cart"]') || document.querySelector('.bag-pill-btn');
  if (!bagIcon || !sourceEl) return;

  const sourceRect = sourceEl.getBoundingClientRect();
  const targetRect = bagIcon.getBoundingClientRect();

  const clone = sourceEl.cloneNode(true);
  clone.style.position = 'fixed';
  clone.style.top = `${sourceRect.top}px`;
  clone.style.left = `${sourceRect.left}px`;
  clone.style.width = `${sourceRect.width}px`;
  clone.style.height = `${sourceRect.height}px`;
  clone.style.zIndex = '99999';
  clone.style.pointerEvents = 'none';
  clone.style.borderRadius = '50%';
  clone.style.transition = 'all 0.75s cubic-bezier(0.2, 0.7, 0.2, 1)';
  document.body.appendChild(clone);

  requestAnimationFrame(() => {
    clone.style.top = `${targetRect.top + targetRect.height / 4}px`;
    clone.style.left = `${targetRect.left + targetRect.width / 4}px`;
    clone.style.width = '20px';
    clone.style.height = '20px';
    clone.style.opacity = '0.2';
    clone.style.transform = 'scale(0.2) rotate(45deg)';
  });

  setTimeout(() => {
    clone.remove();
    // Bump bag icon
    bagIcon.classList.add('bump-effect');
    setTimeout(() => bagIcon.classList.remove('bump-effect'), 350);
  }, 760);
}
