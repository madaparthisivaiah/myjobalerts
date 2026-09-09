<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class WhatJobsService
{
    protected string $baseUrl;

    protected int $publisherId;

    protected int $limit;

    public function __construct()
    {
        $this->baseUrl = rtrim(
            config('services.whatjobs.base_url'),
            '/'
        );

        $this->publisherId = (int) config(
            'services.whatjobs.publisher_id'
        );

        $this->limit = (int) config(
            'services.whatjobs.limit',
            50
        );
    }

    /**
     * Fetch one page of jobs from WhatJobs.
     */
    public function search(
        string $userIp,
        ?string $userAgent = null,
        ?string $keyword = null,
        ?string $location = null,
        int $page = 1,
        ?int $limit = null
    ): array {
        $limit ??= $this->limit;

        // WhatJobs maximum is 50
        $limit = min(max($limit, 1), 50);

        $parameters = [
            'publisher' => $this->publisherId,
            'user_ip' => $userIp,
            'limit' => $limit,
            'page' => max($page, 1),
        ];

        if (!empty($userAgent)) {
            $parameters['user_agent'] = $userAgent;
        }

        if (!empty($keyword)) {
            $parameters['keyword'] = $keyword;
        }

        if (!empty($location)) {
            $parameters['location'] = $location;
        }

        $response = Http::acceptJson()
            ->timeout(20)
            ->retry(2, 500)
            ->get(
                $this->baseUrl . '/api/v1/jobs.json',
                $parameters
            );

        if (!$response->successful()) {
            throw new RuntimeException(
                'WhatJobs API request failed. HTTP status: '
                . $response->status()
            );
        }

        return $response->json();
    }

    /**
     * Get India jobs.
     */
    public function getIndiaJobs(
        string $userIp,
        ?string $userAgent = null,
        int $page = 1
    ): array {
        return $this->search(
            userIp: $userIp,
            userAgent: $userAgent,
            keyword: null,
            location: config(
                'services.whatjobs.country',
                'India'
            ),
            page: $page,
            limit: 50
        );
    }

    /**
     * Search jobs in India.
     *
     * Example:
     * keyword = PHP Developer
     * location = Hyderabad
     */
    public function searchIndiaJobs(
        string $userIp,
        ?string $userAgent = null,
        ?string $keyword = null,
        ?string $location = null,
        int $page = 1
    ): array {
        return $this->search(
            userIp: $userIp,
            userAgent: $userAgent,
            keyword: $keyword,
            location: $location,
            page: $page,
            limit: 50
        );
    }
}