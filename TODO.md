# Luxury Club — Client Action & Confirmation Items (TODO.md)

This document catalogs every business placeholder, secret key, carrier integration, and content item requiring client confirmation or production credentials prior to launch.

---

## 1. Product Catalog & Olfactory Formula Verification
All 14 initial products have been seeded with placeholder prices, fragrance pyramids, and descriptions based on product flacon labels. Please verify and update:

| Product Name | Category | Current Seed Price | Seed Size | Client Confirmed Price | Client Notes & Olfactory Character |
|---|---|---|---|---|---|
| **Blue Orchid** | Eau de Parfum | ₹1,499 | 100 ml | `TODO: Client to confirm` | `TODO: Top/Heart/Base notes confirmation` |
| **Red Crystal** | Eau de Parfum | ₹1,499 | 100 ml | `TODO: Client to confirm` | `TODO: Top/Heart/Base notes confirmation` |
| **Royal Oud** | Eau de Parfum | ₹1,699 | 100 ml | `TODO: Client to confirm` | `TODO: Top/Heart/Base notes confirmation` |
| **Aqua Delight** | Roll-on Attars | ₹499 | 10 ml | `TODO: Client to confirm` | `TODO: Alcohol-free formulation verification` |
| **Ruby Red** | Roll-on Attars | ₹499 | 10 ml | `TODO: Client to confirm` | `TODO: Notes confirmation` |
| **Royal Oudh** | Roll-on Attars | ₹549 | 10 ml | `TODO: Client to confirm` | `TODO: Agarwood origin confirmation` |
| **White Leather** | Roll-on Attars | ₹499 | 10 ml | `TODO: Client to confirm` | `TODO: Notes confirmation` |
| **A Sparkle Oud** | Roll-on Attars | ₹549 | 10 ml | `TODO: Client to confirm` | `TODO: Notes confirmation` |
| **Blooming Dreams** | Roll-on Attars | ₹499 | 10 ml | `TODO: Client to confirm` | `TODO: Notes confirmation` |
| **Magnetic Edge** | Roll-on Attars | ₹499 | 10 ml | `TODO: Client to confirm` | `TODO: Notes confirmation` |
| **Hanging Pod – Red** | Car Hanging Pods | ₹349 | Car freshener | `TODO: Client to confirm` | `TODO: Scent profile confirmation` |
| **Hanging Pod – Blue** | Car Hanging Pods | ₹349 | Car freshener | `TODO: Client to confirm` | `TODO: Scent profile confirmation` |
| **Morning Jasmine** | Scented Candles | ₹599 | 100 g / 3.5 oz | `TODO: Client to confirm` | `TODO: Burn time and soy wax composition` |
| **English Rose** | Scented Candles | ₹599 | 100 g / 3.5 oz | `TODO: Client to confirm` | `TODO: Fragrance profile confirmation` |

---

## 2. Atelier Contact & Business Credentials
- [ ] **Phone Number:** Update in Admin Settings (`settings.phone`) — currently set to `+91 98765 43210 (TODO)`.
- [ ] **WhatsApp Number:** Update in Admin Settings (`settings.whatsapp`) for direct click-to-chat.
- [ ] **Concierge Email:** Update in Admin Settings (`settings.email`) — currently `concierge@luxuryclub.com`.
- [ ] **Physical Atelier Address:** Update physical studio/showroom address in Admin Settings (`settings.address`).
- [ ] **Operating Hours:** Update concierge support schedule.
- [ ] **Social Media Channels:** Update live URLs for Instagram, Facebook, and YouTube channels.

---

## 3. Brand Heritage & Story Copy (`/about`)
- [ ] **Founding Year & Story:** Provide official story on founder origins, inspiration, and city of distillation.
- [ ] **Third Brand Pillar:** Confirm or revise the third core pillar value descriptor on the About page.

---

## 4. Payment Gateway & Financial Protocols
- [ ] **Razorpay Live Credentials:**
  - `RAZORPAY_KEY_ID`: Provide live Razorpay Key ID (update in `.env` and Admin Settings).
  - `RAZORPAY_KEY_SECRET`: Provide live Razorpay Key Secret (update in `.env`).
  - Webhook Secret: Configure webhook URL for automated status synchronization if desired.
- [ ] **Cash on Delivery (COD):** Confirm whether COD should be enabled or disabled in Admin Settings (`settings.enable_cod`).
- [ ] **Shipping Threshold & Fees:** Confirm Free Shipping Threshold (`₹999`) and standard fee (`₹99`).

---

## 5. Transactional Email & SMTP Configuration
- [ ] **SMTP Credentials:** Provide production SMTP host, username, password, port, and TLS settings in `.env`:
  - `SMTP_HOST`
  - `SMTP_PORT`
  - `SMTP_USERNAME`
  - `SMTP_PASSWORD`
  - `SMTP_FROM_ADDRESS`
  - `ADMIN_NOTIFY_EMAIL`

---

## 6. Courier & Logistics API Integration
- [ ] **Automated Serviceability API:** Optional integration with Shiprocket or Delhivery APIs for live pincode serviceability and real-time tracking number generation (`/api/pincode`).

---

## 7. Legal & Store Policies
- [ ] **Shipping Policy (`/policies/shipping`):** Confirm official transit times and logistics partner terms.
- [ ] **Returns & Exchanges (`/policies/returns`):** Review sealed perfume return conditions and transit replacement protocol.
- [ ] **Privacy Policy (`/policies/privacy`):** Ensure GDPR / Indian DPDP Act compliance.
- [ ] **Terms of Service (`/policies/terms`):** Review commercial and checkout terms.
