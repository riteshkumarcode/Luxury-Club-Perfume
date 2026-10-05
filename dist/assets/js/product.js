/**
 * Luxury Club — Product Detail Page Logic (product.js)
 */

import { addToCart, showToast } from './cart.js';

export function initProductPage() {
  const wrap = document.querySelector('.pdp-main-image-wrap');
  const img = document.querySelector('.pdp-main-img');
  const qtyMinus = document.querySelector('.pdp-qty-minus');
  const qtyPlus = document.querySelector('.pdp-qty-plus');
  const qtyNum = document.querySelector('.pdp-qty-val');
  const btnAdd = document.querySelector('.pdp-btn-add');
  const btnBuyNow = document.querySelector('.pdp-btn-buynow');
  const pincodeForm = document.querySelector('.pincode-form');
  const pincodeInput = document.querySelector('.pincode-input');
  const pincodeResult = document.querySelector('.pincode-result');
  const copyBtn = document.querySelector('[data-copy-link]');
  const accordions = document.querySelectorAll('.accordion-item');
  const mobileBar = document.querySelector('.sticky-mobile-bar');

  // 1. Hover Zoom on Image (1.9x)
  if (wrap && img && window.matchMedia('(hover: hover)').matches) {
    wrap.addEventListener('mousemove', (e) => {
      const rect = wrap.getBoundingClientRect();
      const x = ((e.clientX - rect.left) / rect.width) * 100;
      const y = ((e.clientY - rect.top) / rect.height) * 100;
      img.style.transformOrigin = `${x}% ${y}%`;
      img.style.transform = 'scale(1.9)';
    });

    wrap.addEventListener('mouseleave', () => {
      img.style.transformOrigin = 'center center';
      img.style.transform = 'scale(1)';
    });
  }

  // 2. Quantity Stepper & Price Calculation
  let currentQty = 1;
  const unitPrice = parseInt(btnAdd?.getAttribute('data-price') || '0', 10);
  const productId = parseInt(btnAdd?.getAttribute('data-id') || '0', 10);
  const productName = btnAdd?.getAttribute('data-name') || 'Fragrance';

  function updateQtyDisplay() {
    if (qtyNum) qtyNum.textContent = currentQty;
    if (btnAdd) {
      const total = unitPrice * currentQty;
      btnAdd.textContent = `ADD TO BAG · ₹${total.toLocaleString('en-IN')}`;
    }
  }

  if (qtyMinus) {
    qtyMinus.addEventListener('click', () => {
      if (currentQty > 1) {
        currentQty--;
        updateQtyDisplay();
      }
    });
  }

  if (qtyPlus) {
    qtyPlus.addEventListener('click', () => {
      const maxStock = parseInt(btnAdd?.getAttribute('data-stock') || '99', 10);
      if (currentQty < maxStock) {
        currentQty++;
        updateQtyDisplay();
      }
    });
  }

  // 3. Add to Bag & Buy Now
  if (btnAdd) {
    btnAdd.addEventListener('click', async (e) => {
      e.preventDefault();
      await addToCart(productId, currentQty, productName);
    });
  }

  if (btnBuyNow) {
    btnBuyNow.addEventListener('click', async (e) => {
      e.preventDefault();
      await addToCart(productId, currentQty, productName);
      window.location.href = '/checkout';
    });
  }

  // 4. Pincode Checker
  if (pincodeForm && pincodeInput && pincodeResult) {
    pincodeForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const pin = pincodeInput.value.trim();

      if (!/^[1-9]\d{5}$/.test(pin)) {
        pincodeResult.innerHTML = '<span style="color: var(--error);">Please enter a valid 6-digit Indian PIN code.</span>';
        return;
      }

      pincodeResult.innerHTML = '<span style="color: var(--muted);">Checking serviceability...</span>';

      try {
        const res = await fetch('/api/pincode', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
          },
          body: JSON.stringify({ pincode: pin }),
        });
        const data = await res.json();
        if (data.success) {
          pincodeResult.innerHTML = `<span style="color: var(--success); font-weight: 600;">✓ Good news — we deliver to ${pin}.</span><br><span style="font-size: 12px; color: var(--muted);">${data.estimate}</span>`;
        } else {
          pincodeResult.innerHTML = `<span style="color: var(--error);">${data.message}</span>`;
        }
      } catch (err) {
        pincodeResult.innerHTML = '<span style="color: var(--error);">Could not verify PIN code. Please try again.</span>';
      }
    });
  }

  // 5. Accordion Single-open
  accordions.forEach((acc) => {
    const header = acc.querySelector('.accordion-header');
    if (header) {
      header.addEventListener('click', () => {
        const isActive = acc.classList.contains('active');
        accordions.forEach((a) => a.classList.remove('active'));
        if (!isActive) {
          acc.classList.add('active');
        }
      });
    }
  });

  // 6. Copy Link
  if (copyBtn) {
    copyBtn.addEventListener('click', () => {
      navigator.clipboard.writeText(window.location.href).then(() => {
        showToast('✓ Link copied to clipboard');
      });
    });
  }

  // 7. Sticky Mobile Add-to-Cart Bar
  if (btnAdd && mobileBar) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) {
            mobileBar.classList.add('visible');
          } else {
            mobileBar.classList.remove('visible');
          }
        });
      },
      { threshold: 0.1 }
    );
    observer.observe(btnAdd);
  }

  // 8. Track Recently Viewed in localStorage
  if (productId) {
    trackRecentlyViewed(productId);
    loadRecentlyViewed(productId);
  }
}

function trackRecentlyViewed(id) {
  try {
    let list = JSON.parse(localStorage.getItem('luxury_recently_viewed') || '[]');
    list = list.filter((itemId) => itemId !== id);
    list.unshift(id);
    if (list.length > 8) list.pop();
    localStorage.setItem('luxury_recently_viewed', JSON.stringify(list));
  } catch (e) {}
}

async function loadRecentlyViewed(currentId) {
  const container = document.querySelector('.recently-viewed-grid');
  const section = document.querySelector('.recently-viewed-section');
  if (!container || !section) return;

  try {
    let list = JSON.parse(localStorage.getItem('luxury_recently_viewed') || '[]');
    list = list.filter((id) => id !== currentId);
    if (list.length === 0) {
      section.style.display = 'none';
      return;
    }

    const res = await fetch(`/api/products?ids=${list.slice(0, 4).join(',')}`);
    const data = await res.json();
    if (data.success && data.products.length > 0) {
      section.style.display = 'block';
      container.innerHTML = data.products
        .map(
          (p) => `
        <div class="product-card">
          <div class="product-card-media" style="background-color: ${p.tint}">
            <img src="/assets/img/products/${p.image}" alt="${p.name}" class="product-card-img" loading="lazy">
            <div class="product-card-quickadd">
              <button type="button" class="btn-card-add" data-add-to-bag="${p.id}" data-name="${p.name}" data-price="${p.price}">
                ADD TO BAG · ₹${parseInt(p.price, 10).toLocaleString('en-IN')}
              </button>
            </div>
          </div>
          <div class="product-card-info">
            <div class="product-card-eyebrow">${p.category_name}</div>
            <h3 class="product-card-title"><a href="/product/${p.slug}">${p.name}</a></h3>
            <div class="product-card-meta">
              <span class="product-card-price">₹${parseInt(p.price, 10).toLocaleString('en-IN')}</span>
              <span>· ${p.size_label}</span>
            </div>
          </div>
        </div>
      `
        )
        .join('');
    }
  } catch (e) {}
}
