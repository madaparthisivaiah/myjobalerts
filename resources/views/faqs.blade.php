@extends('layouts.app')
@section('title', 'Frequently Asked Questions | MyJobAlerts')
@section('meta_description','Find answers to frequently asked questions about searching for jobs, companies, locations, job listings and applying for jobs on MyJobAlerts.')
@section('content')

{{-- =========================================================
FAQ HERO
========================================================= --}}
<section class="faq-hero static-pages mt-4">
<div class="container">
    <div class="row align-items-center g-4 pb-4">

        {{-- LEFT --}}
        <div class="col-lg-7">

            <div class="faq-hero-content">

                <span class="section-label">
                    <i class="bi bi-patch-question-fill me-1"></i>
                    FREQUENTLY ASKED QUESTIONS
                </span>

                <h1 class="fw-bold mt-3 mb-3">
                    Everything You Need to Know
                    <span class="text-primary">About MyJobAlerts</span>
                </h1>

                <p class="faq-hero-description text-white mb-4">
                    Have questions about finding jobs, searching by
                    location, exploring companies or applying for
                    opportunities? Find quick answers to the most
                    common questions below.
                </p>

                <div class="d-flex flex-wrap gap-2">

                    <a href="{{ route('jobs.index') }}"
                       class="btn btn-primary px-4 py-2">

                        <i class="bi bi-search me-2"></i>
                        Browse Jobs

                    </a>

                    <a href="{{ url('/contact') }}"
                       class="btn btn-outline-secondary px-4 py-2">

                        <i class="bi bi-envelope me-2"></i>
                        Contact Us

                    </a>

                </div>

            </div>

        </div>


        {{-- RIGHT --}}
        <div class="col-lg-5">

            <div class="faq-hero-panel">

                <div class="faq-panel-top">

                    <div class="faq-panel-icon">
                        <i class="bi bi-question-lg"></i>
                    </div>

                    <div>

                        <span class="faq-panel-label">
                            NEED HELP?
                        </span>

                        <h5 class="fw-bold mb-0">
                            We're here to help
                        </h5>

                    </div>
                </div>
                <div class="faq-panel-divider"></div>
                <div class="faq-stat-row">
                    <div class="faq-stat-icon">
                        <i class="bi bi-search"></i>
                    </div>
                    <div class="flex-grow-1">

                        <strong>
                            Search Jobs
                        </strong>

                        <small>
                            Find opportunities by keyword
                        </small>

                    </div>

                    <i class="bi bi-arrow-up-right faq-stat-arrow"></i>

                </div>


                <div class="faq-stat-row">

                    <div class="faq-stat-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>

                    <div class="flex-grow-1">

                        <strong>
                            Explore Locations
                        </strong>

                        <small>
                            Search jobs across India
                        </small>

                    </div>
                    <i class="bi bi-arrow-up-right faq-stat-arrow"></i>
                </div>
                <div class="faq-stat-row">
                    <div class="faq-stat-icon">
                        <i class="bi bi-building"></i>
                    </div>

                    <div class="flex-grow-1">

                        <strong>
                            Discover Companies
                        </strong>

                        <small>
                            Explore opportunities by company
                        </small>

                    </div>
                    <i class="bi bi-arrow-up-right faq-stat-arrow"></i>
                </div>
            </div>
        </div>
    </div>
</div>

</section>

{{-- =========================================================
FAQ SECTION
========================================================= --}}

<section class="section-padding faq-section">
<div class="container">
    {{-- HEADER --}}
    <div class="row align-items-end mb-4">

        <div class="col-lg-8">

            <span class="section-label">
                <i class="bi bi-chat-square-text-fill me-1"></i>
                COMMON QUESTIONS
            </span>

            <h2 class="fw-bold mt-2 mb-2">
                How Can We Help?
            </h2>

            <p class="text-muted mb-0">
                Browse the questions below to learn more about
                using MyJobAlerts.
            </p>

        </div>


        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">

            <div class="faq-total">

                <span class="faq-total-icon">
                    <i class="bi bi-question-circle"></i>
                </span>

                <span>
                    <strong>12</strong>
                    Common Questions
                </span>

            </div>

        </div>

    </div>


    {{-- FAQ GRID --}}
    <div class="row g-4">


        {{-- =================================================
            01
        ================================================== --}}
        <div class="col-lg-6">

            <div class="faq-card">

                <button
                    class="faq-question collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq01"
                    aria-expanded="false"
                    aria-controls="faq01">

                    <span class="faq-question-left">

                        <span class="faq-number">
                            01
                        </span>

                        <span class="faq-title">
                            What is MyJobAlerts?
                        </span>

                    </span>

                    <span class="faq-button-icon">
                        <i class="bi bi-plus"></i>
                    </span>

                </button>


                <div id="faq01" class="collapse">

                    <div class="faq-answer">

                        MyJobAlerts is an India-focused job search
                        platform that helps job seekers discover
                        job opportunities by keyword, company,
                        city, state and other available search
                        criteria.

                    </div>

                </div>

            </div>

        </div>


        {{-- 02 --}}
        <div class="col-lg-6">

            <div class="faq-card">

                <button
                    class="faq-question collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq02"
                    aria-expanded="false"
                    aria-controls="faq02">

                    <span class="faq-question-left">

                        <span class="faq-number">
                            02
                        </span>

                        <span class="faq-title">
                            How can I search for jobs?
                        </span>

                    </span>

                    <span class="faq-button-icon">
                        <i class="bi bi-plus"></i>
                    </span>

                </button>


                <div id="faq02" class="collapse">

                    <div class="faq-answer">

                        You can search for jobs using job titles,
                        skills, keywords, companies and locations.
                        You can also explore jobs by city and state.

                    </div>

                </div>

            </div>

        </div>


        {{-- 03 --}}
        <div class="col-lg-6">

            <div class="faq-card">

                <button
                    class="faq-question collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq03"
                    aria-expanded="false"
                    aria-controls="faq03">

                    <span class="faq-question-left">

                        <span class="faq-number">
                            03
                        </span>

                        <span class="faq-title">
                            Can I search for jobs by city?
                        </span>

                    </span>

                    <span class="faq-button-icon">
                        <i class="bi bi-plus"></i>
                    </span>

                </button>


                <div id="faq03" class="collapse">

                    <div class="faq-answer">

                        Yes. You can search for job opportunities
                        by city and explore vacancies available
                        across cities in India.

                    </div>

                </div>

            </div>

        </div>


        {{-- 04 --}}
        <div class="col-lg-6">

            <div class="faq-card">

                <button
                    class="faq-question collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq04"
                    aria-expanded="false"
                    aria-controls="faq04">

                    <span class="faq-question-left">

                        <span class="faq-number">
                            04
                        </span>

                        <span class="faq-title">
                            Can I search for jobs by state?
                        </span>

                    </span>

                    <span class="faq-button-icon">
                        <i class="bi bi-plus"></i>
                    </span>

                </button>


                <div id="faq04" class="collapse">

                    <div class="faq-answer">

                        Yes. MyJobAlerts lets you explore job
                        opportunities by state and discover
                        vacancies across different parts of India.

                    </div>

                </div>

            </div>

        </div>


        {{-- 05 --}}
        <div class="col-lg-6">

            <div class="faq-card">

                <button
                    class="faq-question collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq05"
                    aria-expanded="false"
                    aria-controls="faq05">

                    <span class="faq-question-left">

                        <span class="faq-number">
                            05
                        </span>

                        <span class="faq-title">
                            Can I search for jobs by company?
                        </span>

                    </span>

                    <span class="faq-button-icon">
                        <i class="bi bi-plus"></i>
                    </span>

                </button>


                <div id="faq05" class="collapse">

                    <div class="faq-answer">

                        Yes. You can search for opportunities
                        associated with specific companies when
                        company information is available.

                    </div>

                </div>

            </div>

        </div>


        {{-- 06 --}}
        <div class="col-lg-6">

            <div class="faq-card">

                <button
                    class="faq-question collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq06"
                    aria-expanded="false"
                    aria-controls="faq06">

                    <span class="faq-question-left">

                        <span class="faq-number">
                            06
                        </span>

                        <span class="faq-title">
                            How do I apply for a job?
                        </span>

                    </span>

                    <span class="faq-button-icon">
                        <i class="bi bi-plus"></i>
                    </span>

                </button>


                <div id="faq06" class="collapse">

                    <div class="faq-answer">

                        Open the job listing you are interested in
                        and click the <strong>Apply Now</strong>
                        button. You may be redirected to an external
                        website where the application is available.

                    </div>

                </div>

            </div>

        </div>


        {{-- 07 --}}
        <div class="col-lg-6">

            <div class="faq-card">

                <button
                    class="faq-question collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq07"
                    aria-expanded="false"
                    aria-controls="faq07">

                    <span class="faq-question-left">

                        <span class="faq-number">
                            07
                        </span>

                        <span class="faq-title">
                            Does MyJobAlerts charge job seekers?
                        </span>

                    </span>

                    <span class="faq-button-icon">
                        <i class="bi bi-plus"></i>
                    </span>

                </button>


                <div id="faq07" class="collapse">

                    <div class="faq-answer">

                        No. Searching for jobs and browsing
                        available job listings on MyJobAlerts
                        is free for job seekers.

                    </div>

                </div>

            </div>

        </div>


        {{-- 08 --}}
        <div class="col-lg-6">

            <div class="faq-card">

                <button
                    class="faq-question collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq08"
                    aria-expanded="false"
                    aria-controls="faq08">

                    <span class="faq-question-left">

                        <span class="faq-number">
                            08
                        </span>

                        <span class="faq-title">
                            Are jobs posted directly by MyJobAlerts?
                        </span>

                    </span>

                    <span class="faq-button-icon">
                        <i class="bi bi-plus"></i>
                    </span>

                </button>


                <div id="faq08" class="collapse">

                    <div class="faq-answer">

                        MyJobAlerts provides a platform for
                        discovering job opportunities from
                        available job sources. Listings may
                        originate from employers, recruitment
                        platforms or job advertising networks.

                    </div>

                </div>

            </div>

        </div>


        {{-- 09 --}}
        <div class="col-lg-6">

            <div class="faq-card">

                <button
                    class="faq-question collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq09"
                    aria-expanded="false"
                    aria-controls="faq09">

                    <span class="faq-question-left">

                        <span class="faq-number">
                            09
                        </span>

                        <span class="faq-title">
                            Why am I redirected to another website?
                        </span>

                    </span>

                    <span class="faq-button-icon">
                        <i class="bi bi-plus"></i>
                    </span>

                </button>


                <div id="faq09" class="collapse">

                    <div class="faq-answer">

                        The actual application may be hosted on an
                        external employer, recruitment or job
                        platform. Clicking Apply Now may therefore
                        take you to the website where the application
                        is available.

                    </div>

                </div>

            </div>

        </div>


        {{-- 10 --}}
        <div class="col-lg-6">

            <div class="faq-card">

                <button
                    class="faq-question collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq10"
                    aria-expanded="false"
                    aria-controls="faq10">

                    <span class="faq-question-left">

                        <span class="faq-number">
                            10
                        </span>

                        <span class="faq-title">
                            How often are job listings updated?
                        </span>

                    </span>

                    <span class="faq-button-icon">
                        <i class="bi bi-plus"></i>
                    </span>

                </button>


                <div id="faq10" class="collapse">

                    <div class="faq-answer">

                        Job availability can change frequently.
                        Listings are updated based on the job
                        sources and feeds used by MyJobAlerts.

                    </div>

                </div>

            </div>

        </div>


        {{-- 11 --}}
        <div class="col-lg-6">

            <div class="faq-card">

                <button
                    class="faq-question collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq11"
                    aria-expanded="false"
                    aria-controls="faq11">

                    <span class="faq-question-left">

                        <span class="faq-number">
                            11
                        </span>

                        <span class="faq-title">
                            What if a job is no longer available?
                        </span>

                    </span>

                    <span class="faq-button-icon">
                        <i class="bi bi-plus"></i>
                    </span>

                </button>


                <div id="faq11" class="collapse">

                    <div class="faq-answer">

                        Job vacancies can close or expire at any
                        time. If a job is no longer available,
                        return to MyJobAlerts and search for
                        similar opportunities.

                    </div>

                </div>

            </div>

        </div>


        {{-- 12 --}}
        <div class="col-lg-6">

            <div class="faq-card">

                <button
                    class="faq-question collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq12"
                    aria-expanded="false"
                    aria-controls="faq12">

                    <span class="faq-question-left">

                        <span class="faq-number">
                            12
                        </span>

                        <span class="faq-title">
                            How can I contact MyJobAlerts?
                        </span>

                    </span>

                    <span class="faq-button-icon">
                        <i class="bi bi-plus"></i>
                    </span>

                </button>


                <div id="faq12" class="collapse">

                    <div class="faq-answer">

                        If you have questions, feedback or
                        suggestions, please visit our Contact Us
                        page to get in touch with the MyJobAlerts
                        team.

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

</section>

{{-- =========================================================
STILL NEED HELP
========================================================= --}}

<section class="faq-help-section">

<div class="container">

    <div class="faq-help-box">

        <div class="row align-items-center g-4">

            <div class="col-lg-8">

                <span class="section-label">
                    <i class="bi bi-headset me-1"></i>
                    STILL NEED HELP?
                </span>

                <h2 class="fw-bold mt-2 mb-2">
                    Can't Find What You're Looking For?
                </h2>

                <p class="text-muted mb-0">
                    If you still have a question, feedback or
                    suggestion, our Contact Us page is the best
                    place to reach us.
                </p>

            </div>


            <div class="col-lg-4 text-lg-end">

                <a href="{{ url('/contact') }}"
                   class="btn btn-primary px-4 py-2">

                    <i class="bi bi-envelope me-2"></i>
                    Contact Us

                </a>

            </div>

        </div>

    </div>

</div>


</section>

{{-- =========================================================
JOB CTA
========================================================= --}}

<section class="section-padding bg-light-subtle border-top ">


<div class="container">

    <div class="text-center cta">

        <span class="section-label text-white">
            <i class="bi bi-briefcase-fill me-1"></i>
            START YOUR SEARCH
        </span>

        <h2 class="fw-bold mt-2 mb-2">
            Ready to Find Your Next Opportunity?
        </h2>

        <p class="text-white mb-4 faq-final-text mx-auto">
            Explore job opportunities across India by job title,
            company, city and state.
        </p>

        <a href="{{ route('jobs.index') }}"
           class="btn btn-primary px-4 py-2">

            <i class="bi bi-search me-2"></i>
            Explore Jobs

        </a>

    </div>

</div>

</section>

@endsection

{{-- =========================================================
FAQ CSS
========================================================= --}}
@push('styles')

<style>

    /* =====================================================
       HERO
    ===================================================== */

    .faq-hero {
        padding: 68px 0;

        background:
            radial-gradient(
                circle at 90% 20%,
                rgba(var(--bs-primary-rgb), .08),
                transparent 35%
            ),
            linear-gradient(
                180deg,
                #f8f9fa 0%,
                #ffffff 100%
            );

        border-bottom: 1px solid #e9ecef;
    }


    .faq-hero-content {
        max-width: 720px;
    }


    .faq-hero h1 {
        font-size: clamp(2.1rem, 4vw, 3.15rem);

        line-height: 1.15;

        letter-spacing: -.8px;
    }


    .faq-hero-description {
        max-width: 650px;

        font-size: 16px;

        line-height: 1.75;
    }


    /* =====================================================
       HERO PANEL
    ===================================================== */

    .faq-hero-panel {
        background: #fff;

        border: 1px solid #e7e9ed;

        border-radius: 20px;

        padding: 22px;

        box-shadow:
            0 15px 40px rgba(0, 0, 0, .07);

        transition:
            transform .25s ease,
            box-shadow .25s ease;
    }


    .faq-hero-panel:hover {
        transform: translateY(-4px);

        box-shadow:
            0 20px 45px rgba(0, 0, 0, .09);
    }


    .faq-panel-top {
        display: flex;

        align-items: center;

        gap: 13px;
    }


    .faq-panel-icon {
        width: 48px;
        height: 48px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 13px;

        background: rgba(var(--bs-primary-rgb), .10);

        color: var(--bs-primary);

        font-size: 21px;
    }


    .faq-panel-label {
        display: block;

        color: var(--bs-primary);

        font-size: 10px;

        font-weight: 700;

        letter-spacing: .9px;

        margin-bottom: 2px;
    }


    .faq-panel-divider {
        height: 1px;

        background: #edf0f2;

        margin: 20px 0 4px;
    }


    .faq-stat-row {
        display: flex;

        align-items: center;

        gap: 12px;

        padding: 13px 5px;

        border-bottom: 1px solid #f0f1f3;
    }


    .faq-stat-row:last-child {
        border-bottom: 0;

        padding-bottom: 4px;
    }


    .faq-stat-icon {
        width: 38px;
        height: 38px;

        flex: 0 0 38px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: #f7f8fa;

        color: var(--bs-primary);

        font-size: 15px;
    }


    .faq-stat-row strong {
        display: block;

        font-size: 13px;

        margin-bottom: 2px;
    }


    .faq-stat-row small {
        display: block;

        color: #7b828a;

        font-size: 11.5px;
    }


    .faq-stat-arrow {
        color: #adb5bd;

        font-size: 12px;

        transition:
            color .2s ease,
            transform .2s ease;
    }


    .faq-stat-row:hover .faq-stat-arrow {
        color: var(--bs-primary);

        transform: translate(2px, -2px);
    }


    /* =====================================================
       FAQ SECTION
    ===================================================== */

    .faq-section {
        background: #fff;
    }


    .faq-total {
        display: inline-flex;

        align-items: center;

        gap: 9px;

        padding: 8px 13px;

        border: 1px solid #e8eaed;

        border-radius: 50px;

        background: #fff;

        color: #6c757d;

        font-size: 12px;
    }


    .faq-total strong {
        color: #212529;

        margin-right: 2px;
    }


    .faq-total-icon {
        width: 25px;
        height: 25px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: rgba(var(--bs-primary-rgb), .08);

        color: var(--bs-primary);

        font-size: 12px;
    }


    /* =====================================================
       FAQ CARD
    ===================================================== */

    .faq-card {
        height: 100%;

        background: #fff;

        border: 1px solid #e5e8eb;

        border-radius: 15px;

        overflow: hidden;

        transition:
            transform .25s ease,
            border-color .25s ease,
            box-shadow .25s ease;
    }


    .faq-card:hover {
        transform: translateY(-3px);

        border-color: rgba(var(--bs-primary-rgb), .25);

        box-shadow:
            0 12px 30px rgba(0, 0, 0, .055);
    }


    /* =====================================================
       QUESTION
    ===================================================== */

    .faq-question {
        width: 100%;

        min-height: 76px;

        padding: 14px 16px;

        border: 0;

        background: #fff;

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 14px;

        text-align: left;

        cursor: pointer;

        color: #212529;

        transition: background .2s ease;
    }


    .faq-question:hover {
        background: #fcfcfd;
    }


    .faq-question:focus {
        outline: none;

        box-shadow: none;
    }


    .faq-question-left {
        min-width: 0;

        display: flex;

        align-items: center;

        gap: 13px;
    }


    /* =====================================================
       NUMBER
    ===================================================== */

    .faq-number {
        width: 40px;
        height: 40px;

        flex: 0 0 40px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 11px;

        background: rgba(var(--bs-primary-rgb), .075);

        color: var(--bs-primary);

        font-size: 10px;

        font-weight: 700;

        letter-spacing: .5px;

        transition:
            background .25s ease,
            color .25s ease;
    }


    .faq-question:not(.collapsed) .faq-number {
        background: var(--bs-primary);

        color: #fff;
    }


    /* =====================================================
       TITLE
    ===================================================== */

    .faq-title {
        font-size: 14px;

        font-weight: 600;

        line-height: 1.5;
    }


    /* =====================================================
       PLUS BUTTON
    ===================================================== */

    .faq-button-icon {
        width: 31px;
        height: 31px;

        flex: 0 0 31px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #f5f6f7;

        color: #6c757d;

        font-size: 17px;

        transition:
            transform .25s ease,
            background .25s ease,
            color .25s ease;
    }


    .faq-question:not(.collapsed) .faq-button-icon {
        background: var(--bs-primary);

        color: #fff;

        transform: rotate(45deg);
    }


    /* =====================================================
       ANSWER
    ===================================================== */

    .faq-answer {
        margin: 0 16px;

        padding: 0 4px 19px 53px;

        color: #6c757d;

        font-size: 13px;

        line-height: 1.75;

        border-top: 1px solid #f0f1f3;

        padding-top: 15px;
    }


    /* =====================================================
       HELP SECTION
    ===================================================== */

    .faq-help-section {
        padding: 0 0 55px;

        background: #fff;
    }


    .faq-help-box {
        padding: 30px 32px;

        border-radius: 18px;

        border: 1px solid #e6e9ed;

        background:
            linear-gradient(
                135deg,
                rgba(var(--bs-primary-rgb), .055),
                rgba(var(--bs-primary-rgb), .015)
            );

        box-shadow:
            0 8px 25px rgba(0, 0, 0, .035);
    }


    /* =====================================================
       FINAL CTA
    ===================================================== */

    .faq-final-text {
        max-width: 650px;

        line-height: 1.7;
    }


    /* =====================================================
       MOBILE
    ===================================================== */

    @media (max-width: 991.98px) {

        .faq-hero {
            padding: 50px 0;
        }


        .faq-hero-panel {
            max-width: 620px;

            margin: 5px auto 0;
        }

    }


    @media (max-width: 767.98px) {

        .faq-hero {
            padding: 40px 0;
        }


        .faq-hero h1 {
            font-size: 2rem;

            letter-spacing: -.4px;
        }


        .faq-hero-description {
            font-size: 14px;
        }


        .faq-total {
            display: none;
        }


        .faq-question {
            min-height: 68px;

            padding: 12px;
        }


        .faq-question-left {
            gap: 10px;
        }


        .faq-number {
            width: 36px;
            height: 36px;

            flex-basis: 36px;
        }


        .faq-title {
            font-size: 13.5px;
        }


        .faq-button-icon {
            width: 29px;
            height: 29px;

            flex-basis: 29px;
        }


        .faq-answer {
            margin: 0 12px;

            padding:
                13px 3px 17px 46px;

            font-size: 12.5px;
        }


        .faq-help-section {
            padding-bottom: 40px;
        }


        .faq-help-box {
            padding: 25px 20px;

            text-align: center;
        }

    }

</style>

@endpush
