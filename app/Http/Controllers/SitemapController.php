<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [
            [
                'loc' => url('/'),
            ],
            [
                'loc' => url('/jobs'),
            ],
            [
                'loc' => url('/about-us'),
            ],
            [
                'loc' => url('/contact'),
            ],
            [
                'loc' => url('/faq'),
            ],
            [
                'loc' => url('/privacy-policy'),
            ],
            [
                'loc' => url('/terms-and-conditions'),
            ],
            [
                'loc' => url('/cookie-policy'),
            ],
            [
                'loc' => url('/disclaimer'),
            ],
        ];

        return response()
            ->view('sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }
}