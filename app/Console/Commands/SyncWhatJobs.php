<?php

namespace App\Console\Commands;

use App\Services\WhatJobsSyncService;
use Illuminate\Console\Command;
use Throwable;

class SyncWhatJobs extends Command
{
    protected $signature = 'whatjobs:sync-india';

    protected $description = 'Sync all India jobs from WhatJobs';

    public function handle(
        WhatJobsSyncService $syncService
    ): int {

        $this->info(
            'Starting WhatJobs India job sync...'
        );

        /*
        |--------------------------------------------------------------------------
        | WhatJobs requires user_ip
        |--------------------------------------------------------------------------
        |
        | For now we will take the IP from configuration.
        |
        */

        $userIp = config(
            'services.whatjobs.sync_ip'
        );

        if (empty($userIp)) {

            $this->error(
                'WHATJOBS_SYNC_IP is not configured.'
            );

            $this->line(
                'Add WHATJOBS_SYNC_IP to your .env file.'
            );

            return self::FAILURE;
        }

        try {

            $result = $syncService->syncIndiaJobs(
                $userIp
            );

            $this->newLine();

            $this->info(
                'WhatJobs sync completed successfully.'
            );

            $this->newLine();

            $this->table(
                [
                    'Total Jobs',
                    'Pages',
                    'Processed',
                    'Inserted',
                    'Updated',
                    'Deactivated',
                ],
                [
                    [
                        $result['total_jobs'],
                        $result['pages'],
                        $result['processed'],
                        $result['inserted'],
                        $result['updated'],
                        $result['deactivated'],
                    ],
                ]
            );

            return self::SUCCESS;

        } catch (Throwable $e) {

            $this->newLine();

            $this->error(
                'WhatJobs sync failed.'
            );

            $this->error(
                $e->getMessage()
            );

            report($e);

            return self::FAILURE;
        }
    }
}