@extends('layouts.app')
@section('title', 'Contact Us | MyJobAlerts')
@section('meta_description', 'Contact MyJobAlerts for questions, feedback, suggestions or support related to our job search platform and job listings.')
@section('content')
{{-- HERO --}}
<section class="bg-light border-bottom static-pages">

    <div class="container py-5">

        <div class="row align-items-center g-4">

            <div class="col-lg-7">

                <span class="badge text-bg-primary rounded-pill px-3 py-2 mb-3">
                    <i class="bi bi-chat-heart-fill me-1"></i>
                    We'd love to hear from you
                </span>

                <h1 class="display-5 fw-bold mb-3">
                    Let's talk about
                    <span class="text-primary">MyJobAlerts.in</span>
                </h1>

                <p class="lead text-white mb-4">
                    Have a question, suggestion, feedback, or found something
                    that needs our attention? Send us a message and we'll be
                    happy to hear from you.
                </p>

                <div class="d-flex flex-wrap gap-2">

                    <a href="{{ url('/jobs') }}"
                       class="btn btn-primary">

                        <i class="bi bi-search me-2"></i>
                        Browse Jobs

                    </a>

                    <a href="#contact-form"
                       class="btn btn-outline-primary">

                        <i class="bi bi-envelope me-2"></i>
                        Send Message

                    </a>

                </div>

            </div>

            <div class="col-lg-5">

                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-3 mb-4">

                            <div class="bg-primary-subtle text-primary rounded-4 p-3">

                                <i class="bi bi-headset fs-3"></i>

                            </div>

                            <div>

                                <h2 class="h5 fw-bold mb-1">
                                    Need help?
                                </h2>

                                <p class="text-secondary small mb-0">
                                    We're here to listen.
                                </p>

                            </div>

                        </div>

                        <div class="d-flex gap-3 mb-4">

                            <div class="text-primary">
                                <i class="bi bi-geo-alt-fill fs-5"></i>
                            </div>

                            <div>
                                <div class="fw-semibold">
                                    Location
                                </div>

                                <div class="text-secondary">
                                    Hyderabad, Telangana, India
                                </div>
                            </div>

                        </div>

                        <div class="d-flex gap-3 mb-4">

                            <div class="text-primary">
                                <i class="bi bi-globe2 fs-5"></i>
                            </div>

                            <div>
                                <div class="fw-semibold">
                                    Website
                                </div>

                                <div class="text-secondary">
                                    MyJobAlerts.in
                                </div>
                            </div>

                        </div>

                        <div class="d-flex gap-3">

                            <div class="text-primary">
                                <i class="bi bi-clock-fill fs-5"></i>
                            </div>

                            <div>
                                <div class="fw-semibold">
                                    Support
                                </div>

                                <div class="text-secondary">
                                    We aim to respond as soon as possible.
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- CONTACT SECTION --}}
<main class="container py-5">

    <div class="row g-4 align-items-stretch">

        {{-- LEFT INFORMATION --}}
        <div class="col-lg-5">

            <div class="h-100">

                <span class="badge text-bg-primary rounded-pill px-3 py-2 mb-3">
                    <i class="bi bi-info-circle-fill me-1"></i>
                    Contact Information
                </span>

                <h2 class="h3 fw-bold mb-3">
                    How can we help?
                </h2>

                <p class="text-secondary mb-4">
                    MyJobAlerts.in is built to make job discovery simpler for
                    job seekers across India. If you have feedback about our
                    website or job listings, we'd like to hear from you.
                </p>


                {{-- LOCATION --}}
                <div class="card border-0 shadow-sm rounded-4 mb-3">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-start gap-3">

                            <div class="bg-primary-subtle text-primary rounded-3 p-3">
                                <i class="bi bi-geo-alt-fill fs-5"></i>
                            </div>

                            <div>

                                <h3 class="h6 fw-bold mb-1">
                                    Visit Us
                                </h3>

                                <p class="text-secondary mb-0">
                                    Hyderabad, Telangana, India
                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- JOB DISCOVERY --}}
                <div class="card border-0 shadow-sm rounded-4 mb-3">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-start gap-3">

                            <div class="bg-success-subtle text-success rounded-3 p-3">
                                <i class="bi bi-search-heart-fill fs-5"></i>
                            </div>

                            <div>

                                <h3 class="h6 fw-bold mb-1">
                                    Job Discovery
                                </h3>

                                <p class="text-secondary mb-0">
                                    Find jobs by company, location and job title.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- FEEDBACK --}}
                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-start gap-3">

                            <div class="bg-warning-subtle text-warning rounded-3 p-3">
                                <i class="bi bi-lightbulb-fill fs-5"></i>
                            </div>

                            <div>

                                <h3 class="h6 fw-bold mb-1">
                                    Your Feedback Matters
                                </h3>

                                <p class="text-secondary mb-0">
                                    Help us improve the job search experience.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- JOB SOURCE NOTICE --}}
                <div class="alert alert-info border-0 rounded-4 mt-4 mb-0">

                    <div class="d-flex gap-3">

                        <i class="bi bi-info-circle-fill fs-5"></i>

                        <div>

                            <strong>
                                About our job listings
                            </strong>

                            <p class="small mb-0 mt-1">
                                Job listings may be sourced from external job
                                platforms. When you choose to apply, you may
                                be redirected to the original job website.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- CONTACT FORM --}}
        <div class="col-lg-7">

            <div id="contact-form"
                 class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4 p-lg-5">

                    <div class="mb-4">

                        <span class="badge text-bg-primary rounded-pill px-3 py-2 mb-3">
                            <i class="bi bi-envelope-fill me-1"></i>
                            Send a Message
                        </span>

                        <h2 class="h3 fw-bold mb-2">
                            Tell us what's on your mind
                        </h2>

                        <p class="text-secondary mb-0">
                            Fill in the form and we'll get back to you.
                        </p>

                    </div>


                    {{-- SUCCESS MESSAGE --}}
                    @if(session('success'))

                        <div class="alert alert-success border-0 rounded-4">

                            <div class="d-flex gap-2">

                                <i class="bi bi-check-circle-fill fs-5"></i>

                                <div>
                                    {{ session('success') }}
                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- VALIDATION ERRORS --}}
                    @if($errors->any())

                        <div class="alert alert-danger border-0 rounded-4">

                            <div class="d-flex gap-2">

                                <i class="bi bi-exclamation-circle-fill fs-5"></i>

                                <div>

                                    <strong>
                                        Please check the following:
                                    </strong>

                                    <ul class="mb-0 mt-2">

                                        @foreach($errors->all() as $error)

                                            <li>
                                                {{ $error }}
                                            </li>

                                        @endforeach

                                    </ul>

                                </div>

                            </div>

                        </div>

                    @endif


                    <form
                        method="POST"
                        action="{{ route('contact.submit') }}"
                    >

                        @csrf

                        <div class="row g-3">


                            {{-- NAME --}}
                            <div class="col-md-6">

                                <label
                                    for="name"
                                    class="form-label fw-semibold"
                                >
                                    Your Name
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-person text-primary"></i>
                                    </span>

                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name') }}"
                                        class="form-control border-start-0"
                                        placeholder="Enter your name"
                                        required
                                    >

                                </div>

                            </div>


                            {{-- EMAIL --}}
                            <div class="col-md-6">

                                <label
                                    for="email"
                                    class="form-label fw-semibold"
                                >
                                    Email Address
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-envelope text-primary"></i>
                                    </span>

                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        class="form-control border-start-0"
                                        placeholder="you@example.com"
                                        required
                                    >

                                </div>

                            </div>


                            {{-- SUBJECT --}}
                            <div class="col-12">

                                <label
                                    for="subject"
                                    class="form-label fw-semibold"
                                >
                                    Subject
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-chat-left-text text-primary"></i>
                                    </span>

                                    <input
                                        type="text"
                                        id="subject"
                                        name="subject"
                                        value="{{ old('subject') }}"
                                        class="form-control border-start-0"
                                        placeholder="What would you like to tell us?"
                                        required
                                    >

                                </div>

                            </div>


                            {{-- MESSAGE --}}
                            <div class="col-12">

                                <label
                                    for="message"
                                    class="form-label fw-semibold"
                                >
                                    Message
                                </label>

                                <textarea
                                    id="message"
                                    name="message"
                                    rows="4"
                                    class="form-control"
                                    placeholder="Write your message here..."
                                    required
                                >{{ old('message') }}</textarea>

                            </div>


                            {{-- PRIVACY NOTICE --}}
                            <div class="col-12">

                                <div class="bg-light rounded-4 p-3">

                                    <div class="d-flex gap-2">

                                        <i class="bi bi-shield-check text-success fs-5"></i>

                                        <small class="text-secondary">
                                            Please do not include passwords,
                                            payment information or other
                                            sensitive information in your message.
                                        </small>

                                    </div>

                                </div>

                            </div>


                            {{-- SUBMIT --}}
                            <div class="col-12">

                                <button
                                    type="submit"
                                    class="btn btn-primary px-4"
                                >

                                    <i class="bi bi-send-fill me-2"></i>

                                    Send Message

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>


    {{-- BOTTOM CTA --}}
    <div class="card border-0 shadow-sm rounded-4 mt-4 cta">

        <div class="card-body p-4 p-lg-5">

            <div class="row align-items-center g-4">

                <div class="col-lg-8">

                    <div class="d-flex align-items-start gap-3">

                        <div class="bg-primary-subtle text-primary rounded-4 p-3">

                            <i class="bi bi-question-circle-fill fs-4"></i>

                        </div>

                        <div>

                            <h2 class="h5 fw-bold mb-2">
                                Looking for a job?
                            </h2>

                            <p class="text-white mb-0">
                                Explore jobs across India by company,
                                job title, city and state directly on
                                MyJobAlerts.in.
                            </p>

                        </div>

                    </div>

                </div>

                <div class="col-lg-4 text-lg-end">

                    <a
                        href="{{ url('/jobs') }}"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-search me-2"></i>

                        Explore Jobs

                        <i class="bi bi-arrow-right ms-1"></i>

                    </a>

                </div>

            </div>

        </div>

    </div>

</main>

@endsection
