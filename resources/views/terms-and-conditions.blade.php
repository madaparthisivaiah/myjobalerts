@extends('layouts.app')

@section('title', 'Terms and Conditions - MyJobAlerts.in')

@section('content')

<div class="bg-light border-bottom static-pages">

    <div class="container py-5">

        <div class="text-center">

            <span class="badge text-bg-primary rounded-pill px-3 py-2 mb-3">
                <i class="bi bi-file-earmark-text me-1"></i>
                Terms
            </span>

            <h1 class="display-6 fw-bold mb-3">
                Terms and Conditions
            </h1>

            <p class="lead text-secondary mb-2">
                Please read these terms before using MyJobAlerts.in.
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

                        <a href="#acceptance"
                           class="list-group-item list-group-item-action border-0 px-0">
                            Acceptance
                        </a>

                        <a href="#service"
                           class="list-group-item list-group-item-action border-0 px-0">
                            Our Service
                        </a>

                        <a href="#listings"
                           class="list-group-item list-group-item-action border-0 px-0">
                            Job Listings
                        </a>

                        <a href="#applications"
                           class="list-group-item list-group-item-action border-0 px-0">
                            Applications
                        </a>

                        <a href="#external"
                           class="list-group-item list-group-item-action border-0 px-0">
                            External Websites
                        </a>

                        <a href="#accuracy"
                           class="list-group-item list-group-item-action border-0 px-0">
                            Accuracy
                        </a>

                        <a href="#use"
                           class="list-group-item list-group-item-action border-0 px-0">
                            Acceptable Use
                        </a>

                        <a href="#liability"
                           class="list-group-item list-group-item-action border-0 px-0">
                            Limitation of Liability
                        </a>

                        <a href="#changes"
                           class="list-group-item list-group-item-action border-0 px-0">
                            Changes
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
                    <section id="acceptance" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                01
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                Acceptance of Terms
                            </h2>

                        </div>

                        <p class="text-secondary mb-0">
                            By accessing or using MyJobAlerts.in, you agree to
                            these Terms and Conditions and any applicable policies
                            published on our website.
                        </p>

                    </section>


                    {{-- 02 --}}
                    <section id="service" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                02
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                Our Service
                            </h2>

                        </div>

                        <p class="text-secondary">
                            MyJobAlerts.in is a job discovery and search platform.
                            We help users discover employment opportunities by
                            job title, company, city and state.
                        </p>

                        <div class="alert alert-primary border-0 rounded-4 mb-0">

                            <i class="bi bi-info-circle-fill me-2"></i>

                            MyJobAlerts.in is not an employer, recruitment agency
                            or hiring authority for the jobs displayed on the
                            platform.

                        </div>

                    </section>


                    {{-- 03 --}}
                    <section id="listings" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                03
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                Job Listings
                            </h2>

                        </div>

                        <p class="text-secondary">
                            Job listings may be obtained from external job
                            platforms and other job sources.
                        </p>

                        <p class="text-secondary mb-0">
                            Listings may include job titles, company names,
                            locations, job types, salaries, descriptions and
                            posting dates.
                        </p>

                    </section>


                    {{-- 04 --}}
                    <section id="applications" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                04
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                Job Applications
                            </h2>

                        </div>

                        <p class="text-secondary">
                            MyJobAlerts.in generally does not process job
                            applications directly.
                        </p>

                        <div class="alert alert-success border-0 rounded-4 mb-0">

                            <i class="bi bi-box-arrow-up-right me-2"></i>

                            When you click <strong>Apply Now</strong>, you may
                            be redirected to the original job source where the
                            application is completed.

                        </div>

                    </section>


                    {{-- 05 --}}
                    <section id="external" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                05
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                External Websites
                            </h2>

                        </div>

                        <p class="text-secondary">
                            External websites may have their own terms, privacy
                            policies and application procedures.
                        </p>

                        <p class="text-secondary mb-0">
                            MyJobAlerts.in does not control or guarantee the
                            content, availability, privacy practices or services
                            provided by external websites.
                        </p>

                    </section>


                    {{-- 06 --}}
                    <section id="accuracy" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                06
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                Accuracy and Availability
                            </h2>

                        </div>

                        <p class="text-secondary mb-0">
                            We aim to provide useful and relevant job information,
                            but listings can change, expire or be removed by
                            employers and external sources.
                        </p>

                    </section>


                    {{-- 07 --}}
                    <section id="use" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                07
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                Acceptable Use
                            </h2>

                        </div>

                        <ul class="text-secondary">

                            <li class="mb-2">
                                Do not misuse or interfere with the website.
                            </li>

                            <li class="mb-2">
                                Do not attempt unauthorized access to systems
                                or data.
                            </li>

                            <li class="mb-2">
                                Do not use automated methods to abuse or overload
                                the service.
                            </li>

                            <li>
                                Do not use information from the website for
                                unlawful purposes.
                            </li>

                        </ul>

                    </section>


                    {{-- 08 --}}
                    <section id="liability" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                08
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                Limitation of Liability
                            </h2>

                        </div>

                        <p class="text-secondary mb-0">
                            To the extent permitted by applicable law,
                            MyJobAlerts.in will not be responsible for losses
                            arising from reliance on job listings, external
                            websites, hiring decisions, application outcomes
                            or information supplied by third parties.
                        </p>

                    </section>


                    {{-- 09 --}}
                    <section id="changes">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                09
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                Changes to These Terms
                            </h2>

                        </div>

                        <p class="text-secondary mb-0">
                            We may update these Terms and Conditions from time
                            to time. Updated terms will be published on this
                            page with a revised effective date.
                        </p>

                    </section>


                </div>

            </div>

        </div>

    </div>

</main>

@endsection