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

                                <input type="text" name="keyword" class="form-control"
                                    placeholder="Job title, keyword or company" value="{{ request('keyword') }}">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="search-input">
                                <i class="bi bi-geo-alt"></i>

                                <input type="text" name="location" class="form-control"
                                    placeholder="City, state or remote" value="{{ request('location', $city ?? '') }}">
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <button type="submit" class="btn btn-primary search-btn w-100">
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
INDIA STATES
========================================================= --}}

<section class="section-padding bg-light-subtle">

    <div class="container">

        {{-- SECTION HEADER --}}
        <div class="row align-items-end mb-4">

            <div class="col-lg-8">

                <span class="section-label">
                    <i class="bi bi-geo-alt-fill me-1"></i>
                    JOBS BY STATE
                </span>

                <h2 class="fw-bold mt-2 mb-2">
                    Find Jobs by Popular State in India
                </h2>

                <p class="text-secondary mb-0">
                    Explore job opportunities across different states in
                    India.
                </p>

            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">

                <a href="{{ url('/jobs') }}" class="btn btn-outline-primary">
                    Explore All Jobs
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>

            </div>

        </div>


        {{-- STATE CARDS --}}
        <div class="row g-3">

            @foreach($states as $key => $state)

                @if(!empty($state['jobs']) && $state['jobs'] > 1000)

                    @php
                        $stateName = $state['name'] ?? '';
                        $stateJobs = (int) ($state['jobs'] ?? 0);

                        $stateUrl = url(
                            '/jobs/' . \Illuminate\Support\Str::slug($stateName)
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

                        $bgClass = $backgrounds[$key % count($backgrounds)];
                        $iconClass = $icons[$key % count($icons)];
                    @endphp

                    <div class="col-xl-3 col-lg-4 col-md-6">

                        <a
                            href="{{ $stateUrl }}"
                            class="state-card h-100 d-flex align-items-center
                                   text-decoration-none p-3
                                   bg-white bg-opacity-75
                                   border rounded-4 shadow-sm"
                        >

                            {{-- STATE ICON --}}
                            <div class="{{ $bgClass }} rounded-4 p-2 flex-shrink-0">

                                <i class="bi bi-geo-alt-fill {{ $iconClass }}"></i>

                            </div>


                            {{-- STATE INFO --}}
                            <div class="ms-3 flex-grow-1">

                                <h6 class="mb-1 fw-bold text-dark">
                                    {{ $stateName }}
                                </h6>

                                <div class="d-flex align-items-center gap-1">

                                    <span class="small text-secondary">
                                        {{ number_format($stateJobs) }}
                                        {{ $stateJobs == 1 ? 'job' : 'jobs' }}
                                    </span>

                                    <span class="text-secondary opacity-50">
                                        •
                                    </span>

                                </div>

                            </div>


                            {{-- ACTION --}}
                            <div class="ms-2 flex-shrink-0">

                                <span class="d-inline-flex align-items-center gap-1
                                             text-primary fw-semibold small">

                                    View

                                    <span class="bg-primary-subtle rounded-circle
                                                 d-inline-flex align-items-center
                                                 justify-content-center">

                                        <i class="bi bi-arrow-up-right"></i>

                                    </span>

                                </span>

                            </div>

                        </a>

                    </div>

                @endif

            @endforeach

        </div>

    </div>

</section>

<section class="section-padding bg-light-subtle">

    <div class="container">

        {{-- SECTION HEADER --}}
        <div class="row align-items-end mb-4">

            <div class="col-lg-8">

                <span class="section-label">
                    <i class="bi bi-geo-alt-fill me-1"></i>
                    JOBS BY CITY
                </span>

                <h2 class="fw-bold mt-2 mb-2">
                    Explore Jobs in Top Indian Cities
                </h2>

                <p class="text-secondary mb-0">
                    Find the latest opportunities in India's most active
                    job markets.
                </p>

            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">

                <a href="{{ url('/jobs') }}" class="btn btn-outline-primary">
                    Explore All Jobs
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>

            </div>

        </div>


        {{-- CITY CARDS --}}
        <div class="row g-3">

            @foreach($cities as $key => $city)

                @if(!empty($city['jobs']) && $city['jobs'] > 1000)

                    @php
                        $cityName = $city['name'] ?? '';
                        $cityJobs = (int) ($city['jobs'] ?? 0);

                        $cityUrl = url(
                            '/jobs/' . \Illuminate\Support\Str::slug($cityName)
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

                        $bgClass = $backgrounds[$key % count($backgrounds)];
                        $iconClass = $icons[$key % count($icons)];
                    @endphp

                    <div class="col-xl-3 col-lg-4 col-md-6">

                        <a
                            href="{{ $cityUrl }}"
                            class="state-card h-100 d-flex align-items-center
                                   text-decoration-none p-3
                                   bg-white bg-opacity-75
                                   border rounded-4 shadow-sm"
                        >

                            {{-- CITY ICON --}}
                            <div class="{{ $bgClass }} rounded-4 p-2 flex-shrink-0">

                                <i class="bi bi-geo-alt-fill {{ $iconClass }}"></i>

                            </div>


                            {{-- CITY INFO --}}
                            <div class="ms-3 flex-grow-1">

                                <h6 class="mb-1 fw-bold text-dark">
                                    {{ $cityName }}
                                </h6>

                                <div class="d-flex align-items-center gap-1">

                                    <span class="small text-secondary">
                                        {{ number_format($cityJobs) }}
                                        {{ $cityJobs == 1 ? 'job' : 'jobs' }}
                                    </span>

                                    <span class="text-secondary opacity-50">
                                        •
                                    </span>
                                    
                                </div>

                            </div>


                            {{-- ACTION --}}
                            <div class="ms-2 flex-shrink-0">

                                <span class="d-inline-flex align-items-center gap-1
                                             text-primary fw-semibold small">

                                    View

                                    <span class="bg-primary-subtle rounded-circle
                                                 d-inline-flex align-items-center
                                                 justify-content-center">

                                        <i class="bi bi-arrow-up-right"></i>

                                    </span>

                                </span>

                            </div>

                        </a>

                    </div>

                @endif

            @endforeach

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

            @if(!empty($company['jobs']) && $company['jobs'] > 2000)

            @php
            $companyName = $company['name'] ?? '';
            $companyJobs = (int) ($company['jobs'] ?? 0);
            $initial = strtoupper(substr(trim($companyName), 0, 1));

            $companyUrl = url(
            '/company/' . \Illuminate\Support\Str::slug($companyName) . '-jobs'
            );

            $logo = companyLogo($companyName);
            @endphp

            <div class="col-lg-3 col-md-6">

                <div class="company-card h-100 d-flex flex-column">

                    {{-- COMPANY LOGO --}}
                    <div class="company-logo d-flex align-items-center justify-content-center mb-3">

                        @if($logo)

                        <img src="{{ $logo }}" alt="{{ $companyName }} logo" class="img-fluid w-auto h-auto"
                            loading="lazy"
                            onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">

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

                        <a href="{{ $companyUrl }}"
                            class="state-card d-flex align-items-center justify-content-between text-decoration-none px-3 py-2">

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

            @endif

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

        <a href="{{ route('jobs.index') }}" class="btn btn-light btn-lg px-4">
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