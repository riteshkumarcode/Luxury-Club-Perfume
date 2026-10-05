/**
 * Luxury Club — Hero Slider (slider.js)
 * Swiper 11 + dynamic background crossfade + GSAP slide transitions
 */

export function initHeroSlider() {
  const sliderEl = document.querySelector('.hero-swiper');
  if (!sliderEl || typeof Swiper === 'undefined') return;

  const sectionEl = document.querySelector('.hero-slider-section');
  const outlineWordEl = document.querySelector('.hero-outline-word');
  const tabBtns = document.querySelectorAll('.hero-tab-btn');
  const counterCurrent = document.querySelector('.hero-counter-current');

  const swiper = new Swiper('.hero-swiper', {
    loop: true,
    speed: 1000,
    effect: 'fade',
    fadeEffect: {
      crossFade: true,
    },
    autoplay: {
      delay: 6000,
      disableOnInteraction: false,
      pauseOnMouseEnter: true,
    },
    navigation: {
      nextEl: '.hero-btn-next',
      prevEl: '.hero-btn-prev',
    },
    keyboard: {
      enabled: true,
    },
    on: {
      init: function () {
        updateSlideDetails(this);
      },
      slideChange: function () {
        updateSlideDetails(this);
      },
    },
  });

  function updateSlideDetails(instance) {
    const activeSlide = instance.slides[instance.activeIndex];
    if (!activeSlide) return;

    const bgColor = activeSlide.getAttribute('data-bg-color') || '#0E1633';
    const outline = activeSlide.getAttribute('data-outline') || 'Luxury';
    const realIndex = instance.realIndex;

    // 1. Cross-fade section background
    if (sectionEl) {
      sectionEl.style.backgroundColor = bgColor;
    }

    // 2. Animate outline word
    if (outlineWordEl) {
      outlineWordEl.textContent = outline;
      if (typeof gsap !== 'undefined') {
        gsap.fromTo(
          outlineWordEl,
          { opacity: 0, x: -60 },
          { opacity: 1, x: 0, duration: 1.1, ease: 'power3.out' }
        );
      }
    }

    // 3. Update tabs
    tabBtns.forEach((btn, idx) => {
      if (idx === realIndex) {
        btn.classList.add('active');
      } else {
        btn.classList.remove('active');
      }
    });

    // 4. Update counter
    if (counterCurrent) {
      counterCurrent.textContent = String(realIndex + 1).padStart(2, '0');
    }

    // 5. GSAP text in animation for active slide
    if (typeof gsap !== 'undefined') {
      const eyebrow = activeSlide.querySelector('.eyebrow');
      const title = activeSlide.querySelector('.hero-title');
      const subline = activeSlide.querySelector('.hero-subline');
      const ctas = activeSlide.querySelector('.hero-ctas');

      const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
      if (eyebrow) tl.fromTo(eyebrow, { opacity: 0, y: 15 }, { opacity: 1, y: 0, duration: 0.5 }, 0.1);
      if (title) tl.fromTo(title, { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: 0.7 }, 0.2);
      if (subline) tl.fromTo(subline, { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.6 }, 0.35);
      if (ctas) tl.fromTo(ctas, { opacity: 0, y: 15 }, { opacity: 1, y: 0, duration: 0.5 }, 0.45);
    }
  }

  // Click on tabs to go to slide
  tabBtns.forEach((btn, index) => {
    btn.addEventListener('click', () => {
      swiper.slideToLoop(index);
    });
  });

  // Pointer parallax on active arch panel (desktop only)
  if (window.matchMedia('(hover: hover) and (pointer: fine)').matches && sectionEl) {
    sectionEl.addEventListener('mousemove', (e) => {
      const activeArch = document.querySelector('.swiper-slide-active .hero-arch-panel');
      const activeImg = document.querySelector('.swiper-slide-active .hero-product-img');
      if (!activeArch) return;

      const rect = sectionEl.getBoundingClientRect();
      const xPercent = (e.clientX - rect.left) / rect.width - 0.5;
      const yPercent = (e.clientY - rect.top) / rect.height - 0.5;

      activeArch.style.transform = `rotateY(${xPercent * 10}deg) rotateX(${-yPercent * 10}deg)`;
      if (activeImg) {
        activeImg.style.transform = `translateX(${xPercent * 16}px) translateY(${yPercent * 16}px)`;
      }
    });

    sectionEl.addEventListener('mouseleave', () => {
      const activeArch = document.querySelector('.swiper-slide-active .hero-arch-panel');
      const activeImg = document.querySelector('.swiper-slide-active .hero-product-img');
      if (activeArch) activeArch.style.transform = 'rotateY(0deg) rotateX(0deg)';
      if (activeImg) activeImg.style.transform = 'none';
    });
  }
}
