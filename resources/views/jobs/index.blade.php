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

        <form
            action="{{ route('jobs.index') }}"
            method="GET"
        >

            <div class="row g-2">

                <!-- KEYWORD -->

                <div class="col-lg-5">

                    <div class="search-input search-input-light">

                        <i class="bi bi-search"></i>

                        <input
                            type="text"
                            name="keyword"
                            class="form-control"
                            placeholder="Job title, keyword or company"
                            value="{{ $keyword ?? request('keyword') }}"
                        >

                    </div>

                </div>


                <!-- LOCATION -->

                <div class="col-lg-4">

                    <div class="search-input search-input-light">

                        <i class="bi bi-geo-alt"></i>

                        <input
                            type="text"
                            name="location"
                            class="form-control"
                            placeholder="City, state or remote"
                            value="{{ $location ?? request('location') }}"
                        >

                    </div>

                </div>


                <!-- SEARCH BUTTON -->

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

    </div>

</section>


<!-- =========================================
     RESULTS
========================================= -->

<main class="container search-results-area">

    <div class="row g-4">


        <!-- =====================================
             SIDEBAR
        ====================================== -->

        <aside class="col-lg-3">


            <!-- =================================
                 ADVERTISEMENT
            ================================== -->

            <div class="job-sidebar-ad">

                <div class="job-sidebar-ad-label">
                    Advertisement
                </div>

                <div class="job-sidebar-ad-content">

                    <i class="bi bi-megaphone"></i>

                    <h5>
                        Your Advertisement
                    </h5>

                    <p>
                        Reach job seekers and promote
                        your company or service here.
                    </p>

                </div>

            </div>


            <!-- =================================
                 JOB ALERT
            ================================== -->

            <div class="job-alert-card">

                <div class="job-alert-icon">

                    <i class="bi bi-bell"></i>

                </div>

                <h4>
                    Never Miss a Job
                </h4>

                <p>
                    Get the latest job opportunities
                    delivered to your inbox.
                </p>

                <a
                    href="#"
                    class="btn btn-primary w-100"
                >
                    Create Job Alert
                </a>

            </div>


            <!-- =================================
                 POPULAR SEARCHES
            ================================== -->

            <div class="sidebar-card">

                <h5>
                    Popular Searches
                </h5>

                <div class="sidebar-links">

                    <a
                        href="{{ route('jobs.index', [
                            'keyword' => 'Software Engineer'
                        ]) }}"
                    >
                        Software Engineer
                    </a>

                    <a
                        href="{{ route('jobs.index', [
                            'keyword' => 'Data Analyst'
                        ]) }}"
                    >
                        Data Analyst
                    </a>

                    <a
                        href="{{ route('jobs.index', [
                            'keyword' => 'Marketing'
                        ]) }}"
                    >
                        Marketing
                    </a>

                    <a
                        href="{{ route('jobs.index', [
                            'keyword' => 'Sales'
                        ]) }}"
                    >
                        Sales Jobs
                    </a>

                    <a
                        href="{{ route('jobs.index', [
                            'keyword' => 'Remote'
                        ]) }}"
                    >
                        Remote Jobs
                    </a>

                </div>

            </div>

        </aside>


        <!-- =====================================
             JOB RESULTS
        ====================================== -->

        <section class="col-lg-9">


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

                <form
                    action="{{ route('jobs.index') }}"
                    method="GET"
                >

                    @if(!empty($keyword))

                        <input
                            type="hidden"
                            name="keyword"
                            value="{{ $keyword }}"
                        >

                    @endif


                    @if(!empty($location))

                        <input
                            type="hidden"
                            name="location"
                            value="{{ $location }}"
                        >

                    @endif


                    <select
                        name="sort"
                        class="form-select sort-select"
                        onchange="this.form.submit()"
                    >

                        <option
                            value="relevance"
                            {{ ($sort ?? request('sort', 'relevance')) === 'relevance'
                                ? 'selected'
                                : ''
                            }}
                        >
                            Most relevant
                        </option>

                        <option
                            value="date"
                            {{ ($sort ?? request('sort')) === 'date'
                                ? 'selected'
                                : ''
                            }}
                        >
                            Newest
                        </option>

                    </select>

                </form>

            </div>


            <!-- =================================
                 JOB LIST
            ================================== -->

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

                    <article class="search-job-card">


                        <!-- COMPANY LOGO -->

                        <div class="company-logo logo-purple">

                            {{ $initial ?: 'C' }}

                        </div>


                        <!-- JOB CONTENT -->

                        <div class="search-job-content">


                            <!-- TITLE / COMPANY -->

                            <div class="d-flex justify-content-between">

                                <div>

                                    <h3>

                                        @if($url && $url !== '#')

                                            <a
                                                href="{{ $url }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                            >
                                                {{ $title }}
                                            </a>

                                        @else

                                            {{ $title }}

                                        @endif

                                    </h3>


                                    <div class="company-name">

                                        {{ $company }}

                                    </div>

                                </div>


                                <!-- SAVE -->

                                <button
                                    type="button"
                                    class="save-job"
                                    aria-label="Save job"
                                >

                                    <i class="bi bi-bookmark"></i>

                                </button>

                            </div>


                            <!-- JOB META -->

                            <div class="job-meta">

                                @if(!empty($locations))

                                    <span>

                                        <i class="bi bi-geo-alt"></i>

                                        {{ $locations }}

                                    </span>

                                @endif


                                @if(!empty($job['contract_type']))

                                    <span>

                                        <i class="bi bi-briefcase"></i>

                                        {{ $job['contract_type'] }}

                                    </span>

                                @endif

                            </div>


                            <!-- SALARY -->

                            @if(!empty($salary))

                                <div class="job-tags">

                                    <span>

                                        {{ $salary }}

                                    </span>

                                </div>

                            @endif


                            <!-- DESCRIPTION -->

                            @if(!empty($description))

                                <p class="job-summary">

                                    {{ \Illuminate\Support\Str::limit(
                                        strip_tags($description),
                                        280
                                    ) }}

                                </p>

                            @endif


                            <!-- DATE -->

                            @if(!empty($date))

                                <small class="posted-date">

                                    {{ $date }}

                                </small>

                            @endif

                        </div>

                    </article>

                @endforeach


            @else


                <!-- =================================
                     NO RESULTS
                ================================== -->

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="bi bi-search"
                            style="font-size: 48px;"
                        ></i>

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

                        <a
                            href="{{ route('jobs.index') }}"
                            class="btn btn-primary"
                        >
                            View All Jobs
                        </a>

                    @endif

                </div>

            @endif


            <!-- =====================================
                 PAGINATION
            ====================================== -->

            @if(($pages ?? 0) > 1)

                <nav
                    class="mt-4"
                    aria-label="Job pagination"
                >

                    <ul class="pagination">


                        <!-- PREVIOUS -->

                        @if(($currentPage ?? 1) > 1)

                            <li class="page-item">

                                <a
                                    class="page-link"
                                    href="{{ route(
                                        'jobs.index',
                                        array_merge(
                                            request()->query(),
                                            [
                                                'page' =>
                                                    $currentPage - 1
                                            ]
                                        )
                                    ) }}"
                                >
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

                                <a
                                    class="page-link"
                                    href="{{ route(
                                        'jobs.index',
                                        array_merge(
                                            request()->query(),
                                            ['page' => 1]
                                        )
                                    ) }}"
                                >
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
                            $pageNumber <= $end;
                            $pageNumber++
                        )

                            <li
                                class="page-item
                                    {{
                                        $pageNumber == $current
                                            ? 'active'
                                            : ''
                                    }}"
                            >

                                @if($pageNumber == $current)

                                    <span class="page-link">

                                        {{ $pageNumber }}

                                    </span>

                                @else

                                    <a
                                        class="page-link"
                                        href="{{ route(
                                            'jobs.index',
                                            array_merge(
                                                request()->query(),
                                                [
                                                    'page' =>
                                                        $pageNumber
                                                ]
                                            )
                                        ) }}"
                                    >

                                        {{ $pageNumber }}

                                    </a>

                                @endif

                            </li>

                        @endfor


                        <!-- LAST PAGE -->

                        @if($end < $total)

                            @if($end < $total - 1)

                                <li class="page-item disabled">

                                    <span class="page-link">
                                        ...
                                    </span>

                                </li>

                            @endif


                            <li class="page-item">

                                <a
                                    class="page-link"
                                    href="{{ route(
                                        'jobs.index',
                                        array_merge(
                                            request()->query(),
                                            ['page' => $total]
                                        )
                                    ) }}"
                                >

                                    {{ $total }}

                                </a>

                            </li>

                        @endif


                        <!-- NEXT -->

                        @if($current < $total)

                            <li class="page-item">

                                <a
                                    class="page-link"
                                    href="{{ route(
                                        'jobs.index',
                                        array_merge(
                                            request()->query(),
                                            [
                                                'page' =>
                                                    $current + 1
                                            ]
                                        )
                                    ) }}"
                                >
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