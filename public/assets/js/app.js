/**
 * Luxury Club — Main Application Entry (app.js)
 */

import { initMotion } from './motion.js';
import { initHeroSlider } from './slider.js';
import { initCart } from './cart.js';
import { initSearch } from './search.js';
import { initProductPage } from './product.js';
import { initForms } from './forms.js';

document.addEventListener('DOMContentLoaded', () => {
  // 1. Initialize Global Motion & Smooth Scrolling
  initMotion();

  // 2. Initialize Header Scroll Behavior (shrink & auto-hide/reveal)
  initHeader();

  // 3. Initialize Mobile Menu
  initMobileNav();

  // 4. Initialize Hero Slider
  initHeroSlider();

  // 5. Initialize Cart Drawer & Wishlist
  initCart();

  // 6. Initialize Search Overlay
  initSearch();

  // 7. Initialize Product Detail Page
  initProductPage();

  // 8. Initialize Forms (Newsletter, Contact)
  initForms();

  // 9. Initialize Shop Page Client Filtering (if present)
  initShopFiltering();

  // 10. Initialize Interactive Scent Quiz
  initScentQuiz();
});

function initHeader() {
  const header = document.querySelector('.site-header');
  if (!header) return;

  let lastScrollY = window.scrollY;

  window.addEventListener('scroll', () => {
    const currentScrollY = window.scrollY;

    // Shrink header after 40px
    if (currentScrollY > 40) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }

    // Hide on scroll down, show on scroll up (after 120px)
    if (currentScrollY > 120 && currentScrollY > lastScrollY && !header.classList.contains('menu-open')) {
      header.classList.add('header-hidden');
    } else {
      header.classList.remove('header-hidden');
    }

    lastScrollY = currentScrollY;
  }, { passive: true });
}

function initMobileNav() {
  const toggle = document.querySelector('.mobile-nav-toggle');
  const menu = document.querySelector('.mobile-nav-drawer');
  const close = document.querySelector('.mobile-nav-close');
  const header = document.querySelector('.site-header');
  if (!toggle || !menu) return;

  function openMenu() {
    menu.classList.add('open');
    menu.setAttribute('aria-hidden', 'false');
    toggle.classList.add('active');
    toggle.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
    if (header) header.classList.add('menu-open');
  }

  function closeMenu() {
    menu.classList.remove('open');
    menu.setAttribute('aria-hidden', 'true');
    toggle.classList.remove('active');
    toggle.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
    if (header) header.classList.remove('menu-open');
  }

  toggle.addEventListener('click', (e) => {
    e.stopPropagation();
    if (menu.classList.contains('open')) {
      closeMenu();
    } else {
      openMenu();
    }
  });

  if (close) {
    close.addEventListener('click', (e) => {
      e.stopPropagation();
      closeMenu();
    });
  }

  // Close when clicking any nav link inside drawer
  menu.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      closeMenu();
    });
  });

  // Close on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && menu.classList.contains('open')) {
      closeMenu();
    }
  });
}

function initShopFiltering() {
  const chips = document.querySelectorAll('.category-chip-btn');
  const sortSelect = document.querySelector('#shop-sort-select');
  const gridDensityBtns = document.querySelectorAll('[data-density]');
  const shopGrid = document.querySelector('.shop-grid');

  // Density Toggle
  gridDensityBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
      const density = btn.getAttribute('data-density');
      gridDensityBtns.forEach((b) => b.classList.remove('active'));
      btn.classList.add('active');

      if (shopGrid) {
        if (density === '3') {
          shopGrid.classList.add('grid-3-col');
        } else {
          shopGrid.classList.remove('grid-3-col');
        }
      }
    });
  });

  // Client Filter chips (Home page or Shop page)
  chips.forEach((chip) => {
    chip.addEventListener('click', () => {
      const cat = chip.getAttribute('data-filter-category');
      chips.forEach((c) => c.classList.remove('active'));
      chip.classList.add('active');

      const items = document.querySelectorAll('.collection-filterable-item');
      if (items.length > 0) {
        items.forEach((item) => {
          const itemCat = item.getAttribute('data-category');
          if (cat === 'all' || itemCat === cat) {
            item.style.display = 'flex';
          } else {
            item.style.display = 'none';
          }
        });
      }
    });
  });

  // Shop sort redirect / reload
  if (sortSelect) {
    sortSelect.addEventListener('change', () => {
      const url = new URL(window.location.href);
      url.searchParams.set('sort', sortSelect.value);
      window.location.href = url.toString();
    });
  }
}

function initScentQuiz() {
  const quizSection = document.querySelector('.scent-quiz-section');
  if (!quizSection) return;

  const step1 = quizSection.querySelector('[data-step="1"]');
  const step2 = quizSection.querySelector('[data-step="2"]');
  const step3 = quizSection.querySelector('[data-step="3"]');
  const progressFill = quizSection.querySelector('.quiz-progress-fill');
  const resultContent = quizSection.querySelector('#quiz-result-content');

  let selectedVibe = '';
  let selectedFormat = '';

  const recommendations = {
    royal: {
      id: 3,
      name: 'Royal Oud',
      category: 'Eau de Parfum',
      price: '₹1,699',
      priceNum: 1699,
      slug: 'royal-oud',
      image: '/assets/img/products/royal-oud.png',
      tint: '#ECE6DA',
      matchText: 'Based on your preference for regal elegance and smoky woods, Royal Oud offers 16+ hour majesty with Cambodian Agarwood, Saffron & Taif Rose.'
    },
    romantic: {
      id: 2,
      name: 'Red Crystal',
      category: 'Eau de Parfum',
      price: '₹1,499',
      priceNum: 1499,
      slug: 'red-crystal',
      image: '/assets/img/products/red-crystal.png',
      tint: '#F4E0E0',
      matchText: 'For a romantic, irresistible trail, Red Crystal blends radiant French Damask rose petals, sparkling pomegranate, and seductive white musk.'
    },
    fresh: {
      id: 1,
      name: 'Blue Orchid',
      category: 'Eau de Parfum',
      price: '₹1,299',
      priceNum: 1299,
      slug: 'blue-orchid',
      image: '/assets/img/products/blue-orchid.png',
      tint: '#E4EDF7',
      matchText: 'Your craving for fresh vibrancy finds its pinnacle in Blue Orchid—an invigorating burst of crisp Italian bergamot, blue orchid petals, and clean cedar.'
    },
    calm: {
      id: 6,
      name: 'Morning Jasmine Candle',
      category: 'Home Fragrance',
      price: '₹899',
      priceNum: 899,
      slug: 'morning-jasmine',
      image: '/assets/img/products/morning-jasmine.png',
      tint: '#F7EED6',
      matchText: 'For serene tranquility, our hand-poured Morning Jasmine soy candle fills your sanctuary with comforting botanical bliss for over 25 burning hours.'
    },
    attar: {
      id: 4,
      name: 'Royal Oudh Attar',
      category: 'Pure Perfume Oil',
      price: '₹899',
      priceNum: 899,
      slug: 'royal-oudh-attar',
      image: '/assets/img/products/royal-oudh.png',
      tint: '#EFE9DF',
      matchText: '100% alcohol-free pure concentrated oil in a precision roll-on crystal vial. Warm, deeply intimate, and enduring 14+ hours on skin.'
    }
  };

  quizSection.querySelectorAll('[data-step="1"] .quiz-opt-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      selectedVibe = btn.getAttribute('data-choice');
      if (step1 && step2) {
        step1.style.display = 'none';
        step2.style.display = 'block';
        if (progressFill) progressFill.style.width = '66.66%';
      }
    });
  });

  quizSection.querySelectorAll('[data-step="2"] .quiz-opt-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      selectedFormat = btn.getAttribute('data-choice');
      if (step2 && step3) {
        step2.style.display = 'none';
        step3.style.display = 'block';
        if (progressFill) progressFill.style.width = '100%';
        renderMatch();
      }
    });
  });

  function renderMatch() {
    let key = selectedVibe || 'royal';
    if (selectedFormat === 'attar') key = 'attar';
    else if (selectedFormat === 'lifestyle' && (selectedVibe === 'calm' || selectedVibe === 'fresh')) key = 'calm';

    const match = recommendations[key] || recommendations.royal;

    if (resultContent) {
      resultContent.innerHTML = `
        <div class="quiz-matched-card">
          <div style="background-color: ${match.tint}; border-radius: 16px; padding: 12px; display: flex; align-items: center; justify-content: center;">
            <img src="${match.image}" alt="${match.name}" class="quiz-matched-img">
          </div>
          <div style="flex: 1;">
            <span class="eyebrow" style="color: var(--gold-deep);">${match.category} · 98% MATCH</span>
            <h3 style="font-family: var(--font-serif); font-size: 26px; margin: 4px 0 10px;">${match.name}</h3>
            <p style="font-size: 14px; line-height: 1.6; color: var(--muted); margin-bottom: 16px;">${match.matchText}</p>
            <div style="display: flex; gap: 14px; align-items: center; flex-wrap: wrap;">
              <span style="font-family: var(--font-serif); font-size: 22px; font-weight: 700; color: var(--ink);">${match.price}</span>
              <button type="button" class="btn btn-primary" data-add-to-bag="${match.id}" data-name="${match.name}" data-price="${match.priceNum}">
                Add Matched Flacon to Bag
              </button>
              <a href="/product/${match.slug}" class="btn btn-outline">
                View Notes
              </a>
            </div>
          </div>
        </div>
        <div style="text-align: center; margin-top: 20px;">
          <button type="button" id="quiz-restart-btn" style="background: none; border: none; font-size: 13px; color: var(--muted); text-decoration: underline; cursor: pointer;">
            ↻ Retake Olfactory Quiz
          </button>
        </div>
      `;

      const restartBtn = resultContent.querySelector('#quiz-restart-btn');
      if (restartBtn) {
        restartBtn.addEventListener('click', () => {
          if (step3 && step1) {
            step3.style.display = 'none';
            step1.style.display = 'block';
            if (progressFill) progressFill.style.width = '33.33%';
          }
        });
      }
    }
  }
}

