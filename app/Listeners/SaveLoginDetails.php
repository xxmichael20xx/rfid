<?php

namespace App\Listeners;

use App\Models\LoginActivity;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveLoginDetails
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        // Get the authenticated user
        $user = $event->user;

        // Extract specific browser information
        $browserInfo = $this->extractBrowserInfo(request()->header('User-Agent'));

        // Save login
        LoginActivity::create([
            'user_id' => $user->id,
            'browser' => implode(', ', $browserInfo),
            'ip' => request()->ip()
        ]);
    }

    protected function extractBrowserInfo($userAgent)
    {
        // You can use a library like browscap/browscap-php to parse user-agent strings,
        // or for a simple example, you can use regular expressions.

        $browserInfo = ['name' => 'Unknown'];

        // Example regex to extract browser name (you may need to adjust this)
        if (preg_match('/(Chrome|Safari|Firefox|Edge)/i', $userAgent, $matches)) {
            $browserInfo['name'] = $matches[1];
        }

        return $browserInfo;
    }
}
