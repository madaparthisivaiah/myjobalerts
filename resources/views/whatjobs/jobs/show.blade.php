@extends('layouts.app')

    @php

        /*
        |--------------------------------------------------------------------------
        | Basic Job Data
        |--------------------------------------------------------------------------
        */

        $jobTitle = trim($job->title ?? 'Job Opportunity');

        $company = trim($job->company ?? '');

        $location = trim($job->location ?? '');


        /*
        |--------------------------------------------------------------------------
        | Clean Job Title for SEO
        |--------------------------------------------------------------------------
        */

        $seoJobTitle = trim(
            preg_replace(
                '/\s+/',
                ' ',
                strip_tags($jobTitle)
            )
        );

        /*
        * Keep the complete original job title for:
        * - H1
        * - JobPosting schema
        *
        * Only shorten the HTML <title>.
        */
        if (\Illuminate\Support\Str::length($seoJobTitle) > 65) {

            $seoJobTitle = \Illuminate\Support\Str::limit(
                $seoJobTitle,
                65,
                ''
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SEO Page Title
        |--------------------------------------------------------------------------
        */

        if ($company !== '' && $location !== '') {

            $pageTitle =
                "{$seoJobTitle} - {$company}, {$location} | MyJobAlerts";

        } elseif ($company !== '') {

            $pageTitle =
                "{$seoJobTitle} - {$company}, India | MyJobAlerts";

        } elseif ($location !== '') {

            $pageTitle =
                "{$seoJobTitle} - {$location}, India | MyJobAlerts";

        } else {

            $pageTitle =
                "{$seoJobTitle} | MyJobAlerts";
        }


        /*
        |--------------------------------------------------------------------------
        | SEO Meta Description
        |--------------------------------------------------------------------------
        |
        | Keep this clean and consistent.
        |
        | Do NOT use the WhatJobs snippet here because feed snippets
        | can contain:
        |
        | - duplicated job titles
        | - company introductions
        | - "About Company"
        | - "Dear Connections"
        | - feed formatting
        |
        |--------------------------------------------------------------------------
        */

        if ($company !== '' && $location !== '') {

            $metaDescription =
                "Find {$jobTitle} at {$company} in {$location}. Explore the job details, requirements and career opportunity, and apply through the original job listing.";

        } elseif ($company !== '') {

            $metaDescription =
                "Find {$jobTitle} at {$company} in India. Explore the job details, requirements and career opportunity, and apply through the original job listing.";

        } elseif ($location !== '') {

            $metaDescription =
                "Find {$jobTitle} jobs in {$location}, India. Explore the job details, requirements and career opportunity, and apply through the original job listing.";

        } else {

            $metaDescription =
                "Find {$jobTitle} job opportunities in India. Explore the job details, requirements and career opportunity, and apply through the original job listing.";
        }


        /*
        |--------------------------------------------------------------------------
        | Final Meta Description Cleanup
        |--------------------------------------------------------------------------
        */

        $metaDescription = html_entity_decode(
            $metaDescription,
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );

        $metaDescription = strip_tags($metaDescription);

        $metaDescription = preg_replace(
            '/\s+/',
            ' ',
            $metaDescription
        );

        $metaDescription = trim($metaDescription);


        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | Do NOT use Str::limit() here.
        |
        | This prevents Laravel from adding:
        |
        | ...
        |
        | to the end of your meta description.
        |
        |--------------------------------------------------------------------------
        */


        /*
        |--------------------------------------------------------------------------
        | Canonical URL
        |--------------------------------------------------------------------------
        */

        $canonicalUrl = url('/viewjob/' . $job->slug);


        /*
        |--------------------------------------------------------------------------
        | Salary
        |--------------------------------------------------------------------------
        */

        $salary = trim($job->salary ?? '');

        if (
            $salary === '0.000000 - 0.000000' ||
            $salary === '0 - 0'
        ) {

            $salary = '';
        }


        /*
        |--------------------------------------------------------------------------
        | Actual Posted Date
        |--------------------------------------------------------------------------
        */

        $datePosted = null;

        if (!empty($job->date_posted)) {

            $datePosted = $job->date_posted;

        } elseif (!empty($job->posted_at)) {

            $datePosted = $job->posted_at;
        }


        /*
        |--------------------------------------------------------------------------
        | Job Location Schema
        |--------------------------------------------------------------------------
        */

        $jobLocation = null;

        if (!empty($job->location)) {

            $jobLocation = [

                '@type' => 'Place',

                'address' => [

                    '@type' => 'PostalAddress',

                    'addressLocality' => trim($job->location),

                    'addressCountry' => 'IN',

                ],

            ];
        }


        /*
        |--------------------------------------------------------------------------
        | JobPosting Schema
        |--------------------------------------------------------------------------
        */

        $jobPostingSchema = [

            '@context' => 'https://schema.org',

            '@type' => 'JobPosting',

            /*
            * Use the complete original job title.
            */
            'title' => $jobTitle,

            /*
            * Keep the actual WhatJobs snippet for structured data.
            *
            * This is separate from the clean SEO meta description.
            */
            'description' => trim(
                preg_replace(
                    '/\s+/',
                    ' ',
                    strip_tags(
                        html_entity_decode(
                            $job->snippet ?? '',
                            ENT_QUOTES | ENT_HTML5,
                            'UTF-8'
                        )
                    )
                )
            ) ?: "Find {$jobTitle} at {$company} in {$location}.",

            'url' => $canonicalUrl,

        ];


        /*
        |--------------------------------------------------------------------------
        | Date Posted
        |--------------------------------------------------------------------------
        */

        if ($datePosted) {

            $jobPostingSchema['datePosted'] = $datePosted;
        }


        /*
        |--------------------------------------------------------------------------
        | Hiring Organization
        |--------------------------------------------------------------------------
        */

        if (!empty($job->company)) {

            $organization = [

                '@type' => 'Organization',

                'name' => trim($job->company),

            ];

            if (!empty($job->logo)) {

                $organization['logo'] = $job->logo;
            }

            $jobPostingSchema['hiringOrganization'] = $organization;
        }


        /*
        |--------------------------------------------------------------------------
        | Job Location
        |--------------------------------------------------------------------------
        */

        if ($jobLocation) {

            $jobPostingSchema['jobLocation'] = $jobLocation;
        }


        /*
        |--------------------------------------------------------------------------
        | Employment Type
        |--------------------------------------------------------------------------
        */

        if (!empty($job->job_type)) {

            $jobPostingSchema['employmentType'] = $job->job_type;
        }


        /*
        |--------------------------------------------------------------------------
        | Original Job URL
        |--------------------------------------------------------------------------
        */

        if (!empty($job->job_url)) {

            $jobPostingSchema['sameAs'] = $job->job_url;
        }

    @endphp


    @section('title', $pageTitle)

    @section('meta_description', $metaDescription)

    @section('canonical', $canonicalUrl)


@section('content')


{{-- =========================================================
     COMPACT JOB HEADER
========================================================= --}}

<section class="bg-light-subtle border-bottom">

    <div class="container py-4">

        <div class="bg-white border rounded-4 shadow-sm">

            <div class="p-4 p-lg-4">

                <div class="row align-items-center g-4">


                    {{-- =================================================
                         JOB INFORMATION
                    ================================================== --}}

                    <div class="col-lg-8">

                        {{-- Badges --}}

                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">

                            <span class="badge text-bg-primary px-3 py-2">

                                <i class="bi bi-briefcase me-1"></i>

                                Job Opportunity

                            </span>


                            @if(!is_null($job->age_days))

                                <span class="badge bg-light text-dark border px-3 py-2">

                                    <i class="bi bi-clock me-1"></i>

                                    @if($job->age_days === 0)

                                        Posted today

                                    @elseif($job->age_days === 1)

                                        Posted yesterday

                                    @else

                                        Posted {{ $job->age_days }} days ago

                                    @endif

                                </span>

                            @endif

                        </div>


                        {{-- Job Title --}}

                        <h1 class="h2 fw-bold mb-3">

                            {{ $jobTitle }}

                        </h1>


                        {{-- Company --}}

                        @if($job->company)

                            <div class="d-flex align-items-center mb-3">

                                <i class="bi bi-building text-primary fs-5 me-2"></i>

                                <span class="fw-semibold">

                                    {{ $job->company }}

                                </span>

                            </div>

                        @endif


                        {{-- Location / Job Type --}}

                        <div class="d-flex flex-wrap gap-3 text-muted small">

                            @if($job->location)

                                <span class="d-inline-flex align-items-center">

                                    <i class="bi bi-geo-alt text-primary me-2"></i>

                                    {{ $job->location }}

                                </span>

                            @endif


                            @if($job->job_type)

                                <span class="d-inline-flex align-items-center">

                                    <i class="bi bi-briefcase text-primary me-2"></i>

                                    {{ $job->job_type }}

                                </span>

                            @endif

                        </div>

                    </div>



                    {{-- =================================================
                         LOGO + APPLY
                    ================================================== --}}

                    <div class="col-lg-4">

                        <div class="d-flex flex-column flex-sm-row flex-lg-column align-items-center align-items-lg-end justify-content-center gap-3">


                            {{-- COMPANY LOGO --}}

                            @if($job->logo)

                                <div class="border rounded-3 bg-white p-2">

                                    <img
                                        src="{{ $job->logo }}"
                                        alt="{{ $job->company ?: $jobTitle }}"
                                        class="img-fluid"
                                        width="90"
                                        height="70"
                                    >

                                </div>

                            @elseif($job->company)

                                <div class="bg-light border rounded-3 d-flex align-items-center justify-content-center fw-bold fs-3 text-primary px-4 py-3">

                                    {{ strtoupper(substr(trim($job->company), 0, 1)) }}

                                </div>

                            @endif


                            {{-- HEADER APPLY BUTTON --}}

                            @if($job->job_url)

                                <a
                                    href="{{ $job->job_url }}"
                                    target="_blank"
                                    rel="nofollow sponsored"
                                    class="btn btn-primary btn-lg fw-semibold px-4"
                                >

                                    <i class="bi bi-send me-2"></i>

                                    Apply Now

                                    <i class="bi bi-box-arrow-up-right ms-2"></i>

                                </a>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



{{-- =========================================================
     MAIN JOB CONTENT
========================================================= --}}

<main class="container py-5">

    <div class="row g-4 g-lg-5">


        {{-- =================================================
             JOB DESCRIPTION
        ================================================== --}}

        <article class="col-lg-8">


            {{-- DESCRIPTION CARD --}}

            <div class="bg-white border rounded-4 shadow-sm p-4 p-lg-5">

                <h2 class="h4 fw-bold mb-4">

                    <i class="bi bi-file-text text-primary me-2"></i>

                    Job Description

                </h2>


                @if($job->snippet)

                    <div class="job-description">

                        {!! $job->snippet !!}

                    </div>

                @else

                    <p class="text-muted mb-0">

                        Job description is available on the employer's
                        application page.

                    </p>

                @endif

            </div>



            {{-- EXTERNAL JOB NOTICE --}}

            <div class="alert alert-light border rounded-4 mt-4 mb-0">

                <div class="d-flex align-items-start">

                    <i class="bi bi-info-circle text-primary fs-5 me-3"></i>

                    <div>

                        <div class="fw-semibold mb-1">

                            About this job listing

                        </div>

                        <div class="text-muted small">

                            This job opportunity is provided through our
                            external job listing network. MyJobAlerts helps
                            you discover job opportunities and redirects you
                            to the original listing to apply.

                        </div>

                    </div>

                </div>

            </div>

        </article>



        {{-- =================================================
             SIDEBAR
        ================================================== --}}

        <aside class="col-lg-4">


            {{-- APPLY / OVERVIEW CARD --}}

            <div class="bg-white border rounded-4 shadow-sm p-4 sticky-lg-top">


                {{-- SALARY --}}

                @if($salary)

                    <div class="mb-4">

                        <div class="small text-muted mb-1">

                            Salary

                        </div>

                        <div class="h5 fw-bold mb-0">

                            <i class="bi bi-cash-stack text-success me-2"></i>

                            {{ $salary }}

                        </div>

                    </div>

                @endif



                {{-- APPLY BUTTON --}}

                @if($job->job_url)

                    <a
                        href="{{ $job->job_url }}"
                        target="_blank"
                        rel="nofollow sponsored"
                        class="btn btn-primary btn-lg w-100 fw-semibold"
                    >

                        Apply for this job

                        <i class="bi bi-box-arrow-up-right ms-2"></i>

                    </a>

                @endif


                <hr class="my-4">



                {{-- JOB OVERVIEW --}}

                <h2 class="h5 fw-bold mb-4">

                    Job Overview

                </h2>


                {{-- COMPANY --}}

                @if($job->company)

                    <div class="d-flex align-items-start gap-3 mb-4">

                        <div class="text-primary fs-5">

                            <i class="bi bi-building"></i>

                        </div>

                        <div>

                            <div class="small text-muted">

                                Company

                            </div>

                            <div class="fw-semibold">

                                {{ $job->company }}

                            </div>

                        </div>

                    </div>

                @endif


                {{-- LOCATION --}}

                @if($job->location)

                    <div class="d-flex align-items-start gap-3 mb-4">

                        <div class="text-primary fs-5">

                            <i class="bi bi-geo-alt"></i>

                        </div>

                        <div>

                            <div class="small text-muted">

                                Location

                            </div>

                            <div class="fw-semibold">

                                {{ $job->location }}

                            </div>

                        </div>

                    </div>

                @endif


                {{-- JOB TYPE --}}

                @if($job->job_type)

                    <div class="d-flex align-items-start gap-3 mb-4">

                        <div class="text-primary fs-5">

                            <i class="bi bi-briefcase"></i>

                        </div>

                        <div>

                            <div class="small text-muted">

                                Job Type

                            </div>

                            <div class="fw-semibold">

                                {{ $job->job_type }}

                            </div>

                        </div>

                    </div>

                @endif


                {{-- POSTED --}}

                @if(!is_null($job->age_days))

                    <div class="d-flex align-items-start gap-3">

                        <div class="text-primary fs-5">

                            <i class="bi bi-clock"></i>

                        </div>

                        <div>

                            <div class="small text-muted">

                                Posted

                            </div>

                            <div class="fw-semibold">

                                @if($job->age_days === 0)

                                    Today

                                @elseif($job->age_days === 1)

                                    Yesterday

                                @else

                                    {{ $job->age_days }} days ago

                                @endif

                            </div>

                        </div>

                    </div>

                @endif

            </div>



            {{-- =================================================
                 ORIGINAL LISTING CARD
            ================================================== --}}

            <div class="bg-white border rounded-4 shadow-sm p-4 mt-4">

                <h2 class="h5 fw-bold mb-3">

                    <i class="bi bi-globe2 text-primary me-2"></i>

                    Original Job Listing

                </h2>


                <p class="text-muted small mb-3">

                    View the original job posting and complete your
                    application on the external job platform.

                </p>


                @if($job->job_url)

                    <a
                        href="{{ $job->job_url }}"
                        target="_blank"
                        rel="nofollow sponsored"
                        class="fw-semibold text-decoration-none"
                    >

                        View original job

                        <i class="bi bi-arrow-right ms-1"></i>

                    </a>

                @endif

            </div>

        </aside>

    </div>

</main>



{{-- =========================================================
     JOBPOSTING STRUCTURED DATA
========================================================= --}}

<script type="application/ld+json">
{!! json_encode(
    $jobPostingSchema,
    JSON_UNESCAPED_SLASHES |
    JSON_UNESCAPED_UNICODE |
    JSON_PRETTY_PRINT
) !!}
</script>


@endsection

