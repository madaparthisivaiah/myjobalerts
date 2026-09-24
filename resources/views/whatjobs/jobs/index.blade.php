@extends('layouts.app')
@section('title', $pageTitle)
@section('meta_description', $metaDescription)
@section('content')
<section class="py-5 bg-light-subtle">

    <div class="container">

        {{-- HEADER --}}
        <div class="text-center mb-4">

            <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis px-3 py-2 fw-semibold text-uppercase small">
                <i class="bi bi-search me-1"></i>
                FIND YOUR NEXT JOB
            </span>

            <h1 class="display-6 fw-bold mt-3">
                Search Jobs in India
            </h1>

            <p class="text-muted mb-0">
                Browse the latest job opportunities from leading employers.
            </p>

        </div>


        {{-- SEARCH --}}
        <div class="bg-white border-0 rounded-5 shadow-lg p-4 mb-4">

            <form method="GET" action="{{ route('jobs.index') }}">

                <div class="row g-2">

                    {{-- KEYWORD --}}
                    <div class="col-lg-5">

                        <div class="input-group rounded-pill overflow-hidden shadow-sm">

                            <span class="input-group-text bg-primary-subtle text-primary-emphasis border-0">
                                <i class="bi bi-search"></i>
                            </span>

                            <input type="text" name="keyword" class="form-control border-0 bg-light" placeholder="Job title or company"
                                value="{{ $keyword }}">

                        </div>

                    </div>


                    {{-- LOCATION --}}
                    <div class="col-lg-4">

                        <div class="input-group rounded-pill overflow-hidden shadow-sm">

                            <span class="input-group-text bg-primary-subtle text-primary-emphasis border-0">
                                <i class="bi bi-geo-alt"></i>
                            </span>

                            <input type="text" name="location" class="form-control border-0 bg-light" placeholder="City or location"
                                value="{{ $location }}">

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


        {{-- RESULTS --}}
        <div class="row">

            <main class="col-12">


                {{-- RESULTS TOOLBAR --}}
                <div class="bg-white border-0 rounded-4 shadow-sm px-3 py-3 mb-3">

                    <div class="row align-items-center g-3">


                        {{-- RESULTS INFORMATION --}}
                        <div class="col-md">

                            <div class="d-flex align-items-center gap-3">

                                <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary-emphasis rounded-circle p-3">
                                    <i class="bi bi-briefcase fs-5"></i>
                                </div>


                                <div>

                                    <h2 class="h5 mb-0 fw-bold">

                                        @if($keyword)

                                        Jobs for "{{ $keyword }}"

                                        @elseif($location)

                                        Jobs in "{{ $location }}"

                                        @elseif($company)

                                        Jobs at "{{ $company }}"

                                        @else

                                        Latest Jobs

                                        @endif

                                    </h2>


                                    <small class="text-muted">

                                        <strong class="text-primary-emphasis">
                                            {{ number_format($jobs->total()) }}
                                        </strong>

                                        {{ Str::plural('job', $jobs->total()) }}

                                        available

                                    </small>

                                </div>

                            </div>

                        </div>


                        {{-- SORT --}}
                        <div class="col-md-auto">

                            <form method="GET" action="{{ route('jobs.index') }}"
                                class="d-flex align-items-center gap-2">

                                {{-- PRESERVE KEYWORD --}}
                                <input type="hidden" name="keyword" value="{{ $keyword }}">


                                {{-- PRESERVE LOCATION --}}
                                <input type="hidden" name="location" value="{{ $location }}">


                                {{-- PRESERVE COMPANY --}}
                                @if($company)

                                <input type="hidden" name="company" value="{{ $company }}">

                                @endif


                                <label for="sort" class="small text-muted mb-0 text-nowrap">
                                    <i class="bi bi-sort-down me-1"></i>
                                    Sort by
                                </label>


                                <select id="sort" name="sort" class="form-select form-select-sm rounded-pill w-auto"
                                    onchange="this.form.submit()">

                                    <option value="latest" @selected($sort=='latest' )>
                                        Newest First
                                    </option>

                                    <option value="oldest" @selected($sort=='oldest' )>
                                        Oldest First
                                    </option>

                                </select>

                            </form>

                        </div>

                    </div>

                </div>


                {{-- ACTIVE FILTERS --}}
                @if($keyword || $location || $company)

                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">

                    <span class="small text-muted">
                        Active filters:
                    </span>


                    {{-- KEYWORD --}}
                    @if($keyword)

                    <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis px-3 py-2 shadow-sm">

                        <i class="bi bi-search me-1"></i>

                        {{ $keyword }}

                    </span>

                    @endif


                    {{-- LOCATION --}}
                    @if($location)

                    <span class="badge rounded-pill bg-info-subtle text-info-emphasis px-3 py-2 shadow-sm">

                        <i class="bi bi-geo-alt me-1"></i>

                        {{ $location }}

                    </span>

                    @endif


                    {{-- COMPANY --}}
                    @if($company)

                    <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis px-3 py-2 shadow-sm">

                        <i class="bi bi-building me-1"></i>

                        {{ $company }}

                    </span>

                    @endif


                    {{-- CLEAR --}}
                    <a href="{{ route('jobs.index') }}" class="small text-decoration-none link-primary ms-1">
                        Clear filters
                        <i class="bi bi-x-circle ms-1"></i>
                    </a>

                </div>

                @endif


                {{-- JOB GRID --}}
                <div class="row g-4">

                    @forelse($jobs as $job)

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
                                    <img src="{{ $logo }}" alt="{{ $job->company }}"
                                        class="rounded-3 border shadow-sm img-fluid" width="56" height="56" loading="lazy">

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
                                    @if($job->employment_type)

                                    <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis fw-normal px-3 py-2">

                                        <i class="bi bi-briefcase me-1"></i>

                                        {{ $job->employment_type }}

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


                {{-- PAGINATION --}}
                @if($jobs->hasPages())

                <div class="mt-4">

                    {{ $jobs
                        ->onEachSide(1)
                        ->links('pagination::bootstrap-5')
                    }}

                </div>

                @endif


            </main>

        </div>

    </div>

</section>

@endsection