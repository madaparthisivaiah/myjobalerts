@extends('layouts.app')

@section('title', 'Jobs in India - Latest Job Vacancies and Careers | MyJobAlerts')

@section('meta_description', 'Discover the latest jobs in India by company, location and job title. Search thousands of
job opportunities and apply directly through the original job listing.')

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


                {{-- SEARCH --}}

                <form action="{{ route('jobs.index') }}" method="GET" class="hero-search">

                    <div class="row g-2">


                        {{-- KEYWORD --}}

                        <div class="col-lg-5">

                            <div class="search-input">

                                <i class="bi bi-search"></i>

                                <input type="text" name="keyword" class="form-control"
                                    placeholder="Job title, keyword or company" value="{{ request('keyword') }}">

                            </div>

                        </div>


                        {{-- LOCATION --}}

                        <div class="col-lg-4">

                            <div class="search-input">

                                <i class="bi bi-geo-alt"></i>

                                <input type="text" name="location" class="form-control"
                                    placeholder="City, state or remote" value="{{ request('location') }}">

                            </div>

                        </div>


                        {{-- SEARCH BUTTON --}}

                        <div class="col-lg-3">

                            <button type="submit" class="btn btn-primary search-btn w-100">

                                <i class="bi bi-search me-1"></i>

                                Search Jobs

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</section>



{{-- =========================================================
     LATEST JOBS
========================================================= --}}

<section class="section-padding">

    <div class="container">


        {{-- SECTION HEADER --}}

        <div class="row align-items-end mb-4">

            <div class="col-lg-8">

                <span class="section-label">

                    <i class="bi bi-lightning-charge-fill me-1"></i>

                    LATEST JOBS

                </span>

                <h2 class="fw-bold mt-2 mb-2">

                    Latest Job Opportunities

                </h2>

                <p class="text-secondary mb-0">

                    Explore the latest jobs available from companies hiring
                    now.

                </p>

            </div>


            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">

                <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary">

                    Explore All Jobs

                    <i class="bi bi-arrow-right ms-1"></i>

                </a>

            </div>

        </div>



        {{-- =================================================
             JOB CARDS
        ================================================== --}}

        <div class="row g-4">

            @forelse($latestJobs as $job)

            {{-- TWO JOBS PER ROW --}}
            <div class="col-12 col-md-6">

                <article class="search-job-card h-100">


                    {{-- LOGO --}}
                    <div class="job-logo-wrapper">

                        @if($job->logo)

                        <img src="{{ $job->logo }}" alt="{{ $job->company }}" class="job-company-logo" loading="lazy">

                        @else

                        <div class="job-company-logo-placeholder">

                            {{ strtoupper(
                                            substr(
                                                $job->company ?: $job->title,
                                                0,
                                                1
                                            )
                                        ) }}

                        </div>

                        @endif

                    </div>


                    {{-- JOB CONTENT --}}
                    <div class="search-job-content">


                        {{-- JOB TITLE --}}
                        <h3 class="mb-2">

                            <a href="{{ url('viewjob/' . $job->slug) }}" class="text-decoration-none text-dark">
                                {{ $job->title }}
                            </a>

                        </h3>


                        {{-- COMPANY --}}
                        @if($job->company)

                        <div class="company-name mb-2">

                            <i class="bi bi-building me-1"></i>

                            {{ $job->company }}

                        </div>

                        @endif


                        {{-- JOB META --}}
                        <div class="job-meta">


                            {{-- LOCATION --}}
                            @if($job->location)

                            <span>

                                <i class="bi bi-geo-alt"></i>

                                {{ $job->location }}

                            </span>

                            @endif


                            {{-- JOB TYPE --}}
                            @if($job->job_type)

                            <span>

                                <i class="bi bi-briefcase"></i>

                                {{ $job->job_type }}

                            </span>

                            @endif


                            {{-- SALARY --}}
                            @if($job->salary)

                            @php

                            $salary = trim(
                            $job->salary
                            );

                            @endphp


                            @if(
                            $salary !== '0.000000 - 0.000000' &&
                            $salary !== '0 - 0'
                            )

                            <span>

                                <i class="bi bi-currency-rupee"></i>

                                {{ $salary }}

                            </span>

                            @endif

                            @endif

                        </div>


                        {{-- JOB SNIPPET --}}
                        @if($job->snippet)

                        <div class="mt-2 text-muted small">

                            {!! Str::limit(
                            strip_tags($job->snippet),
                            180
                            ) !!}

                        </div>

                        @endif


                        {{-- POSTED DATE --}}
                        @if(!is_null($job->age_days))

                        <div class="job-tags mt-2">

                            <span class="posted-badge">

                                @if($job->age_days === 0)

                                Posted today

                                @elseif($job->age_days === 1)

                                Posted yesterday

                                @else

                                Posted
                                {{ $job->age_days }}
                                days ago

                                @endif

                            </span>

                        </div>

                        @endif
                        {{-- VIEW JOB + QUICK APPLY --}} <div class="d-flex gap-2 mt-3"> {{-- VIEW JOB --}} <a
                                href="{{ url('viewjob/' . $job->slug) }}"
                                class="btn btn-primary btn-sm flex-grow-1 fw-semibold"> <i class="bi bi-eye me-1"></i>
                                View Job </a> {{-- QUICK APPLY --}}
                            @if(!empty($job->job_url)) <a href="{{ $job->job_url }}" target="_blank"
                                rel="nofollow sponsored" class="btn btn-success btn-sm flex-grow-1 fw-semibold">
                                <i class="bi bi-send me-1"></i> Quick Apply <i
                                    class="bi bi-box-arrow-up-right ms-1"></i> </a> @endif </div>

                    </div>

                </article>

            </div>

            @empty


            {{-- NO JOBS --}}
            <div class="col-12">

                <div class="job-alert-card">

                    <div class="job-alert-icon">

                        <i class="bi bi-search"></i>

                    </div>


                    <h4>
                        No jobs found
                    </h4>


                    <p>

                        We couldn't find jobs matching your search.

                        Try another keyword or location.

                    </p>


                    <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                        Browse All Jobs
                    </a>

                </div>

            </div>

            @endforelse

        </div>
    </div>

</section>

{{-- =========================================================
     JOBS BY LOCATION
========================================================= --}}

<section class="mt-1 bg-light-subtle">

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

                <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary">

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
            [
            'location' => $location['slug']
            ]
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

                <a href="{{ $locationUrl }}" class="state-card h-100 d-flex align-items-center
                               text-decoration-none p-3
                               bg-white bg-opacity-75
                               border rounded-4 shadow-sm">


                    {{-- ICON --}}

                    <div class="{{ $bgClass }} rounded-4 p-2 flex-shrink-0">

                        <i class="bi bi-geo-alt-fill {{ $iconClass }}"></i>

                    </div>


                    {{-- INFO --}}

                    <div class="ms-3 flex-grow-1 min-w-0">

                        <h3 class="h6 mb-1 fw-bold text-dark text-truncate">

                            {{ $locationName }}

                        </h3>

                        <span class="small text-secondary">

                            {{ number_format($locationJobs) }}

                            {{ $locationJobs == 1 ? 'job' : 'jobs' }}

                        </span>

                    </div>


                    {{-- ACTION --}}

                    <div class="ms-2 flex-shrink-0">

                        <span class="d-inline-flex align-items-center gap-1
                                         text-primary fw-semibold small">

                            View

                            <span class="bg-primary-subtle rounded-circle
                                             d-inline-flex align-items-center
                                             justify-content-center px-2 py-1">

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


        {{-- SECTION HEADER --}}

        <div class="text-center section-heading">

            <span class="section-label">

                <i class="bi bi-buildings-fill me-1"></i>

                TOP EMPLOYERS

            </span>

            <h2>

                Companies hiring now

            </h2>

        </div>



        {{-- COMPANY CARDS --}}

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
            [
            'company' => $company['slug']
            ]
            );


            $logo = companyLogo($companyName);

            @endphp


            <div class="col-lg-3 col-md-6">

                <div class="company-card h-100 d-flex flex-column">


                    {{-- COMPANY LOGO --}}

                    <div class="company-logo d-flex align-items-center
                                    justify-content-center mb-3">

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

                        <a href="{{ $companyUrl }}" class="state-card d-flex align-items-center
                                       justify-content-between
                                       text-decoration-none px-3 py-2">

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

        <a href="{{ route('jobs.index') }}" class="btn btn-light btn-lg px-4">

            <i class="bi bi-search me-1"></i>

            Explore Jobs

        </a>

    </div>

</section>


@endsection