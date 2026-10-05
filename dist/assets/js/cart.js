/**
 * Luxury Club — Cart, Wishlist & Drawer (cart.js)
 */

import { animateFlyToBag } from './motion.js';

export function initCart() {
  const backdrop = document.querySelector('.drawer-backdrop');
  const drawer = document.querySelector('.cart-drawer');
  const closeBtn = document.querySelector('.drawer-close');
  const openBtns = document.querySelectorAll('[data-drawer-open]');
  const tabBtns = document.querySelectorAll('.drawer-tab-btn');

  // Open Drawer
  openBtns.forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const tab = btn.getAttribute('data-drawer-open') || 'bag';
      openCartDrawer(tab);
    });
  });

  // Close Drawer
  if (closeBtn) closeBtn.addEventListener('click', closeCartDrawer);
  if (backdrop) backdrop.addEventListener('click', closeCartDrawer);

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && drawer && drawer.classList.contains('open')) {
      closeCartDrawer();
    }
  });

  // Tab switching
  tabBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
      const tab = btn.getAttribute('data-tab');
      switchDrawerTab(tab);
    });
  });

  // Global delegate for Quick Add to Bag
  document.addEventListener('click', async (e) => {
    const addBtn = e.target.closest('[data-add-to-bag]');
    if (addBtn) {
      e.preventDefault();
      const productId = addBtn.getAttribute('data-add-to-bag');
      const qty = parseInt(addBtn.getAttribute('data-qty') || '1', 10);
      const name = addBtn.getAttribute('data-name') || 'Product';

      const mediaImg = addBtn.closest('.product-card')?.querySelector('.product-card-img') ||
                       document.querySelector('.pdp-main-img');
      if (mediaImg) {
        animateFlyToBag(mediaImg);
      }

      await addToCart(productId, qty, name);
    }

    // Wishlist toggle delegate
    const wishBtn = e.target.closest('[data-wishlist-toggle]');
    if (wishBtn) {
      e.preventDefault();
      const productId = wishBtn.getAttribute('data-wishlist-toggle');
      await toggleWishlist(productId, wishBtn);
    }
  });
}

export function openCartDrawer(tab = 'bag') {
  const backdrop = document.querySelector('.drawer-backdrop');
  const drawer = document.querySelector('.cart-drawer');
  if (backdrop && drawer) {
    backdrop.classList.add('open');
    drawer.classList.add('open');
    document.body.style.overflow = 'hidden';
    switchDrawerTab(tab);
    refreshCartDrawer();
  }
}

export function closeCartDrawer() {
  const backdrop = document.querySelector('.drawer-backdrop');
  const drawer = document.querySelector('.cart-drawer');
  if (backdrop && drawer) {
    backdrop.classList.remove('open');
    drawer.classList.remove('open');
    document.body.style.overflow = '';
  }
}

function switchDrawerTab(tab) {
  const tabBtns = document.querySelectorAll('.drawer-tab-btn');
  const bagContent = document.querySelector('.drawer-bag-content');
  const wishlistContent = document.querySelector('.drawer-wishlist-content');

  tabBtns.forEach((btn) => {
    if (btn.getAttribute('data-tab') === tab) {
      btn.classList.add('active');
    } else {
      btn.classList.remove('active');
    }
  });

  if (tab === 'wishlist') {
    if (bagContent) bagContent.style.display = 'none';
    if (wishlistContent) wishlistContent.style.display = 'flex';
  } else {
    if (bagContent) bagContent.style.display = 'flex';
    if (wishlistContent) wishlistContent.style.display = 'none';
  }
}

export async function addToCart(productId, qty = 1, name = 'Product') {
  try {
    const res = await fetch('/api/cart/add', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': getCsrfToken(),
      },
      body: JSON.stringify({ product_id: productId, qty: qty }),
    });

    if (res.ok) {
      const data = await res.json();
      if (data.success) {
        updateCartState(data);
        showToast(`✓ ${name} added to your bag`, () => openCartDrawer('bag'));
        return;
      }
    }
    throw new Error('Fallback to local storage cart');
  } catch (err) {
    // Client-side fallback for static Netlify deployment
    await addLocalCart(productId, qty, name);
  }
}

export async function updateCartItem(productId, qty) {
  try {
    const res = await fetch('/api/cart/update', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': getCsrfToken(),
      },
      body: JSON.stringify({ product_id: productId, qty: qty }),
    });
    if (res.ok) {
      const data = await res.json();
      if (data.success) {
        updateCartState(data);
        return;
      }
    }
    throw new Error('Fallback to local storage update');
  } catch (err) {
    updateLocalCartQty(productId, qty);
  }
}

export async function removeCartItem(productId) {
  try {
    const res = await fetch('/api/cart/remove', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': getCsrfToken(),
      },
      body: JSON.stringify({ product_id: productId }),
    });
    if (res.ok) {
      const data = await res.json();
      if (data.success) {
        updateCartState(data);
        return;
      }
    }
    throw new Error('Fallback to local storage removal');
  } catch (err) {
    removeLocalCartItem(productId);
  }
}

export async function refreshCartDrawer() {
  try {
    const res = await fetch('/api/cart');
    if (res.ok) {
      const data = await res.json();
      if (data.success) {
        updateCartState(data);
        return;
      }
    }
    throw new Error('Fallback to local storage cart render');
  } catch (err) {
    renderLocalCartState();
  }
}

export async function toggleWishlist(productId, btnEl) {
  try {
    const res = await fetch('/api/wishlist/toggle', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': getCsrfToken(),
      },
      body: JSON.stringify({ product_id: productId }),
    });
    if (res.ok) {
      const data = await res.json();
      if (data.success) {
        const isAdded = data.is_added;
        if (btnEl) {
          btnEl.classList.toggle('active', isAdded);
        }
        document.querySelectorAll('.wishlist-badge-count').forEach((badge) => {
          badge.textContent = data.count;
          badge.style.display = data.count > 0 ? 'flex' : 'none';
        });
        showToast(isAdded ? '✓ Saved to your wishlist' : 'Removed from your wishlist');
        return;
      }
    }
    throw new Error('Fallback to local wishlist');
  } catch (err) {
    toggleLocalWishlist(productId, btnEl);
  }
}

// -------------------------------------------------------------
// Netlify / Static Jamstack LocalStorage Storage Engine
// -------------------------------------------------------------

function getLocalCart() {
  try {
    return JSON.parse(localStorage.getItem('luxury_cart') || '[]');
  } catch {
    return [];
  }
}

function saveLocalCart(items) {
  localStorage.setItem('luxury_cart', JSON.stringify(items));
}

async function getProductCatalog() {
  try {
    const res = await fetch('/api/products.json');
    const data = await res.json();
    return data.products || [];
  } catch {
    return [];
  }
}

async function addLocalCart(productId, qty, name) {
  let cart = getLocalCart();
  const catalog = await getProductCatalog();
  const prod = catalog.find((p) => String(p.id) === String(productId)) || {
    id: productId,
    name: name,
    price: 1499,
    size_label: '100 ml',
    tint: '#E3E7F3',
    image: 'blue-orchid.png',
    category_name: 'Fragrance'
  };

  const existing = cart.find((item) => String(item.id) === String(productId));
  if (existing) {
    existing.qty += qty;
    existing.line_total = existing.qty * existing.price;
  } else {
    cart.push({
      id: prod.id,
      name: prod.name,
      price: parseInt(prod.price, 10),
      size_label: prod.size_label || '100 ml',
      tint: prod.tint || '#ECE6DA',
      image: prod.image,
      category_name: prod.category_name || 'Fragrance',
      qty: qty,
      line_total: parseInt(prod.price, 10) * qty
    });
  }

  saveLocalCart(cart);
  renderLocalCartState();
  showToast(`✓ ${prod.name} added to your bag`, () => openCartDrawer('bag'));
}

function updateLocalCartQty(productId, qty) {
  let cart = getLocalCart();
  if (qty <= 0) {
    cart = cart.filter((item) => String(item.id) !== String(productId));
  } else {
    const item = cart.find((i) => String(i.id) === String(productId));
    if (item) {
      item.qty = qty;
      item.line_total = item.qty * item.price;
    }
  }
  saveLocalCart(cart);
  renderLocalCartState();
}

function removeLocalCartItem(productId) {
  let cart = getLocalCart();
  cart = cart.filter((item) => String(item.id) !== String(productId));
  saveLocalCart(cart);
  renderLocalCartState();
}

function renderLocalCartState() {
  const cart = getLocalCart();
  let count = 0;
  let subtotal = 0;

  cart.forEach((item) => {
    count += item.qty;
    subtotal += item.line_total;
  });

  const threshold = 999;
  const shippingFee = subtotal >= threshold || subtotal === 0 ? 0 : 99;
  const total = subtotal + shippingFee;
  const amountNeeded = Math.max(0, threshold - subtotal);
  const progressPercent = Math.min(100, Math.round((subtotal / threshold) * 100));

  updateCartState({
    success: true,
    items: cart,
    totals: {
      count: count,
      subtotal: subtotal,
      shipping: shippingFee,
      total: total,
      free_shipping_unlocked: subtotal >= threshold && subtotal > 0,
      amount_needed: amountNeeded,
      progress_percent: progressPercent
    }
  });
}

function toggleLocalWishlist(productId, btnEl) {
  let list = [];
  try {
    list = JSON.parse(localStorage.getItem('luxury_wishlist') || '[]');
  } catch {}

  const idx = list.indexOf(String(productId));
  let isAdded = false;

  if (idx > -1) {
    list.splice(idx, 1);
    isAdded = false;
  } else {
    list.push(String(productId));
    isAdded = true;
  }

  localStorage.setItem('luxury_wishlist', JSON.stringify(list));

  if (btnEl) {
    btnEl.classList.toggle('active', isAdded);
  }

  document.querySelectorAll('.wishlist-badge-count').forEach((badge) => {
    badge.textContent = list.length;
    badge.style.display = list.length > 0 ? 'flex' : 'none';
  });

  showToast(isAdded ? '✓ Saved to your wishlist' : 'Removed from your wishlist');
}

function updateCartState(data) {
  const count = data.totals.count;
  const subtotal = data.totals.subtotal;
  const total = data.totals.total;

  // Header badges
  document.querySelectorAll('.cart-badge-count').forEach((b) => {
    b.textContent = count;
    b.style.display = count > 0 ? 'flex' : 'none';
  });
  document.querySelectorAll('.bag-pill-text').forEach((p) => {
    p.textContent = `BAG · ${count}`;
  });

  // Shipping progress bar
  const shippingMsg = document.querySelector('.shipping-bar-text');
  const shippingFill = document.querySelector('.shipping-progress-fill');
  if (shippingMsg && shippingFill) {
    if (data.totals.free_shipping_unlocked) {
      shippingMsg.innerHTML = '✦ You\'ve unlocked <strong>Complimentary Express Shipping</strong>!';
    } else {
      shippingMsg.innerHTML = `Add <strong>₹${data.totals.amount_needed}</strong> more for free shipping`;
    }
    shippingFill.style.width = `${data.totals.progress_percent}%`;
  }

  // Subtotal & Total
  document.querySelectorAll('.drawer-subtotal-val').forEach((el) => {
    el.textContent = `₹${subtotal.toLocaleString('en-IN')}`;
  });
  document.querySelectorAll('.drawer-total-val').forEach((el) => {
    el.textContent = `₹${total.toLocaleString('en-IN')}`;
  });

  // Render items in drawer
  const itemsContainer = document.querySelector('.drawer-items-list');
  const emptyState = document.querySelector('.drawer-empty-state');
  const footer = document.querySelector('.drawer-footer');

  if (itemsContainer) {
    if (data.items.length === 0) {
      itemsContainer.innerHTML = '';
      if (emptyState) emptyState.style.display = 'block';
      if (footer) footer.style.display = 'none';
    } else {
      if (emptyState) emptyState.style.display = 'none';
      if (footer) footer.style.display = 'block';

      itemsContainer.innerHTML = data.items
        .map(
          (item) => `
        <div class="cart-item-row" data-id="${item.id}">
          <div class="cart-item-thumb" style="background-color: ${item.tint}">
            <img src="/assets/img/products/${item.image}" alt="${item.name}">
          </div>
          <div class="cart-item-info">
            <h4>${item.name}</h4>
            <div class="cart-cat-size">${item.category_name} · ${item.size_label}</div>
            <div class="cart-qty-stepper">
              <button type="button" class="cart-qty-btn btn-qty-minus" onclick="window.LuxuryCart.updateQty(${item.id}, ${item.qty - 1})">−</button>
              <span class="cart-qty-num">${item.qty}</span>
              <button type="button" class="cart-qty-btn btn-qty-plus" onclick="window.LuxuryCart.updateQty(${item.id}, ${item.qty + 1})">+</button>
            </div>
          </div>
          <div class="cart-item-actions">
            <div class="cart-item-price">₹${item.line_total.toLocaleString('en-IN')}</div>
            <button type="button" class="cart-item-remove" onclick="window.LuxuryCart.removeItem(${item.id})">Remove</button>
          </div>
        </div>
      `
        )
        .join('');
    }
  }
}

// Global LuxuryCart namespace for inline triggers
window.LuxuryCart = {
  updateQty: (id, qty) => updateCartItem(id, qty),
  removeItem: (id) => removeCartItem(id),
  open: (tab) => openCartDrawer(tab),
  close: () => closeCartDrawer(),
};

function getCsrfToken() {
  const meta = document.querySelector('meta[name="csrf-token"]');
  return meta ? meta.getAttribute('content') : '';
}

export function showToast(message, actionCallback = null) {
  let container = document.querySelector('.toast-container');
  if (!container) {
    container = document.createElement('div');
    container.className = 'toast-container';
    document.body.appendChild(container);
  }

  const toast = document.createElement('div');
  toast.className = 'toast';
  toast.innerHTML = `
    <span>${message}</span>
    ${actionCallback ? '<button type="button" class="toast-view-btn">View Bag</button>' : ''}
  `;

  if (actionCallback) {
    toast.querySelector('.toast-view-btn')?.addEventListener('click', actionCallback);
  }

  container.appendChild(toast);
  requestAnimationFrame(() => toast.classList.add('show'));

  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 400);
  }, 3200);
}
