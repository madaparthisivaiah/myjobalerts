@extends('layouts.app')
@section('title', 'Jobs in India - Latest Job Vacancies and Careers | MyJobAlerts')
@section('meta_description', 'Discover the latest jobs in India by company, location, and job title. Search thousands of job opportunities and apply directly through the original job listing.')
@section('content')
{{-- =========================================================
     HERO
========================================================= --}}

<section class="home-hero py-5">

    <div class="container py-4">

        <div class="row justify-content-center">

            <div class="col-xl-9 text-center">

                <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis px-3 py-2 fw-semibold text-uppercase small">
                    FIND YOUR NEXT OPPORTUNITY
                </span>

                <h1 class="display-4 fw-bold mt-3">

                    Find a job that

                    <span class="text-primary">fits your life.</span>

                </h1>

                <p class="hero-description mt-3">

                    Discover thousands of jobs from companies hiring
                    talented people like you.

                </p>


                {{-- SEARCH --}}

                <div class="bg-white border-0 rounded-5 shadow-lg p-3 p-lg-4 mt-4">

                    <form action="{{ route('jobs.index') }}" method="GET">

                        <div class="row g-2">


                            {{-- KEYWORD --}}

                            <div class="col-lg-5">

                                <div class="input-group rounded-pill overflow-hidden shadow-sm">

                                    <span class="input-group-text bg-primary-subtle text-primary-emphasis border-0">
                                        <i class="bi bi-search"></i>
                                    </span>

                                    <input type="text" name="keyword" class="form-control border-0 bg-light"
                                        placeholder="Job title, keyword or company" value="{{ request('keyword') }}">

                                </div>

                            </div>


                            {{-- LOCATION --}}

                            <div class="col-lg-4">

                                <div class="input-group rounded-pill overflow-hidden shadow-sm">

                                    <span class="input-group-text bg-primary-subtle text-primary-emphasis border-0">
                                        <i class="bi bi-geo-alt"></i>
                                    </span>

                                    <input type="text" name="location" class="form-control border-0 bg-light"
                                        placeholder="City, state or remote" value="{{ request('location') }}">

                                </div>

                            </div>


                            {{-- SEARCH BUTTON --}}

                            <div class="col-lg-3">

                                <button type="submit" class="btn btn-primary rounded-pill shadow-sm w-100 h-100">

                                    <i class="bi bi-search me-1"></i>

                                    Search Jobs

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</section>



{{-- =========================================================
     LATEST JOBS
========================================================= --}}

<section class="py-5">

    <div class="container">


        {{-- SECTION HEADER --}}

        <div class="row align-items-end mb-4">

            <div class="col-lg-8">

                <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis px-3 py-2 fw-semibold text-uppercase small">

                    <i class="bi bi-lightning-charge-fill me-1"></i>

                    LATEST JOBS

                </span>

                <h2 class="fw-bold mt-3 mb-2">

                    Latest Job Opportunities

                </h2>

                <p class="text-secondary mb-0">

                    Explore the latest jobs available from companies hiring
                    now.

                </p>

            </div>


            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">

                <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary rounded-pill">

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

                <article class="card h-100 border-0 rounded-4 shadow-sm">

                    <div class="card-body d-flex flex-column p-4">


                        {{-- LOGO + TITLE --}}
                        <div class="d-flex align-items-start gap-3 mb-3">

                            {{-- LOGO --}}
                            @php
                            $logo = companyLogo($job->company);
                            @endphp
                            @if($logo)                            
                            <img src="{{ $logo }}" alt="{{ $job->company }}" class="rounded-3 border shadow-sm img-fluid" width="56" height="56" loading="lazy">
                            @else
                            <div class="bg-primary-subtle text-primary-emphasis rounded-3 d-flex align-items-center justify-content-center fw-bold fs-4 p-3">

                                {{ strtoupper(
                                                substr(
                                                    $job->company ?: $job->title,
                                                    0,
                                                    1
                                                )
                                            ) }}

                            </div>

                            @endif


                            <div class="flex-grow-1">

                                {{-- JOB TITLE --}}
                                <h3 class="h6 fw-bold mb-1">

                                    <a href="{{ url('viewjob/' . $job->slug) }}" class="text-decoration-none text-dark stretched-link">
                                        {{ $job->title }}
                                    </a>

                                </h3>


                                {{-- COMPANY --}}
                                @if($job->company)

                                <div class="small text-muted">

                                    <i class="bi bi-building me-1"></i>

                                    {{ $job->company }}

                                </div>

                                @endif

                            </div>

                        </div>


                        {{-- JOB META --}}
                        <div class="d-flex flex-wrap gap-2 mb-3">


                            {{-- LOCATION --}}
                            @if($job->location)

                            <span class="badge rounded-pill bg-info-subtle text-info-emphasis fw-normal px-3 py-2">

                                <i class="bi bi-geo-alt me-1"></i>

                                {{ $job->location }}

                            </span>

                            @endif


                            {{-- JOB TYPE --}}
                            @if($job->job_type)

                            <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis fw-normal px-3 py-2">

                                <i class="bi bi-briefcase me-1"></i>

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

                            <span class="badge rounded-pill bg-success-subtle text-success-emphasis fw-normal px-3 py-2">

                                <i class="bi bi-currency-rupee me-1"></i>

                                {{ $salary }}

                            </span>

                            @endif

                            @endif

                        </div>


                        {{-- JOB SNIPPET --}}
                        @if($job->snippet)

                        <p class="text-muted small mb-3">

                            {!! Str::limit(
                            strip_tags($job->snippet),
                            180
                            ) !!}

                        </p>

                        @endif


                        {{-- POSTED DATE --}}
                        @if(!is_null($job->age_days))

                        <div class="mb-3">

                            <span class="badge rounded-pill bg-body-secondary text-body-secondary border px-3 py-2 fw-normal">

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


                        {{-- VIEW JOB + QUICK APPLY --}}
                        <div class="d-flex gap-2 mt-auto pt-2">

                            {{-- VIEW JOB --}}
                            <a href="{{ url('viewjob/' . $job->slug) }}"
                                class="btn btn-primary rounded-pill btn-sm flex-grow-1 fw-semibold shadow-sm position-relative z-2">
                                <i class="bi bi-eye me-1"></i>
                                View Job
                            </a>

                            {{-- QUICK APPLY --}}
                            @if(!empty($job->job_url))

                            <a href="{{ $job->job_url }}" target="_blank" rel="nofollow sponsored"
                                class="btn btn-success rounded-pill btn-sm flex-grow-1 fw-semibold shadow-sm position-relative z-2">
                                <i class="bi bi-send me-1"></i>
                                Quick Apply
                                <i class="bi bi-box-arrow-up-right ms-1"></i>
                            </a>

                            @endif

                        </div>

                    </div>

                </article>

            </div>

            @empty


            {{-- NO JOBS --}}
            <div class="col-12">

                <div class="bg-white border-0 rounded-5 shadow-lg text-center p-5">

                    <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary-emphasis rounded-circle p-4 mb-3">

                        <i class="bi bi-search fs-1"></i>

                    </div>


                    <h4 class="fw-bold mb-2">
                        No jobs found
                    </h4>


                    <p class="text-muted mb-4">

                        We couldn't find jobs matching your search.

                        Try another keyword or location.

                    </p>


                    <a href="{{ route('jobs.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
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

<section class="py-5 bg-light-subtle">

    <div class="container">


        {{-- SECTION HEADER --}}

        <div class="row align-items-end mb-4">

            <div class="col-lg-8">

                <span class="badge rounded-pill bg-success-subtle text-success-emphasis px-3 py-2 fw-semibold text-uppercase small">

                    <i class="bi bi-geo-alt-fill me-1"></i>

                    JOBS BY LOCATION

                </span>

                <h2 class="fw-bold mt-3 mb-2">

                    Find Jobs by Popular Location in India

                </h2>

                <p class="text-secondary mb-0">

                    Explore job opportunities across popular locations
                    in India.

                </p>

            </div>


            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">

                <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary rounded-pill">

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
            'text-primary-emphasis',
            'text-info-emphasis',
            'text-success-emphasis',
            'text-warning-emphasis',
            ];


            $bgClass = $backgrounds[
            $key % count($backgrounds)
            ];


            $iconClass = $icons[
            $key % count($icons)
            ];

            @endphp


            <div class="col-xl-3 col-lg-4 col-md-6">

                <a href="{{ $locationUrl }}" class="h-100 d-flex align-items-center
                               text-decoration-none p-3
                               bg-white
                               border-0 rounded-4 shadow-sm">


                    {{-- ICON --}}

                    <div class="{{ $bgClass }} rounded-circle p-3 flex-shrink-0">

                        <i class="bi bi-geo-alt-fill {{ $iconClass }}"></i>

                    </div>


                    {{-- INFO --}}

                    <div class="ms-3 flex-grow-1 text-truncate">

                        <h3 class="h6 mb-1 fw-bold text-dark">

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
                                         text-primary-emphasis fw-semibold small">

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

                <div class="alert alert-light border-0 text-center rounded-4 shadow-sm py-4">

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

<section class="py-5" id="companies">

    <div class="container">


        {{-- SECTION HEADER --}}

        <div class="text-center mb-5">

            <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis px-3 py-2 fw-semibold text-uppercase small">

                <i class="bi bi-buildings-fill me-1"></i>

                TOP EMPLOYERS

            </span>

            <h2 class="fw-bold mt-3">

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
            //echo $companyName;

            $logo = companyLogo($companyName);

            @endphp


            <div class="col-lg-3 col-md-6">

                <div class="card h-100 border-0 rounded-4 shadow-sm">

                    <div class="card-body d-flex flex-column p-4 text-center">


                        {{-- COMPANY LOGO --}}

                        <div class="bg-light-subtle rounded-4 d-flex align-items-center
                                        justify-content-center mb-3 p-3">

                            @if($logo)

                            <img src="{{ $logo }}" alt="{{ $companyName }} logo" class="img-fluid"
                                loading="lazy"
                                onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');" width="55" height="55">

                            <span class="fw-bold text-primary-emphasis fs-4 d-none">

                                {{ $initial ?: 'C' }}

                            </span>

                            @else

                            <span class="fw-bold text-primary-emphasis fs-4">

                                {{ $initial ?: 'C' }}

                            </span>

                            @endif

                        </div>



                        {{-- COMPANY NAME --}}

                        <h3 class="h6 fw-bold mb-2">

                            {{ $companyName }}

                        </h3>



                        {{-- JOB COUNT --}}

                        <div class="mb-4">

                            <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis px-3 py-2">

                                <i class="bi bi-briefcase-fill me-1"></i>

                                {{ number_format($companyJobs) }}

                                {{ $companyJobs == 1 ? 'Job' : 'Jobs' }}

                            </span>

                        </div>



                        {{-- VIEW JOBS --}}

                        <div class="mt-auto">

                            <a href="{{ $companyUrl }}" class="bg-primary-subtle text-primary-emphasis rounded-pill
                                           d-flex align-items-center
                                           justify-content-between
                                           text-decoration-none px-3 py-2 fw-semibold">

                                <span>

                                    View Jobs

                                </span>

                                <span class="d-flex align-items-center gap-2">

                                    <span class="small">

                                        Explore

                                    </span>

                                    <i class="bi bi-arrow-right"></i>

                                </span>

                            </a>

                        </div>

                    </div>

                </div>

            </div>

            @empty

            <div class="col-12">

                <div class="alert alert-light border-0 text-center rounded-4 shadow-sm py-4">

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

<section class="main-cta py-5">

    <div class="container text-center">

        <h2 class="fw-bold">

            Ready for your next opportunity?

        </h2>

        <p class="text-white mb-4">

            Start exploring jobs and take the next step in your career.

        </p>

        <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm">

            <i class="bi bi-search me-1"></i>

            Explore Jobs

        </a>

    </div>

</section>


@endsection