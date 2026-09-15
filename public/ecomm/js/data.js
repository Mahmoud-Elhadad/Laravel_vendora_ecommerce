/* ==========================================================================
   Vendora E-commerce — Demo Data (data.js)
   Single source of truth for products, categories, brands, blog, orders, etc.
   Frontend demo only — replace these arrays with Laravel data later.
   Loaded BEFORE all other site scripts.
   ========================================================================== */
(function (global) {
  'use strict';

  // All pages resolve relative URLs against root/pages/ (index.html uses a
  // <base href="pages/"> tag), so assets are referenced one level up.
  var IMG = '../assets/images/';

  /* ---------- Color palette (name -> hex) ---------- */
  var COLORS = {
    'Black': '#1a1a1a', 'White': '#f4f4f5', 'Gray': '#9ca3af', 'Charcoal': '#3f4350',
    'Navy': '#1e3a8a', 'Blue': '#3b82f6', 'Midnight': '#26304a', 'Starlight': '#e7e3d6',
    'Red': '#ef4444', 'Burgundy': '#7f1d2b', 'Green': '#22c55e', 'Teal': '#14b8a6',
    'Olive': '#6b7a3a', 'Sage': '#9caf9a', 'Brown': '#8b5a2b', 'Camel': '#c19a6b',
    'Beige': '#e7d8c4', 'Cream': '#f6efdf', 'Pink': '#ec4899', 'Purple': '#8b5cf6',
    'Silver': '#cbd5e1', 'Gold': '#d4af37', 'Brass': '#b08d57', 'Gunmetal': '#5a5f6a',
    'Indigo': '#3f4c8c', 'Light Wash': '#8fa8c9', 'Floral Blue': '#6f8fd0',
    'Floral Pink': '#e88fb0', 'Nude': '#e3c4b0', 'Terracotta': '#c86b4a',
    'Mustard': '#d9a521', 'Wood': '#a9814f', 'Space Gray': '#6b6f7a', 'Berry': '#9b2c4c'
  };

  /* ---------- Categories ---------- */
  var CATEGORIES = [
    { key: 'electronics', name: 'Electronics', icon: 'fa-laptop', image: IMG + 'categories/electronics.svg', description: 'Latest gadgets, audio, computing and smart devices from trusted brands.' },
    { key: 'fashion', name: 'Fashion', icon: 'fa-shirt', image: IMG + 'categories/fashion.svg', description: 'Trending apparel for every occasion — casual, formal and everything between.' },
    { key: 'shoes', name: 'Shoes', icon: 'fa-shoe-prints', image: IMG + 'categories/shoes.svg', description: 'Sneakers, boots, heels and trainers built for comfort and style.' },
    { key: 'watches', name: 'Watches', icon: 'fa-clock', image: IMG + 'categories/watches.svg', description: 'Analog, digital and smart watches to match every wrist and lifestyle.' },
    { key: 'bags', name: 'Bags', icon: 'fa-bag-shopping', image: IMG + 'categories/bags.svg', description: 'Backpacks, totes, messengers and travel bags for work and adventure.' },
    { key: 'accessories', name: 'Accessories', icon: 'fa-glasses', image: IMG + 'categories/accessories.svg', description: 'Finish the look with belts, wallets, sunglasses and scarves.' },
    { key: 'home', name: 'Home & Living', icon: 'fa-couch', image: IMG + 'categories/home.svg', description: 'Cozy, functional pieces to make your space feel like home.' },
    { key: 'beauty', name: 'Beauty', icon: 'fa-spa', image: IMG + 'categories/beauty.svg', description: 'Skincare, fragrance and cosmetics for your daily self-care routine.' },
    { key: 'sports', name: 'Sports', icon: 'fa-dumbbell', image: IMG + 'categories/sports.svg', description: 'Gear and equipment to power your training, play and recovery.' }
  ];
  var CAT_BY_KEY = {};
  CATEGORIES.forEach(function (c) { CAT_BY_KEY[c.key] = c; });

  /* ---------- Brands ---------- */
  var BRAND_NAMES = ['Aura', 'Vista', 'Zenith', 'Novatek', 'StrideX', 'TimeCraft', 'Elegance', 'CarryPro', 'GlowLab', 'IronCore'];
  var BRANDS = BRAND_NAMES.map(function (b, i) {
    return { name: b, image: IMG + 'brands/brand-' + (i + 1) + '.svg' };
  });

  /* ---------- Specification & feature templates per category ---------- */
  var SPEC_TEMPLATES = {
    electronics: [['Model', 'Gen {n}'], ['Connectivity', 'Bluetooth 5.3, Wi-Fi 6'], ['Battery Life', 'Up to 24 hours'], ['Warranty', '2 years limited'], ['In the Box', 'Device, cable, manual']],
    fashion: [['Material', 'Premium blended fabric'], ['Fit', 'Regular'], ['Care', 'Machine wash cold'], ['Pattern', 'Solid'], ['Origin', 'Imported']],
    shoes: [['Upper Material', 'Breathable mesh / leather'], ['Sole', 'Non-slip rubber'], ['Closure', 'Lace-up'], ['Water Resistant', 'No'], ['Care', 'Wipe clean']],
    watches: [['Movement', 'Quartz / Automatic'], ['Case Material', 'Stainless steel'], ['Water Resistance', '50 m'], ['Crystal', 'Sapphire-coated'], ['Strap', 'Adjustable']],
    bags: [['Material', 'Durable composite'], ['Compartments', 'Multiple'], ['Strap', 'Adjustable'], ['Water Resistant', 'Yes'], ['Care', 'Spot clean']],
    accessories: [['Material', 'High-grade finish'], ['Dimensions', 'Standard'], ['UV Protection', 'UV400'], ['Care', 'Wipe with soft cloth'], ['Origin', 'Imported']],
    home: [['Material', 'Quality composite'], ['Care', 'Easy clean'], ['Style', 'Modern'], ['Room', 'Any'], ['Assembly', 'Minimal']],
    beauty: [['Volume', '50 ml'], ['Skin Type', 'All skin types'], ['Key Ingredients', 'Natural actives'], ['Cruelty Free', 'Yes'], ['Origin', 'Imported']],
    sports: [['Material', 'Performance grade'], ['Skill Level', 'All levels'], ['Care', 'Wipe clean'], ['Warranty', '1 year'], ['Use', 'Indoor / Outdoor']]
  };
  var FEATURE_TEMPLATES = {
    electronics: ['Premium build with a refined finish', 'Fast, reliable everyday performance', 'Backed by a 2-year warranty', 'Easy setup out of the box'],
    fashion: ['Soft, breathable fabric', 'Versatile styling for any occasion', 'Holds shape and color wash after wash', 'True-to-size comfortable fit'],
    shoes: ['Cushioned comfort for all-day wear', 'Durable non-slip outsole', 'Breathable, supportive design', 'Flexible and lightweight'],
    watches: ['Precise, dependable movement', 'Scratch-resistant crystal', 'Water resistant for daily wear', 'Comfortable adjustable strap'],
    bags: ['Spacious, well-organized interior', 'Durable water-resistant material', 'Comfortable padded straps', 'Secure quality hardware'],
    accessories: ['Crafted from premium materials', 'Timeless versatile design', 'Comfortable everyday wear', 'Makes a great gift'],
    home: ['Adds a warm, modern touch', 'Built to last with quality materials', 'Easy to clean and maintain', 'Fits any room or style'],
    beauty: ['Gentle, effective formula', 'Suitable for all skin types', 'Free from harsh chemicals', 'Visible results with regular use'],
    sports: ['Built for performance and durability', 'Comfortable, secure fit', 'Suitable for all skill levels', 'Easy to store and transport']
  };

  /* ---------- Raw product rows ----------
     [id, name, cat, brand, price, old, rating, reviews, stock, qty, colors, sizes, short] */
  var RAW = [
    [1, 'Wireless Noise-Cancelling Headphones', 'electronics', 'Aura', 199.99, 249.99, 4.7, 214, 'in', 32, 'Black,Silver,Navy', '', 'Immersive sound with adaptive noise cancellation and 40-hour battery life.'],
    [2, '4K Ultra HD Smart TV 55"', 'electronics', 'Vista', 649.00, 799.00, 4.6, 158, 'in', 12, 'Black', '43",50",55",65"', 'Crystal-clear 4K HDR picture with built-in streaming apps and voice control.'],
    [3, 'Ultrabook Pro 14 Laptop', 'electronics', 'Zenith', 1299.00, 1499.00, 4.8, 96, 'in', 8, 'Space Gray,Silver', '', 'Featherlight 14-inch laptop with all-day battery and a stunning display.'],
    [4, 'Smartphone X12 Pro', 'electronics', 'Novatek', 899.00, 999.00, 4.5, 342, 'in', 45, 'Midnight,Starlight,Blue', '128GB,256GB,512GB', 'Flagship camera, blazing chipset and a pro-grade 120Hz display.'],
    [5, 'Portable Bluetooth Speaker', 'electronics', 'SoundWave', 79.99, 99.99, 4.4, 187, 'in', 60, 'Black,Red,Blue', '', 'Waterproof 360° speaker with deep bass and 20 hours of playtime.'],
    [6, 'DSLR Camera Kit 24MP', 'electronics', 'OptixPro', 1099.00, 1299.00, 4.9, 64, 'low', 4, 'Black', '', '24MP DSLR with 18-55mm lens, 4K video and dual-pixel autofocus.'],
    [7, 'Mechanical Gaming Keyboard', 'electronics', 'KeyForge', 129.99, 159.99, 4.6, 231, 'in', 40, 'Black,White', '', 'RGB mechanical keyboard with hot-swappable switches and aluminum frame.'],
    [8, 'Smart Fitness Tracker Band', 'electronics', 'PulseFit', 59.99, 79.99, 4.2, 410, 'in', 120, 'Black,Teal,Pink', '', 'Track heart rate, sleep and 20+ workouts with a 10-day battery.'],
    [9, 'Classic Cotton T-Shirt', 'fashion', 'UrbanThread', 24.99, 34.99, 4.3, 512, 'in', 200, 'White,Black,Navy,Gray', 'S,M,L,XL,XXL', 'Ultra-soft 100% organic cotton tee with a relaxed everyday fit.'],
    [10, 'Slim Fit Denim Jeans', 'fashion', 'DenimCo', 49.99, 69.99, 4.4, 289, 'in', 85, 'Indigo,Black,Light Wash', '30,32,34,36,38', 'Stretch slim-fit jeans that move with you and hold their shape.'],
    [11, 'Wool Blend Overcoat', 'fashion', 'Elegance', 189.00, 249.00, 4.7, 74, 'in', 22, 'Camel,Charcoal,Black', 'S,M,L,XL', 'Tailored wool-blend overcoat with a warm satin lining.'],
    [12, 'Summer Floral Dress', 'fashion', 'Bloom', 59.99, 79.99, 4.5, 163, 'in', 48, 'Floral Blue,Floral Pink', 'XS,S,M,L', 'Breezy floral midi dress perfect for warm-weather days.'],
    [13, 'Casual Hooded Sweatshirt', 'fashion', 'UrbanThread', 44.99, 59.99, 4.6, 328, 'in', 95, 'Gray,Black,Green', 'S,M,L,XL,XXL', 'Cozy fleece-lined hoodie with a kangaroo pocket and drawstring hood.'],
    [14, 'Formal Silk Neck Tie', 'fashion', 'Gentlemen', 29.99, 39.99, 4.2, 88, 'in', 150, 'Burgundy,Navy,Silver', '', 'Hand-finished 100% mulberry silk tie with a subtle sheen.'],
    [15, 'Running Shoes AirFlex', 'shoes', 'StrideX', 119.99, 149.99, 4.7, 276, 'in', 55, 'Black,White,Red', '7,8,9,10,11,12', 'Responsive cushioning and breathable mesh for your daily miles.'],
    [16, 'Leather Oxford Shoes', 'shoes', 'Classico', 149.00, 189.00, 4.6, 121, 'low', 5, 'Brown,Black', '7,8,9,10,11', 'Full-grain leather Oxfords with a hand-stitched Goodyear welt.'],
    [17, 'Casual Street Sneakers', 'shoes', 'StreetStep', 89.99, 109.99, 4.4, 398, 'in', 70, 'White,Black,Pink', '6,7,8,9,10,11', 'Everyday low-top sneakers with cushioned insoles and clean lines.'],
    [18, 'Trail Hiking Boots', 'shoes', 'SummitGear', 159.99, 199.99, 4.8, 92, 'in', 26, 'Brown,Olive', '8,9,10,11,12', 'Waterproof hiking boots with aggressive grip for rough terrain.'],
    [19, "Women's Heeled Sandals", 'shoes', 'Elegance', 79.99, 99.99, 4.3, 145, 'in', 40, 'Nude,Black,Gold', '5,6,7,8,9', 'Elegant block-heel sandals with a padded footbed for all-day comfort.'],
    [20, 'Athletic Training Shoes', 'shoes', 'StrideX', 99.99, 129.99, 4.5, 210, 'in', 58, 'Gray,Blue,Black', '7,8,9,10,11,12', 'Stable, supportive trainers built for gym sessions and cross-fit.'],
    [21, 'Analog Chronograph Watch', 'watches', 'TimeCraft', 249.00, 329.00, 4.7, 67, 'in', 18, 'Silver,Gunmetal', '', 'Precision chronograph with sapphire crystal and 100m water resistance.'],
    [22, 'Smart Watch Series 8', 'watches', 'Novatek', 299.00, 349.00, 4.6, 254, 'in', 44, 'Midnight,Silver,Gold', '41mm,45mm', 'Advanced health tracking, always-on display and seamless notifications.'],
    [23, 'Minimalist Leather Watch', 'watches', 'TimeCraft', 129.00, 169.00, 4.5, 143, 'in', 36, 'Brown,Black', '', 'Clean minimalist dial on a genuine Italian leather strap.'],
    [24, "Diver's Automatic Watch", 'watches', 'AquaTime', 399.00, 499.00, 4.9, 38, 'low', 5, 'Blue,Black', '', 'Automatic dive watch with 300m resistance and luminous markers.'],
    [25, 'Digital Sports Watch', 'watches', 'PulseFit', 89.99, 119.99, 4.2, 176, 'in', 62, 'Black,Red', '', 'Rugged digital watch with stopwatch, alarm and 50m water resistance.'],
    [26, 'Leather Laptop Backpack', 'bags', 'CarryPro', 129.99, 169.99, 4.7, 198, 'in', 40, 'Brown,Black', '', 'Water-resistant leather backpack with a padded 15.6-inch laptop sleeve.'],
    [27, 'Canvas Messenger Bag', 'bags', 'UrbanCarry', 79.99, 99.99, 4.4, 132, 'in', 52, 'Olive,Gray,Navy', '', 'Durable waxed canvas messenger with multiple organizer pockets.'],
    [28, "Women's Tote Handbag", 'bags', 'Elegance', 99.99, 139.99, 4.6, 221, 'in', 46, 'Beige,Black,Burgundy', '', 'Spacious structured tote in pebbled vegan leather with gold hardware.'],
    [29, 'Travel Duffel Bag', 'bags', 'Voyager', 89.99, 119.99, 4.5, 87, 'in', 38, 'Black,Navy', '40L,60L', 'Carry-on friendly duffel with shoe compartment and trolley sleeve.'],
    [30, 'Anti-Theft Sling Bag', 'bags', 'CarryPro', 59.99, 79.99, 4.3, 264, 'in', 74, 'Black,Gray', '', 'Compact sling with hidden zippers, RFID pocket and USB charging port.'],
    [31, 'Aviator Sunglasses', 'accessories', 'SunShade', 69.99, 89.99, 4.4, 156, 'in', 68, 'Gold,Silver,Black', '', 'Polarized UV400 aviators with a lightweight metal frame.'],
    [32, 'Genuine Leather Belt', 'accessories', 'Gentlemen', 39.99, 54.99, 4.5, 342, 'in', 120, 'Brown,Black', '32,34,36,38,40', 'Full-grain leather belt with a classic brushed-metal buckle.'],
    [33, 'Wool Winter Scarf', 'accessories', 'CozyWrap', 29.99, 39.99, 4.6, 189, 'in', 96, 'Gray,Camel,Burgundy', '', 'Soft lambswool scarf that keeps you warm in style.'],
    [34, 'Classic Leather Wallet', 'accessories', 'Gentlemen', 44.99, 59.99, 4.7, 421, 'in', 140, 'Brown,Black', '', 'Slim bifold wallet with RFID blocking and 8 card slots.'],
    [35, 'Silver Cufflink Set', 'accessories', 'Gentlemen', 34.99, 49.99, 4.2, 47, 'out', 0, 'Silver', '', 'Polished stainless steel cufflinks in an elegant gift box.'],
    [36, 'Modern Table Lamp', 'home', 'LumiHome', 59.99, 79.99, 4.5, 118, 'in', 42, 'White,Black,Brass', '', 'Scandinavian table lamp with touch dimming and warm LED glow.'],
    [37, 'Memory Foam Pillow', 'home', 'DreamRest', 39.99, 54.99, 4.6, 534, 'in', 150, 'White', 'Standard,Queen,King', 'Ergonomic memory foam pillow for cool, supportive sleep.'],
    [38, 'Ceramic Coffee Mug Set', 'home', 'HomeEssence', 24.99, 34.99, 4.4, 276, 'in', 110, 'White,Terracotta,Sage', '', 'Set of 4 handcrafted stoneware mugs, microwave and dishwasher safe.'],
    [39, 'Scented Candle Trio', 'home', 'AromaCasa', 29.99, 39.99, 4.7, 198, 'in', 88, 'Cream', '', 'Three soy-wax candles in lavender, vanilla and sandalwood.'],
    [40, 'Cozy Knit Throw Blanket', 'home', 'CozyWrap', 49.99, 69.99, 4.8, 312, 'in', 76, 'Cream,Gray,Mustard', '', 'Chunky knit throw blanket, irresistibly soft and machine washable.'],
    [41, 'Minimalist Wall Clock', 'home', 'LumiHome', 44.99, 59.99, 4.3, 84, 'in', 50, 'White,Black,Wood', '', 'Silent non-ticking wall clock with a clean Scandinavian dial.'],
    [42, 'Hydrating Face Serum', 'beauty', 'GlowLab', 49.99, 69.99, 4.6, 287, 'in', 90, 'White', '', 'Hyaluronic acid serum that plumps and hydrates for a dewy glow.'],
    [43, 'Matte Lipstick Collection', 'beauty', 'Bellezza', 24.99, 34.99, 4.4, 398, 'in', 130, 'Red,Nude,Berry,Pink', '', 'Long-wear matte lipsticks with a comfortable, non-drying formula.'],
    [44, 'Luxury Perfume 100ml', 'beauty', 'Essence', 89.99, 119.99, 4.7, 156, 'in', 64, 'Gold', '', 'An elegant eau de parfum blending citrus, jasmine and warm amber.'],
    [45, 'Facial Cleansing Brush', 'beauty', 'GlowLab', 69.99, 89.99, 4.3, 143, 'in', 55, 'Pink,White', '', 'Sonic cleansing brush for a deeper, spa-quality clean at home.'],
    [46, 'Organic Skincare Set', 'beauty', 'PureCare', 79.99, 99.99, 4.8, 210, 'in', 48, 'White', '', '4-step organic routine: cleanser, toner, serum and moisturizer.'],
    [47, 'Adjustable Dumbbell Set', 'sports', 'IronCore', 149.99, 189.99, 4.7, 176, 'in', 34, 'Black', '', 'Space-saving adjustable dumbbells from 5 to 52.5 lbs each.'],
    [48, 'Premium Yoga Mat', 'sports', 'FlexFit', 34.99, 49.99, 4.6, 421, 'in', 120, 'Purple,Teal,Gray', '', 'Extra-thick non-slip yoga mat with alignment lines and carry strap.'],
    [49, 'Professional Soccer Ball', 'sports', 'StrikePro', 39.99, 54.99, 4.5, 98, 'in', 80, 'White,Black', '4,5', 'Match-quality thermal-bonded ball with true flight and touch.'],
    [50, 'Insulated Water Bottle', 'sports', 'HydraPeak', 24.99, 34.99, 4.6, 654, 'in', 200, 'Black,Blue,Sage,Pink', '500ml,750ml,1L', 'Double-wall vacuum bottle keeps drinks cold 24h or hot 12h.']
  ];

  var NEW_ARRIVALS = [3, 4, 8, 12, 17, 21, 25, 29, 33, 42, 45, 49];
  var BEST_SELLERS = [1, 5, 9, 10, 15, 17, 22, 30, 34, 37, 40, 48, 50];
  var FEATURED = [2, 6, 11, 18, 24, 26, 36, 44, 46, 47];

  function slugify(s) {
    return s.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
  }
  function pad(n, len) { n = String(n); while (n.length < len) n = '0' + n; return n; }

  var PRODUCTS = RAW.map(function (r) {
    var id = r[0], name = r[1], cat = r[2], brand = r[3], price = r[4], old = r[5],
      rating = r[6], reviews = r[7], stock = r[8], qty = r[9], colorsStr = r[10], sizesStr = r[11], short = r[12];
    var category = CAT_BY_KEY[cat];
    var discount = old > price ? Math.round(((old - price) / old) * 100) : 0;
    var colors = colorsStr ? colorsStr.split(',').map(function (cn) {
      cn = cn.trim(); return { name: cn, hex: COLORS[cn] || '#cccccc' };
    }) : [];
    var sizes = sizesStr ? sizesStr.split(',').map(function (s) { return s.trim(); }) : [];
    var imgBase = IMG + 'products/product-' + id;
    var images = [imgBase + '.svg', imgBase + '-2.svg', imgBase + '-3.svg'];

    // Specifications
    var specs = { 'Brand': brand, 'Category': category.name, 'SKU': cat.slice(0, 3).toUpperCase() + '-' + pad(id, 4), 'Availability': stock === 'out' ? 'Out of stock' : 'In stock' };
    (SPEC_TEMPLATES[cat] || []).forEach(function (pair, i) {
      var val = pair[1].replace('{n}', String((id % 5) + 1));
      specs[pair[0]] = val;
    });

    var description = 'The ' + name + ' from ' + brand + ' blends thoughtful design with everyday practicality. ' + short +
      ' Made from premium materials and finished with care, it is built to last and easy to love. ' +
      'Whether you are treating yourself or shopping for a gift, the ' + name + ' delivers quality and style in equal measure — backed by the Vendora satisfaction guarantee.';

    return {
      id: id,
      name: name,
      slug: slugify(name),
      category: cat,
      categoryName: category.name,
      brand: brand,
      price: price,
      oldPrice: old,
      discount: discount,
      rating: rating,
      reviews: reviews,
      stock: stock,                 // 'in' | 'low' | 'out'
      stockQty: qty,
      sku: cat.slice(0, 3).toUpperCase() + '-' + pad(id, 4),
      image: images[0],
      images: images,
      colors: colors,
      sizes: sizes,
      short: short,
      description: description,
      specs: specs,
      features: FEATURE_TEMPLATES[cat] || [],
      isNew: NEW_ARRIVALS.indexOf(id) !== -1,
      isBestSeller: BEST_SELLERS.indexOf(id) !== -1,
      isFeatured: FEATURED.indexOf(id) !== -1,
      isDeal: discount >= 20
    };
  });

  // Attach product counts to categories
  CATEGORIES.forEach(function (c) {
    c.count = PRODUCTS.filter(function (p) { return p.category === c.key; }).length;
  });

  /* ---------- Blog posts ---------- */
  var BLOG_POSTS = [
    { id: 1, title: '10 Tech Gadgets That Will Upgrade Your Everyday Life', category: 'Technology', author: 'Sophia Reyes', date: '2026-08-28', readTime: '6 min read', image: IMG + 'blog/blog-1.svg', featured: true, excerpt: 'From noise-cancelling headphones to smart home essentials, these gadgets punch far above their price.', tags: ['Tech', 'Gadgets', 'Lifestyle'], content: ['Modern life moves fast, and the right gadgets can help you keep up without adding friction. We tested dozens of products across audio, computing and smart home categories to bring you the ones that genuinely earn a place in your routine.', 'Start with audio. A great pair of noise-cancelling headphones transforms commutes and open-plan offices into focused, calm spaces. Pair that with a portable speaker for the weekends and you have covered almost every listening scenario.', 'Next, think about your desk. A mechanical keyboard and a reliable ultrabook turn work into a pleasure rather than a chore. Add a smart fitness band to keep movement on your radar, and you have a setup that supports both productivity and wellbeing.', 'The common thread? Each of these picks solves a real problem and does it well. Skip the gimmicks, invest in quality, and your everyday life gets a little smoother, a little faster and a lot more enjoyable.'] },
    { id: 2, title: 'How to Build a Capsule Wardrobe That Actually Works', category: 'Fashion', author: 'Daniel Kim', date: '2026-08-21', readTime: '8 min read', image: IMG + 'blog/blog-2.svg', excerpt: 'Fewer pieces, better outfits. Here is the practical framework for a wardrobe that mixes and matches effortlessly.', tags: ['Fashion', 'Style', 'Minimalism'], content: ['A capsule wardrobe is not about owning less for the sake of it — it is about owning the right things so getting dressed becomes effortless.', 'Begin with a neutral base: a crisp white tee, well-fitting denim, a versatile overcoat and one pair of go-to shoes. These anchors multiply into dozens of outfits.', 'Then add two or three statement pieces that reflect your personality. Keep the palette cohesive so everything works together, and prioritize fit and fabric over trend.', 'Maintain it seasonally. Rotate out what you do not wear, repair what you love, and resist impulse buys. A thoughtful wardrobe saves money, time and mental energy.'] },
    { id: 3, title: 'The Complete Guide to Choosing Running Shoes', category: 'Sports', author: 'Amara Okafor', date: '2026-08-14', readTime: '7 min read', image: IMG + 'blog/blog-3.svg', excerpt: 'Cushion, drop, terrain — everything you need to know before your next pair of runners.', tags: ['Running', 'Fitness', 'Shoes'], content: ['The right running shoe prevents injury and makes every mile more enjoyable. The wrong one does the opposite.', 'First, know your terrain. Road shoes prioritize cushioning, trail shoes prioritize grip and protection, and trainers prioritize stability for the gym.', 'Next, understand cushioning and drop. More cushion suits long, easy miles; a lower drop encourages a more natural stride. There is no single right answer — it depends on your body and goals.', 'Finally, get fitted. Feet change over time, and sizing varies by brand. Leave a thumb’s width of space at the toe and test them on a short run before committing.'] },
    { id: 4, title: '5 Star Customer Reviews: What Shoppers Love Most', category: 'Community', author: 'Vendora Team', date: '2026-08-07', readTime: '5 min read', image: IMG + 'blog/blog-4.svg', excerpt: 'We analyzed thousands of reviews to find out which products earn a perfect score — and why.', tags: ['Reviews', 'Community'], content: ['Reviews are the heartbeat of a great shopping experience. We dug into the data to see what earns five stars.', 'Across categories, three themes dominate top-rated products: build quality, value for money and fast delivery.', 'Customers also rave about responsive support. A smooth return or a helpful chat goes a long way toward turning a buyer into a lifelong fan.', 'Thank you to our community for the honest feedback — it helps everyone shop smarter.'] },
    { id: 5, title: 'Sustainable Shopping: Our Promise to the Planet', category: 'Sustainability', author: 'Elena Fischer', date: '2026-07-30', readTime: '6 min read', image: IMG + 'blog/blog-5.svg', excerpt: 'How we are reducing packaging, sourcing responsibly and helping you shop with a lighter footprint.', tags: ['Sustainability', 'Ethics'], content: ['Sustainability is not a marketing buzzword for us — it is an operating principle.', 'We are cutting single-use packaging, consolidating shipments and partnering with brands that meet strict sourcing standards.', 'You can help too: choose quality over quantity, recycle packaging, and use our consolidated shipping option at checkout.', 'Small choices add up. Together we can make commerce a force for good.'] },
    { id: 6, title: 'Fast & Free Shipping: Understanding Delivery Times', category: 'Shipping', author: 'Marcus Lee', date: '2026-07-22', readTime: '4 min read', image: IMG + 'blog/blog-6.svg', excerpt: 'A clear breakdown of our shipping tiers, cut-off times and how to track your order.', tags: ['Shipping', 'Help'], content: ['Nobody likes guessing when a package will arrive. Here is exactly how our shipping works.', 'Standard delivery arrives in 3–5 business days and is free over $75. Express ships in 1–2 days, and same-day is available in select cities.', 'Orders placed before 2 PM are dispatched the same day. You will get a tracking link the moment your parcel leaves our warehouse.'] },
    { id: 7, title: 'Home Refresh: Small Changes, Big Impact', category: 'Home & Living', author: 'Sophia Reyes', date: '2026-07-15', readTime: '7 min read', image: IMG + 'blog/blog-7.svg', excerpt: 'You do not need a renovation. These affordable tweaks make your space feel brand new.', tags: ['Home', 'Decor'], content: ['Refreshing your home does not require a big budget — just a few well-chosen changes.', 'Start with lighting. A warm lamp and a dimmer switch instantly change the mood of a room.', 'Add texture with a chunky throw and a few cushions, then bring in scent with a candle trio. These layered touches make a space feel considered and cozy.', 'Finally, declutter. A clear surface does more for how a room feels than any accessory.'] },
    { id: 8, title: 'Skincare 101: Building a Routine That Delivers', category: 'Beauty', author: 'Amara Okafor', date: '2026-07-08', readTime: '9 min read', image: IMG + 'blog/blog-8.svg', excerpt: 'Cleanse, treat, moisturize, protect. The simple four-step routine dermatologists recommend.', tags: ['Beauty', 'Skincare'], content: ['Great skin is built on consistency, not complexity. A simple routine done daily beats an elaborate one done occasionally.', 'Step one: cleanse gently morning and night. Step two: treat with a targeted serum — hyaluronic acid for hydration, vitamin C for brightness.', 'Step three: moisturize to lock everything in. Step four (morning only): SPF. Sunscreen is the single most effective anti-aging product you can use.', 'Give any new routine at least six weeks before judging results, and introduce one new product at a time.'] }
  ];

  /* ---------- Testimonials ---------- */
  var TESTIMONIALS = [
    { name: 'Emily Carter', role: 'Verified Buyer', avatar: IMG + 'users/user-1.svg', rating: 5, text: 'The quality blew me away. My headphones arrived in two days, perfectly packaged, and the sound is incredible. Vendora is now my go-to store.' },
    { name: 'James Osei', role: 'Loyal Customer', avatar: IMG + 'users/user-2.svg', rating: 5, text: 'I have ordered over a dozen times and every experience has been smooth. Easy returns, fair prices and genuinely helpful support.' },
    { name: 'Maria Lopez', role: 'Verified Buyer', avatar: IMG + 'users/user-3.svg', rating: 4, text: 'Great selection and fast shipping. The website makes it easy to compare products, and checkout takes seconds. Highly recommend.' },
    { name: 'Liam Novak', role: 'Tech Enthusiast', avatar: IMG + 'users/user-4.svg', rating: 5, text: 'Bought a laptop and a smartwatch here. Both authentic, both at better prices than anywhere else I checked. Five stars.' },
    { name: 'Aisha Bello', role: 'Verified Buyer', avatar: IMG + 'users/user-5.svg', rating: 5, text: 'The clothing fits true to size and the fabric feels premium. Returns were painless when I needed to swap a size.' },
    { name: 'Tom Fischer', role: 'Fitness Coach', avatar: IMG + 'users/user-6.svg', rating: 4, text: 'Solid sports gear at honest prices. My dumbbell set is rugged and space-saving — exactly what my home gym needed.' }
  ];

  /* ---------- Team ---------- */
  var TEAM = [
    { name: 'Alexandra Chen', role: 'Founder & CEO', avatar: IMG + 'users/user-7.svg' },
    { name: 'David Moreau', role: 'Head of Design', avatar: IMG + 'users/user-8.svg' },
    { name: 'Priya Nair', role: 'Chief Technology Officer', avatar: IMG + 'users/user-9.svg' },
    { name: 'Samuel Adeyemi', role: 'Customer Experience', avatar: IMG + 'users/user-10.svg' }
  ];

  /* ---------- Demo orders (account history) ---------- */
  var ORDERS = [
    {
      id: 'VDR-2026-00842', date: '2026-08-30', status: 'delivered', statusLabel: 'Delivered', payment: 'Paid', paymentMethod: 'Credit Card',
      total: 349.97, shipping: 0, tax: 24.50, items: [
        { productId: 1, qty: 1, price: 199.99 }, { productId: 5, qty: 1, price: 79.99 }, { productId: 8, qty: 1, price: 59.99 }
      ],
      shippingAddress: { name: 'John Carter', phone: '+1 (555) 019-2837', line1: '24 Market Street, Apt 5B', city: 'San Francisco', state: 'CA', zip: '94103', country: 'United States' },
      timeline: [
        { label: 'Order placed', desc: 'Aug 30, 2026 · 10:24 AM', state: 'done' },
        { label: 'Payment confirmed', desc: 'Aug 30, 2026 · 10:26 AM', state: 'done' },
        { label: 'Shipped', desc: 'Aug 31, 2026 · 2:10 PM', state: 'done' },
        { label: 'Out for delivery', desc: 'Sep 2, 2026 · 8:45 AM', state: 'done' },
        { label: 'Delivered', desc: 'Sep 2, 2026 · 1:30 PM', state: 'done' }
      ]
    },
    {
      id: 'VDR-2026-00791', date: '2026-08-24', status: 'shipped', statusLabel: 'Shipped', payment: 'Paid', paymentMethod: 'PayPal',
      total: 159.98, shipping: 0, tax: 11.20, items: [
        { productId: 15, qty: 1, price: 119.99 }, { productId: 50, qty: 1, price: 24.99 }, { productId: 33, qty: 1, price: 15.00 }
      ],
      shippingAddress: { name: 'John Carter', phone: '+1 (555) 019-2837', line1: '24 Market Street, Apt 5B', city: 'San Francisco', state: 'CA', zip: '94103', country: 'United States' },
      timeline: [
        { label: 'Order placed', desc: 'Aug 24, 2026 · 4:02 PM', state: 'done' },
        { label: 'Payment confirmed', desc: 'Aug 24, 2026 · 4:03 PM', state: 'done' },
        { label: 'Shipped', desc: 'Aug 26, 2026 · 9:15 AM', state: 'done' },
        { label: 'Out for delivery', desc: 'Expected Sep 5, 2026', state: 'active' },
        { label: 'Delivered', desc: 'Pending', state: '' }
      ]
    },
    {
      id: 'VDR-2026-00733', date: '2026-08-18', status: 'processing', statusLabel: 'Processing', payment: 'Pending', paymentMethod: 'Cash on Delivery',
      total: 1428.99, shipping: 0, tax: 99.99, items: [
        { productId: 3, qty: 1, price: 1299.00 }, { productId: 36, qty: 1, price: 59.99 }, { productId: 39, qty: 1, price: 29.99 }
      ],
      shippingAddress: { name: 'John Carter', phone: '+1 (555) 019-2837', line1: '24 Market Street, Apt 5B', city: 'San Francisco', state: 'CA', zip: '94103', country: 'United States' },
      timeline: [
        { label: 'Order placed', desc: 'Aug 18, 2026 · 11:40 AM', state: 'done' },
        { label: 'Processing', desc: 'Preparing your items', state: 'active' },
        { label: 'Shipped', desc: 'Pending', state: '' },
        { label: 'Delivered', desc: 'Pending', state: '' }
      ]
    },
    {
      id: 'VDR-2026-00654', date: '2026-08-05', status: 'delivered', statusLabel: 'Delivered', payment: 'Paid', paymentMethod: 'Credit Card',
      total: 244.96, shipping: 0, tax: 17.15, items: [
        { productId: 22, qty: 1, price: 299.00 }
      ],
      shippingAddress: { name: 'John Carter', phone: '+1 (555) 019-2837', line1: '24 Market Street, Apt 5B', city: 'San Francisco', state: 'CA', zip: '94103', country: 'United States' },
      timeline: [
        { label: 'Order placed', desc: 'Aug 5, 2026 · 9:12 AM', state: 'done' },
        { label: 'Shipped', desc: 'Aug 6, 2026 · 3:30 PM', state: 'done' },
        { label: 'Delivered', desc: 'Aug 8, 2026 · 12:05 PM', state: 'done' }
      ]
    },
    {
      id: 'VDR-2026-00588', date: '2026-07-27', status: 'cancelled', statusLabel: 'Cancelled', payment: 'Refunded', paymentMethod: 'Credit Card',
      total: 89.98, shipping: 5.99, tax: 6.30, items: [
        { productId: 27, qty: 1, price: 79.99 }
      ],
      shippingAddress: { name: 'John Carter', phone: '+1 (555) 019-2837', line1: '24 Market Street, Apt 5B', city: 'San Francisco', state: 'CA', zip: '94103', country: 'United States' },
      timeline: [
        { label: 'Order placed', desc: 'Jul 27, 2026 · 6:45 PM', state: 'done' },
        { label: 'Cancelled', desc: 'Jul 28, 2026 · 10:00 AM', state: 'done' }
      ]
    }
  ];

  /* ---------- Demo customer (for account pages) ---------- */
  var CUSTOMER = {
    firstName: 'John', lastName: 'Carter', email: 'john.carter@example.com', phone: '+1 (555) 019-2837',
    dob: '1992-04-18', avatar: IMG + 'users/user-11.svg', memberSince: 'March 2024'
  };

  var ADDRESSES = [
    { id: 1, type: 'shipping', label: 'Home', isDefault: true, name: 'John Carter', phone: '+1 (555) 019-2837', line1: '24 Market Street, Apt 5B', line2: '', city: 'San Francisco', state: 'CA', zip: '94103', country: 'United States' },
    { id: 2, type: 'billing', label: 'Office', isDefault: true, name: 'John Carter', phone: '+1 (555) 019-2837', line1: '500 Howard Ave, Floor 3', line2: 'Vendora Labs', city: 'San Francisco', state: 'CA', zip: '94105', country: 'United States' }
  ];

  /* ---------- Public API ---------- */
  global.VendoraDB = {
    IMG: IMG,
    colors: COLORS,
    categories: CATEGORIES,
    categoryByKey: function (k) { return CAT_BY_KEY[k]; },
    brands: BRANDS,
    products: PRODUCTS,
    productById: function (id) {
      id = parseInt(id, 10);
      for (var i = 0; i < PRODUCTS.length; i++) if (PRODUCTS[i].id === id) return PRODUCTS[i];
      return null;
    },
    productBySlug: function (slug) {
      for (var i = 0; i < PRODUCTS.length; i++) if (PRODUCTS[i].slug === slug) return PRODUCTS[i];
      return null;
    },
    blog: BLOG_POSTS,
    blogById: function (id) { id = parseInt(id, 10); for (var i = 0; i < BLOG_POSTS.length; i++) if (BLOG_POSTS[i].id === id) return BLOG_POSTS[i]; return null; },
    testimonials: TESTIMONIALS,
    team: TEAM,
    orders: ORDERS,
    orderById: function (id) { for (var i = 0; i < ORDERS.length; i++) if (ORDERS[i].id === id) return ORDERS[i]; return null; },
    customer: CUSTOMER,
    addresses: ADDRESSES,
    // Query helpers
    byCategory: function (key) { return PRODUCTS.filter(function (p) { return p.category === key; }); },
    byBrand: function (name) { return PRODUCTS.filter(function (p) { return p.brand === name; }); },
    newArrivals: function (n) { var l = PRODUCTS.filter(function (p) { return p.isNew; }); return n ? l.slice(0, n) : l; },
    bestSellers: function (n) { var l = PRODUCTS.slice().sort(function (a, b) { return b.reviews - a.reviews; }); return n ? l.slice(0, n) : l; },
    featured: function (n) { var l = PRODUCTS.filter(function (p) { return p.isFeatured; }); return n ? l.slice(0, n) : l; },
    deals: function (n) { var l = PRODUCTS.filter(function (p) { return p.isDeal; }).sort(function (a, b) { return b.discount - a.discount; }); return n ? l.slice(0, n) : l; },
    topRated: function (n) { var l = PRODUCTS.slice().sort(function (a, b) { return b.rating - a.rating; }); return n ? l.slice(0, n) : l; },
    related: function (product, n) {
      n = n || 4;
      return PRODUCTS.filter(function (p) { return p.id !== product.id && p.category === product.category; })
        .concat(PRODUCTS.filter(function (p) { return p.id !== product.id && p.category !== product.category; }))
        .slice(0, n);
    },
    search: function (q) {
      q = (q || '').toLowerCase().trim();
      if (!q) return PRODUCTS;
      return PRODUCTS.filter(function (p) {
        return (p.name + ' ' + p.brand + ' ' + p.categoryName + ' ' + p.short).toLowerCase().indexOf(q) !== -1;
      });
    }
  };
})(window);
