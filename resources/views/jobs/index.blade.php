@extends('layouts.app')

@section('title', 'Search Jobs - JobBoard')

@section('content')

<style>
.job-sidebar-ad {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 20px;
}

.job-sidebar-ad-label {
    padding: 8px 12px;
    text-align: center;
    font-size: 11px;
    color: #999;
    text-transform: uppercase;
    letter-spacing: 1px;
    background: #f1f3f5;
}

.job-sidebar-ad-content {
    min-height: 280px;
    padding: 35px 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
}

.job-sidebar-ad-content i {
    font-size: 42px;
    margin-bottom: 15px;
    color: #adb5bd;
}

.job-sidebar-ad-content h5 {
    margin-bottom: 8px;
}

.job-sidebar-ad-content p {
    color: #777;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 0;
}

.job-alert-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 24px 20px;
    margin-bottom: 20px;
    text-align: center;
}

.job-alert-icon {
    width: 52px;
    height: 52px;
    margin: 0 auto 15px;
    border-radius: 50%;
    background: #f0f4ff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.job-alert-icon i {
    font-size: 23px;
}

.job-alert-card h4 {
    font-size: 18px;
    margin-bottom: 8px;
}

.job-alert-card p {
    color: #777;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 18px;
}

.sidebar-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

.sidebar-card h5 {
    font-size: 16px;
    margin-bottom: 15px;
}

.sidebar-links {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.sidebar-links a {
    color: #555;
    text-decoration: none;
    font-size: 14px;
}

.sidebar-links a:hover {
    color: #0d6efd;
}

.results-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
}

.results-heading h2 {
    margin-bottom: 5px;
}

.results-heading p {
    margin-bottom: 0;
    color: #777;
}

.sort-select {
    min-width: 170px;
}

.pagination {
    flex-wrap: wrap;
    gap: 4px;
}

.pagination .page-link {
    border-radius: 6px;
}

.search-job-card {
    display: flex;
    gap: 18px;
    padding: 22px;
    margin-bottom: 16px;
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
}

.search-job-content {
    flex: 1;
    min-width: 0;
}

.search-job-card h3 {
    font-size: 19px;
    margin-bottom: 5px;
}

.search-job-card h3 a {
    color: inherit;
    text-decoration: none;
}

.search-job-card h3 a:hover {
    color: #0d6efd;
}

.company-name {
    color: #666;
    font-size: 14px;
}

.job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 10px;
    color: #666;
    font-size: 14px;
}

.job-meta span {
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.job-tags {
    margin-top: 10px;
}

.job-tags span {
    display: inline-block;
    padding: 5px 10px;
    background: #f1f3f5;
    border-radius: 5px;
    font-size: 13px;
    color: #555;
}

.job-summary {
    margin-top: 12px;
    margin-bottom: 8px;
    color: #666;
    line-height: 1.6;
    font-size: 14px;
}

.posted-date {
    color: #999;
}

.save-job {
    border: 0;
    background: transparent;
    font-size: 20px;
    color: #999;
    padding: 4px;
    cursor: pointer;
}

.save-job:hover {
    color: #0d6efd;
}

@media (max-width: 991px) {
    .results-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .sort-select {
        width: 100%;
    }
}

@media (max-width: 575px) {
    .search-job-card {
        padding: 16px;
        gap: 12px;
    }

    .company-logo {
        width: 42px !important;
        height: 42px !important;
        min-width: 42px;
    }

    .search-job-card h3 {
        font-size: 17px;
    }
}
</style>


<!-- =========================================
     SEARCH HEADER
========================================= -->

<section class="search-page-header">

    <div class="container">

        <form action="{{ route('jobs.index') }}" method="GET">

            <div class="row g-2">

                <!-- KEYWORD -->

                <div class="col-lg-5">

                    <div class="search-input search-input-light">

                        <i class="bi bi-search"></i>

                        <input type="text" name="keyword" class="form-control"
                            placeholder="Job title, keyword or company" value="{{ $keyword ?? request('keyword') }}">

                    </div>

                </div>


                <!-- LOCATION -->

                <div class="col-lg-4">

                    <div class="search-input search-input-light">

                        <i class="bi bi-geo-alt"></i>

                        <input type="text" name="location" class="form-control" placeholder="City, state or remote"
                            value="{{ $location ?? request('location') }}">

                    </div>

                </div>


                <!-- SEARCH BUTTON -->

                <div class="col-lg-3">

                    <button type="submit" class="btn btn-primary search-btn w-100">
                        Search Jobs
                    </button>

                </div>

            </div>

        </form>

    </div>

</section>


<!-- =========================================
     RESULTS
========================================= -->

<main class="container search-results-area">

    <div class="row g-4">

        <!-- =====================================
             JOB RESULTS
        ====================================== -->

        <section class="col-lg-12">

            <div class="alert alert-light border mb-4">
                <i class="bi bi-info-circle me-2"></i>
                <strong>About these job listings:</strong>
                Jobs shown here are collected from external job sources.
                MyJobAlerts.in helps you discover job opportunities, while applications
                are completed on the original job website.
            </div>
            <!-- =================================
                 RESULTS HEADER
            ================================== -->

            <div class="results-heading">

                <div>

                    <h2>

                        @if(!empty($keyword))

                        {{ $keyword }} jobs

                        @elseif(!empty($location))

                        Jobs in {{ $location }}

                        @else

                        Jobs in India

                        @endif

                    </h2>


                    <p>

                        @if(isset($hits))

                        {{ number_format($hits) }}

                        {{ $hits == 1
                                ? 'opportunity'
                                : 'opportunities'
                            }}

                        found

                        @else

                        Jobs found

                        @endif

                    </p>

                </div>


                <!-- SORT -->

                <form action="{{ route('jobs.index') }}" method="GET">

                    @if(!empty($keyword))

                    <input type="hidden" name="keyword" value="{{ $keyword }}">

                    @endif


                    @if(!empty($location))

                    <input type="hidden" name="location" value="{{ $location }}">

                    @endif


                    <select name="sort" class="form-select sort-select" onchange="this.form.submit()">

                        <option value="relevance" {{ ($sort ?? request('sort', 'relevance')) === 'relevance'
                                ? 'selected'
                                : ''
                            }}>
                            Most relevant
                        </option>

                        <option value="date" {{ ($sort ?? request('sort')) === 'date'
                                ? 'selected'
                                : ''
                            }}>
                            Newest
                        </option>

                    </select>

                </form>

            </div>


            <!-- =================================
                 JOB LIST
            ================================== -->
            <div class="row g-4">

                @if(!empty($jobs))

                @foreach($jobs as $job)

                @php

                $title = $job['title']
                ?? 'Untitled Job';

                $company = $job['company']
                ?? 'Company not specified';

                $locations = $job['locations']
                ?? 'India';

                $description = $job['description']
                ?? '';

                $salary = $job['salary']
                ?? '';

                $url = $job['url']
                ?? '#';

                $date = $job['date']
                ?? '';

                $initial = mb_strtoupper(
                mb_substr(
                trim($company),
                0,
                1
                )
                );

                @endphp


                <!-- JOB CARD -->
                <div class="col-12 col-md-6">
    <article class="search-job-card h-100">

        <!-- COMPANY ICON -->
        <div class="company-logo logo-purple">
            {{ $initial ?: 'C' }}
        </div>

        <div class="search-job-content">

            <!-- TITLE -->
            <!-- TITLE -->
<div>
    <h3 class="mb-2">
        @if($url && $url !== '#')
            <a
                href="{{ $url }}"
                target="_blank"
                rel="noopener noreferrer"
                class="text-primary text-decoration-none"
            >
                {{ $title }}
            </a>
        @else
            {{ $title }}
        @endif
    </h3>

    <!-- COMPANY -->
    @if(!empty($company))
        <div class="company-name text-dark">
            <span class="text-primary me-1">🏢</span>
            {{ $company }}
        </div>
    @endif
</div>

            <!-- JOB META -->
            <div class="job-meta mt-3">

                @if(!empty($locations))
                    <span class="text-secondary">
                        <span class="bi text-danger me-1">📍</span>
                        {{ $locations }}
                    </span>
                @endif

                @if(!empty($job['contract_type']))
                    <span class="text-secondary">
                        <span class="bi bi-briefcase-fill text-success me-1">💼</span>
                        {{ $job['contract_type'] }}
                    </span>
                @endif               

            </div>

            <!-- SALARY -->
            @if(!empty($salary))
                <div class="job-tags mt-3">
                    <span class="text-success">
                        <i class="bi bi-currency-rupee me-1"></i>
                        {{ $salary }}
                    </span>
                </div>
            @endif

            <!-- DESCRIPTION -->
            @if(!empty($description))
                <p class="job-summary mt-3">
                    {{ \Illuminate\Support\Str::limit(
                        strip_tags($description),
                        220
                    ) }}
                </p>
            @endif
            <!-- DATE + APPLY -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mt-3">

    <!-- DATE -->
    @if(!empty($date))
        <small class="posted-date">
            <i class="bi bi-clock-fill text-warning me-1"></i>
            {{ \Carbon\Carbon::parse($date)->diffForHumans() }}
        </small>
    @endif

    <!-- APPLY -->
    @if($url && $url !== '#')
        <a
            href="{{ $url }}"
            target="_blank"
            rel="noopener noreferrer"
            class="btn btn-primary"
        >
            <i class="bi bi-send-fill me-1"></i>
            Apply Now
            <i class="bi bi-box-arrow-up-right ms-1"></i>
        </a>
    @endif

</div>

        </div>

    </article>
</div>

                @endforeach
                @else
                <!-- =================================
                     NO RESULTS
                ================================== -->
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-search" style="font-size: 48px;"></i>
                    </div>
                    <h3>
                        No jobs found
                    </h3>


                    <p class="text-muted">

                        @if(!empty($keyword) || !empty($location))

                        We couldn't find jobs matching
                        your search.

                        Try a different keyword
                        or location.

                        @else

                        No cached jobs are currently
                        available.

                        Please try again later.

                        @endif

                    </p>


                    @if(!empty($keyword) || !empty($location))

                    <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                        View All Jobs
                    </a>

                    @endif

                </div>

                @endif
            </div>

            <!-- =====================================
                 PAGINATION
            ====================================== -->

            @if(($pages ?? 0) > 1)

            <nav class="mt-4" aria-label="Job pagination">

                <ul class="pagination">


                    <!-- PREVIOUS -->

                    @if(($currentPage ?? 1) > 1)

                    <li class="page-item">

                        <a class="page-link" href="{{ route(
                                        'jobs.index',
                                        array_merge(
                                            request()->query(),
                                            [
                                                'page' =>
                                                    $currentPage - 1
                                            ]
                                        )
                                    ) }}">
                            Previous
                        </a>

                    </li>

                    @else

                    <li class="page-item disabled">

                        <span class="page-link">
                            Previous
                        </span>

                    </li>

                    @endif


                    <!-- PAGE NUMBERS -->

                    @php

                    $current =
                    $currentPage ?? 1;

                    $total =
                    $pages ?? 1;

                    $start = max(
                    1,
                    $current - 2
                    );

                    $end = min(
                    $total,
                    $current + 2
                    );

                    @endphp


                    <!-- FIRST PAGE -->

                    @if($start > 1)

                    <li class="page-item">

                        <a class="page-link" href="{{ route(
                                        'jobs.index',
                                        array_merge(
                                            request()->query(),
                                            ['page' => 1]
                                        )
                                    ) }}">
                            1
                        </a>

                    </li>


                    @if($start > 2)

                    <li class="page-item disabled">

                        <span class="page-link">
                            ...
                        </span>

                    </li>

                    @endif

                    @endif


                    <!-- CURRENT PAGE RANGE -->

                    @for(
                    $pageNumber = $start;
                    $pageNumber <= $end; $pageNumber++ ) <li class="page-item
                                    {{
                                        $pageNumber == $current
                                            ? 'active'
                                            : ''
                                    }}">

                        @if($pageNumber == $current)

                        <span class="page-link">

                            {{ $pageNumber }}

                        </span>

                        @else

                        <a class="page-link" href="{{ route(
                                            'jobs.index',
                                            array_merge(
                                                request()->query(),
                                                [
                                                    'page' =>
                                                        $pageNumber
                                                ]
                                            )
                                        ) }}">

                            {{ $pageNumber }}

                        </a>

                        @endif

                        </li>

                        @endfor


                        <!-- LAST PAGE -->

                        @if($end < $total) @if($end < $total - 1) <li class="page-item disabled">

                            <span class="page-link">
                                ...
                            </span>

                            </li>

                            @endif


                            <li class="page-item">

                                <a class="page-link" href="{{ route(
                                        'jobs.index',
                                        array_merge(
                                            request()->query(),
                                            ['page' => $total]
                                        )
                                    ) }}">

                                    {{ $total }}

                                </a>

                            </li>

                            @endif


                            <!-- NEXT -->

                            @if($current < $total) <li class="page-item">

                                <a class="page-link" href="{{ route(
                                        'jobs.index',
                                        array_merge(
                                            request()->query(),
                                            [
                                                'page' =>
                                                    $current + 1
                                            ]
                                        )
                                    ) }}">
                                    Next
                                </a>

                                </li>

                                @else

                                <li class="page-item disabled">

                                    <span class="page-link">
                                        Next
                                    </span>

                                </li>

                                @endif

                </ul>

            </nav>

            @endif

        </section>

    </div>

</main>

@endsection