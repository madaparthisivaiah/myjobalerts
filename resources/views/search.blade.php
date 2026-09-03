@extends('layouts.app')

@section('title', 'Search Jobs - JobBoard')


@section('content')

<!-- =========================================
     SEARCH HEADER
========================================= -->

<section class="search-page-header">

    <div class="container">

        <form
            action="{{ url('/search') }}"
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
                            value="Software Engineer"
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

</section>


<!-- =========================================
     RESULTS
========================================= -->

<main class="container search-results-area">

    <div class="row g-4">


        <!-- FILTERS -->

        <aside class="col-lg-3">

            <div class="filter-box">

                <div class="filter-header">

                    <h5>
                        Filter jobs
                    </h5>


                    <a href="#">
                        Clear
                    </a>

                </div>


                <hr>


                <!-- DATE -->

                <div class="filter-section">

                    <label class="filter-title">
                        Date posted
                    </label>


                    <select class="form-select">

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
                            id="fulltime"
                        >

                        <label
                            class="form-check-label"
                            for="fulltime"
                        >
                            Full-time
                        </label>

                    </div>


                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="parttime"
                        >

                        <label
                            class="form-check-label"
                            for="parttime"
                        >
                            Part-time
                        </label>

                    </div>


                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="contract"
                        >

                        <label
                            class="form-check-label"
                            for="contract"
                        >
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
                            id="remote"
                        >

                        <label
                            class="form-check-label"
                            for="remote"
                        >
                            Remote
                        </label>

                    </div>


                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="hybrid"
                        >

                        <label
                            class="form-check-label"
                            for="hybrid"
                        >
                            Hybrid
                        </label>

                    </div>


                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="onsite"
                        >

                        <label
                            class="form-check-label"
                            for="onsite"
                        >
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
                        >

                        <label class="form-check-label">
                            Entry level
                        </label>

                    </div>


                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="checkbox"
                        >

                        <label class="form-check-label">
                            Mid level
                        </label>

                    </div>


                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="checkbox"
                        >

                        <label class="form-check-label">
                            Senior level
                        </label>

                    </div>

                </div>

            </div>

        </aside>


        <!-- JOB RESULTS -->

        <section class="col-lg-9">

            <div class="results-heading">

                <div>

                    <h2>
                        Software Engineer jobs
                    </h2>


                    <p>
                        1,284 opportunities found
                    </p>

                </div>


                <select class="form-select sort-select">

                    <option>
                        Most relevant
                    </option>

                    <option>
                        Newest
                    </option>

                    <option>
                        Salary: High to Low
                    </option>

                </select>

            </div>


            <!-- JOB 1 -->

            <article class="search-job-card">

                <div class="company-logo logo-purple">
                    N
                </div>


                <div class="search-job-content">

                    <div class="d-flex justify-content-between">

                        <div>

                            <h3>

                                <a href="{{ url('/job-details') }}">
                                    Senior Software Engineer
                                </a>

                            </h3>


                            <div class="company-name">
                                Nova Technologies
                            </div>

                        </div>


                        <button
                            type="button"
                            class="save-job"
                        >
                            <i class="bi bi-bookmark"></i>
                        </button>

                    </div>


                    <div class="job-meta">

                        <span>
                            <i class="bi bi-geo-alt"></i>
                            Hyderabad, India
                        </span>

                        <span>
                            <i class="bi bi-briefcase"></i>
                            Full-time
                        </span>

                    </div>


                    <div class="job-tags">

                        <span>
                            ₹18–28 LPA
                        </span>

                        <span>
                            Hybrid
                        </span>

                    </div>


                    <p class="job-summary">

                        Build scalable applications and work with a talented
                        engineering team to deliver products used by millions.

                    </p>


                    <small class="posted-date">
                        Posted 2 days ago
                    </small>

                </div>

            </article>


            <!-- JOB 2 -->

            <article class="search-job-card">

                <div class="company-logo logo-blue">
                    T
                </div>


                <div class="search-job-content">

                    <div class="d-flex justify-content-between">

                        <div>

                            <h3>

                                <a href="{{ url('/job-details') }}">
                                    Backend Engineer — PHP / Laravel
                                </a>

                            </h3>


                            <div class="company-name">
                                TechNova
                            </div>

                        </div>


                        <button
                            type="button"
                            class="save-job"
                        >
                            <i class="bi bi-bookmark"></i>
                        </button>

                    </div>


                    <div class="job-meta">

                        <span>
                            <i class="bi bi-geo-alt"></i>
                            Remote, India
                        </span>

                        <span>
                            <i class="bi bi-briefcase"></i>
                            Full-time
                        </span>

                    </div>


                    <div class="job-tags">

                        <span>
                            ₹14–24 LPA
                        </span>

                        <span>
                            Remote
                        </span>

                    </div>


                    <p class="job-summary">

                        Join our backend engineering team and build
                        high-performance APIs and services.

                    </p>


                    <small class="posted-date">
                        Posted 1 day ago
                    </small>

                </div>

            </article>


            <!-- JOB 3 -->

            <article class="search-job-card">

                <div class="company-logo logo-orange">
                    A
                </div>


                <div class="search-job-content">

                    <div class="d-flex justify-content-between">

                        <div>

                            <h3>

                                <a href="{{ url('/job-details') }}">
                                    Software Engineer II
                                </a>

                            </h3>


                            <div class="company-name">
                                Atlas Labs
                            </div>

                        </div>


                        <button
                            type="button"
                            class="save-job"
                        >
                            <i class="bi bi-bookmark"></i>
                        </button>

                    </div>


                    <div class="job-meta">

                        <span>
                            <i class="bi bi-geo-alt"></i>
                            Bengaluru, India
                        </span>

                        <span>
                            <i class="bi bi-briefcase"></i>
                            Full-time
                        </span>

                    </div>


                    <div class="job-tags">

                        <span>
                            ₹16–25 LPA
                        </span>

                        <span>
                            On-site
                        </span>

                    </div>


                    <p class="job-summary">

                        Help us create reliable products while working
                        closely with product and design teams.

                    </p>


                    <small class="posted-date">
                        Posted 3 days ago
                    </small>

                </div>

            </article>


            <!-- JOB 4 -->

            <article class="search-job-card">

                <div class="company-logo logo-green">
                    C
                </div>


                <div class="search-job-content">

                    <div class="d-flex justify-content-between">

                        <div>

                            <h3>

                                <a href="{{ url('/job-details') }}">
                                    Full Stack Developer
                                </a>

                            </h3>


                            <div class="company-name">
                                CloudPeak
                            </div>

                        </div>


                        <button
                            type="button"
                            class="save-job"
                        >
                            <i class="bi bi-bookmark"></i>
                        </button>

                    </div>


                    <div class="job-meta">

                        <span>
                            <i class="bi bi-geo-alt"></i>
                            Pune, India
                        </span>

                        <span>
                            <i class="bi bi-briefcase"></i>
                            Full-time
                        </span>

                    </div>


                    <div class="job-tags">

                        <span>
                            ₹12–20 LPA
                        </span>

                        <span>
                            Hybrid
                        </span>

                    </div>


                    <p class="job-summary">

                        Work across frontend and backend systems and
                        contribute to a fast-growing product.

                    </p>


                    <small class="posted-date">
                        Posted 4 days ago
                    </small>

                </div>

            </article>


            <!-- JOB 5 -->

            <article class="search-job-card">

                <div class="company-logo logo-purple">
                    N
                </div>


                <div class="search-job-content">

                    <div class="d-flex justify-content-between">

                        <div>

                            <h3>

                                <a href="{{ url('/job-details') }}">
                                    Frontend Engineer — React
                                </a>

                            </h3>


                            <div class="company-name">
                                Nova Technologies
                            </div>

                        </div>


                        <button
                            type="button"
                            class="save-job"
                        >
                            <i class="bi bi-bookmark"></i>
                        </button>

                    </div>


                    <div class="job-meta">

                        <span>
                            <i class="bi bi-geo-alt"></i>
                            Chennai, India
                        </span>

                        <span>
                            <i class="bi bi-briefcase"></i>
                            Full-time
                        </span>

                    </div>


                    <div class="job-tags">

                        <span>
                            ₹10–18 LPA
                        </span>

                        <span>
                            Remote
                        </span>

                    </div>


                    <p class="job-summary">

                        Build modern, responsive interfaces for a rapidly
                        growing technology platform.

                    </p>


                    <small class="posted-date">
                        Posted 5 days ago
                    </small>

                </div>

            </article>


            <!-- PAGINATION -->

            <nav class="mt-4">

                <ul class="pagination">

                    <li class="page-item disabled">

                        <a class="page-link">
                            Previous
                        </a>

                    </li>


                    <li class="page-item active">

                        <a class="page-link">
                            1
                        </a>

                    </li>


                    <li class="page-item">
                        <a class="page-link">
                            2
                        </a>
                    </li>


                    <li class="page-item">
                        <a class="page-link">
                            3
                        </a>
                    </li>


                    <li class="page-item">
                        <a class="page-link">
                            4
                        </a>
                    </li>


                    <li class="page-item">
                        <a class="page-link">
                            Next
                        </a>
                    </li>

                </ul>

            </nav>

        </section>

    </div>

</main>

@endsection