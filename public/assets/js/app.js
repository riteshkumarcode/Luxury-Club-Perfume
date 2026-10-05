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
  if (!toggle || !menu) return;

  toggle.addEventListener('click', () => {
    menu.classList.toggle('open');
    document.body.style.overflow = menu.classList.contains('open') ? 'hidden' : '';
  });

  if (close) {
    close.addEventListener('click', () => {
      menu.classList.remove('open');
      document.body.style.overflow = '';
    });
  }
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
