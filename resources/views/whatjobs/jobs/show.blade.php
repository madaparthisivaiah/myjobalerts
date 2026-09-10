@extends('layouts.app')
@php
$jobTitle = trim($job->title);

$description = trim(
    preg_replace(
        '/\s+/',
        ' ',
        strip_tags($job->snippet ?? '')
    )
);

if (!$description) {
    $description = $jobTitle . ' job opportunity on MyJobAlerts.';
}

$metaDescription = \Illuminate\Support\Str::limit(
    $description,
    155,
    '...'
);

$canonicalUrl = url('/viewjob/' . $job->slug);

@endphp
@section('title', $jobTitle . ' Jobs | MyJobAlerts')
@section('meta_description', $metaDescription)
@section('canonical', $canonicalUrl)
@section('content')

<section class="job-detail-header">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-9">

                <div class="mb-2">

                    <span class="job-badge">
                        Job Opportunity
                    </span>

                    @if(!is_null($job->age_days))

                        <span class="posted-badge">

                            @if($job->age_days === 0)
                                Posted today
                            @elseif($job->age_days === 1)
                                Posted yesterday
                            @else
                                Posted {{ $job->age_days }} days ago
                            @endif

                        </span>

                    @endif

                </div>


                <h1>
                    {{ $job->title }}
                </h1>


                @if($job->company)

                    <div class="company-name detail-company">

                        <i class="bi bi-building me-1"></i>

                        {{ $job->company }}

                    </div>

                @endif


                <div class="job-meta">

                    @if($job->location)

                        <span>

                            <i class="bi bi-geo-alt"></i>

                            {{ $job->location }}

                        </span>

                    @endif


                    @if($job->job_type)

                        <span>

                            <i class="bi bi-briefcase"></i>

                            {{ $job->job_type }}

                        </span>

                    @endif

                </div>

            </div>


            <div class="col-lg-3 text-lg-end mt-4 mt-lg-0">

                @if($job->logo)

                    <img
                        src="{{ $job->logo }}"
                        alt="{{ $job->company }}"
                        class="img-fluid"
                        style="max-width:100px;max-height:80px;object-fit:contain;"
                    >

                @endif

            </div>

        </div>

    </div>

</section>


<main class="container job-detail-area">

    <div class="row g-5">


        {{-- MAIN CONTENT --}}
        <article class="col-lg-8">

            <div class="job-content-card">

                <h2>
                    Job Description
                </h2>

                @if($job->snippet)

                    <div class="job-description">

                        {!! $job->snippet !!}

                    </div>

                @else

                    <p class="text-muted">
                        Job description is available on the employer's
                        application page.
                    </p>

                @endif

            </div>

        </article>


        {{-- SIDEBAR --}}
        <aside class="col-lg-4">


            <div class="apply-card">

                @if($job->salary && $job->salary !== '0.000000 - 0.000000')

                    <div class="salary-label">
                        Salary
                    </div>

                    <div class="salary-value">
                        {{ $job->salary }}
                    </div>

                @endif


                <a
                    href="{{ $job->job_url }}"
                    target="_blank"
                    rel="nofollow sponsored"
                    class="btn btn-primary btn-lg w-100 mt-3"
                >
                    Apply for this job
                    <i class="bi bi-box-arrow-up-right ms-1"></i>
                </a>


                <hr>


                <h3>
                    Job Overview
                </h3>


                @if($job->company)

                    <div class="overview-item">

                        <i class="bi bi-building"></i>

                        <div>

                            <small>
                                Company
                            </small>

                            <strong>
                                {{ $job->company }}
                            </strong>

                        </div>

                    </div>

                @endif


                @if($job->location)

                    <div class="overview-item">

                        <i class="bi bi-geo-alt"></i>

                        <div>

                            <small>
                                Location
                            </small>

                            <strong>
                                {{ $job->location }}
                            </strong>

                        </div>

                    </div>

                @endif


                @if($job->job_type)

                    <div class="overview-item">

                        <i class="bi bi-briefcase"></i>

                        <div>

                            <small>
                                Job Type
                            </small>

                            <strong>
                                {{ $job->job_type }}
                            </strong>

                        </div>

                    </div>

                @endif


                @if(!is_null($job->age_days))

                    <div class="overview-item">

                        <i class="bi bi-clock"></i>

                        <div>

                            <small>
                                Posted
                            </small>

                            <strong>

                                @if($job->age_days === 0)
                                    Today
                                @elseif($job->age_days === 1)
                                    Yesterday
                                @else
                                    {{ $job->age_days }} days ago
                                @endif

                            </strong>

                        </div>

                    </div>

                @endif

            </div>


            {{-- SOURCE --}}
            <div class="company-sidebar-card">

                <h3>
                    About this job
                </h3>

                <p>
                    This job opportunity is provided through
                    our job listing network.
                </p>

                <a
                    href="{{ $job->job_url }}"
                    target="_blank"
                    rel="nofollow sponsored"
                >
                    View original job
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>

        </aside>

    </div>

</main>

@endsection