@extends('layouts.app')

@section('title', 'Find Your Next Job - JobBoard')

@section('content')

{{-- =========================================================
     HERO
========================================================= --}}

<section class="home-hero">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9 text-center">

                <span class="hero-label">
                    FIND YOUR NEXT OPPORTUNITY
                </span>

                <h1 class="hero-title">
                    Find a job that
                    <span>fits your life.</span>
                </h1>

                <p class="hero-description">
                    Discover thousands of jobs from companies hiring
                    talented people like you.
                </p>

                <form action="{{ route('jobs.index') }}" method="GET" class="hero-search">
                    <div class="row g-2">

                        <div class="col-lg-5">
                            <div class="search-input">
                                <i class="bi bi-search"></i>

                                <input
                                    type="text"
                                    name="keyword"
                                    class="form-control"
                                    placeholder="Job title, keyword or company"
                                    value="{{ request('keyword') }}"
                                >
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="search-input">
                                <i class="bi bi-geo-alt"></i>

                                <input
                                    type="text"
                                    name="location"
                                    class="form-control"
                                    placeholder="City, state or remote"
                                    value="{{ request('location') }}"
                                >
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <button
                                type="submit"
                                class="btn btn-primary search-btn w-100"
                            >
                                Search Jobs
                            </button>
                        </div>

                    </div>
                </form>

                <div class="popular-searches">

                    <span>Popular:</span>

                    <a href="{{ route('jobs.index', ['keyword' => 'Software Engineer']) }}">
                        Software Engineer
                    </a>

                    <a href="{{ route('jobs.index', ['keyword' => 'Marketing']) }}">
                        Marketing
                    </a>

                    <a href="{{ route('jobs.index', ['keyword' => 'Data Analyst']) }}">
                        Data Analyst
                    </a>

                    <a href="{{ route('jobs.index', ['keyword' => 'Remote']) }}">
                        Remote Jobs
                    </a>

                </div>

            </div>
        </div>
    </div>
</section>


{{-- =========================================================
     LATEST JOBS
========================================================= --}}

<section class="section-padding">

    <div class="container">

        <div class="row align-items-end mb-4">

            <div class="col-lg-8">

                <span class="section-label">
                    <i class="bi bi-briefcase-fill me-1"></i>
                    LATEST JOBS
                </span>

                <h2 class="fw-bold mt-2 mb-2">
                    Latest Job Opportunities
                </h2>

                <p class="text-secondary mb-0">
                    Explore the latest jobs available from companies hiring now.
                </p>

            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">

                <a
                    href="{{ route('jobs.index') }}"
                    class="btn btn-outline-primary"
                >
                    Explore All Jobs
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>

            </div>

        </div>


        <div class="row g-4">

            @forelse($latestJobs as $job)

                <div class="col-lg-6">

                   
                    <a href="{{ url('viewjob/' . $job->slug) }}" class="text-decoration-none text-dark">
                                        

                        <div class="job-card h-100">

                            <div class="job-card-body">

                                <h3 class="job-title">
                                    {{ $job->title }}
                                </h3>

                                @if(!empty($job->company))
                                    <div class="job-company text-secondary">
                                        <i class="bi bi-building"></i>
                                        {{ $job->company }}
                                    </div>
                                @endif

                                @if(!empty($job->location))
                                    <div class="job-location text-secondary">
                                        <i class="bi bi-geo-alt"></i>
                                        {{ $job->location }}
                                    </div>
                                @endif

                                @if($job->salary)
                            @php
                            $salary = trim($job->salary);
                            $salaryNumbers = preg_replace('/[^0-9.\-]+/', '', $salary);
                            $salaryNumbers = str_replace('--', '-', $salaryNumbers);
                            @endphp

                            @if($salary !== '0.000000 - 0.000000' && $salary !== '0 - 0')
                            <div class="job-salary text-secondary">
                                        <i class="bi bi-cash-stack"></i>
                                        {{ $job->salary }}
                                    </div>
                            @endif
                            @endif

                                

                                @if(!empty($job->job_type))
                                    <div class="job-contract text-secondary">
                                        <i class="bi bi-briefcase"></i>
                                        {{ $job->job_type }}
                                    </div>
                                @endif

                                @if(!empty($job->description))
                                    <div class="job-description">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($job->description), 150) }}
                                    </div>
                                @endif

                                @if(!empty($job->published_at))
                                    <div class="job-date">
                                        <i class="bi bi-clock"></i>

                                        {{ \Carbon\Carbon::parse($job->published_at)->diffForHumans() }}
                                    </div>
                                @endif

                            </div>

                        </div>

                    </a>

                </div>

            @empty

                <div class="col-12">

                    <div class="alert alert-light border text-center rounded-4 py-4">

                        <i class="bi bi-briefcase fs-3 d-block mb-2 text-secondary"></i>

                        <span class="text-secondary">
                            No jobs found.
                        </span>

                    </div>

                </div>

            @endforelse

        </div>

    </div>

</section>


{{-- =========================================================
     JOBS BY LOCATION
========================================================= --}}

<section class="section-padding bg-light-subtle">

    <div class="container">

        {{-- SECTION HEADER --}}

        <div class="row align-items-end mb-4">

            <div class="col-lg-8">

                <span class="section-label">

                    <i class="bi bi-geo-alt-fill me-1"></i>

                    JOBS BY LOCATION

                </span>

                <h2 class="fw-bold mt-2 mb-2">

                    Find Jobs by Popular Location in India

                </h2>

                <p class="text-secondary mb-0">

                    Explore job opportunities across popular locations
                    in India.

                </p>

            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">

                <a
                    href="{{ route('jobs.index') }}"
                    class="btn btn-outline-primary"
                >

                    Explore All Jobs

                    <i class="bi bi-arrow-right ms-1"></i>

                </a>

            </div>

        </div>


        {{-- LOCATION CARDS --}}

        <div class="row g-3">

            @forelse($locations as $key => $location)

                @php

                    $locationName = $location['name'] ?? '';

                    $locationJobs = (int) ($location['count'] ?? 0);

                    $locationUrl = route(
                        'jobs.location',
                        ['location' => $location['slug']]
                    );

                    $backgrounds = [
                        'bg-primary-subtle',
                        'bg-info-subtle',
                        'bg-success-subtle',
                        'bg-warning-subtle',
                    ];

                    $icons = [
                        'text-primary',
                        'text-info',
                        'text-success',
                        'text-warning',
                    ];

                    $bgClass = $backgrounds[
                        $key % count($backgrounds)
                    ];

                    $iconClass = $icons[
                        $key % count($icons)
                    ];

                @endphp


                <div class="col-xl-3 col-lg-4 col-md-6">

                    <a
                        href="{{ $locationUrl }}"
                        class="state-card h-100 d-flex align-items-center
                               text-decoration-none p-3
                               bg-white bg-opacity-75
                               border rounded-4 shadow-sm"
                    >

                        {{-- LOCATION ICON --}}

                        <div class="{{ $bgClass }} rounded-4 p-2 flex-shrink-0">

                            <i class="bi bi-geo-alt-fill {{ $iconClass }}"></i>

                        </div>


                        {{-- LOCATION INFO --}}

                        <div class="ms-3 flex-grow-1">

                            <h6 class="mb-1 fw-bold text-dark">

                                {{ $locationName }}

                            </h6>

                            <div class="d-flex align-items-center gap-1">

                                <span class="small text-secondary">

                                    {{ number_format($locationJobs) }}

                                    {{ $locationJobs == 1 ? 'job' : 'jobs' }}

                                </span>

                                <span class="text-secondary opacity-50">
                                    •
                                </span>

                            </div>

                        </div>


                        {{-- ACTION --}}

                        <div class="ms-2 flex-shrink-0">

                            <span
                                class="d-inline-flex align-items-center gap-1
                                       text-primary fw-semibold small"
                            >

                                View

                                <span
                                    class="bg-primary-subtle rounded-circle
                                           d-inline-flex align-items-center
                                           justify-content-center"
                                >

                                    <i class="bi bi-arrow-up-right"></i>

                                </span>

                            </span>

                        </div>

                    </a>

                </div>

            @empty

                <div class="col-12">

                    <div class="alert alert-light border text-center rounded-4 py-4">

                        <i class="bi bi-geo-alt fs-3 d-block mb-2 text-secondary"></i>

                        <span class="text-secondary">
                            No locations found.
                        </span>

                    </div>

                </div>

            @endforelse

        </div>

    </div>

</section>


{{-- =========================================================
     TOP COMPANIES
========================================================= --}}

<section class="section-padding" id="companies">

    <div class="container">

        <div class="text-center section-heading">

            <span class="section-label">
                TOP EMPLOYERS
            </span>

            <h2>
                Companies hiring now
            </h2>

        </div>


        <div class="row g-4">

            @forelse($companies as $key => $company)

                @php

                    $companyName = $company['name'] ?? '';

                    $companyJobs = (int) ($company['count'] ?? 0);

                    $initial = strtoupper(
                        substr(trim($companyName), 0, 1)
                    );

                    $companyUrl = route(
                        'jobs.company',
                        ['company' => $company['slug']]
                    );

                    $logo = companyLogo($companyName);

                @endphp


                <div class="col-lg-3 col-md-6">

                    <div class="company-card h-100 d-flex flex-column">

                        {{-- COMPANY LOGO --}}

                        <div
                            class="company-logo d-flex align-items-center
                                   justify-content-center mb-3"
                        >

                            @if($logo)

                                <img
                                    src="{{ $logo }}"
                                    alt="{{ $companyName }} logo"
                                    class="img-fluid w-auto h-auto"
                                    loading="lazy"
                                    onerror="this.style.display='none';
                                             this.nextElementSibling.classList.remove('d-none');"
                                >

                                <span class="fw-bold text-primary fs-4 d-none">
                                    {{ $initial ?: 'C' }}
                                </span>

                            @else

                                <span class="fw-bold text-primary fs-4">
                                    {{ $initial ?: 'C' }}
                                </span>

                            @endif

                        </div>


                        {{-- COMPANY NAME --}}

                        <h3 class="company-name mb-2">

                            {{ $companyName }}

                        </h3>


                        {{-- JOB COUNT --}}

                        <div class="company-jobs mb-4">

                            <span class="badge rounded-pill text-bg-light px-3 py-2">

                                <i class="bi bi-briefcase-fill me-1"></i>

                                {{ number_format($companyJobs) }}

                                {{ $companyJobs == 1 ? 'Job' : 'Jobs' }}

                            </span>

                        </div>


                        {{-- VIEW JOBS --}}

                        <div class="mt-auto">

                            <a
                                href="{{ $companyUrl }}"
                                class="state-card d-flex align-items-center
                                       justify-content-between
                                       text-decoration-none px-3 py-2"
                            >

                                <span class="fw-semibold">

                                    View Jobs

                                </span>

                                <span class="d-flex align-items-center gap-2">

                                    <span class="small text-secondary">

                                        Explore

                                    </span>

                                    <i class="bi bi-arrow-right"></i>

                                </span>

                            </a>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">

                    <div class="alert alert-light border text-center rounded-4 py-4">

                        <i class="bi bi-building fs-3 d-block mb-2 text-secondary"></i>

                        <span class="text-secondary">

                            No companies found.

                        </span>

                    </div>

                </div>

            @endforelse

        </div>

    </div>

</section>


{{-- =========================================================
     CTA
========================================================= --}}

<section class="main-cta">

    <div class="container text-center">

        <h2>
            Ready for your next opportunity?
        </h2>

        <p>
            Start exploring jobs and take the next step in your career.
        </p>

        <a
            href="{{ route('jobs.index') }}"
            class="btn btn-light btn-lg px-4"
        >
            Explore Jobs
        </a>

    </div>

</section>


{{-- =========================================================
     HOMEPAGE STYLES
========================================================= --}}

<style>

.job-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    transition: all 0.2s ease;
    overflow: hidden;
}

.job-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
}

.job-card-body {
    padding: 24px;
}

.job-title {
    font-size: 19px;
    font-weight: 600;
    line-height: 1.4;
    margin-bottom: 16px;
}

.job-company,
.job-location,
.job-salary,
.job-contract,
.job-date {
    font-size: 14px;
    margin-bottom: 8px;
}

.job-company i,
.job-location i,
.job-salary i,
.job-contract i,
.job-date i {
    margin-right: 6px;
}

.job-description {
    font-size: 14px;
    line-height: 1.6;
    margin: 16px 0;
    color: #6c757d;
}

.job-date {
    color: #888;
    margin-bottom: 16px;
}

.company-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    transition: all 0.2s ease;
}

.company-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
}

.company-icon {
    width: 55px;
    height: 55px;
    margin: 0 auto 16px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f1f3f5;
    font-size: 24px;
}

.company-name {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
}

.company-jobs {
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 15px;
}

.company-link {
    font-size: 14px;
    text-decoration: none;
}

.company-link i {
    margin-left: 4px;
}

</style>

@endsection