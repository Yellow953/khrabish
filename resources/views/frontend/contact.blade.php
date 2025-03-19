@extends('frontend.layouts.app')

@section('title', 'Contact')

@section('content')
<section class="pb-5 mt-5">
    <div class="container mt-3">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="text-primary mb-3 fw-bold text-shadow">Contact Us</h1>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-6">
                <div class="py-5 text-center">
                    <h2 class="mb-4 animate-on-scroll slide-left text-primary fw-bold text-shadow">Contact Info</h2>
                    <div class="mb-4 animate-on-scroll slide-left">
                        <h5><i class="fa-solid fa-location-dot text-secondary"></i></h5>
                        <p>Lebanon, Mont-Liban , Jdeideh</p>
                    </div>
                    <div class="mb-4 animate-on-scroll slide-left">
                        <h5><i class="fa fa-clock text-secondary"></i></h5>
                        <p>24/7</p>
                    </div>
                    <div class="mb-4 animate-on-scroll slide-left">
                        <h5><i class="fa fa-phone text-secondary"></i></h5>
                        <p>+961 70 231 446</p>
                    </div>
                    <div class="d-flex mt-3 justify-content-center">
                        <div class="social-icons animate-on-scroll slide-up text-center">
                            <h3 class="fw-bold mb-3 text-secondary">Follow Us</h3>

                            <a href="http://www.facebook.com/khrabish.store" target="blank"
                                class="social-icon text-decoration-none text-secondary"><i
                                    class="fa-brands fa-facebook fs-3 mx-1"></i></a>
                            <a href="http://www.instagram.com/khrabish.store" target="blank"
                                class="social-icon text-decoration-none text-secondary"><i
                                    class="fa-brands fa-instagram fs-3 mx-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card rounded p-5 text-center bg-primary">
                    <h2 class="mb-4 animate-on-scroll slide-right text-white fw-bold text-shadow">
                        Send Us A Message
                    </h2>
                    <form class="form" action="{{ route('contact.send') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3 animate-on-scroll slide-right text-white">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control input" id="name" required />
                        </div>
                        <div class="mb-3 animate-on-scroll slide-right text-white">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control input" id="email" required />
                        </div>
                        <div class="mb-3 animate-on-scroll slide-right text-white">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control input" id="phone" required />
                        </div>
                        <div class="mb-3 animate-on-scroll slide-up text-white">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control input" id="message" rows="5" required></textarea>
                        </div>
                        <div class="y-on-hover">
                            <button type="submit" class="btn btn-secondary animate-on-scroll slide-up">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection