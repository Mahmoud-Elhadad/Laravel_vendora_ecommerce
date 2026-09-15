
    <x-navbar />



        <section class="page-title-bar">
            <div class="container">
            <h1 id="page-title-text">Contact Us</h1>
            <p class="mb-0 mt-2 text-white-50">We usually reply within 24 hours.</p>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-v">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Contact</li>
                </ol>
            </nav>
            </div>
        </section>

        <main id="main-content">
            <section class="section-sm">
                <div class="container">
                <div class="row g-4">
                    <div class="col-lg-5">
                    <div class="checkout-card mb-4">
                        <h5 class="mb-3">Get in touch</h5>
                        <ul class="footer-contact">
                        <li><i class="fa-solid fa-location-dot"></i><span>1200 Market Street, Suite 400<br>San Francisco, CA 94103</span></li>
                        <li><i class="fa-solid fa-phone"></i><span>+20 1092842953 </span></li>
                        <li><i class="fa-regular fa-envelope"></i><span>mahmoudelhadad314@gmail.com</span></li>
                        <li><i class="fa-regular fa-clock"></i><span>Mon–Fri: 9am – 6pm<br>Sat: 10am – 4pm</span></li>
                        </ul>
                    </div>
                    <div class="checkout-card p-2" style="overflow: hidden">
                        <iframe style="width: 100% ; height: 100%" src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d6864.569301755015!2d31.73389023885156!3d30.654112!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sar!2seg!4v1789308889310!5m2!1sar!2seg" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                    </div>
                    </div>
                    <div class="col-lg-7">
                    <div class="checkout-card">
                        <h4 class="mb-1">Send us a message</h4>
                        <p class="text-muted mb-4">Have a question about an order, a product or a return? Fill in the form and our team will help.</p>
                        <div id="contact-success" class="alert alert-success d-none"><i class="fa-solid fa-circle-check me-1"></i> Thanks! Your message has been sent. We will get back to you shortly.</div>


                        <form id="contact-form" novalidate action="{{ route("store.message") }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                            <label class="form-label" for="contact-name">Full name</label>
                            <input type="text" class="form-control" id="contact-name" name="contact-name" placeholder="Your name" required>
                            <div class="invalid-feedback"></div>
                            </div>
                                <div class="col-md-6 mb-3">
                            <label class="form-label" for="contact-email">Email address</label>
                            <input type="email" class="form-control" id="contact-email" name="contact-email" placeholder="you@example.com" required>
                            <div class="invalid-feedback"></div>
                            </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="contact-subject">Subject</label>
                                <select class="form-select" id="contact-subject" name="contact-subject">
                                    <option value="general enquiry">General enquiry</option>
                                    <option value="order support">Order support</option>
                                    <option value="returns & refunds">Returns &amp; refunds</option>
                                    <option value="product question">Product question</option>
                                    <option value="partnership">Partnership</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="contact-message">Message</label>
                                <textarea class="form-control" id="contact-message" name="contact-message" rows="5" placeholder="Tell us how we can help (min. 10 characters)..." required></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                            <button type="submit" class="btn btn-primary-v btn-lg"><i class="fa-solid fa-paper-plane"></i> Send Message</button>
                        </form>
                    </div>
                    </div>
                </div>
                </div>
            </section>
        </main>

    <x-footer />
