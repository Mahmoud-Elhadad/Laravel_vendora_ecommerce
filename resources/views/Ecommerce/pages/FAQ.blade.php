<x-navbar />





  <section class="page-title-bar">
    <div class="container">
      <h1 id="page-title-text">Help Center</h1>
      <p class="mb-0 mt-2 text-white-50">Quick answers to the questions we hear most.</p>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-v">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">FAQ</li>
        </ol>
      </nav>
    </div>
  </section>

  <main id="main-content">
      <section class="section-sm">
        <div class="container">
          <div class="row g-4">
            <div class="col-lg-3">
              <div class="widget position-sticky" style="top:96px">
                <h6>Browse topics</h6>
                <div id="faq-cat-nav" class="faq-cat-nav nav flex-column">
                  <a href="#" class="nav-link active" data-faq="all"><i class="fa-solid fa-border-all"></i> All questions</a>
                  <a href="#" class="nav-link" data-faq="orders"><i class="fa-solid fa-box"></i> Orders</a>
                  <a href="#" class="nav-link" data-faq="shipping"><i class="fa-solid fa-truck"></i> Shipping</a>
                  <a href="#" class="nav-link" data-faq="returns"><i class="fa-solid fa-rotate-left"></i> Returns</a>
                  <a href="#" class="nav-link" data-faq="payment"><i class="fa-solid fa-credit-card"></i> Payment</a>
                  <a href="#" class="nav-link" data-faq="account"><i class="fa-regular fa-user"></i> Account</a>
                </div>
                <hr>
                <p class="text-muted small mb-2">Still need help?</p>
                <a href="{{ route("show.message") }}" class="btn btn-outline-v btn-sm btn-block">Contact support</a>
              </div>
            </div>
            <div class="col-lg-9">
              <div class="faq-group" data-group="orders">
          <h3 class="h5 mb-3">Orders</h3>
          <div class="accordion accordion-v mb-4" id="acc-orders">
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#orders-0" aria-expanded="true" aria-controls="orders-0">How do I place an order?</button>
                </h2>
                <div id="orders-0" class="accordion-collapse collapse show" data-bs-parent="#acc-orders">
                  <div class="accordion-body text-muted">Browse the catalog, add items to your cart, then proceed to checkout. Enter your shipping details, choose a delivery and payment method, and click "Place order". You will see a confirmation screen with your order number.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#orders-1" aria-expanded="false" aria-controls="orders-1">Can I change or cancel my order?</button>
                </h2>
                <div id="orders-1" class="accordion-collapse collapse" data-bs-parent="#acc-orders">
                  <div class="accordion-body text-muted">You can cancel an order from the <a>My Orders</a> page as long as it is still pending or processing. Once an order has shipped it can no longer be cancelled, but you can return it after delivery.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#orders-2" aria-expanded="false" aria-controls="orders-2">How do I track my order?</button>
                </h2>
                <div id="orders-2" class="accordion-collapse collapse" data-bs-parent="#acc-orders">
                  <div class="accordion-body text-muted">Open <a>My Orders</a> and click "Track" next to any order to see its live status and delivery timeline.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#orders-3" aria-expanded="false" aria-controls="orders-3">Do you offer gift wrapping?</button>
                </h2>
                <div id="orders-3" class="accordion-collapse collapse" data-bs-parent="#acc-orders">
                  <div class="accordion-body text-muted">Yes. Gift wrapping options are shown at checkout for eligible items, along with a personal message you can include.</div>
                </div>
              </div>
          </div>
        </div>
              <div class="faq-group" data-group="shipping">
          <h3 class="h5 mb-3">Shipping & delivery</h3>
          <div class="accordion accordion-v mb-4" id="acc-shipping">
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#shipping-0" aria-expanded="true" aria-controls="shipping-0">How much does shipping cost?</button>
                </h2>
                <div id="shipping-0" class="accordion-collapse collapse show" data-bs-parent="#acc-shipping">
                  <div class="accordion-body text-muted">Standard shipping is free on all orders over $75. Below that, standard shipping is a flat $4.99. Express delivery is $9.99.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#shipping-1" aria-expanded="false" aria-controls="shipping-1">How long does delivery take?</button>
                </h2>
                <div id="shipping-1" class="accordion-collapse collapse" data-bs-parent="#acc-shipping">
                  <div class="accordion-body text-muted">Standard delivery arrives in 4–6 business days, express in 2–3 business days, and store pickup is usually ready within 24 hours.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#shipping-2" aria-expanded="false" aria-controls="shipping-2">Do you ship internationally?</button>
                </h2>
                <div id="shipping-2" class="accordion-collapse collapse" data-bs-parent="#acc-shipping">
                  <div class="accordion-body text-muted">We currently ship to the United States, Canada, the United Kingdom, Australia and several EU countries. International delivery times vary by destination.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#shipping-3" aria-expanded="false" aria-controls="shipping-3">Can I get a PO box delivery?</button>
                </h2>
                <div id="shipping-3" class="accordion-collapse collapse" data-bs-parent="#acc-shipping">
                  <div class="accordion-body text-muted">We can only deliver to PO boxes via standard postal shipping. Express courier options require a physical street address.</div>
                </div>
              </div>
          </div>
        </div>
              <div class="faq-group" data-group="returns">
          <h3 class="h5 mb-3">Returns & refunds</h3>
          <div class="accordion accordion-v mb-4" id="acc-returns">
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#returns-0" aria-expanded="true" aria-controls="returns-0">What is your return policy?</button>
                </h2>
                <div id="returns-0" class="accordion-collapse collapse show" data-bs-parent="#acc-returns">
                  <div class="accordion-body text-muted">You can return most items within 30 days of delivery in their original, unused condition. See our <a href="returns.html">full returns policy</a> for details and exceptions.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#returns-1" aria-expanded="false" aria-controls="returns-1">How do I start a return?</button>
                </h2>
                <div id="returns-1" class="accordion-collapse collapse" data-bs-parent="#acc-returns">
                  <div class="accordion-body text-muted">Go to <a>My Orders</a>, open the order and select "Return". Print the prepaid label, pack the item and drop it at any carrier location.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#returns-2" aria-expanded="false" aria-controls="returns-2">When will I be refunded?</button>
                </h2>
                <div id="returns-2" class="accordion-collapse collapse" data-bs-parent="#acc-returns">
                  <div class="accordion-body text-muted">Refunds are issued to your original payment method within 3–5 business days after the returned item reaches our warehouse.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#returns-3" aria-expanded="false" aria-controls="returns-3">Can I exchange an item?</button>
                </h2>
                <div id="returns-3" class="accordion-collapse collapse" data-bs-parent="#acc-returns">
                  <div class="accordion-body text-muted">Yes. Exchanges for a different size or colour are free of charge subject to availability. Start an exchange the same way as a return.</div>
                </div>
              </div>
          </div>
        </div>
              <div class="faq-group" data-group="payment">
          <h3 class="h5 mb-3">Payment & security</h3>
          <div class="accordion accordion-v mb-4" id="acc-payment">
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#payment-0" aria-expanded="true" aria-controls="payment-0">What payment methods do you accept?</button>
                </h2>
                <div id="payment-0" class="accordion-collapse collapse show" data-bs-parent="#acc-payment">
                  <div class="accordion-body text-muted">We accept Visa, Mastercard, American Express, PayPal, Apple Pay and cash on delivery in select regions.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#payment-1" aria-expanded="false" aria-controls="payment-1">Is my payment information secure?</button>
                </h2>
                <div id="payment-1" class="accordion-collapse collapse" data-bs-parent="#acc-payment">
                  <div class="accordion-body text-muted">Absolutely. All transactions are encrypted and we never store your full card details on our servers.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#payment-2" aria-expanded="false" aria-controls="payment-2">Why was my card declined?</button>
                </h2>
                <div id="payment-2" class="accordion-collapse collapse" data-bs-parent="#acc-payment">
                  <div class="accordion-body text-muted">Common causes include insufficient funds, incorrect billing details or a bank block. Try another card or contact your bank.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#payment-3" aria-expanded="false" aria-controls="payment-3">Can I pay in instalments?</button>
                </h2>
                <div id="payment-3" class="accordion-collapse collapse" data-bs-parent="#acc-payment">
                  <div class="accordion-body text-muted">Select regions offer interest-free instalments at checkout through our financing partners.</div>
                </div>
              </div>
          </div>
        </div>
              <div class="faq-group" data-group="account">
          <h3 class="h5 mb-3">Your account</h3>
          <div class="accordion accordion-v mb-4" id="acc-account">
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#account-0" aria-expanded="true" aria-controls="account-0">How do I create an account?</button>
                </h2>
                <div id="account-0" class="accordion-collapse collapse show" data-bs-parent="#acc-account">
                  <div class="accordion-body text-muted">Click "Sign in" in the header, then choose "Create one free". Fill in your details and you are ready to go.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#account-1" aria-expanded="false" aria-controls="account-1">I forgot my password. What do I do?</button>
                </h2>
                <div id="account-1" class="accordion-collapse collapse" data-bs-parent="#acc-account">
                  <div class="accordion-body text-muted">Use the <a href="forgot-password.html">forgot password</a> link to receive a secure reset email.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#account-2" aria-expanded="false" aria-controls="account-2">How do I update my address book?</button>
                </h2>
                <div id="account-2" class="accordion-collapse collapse" data-bs-parent="#acc-account">
                  <div class="accordion-body text-muted">Visit <a href="addresses.html">Addresses</a> in your account to add, edit or remove saved shipping and billing addresses.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#account-3" aria-expanded="false" aria-controls="account-3">Can I delete my account?</button>
                </h2>
                <div id="account-3" class="accordion-collapse collapse" data-bs-parent="#acc-account">
                  <div class="accordion-body text-muted">Yes. Contact support and we will permanently delete your account and associated personal data within 30 days.</div>
                </div>
              </div>
          </div>
        </div>
            </div>
          </div>
        </div>
      </section>
  </main>

<x-footer />
