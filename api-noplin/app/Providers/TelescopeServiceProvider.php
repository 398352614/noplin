<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Telescope::night();

        $this->hideSensitiveRequestDetails();


        //请求 标签
        Telescope::tag(function (IncomingEntry $entry) {

            if ($entry->type === 'request') {
                $name = '.';
                if (!empty(request()->route())) {
                    $name = request()->route()->getName() ?? '.';
                }

                $data = [
                    $entry->content['uri'] ?? '.',
                    $entry->recordedAt->format('H:i') ?? '.',
                    $entry->recordedAt->format('H:i:s') ?? '.',
                    $entry->content['method'] ?? '.',
                    $name
                ];
                if (!empty($entry->content['response']['msg']) && strlen($entry->content['response']['msg']) < 100) {
                    $data[] = $entry->content['response']['msg'];
                }
                return $data;
            }
//            if ($entry->type === 'job') {
//                if ($entry->content['queue'] == 'queues:tour-notify') {
//                    return [
//                        $entry->recordedAt->format('H:i'),
//                        $entry->content['queue'] ?? null,
//                        $entry->content['uri'] ?? null,
//                        $entry->content['data']['data'] ? $entry->content['data']['data'][0]['type'] : []
//                    ];
//                } else {
//                    return [
//                        $entry->recordedAt->format('H:i'),
//                        $entry->content['queue'] ?? null,
//                        $entry->content['uri'] ?? null
//                    ];
//                }
//            }
            if ($entry->type === 'log') {
                return [
                    $entry->recordedAt->format('H:i') ?? '.',
                    $entry->content['level'] ?? '.'
                ];
            }
            if ($entry->type === 'exception') {
                return [
                    $entry->recordedAt->format('H:i:s') ?? '.',
                ];
            }

            return [];
        });

        Telescope::filter(function (IncomingEntry $entry) {
            if ($this->app->environment('local')) {
                return true;
            }

            return $entry->isReportableException() ||
                $entry->isFailedRequest() ||
                $entry->isFailedJob() ||
                $entry->isScheduledTask() ||
                $entry->hasMonitoredTag();
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     *
     * @return void
     */
    protected function hideSensitiveRequestDetails()
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewTelescope', function () {
            return true;
        });
    }
}
