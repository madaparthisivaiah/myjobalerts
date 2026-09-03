<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'MyJobAlerts - Find Your Dream Job')
    </title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <!-- JobBoard CSS -->
   <link href="{{ asset('css/jobboard.css') }}" rel="stylesheet">

    @yield('styles')

</head>

<body>


<!-- =========================================
     NAVBAR
========================================= -->

<nav class="navbar navbar-expand-lg navbar-dark main-navbar welcome-navbar">

    <div class="container">

        <a
            class="navbar-brand fw-bold"
            href="{{ url('/') }}"
        >

            <span class="brand-icon">
                <i class="bi bi-briefcase-fill"></i>
            </span>
            MyJobAlerts
        </a>
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <div
            class="collapse navbar-collapse"
            id="mainNavbar"
        >

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="{{ url('/') }}"
                    >
                        Find Jobs
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="{{ url('/search') }}"
                    >
                        Browse Jobs
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="{{ url('/#companies') }}"
                    >
                        Companies
                    </a>

                </li>


                <li class="nav-item ms-lg-3 mt-2 mt-lg-0">

                    <a
                        href="#"
                        class="btn btn-light post-job-btn"
                    >
                        Post a Job
                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>


<!-- =========================================
     PAGE CONTENT
========================================= -->

@yield('content')


<!-- =========================================
     FOOTER
========================================= -->

<footer class="site-footer">

    <div class="container">

        <div class="row gy-4">

            <div class="col-md-6">

                <div class="footer-brand">

                    <span class="brand-icon">
                        <i class="bi bi-briefcase-fill"></i>
                    </span>
                    MyJobAlerts
                </div>

                <p>
                    Find better opportunities and build your next career move.
                </p>

            </div>


            <div class="col-md-6 text-md-end">

                <a href="#">
                    Privacy
                </a>

                <a href="#">
                    Terms
                </a>

                <a href="#">
                    Contact
                </a>

            </div>

        </div>


        <hr>


        <div class="footer-bottom">

            © 2026 MyJobAlerts. All rights reserved.

        </div>

    </div>

</footer>


<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

@yield('scripts')

</body>

</html>