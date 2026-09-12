<?php

namespace App\Services;

use App\Models\Job;
use Illuminate\Support\Facades\File;

class SitemapService
{
    /**
     * Maximum URLs allowed in one sitemap file.
     */
    protected int $maxUrlsPerFile = 50000;

    /**
     * Application base URL.
     */
    protected string $baseUrl;

    /**
     * Public directory.
     */
    protected string $publicPath;

    public function __construct()
    {
        $this->baseUrl = rtrim(
            config('app.url'),
            '/'
        );

        $this->publicPath = public_path();
    }

    /**
     * Generate the complete dynamic sitemap.
     *
     * This currently generates:
     *
     * sitemap.xml
     * sitemap-jobs-1.xml
     * sitemap-jobs-2.xml
     * ...
     */
    public function generate(): void
    {
        $jobSitemapFiles = $this->generateJobsSitemaps();

        $this->generateSitemapIndex(
            $jobSitemapFiles
        );
    }

    /**
     * Generate sitemap files containing active WhatJobs jobs.
     */
    protected function generateJobsSitemaps(): array
    {
        /*
         * IMPORTANT:
         *
         * Only active WhatJobs jobs are included.
         *
         * Expired/deactivated jobs:
         *
         * is_active = false
         *
         * are automatically excluded.
         */
        $jobs = Job::query()
            ->where('provider', 'whatjobs')
            ->where('is_active', 0)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->orderBy('id')
            ->get([
                'id',
                'slug',
                'published_at',
            ]);

        /*
         * Remove previously generated job sitemap files.
         */
        $this->removeOldJobSitemaps();

        $jobSitemapFiles = [];

        $chunkNumber = 0;

        foreach (
            $jobs->chunk($this->maxUrlsPerFile)
            as $jobChunk
        ) {
            $chunkNumber++;

            $filename = "sitemap-jobs-{$chunkNumber}.xml";

            $filepath = $this->publicPath . '/' . $filename;

            $urls = [];

            foreach ($jobChunk as $job) {

                $urls[] = [
                    'loc' => $this->baseUrl
                        . '/viewjob/'
                        . rawurlencode($job->slug),

                    /*
                     * Use published_at when available.
                     *
                     * If unavailable, we simply do not
                     * include lastmod.
                     */
                    'lastmod' => $job->published_at
                        ? $job->published_at->toAtomString()
                        : null,
                ];
            }

            File::put(
                $filepath,
                $this->buildUrlsetXml($urls)
            );

            $jobSitemapFiles[] = $filename;
        }

        return $jobSitemapFiles;
    }

    /**
     * Generate the main sitemap index.
     *
     * /sitemap.xml
     */
    protected function generateSitemapIndex(
        array $jobSitemapFiles
    ): void {
        $sitemaps = [];

        foreach ($jobSitemapFiles as $filename) {

            $sitemaps[] = [
                'loc' => $this->baseUrl
                    . '/'
                    . $filename,
            ];
        }

        File::put(
            $this->publicPath . '/sitemap.xml',
            $this->buildSitemapIndexXml($sitemaps)
        );
    }

    /**
     * Remove old job sitemap files.
     *
     * Example:
     *
     * sitemap-jobs-1.xml
     * sitemap-jobs-2.xml
     * sitemap-jobs-3.xml
     *
     * If the number of active jobs decreases,
     * old files are removed automatically.
     */
    protected function removeOldJobSitemaps(): void
    {
        $files = File::glob(
            $this->publicPath . '/sitemap-jobs-*.xml'
        );

        foreach ($files as $file) {
            File::delete($file);
        }
    }

    /**
     * Build a sitemap URL set.
     */
    protected function buildUrlsetXml(
        array $urls
    ): string {
        $xml =
            '<?xml version="1.0" encoding="UTF-8"?>'
            . PHP_EOL;

        $xml .=
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'
            . PHP_EOL;

        foreach ($urls as $url) {

            $xml .= '    <url>' . PHP_EOL;

            $xml .=
                '        <loc>'
                . $this->escapeXml($url['loc'])
                . '</loc>'
                . PHP_EOL;

            if (!empty($url['lastmod'])) {

                $xml .=
                    '        <lastmod>'
                    . $this->escapeXml($url['lastmod'])
                    . '</lastmod>'
                    . PHP_EOL;
            }

            $xml .= '    </url>' . PHP_EOL;
        }

        $xml .= '</urlset>' . PHP_EOL;

        return $xml;
    }

    /**
     * Build sitemap index XML.
     */
    protected function buildSitemapIndexXml(
        array $sitemaps
    ): string {
        $xml =
            '<?xml version="1.0" encoding="UTF-8"?>'
            . PHP_EOL;

        $xml .=
            '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'
            . PHP_EOL;

        foreach ($sitemaps as $sitemap) {

            $xml .= '    <sitemap>' . PHP_EOL;

            $xml .=
                '        <loc>'
                . $this->escapeXml($sitemap['loc'])
                . '</loc>'
                . PHP_EOL;

            $xml .= '    </sitemap>' . PHP_EOL;
        }

        $xml .= '</sitemapindex>' . PHP_EOL;

        return $xml;
    }

    /**
     * Escape XML values safely.
     */
    protected function escapeXml(
        string $value
    ): string {
        return htmlspecialchars(
            $value,
            ENT_XML1 | ENT_QUOTES,
            'UTF-8'
        );
    }
}