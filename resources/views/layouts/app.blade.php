 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        {{ $metaTitle ?? View::yieldContent('title', 'MyJobAlerts - Find Your Dream Job') }}
    </title>
    <meta
        name="description"
        content="{{ $metaDescription ?? View::yieldContent('meta_description', 'Find the latest jobs in India by job title, company, city and state. Search and discover job opportunities from leading employers and job platforms on MyJobAlerts.in.') }}"
    >
    <link
        rel="canonical"
        href="@yield('canonical', url()->current())"
    >

    <link
        rel="icon"
        type="image/x-icon"
        href="{{ asset('favicon.ico') }}"
    >

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <!-- JobBoard CSS -->
    <link
        href="{{ asset('css/jobboard.css') }}"
        rel="stylesheet"
    >

    @yield('styles')


    {{-- =========================================================
         WEBSITE JSON-LD
         Used on all pages except job detail pages
    ========================================================== --}}

    @if (request()->segment(1) !== 'viewjob')

        @php

            $jsonLd = json_encode([
                "@context" => "https://schema.org",
                "@type" => "WebSite",
                "name" => "MyJobAlerts",
                "url" => url('/'),
                "description" => "Find the latest jobs in India by job title, company, city and state.",
            ]);

        @endphp

        <script type="application/ld+json">
        {!! $jsonLd !!}
        </script>

    @endif

    {{-- =========================================================
         JOBPOSTING JSON-LD
         Used only on active job detail pages
    ========================================================== --}}

    @if (request()->segment(1) === 'viewjob' && isset($job) && $job->is_active == true)

        @php
            /*
            |--------------------------------------------------------------------------
            | Clean Description
            |--------------------------------------------------------------------------
            */

            $description = trim(
                preg_replace(
                    '/\s+/',
                    ' ',
                    strip_tags($job->snippet ?? $job->title)
                )
            );


            /*
            |--------------------------------------------------------------------------
            | Base JobPosting Schema
            |--------------------------------------------------------------------------
            */

            $jobPosting = [
                "@context" => "https://schema.org",
                "@type" => "JobPosting",

                "title" => $job->title,

                "description" => $description,

                "url" => url('/viewjob/' . $job->slug),

                "hiringOrganization" => [
                    "@type" => "Organization",
                    "name" => $job->company ?: "Company",
                ],

                "directApply" => false,
            ];


            /*
            |--------------------------------------------------------------------------
            | Date Posted
            |--------------------------------------------------------------------------
            */

            if ($job->published_at) {

                $jobPosting["datePosted"] =
                    $job->published_at->toIso8601String();

            }


            /*
            |--------------------------------------------------------------------------
            | Job Location
            |--------------------------------------------------------------------------
            */

            if (!empty($job->location)) {

                $postalAddress = [
                    "@type" => "PostalAddress",
                    "addressLocality" => trim($job->location),
                    "addressCountry" => "IN",
                ];


                /*
                |--------------------------------------------------------------------------
                | Postal Code
                |--------------------------------------------------------------------------
                */

                if (!empty($job->postcode)) {

                    $postalAddress["postalCode"] =
                        trim($job->postcode);

                }


                $jobPosting["jobLocation"] = [
                    "@type" => "Place",
                    "address" => $postalAddress,
                ];

            }


            /*
            |--------------------------------------------------------------------------
            | Employment Type
            |--------------------------------------------------------------------------
            |
            | Prefer the dedicated employment_type database field.
            |
            | Example:
            | CONTRACTOR
            | FULL_TIME
            | PART_TIME
            |
            | Fall back to job_type if employment_type is unavailable.
            |
            |--------------------------------------------------------------------------
            */

            if (!empty($job->employment_type)) {

                $jobPosting["employmentType"] =
                    trim($job->employment_type);

            } elseif (!empty($job->job_type)) {

                $jobPosting["employmentType"] =
                    trim($job->job_type);

            }


            /*
            |--------------------------------------------------------------------------
            | Salary
            |--------------------------------------------------------------------------
            |
            | Only add baseSalary when there is a valid salary value.
            |
            | Ignore:
            | 0 - 0
            | NULL
            | empty values
            |
            |--------------------------------------------------------------------------
            */

            $salaryMin = is_numeric($job->salary_min ?? null)
                ? (float) $job->salary_min
                : null;

            $salaryMax = is_numeric($job->salary_max ?? null)
                ? (float) $job->salary_max
                : null;

            $salaryCurrency = !empty($job->salary_currency)
                ? strtoupper(trim($job->salary_currency))
                : null;

            $salaryUnit = !empty($job->salary_unit)
                ? strtoupper(trim($job->salary_unit))
                : null;


            /*
            |--------------------------------------------------------------------------
            | Validate Salary
            |--------------------------------------------------------------------------
            */

            $hasValidSalary = (
                (
                    !is_null($salaryMin) &&
                    $salaryMin > 0
                )
                ||
                (
                    !is_null($salaryMax) &&
                    $salaryMax > 0
                )
            );


            /*
            |--------------------------------------------------------------------------
            | Build baseSalary
            |--------------------------------------------------------------------------
            */

            if ($hasValidSalary && $salaryCurrency) {

                $salaryValue = [
                    "@type" => "QuantitativeValue",
                ];


                /*
                |--------------------------------------------------------------------------
                | Minimum Salary
                |--------------------------------------------------------------------------
                */

                if (!is_null($salaryMin) && $salaryMin > 0) {

                    $salaryValue["minValue"] =
                        $salaryMin;

                }


                /*
                |--------------------------------------------------------------------------
                | Maximum Salary
                |--------------------------------------------------------------------------
                */

                if (!is_null($salaryMax) && $salaryMax > 0) {

                    $salaryValue["maxValue"] =
                        $salaryMax;

                }


                /*
                |--------------------------------------------------------------------------
                | Salary Unit
                |--------------------------------------------------------------------------
                */

                if ($salaryUnit) {

                    $salaryValue["unitText"] =
                        $salaryUnit;

                }


                /*
                |--------------------------------------------------------------------------
                | Base Salary
                |--------------------------------------------------------------------------
                */

                $jobPosting["baseSalary"] = [
                    "@type" => "MonetaryAmount",
                    "currency" => $salaryCurrency,
                    "value" => $salaryValue,
                ];

            }


            /*
            |--------------------------------------------------------------------------
            | Company Logo
            |--------------------------------------------------------------------------
            */

            if (!empty($job->logo)) {

                $jobPosting["hiringOrganization"]["logo"] =
                    $job->logo;

            }

        @endphp


        <script type="application/ld+json">
            {!! json_encode(
                $jobPosting,
                JSON_UNESCAPED_SLASHES |
                JSON_UNESCAPED_UNICODE |
                JSON_PRETTY_PRINT
            ) !!}
        </script>

    @endif

</head>


<body>


    <!-- =========================================
         NAVBAR
    ========================================= -->

    <nav class="navbar navbar-expand-lg navbar-dark main-navbar">

        <div class="container">

            <a
                class="navbar-brand fw-bold"
                href="{{ url('/') }}"
            >

                <span class="brand-icon">

                    <img
                        src="{{ asset('images/myjobalerts-logo.png') }}"
                        alt="MyJobAlerts Logo"
                        width="30"
                        height="30"
                    >

                </span>

                MyJobAlerts

            </a>


            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar"
                aria-controls="mainNavbar"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >

                <span class="navbar-toggler-icon"></span>

            </button>


            <div
                class="collapse navbar-collapse"
                id="mainNavbar"
            >

                <ul class="navbar-nav ms-auto align-items-lg-center">

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="{{ url('/') }}"
                        >
                            Home
                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="{{ url('/about-us') }}"
                        >
                            About Us
                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="{{ url('/jobs') }}"
                        >
                            Browse Jobs
                        </a>

                    </li>


                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">

                        <a
                            href="#"
                            class="btn btn-light post-job-btn"
                        >
                            Post a Job
                        </a>

                    </li>

                </ul>

            </div>

        </div>

    </nav>


    <!-- =========================================
         PAGE CONTENT
    ========================================= -->

    @yield('content')


    <!-- =========================================
         MODERN FOOTER
    ========================================= -->

    <footer class="site-footer">

        <div class="container">


            {{-- =====================================
                 FOOTER MAIN
            ====================================== --}}

            <div class="footer-main">

                <div class="row gy-4">


                    {{-- =================================
                         BRAND
                    ================================== --}}

                    <div class="col-lg-6 col-md-6">

                        <a
                            href="{{ url('/') }}"
                            class="footer-brand text-decoration-none"
                        >

                            <span class="footer-brand-icon">

                                <img
                                    src="{{ asset('images/myjobalerts-logo.png') }}"
                                    alt="MyJobAlerts Logo"
                                    width="34"
                                    height="34"
                                >

                            </span>

                            <span>
                                MyJobAlerts
                            </span>

                        </a>


                        <p class="footer-description">

                            Discover the latest job opportunities across India.
                            Search jobs by title, company, city and state and
                            take the next step in your career.

                        </p>

                    </div>


                    {{-- =================================
                         QUICK LINKS
                    ================================== --}}

                    <div class="col-lg-3 col-md-6">

                        <div class="footer-column">

                            <h6>
                                Explore
                            </h6>

                            <ul>

                                <li>
                                    <a href="{{ url('/') }}">
                                        Home
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ url('/jobs') }}">
                                        Browse Jobs
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ url('/about-us') }}">
                                        About Us
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ url('/contact') }}">
                                        Contact
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ url('/faqs') }}">
                                        FAQs
                                    </a>
                                </li>

                            </ul>

                        </div>

                    </div>


                    {{-- =================================
                         LEGAL
                    ================================== --}}

                    <div class="col-lg-3 col-md-6">

                        <div class="footer-column">

                            <h6>
                                Information
                            </h6>

                            <ul>

                                <li>
                                    <a href="{{ url('/privacy-policy') }}">
                                        Privacy Policy
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ url('/terms-and-conditions') }}">
                                        Terms & Conditions
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ url('/cookie-policy') }}">
                                        Cookie Policy
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ url('/disclaimer') }}">
                                        Disclaimer
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ url('/contact') }}">
                                        Support
                                    </a>
                                </li>

                            </ul>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================
                 FOOTER BOTTOM
            ====================================== --}}

            <div class="footer-bottom">

                <div class="footer-copyright">

                    <span>
                        © {{ date('Y') }} MyJobAlerts.
                    </span>

                    <span class="footer-rights">
                        All rights reserved.
                    </span>

                </div>

            </div>

        </div>

    </footer>


    <!-- Bootstrap JS -->

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

    @yield('scripts')

</body>

</html>