<?php
/**
 * Created by PhpStorm.
 * User: ben
 * Date: 2018/7/16
 * Time: 22:08
 */

namespace Wiltechsteam\FoundationServiceSingle;


use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Wiltechsteam\FoundationServiceSingle\Commands\FoundationSingleCommand;

class FoundationServiceSingleProvider extends ServiceProvider
{
    public function boot()
    {
        $this->eventBoot(); //事件监听绑定
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/foundationsingle.php', 'foundationsingle');

        $this->app->singleton('foundation:single',FoundationSingleCommand::class);

        $this->commands(['foundation:single']);
    }
    /**
     * 批量绑定事件监听
     */
    public function eventBoot()
    {
        foreach (config('foundationsingle.listens') as $event => $listeners)
        {
            foreach ($listeners as $listener)
            {
                Event::listen($event, $listener);
            }
        }
    }
}