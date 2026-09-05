@extends('layouts.app')

@section('title', 'Disclaimer | MyJobAlerts')

@section('meta_description', 'Read the MyJobAlerts disclaimer regarding job listings, third-party sources, accuracy of information and external job application links.')

@section('content')

<div class="bg-light border-bottom static-pages">

    <div class="container py-5">

        <div class="text-center">

            <span class="badge text-bg-primary rounded-pill px-3 py-2 mb-3">
                <i class="bi bi-shield-check me-1"></i>
                Disclaimer
            </span>

            <h1 class="display-6 fw-bold mb-3">
                Disclaimer
            </h1>

            <p class="lead text-secondary mb-2">
                Important information about using MyJobAlerts.in.
            </p>

            <small class="text-secondary">
                Last updated: September 5, 2026
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

                        <a href="#general"
                           class="list-group-item list-group-item-action border-0 px-0">
                            General Disclaimer
                        </a>

                        <a href="#service"
                           class="list-group-item list-group-item-action border-0 px-0">
                            Our Service
                        </a>

                        <a href="#listings"
                           class="list-group-item list-group-item-action border-0 px-0">
                            Job Listings
                        </a>

                        <a href="#third-party"
                           class="list-group-item list-group-item-action border-0 px-0">
                            Third-Party Sources
                        </a>

                        <a href="#accuracy"
                           class="list-group-item list-group-item-action border-0 px-0">
                            Accuracy
                        </a>

                        <a href="#external"
                           class="list-group-item list-group-item-action border-0 px-0">
                            External Websites
                        </a>

                        <a href="#applications"
                           class="list-group-item list-group-item-action border-0 px-0">
                            Job Applications
                        </a>

                        <a href="#employment"
                           class="list-group-item list-group-item-action border-0 px-0">
                            Employment Outcomes
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
                    <section id="general" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                01
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                General Disclaimer
                            </h2>

                        </div>

                        <p class="text-secondary mb-0">
                            MyJobAlerts.in is a job discovery and search
                            platform designed to help users find employment
                            opportunities from various sources.
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
                            MyJobAlerts.in helps users discover job
                            opportunities by job title, company, city and
                            state.
                        </p>

                        <div class="alert alert-primary border-0 rounded-4 mb-0">

                            <i class="bi bi-info-circle-fill me-2"></i>

                            MyJobAlerts.in is not an employer, recruitment
                            agency or hiring authority for the jobs displayed
                            on the platform.

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
                            Job listings displayed on MyJobAlerts.in may be
                            obtained from external job platforms, employers,
                            recruitment sources and other third-party sources.
                        </p>

                        <p class="text-secondary mb-0">
                            Job details may include job titles, company names,
                            locations, job types, salaries, descriptions,
                            posting dates and other related information.
                        </p>

                    </section>


                    {{-- 04 --}}
                    <section id="third-party" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                04
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                Third-Party Sources
                            </h2>

                        </div>

                        <p class="text-secondary">
                            Some job information displayed on MyJobAlerts.in
                            may be supplied by third-party sources.
                        </p>

                        <div class="alert alert-warning border-0 rounded-4 mb-0">

                            <i class="bi bi-exclamation-triangle-fill me-2"></i>

                            We do not independently verify every job listing
                            and cannot guarantee that all information supplied
                            by third parties is complete or current.

                        </div>

                    </section>


                    {{-- 05 --}}
                    <section id="accuracy" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                05
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                Accuracy and Availability
                            </h2>

                        </div>

                        <p class="text-secondary">
                            We aim to provide useful and relevant job
                            information. However, job listings can change,
                            expire, become unavailable or be removed by
                            employers and external sources at any time.
                        </p>

                        <p class="text-secondary mb-0">
                            Users should verify important information,
                            including job requirements, salary, location,
                            employment conditions and application deadlines,
                            before applying.
                        </p>

                    </section>


                    {{-- 06 --}}
                    <section id="external" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                06
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                External Websites
                            </h2>

                        </div>

                        <p class="text-secondary">
                            MyJobAlerts.in may contain links or Apply Now
                            buttons that redirect users to external websites.
                        </p>

                        <div class="alert alert-info border-0 rounded-4 mb-0">

                            <i class="bi bi-box-arrow-up-right me-2"></i>

                            External websites operate independently and may
                            have their own terms, privacy policies, cookies
                            and application procedures.

                        </div>

                    </section>


                    {{-- 07 --}}
                    <section id="applications" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                07
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                Job Applications
                            </h2>

                        </div>

                        <p class="text-secondary">
                            MyJobAlerts.in generally does not process job
                            applications directly.
                        </p>

                        <p class="text-secondary mb-0">
                            When you click <strong>Apply Now</strong>, you may
                            be redirected to the original job source or another
                            external website where the application process is
                            completed.
                        </p>

                    </section>


                    {{-- 08 --}}
                    <section id="employment" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                08
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                Employment Outcomes
                            </h2>

                        </div>

                        <p class="text-secondary">
                            Displaying a job listing on MyJobAlerts.in does not
                            represent an endorsement or guarantee of employment.
                        </p>

                        <p class="text-secondary mb-0">
                            MyJobAlerts.in does not guarantee interviews,
                            selection, employment, salary, promotions or any
                            other hiring outcome. Hiring decisions are made
                            solely by the respective employers or recruiting
                            organizations.
                        </p>

                    </section>


                    {{-- 09 --}}
                    <section id="liability" class="mb-5">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                09
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                Limitation of Liability
                            </h2>

                        </div>

                        <p class="text-secondary mb-0">
                            To the extent permitted by applicable law,
                            MyJobAlerts.in will not be responsible for losses,
                            damages or consequences arising from reliance on
                            job listings, third-party information, external
                            websites, application processes or hiring decisions.
                        </p>

                    </section>


                    {{-- 10 --}}
                    <section id="changes">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <span class="badge text-bg-primary rounded-pill">
                                10
                            </span>

                            <h2 class="h4 fw-bold mb-0">
                                Changes to This Disclaimer
                            </h2>

                        </div>

                        <p class="text-secondary mb-0">
                            We may update this Disclaimer from time to time to
                            reflect changes to our services, website or
                            applicable requirements. Updated versions will be
                            published on this page with a revised effective date.
                        </p>

                    </section>


                </div>

            </div>

        </div>

    </div>

</main>

@endsection