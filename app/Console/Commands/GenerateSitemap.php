<?php

namespace App\Console\Commands;

use App\Services\SitemapService;
use Illuminate\Console\Command;
use Throwable;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description =
        'Generate dynamic MyJobAlerts sitemaps';

    public function handle(
        SitemapService $sitemapService
    ): int {
        $this->info(
            'Starting MyJobAlerts sitemap generation...'
        );

        try {

            $sitemapService->generate();

            $this->info(
                'Sitemap generated successfully.'
            );

            $this->info(
                'Sitemap: '
                . config('app.url')
                . '/sitemap.xml'
            );

            return self::SUCCESS;

        } catch (Throwable $e) {

            $this->error(
                'Sitemap generation failed.'
            );

            $this->error(
                $e->getMessage()
            );

            report($e);

            return self::FAILURE;
        }
    }
}