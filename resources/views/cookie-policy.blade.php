@extends('layouts.app')

@section('title', 'Cookie Policy - MyJobAlerts.in')

@section('content')

<div class="bg-light border-bottom static-pages">

    <div class="container py-5">

        <div class="text-center">

            <span class="badge text-bg-primary rounded-pill px-3 py-2 mb-3">
                <i class="bi bi-cookie me-1"></i>
                Cookies
            </span>

            <h1 class="display-6 fw-bold mb-3">
                Cookie Policy
            </h1>

            <p class="lead text-secondary mb-2">
                How cookies and similar technologies may be used on
                MyJobAlerts.in.
            </p>

            <small class="text-white-50">
                Last updated: September 4, 2026
            </small>

        </div>

    </div>

</div>


<main class="container py-5">

    <div class="row g-4">


        {{-- Sidebar --}}
        <div class="col-lg-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <h2 class="h6 fw-bold mb-3">
                        On this page
                    </h2>

                    <div class="list-group list-group-flush">

                        <a href="#what"
                           class="list-group-item list-group-item-action border-0 px-0">
                            What Are Cookies?
                        </a>

                        <a href="#use"
                           class="list-group-item list-group-item-action border-0 px-0">
                            How We Use Cookies
                        </a>

                        <a href="#types"
                           class="list-group-item list-group-item-action border-0 px-0">
                            Types of Cookies
                        </a>

                        <a href="#third-party"
                           class="list-group-item list-group-item-action border-0 px-0">
                            Third-Party Cookies
                        </a>

                        <a href="#external"
                           class="list-group-item list-group-item-action border-0 px-0">
                            External Job Websites
                        </a>

                        <a href="#control"
                           class="list-group-item list-group-item-action border-0 px-0">
                            Managing Cookies
                        </a>

                        <a href="#changes"
                           class="list-group-item list-group-item-action border-0 px-0">
                            Changes
                        </a>

                        <a href="#contact"
                           class="list-group-item list-group-item-action border-0 px-0">
                            Contact
                        </a>

                    </div>

                </div>

            </div>

        </div>


        {{-- Content --}}
        <div class="col-lg-9">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4 p-lg-5">


                    {{-- 01 --}}
                    <section id="what" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                01
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                What Are Cookies?
                            </h2>

                        </div>

                        <p class="text-secondary mb-0">
                            Cookies are small text files stored on your device
                            when you visit a website. They can help websites
                            remember information, maintain functionality and
                            understand how visitors use the service.
                        </p>

                    </section>


                    {{-- 02 --}}
                    <section id="use" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                02
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                How We Use Cookies
                            </h2>

                        </div>

                        <p class="text-secondary mb-0">
                            MyJobAlerts.in may use cookies and similar
                            technologies to support website functionality,
                            remember preferences, understand traffic and usage,
                            improve performance and help protect the website
                            from misuse.
                        </p>

                    </section>


                    {{-- 03 --}}
                    <section id="types" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-4">

                            <span class="badge text-bg-primary rounded-pill">
                                03
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                Types of Cookies
                            </h2>

                        </div>


                        <div class="row g-3">


                            {{-- Essential --}}
                            <div class="col-md-6">

                                <div class="card h-100 bg-light border-0 rounded-4">

                                    <div class="card-body p-4">

                                        <h3 class="h6 fw-bold">

                                            <i class="bi bi-check-circle-fill text-success me-2"></i>

                                            Essential Cookies

                                        </h3>

                                        <p class="text-secondary small mb-0">
                                            These may be needed for core website
                                            functionality, security and basic
                                            operation.
                                        </p>

                                    </div>

                                </div>

                            </div>


                            {{-- Analytics --}}
                            <div class="col-md-6">

                                <div class="card h-100 bg-light border-0 rounded-4">

                                    <div class="card-body p-4">

                                        <h3 class="h6 fw-bold">

                                            <i class="bi bi-bar-chart-fill text-primary me-2"></i>

                                            Analytics Cookies

                                        </h3>

                                        <p class="text-secondary small mb-0">
                                            These may help us understand website
                                            traffic and how visitors use
                                            different features.
                                        </p>

                                    </div>

                                </div>

                            </div>


                            {{-- Preferences --}}
                            <div class="col-md-6">

                                <div class="card h-100 bg-light border-0 rounded-4">

                                    <div class="card-body p-4">

                                        <h3 class="h6 fw-bold">

                                            <i class="bi bi-sliders text-warning me-2"></i>

                                            Preference Cookies

                                        </h3>

                                        <p class="text-secondary small mb-0">
                                            These may remember choices or
                                            preferences to provide a more
                                            convenient experience.
                                        </p>

                                    </div>

                                </div>

                            </div>


                            {{-- Advertising --}}
                            <div class="col-md-6">

                                <div class="card h-100 bg-light border-0 rounded-4">

                                    <div class="card-body p-4">

                                        <h3 class="h6 fw-bold">

                                            <i class="bi bi-megaphone-fill text-danger me-2"></i>

                                            Advertising Cookies

                                        </h3>

                                        <p class="text-secondary small mb-0">
                                            Where advertising services are used,
                                            they may place or access cookies
                                            according to their own policies.
                                        </p>

                                    </div>

                                </div>

                            </div>


                        </div>

                    </section>


                    {{-- 04 --}}
                    <section id="third-party" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                04
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                Third-Party Cookies
                            </h2>

                        </div>

                        <p class="text-secondary">
                            Third-party providers such as analytics, advertising,
                            security or other service providers may use cookies
                            or similar technologies when their services are
                            integrated into the website.
                        </p>

                        <div class="alert alert-info border-0 rounded-4 mb-0">

                            <i class="bi bi-info-circle-fill me-2"></i>

                            Third-party cookies are controlled by the respective
                            provider and may be subject to that provider's
                            privacy policy.

                        </div>

                    </section>


                    {{-- 05 --}}
                    <section id="external" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                05
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                External Job Websites
                            </h2>

                        </div>

                        <p class="text-secondary mb-0">
                            When you click an Apply Now button, you may leave
                            MyJobAlerts.in and visit an external job website.
                            That website may use its own cookies and tracking
                            technologies.
                        </p>

                    </section>


                    {{-- 06 --}}
                    <section id="control" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                06
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                Managing Cookies
                            </h2>

                        </div>

                        <p class="text-secondary mb-0">
                            Most browsers allow you to view, block or delete
                            cookies through their settings.
                        </p>

                        <div class="alert alert-warning border-0 rounded-4 mt-3 mb-0">

                            <i class="bi bi-exclamation-triangle-fill me-2"></i>

                            Disabling cookies may affect some features or
                            functionality of MyJobAlerts.in.

                        </div>

                    </section>


                    {{-- 07 --}}
                    <section id="changes" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                07
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                Changes to This Policy
                            </h2>

                        </div>

                        <p class="text-secondary mb-0">
                            We may update this Cookie Policy when our website,
                            services or cookie practices change. The latest
                            version will be published on this page with an
                            updated date.
                        </p>

                    </section>


                    {{-- 08 --}}
                    <section id="contact">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                08
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                Contact
                            </h2>

                        </div>

                        <p class="text-secondary mb-0">
                            If you have questions about cookies used by
                            MyJobAlerts.in, please contact us through the
                            Contact Us page.
                        </p>

                    </section>


                </div>

            </div>

        </div>

    </div>

</main>

@endsection