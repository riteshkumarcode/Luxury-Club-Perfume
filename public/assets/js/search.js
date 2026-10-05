/**
 * Luxury Club — Search Overlay (search.js)
 */

export function initSearch() {
  const overlay = document.querySelector('.search-overlay');
  const openBtns = document.querySelectorAll('[data-search-open]');
  const closeBtn = document.querySelector('.search-overlay-close');
  const searchInput = document.querySelector('.search-main-input');
  const resultsGrid = document.querySelector('.search-results-grid');
  const popularSection = document.querySelector('.search-popular-section');

  let debounceTimer = null;

  // Open Search
  openBtns.forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      openSearch();
    });
  });

  // Shortcut key '/' to open, 'Esc' to close
  document.addEventListener('keydown', (e) => {
    if (e.key === '/' && !isInputFocused() && !overlay.classList.contains('open')) {
      e.preventDefault();
      openSearch();
    } else if (e.key === 'Escape' && overlay && overlay.classList.contains('open')) {
      closeSearch();
    }
  });

  if (closeBtn) {
    closeBtn.addEventListener('click', closeSearch);
  }

  function openSearch() {
    if (!overlay) return;
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
    setTimeout(() => searchInput?.focus(), 100);
  }

  function closeSearch() {
    if (!overlay) return;
    overlay.classList.remove('open');
    document.body.style.overflow = '';
  }

  function isInputFocused() {
    const active = document.activeElement;
    return active && (active.tagName === 'INPUT' || active.tagName === 'TEXTAREA' || active.isContentEditable);
  }

  // Live Debounced Search (150ms)
  if (searchInput && resultsGrid) {
    searchInput.addEventListener('input', (e) => {
      const q = e.target.value.trim();
      clearTimeout(debounceTimer);

      if (!q) {
        resultsGrid.innerHTML = '';
        if (popularSection) popularSection.style.display = 'block';
        return;
      }

      debounceTimer = setTimeout(async () => {
        try {
          const res = await fetch(`/api/search?q=${encodeURIComponent(q)}`);
          if (res.ok) {
            const data = await res.json();
            if (data.success) {
              renderResults(data.results, q);
              return;
            }
          }
          throw new Error('Fallback to static JSON search');
        } catch (err) {
          // Fallback for static Netlify host via pre-rendered /api/search.json
          try {
            const staticRes = await fetch('/api/search.json');
            const staticData = await staticRes.json();
            const lowerQ = q.toLowerCase();
            const filtered = (staticData.results || []).filter((item) => 
              (item.name && item.name.toLowerCase().includes(lowerQ)) ||
              (item.category_name && item.category_name.toLowerCase().includes(lowerQ)) ||
              (item.notes && item.notes.toLowerCase().includes(lowerQ)) ||
              (item.short_description && item.short_description.toLowerCase().includes(lowerQ))
            );
            renderResults(filtered, q);
          } catch (staticErr) {
            console.error('Search query error:', staticErr);
          }
        }
      }, 150);
    });
  }

  function renderResults(results, query) {
    if (popularSection) popularSection.style.display = 'none';

    if (results.length === 0) {
      resultsGrid.innerHTML = `
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px 0; color: var(--muted-dark);">
          <p style="font-size: 18px; font-family: var(--font-serif); color: var(--cream);">No fragrances found for "${escapeHtml(query)}"</p>
          <p style="font-size: 13px; margin-top: 8px;">Try searching for notes like "Oud", "Orchid", "Rose" or "Attar".</p>
        </div>
      `;
      return;
    }

    resultsGrid.innerHTML = results
      .map(
        (item) => `
      <a href="/product/${item.slug}" class="search-result-item">
        <div class="search-thumb" style="background-color: ${item.tint}">
          <img src="/assets/img/products/${item.image}" alt="${item.name}">
        </div>
        <div class="search-info">
          <div class="search-cat">${item.category_name}</div>
          <h5>${item.name}</h5>
          <div class="search-price">₹${parseInt(item.price, 10).toLocaleString('en-IN')}</div>
        </div>
      </a>
    `
      )
      .join('');
  }

  function escapeHtml(str) {
    return str.replace(/[&<>"']/g, (m) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[m]);
  }
}
