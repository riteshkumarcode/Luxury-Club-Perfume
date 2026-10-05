/**
 * Luxury Club — Form Handlers (forms.js)
 */

import { showToast } from './cart.js';

export function initForms() {
  initNewsletterForm();
  initContactForm();
}

function initNewsletterForm() {
  const form = document.querySelector('.newsletter-form');
  const input = document.querySelector('.newsletter-input');
  const successMsg = document.querySelector('.newsletter-success-msg');
  if (!form || !input) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const email = input.value.trim();

    if (!email || !filterEmail(email)) {
      showToast('Please enter a valid email address.');
      return;
    }

    try {
      const res = await fetch('/api/newsletter', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': getCsrfToken(),
        },
        body: JSON.stringify({ email: email }),
      });

      const data = await res.json();
      if (data.success) {
        form.style.display = 'none';
        if (successMsg) {
          successMsg.style.display = 'block';
          successMsg.textContent = 'Welcome to the Club — check your inbox.';
        }
      } else {
        showToast(data.message || 'Subscription failed. Please try again.');
      }
    } catch (err) {
      showToast('Something went wrong. Please try again.');
    }
  });
}

function initContactForm() {
  const form = document.querySelector('.contact-form');
  if (!form) return;

  const msgTextarea = form.querySelector('textarea[name="message"]');
  const charCounter = form.querySelector('.char-counter');
  const successBlock = document.querySelector('.contact-success-state');

  // Live char counter
  if (msgTextarea && charCounter) {
    msgTextarea.addEventListener('input', () => {
      const len = msgTextarea.value.length;
      charCounter.textContent = `${len}/1000`;
      if (len < 10 || len > 1000) {
        charCounter.style.color = 'var(--error)';
      } else {
        charCounter.style.color = 'var(--muted)';
      }
    });
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearFormErrors(form);

    const formData = new FormData(form);
    const dataObj = Object.fromEntries(formData.entries());

    // Basic client validation
    let hasError = false;
    if (!dataObj.name?.trim()) {
      showFieldError(form, 'name', 'Full name is required.');
      hasError = true;
    }
    if (!dataObj.email?.trim() || !filterEmail(dataObj.email)) {
      showFieldError(form, 'email', 'Please enter a valid email address.');
      hasError = true;
    }
    if (!dataObj.topic) {
      showFieldError(form, 'topic', 'Please select an inquiry topic.');
      hasError = true;
    }
    if (!dataObj.message || dataObj.message.length < 10) {
      showFieldError(form, 'message', 'Please write a message of at least 10 characters.');
      hasError = true;
    }

    if (hasError) return;

    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'SENDING...';
    submitBtn.disabled = true;

    try {
      const res = await fetch('/api/contact', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': getCsrfToken(),
        },
        body: JSON.stringify(dataObj),
      });

      if (res.ok) {
        const resData = await res.json();
        if (resData.success) {
          showContactSuccess(form, successBlock, dataObj.name);
          return;
        } else if (resData.errors) {
          for (const [field, msg] of Object.entries(resData.errors)) {
            showFieldError(form, field, Array.isArray(msg) ? msg[0] : msg);
          }
          return;
        }
      }
      throw new Error('Fallback to static / Netlify form submission');
    } catch (err) {
      // Netlify Forms fallback
      try {
        await fetch('/', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams(formData).toString(),
        });
      } catch {}
      showContactSuccess(form, successBlock, dataObj.name);
    } finally {
      submitBtn.textContent = originalText;
      submitBtn.disabled = false;
    }
  });
}

function showContactSuccess(form, successBlock, name) {
  form.style.display = 'none';
  if (successBlock) {
    successBlock.style.display = 'block';
    const nameSpan = successBlock.querySelector('.contact-success-name');
    if (nameSpan) nameSpan.textContent = name;
  }
}

function showFieldError(form, fieldName, message) {
  const field = form.querySelector(`[name="${fieldName}"]`);
  if (!field) return;

  field.setAttribute('aria-invalid', 'true');
  const group = field.closest('.form-group');
  if (group) {
    const err = document.createElement('div');
    err.className = 'form-error-msg';
    err.textContent = message;
    group.appendChild(err);
  }
}

function clearFormErrors(form) {
  form.querySelectorAll('[aria-invalid="true"]').forEach((el) => el.removeAttribute('aria-invalid'));
  form.querySelectorAll('.form-error-msg').forEach((el) => el.remove());
}

function filterEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function getCsrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}
