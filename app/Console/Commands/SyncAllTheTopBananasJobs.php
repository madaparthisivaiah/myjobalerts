<?php

namespace App\Console\Commands;

use App\Models\Job;
use App\Services\AllTheTopBananas\AllTheTopBananasService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Throwable;

class SyncAllTheTopBananasJobs extends Command
{
    protected $signature = 'attb:sync-jobs';

    protected $description = 'Sync jobs from AllTheTopBananas';

    public function handle(
        AllTheTopBananasService $attbService
    ): int {
        $this->info('Starting AllTheTopBananas job sync...');

        try {
            $jobs = $attbService->getIndiaJobs();
        } catch (Throwable $e) {
            $this->error(
                'ATTB sync failed: ' . $e->getMessage()
            );

            return self::FAILURE;
        }

        $this->info(
            'ATTB jobs received: ' . count($jobs)
        );

        $seenProviderJobIds = [];

        $inserted = 0;
        $updated = 0;
        $inactive = 0;
        $skipped = 0;

        foreach ($jobs as $jobData) {
            if (!is_array($jobData)) {
                $skipped++;
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Provider Job ID
            |--------------------------------------------------------------------------
            */

            $providerJobId = trim(
                (string) ($jobData['id'] ?? '')
            );

            if ($providerJobId === '') {
                $skipped++;
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Title
            |--------------------------------------------------------------------------
            */

            $title = trim(
                (string) ($jobData['title'] ?? '')
            );

            if ($title === '') {
                $skipped++;
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Company
            |--------------------------------------------------------------------------
            */

            $company = trim(
                (string) ($jobData['company'] ?? '')
            );

            $company = $company !== ''
                ? $company
                : null;

            /*
            |--------------------------------------------------------------------------
            | Category
            |--------------------------------------------------------------------------
            | Example:
            | "category" => "Business"
            |--------------------------------------------------------------------------
            */

            $category = trim(
                (string) ($jobData['category'] ?? '')
            );

            $category = $category !== ''
                ? $category
                : null;

            /*
            |--------------------------------------------------------------------------
            | Location
            |--------------------------------------------------------------------------
            */

            $location = trim(
                (string) ($jobData['location'] ?? '')
            );

            $location = $location !== ''
                ? $location
                : null;

            /*
            |--------------------------------------------------------------------------
            | Geo Location
            |--------------------------------------------------------------------------
            | ATTB response:
            |
            | geoLocation.city
            | geoLocation.state
            | geoLocation.zipCode
            |--------------------------------------------------------------------------
            */

            $geoLocation = $jobData['geoLocation'] ?? [];

            $city = null;
            $state = null;
            $postcode = null;

            if (is_array($geoLocation)) {
                $city = trim(
                    (string) ($geoLocation['city'] ?? '')
                );

                $city = $city !== ''
                    ? $city
                    : null;

                $state = trim(
                    (string) ($geoLocation['state'] ?? '')
                );

                $state = $state !== ''
                    ? $state
                    : null;

                $postcode = trim(
                    (string) ($geoLocation['zipCode'] ?? '')
                );

                $postcode = $postcode !== ''
                    ? $postcode
                    : null;
            }

            /*
            |--------------------------------------------------------------------------
            | Job URL
            |--------------------------------------------------------------------------
            */

            $jobUrl = trim(
                (string) ($jobData['uri'] ?? '')
            );

            if ($jobUrl === '') {
                $skipped++;
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Summary / Snippet
            |--------------------------------------------------------------------------
            */

            $snippet = trim(
                (string) (
                    $jobData['summary']
                    ?? $jobData['abstract']
                    ?? ''
                )
            );

            if ($snippet !== '') {
                $snippet = trim(
                    preg_replace('/\s+/', ' ', $snippet)
                );
            }

            $snippet = $snippet !== ''
                ? $snippet
                : null;

            /*
            |--------------------------------------------------------------------------
            | Job Type
            |--------------------------------------------------------------------------
            */

            $jobType = trim(
                (string) ($jobData['jobType'] ?? '')
            );

            $jobType = $jobType !== ''
                ? $jobType
                : null;

            /*
            |--------------------------------------------------------------------------
            | Employment Type
            |--------------------------------------------------------------------------
            */

            $employmentType = trim(
                (string) ($jobData['jobContract'] ?? '')
            );

            $employmentType = $employmentType !== ''
                ? $employmentType
                : null;

            /*
            |--------------------------------------------------------------------------
            | Remote / Onsite
            |--------------------------------------------------------------------------
            */

            $workLocation = strtolower(
                trim(
                    (string) ($jobData['workLocation'] ?? '')
                )
            );

            $isRemote = $workLocation === 'remote'
                ? 1
                : 0;

            /*
            |--------------------------------------------------------------------------
            | Salary
            |--------------------------------------------------------------------------
            */

            $salary = trim(
                (string) ($jobData['salary'] ?? '')
            );

            $salary = $salary !== ''
                ? $salary
                : null;

            /*
            |--------------------------------------------------------------------------
            | Published Date
            |--------------------------------------------------------------------------
            */

            $publishedAt = null;

            if (!empty($jobData['datePosted'])) {
                try {
                    $publishedAt = Carbon::parse(
                        $jobData['datePosted']
                    );
                } catch (Throwable) {
                    $publishedAt = null;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Active Status
            |--------------------------------------------------------------------------
            | 1 = ACTIVE
            |--------------------------------------------------------------------------
            */

            $isActive = 1;

            /*
            |--------------------------------------------------------------------------
            | Slug
            |--------------------------------------------------------------------------
            */

            $slugParts = array_filter([
                $title,
                $company,
                $location,
            ]);

            $readableSlug = Str::slug(
                implode(' ', $slugParts)
            );

            $readableSlug = Str::limit(
                $readableSlug,
                150,
                ''
            );

            $slug = $readableSlug
                . '-'
                . $providerJobId;

            /*
            |--------------------------------------------------------------------------
            | Job Attributes
            |--------------------------------------------------------------------------
            */

            $attributes = [
                'title' => $title,
                'slug' => $slug,

                'company' => $company,
                'category' => $category,

                'location' => $location,
                'city' => $city,
                'state' => $state,

                'is_remote' => $isRemote,

                /*
                 * Existing jobs.postcode column.
                 * ATTB geoLocation.zipCode is stored here.
                 */
                'postcode' => $postcode,

                'job_type' => $jobType,
                'employment_type' => $employmentType,

                'salary' => $salary,

                'snippet' => $snippet,

                'job_url' => $jobUrl,

                'published_at' => $publishedAt,

                'last_seen_at' => now(),

                /*
                 * 1 = ACTIVE
                 */
                'is_active' => $isActive,

                'age_days' => $publishedAt
                    ? $publishedAt->diffInDays(now())
                    : null,
            ];

            /*
            |--------------------------------------------------------------------------
            | Mark Job As Seen
            |--------------------------------------------------------------------------
            */

            $seenProviderJobIds[] = $providerJobId;

            /*
            |--------------------------------------------------------------------------
            | Existing Job
            |--------------------------------------------------------------------------
            */

            $existingJob = Job::where('provider', 'attb')
                ->where(
                    'provider_job_id',
                    $providerJobId
                )
                ->first();

            if ($existingJob) {
                /*
                 * Keep the existing slug stable.
                 */
                unset($attributes['slug']);

                $existingJob->update($attributes);

                $updated++;
            } else {
                /*
                |--------------------------------------------------------------------------
                | New Job
                |--------------------------------------------------------------------------
                */

                Job::create(
                    array_merge(
                        $attributes,
                        [
                            'provider' => 'attb',
                            'provider_job_id' => $providerJobId,
                        ]
                    )
                );

                $inserted++;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Deactivate Jobs Missing From Current Feed
        |--------------------------------------------------------------------------
        |
        | 1 = ACTIVE
        | 0 = INACTIVE / expired
        |--------------------------------------------------------------------------
        */

        if (!empty($seenProviderJobIds)) {
            $deactivated = Job::where(
                    'provider',
                    'attb'
                )
                ->whereNotIn(
                    'provider_job_id',
                    $seenProviderJobIds
                )
                ->where(
                    'is_active',
                    1
                )
                ->update([
                    'is_active' => 0,
                    'last_seen_at' => now(),
                ]);

            $inactive = $deactivated;
        }

        /*
        |--------------------------------------------------------------------------
        | Sync Summary
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->table(
            [
                'Provider',
                'Received',
                'Inserted',
                'Updated',
                'Deactivated',
                'Skipped',
            ],
            [
                [
                    'attb',
                    count($jobs),
                    $inserted,
                    $updated,
                    $inactive,
                    $skipped,
                ],
            ]
        );

        $this->newLine();

        $this->info(
            'AllTheTopBananas sync completed.'
        );

        return self::SUCCESS;
    }
}