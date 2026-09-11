@extends('layouts.app')
@section('title', $pageTitle)
@section('meta_description', $metaDescription)
@section('content')
<section class="section-padding bg-light-subtle">

    <div class="container">

        {{-- HEADER --}}
        <div class="text-center mb-4">

            <span class="section-label">
                <i class="bi bi-search me-1"></i>
                FIND YOUR NEXT JOB
            </span>

            <h1 class="mt-2">
                Search Jobs in India
            </h1>

            <p class="text-muted mb-0">
                Browse the latest job opportunities from leading employers.
            </p>

        </div>


        {{-- SEARCH --}}
        <div class="job-search-box mb-4">

            <form method="GET" action="{{ route('jobs.index') }}">

                <div class="row g-2">

                    {{-- KEYWORD --}}
                    <div class="col-lg-5">

                        <div class="input-group">

                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>

                            <input type="text" name="keyword" class="form-control" placeholder="Job title or company"
                                value="{{ $keyword }}">

                        </div>

                    </div>


                    {{-- LOCATION --}}
                    <div class="col-lg-4">

                        <div class="input-group">

                            <span class="input-group-text bg-white">
                                <i class="bi bi-geo-alt"></i>
                            </span>

                            <input type="text" name="location" class="form-control" placeholder="City or location"
                                value="{{ $location }}">

                        </div>

                    </div>


                    {{-- SEARCH BUTTON --}}
                    <div class="col-lg-3">

                        <button type="submit" class="btn btn-primary w-100 h-100">
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
                <div class="bg-white border rounded-3 shadow-sm px-3 py-3 mb-3">

                    <div class="row align-items-center g-3">


                        {{-- RESULTS INFORMATION --}}
                        <div class="col-md">

                            <div class="d-flex align-items-center gap-2">

                                <div class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle"
                                    style="width: 42px; height: 42px;">
                                    <i class="bi bi-briefcase fs-5"></i>
                                </div>


                                <div>

                                    <h2 class="h5 mb-0 fw-semibold">

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

                                        <strong class="text-dark">
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


                                <select id="sort" name="sort" class="form-select form-select-sm"
                                    onchange="this.form.submit()" style="min-width: 150px;">

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

                    <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">

                        <i class="bi bi-search me-1"></i>

                        {{ $keyword }}

                    </span>

                    @endif


                    {{-- LOCATION --}}
                    @if($location)

                    <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">

                        <i class="bi bi-geo-alt me-1"></i>

                        {{ $location }}

                    </span>

                    @endif


                    {{-- COMPANY --}}
                    @if($company)

                    <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">

                        <i class="bi bi-building me-1"></i>

                        {{ $company }}

                    </span>

                    @endif


                    {{-- CLEAR --}}
                    <a href="{{ route('jobs.index') }}" class="small text-decoration-none ms-1">
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

                        <article class="search-job-card h-100">


                            {{-- LOGO --}}
                            <div class="job-logo-wrapper">

                                @if($job->logo)

                                <img src="{{ $job->logo }}" alt="{{ $job->company }}" class="job-company-logo"
                                    loading="lazy">

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
                                        class="btn btn-primary btn-sm flex-grow-1 fw-semibold"> <i
                                            class="bi bi-eye me-1"></i> View Job </a> {{-- QUICK APPLY --}}
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