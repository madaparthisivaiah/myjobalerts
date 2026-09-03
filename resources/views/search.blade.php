@extends('layouts.app')

@section('title', 'Search Jobs - JobBoard')

@section('content')

<!-- =========================================
     SEARCH HEADER
========================================= -->

<section class="search-page-header">

```
<div class="container">

    <form
        action="{{ route('jobs.index') }}"
        method="GET"
    >

        <div class="row g-2">

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
```

</section>

<!-- =========================================
     RESULTS
========================================= -->

<main class="container search-results-area">

```
<div class="row g-4">


    <!-- =====================================
         FILTERS
    ====================================== -->

    <aside class="col-lg-3">

        <div class="filter-box">

            <div class="filter-header">

                <h5>
                    Filter jobs
                </h5>

                <a href="{{ route('jobs.index') }}">
                    Clear
                </a>

            </div>


            <hr>


            <!-- DATE -->

            <div class="filter-section">

                <label class="filter-title">
                    Date posted
                </label>

                <select
                    class="form-select"
                    disabled
                >

                    <option>
                        Any time
                    </option>

                    <option>
                        Past 24 hours
                    </option>

                    <option>
                        Past week
                    </option>

                    <option>
                        Past month
                    </option>

                </select>

            </div>


            <!-- JOB TYPE -->

            <div class="filter-section">

                <label class="filter-title">
                    Job type
                </label>


                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        disabled
                    >

                    <label class="form-check-label">
                        Full-time
                    </label>

                </div>


                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        disabled
                    >

                    <label class="form-check-label">
                        Part-time
                    </label>

                </div>


                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        disabled
                    >

                    <label class="form-check-label">
                        Contract
                    </label>

                </div>

            </div>


            <!-- WORK MODE -->

            <div class="filter-section">

                <label class="filter-title">
                    Work mode
                </label>


                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        disabled
                    >

                    <label class="form-check-label">
                        Remote
                    </label>

                </div>


                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        disabled
                    >

                    <label class="form-check-label">
                        Hybrid
                    </label>

                </div>


                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        disabled
                    >

                    <label class="form-check-label">
                        On-site
                    </label>

                </div>

            </div>


            <!-- EXPERIENCE -->

            <div class="filter-section">

                <label class="filter-title">
                    Experience
                </label>


                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        disabled
                    >

                    <label class="form-check-label">
                        Entry level
                    </label>

                </div>


                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        disabled
                    >

                    <label class="form-check-label">
                        Mid level
                    </label>

                </div>


                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        disabled
                    >

                    <label class="form-check-label">
                        Senior level
                    </label>

                </div>

            </div>

        </div>

    </aside>


    <!-- =====================================
         JOB RESULTS
    ====================================== -->

    <section class="col-lg-9">


        <!-- RESULTS HEADER -->

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

                        {{ $hits == 1 ? 'opportunity' : 'opportunities' }}
                        found

                    @else

                        Jobs found

                    @endif

                </p>

            </div>


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
                        {{ request('sort', 'relevance') === 'relevance' ? 'selected' : '' }}
                    >
                        Most relevant
                    </option>

                    <option
                        value="date"
                        {{ request('sort') === 'date' ? 'selected' : '' }}
                    >
                        Newest
                    </option>

                </select>

            </form>

        </div>


        <!-- =====================================
             JOB LIST
        ====================================== -->

        @if(!empty($jobs))

            @foreach($jobs as $job)

                @php

                    $title = $job['title'] ?? 'Untitled Job';

                    $company = $job['company'] ?? 'Company not specified';

                    $locations = $job['locations'] ?? 'India';

                    $description = $job['description'] ?? '';

                    $salary = $job['salary'] ?? '';

                    $url = $job['url'] ?? '#';

                    $date = $job['date'] ?? '';

                    $initial = mb_strtoupper(
                        mb_substr(
                            trim($company),
                            0,
                            1
                        )
                    );

                @endphp


                <article class="search-job-card">


                    <!-- COMPANY LOGO -->

                    <div class="company-logo logo-purple">

                        {{ $initial ?: 'C' }}

                    </div>


                    <!-- JOB CONTENT -->

                    <div class="search-job-content">


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


                        <!-- JOB TAGS -->

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

                        We couldn't find jobs matching your search.

                        Try a different keyword or location.

                    @else

                        No cached jobs are currently available.

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
                                href="{{ route('jobs.index', array_merge(
                                    request()->query(),
                                    ['page' => $currentPage - 1]
                                )) }}"
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

                        $current = $currentPage ?? 1;

                        $total = $pages ?? 1;

                        $start = max(
                            1,
                            $current - 2
                        );

                        $end = min(
                            $total,
                            $current + 2
                        );

                    @endphp


                    @if($start > 1)

                        <li class="page-item">

                            <a
                                class="page-link"
                                href="{{ route('jobs.index', array_merge(
                                    request()->query(),
                                    ['page' => 1]
                                )) }}"
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


                    @for($pageNumber = $start; $pageNumber <= $end; $pageNumber++)

                        <li
                            class="page-item {{ $pageNumber == $current ? 'active' : '' }}"
                        >

                            @if($pageNumber == $current)

                                <span class="page-link">
                                    {{ $pageNumber }}
                                </span>

                            @else

                                <a
                                    class="page-link"
                                    href="{{ route('jobs.index', array_merge(
                                        request()->query(),
                                        ['page' => $pageNumber]
                                    )) }}"
                                >
                                    {{ $pageNumber }}
                                </a>

                            @endif

                        </li>

                    @endfor


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
                                href="{{ route('jobs.index', array_merge(
                                    request()->query(),
                                    ['page' => $total]
                                )) }}"
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
                                href="{{ route('jobs.index', array_merge(
                                    request()->query(),
                                    ['page' => $current + 1]
                                )) }}"
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
```

</main>

@endsection