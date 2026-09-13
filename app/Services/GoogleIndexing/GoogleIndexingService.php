<?php

namespace App\Services\GoogleIndexing;

use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class GoogleIndexingService
{
    private string $credentialsPath;

    private string $scope = 'https://www.googleapis.com/auth/indexing';

    private string $endpoint =
        'https://indexing.googleapis.com/v3/urlNotifications:publish';

    /**
     * Cache TTL for the access token, in seconds.
     *
     * Google's access tokens are typically valid for about 3600 seconds.
     * Keeping the cache below that avoids using a token close to expiry.
     */
    private int $tokenTtl = 3300;

    public function __construct()
    {
        $this->credentialsPath = (string) config(
            'services.google_indexing.credentials'
        );

        if ($this->credentialsPath === '') {
            throw new RuntimeException(
                'Google Indexing API credentials path is not configured.'
            );
        }

        if (! is_file($this->credentialsPath)) {
            throw new RuntimeException(
                'Google Indexing API credentials file not found: '
                . $this->credentialsPath
            );
        }
    }

    /**
     * Notify Google that a JobPosting URL was added or updated.
     */
    public function update(string $url): array
    {
        return $this->publish($url, 'URL_UPDATED');
    }

    /**
     * Notify Google that a JobPosting URL was removed.
     */
    public function delete(string $url): array
    {
        return $this->publish($url, 'URL_DELETED');
    }

    /**
     * Send a notification to Google's Indexing API.
     *
     * A single retry is performed when Google returns HTTP 401,
     * because the cached access token may have expired or been revoked.
     */
    private function publish(string $url, string $type): array
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = $this->sendRequest(
                $url,
                $type,
                $accessToken
            );

            /*
             * If the cached token is no longer accepted, remove it
             * and obtain a fresh token once.
             */
            if ($response->status() === 401) {
                Cache::forget($this->tokenCacheKey());

                try {
                    $accessToken = $this->getAccessToken();

                    $response = $this->sendRequest(
                        $url,
                        $type,
                        $accessToken
                    );
                } catch (Throwable $e) {
                    Log::warning(
                        'Google Indexing API: failed to refresh access token',
                        [
                            'url' => $url,
                            'type' => $type,
                            'error' => $e->getMessage(),
                        ]
                    );

                    return [
                        'success' => false,
                        'status' => 401,
                        'type' => $type,
                        'url' => $url,
                        'response' => null,
                        'body' => null,
                        'error' => $e->getMessage(),
                    ];
                }
            }
        } catch (Throwable $e) {
            Log::warning(
                'Google Indexing API: failed to obtain access token',
                [
                    'url' => $url,
                    'type' => $type,
                    'error' => $e->getMessage(),
                ]
            );

            return [
                'success' => false,
                'status' => null,
                'type' => $type,
                'url' => $url,
                'response' => null,
                'body' => null,
                'error' => $e->getMessage(),
            ];
        }

        if ($response->successful()) {
            return [
                'success' => true,
                'status' => $response->status(),
                'type' => $type,
                'url' => $url,
                'response' => $response->json(),
                'body' => $response->body(),
            ];
        }

        Log::warning(
            'Google Indexing API: publish request failed',
            [
                'url' => $url,
                'type' => $type,
                'status' => $response->status(),
                'body' => $response->body(),
            ]
        );

        return [
            'success' => false,
            'status' => $response->status(),
            'type' => $type,
            'url' => $url,
            'response' => $response->json(),
            'body' => $response->body(),
        ];
    }

    /**
     * Perform the HTTP request to Google.
     */
    private function sendRequest(
        string $url,
        string $type,
        string $accessToken
    ) {
        return Http::timeout(30)
            ->acceptJson()
            ->withToken($accessToken)
            ->post($this->endpoint, [
                'url' => $url,
                'type' => $type,
            ]);
    }

    /**
     * Generate or retrieve a cached OAuth access token using
     * the service-account credentials.
     */
    private function getAccessToken(): string
    {
        return Cache::remember(
            $this->tokenCacheKey(),
            $this->tokenTtl,
            function (): string {
                try {
                    $client = new GoogleClient();

                    $client->setAuthConfig($this->credentialsPath);

                    $client->setScopes([
                        $this->scope,
                    ]);

                    $token = $client->fetchAccessTokenWithAssertion();
                } catch (Throwable $e) {
                    throw new RuntimeException(
                        'Unable to obtain Google access token: '
                        . $e->getMessage(),
                        previous: $e
                    );
                }

                if (
                    ! is_array($token) ||
                    empty($token['access_token'])
                ) {
                    $error = is_array($token)
                        ? (
                            $token['error_description']
                            ?? $token['error']
                            ?? null
                        )
                        : null;

                    throw new RuntimeException(
                        'Unable to obtain Google access token.'
                        . ($error ? ' ' . $error : '')
                    );
                }

                return (string) $token['access_token'];
            }
        );
    }

    /**
     * Cache key for the access token.
     *
     * The credential path is included so different Google service
     * accounts do not accidentally share the same cached token.
     */
    private function tokenCacheKey(): string
    {
        return 'google_indexing_token:'
            . md5($this->credentialsPath);
    }
}
