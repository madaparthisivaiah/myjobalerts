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
* Only shorten the HTML
*/

if (\Illuminate\Support\Str::length($seoJobTitle) > 70) {

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
| Optional Postal Code
|--------------------------------------------------------------------------
*/

$postalCode = null;

if (!empty($job->postcode)) {

    $postalCode = trim(
        (string) $job->postcode
    );

    if ($postalCode === '') {

        $postalCode = null;

    }

}


/*
|--------------------------------------------------------------------------
| Optional Salary Fields
|--------------------------------------------------------------------------
|
| Only valid positive numeric values are used.
|
*/

$salaryMin = null;

$salaryMax = null;

$salaryCurrency = null;

$salaryUnit = null;


if (
    isset($job->salary_min) &&
    is_numeric($job->salary_min) &&
    (float) $job->salary_min > 0
) {

    $salaryMin = (float) $job->salary_min;

}


if (
    isset($job->salary_max) &&
    is_numeric($job->salary_max) &&
    (float) $job->salary_max > 0
) {

    $salaryMax = (float) $job->salary_max;

}


if (!empty($job->salary_currency)) {

    $salaryCurrency = strtoupper(
        trim(
            (string) $job->salary_currency
        )
    );

}


if (!empty($job->salary_unit)) {

    $salaryUnit = trim(
        (string) $job->salary_unit
    );

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

    $postalAddress = [
        '@type' => 'PostalAddress',
        'addressLocality' => trim($job->location),
        'addressCountry' => 'IN',
    ];


    /*
    |--------------------------------------------------------------------------
    | Add Postal Code Only If Available
    |--------------------------------------------------------------------------
    */

    if ($postalCode !== null) {

        $postalAddress['postalCode'] = $postalCode;

    }


    $jobLocation = [
        '@type' => 'Place',
        'address' => $postalAddress,
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
| Job Location Schema
|--------------------------------------------------------------------------
*/

$jobLocation = null;

if (!empty($job->location)) {

    $postalAddress = [
        '@type' => 'PostalAddress',
        'addressLocality' => trim($job->location),
        'addressCountry' => 'IN',
    ];

    /*
    |--------------------------------------------------------------------------
    | Postal Code
    |--------------------------------------------------------------------------
    */

    if (!empty($job->postcode)) {
        $postalAddress['postalCode'] = trim($job->postcode);
    }

    $jobLocation = [
        '@type' => 'Place',
        'address' => $postalAddress,
    ];

    //dd($jobLocation);
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


/*
|--------------------------------------------------------------------------
| Optional Base Salary
|--------------------------------------------------------------------------
|
| Add baseSalary only when salary_min or salary_max
| contains a valid positive numeric value.
|
*/

if (
    $salaryMin !== null ||
    $salaryMax !== null
) {

    $baseSalary = [

        '@type' => 'MonetaryAmount',

    ];


    /*
    | Currency only if available.
    */

    if (
        $salaryCurrency !== null &&
        $salaryCurrency !== ''
    ) {

        $baseSalary['currency'] = $salaryCurrency;

    }


    $salaryValue = [

        '@type' => 'QuantitativeValue',

    ];


    if ($salaryMin !== null) {

        $salaryValue['minValue'] = $salaryMin;

    }


    if ($salaryMax !== null) {

        $salaryValue['maxValue'] = $salaryMax;

    }


    /*
    | Salary unit only if available.
    */

    if (
        $salaryUnit !== null &&
        $salaryUnit !== ''
    ) {

        $salaryValue['unitText'] = strtoupper(
            $salaryUnit
        );

    }


    $baseSalary['value'] = $salaryValue;

    $jobPostingSchema['baseSalary'] = $baseSalary;

}


@endphp

@section('title', $pageTitle)

@section('meta_description', $metaDescription)

@section('canonical', $canonicalUrl)

@section('content')

{{-- =========================================================
COMPACT JOB HEADER
========================================================= --}}

<section class="bg-primary bg-gradient bg-opacity-10 border-bottom">


<div class="container py-5">

    <div class="bg-white border-0 rounded-5 shadow-lg">

        <div class="p-4 p-lg-5">

            <div class="row align-items-center g-4">


                {{-- =================================================
                     JOB INFORMATION
                ================================================== --}}

                <div class="col-lg-8">


                    {{-- Badges --}}

                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">

                        @if($isExpired)

                                <span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis px-3 py-2 fw-semibold">

                                    <i class="bi bi-x-circle me-1"></i>

                                    Job Expired

                                </span>

                            @else

                                <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis px-3 py-2 fw-semibold">

                                    <i class="bi bi-briefcase me-1"></i>

                                    Job Opportunity

                                </span>

                            @endif


                        @if(!is_null($job->age_days))

                            <span class="badge rounded-pill bg-body-secondary text-body-secondary border px-3 py-2 fw-semibold">

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

                    <h1 class="display-6 fw-bold mb-3 text-body-emphasis">

                        {{ $jobTitle }}

                    </h1>


                    {{-- =================================================
                         COMPANY / LOCATION / EMPLOYMENT / SALARY
                    ================================================== --}}

                    <div class="d-flex flex-wrap gap-3 mb-3">

                        {{-- COMPANY --}}

                        @if($job->company)

                            <div class="d-inline-flex align-items-center bg-primary-subtle border-0 rounded-pill px-3 py-2 shadow-sm">

                                <i class="bi bi-building text-primary-emphasis fs-5 me-2"></i>

                                <div>

                                    <div class="small text-primary-emphasis opacity-75 lh-1 mb-1">
                                        Company
                                    </div>

                                    <div class="fw-bold text-primary-emphasis">
                                        {{ $job->company }}
                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- LOCATION --}}

                        @if($job->location)

                            <div class="d-inline-flex align-items-center bg-info-subtle border-0 rounded-pill px-3 py-2 shadow-sm">

                                <i class="bi bi-geo-alt text-info-emphasis fs-5 me-2"></i>

                                <div>

                                    <div class="small text-info-emphasis opacity-75 lh-1 mb-1">
                                        Location
                                    </div>

                                    <div class="fw-bold text-info-emphasis">
                                        {{ $job->location }}
                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- EMPLOYMENT TYPE --}}

                        @if($job->employment_type != '')

                            <div class="d-inline-flex align-items-center bg-warning-subtle border-0 rounded-pill px-3 py-2 shadow-sm">

                                <i class="bi bi-briefcase text-warning-emphasis fs-5 me-2"></i>

                                <div>

                                    <div class="small text-warning-emphasis opacity-75 lh-1 mb-1">
                                        Employment Type
                                    </div>

                                    <div class="fw-bold text-warning-emphasis">
                                        {{ $job->employment_type }}
                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- SALARY --}}

                        @if($salaryMin !== null || $salaryMax !== null)

                            <div class="d-inline-flex align-items-center bg-success-subtle border-0 rounded-pill px-3 py-2 shadow-sm">

                                <i class="bi bi-cash-stack text-success-emphasis fs-5 me-2"></i>

                                <div>

                                    <div class="small text-success-emphasis opacity-75 lh-1 mb-1">
                                        Salary
                                    </div>

                                    <div class="fw-bold text-success-emphasis">

                                        @if($salaryMin !== null && $salaryMax !== null)

                                            {{ number_format($salaryMin, 0) }}
                                            -
                                            {{ number_format($salaryMax, 0) }}

                                        @elseif($salaryMin !== null)

                                            {{ number_format($salaryMin, 0) }}

                                        @elseif($salaryMax !== null)

                                            {{ number_format($salaryMax, 0) }}

                                        @endif

                                        @if($salaryCurrency)
                                            {{ $salaryCurrency }}
                                        @endif

                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- PAY PERIOD --}}

                        @if($salaryUnit !== null)

                            <div class="d-inline-flex align-items-center bg-primary-subtle border-0 rounded-pill px-3 py-2 shadow-sm">

                                <i class="bi bi-calendar3 text-primary-emphasis fs-5 me-2"></i>

                                <div>

                                    <div class="small text-primary-emphasis opacity-75 lh-1 mb-1">
                                        Pay Period
                                    </div>

                                    <div class="fw-bold text-primary-emphasis">
                                        {{ $salaryUnit }}
                                    </div>

                                </div>

                            </div>

                        @endif

                    </div>


                </div>


                {{-- =================================================
                     LOGO + APPLY
                ================================================== --}}

                <div class="col-lg-4">

                    <div
                        class="d-flex flex-column flex-sm-row flex-lg-column align-items-center align-items-lg-end justify-content-center gap-3">

                        {{-- COMPANY LOGO --}}

                        @php
                        $logo = companyLogo($job->company);
                        @endphp
                        @if($logo)

                            <div class="border-0 rounded-4 bg-white shadow p-2">

                                <img
                                    src="{{ $logo }}"
                                    alt="{{ $job->company ?: $jobTitle }}"
                                    class="img-fluid rounded-3"
                                    width="90"
                                    height="70"
                                >

                            </div>

                        @elseif($job->company)

                            <div
                                class="bg-primary bg-gradient bg-opacity-75 border-0 rounded-4 shadow d-flex align-items-center justify-content-center fw-bold fs-3 text-white px-4 py-3">

                                {{ strtoupper(substr(trim($job->company), 0, 1)) }}

                            </div>

                        @endif


                        {{-- HEADER APPLY BUTTON --}}

                        @if($isExpired)

                            <button
                                type="button"
                                class="btn btn-secondary rounded-pill btn-lg fw-semibold px-4"
                                disabled
                            >

                                <i class="bi bi-x-circle me-2"></i>

                                Expired

                            </button>

                        @elseif($job->job_url)

                            <a
                                href="{{ $job->job_url }}"
                                target="_blank"
                                rel="nofollow sponsored"
                                class="btn btn-primary rounded-pill btn-lg fw-semibold px-4 shadow-sm"
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
     EXPIRED JOB NOTICE
========================================================= --}}

@if($isExpired)

    <div class="container pt-4">

        <div class="alert alert-secondary bg-secondary-subtle border-0 rounded-4 shadow-sm mb-0">

            <div class="d-flex align-items-start">

                <i class="bi bi-exclamation-circle fs-5 me-3"></i>

                <div>

                    <div class="fw-semibold mb-1">

                        This job has expired

                    </div>

                    <div class="small">

                        This job opportunity is no longer available
                        for applications. You can continue browsing
                        other job opportunities on MyJobAlerts.

                    </div>

                </div>

            </div>

        </div>

    </div>

@endif

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

        <div class="bg-white border-0 rounded-5 shadow-lg p-4 p-lg-5">

            <h2 class="h4 fw-bold mb-4 pb-3 border-bottom">

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

        <div class="alert alert-primary bg-primary-subtle border-0 rounded-4 mt-4 mb-0">

            <div class="d-flex align-items-start">

                <i class="bi bi-info-circle text-primary-emphasis fs-5 me-3"></i>

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

        <div class="bg-white border-0 rounded-5 shadow-lg p-4 sticky-lg-top">          


            {{-- MINIMUM SALARY --}}

            @if($salaryMin !== null)

                <div class="mb-3 p-3 rounded-4 bg-success-subtle">

                    <div class="small text-success-emphasis opacity-75 mb-1">

                        Minimum Salary

                    </div>

                    <div class="fw-bold text-success-emphasis fs-5">

                        <i class="bi bi-cash-stack me-2"></i>

                        {{ $salaryMin }}

                        @if($salaryCurrency)

                            {{ $salaryCurrency }}

                        @endif

                    </div>

                </div>

            @endif


            {{-- MAXIMUM SALARY --}}

            @if($salaryMax !== null)

                <div class="mb-3 p-3 rounded-4 bg-success-subtle">

                    <div class="small text-success-emphasis opacity-75 mb-1">

                        Maximum Salary

                    </div>

                    <div class="fw-bold text-success-emphasis fs-5">

                        <i class="bi bi-cash-stack me-2"></i>

                        {{ $salaryMax }}

                        @if($salaryCurrency)

                            {{ $salaryCurrency }}

                        @endif

                    </div>

                </div>

            @endif


            {{-- SALARY UNIT --}}

            @if($salaryUnit !== null)

                <div class="mb-4 p-3 rounded-4 bg-primary-subtle">

                    <div class="small text-primary-emphasis opacity-75 mb-1">

                        Salary Unit

                    </div>

                    <div class="fw-bold text-primary-emphasis fs-5">

                        {{ $salaryUnit }}

                    </div>

                </div>

            @endif          

            {{-- APPLY / EXPIRED BUTTON --}}

                @if($isExpired)

                    <button
                        type="button"
                        class="btn btn-secondary rounded-pill btn-lg w-100 fw-semibold"
                        disabled
                    >

                        <i class="bi bi-x-circle me-2"></i>

                        Job Expired

                    </button>

                @elseif($job->job_url)

                <a
                    href="{{ $job->job_url }}"
                    target="_blank"
                    rel="nofollow sponsored"
                    class="btn btn-primary rounded-pill btn-lg w-100 fw-semibold shadow-sm"
                >

                    Apply for this job

                    <i class="bi bi-box-arrow-up-right ms-2"></i>

                </a>

            @endif


            <hr class="my-4">


            {{-- JOB OVERVIEW --}}

            <h2 class="h5 fw-bold mb-4 pb-2 border-bottom">

                Job Overview

            </h2>


            {{-- COMPANY --}}

            @if($job->company)

                <div class="d-flex align-items-start gap-3 mb-4">

                    <div class="bg-primary-subtle text-primary-emphasis fs-5 rounded-circle p-2 lh-1">

                        <i class="bi bi-building"></i>

                    </div>

                    <div>

                        <div class="small text-body-secondary text-uppercase">

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

                    <div class="bg-info-subtle text-info-emphasis fs-5 rounded-circle p-2 lh-1">

                        <i class="bi bi-geo-alt"></i>

                    </div>

                    <div>

                        <div class="small text-body-secondary text-uppercase">

                            Location

                        </div>

                        <div class="fw-semibold">

                            {{ $job->location }}

                        </div>

                    </div>

                </div>

            @endif


            {{-- POSTAL CODE --}}

            @if($postalCode !== null)

                <div class="d-flex align-items-start gap-3 mb-4">

                    <div class="bg-secondary-subtle text-secondary-emphasis fs-5 rounded-circle p-2 lh-1">

                        <i class="bi bi-mailbox"></i>

                    </div>

                    <div>

                        <div class="small text-body-secondary text-uppercase">

                            Postal Code

                        </div>

                        <div class="fw-semibold">

                            {{ $postalCode }}

                        </div>

                    </div>

                </div>

            @endif


            {{-- JOB TYPE --}}

            @if($job->job_type)

                <div class="d-flex align-items-start gap-3 mb-4">

                    <div class="bg-warning-subtle text-warning-emphasis fs-5 rounded-circle p-2 lh-1">

                        <i class="bi bi-briefcase"></i>

                    </div>

                    <div>

                        <div class="small text-body-secondary text-uppercase">

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

                    <div class="bg-success-subtle text-success-emphasis fs-5 rounded-circle p-2 lh-1">

                        <i class="bi bi-clock"></i>

                    </div>

                    <div>

                        <div class="small text-body-secondary text-uppercase">

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

        <div class="bg-white border-0 rounded-5 shadow-lg p-4 mt-4">

            <h2 class="h5 fw-bold mb-3">

                <i class="bi bi-globe2 text-primary-emphasis bg-primary-subtle rounded-circle p-2 me-2"></i>

                Original Job Listing

            </h2>


            <p class="text-muted small mb-3">

                View the original job posting and complete your
                application on the external job platform.

            </p>


            {{-- APPLY / EXPIRED BUTTON --}}

                @if($isExpired)

                    <button
                        type="button"
                        class="btn btn-secondary rounded-pill btn-lg w-100 fw-semibold"
                        disabled
                    >

                        <i class="bi bi-x-circle me-2"></i>

                        Job Expired

                    </button>

                @elseif($job->job_url)

                <a
                    href="{{ $job->job_url }}"
                    target="_blank"
                    rel="nofollow sponsored"
                    class="fw-semibold text-decoration-none link-primary"
                >

                    View original job

                    <i class="bi bi-arrow-right ms-1"></i>

                </a>

            @endif
        </div>
    </aside>
</div>
</main>

@endsection