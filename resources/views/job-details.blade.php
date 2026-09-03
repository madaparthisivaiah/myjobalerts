@extends('layouts.app')

@section('title', 'Senior Software Engineer - JobBoard')


@section('content')

<!-- =========================================
     JOB HEADER
========================================= -->

<section class="job-detail-header">

    <div class="container">

        <a
            href="{{ url('/search') }}"
            class="back-link"
        >

            <i class="bi bi-arrow-left"></i>

            Back to search

        </a>


        <div class="job-header-content">

            <div class="company-logo logo-purple large-logo">
                N
            </div>


            <div>

                <div class="mb-2">

                    <span class="job-badge">
                        Featured
                    </span>


                    <span class="posted-badge">
                        Posted 2 days ago
                    </span>

                </div>


                <h1>
                    Senior Software Engineer
                </h1>


                <div class="company-name detail-company">
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


                    <span>
                        <i class="bi bi-house"></i>
                        Hybrid
                    </span>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================
     JOB CONTENT
========================================= -->

<main class="container job-detail-area">

    <div class="row g-5">


        <!-- MAIN CONTENT -->

        <article class="col-lg-8">

            <div class="job-content-card">


                <h2>
                    About the role
                </h2>


                <p>

                    We’re looking for a Senior Software Engineer to build
                    reliable, scalable products used by millions of people.
                    You’ll work with product, design and engineering teams
                    to deliver high-quality features.

                </p>


                <p>

                    This role is ideal for an experienced engineer who enjoys
                    solving complex technical problems and building systems
                    that can scale.

                </p>


                <h2>
                    Responsibilities
                </h2>


                <ul class="detail-list">

                    <li>
                        Design and develop scalable web applications using
                        PHP and Laravel.
                    </li>

                    <li>
                        Write clean, tested and maintainable production code.
                    </li>

                    <li>
                        Collaborate with product, design and frontend teams.
                    </li>

                    <li>
                        Review code and improve engineering standards.
                    </li>

                    <li>
                        Monitor application performance and troubleshoot
                        production issues.
                    </li>

                    <li>
                        Participate in technical architecture discussions.
                    </li>

                </ul>


                <h2>
                    Requirements
                </h2>


                <ul class="detail-list">

                    <li>
                        5+ years of software development experience.
                    </li>

                    <li>
                        Strong PHP and Laravel experience.
                    </li>

                    <li>
                        Experience with MySQL and REST APIs.
                    </li>

                    <li>
                        Strong knowledge of Git and software development
                        best practices.
                    </li>

                    <li>
                        Experience with queues, caching and application
                        performance.
                    </li>

                    <li>
                        Strong communication and problem-solving skills.
                    </li>

                </ul>


                <h2>
                    Benefits
                </h2>


                <div class="row g-3">


                    <div class="col-md-6">

                        <div class="benefit-card">

                            <i class="bi bi-heart"></i>

                            <span>
                                Health insurance
                            </span>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="benefit-card">

                            <i class="bi bi-house"></i>

                            <span>
                                Flexible hybrid work
                            </span>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="benefit-card">

                            <i class="bi bi-mortarboard"></i>

                            <span>
                                Learning budget
                            </span>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="benefit-card">

                            <i class="bi bi-calendar-check"></i>

                            <span>
                                Generous paid leave
                            </span>

                        </div>

                    </div>

                </div>


                <h2>
                    About the company
                </h2>


                <p>

                    Nova Technologies is a product technology company
                    building tools that make work simpler and more connected.

                </p>


                <p>

                    Our teams work across engineering, product and design
                    to create products that solve real-world problems.

                </p>

            </div>

        </article>


        <!-- =========================================
             SIDEBAR
        ========================================= -->

        <aside class="col-lg-4">


            <!-- APPLY -->

            <div class="apply-card">

                <div class="salary-label">
                    Salary
                </div>


                <div class="salary-value">
                    ₹18–28 LPA
                </div>


                <button
                    type="button"
                    class="btn btn-primary btn-lg w-100 mt-3"
                >
                    Apply for this job
                </button>


                <button
                    type="button"
                    class="btn btn-outline-secondary btn-lg w-100 mt-2"
                >

                    <i class="bi bi-bookmark"></i>

                    Save job

                </button>


                <hr>


                <h3>
                    Job overview
                </h3>


                <div class="overview-item">

                    <i class="bi bi-briefcase"></i>

                    <div>

                        <small>
                            Job type
                        </small>

                        <strong>
                            Full-time
                        </strong>

                    </div>

                </div>


                <div class="overview-item">

                    <i class="bi bi-geo-alt"></i>

                    <div>

                        <small>
                            Location
                        </small>

                        <strong>
                            Hyderabad, India
                        </strong>

                    </div>

                </div>


                <div class="overview-item">

                    <i class="bi bi-house"></i>

                    <div>

                        <small>
                            Work mode
                        </small>

                        <strong>
                            Hybrid
                        </strong>

                    </div>

                </div>


                <div class="overview-item">

                    <i class="bi bi-clock"></i>

                    <div>

                        <small>
                            Experience
                        </small>

                        <strong>
                            5+ years
                        </strong>

                    </div>

                </div>


                <div class="overview-item">

                    <i class="bi bi-code-slash"></i>

                    <div>

                        <small>
                            Skills
                        </small>

                        <strong>
                            PHP, Laravel, MySQL
                        </strong>

                    </div>

                </div>

            </div>


            <!-- COMPANY -->

            <div class="company-sidebar-card">

                <div class="company-logo logo-purple">
                    N
                </div>


                <h3>
                    Nova Technologies
                </h3>


                <p>
                    Technology · 500+ employees
                </p>


                <a href="#">

                    View company profile

                    <i class="bi bi-arrow-right"></i>

                </a>

            </div>

        </aside>

    </div>

</main>

@endsection