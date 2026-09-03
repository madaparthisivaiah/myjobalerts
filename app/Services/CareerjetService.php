<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CareerjetService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $locale;

    public function __construct()
    {
        $this->baseUrl = config('services.careerjet.base_url');
        $this->apiKey = config('services.careerjet.api_key');
        $this->locale = config(
            'services.careerjet.locale',
            'en_IN'
        );
    }

    /**
     * Search jobs from Careerjet.
     */
    public function search(array $params = []): array
    {
        $requestParams = [
            'locale_code' => $this->locale,

            'keywords' => $params['keywords'] ?? null,

            'location' => $params['location'] ?? null,

            'page' => $params['page'] ?? 1,

            'page_size' => $params['page_size'] ?? 200,

            'sort' => $params['sort'] ?? 'relevance',

            'contract_type' => $params['contract_type'] ?? null,

            'work_hours' => $params['work_hours'] ?? null,

            /*
             * Careerjet requires the user's IP and user agent.
             *
             * For normal website requests we get these from
             * the current HTTP request.
             *
             * For Artisan/background requests we use the
             * values passed in $params.
             */
            'user_ip' => $params['user_ip']
                ?? request()->ip(),

            'user_agent' => $params['user_agent']
                ?? request()->userAgent(),
        ];

        $requestParams = array_filter(
            $requestParams,
            fn ($value) => $value !== null && $value !== ''
        );

        /*
         * Referer required by Careerjet.
         *
         * For the Artisan command there is no real browser URL,
         * so use the configured application URL.
         */
        $referer = $params['referer']
            ?? (request()->fullUrl() ?: config('app.url'));

        try {

            $response = Http::withBasicAuth(
                $this->apiKey,
                ''
            )
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Referer' => $referer,
                ])
                ->timeout(30)
                ->get(
                    $this->baseUrl . '/v4/query',
                    $requestParams
                );

            if ($response->failed()) {

                return [
                    'type' => 'ERROR',
                    'hits' => 0,
                    'pages' => 0,
                    'jobs' => [],
                    'message' => 'Careerjet API request failed.',
                ];
            }

            $data = $response->json();

            return [
                'type' => $data['type'] ?? 'ERROR',

                'hits' => (int) ($data['hits'] ?? 0),

                'pages' => (int) ($data['pages'] ?? 0),

                'jobs' => $data['jobs'] ?? [],

                'message' => $data['message'] ?? null,
            ];

        } catch (\Throwable $e) {

            report($e);

            return [
                'type' => 'ERROR',
                'hits' => 0,
                'pages' => 0,
                'jobs' => [],
                'message' => 'Unable to connect to Careerjet.',
            ];
        }
    }
}