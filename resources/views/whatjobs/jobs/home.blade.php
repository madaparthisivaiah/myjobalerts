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

<section class="whatjobs-hero">
    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-8 mx-auto text-center">

                <span class="section-label">
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

                    <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-lg px-4">

                        <i class="bi bi-search me-2"></i>

                        Browse All Jobs

                    </a>

                </div>

                @if(isset($totalJobs))

                <div class="mt-3 text-muted">

                    <strong>
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

<section class="section-padding">

    <div class="container">

        <div class="section-heading text-center mb-5">

            <span class="section-label">
                <i class="bi bi-clock-history me-1"></i>
                LATEST JOBS
            </span>

            <h2 class="mt-2">
                Latest Job Opportunities
            </h2>

            <p class="text-muted">
                Explore some of the latest jobs available across India.
            </p>

        </div>


        <div class="row g-4">

            @forelse($latestJobs as $job)

            <div class="col-lg-4 col-md-6">

                <div class="card h-100 border-0 shadow-sm job-card">

                    <div class="card-body">

                        <div class="d-flex align-items-start">

                            @if(!empty($job->logo))

                            <img src="{{ $job->logo }}" alt="{{ $job->company ?? 'Company' }}"
                                class="job-company-logo me-3" width="50" height="50" loading="lazy">

                            @else

                            <div class="job-logo-placeholder me-3">

                                <i class="bi bi-building"></i>

                            </div>

                            @endif


                            <div class="flex-grow-1">

                                <h5 class="mb-1">

                                    <a href="{{ url('job/' . $job->slug) }}" class="text-decoration-none text-dark">
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


                        <div class="job-meta mt-3">

                            @if($job->location)

                            <span class="me-3">

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

                <div class="alert alert-light text-center">

                    No jobs are currently available.

                </div>

            </div>

            @endforelse

        </div>


        @if($latestJobs->count())

        <div class="text-center mt-5">

            <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary px-4">

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

<section class="section-padding bg-light-subtle">

    <div class="container">

        <div class="section-heading text-center mb-5">

            <span class="section-label">

                <i class="bi bi-map me-1"></i>

                JOBS BY STATE

            </span>

            <h2 class="mt-2">

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

                    <div class="browse-card h-100">

                        <div class="browse-icon">

                            <i class="bi bi-geo-alt-fill"></i>

                        </div>

                        <div class="browse-content">

                            <h5 class="mb-1">

                                {{ $state['name'] }}

                            </h5>

                            <span class="text-muted small">

                                {{ number_format($state['count']) }}
                                {{ Str::plural('job', $state['count']) }}

                            </span>

                        </div>

                        <i class="bi bi-arrow-right browse-arrow"></i>

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

<section class="section-padding">

    <div class="container">

        <div class="section-heading text-center mb-5">

            <span class="section-label">

                <i class="bi bi-buildings me-1"></i>

                JOBS BY CITY

            </span>

            <h2 class="mt-2">

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

                    <div class="browse-card h-100">

                        <div class="browse-icon">

                            <i class="bi bi-buildings-fill"></i>

                        </div>

                        <div class="browse-content">

                            <h5 class="mb-1">

                                {{ $city['name'] }}

                            </h5>

                            <span class="text-muted small">

                                {{ number_format($city['count']) }}
                                {{ Str::plural('job', $city['count']) }}

                            </span>

                        </div>

                        <i class="bi bi-arrow-right browse-arrow"></i>

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

<section class="section-padding bg-light-subtle">

    <div class="container">

        <div class="section-heading text-center mb-5">

            <span class="section-label">

                <i class="bi bi-building me-1"></i>

                JOBS BY COMPANY

            </span>

            <h2 class="mt-2">

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

                    <div class="browse-card h-100">

                        <div class="browse-icon">

                            <i class="bi bi-building-fill"></i>

                        </div>

                        <div class="browse-content">

                            <h5 class="mb-1">

                                {{ $company['name'] }}

                            </h5>

                            <span class="text-muted small">

                                {{ number_format($company['count']) }}
                                {{ Str::plural('job', $company['count']) }}

                            </span>

                        </div>

                        <i class="bi bi-arrow-right browse-arrow"></i>

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

<section class="section-padding">

    <div class="container">

        <div class="row">

            <div class="col-lg-9 mx-auto">

                <div class="browse-jobs-cta text-center">

                    <span class="section-label">

                        <i class="bi bi-search me-1"></i>

                        FIND YOUR NEXT JOB

                    </span>

                    <h2 class="mt-3">

                        Ready to Find Your Next Opportunity?

                    </h2>

                    <p class="text-muted">

                        Browse the latest jobs from companies
                        hiring across India.

                    </p>

                    <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-lg px-4 mt-2">

                        Browse Jobs

                        <i class="bi bi-arrow-right ms-2"></i>

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>


@endsection



@push('styles')

<style>
.whatjobs-hero {
    padding: 90px 0;
    background: linear-gradient(180deg,
            #f8faff 0%,
            #ffffff 100%);
}


.section-padding {
    padding: 70px 0;
}


.section-label {
    display: inline-block;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}


.job-card {
    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease;
}


.job-card:hover {
    transform: translateY(-5px);
    box-shadow:
        0 12px 30px rgba(0, 0, 0, 0.10) !important;
}


.job-company-logo {
    object-fit: contain;
    border-radius: 8px;
    background: #f8f9fa;
}


.job-logo-placeholder {
    width: 50px;
    height: 50px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 8px;

    background: #f1f3f5;

    font-size: 1.25rem;
}


.job-meta {
    font-size: 0.85rem;
    color: #6c757d;
}


.browse-card {
    display: flex;
    align-items: center;

    position: relative;

    padding: 20px;

    background: #ffffff;

    border: 1px solid #e9ecef;

    border-radius: 12px;

    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease,
        border-color 0.25s ease;
}


.browse-card:hover {
    transform: translateY(-6px);

    border-color: #dee2e6;

    box-shadow:
        0 12px 30px rgba(0, 0, 0, 0.08);
}


.browse-icon {
    width: 48px;
    height: 48px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-right: 15px;

    border-radius: 10px;

    background: #f1f3f5;

    font-size: 1.2rem;
}


.browse-content {
    min-width: 0;
    padding-right: 25px;
}


.browse-content h5 {
    color: #212529;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}


.browse-arrow {
    position: absolute;

    right: 18px;

    color: #adb5bd;

    transition:
        transform 0.25s ease,
        color 0.25s ease;
}


.browse-card:hover .browse-arrow {
    transform: translateX(5px);
    color: #212529;
}


.browse-jobs-cta {
    padding: 60px 30px;

    border-radius: 16px;

    background: #f8f9fa;

    border: 1px solid #e9ecef;
}


@media (max-width: 767px) {

    .whatjobs-hero {
        padding: 60px 0;
    }

    .section-padding {
        padding: 50px 0;
    }

}
</style>

@endpush