@extends('layouts.app')

@section('title', 'Search Jobs in India | MyJobAlerts')

@section('meta_description', 'Find the latest jobs in India by job title, company and location. Browse thousands of job opportunities on MyJobAlerts.')

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
        <div class="job-search-box mb-5">

            <form method="GET" action="{{ route('jobs.index') }}">

                <div class="row g-2">

                    <div class="col-lg-5">

                        <div class="input-group">

                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>

                            <input
                                type="text"
                                name="keyword"
                                class="form-control"
                                placeholder="Job title or company"
                                value="{{ $keyword }}"
                            >

                        </div>

                    </div>


                    <div class="col-lg-4">

                        <div class="input-group">

                            <span class="input-group-text bg-white">
                                <i class="bi bi-geo-alt"></i>
                            </span>

                            <input
                                type="text"
                                name="location"
                                class="form-control"
                                placeholder="City or location"
                                value="{{ $location }}"
                            >

                        </div>

                    </div>


                    <div class="col-lg-3">

                        <button
                            type="submit"
                            class="btn btn-primary w-100 h-100"
                        >
                            <i class="bi bi-search me-1"></i>
                            Search Jobs
                        </button>

                    </div>

                </div>

            </form>

        </div>


        <div class="row g-4">


            {{-- SIDEBAR --}}
            <aside class="col-lg-3">


                {{-- SORT --}}
                <div class="sidebar-card">

                    <h5>
                        <i class="bi bi-filter me-1"></i>
                        Sort Jobs
                    </h5>

                    <form method="GET" action="{{ route('jobs.index') }}">

                        <input
                            type="hidden"
                            name="keyword"
                            value="{{ $keyword }}"
                        >

                        <input
                            type="hidden"
                            name="location"
                            value="{{ $location }}"
                        >

                        <select
                            name="sort"
                            class="form-select"
                            onchange="this.form.submit()"
                        >

                            <option
                                value="latest"
                                @selected($sort === 'latest')
                            >
                                Newest First
                            </option>

                            <option
                                value="oldest"
                                @selected($sort === 'oldest')
                            >
                                Oldest First
                            </option>

                        </select>

                    </form>

                </div>


                {{-- POPULAR COMPANIES --}}
                <div class="sidebar-card">

                    <h5>
                        <i class="bi bi-building me-1"></i>
                        Popular Companies
                    </h5>

                    <div class="sidebar-links">

                        @foreach($companies as $item)

                            <a
                                href="{{ route('jobs.index', ['company' => $item]) }}"
                            >
                                {{ $item }}
                            </a>

                        @endforeach

                    </div>

                </div>


                {{-- AD --}}
                <div class="job-sidebar-ad">

                    <div class="job-sidebar-ad-label">
                        Advertisement
                    </div>

                    <div class="job-sidebar-ad-content">

                        <i class="bi bi-megaphone"></i>

                        <h5>
                            Your Career Starts Here
                        </h5>

                        <p>
                            Discover new opportunities and take
                            the next step in your career.
                        </p>

                    </div>

                </div>

            </aside>


            {{-- RESULTS --}}
            <main class="col-lg-9">


                <div class="results-heading">

                    <div>

                        <h2>
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

                        <p>
                            {{ number_format($jobs->total()) }}
                            {{ Str::plural('job', $jobs->total()) }} found
                        </p>

                    </div>

                </div>


                {{-- ACTIVE FILTER --}}
                @if($keyword || $location || $company)

                    <div class="mb-3">

                        @if($keyword)

                            <span class="badge bg-primary-subtle text-primary me-1">
                                Keyword: {{ $keyword }}
                            </span>

                        @endif

                        @if($location)

                            <span class="badge bg-primary-subtle text-primary me-1">
                                Location: {{ $location }}
                            </span>

                        @endif

                        @if($company)

                            <span class="badge bg-primary-subtle text-primary me-1">
                                Company: {{ $company }}
                            </span>

                        @endif

                        <a
                            href="{{ route('jobs.index') }}"
                            class="small text-decoration-none"
                        >
                            Clear filters
                        </a>

                    </div>

                @endif


                {{-- JOBS --}}
                @forelse($jobs as $job)

                    <article class="search-job-card">

                        {{-- LOGO --}}
                        <div class="job-logo-wrapper">

                            @if($job->logo)

                                <img
                                    src="{{ $job->logo }}"
                                    alt="{{ $job->company }}"
                                    class="job-company-logo"
                                    loading="lazy"
                                >

                            @else

                                <div class="job-company-logo-placeholder">

                                    {{ strtoupper(
                                        substr($job->company ?: $job->title, 0, 1)
                                    ) }}

                                </div>

                            @endif

                        </div>


                        {{-- CONTENT --}}
                        <div class="search-job-content">

                            <h3>

                                <a
                                    href="{{ route(
                                        'jobs.show',
                                        $job->provider_job_id
                                    ) }}"
                                >
                                    {{ $job->title }}
                                </a>

                            </h3>


                            @if($job->company)

                                <div class="company-name">

                                    <i class="bi bi-building me-1"></i>

                                    {{ $job->company }}

                                </div>

                            @endif


                            <div class="job-meta">

                                @if($job->location)

                                    <span>

                                        <i class="bi bi-geo-alt"></i>

                                        {{ $job->location }}

                                    </span>

                                @endif


                                @if($job->job_type)

                                    <span>

                                        <i class="bi bi-briefcase"></i>

                                        {{ $job->job_type }}

                                    </span>

                                @endif


                                @if($job->salary && $job->salary !== '0.000000 - 0.000000')

                                    <span>

                                        <i class="bi bi-currency-rupee"></i>

                                        {{ $job->salary }}

                                    </span>

                                @endif

                            </div>


                            @if($job->snippet)

                                <div class="mt-2 text-muted small">

                                    {!! Str::limit(
                                        strip_tags($job->snippet),
                                        180
                                    ) !!}

                                </div>

                            @endif


                            <div class="job-tags mt-2">

                                @if(!is_null($job->age_days))

                                    <span class="posted-badge">

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

                        </div>

                    </article>

                @empty

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

                        <a
                            href="{{ route('jobs.index') }}"
                            class="btn btn-primary"
                        >
                            Browse All Jobs
                        </a>

                    </div>

                @endforelse


                {{-- PAGINATION --}}
                @if($jobs->hasPages())

                    <div class="mt-4">

                        {{ $jobs->onEachSide(1)->links('pagination::bootstrap-5') }}

                    </div>

                @endif

            </main>

        </div>

    </div>

</section>

@endsection