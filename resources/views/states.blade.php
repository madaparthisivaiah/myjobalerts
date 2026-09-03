@extends('layouts.app')

@section('title', 'Jobs by State in India - MyJobAlerts')

@section('content')

<section class="section-padding bg-light-subtle">
    <div class="container">

        <div class="text-center section-heading mb-5">
            <span class="section-label">
                JOBS BY LOCATION
            </span>

            <h1>Jobs by State in India</h1>

            <p>
                Find the latest job opportunities across states and union territories in India.
            </p>
        </div>

        <div class="row g-4">

            @foreach($states as $state => $cities)

                <div class="col-lg-3 col-md-4 col-sm-6">

                    <a
                        href="{{ url('/india-jobs/' . \Illuminate\Support\Str::slug($state)) }}"
                        class="state-card text-decoration-none"
                    >

                        <div class="state-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>

                        <div class="state-content">

                            <h2 class="state-name">
                                {{ $state }}
                            </h2>

                            <span class="state-link">
                                View jobs
                                <i class="bi bi-arrow-right"></i>
                            </span>

                        </div>

                    </a>

                </div>

            @endforeach

        </div>

    </div>
</section>

@endsection
