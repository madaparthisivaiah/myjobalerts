@extends('layouts.app')

@section('title', 'Find Your Next Job - JobBoard')


@section('content')

<!-- =========================================
     HERO
========================================= -->

<section class="home-hero">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-xl-9 text-center">

                <span class="hero-label">
                    FIND YOUR NEXT OPPORTUNITY
                </span>


                <h1 class="hero-title">

                    Find a job that
                    <span>fits your life.</span>

                </h1>


                <p class="hero-description">

                    Discover thousands of jobs from companies hiring
                    talented people like you.

                </p>


                <!-- Search -->

                <form
                    action="{{ url('/search') }}"
                    method="GET"
                    class="hero-search"
                >

                    <div class="row g-2">

                        <div class="col-lg-5">

                            <div class="search-input">

                                <i class="bi bi-search"></i>

                                <input
                                    type="text"
                                    name="keyword"
                                    class="form-control"
                                    placeholder="Job title, keyword or company"
                                >

                            </div>

                        </div>


                        <div class="col-lg-4">

                            <div class="search-input">

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


                <!-- Popular Searches -->

                <div class="popular-searches">

                    <span>
                        Popular:
                    </span>


                    <a href="{{ url('/search') }}">
                        Software Engineer
                    </a>


                    <a href="{{ url('/search') }}">
                        Marketing
                    </a>


                    <a href="{{ url('/search') }}">
                        Data Analyst
                    </a>


                    <a href="{{ url('/search') }}">
                        Remote Jobs
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================
     FEATURED JOBS
========================================= -->

<section class="section-padding bg-light-subtle">

    <div class="container">

        <div class="section-heading-row">

            <div>

                <span class="section-label">
                    FRESH OPPORTUNITIES
                </span>


                <h2>
                    Featured jobs
                </h2>


                <p>
                    Explore some of the latest opportunities.
                </p>

            </div>


            <a
                href="{{ url('/search') }}"
                class="view-all-link"
            >

                View all jobs

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>


        <div class="row g-4">


            <!-- JOB 1 -->

            <div class="col-md-6 col-xl-4">

                <div class="job-card">

                    <div class="job-card-top">

                        <div class="company-logo logo-purple">
                            N
                        </div>


                        <span class="job-badge">
                            Featured
                        </span>

                    </div>


                    <h3 class="job-title">

                        <a href="{{ url('/job-details') }}">
                            Senior Software Engineer
                        </a>

                    </h3>


                    <div class="company-name">
                        Nova Technologies
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


                    <div class="job-card-footer">

                        <small>
                            Posted 2 days ago
                        </small>


                        <button
                            type="button"
                            class="save-job"
                        >
                            <i class="bi bi-bookmark"></i>
                        </button>

                    </div>

                </div>

            </div>


            <!-- JOB 2 -->

            <div class="col-md-6 col-xl-4">

                <div class="job-card">

                    <div class="job-card-top">

                        <div class="company-logo logo-orange">
                            A
                        </div>


                        <span class="job-badge">
                            New
                        </span>

                    </div>


                    <h3 class="job-title">

                        <a href="{{ url('/job-details') }}">
                            Product Designer
                        </a>

                    </h3>


                    <div class="company-name">
                        Atlas Labs
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
                            ₹12–20 LPA
                        </span>

                        <span>
                            Remote
                        </span>

                    </div>


                    <div class="job-card-footer">

                        <small>
                            Posted 1 day ago
                        </small>


                        <button
                            type="button"
                            class="save-job"
                        >
                            <i class="bi bi-bookmark"></i>
                        </button>

                    </div>

                </div>

            </div>


            <!-- JOB 3 -->

            <div class="col-md-6 col-xl-4">

                <div class="job-card">

                    <div class="job-card-top">

                        <div class="company-logo logo-green">
                            C
                        </div>


                        <span class="job-badge">
                            Hot
                        </span>

                    </div>


                    <h3 class="job-title">

                        <a href="{{ url('/job-details') }}">
                            Data Analyst
                        </a>

                    </h3>


                    <div class="company-name">
                        CloudPeak
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
                            ₹9–15 LPA
                        </span>

                        <span>
                            Hybrid
                        </span>

                    </div>


                    <div class="job-card-footer">

                        <small>
                            Posted 3 days ago
                        </small>


                        <button
                            type="button"
                            class="save-job"
                        >
                            <i class="bi bi-bookmark"></i>
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================
     COMPANIES
========================================= -->

<section
    class="section-padding"
    id="companies"
>

    <div class="container">

        <div class="text-center section-heading">

            <span class="section-label">
                TOP EMPLOYERS
            </span>


            <h2>
                Companies hiring now
            </h2>


            <p>
                Find your next opportunity at companies you'll love.
            </p>

        </div>


        <div class="row g-4">


            <div class="col-6 col-md-3">

                <div class="company-card">

                    <div class="company-logo logo-blue">
                        T
                    </div>

                    <h4>
                        TechNova
                    </h4>

                    <p>
                        42 open roles
                    </p>

                </div>

            </div>


            <div class="col-6 col-md-3">

                <div class="company-card">

                    <div class="company-logo logo-orange">
                        A
                    </div>

                    <h4>
                        Atlas Labs
                    </h4>

                    <p>
                        18 open roles
                    </p>

                </div>

            </div>


            <div class="col-6 col-md-3">

                <div class="company-card">

                    <div class="company-logo logo-green">
                        C
                    </div>

                    <h4>
                        CloudPeak
                    </h4>

                    <p>
                        27 open roles
                    </p>

                </div>

            </div>


            <div class="col-6 col-md-3">

                <div class="company-card">

                    <div class="company-logo logo-purple">
                        N
                    </div>

                    <h4>
                        Nova Technologies
                    </h4>

                    <p>
                        31 open roles
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================
     CTA
========================================= -->

<section class="main-cta">

    <div class="container text-center">

        <h2>
            Ready for your next opportunity?
        </h2>


        <p>
            Start exploring jobs and take the next step in your career.
        </p>


        <a
            href="{{ url('/search') }}"
            class="btn btn-light btn-lg px-4"
        >
            Explore Jobs
        </a>

    </div>

</section>

@endsection