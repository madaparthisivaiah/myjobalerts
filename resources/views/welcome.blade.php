@extends('layouts.app')

@section('title', 'CareerJet Jobs Coming Soon - JobBoard')

@section('content')

<section class="welcome-page">
    <div class="container">
        <div class="row align-items-center min-vh-75">

            <!-- Left Content -->
            <div class="col-lg-7">
                <div class="welcome-content">

                    <div class="welcome-badge">
                        <span class="welcome-status-dot"></span>
                        Currently in development
                    </div>

                    <h1 class="welcome-title">
                        Your next career opportunity
                        <span>is coming soon.</span>
                    </h1>

                    <p class="welcome-description">
                        We're working behind the scenes to bring you a powerful
                        collection of CareerJet jobs, all in one beautiful and
                        easy-to-use place.
                    </p>

                    <p class="welcome-description">
                        Our job listings are currently being processed and prepared.
                        Very soon, you'll be able to discover thousands of career
                        opportunities right here.
                    </p>

                    <div class="welcome-actions">
                        <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-house-door me-2"></i>
                            Back to Home
                        </a>

                        <a href="#coming-soon" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-info-circle me-2"></i>
                            What's Coming
                        </a>
                    </div>

                </div>
            </div>

            <!-- Right Visual -->
            <div class="col-lg-5 mt-5 mt-lg-0">
                <div class="welcome-visual">

                    <div class="welcome-icon-wrapper">
                        <div class="welcome-icon">
                            <i class="bi bi-briefcase-fill"></i>
                        </div>
                    </div>

                    <div class="welcome-floating-card card-one">
                        <div class="floating-icon">
                            <i class="bi bi-search"></i>
                        </div>
                        <div>
                            <strong>Find Jobs</strong>
                            <small>Thousands of opportunities</small>
                        </div>
                    </div>

                    <div class="welcome-floating-card card-two">
                        <div class="floating-icon success">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div>
                            <strong>Coming Soon</strong>
                            <small>Jobs are being prepared</small>
                        </div>
                    </div>

                    <div class="welcome-main-card">
                        <div class="welcome-card-header">
                            <div class="mini-logo">
                                <i class="bi bi-briefcase-fill"></i>
                            </div>

                            <div>
                                <strong>Career Opportunities</strong>
                                <small>Coming to JobBoard</small>
                            </div>
                        </div>

                        <div class="placeholder-line large"></div>
                        <div class="placeholder-line"></div>
                        <div class="placeholder-line short"></div>

                        <div class="welcome-card-tags">
                            <span>Technology</span>
                            <span>Marketing</span>
                            <span>Finance</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>


<section class="coming-section" id="coming-soon">
    <div class="container">

        <div class="section-heading text-center">
            <span class="section-eyebrow">WHAT WE'RE BUILDING</span>

            <h2>
                A better way to discover your next job
            </h2>

            <p>
                We're preparing everything to make your job search
                faster, simpler and more useful.
            </p>
        </div>

        <div class="row g-4 mt-4">

            <div class="col-md-4">
                <div class="coming-card">
                    <div class="coming-card-icon">
                        <i class="bi bi-search"></i>
                    </div>

                    <h3>Thousands of Jobs</h3>

                    <p>
                        Explore CareerJet job opportunities across
                        different industries, companies and locations.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="coming-card">
                    <div class="coming-card-icon">
                        <i class="bi bi-funnel"></i>
                    </div>

                    <h3>Smart Job Search</h3>

                    <p>
                        Find relevant opportunities quickly with
                        powerful search and filtering options.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="coming-card">
                    <div class="coming-card-icon">
                        <i class="bi bi-lightning-charge"></i>
                    </div>

                    <h3>Fresh Opportunities</h3>

                    <p>
                        Discover newly processed jobs and stay closer
                        to the opportunities that matter to you.
                    </p>
                </div>
            </div>

        </div>

    </div>
</section>


<section class="welcome-cta">
    <div class="container">
        <div class="welcome-cta-inner text-center">

            <div class="cta-icon">
                <i class="bi bi-stars"></i>
            </div>

            <h2>
                Something exciting is on the way.
            </h2>

            <p>
                We're putting the finishing touches on the platform.
                CareerJet jobs will be available here very soon.
            </p>

            <a href="{{ url('/') }}" class="btn btn-light btn-lg">
                Explore JobBoard
                <i class="bi bi-arrow-right ms-2"></i>
            </a>

        </div>
    </div>
</section>

@endsection


@section('styles')

<style>

    .min-vh-75 {
        min-height: 75vh;
    }

    .welcome-page {
        background:
            radial-gradient(circle at 85% 20%, rgba(79, 70, 229, 0.08), transparent 30%),
            radial-gradient(circle at 10% 80%, rgba(99, 102, 241, 0.06), transparent 25%),
            #ffffff;
        padding: 50px 0 80px;
        overflow: hidden;
    }

    .welcome-content {
        max-width: 680px;
    }

    .welcome-badge {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 8px 14px;
        border-radius: 50px;
        background: var(--primary-light);
        color: var(--primary);
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 22px;
    }

    .welcome-status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #22c55e;
        box-shadow: 0 0 0 5px rgba(34, 197, 94, 0.12);
    }

    .welcome-title {
        font-size: clamp(42px, 5vw, 68px);
        line-height: 1.05;
        letter-spacing: -2px;
        font-weight: 800;
        color: var(--navy);
        margin-bottom: 24px;
    }

    .welcome-title span {
        display: block;
        color: var(--primary);
    }

    .welcome-description {
        max-width: 620px;
        color: var(--text-secondary);
        font-size: 17px;
        line-height: 1.75;
        margin-bottom: 12px;
    }

    .welcome-actions {
        display: flex;
        gap: 12px;
        margin-top: 30px;
        flex-wrap: wrap;
    }

    .welcome-actions .btn {
        border-radius: 10px;
        padding: 12px 20px;
        font-weight: 600;
    }


    /* Visual */

    .welcome-visual {
        position: relative;
        min-height: 480px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .welcome-icon-wrapper {
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .welcome-icon {
        width: 130px;
        height: 130px;
        border-radius: 32px;
        background: linear-gradient(
            135deg,
            var(--primary),
            var(--primary-dark)
        );
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 55px;
        box-shadow: 0 25px 50px rgba(79, 70, 229, 0.25);
    }

    .welcome-main-card {
        position: relative;
        z-index: 2;
        width: 330px;
        padding: 24px;
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 18px;
        box-shadow: var(--shadow-md);
    }

    .welcome-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
    }

    .mini-logo {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background: var(--primary-light);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .welcome-card-header strong,
    .welcome-card-header small {
        display: block;
    }

    .welcome-card-header strong {
        color: var(--navy);
        font-size: 14px;
    }

    .welcome-card-header small {
        color: var(--text-muted);
        font-size: 12px;
        margin-top: 3px;
    }

    .placeholder-line {
        height: 10px;
        width: 85%;
        background: var(--background-soft);
        border-radius: 10px;
        margin-bottom: 12px;
    }

    .placeholder-line.large {
        width: 100%;
        height: 15px;
    }

    .placeholder-line.short {
        width: 55%;
    }

    .welcome-card-tags {
        display: flex;
        gap: 7px;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .welcome-card-tags span {
        background: var(--primary-light);
        color: var(--primary);
        padding: 6px 10px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 600;
    }

    .welcome-floating-card {
        position: absolute;
        z-index: 3;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 13px 15px;
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: var(--shadow-md);
    }

    .welcome-floating-card strong,
    .welcome-floating-card small {
        display: block;
    }

    .welcome-floating-card strong {
        font-size: 12px;
        color: var(--navy);
    }

    .welcome-floating-card small {
        font-size: 10px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .floating-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--primary-light);
        color: var(--primary);
    }

    .floating-icon.success {
        background: var(--green-bg);
        color: var(--green-text);
    }

    .card-one {
        top: 65px;
        left: 0;
    }

    .card-two {
        bottom: 55px;
        right: 0;
    }


    /* Coming Soon Section */

    .coming-section {
        background: var(--background-soft);
        padding: 90px 0;
    }

    .section-eyebrow {
        color: var(--primary);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.5px;
    }

    .section-heading h2 {
        color: var(--navy);
        font-size: 36px;
        font-weight: 800;
        margin: 10px 0;
    }

    .section-heading p {
        color: var(--text-secondary);
        max-width: 600px;
        margin: auto;
    }

    .coming-card {
        height: 100%;
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 30px;
        transition: all 0.25s ease;
    }

    .coming-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .coming-card-icon {
        width: 52px;
        height: 52px;
        border-radius: 13px;
        background: var(--primary-light);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 20px;
    }

    .coming-card h3 {
        color: var(--navy);
        font-size: 19px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .coming-card p {
        color: var(--text-secondary);
        line-height: 1.7;
        margin-bottom: 0;
    }


    /* CTA */

    .welcome-cta {
        padding: 80px 0;
        background: #ffffff;
    }

    .welcome-cta-inner {
        background: linear-gradient(
            135deg,
            var(--navy),
            var(--navy-light)
        );
        border-radius: 24px;
        padding: 65px 25px;
        color: #ffffff;
        overflow: hidden;
        position: relative;
    }

    .welcome-cta-inner h2 {
        font-size: 36px;
        font-weight: 800;
        margin: 15px 0;
    }

    .welcome-cta-inner p {
        max-width: 600px;
        margin: 0 auto 25px;
        color: #cbd5e1;
        line-height: 1.7;
    }

    .cta-icon {
        width: 55px;
        height: 55px;
        margin: auto;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
    }

    .welcome-cta .btn {
        border-radius: 10px;
        font-weight: 700;
        padding: 12px 22px;
    }


    /* Responsive */

    @media (max-width: 991px) {

        .welcome-page {
            padding-top: 30px;
        }

        .welcome-content {
            max-width: 100%;
            text-align: center;
        }

        .welcome-description {
            margin-left: auto;
            margin-right: auto;
        }

        .welcome-actions {
            justify-content: center;
        }

        .welcome-visual {
            min-height: 430px;
        }

    }

    @media (max-width: 767px) {

        .welcome-title {
            font-size: 42px;
            letter-spacing: -1.5px;
        }

        .welcome-description {
            font-size: 15px;
        }

        .welcome-visual {
            transform: scale(0.9);
            margin-top: -20px;
        }

        .section-heading h2,
        .welcome-cta-inner h2 {
            font-size: 29px;
        }

        .coming-section {
            padding: 65px 0;
        }

    }

    @media (max-width: 480px) {

        .welcome-title {
            font-size: 36px;
        }

        .welcome-actions .btn {
            width: 100%;
        }

        .welcome-visual {
            transform: scale(0.78);
            margin-left: -30px;
            margin-right: -30px;
        }

    }

</style>

@endsection