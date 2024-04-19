<?php

namespace NINA\Core\Agent;
use NINA\Core\ServiceProvider;
class AgentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('agent', function () {
            return new \Jenssegers\Agent\Agent();
        });
    }
}