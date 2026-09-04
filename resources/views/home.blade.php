@extends('layouts.app')

@section('title', 'Find Your Next Job - JobBoard')

@section('content')

{{-- =========================================================
     HERO
========================================================= --}}

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

                <form
                    action="{{ route('jobs.index') }}"
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
                                    value="{{ request('keyword') }}"
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
                                    value="{{ request('location', $city ?? '') }}"
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

                <div class="popular-searches">
                    <span>Popular:</span>

                    <a href="{{ route('jobs.index', ['keyword' => 'Software Engineer']) }}">
                        Software Engineer
                    </a>

                    <a href="{{ route('jobs.index', ['keyword' => 'Marketing']) }}">
                        Marketing
                    </a>

                    <a href="{{ route('jobs.index', ['keyword' => 'Data Analyst']) }}">
                        Data Analyst
                    </a>

                    <a href="{{ route('jobs.index', ['keyword' => 'Remote']) }}">
                        Remote Jobs
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>


{{-- =========================================================
INDIA STATES
========================================================= --}}

<section class="section-padding bg-light-subtle">
    <div class="container">

        <div class="text-center section-heading">

            <span class="section-label">
                JOBS BY STATE
            </span>
            <h2>
                Find Jobs by Popular State in India
            </h2>
            <p>
                Explore job opportunities across different states in India.
            </p>
        </div>

        <div class="row g-4">
            @foreach($states as $key => $state)
            @if(!empty($state['jobs']) && $state['jobs'] > 1000)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a
                    href="{{ url('/jobs/' . \Illuminate\Support\Str::slug($state['name'])) }}"
                    class="state-card"
                >
                    <div class="state-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>

                    <div class="state-content">
                        <h3 class="company-name">
                            {{ $state['name'] }}
                        </h3>

                        <span class="state-link">
                            View jobs
                            <i class="bi bi-arrow-right"></i>
                        </span>
                    </div>
                </a>
            </div>
            @endif
        @endforeach

        </div>

    </div>
</section>

<section class="section-padding bg-light-subtle">
    <div class="container">

        <div class="text-center section-heading">

            <span class="section-label">
                JOBS BY CITY
            </span>
            <h2>
                Find Jobs by Popular Cities in India
            </h2>
            <p>
                Explore job opportunities across different cities in India.
            </p>
        </div>

        <div class="row g-4">
            @foreach($cities as $key => $city)
            @if(!empty($city['jobs']) && $city['jobs'] > 1000)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a
                    href="{{ url('/jobs/' . \Illuminate\Support\Str::slug($city['name'])) }}"
                    class="state-card"
                >
                    <div class="state-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>

                    <div class="state-content">
                        <h3 class="company-name">
                            {{ $city['name'] }}
                        </h3>

                        <span class="state-link">
                            View jobs
                            <i class="bi bi-arrow-right"></i>
                        </span>
                    </div>
                </a>
            </div>
            @endif
        @endforeach

        </div>

    </div>
</section>


{{-- =========================================================
     TOP COMPANIES
========================================================= --}}

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

        </div>

        <div class="row g-4">

            @forelse($companies as $key => $company)
               @if(!empty($company['jobs']) && $company['jobs'] > 2000)
                <div class="col-lg-3 col-md-6">

                    <div class="company-card h-100">

                        <div class="company-icon">
                            <i class="bi bi-building"></i>
                        </div>

                        <h3 class="company-name">
                            {{ $company['name'] }}
                        </h3>

                        <p class="company-jobs">
                            {{ $company['jobs'] }}
                            {{ $company['jobs'] == 1 ? 'job' : 'jobs' }}
                        </p>

                        <a href="{{ url('/company/' . \Illuminate\Support\Str::slug($company['name']) . '-jobs') }}"
class="state-card">
                            View jobs
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </div>

                </div>
                @endif

            @empty

                <div class="col-12">

                    <div class="alert alert-light text-center">
                        No companies found.
                    </div>

                </div>

            @endforelse

        </div>

    </div>

</section>


{{-- =========================================================
     CTA
========================================================= --}}

<section class="main-cta">

    <div class="container text-center">

        <h2>
            Ready for your next opportunity?
        </h2>

        <p>
            Start exploring jobs and take the next step in your career.
        </p>

        <a
            href="{{ route('jobs.index') }}"
            class="btn btn-light btn-lg px-4"
        >
            Explore Jobs
        </a>

    </div>

</section>


{{-- =========================================================
     HOMEPAGE STYLES
========================================================= --}}

<style>

.job-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    transition: all 0.2s ease;
    overflow: hidden;
}

.job-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
}

.job-card-body {
    padding: 24px;
}

.job-title {
    font-size: 19px;
    font-weight: 600;
    line-height: 1.4;
    margin-bottom: 16px;
}

.job-company,
.job-location,
.job-salary,
.job-contract,
.job-date {
    font-size: 14px;
    margin-bottom: 8px;
}

.job-company i,
.job-location i,
.job-salary i,
.job-contract i,
.job-date i {
    margin-right: 6px;
}

.job-description {
    font-size: 14px;
    line-height: 1.6;
    margin: 16px 0;
    color: #6c757d;
}

.job-date {
    color: #888;
    margin-bottom: 16px;
}

.company-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    transition: all 0.2s ease;
}

.company-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
}

.company-icon {
    width: 55px;
    height: 55px;
    margin: 0 auto 16px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f1f3f5;
    font-size: 24px;
}

.company-name {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
}

.company-jobs {
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 15px;
}

.company-link {
    font-size: 14px;
    text-decoration: none;
}

.company-link i {
    margin-left: 4px;
}

</style>

@endsection