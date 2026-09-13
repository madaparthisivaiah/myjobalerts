@extends('layouts.app')

@section('title', 'Latest Jobs in India | MyJobAlerts')

@section('meta_description')
Find the latest jobs in India by state, city and company.
Search and explore job opportunities from across India on MyJobAlerts.
@endsection

@section('content')

{{-- =========================================================
     HERO
========================================================= --}}

<section class="bg-primary bg-gradient bg-opacity-10 py-5">
    <div class="container py-4">

        <div class="row align-items-center">

            <div class="col-lg-8 mx-auto text-center">

                <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis px-3 py-2 fw-semibold text-uppercase small">
                    <i class="bi bi-briefcase-fill me-1"></i>
                    WHATJOBS JOBS
                </span>

                <h1 class="display-4 fw-bold mt-3">
                    Find Jobs Across India
                </h1>

                <p class="lead text-muted mt-3">
                    Discover the latest job opportunities by
                    state, city and company.
                </p>

                <div class="mt-4">

                    <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm">

                        <i class="bi bi-search me-2"></i>

                        Browse All Jobs

                    </a>

                </div>

                @if(isset($totalJobs))

                <div class="mt-3 text-muted">

                    <strong class="text-primary-emphasis">
                        {{ number_format($totalJobs) }}
                    </strong>

                    active jobs available

                </div>

                @endif

            </div>

        </div>

    </div>
</section>


{{-- =========================================================
     LATEST JOBS
========================================================= --}}

<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis px-3 py-2 fw-semibold text-uppercase small">
                <i class="bi bi-clock-history me-1"></i>
                LATEST JOBS
            </span>

            <h2 class="fw-bold mt-3">
                Latest Job Opportunities
            </h2>

            <p class="text-muted">
                Explore some of the latest jobs available across India.
            </p>

        </div>


        <div class="row g-4">

            @forelse($latestJobs as $job)

            <div class="col-lg-4 col-md-6">

                <div class="card h-100 border-0 rounded-4 shadow-sm">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-start">

                            @if(!empty($job->logo))

                            <img src="{{ $job->logo }}" alt="{{ $job->company ?? 'Company' }}"
                                class="rounded-3 border object-fit-contain bg-light p-1 me-3" width="50" height="50" loading="lazy">

                            @else

                            <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary-emphasis rounded-3 fs-4 p-3 me-3">

                                <i class="bi bi-building"></i>

                            </div>

                            @endif


                            <div class="flex-grow-1">

                                <h5 class="fw-bold mb-1">

                                    <a href="{{ url('job/' . $job->slug) }}" class="text-decoration-none text-dark stretched-link">
                                        {{ $job->title }}
                                    </a>

                                </h5>


                                @if($job->company)

                                <div class="text-muted small">

                                    <i class="bi bi-building me-1"></i>

                                    {{ $job->company }}

                                </div>

                                @endif

                            </div>

                        </div>


                        <div class="d-flex flex-wrap gap-3 text-muted small mt-3">

                            @if($job->location)

                            <span>

                                <i class="bi bi-geo-alt me-1"></i>

                                {{ $job->location }}

                            </span>

                            @endif


                            @if($job->job_type)

                            <span>

                                <i class="bi bi-clock me-1"></i>

                                {{ $job->job_type }}

                            </span>

                            @endif

                        </div>


                        @if($job->snippet)

                        <p class="text-muted small mt-3 mb-0">

                            {{ Str::limit(
                                        strip_tags($job->snippet),
                                        120
                                    ) }}

                        </p>

                        @endif

                    </div>

                </div>

            </div>

            @empty

            <div class="col-12">

                <div class="alert alert-light border-0 rounded-4 shadow-sm text-center">

                    No jobs are currently available.

                </div>

            </div>

            @endforelse

        </div>


        @if($latestJobs->count())

        <div class="text-center mt-5">

            <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary rounded-pill px-4">

                View All Jobs

                <i class="bi bi-arrow-right ms-2"></i>

            </a>

        </div>

        @endif

    </div>

</section>



{{-- =========================================================
     JOBS BY STATE
========================================================= --}}

<section class="py-5 bg-light-subtle">

    <div class="container">

        <div class="text-center mb-5">

            <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis px-3 py-2 fw-semibold text-uppercase small">

                <i class="bi bi-map me-1"></i>

                JOBS BY STATE

            </span>

            <h2 class="fw-bold mt-3">

                Find Jobs by State

            </h2>

            <p class="text-muted">

                Explore job opportunities across different
                states in India.

            </p>

        </div>


        <div class="row g-4">

            @foreach($states as $state)

            <div class="col-xl-3 col-lg-4 col-md-6">

                <a href="{{ route(
                            'jobs.state',
                            ['state' => $state['slug']]
                        ) }}" class="text-decoration-none">

                    <div class="card h-100 border-0 rounded-4 shadow-sm">

                        <div class="card-body d-flex align-items-center p-3">

                            <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary-emphasis rounded-circle fs-4 p-3 flex-shrink-0 me-3">

                                <i class="bi bi-geo-alt-fill"></i>

                            </div>

                            <div class="flex-grow-1">

                                <h5 class="fw-bold mb-1 text-truncate text-dark">

                                    {{ $state['name'] }}

                                </h5>

                                <span class="text-muted small">

                                    {{ number_format($state['count']) }}
                                    {{ Str::plural('job', $state['count']) }}

                                </span>

                            </div>

                            <i class="bi bi-arrow-right text-primary-emphasis ms-2"></i>

                        </div>

                    </div>

                </a>

            </div>

            @endforeach

        </div>

    </div>

</section>



{{-- =========================================================
     JOBS BY CITY
========================================================= --}}

<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <span class="badge rounded-pill bg-info-subtle text-info-emphasis px-3 py-2 fw-semibold text-uppercase small">

                <i class="bi bi-buildings me-1"></i>

                JOBS BY CITY

            </span>

            <h2 class="fw-bold mt-3">

                Find Jobs by City

            </h2>

            <p class="text-muted">

                Search job opportunities in popular cities
                across India.

            </p>

        </div>


        <div class="row g-4">

            @foreach($cities as $city)

            <div class="col-xl-3 col-lg-4 col-md-6">

                <a href="{{ route(
                            'jobs.city',
                            ['city' => $city['slug']]
                        ) }}" class="text-decoration-none">

                    <div class="card h-100 border-0 rounded-4 shadow-sm">

                        <div class="card-body d-flex align-items-center p-3">

                            <div class="d-inline-flex align-items-center justify-content-center bg-info-subtle text-info-emphasis rounded-circle fs-4 p-3 flex-shrink-0 me-3">

                                <i class="bi bi-buildings-fill"></i>

                            </div>

                            <div class="flex-grow-1">

                                <h5 class="fw-bold mb-1 text-truncate text-dark">

                                    {{ $city['name'] }}

                                </h5>

                                <span class="text-muted small">

                                    {{ number_format($city['count']) }}
                                    {{ Str::plural('job', $city['count']) }}

                                </span>

                            </div>

                            <i class="bi bi-arrow-right text-info-emphasis ms-2"></i>

                        </div>

                    </div>

                </a>

            </div>

            @endforeach

        </div>

    </div>

</section>



{{-- =========================================================
     JOBS BY COMPANY
========================================================= --}}

<section class="py-5 bg-light-subtle">

    <div class="container">

        <div class="text-center mb-5">

            <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis px-3 py-2 fw-semibold text-uppercase small">

                <i class="bi bi-building me-1"></i>

                JOBS BY COMPANY

            </span>

            <h2 class="fw-bold mt-3">

                Explore Jobs by Company

            </h2>

            <p class="text-muted">

                Discover opportunities from companies hiring
                across India.

            </p>

        </div>


        <div class="row g-4">

            @foreach($companies as $company)

            <div class="col-xl-3 col-lg-4 col-md-6">

                <a href="{{ route(
                            'company.show',
                            ['company' => $company['slug']]
                        ) }}" class="text-decoration-none">

                    <div class="card h-100 border-0 rounded-4 shadow-sm">

                        <div class="card-body d-flex align-items-center p-3">

                            <div class="d-inline-flex align-items-center justify-content-center bg-warning-subtle text-warning-emphasis rounded-circle fs-4 p-3 flex-shrink-0 me-3">

                                <i class="bi bi-building-fill"></i>

                            </div>

                            <div class="flex-grow-1">

                                <h5 class="fw-bold mb-1 text-truncate text-dark">

                                    {{ $company['name'] }}

                                </h5>

                                <span class="text-muted small">

                                    {{ number_format($company['count']) }}
                                    {{ Str::plural('job', $company['count']) }}

                                </span>

                            </div>

                            <i class="bi bi-arrow-right text-warning-emphasis ms-2"></i>

                        </div>

                    </div>

                </a>

            </div>

            @endforeach

        </div>

    </div>

</section>



{{-- =========================================================
     FINAL CTA
========================================================= --}}

<section class="py-5">

    <div class="container">

        <div class="row">

            <div class="col-lg-9 mx-auto">

                <div class="bg-primary bg-gradient bg-opacity-10 rounded-5 shadow-lg text-center p-5">

                    <span class="badge rounded-pill bg-white text-primary-emphasis px-3 py-2 fw-semibold text-uppercase small shadow-sm">

                        <i class="bi bi-search me-1"></i>

                        FIND YOUR NEXT JOB

                    </span>

                    <h2 class="fw-bold mt-3">

                        Ready to Find Your Next Opportunity?

                    </h2>

                    <p class="text-muted">

                        Browse the latest jobs from companies
                        hiring across India.

                    </p>

                    <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-lg rounded-pill px-4 mt-2 shadow-sm">

                        Browse Jobs

                        <i class="bi bi-arrow-right ms-2"></i>

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>


@endsection