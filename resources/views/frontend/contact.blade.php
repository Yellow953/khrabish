@extends('frontend.layouts.app')

@section('title', 'Contact')

@section('content')
    <div class="container pb-5">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="text-primary mb-3 fw-bold text-shadow">Contact Us</h1>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-6 pb-5 pb-md-0">
                <div class="card rounded p-5 text-center bg-secondary-lighter">
                    <h2 class="mb-4 animate-on-scroll slide-left text-white fw-bold text-shadow-secondary">Contact Info
                    </h2>
                    <div class="mb-4 animate-on-scroll slide-left">
                        <h5><i class="fa-solid fa-location-dot text-tertiary"></i></h5>
                        <p class="text-white">Lebanon, Mont-Liban , Jdeideh</p>
                    </div>
                    <div class="mb-4 animate-on-scroll slide-left">
                        <h5><i class="fa fa-clock text-tertiary"></i></h5>
                        <p class="text-white">24/7</p>
                    </div>
                    <div class="mb-4 animate-on-scroll slide-left">
                        <h5><i class="fa fa-phone text-tertiary"></i></h5>
                        <p class="text-white">+961 70 231 446</p>
                    </div>
                    <div class="d-flex mt-3 justify-content-center">
                        <div class="social-icons animate-on-scroll slide-up text-center">
                            <h3 class="fw-bold mb-3 text-tertiary">Follow Us</h3>

                            <a href="http://www.facebook.com/khrabish.store" target="blank"
                                class="social-icon text-decoration-none text-tertiary"><i
                                    class="fa-brands fa-facebook fs-3 mx-1"></i></a>
                            <a href="http://www.instagram.com/khrabish.store" target="blank"
                                class="social-icon text-decoration-none text-tertiary"><i
                                    class="fa-brands fa-instagram fs-3 mx-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card rounded p-5 text-center bg-tertiary">
                    <h2 class="mb-4 animate-on-scroll slide-right text-white fw-bold text-shadow-tertiary">
                        Send Us A Message
                    </h2>
                    <form class="form" action="{{ route('contact.send') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3 animate-on-scroll slide-right text-white">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control input" id="name" required />
                        </div>
                        <div class="mb-3 animate-on-scroll slide-right text-white">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control input" id="email" required />
                        </div>
                        <div class="mb-3 animate-on-scroll slide-right text-white">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" name="phone" class="form-control input" id="phone" required />
                        </div>
                        <div class="mb-3 animate-on-scroll slide-up text-white">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control input" name="message" id="message" rows="5" required></textarea>
                        </div>
                        <div
                            class="mb-3 animate-on-scroll slide-up text-white d-flex align-items-center justify-content-center">
                            <img src="{{ asset('assets/images/spam1.png') }}" width="50" height="50">
                            <span class="fs-1 mx-1">+</span>
                            <img src="{{ asset('assets/images/spam2.png') }}" width="50" height="50">
                            <span class="fs-1 mx-1">=</span>
                            <input type="number" class="ms-3 spam-input" id="spam" name="spam" required />
                        </div>

                        <div class="y-on-hover">
                            <button type="submit" class="btn btn-primary animate-on-scroll slide-up">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection