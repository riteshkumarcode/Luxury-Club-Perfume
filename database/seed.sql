-- Luxury Club Perfume E-commerce Seed Data (MySQL 8 / MariaDB & SQLite compatible)

-- 1. Categories
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `cover_image`, `sort_order`, `is_active`) VALUES
(1, 'Eau de Parfum', 'eau-de-parfum', 'Exquisite 100 ml crystal flacons adorned with gold crests. Long-lasting, complex olfactory journeys created by master perfumers.', 'red-crystal.png', 1, 1),
(2, 'Roll-on Attars', 'attars', '10 ml pocket-sized roll-ons of concentrated, alcohol-free pure perfume oils topped with faceted gold crowns.', 'royal-oudh.png', 2, 1),
(3, 'Car Hanging Pods', 'car-pods', 'Signature wooden diffuser caps with braided gold cords that slowly release bespoke luxury essences on every journey.', 'pod-blue.png', 3, 1),
(4, 'Scented Candles', 'candles', 'Luxury premium scented jar candles (100 g / 3.5 oz) with gold lids, hand-poured with natural soy-blend wax.', 'english-rose.png', 4, 1);

-- 2. Products
INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `size_label`, `price`, `compare_at_price`, `image`, `tint`, `hero_bg`, `badge`, `short_description`, `description`, `notes_top`, `notes_heart`, `notes_base`, `how_to_use`, `tags`, `stock_qty`, `is_featured`, `is_active`, `sort_order`, `meta_title`, `meta_description`) VALUES
(1, 1, 'Blue Orchid', 'blue-orchid', '100 ml', 1499, 1899, 'blue-orchid.png', '#E3E7F3', '#0E1633', 'Bestseller', 
 'Sapphire glass, a crystal crown, and a floral heart that stays with you throughout the day and evening.', 
 'Blue Orchid opens with sparkling crisp bergamot and dew-kissed petals before unfurling into an opulent bouquet of rare midnight orchids and jasmine sambac. Finished on a bed of velvety white amber and cashmere wood. Presented in a heavy crystal flacon with an engraved gold crest.', 
 'Bergamot, Mandarin, Dewy Greens', 'Midnight Blue Orchid, Sambac Jasmine, Orris', 'White Amber, Cashmere Wood, Warm Musk', 
 'Spray on pulse points from about 15 cm and do not rub.', 'floral,woody,evening,luxury', 45, 1, 1, 1, 
 'Blue Orchid Eau de Parfum 100ml | Luxury Club', 'Experience Blue Orchid Eau de Parfum by Luxury Club. A masterwork of midnight orchids and amber in a crystal flacon.'),

(2, 1, 'Red Crystal', 'red-crystal', '100 ml', 1499, 1799, 'red-crystal.png', '#F4E0E0', '#2B0A10', 'New', 
 'Radiant, bold and unforgettable — our signature crimson statement that turns heads wherever you step.', 
 'An intoxicating blend of wild saffron, candied berries, and damask rose suspended over smoked cedar and crystalline amber. Red Crystal is designed for magnetic personalities who command the room with understated elegance.', 
 'Blood Orange, Saffron, Wild Berries', 'Damask Rose, Caramelized Cedar, Orange Blossom', 'Ambergris, Oakmoss, Sweet Resin', 
 'Spray on pulse points from about 15 cm and do not rub.', 'oriental,fruity,bold,signature', 30, 1, 1, 2, 
 'Red Crystal Eau de Parfum 100ml | Luxury Club', 'Discover Red Crystal Eau de Parfum. A vibrant symphony of saffron, berries and rich amber by Luxury Club.'),

(3, 1, 'Royal Oud', 'royal-oud', '100 ml', 1699, 2199, 'royal-oud.png', '#ECE6DA', '#0D0B08', NULL, 
 'Smoky, regal oud in obsidian glass. A timeless tribute to ancestral nobility made to linger.', 
 'Crafted with aged Cambodian agarwood, spicy cardamom, and rare frankincense. Royal Oud envelops the senses with a dark, majestic warmth that evolves over 12+ hours into a creamy sandalwood trail.', 
 'Cardamom, Pink Pepper, Frankincense', 'Aged Cambodian Oud, Nutmeg, Turkish Rose', 'Sandalwood, Leather, Birch Tar, Dark Amber', 
 'Spray on pulse points from about 15 cm and do not rub.', 'oud,smoky,regal,wood', 25, 1, 1, 3, 
 'Royal Oud Eau de Parfum 100ml | Luxury Club', 'Royal Oud EDP by Luxury Club. Pure nobility in black glass with smoky Cambodian agarwood and spices.'),

(4, 2, 'Aqua Delight', 'aqua-delight', '10 ml', 499, 649, 'aqua-delight.png', '#DCEFF2', '#06272D', NULL, 
 'Pure perfume oil, fresh as open water. An invigorating alcohol-free rush for all-day freshness.', 
 'Aqua Delight combines oceanic ozonic accords with crushed mint, Italian lemon, and cedarwood oil. Concentrated yet refreshingly clean, it rolls smoothly onto the skin for an enduring aura of coastal luxury.', 
 'Sea Breeze Accord, Crushed Mint, Amalfi Lemon', 'Lavender, Cardamom, Clary Sage', 'Cedarwood, Vetiver, Clean White Musk', 
 'Roll onto wrists, neck and behind the ears. It is a concentrated alcohol-free oil, so a light touch lasts.', 'fresh,aquatic,alcohol-free,daily', 60, 1, 1, 4, 
 'Aqua Delight Roll-on Attar 10ml | Luxury Club', 'Shop Aqua Delight 10ml concentrated roll-on attar. 100% alcohol-free marine freshness by Luxury Club.'),

(5, 2, 'Ruby Red', 'ruby-red', '10 ml', 499, 599, 'ruby-red.png', '#F6E1E3', NULL, NULL, 
 'Sweet candied florals and velvety warmth in an artisanal alcohol-free concentrated perfume oil.', 
 'Ruby Red delights the senses with succulent pomegranate, candied rose petals, and a creamy vanilla undertone that lingers softly throughout the day. Handcrafted with faceted gold caps.', 
 'Pomegranate, Raspberry, Red Apple', 'Turkish Rose, Violet Leaf, Jasmine', 'Bourbon Vanilla, Soft Amber, Sandalwood', 
 'Roll onto wrists, neck and behind the ears. It is a concentrated alcohol-free oil, so a light touch lasts.', 'sweet,floral,attar,gourmand', 50, 1, 1, 5, 
 'Ruby Red Roll-on Attar 10ml | Luxury Club', 'Ruby Red Roll-on Attar by Luxury Club. Alcohol-free sweet rose and vanilla pure perfume oil.'),

(6, 2, 'Royal Oudh', 'royal-oudh', '10 ml', 549, 699, 'royal-oudh.png', '#EEEADF', NULL, 'Bestseller', 
 'Authentic Middle Eastern oudh oil with hints of saffron and leather. Our highest-rated roll-on attar.', 
 'A rich, deep and grounding roll-on attar that balances precious agarwood resin with warm amber and spicy nutmeg. Alcohol-free formulation allows the authentic warmth to evolve naturally on the skin.', 
 'Saffron, Nutmeg, Bergamot', 'Agarwood (Oudh), Rose absolute, Patchouli', 'Ambergris, Tobacco Leaf, Guaiacwood', 
 'Roll onto wrists, neck and behind the ears. It is a concentrated alcohol-free oil, so a light touch lasts.', 'oud,bestseller,oriental,alcohol-free', 70, 1, 1, 6, 
 'Royal Oudh Roll-on Attar 10ml | Luxury Club', 'Royal Oudh Roll-on Attar 10ml. Best-selling alcohol-free oud oil by Luxury Club.'),

(7, 2, 'White Leather', 'white-leather', '10 ml', 499, 599, 'white-leather.png', '#EFEDE8', NULL, NULL, 
 'Sophisticated suede accords blended with powdery iris and crisp juniper. A modern unisex classic.', 
 'A sleek and modern composition featuring supple white suede, Tuscan iris, and aromatic juniper berries. Clean, sharp, and irresistibly refined for boardroom or black-tie wear.', 
 'Juniper Berries, Thyme, Clary Sage', 'White Suede, Tuscan Iris, Saffron', 'Birch Wood, Amber, Clean Musk', 
 'Roll onto wrists, neck and behind the ears. It is a concentrated alcohol-free oil, so a light touch lasts.', 'leather,unisex,modern,clean', 40, 1, 1, 7, 
 'White Leather Roll-on Attar 10ml | Luxury Club', 'White Leather Attar 10ml by Luxury Club. Pure suede and Tuscan iris in an alcohol-free roll-on.'),

(8, 2, 'A Sparkle Oud', 'sparkle-oud', '10 ml', 549, 699, 'sparkle-oud.png', '#E8E6E2', NULL, NULL, 
 'Luminous citrus sparkles dancing over a deep, resonant oudh foundation. A contemporary marvel.', 
 'Bridging luminous brightness and dark oriental depth, A Sparkle Oud begins with sparkling grapefruit and pepper before sinking into rich oudh, leather, and vanilla.', 
 'Sparkling Grapefruit, Black Pepper, Coriander', 'Precious Oud, Geranium, Frankincense', 'Smoked Vetiver, Leather, Tonka Bean', 
 'Roll onto wrists, neck and behind the ears. It is a concentrated alcohol-free oil, so a light touch lasts.', 'oud,citrus,sparkling,alcohol-free', 35, 1, 1, 8, 
 'A Sparkle Oud Roll-on Attar 10ml | Luxury Club', 'A Sparkle Oud 10ml Roll-on by Luxury Club. Bright citrus harmonized with regal oud oil.'),

(9, 2, 'Blooming Dreams', 'blooming-dreams', '10 ml', 499, 599, 'blooming-dreams.png', '#F9E4DC', NULL, NULL, 
 'A dreamscape of freshly bloomed gardenias, sweet nectarines, and creamy coconut blossoms.', 
 'Gentle, romantic, and uplifting. Blooming Dreams wraps you in sunlit gardenia, juicy peach nectar, and golden amber for a blissful scent trail that brightens any day.', 
 'Nectarine, Golden Peach, Freesia', 'Gardenia, Orange Blossom, Coconut Water', 'Golden Amber, Vanilla, Sheer Musk', 
 'Roll onto wrists, neck and behind the ears. It is a concentrated alcohol-free oil, so a light touch lasts.', 'floral,sweet,uplifting,summer', 55, 1, 1, 9, 
 'Blooming Dreams Roll-on Attar 10ml | Luxury Club', 'Blooming Dreams 10ml Attar by Luxury Club. Pure alcohol-free floral essence with peach and gardenia.'),

(10, 2, 'Magnetic Edge', 'magnetic-edge', '10 ml', 499, 649, 'magnetic-edge.png', '#E2E7F6', NULL, 'New', 
 'A dark aquatic-spicy signature designed for nocturnal allure. Bold, sharp and magnetic.', 
 'Magnetic Edge commands attention with spicy black pepper, mineral sea salt, and magnetic Ambroxan laced over dark patchouli. Created for captivating presence.', 
 'Black Pepper, Sea Salt, Cardamom', 'Mineral Marine Accord, Lavender, Cypress', 'Ambroxan, Dark Patchouli, Tonka', 
 'Roll onto wrists, neck and behind the ears. It is a concentrated alcohol-free oil, so a light touch lasts.', 'magnetic,aquatic,spicy,nocturnal', 45, 1, 1, 10, 
 'Magnetic Edge Roll-on Attar 10ml | Luxury Club', 'Magnetic Edge 10ml Attar by Luxury Club. Intensely magnetic aquatic-spice perfume oil.'),

(11, 3, 'Hanging Pod – Red', 'hanging-pod-red', 'Car freshener', 349, 449, 'pod-red.png', '#F7E4E4', NULL, NULL, 
 'Luxurious car hanging diffuser pod with rich crimson berry and amber essence. Wooden diffuser cap.', 
 'Elevate every drive into a first-class sensory voyage. Features a porous natural beechwood cap and braided gold suspension cord that gently diffuse exquisite warm amber and crimson berries.', 
 'Wild Berry, Bergamot, Pink Peppercorn', 'Warm Amber, Rose, Nutmeg', 'Cedarwood, Vanilla, Soft Musk', 
 'Hang from the rear-view mirror, tip briefly so the wooden cap absorbs oil, and repeat when the scent fades.', 'car,diffuser,amber,travel', 80, 1, 1, 11, 
 'Hanging Pod Red Car Freshener | Luxury Club', 'Luxury Car Hanging Pod in Red. Premium wooden diffuser with braided gold cord by Luxury Club.'),

(12, 3, 'Hanging Pod – Blue', 'hanging-pod-blue', 'Car freshener', 349, 449, 'pod-blue.png', '#DFEDF2', NULL, NULL, 
 'Invigorating oceanic breeze and crisp cedarwood car diffuser pod. Long-lasting natural diffusion.', 
 'Infuse your car cabin with the crisp clarity of oceanic air, Mediterranean citrus, and sun-warmed cedar. Handcrafted glass flask with porous beechwood cap and braided gold lanyard.', 
 'Ocean Mist, Lime, Italian Lemon', 'Eucalyptus, Sea Salt, Lavender', 'Cedarwood, Driftwood, Clean Musk', 
 'Hang from the rear-view mirror, tip briefly so the wooden cap absorbs oil, and repeat when the scent fades.', 'car,diffuser,marine,fresh', 85, 1, 1, 12, 
 'Hanging Pod Blue Car Freshener | Luxury Club', 'Luxury Car Hanging Pod in Blue. Oceanic cedar diffuser with wooden cap by Luxury Club.'),

(13, 4, 'Morning Jasmine', 'morning-jasmine', '100 g', 599, 799, 'morning-jasmine.png', '#F7EED6', NULL, NULL, 
 'Luxury premium scented jar candle with gold lid. Hand-poured soy wax with dew-fresh jasmine sambac.', 
 'Transform your sanctuary into a tranquil garden. Morning Jasmine releases notes of night-blooming jasmine, green tea, and calming sandalwood with an even, clean 25+ hour burn time.', 
 'Morning Dew, Green Tea Leaves, Neroli', 'Grandiflorum Jasmine, Sambac Blossom, Lily', 'White Sandalwood, Cashmere, Honey', 
 'Trim the wick to 5 mm, let the top melt evenly on the first burn, and never leave it unattended.', 'candle,home,jasmine,relaxation', 40, 1, 1, 13, 
 'Morning Jasmine Scented Candle 100g | Luxury Club', 'Morning Jasmine 100g Luxury Scented Candle with gold lid. Hand-poured soy wax by Luxury Club.'),

(14, 4, 'English Rose', 'english-rose', '100 g', 599, 799, 'english-rose.png', '#F5E1DD', NULL, 'Gift pick', 
 'Luxury premium scented jar candle with gold lid. Romantic damascena rose with powdered vanilla.', 
 'A celebration of romantic heritage. English Rose fills your living space with the delicate sweetness of freshly cut English garden roses, powdered vanilla, and golden amber.', 
 'Velvet Rose Petals, Lychee, Pink Peony', 'Damascena Rose Absolute, Geranium, Violet', 'Powdered Amber, Vanilla Bean, Musk', 
 'Trim the wick to 5 mm, let the top melt evenly on the first burn, and never leave it unattended.', 'candle,rose,gift,romantic', 50, 1, 1, 14, 
 'English Rose Scented Candle 100g | Luxury Club', 'English Rose 100g Luxury Scented Candle with gold lid. The ultimate gift pick by Luxury Club.');

-- 3. Hero Slides
INSERT INTO `hero_slides` (`id`, `product_id`, `eyebrow`, `title`, `subline`, `cta_label`, `cta_url`, `cta2_label`, `cta2_url`, `image`, `tint`, `bg_color`, `outline_word`, `sort_order`, `is_active`) VALUES
(1, 1, 'EAU DE PARFUM · 100 ML', 'Blue<br>Orchid', 'Sapphire glass, a crystal crown, and a floral heart that stays with you.', 'Explore Blue Orchid', '/product/blue-orchid', 'View Collection', '/shop', 'blue-orchid.png', '#E3E7F3', '#0E1633', 'Orchid', 1, 1),
(2, 2, 'NEW · EAU DE PARFUM', 'Red<br>Crystal', 'Radiant, bold and unforgettable — our signature in crimson.', 'Discover Red Crystal', '/product/red-crystal', 'Shop Perfumes', '/shop/eau-de-parfum', 'red-crystal.png', '#F4E0E0', '#2B0A10', 'Crystal', 2, 1),
(3, 3, 'SIGNATURE · 100 ML', 'Royal<br>Oud', 'Smoky, regal oud in black glass. Made to linger.', 'Explore Royal Oud', '/product/royal-oud', 'All Eau de Parfum', '/shop/eau-de-parfum', 'royal-oud.png', '#ECE6DA', '#0D0B08', 'Oud', 3, 1),
(4, 4, 'ROLL-ON ATTAR · ALCOHOL-FREE', 'Aqua<br>Delight', 'Pure perfume oil, fresh as open water. Roll on and go.', 'Shop Attars', '/shop/attars', 'Explore All', '/shop', 'aqua-delight.png', '#DCEFF2', '#06272D', 'Attar', 4, 1);

-- 4. Settings
INSERT INTO `settings` (`key`, `value`) VALUES
('free_shipping_threshold', '999'),
('shipping_fee', '99'),
('phone', '+91 98765 43210 (TODO: Client phone)'),
('whatsapp', '+91 98765 43210 (TODO: Client whatsapp)'),
('email', 'concierge@luxuryclub.com (TODO: Client email)'),
('address', 'Luxury Club Atelier, Suite 402, Signature Towers, Mumbai, Maharashtra 400001 (TODO: Client address)'),
('hours', 'Monday – Saturday: 10:00 AM – 8:00 PM IST'),
('instagram_url', 'https://instagram.com/luxuryclub'),
('facebook_url', 'https://facebook.com/luxuryclub'),
('youtube_url', 'https://youtube.com/luxuryclub'),
('announcement_messages', '["COMPLIMENTARY SHIPPING ON ORDERS OVER ₹999", "ARTISANAL ALCOFREE ATTARS & PURE PARFUMS", "EXQUISITE GIFT BOX PACKAGING WITH EVERY ORDER", "DISPATCHED WITHIN 24 HOURS ACROSS INDIA"]'),
('razorpay_key_id', 'rzp_test_luxuryclub2026'),
('delivery_estimate_text', '2–4 business days across major Indian metros and 4–6 days rest of India.'),
('enable_cod', '1'),
('policy_shipping', 'Standard delivery takes 2–4 business days for metro locations and 4–6 business days for non-metro areas across India. Orders above ₹999 qualify for complimentary express shipping. (TODO: Client to confirm carrier terms)'),
('policy_returns', 'Due to the artisanal and personal nature of fragrance products, returns are accepted within 7 days of delivery only for sealed, unopened items or in the rare event of transit damage. (TODO: Client return policy)'),
('policy_privacy', 'Luxury Club respects your privacy. We collect customer information strictly for fulfilling orders and enhancing your fragrance concierge experience. We never sell your personal data. (TODO: Client privacy policy)'),
('policy_terms', 'By using the Luxury Club online boutique and placing orders, you agree to our terms of service, payment protocols and shipping terms. (TODO: Client terms of service)');

-- 5. Admin User (Default password: AdminLuxury2026!)
INSERT INTO `admin_users` (`id`, `name`, `email`, `password_hash`, `created_at`) VALUES
(1, 'Luxury Club Admin', 'admin@luxuryclub.com', '$2y$10$OPj9s8anS462pBB8mlPUh.pd42yYCqmJZiLeLgT1NexddixYOkZgS', CURRENT_TIMESTAMP);
