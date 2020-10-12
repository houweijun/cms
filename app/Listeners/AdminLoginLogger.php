<?php

namespace App\Listeners;
use App\Models\Admin\System\Log;
use Request;
use App\Events\AdminLoginSucceeded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminLoginLogger
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
     * @param  AdminLoginSucceeded  $event
     * @return void
     */
    public function handle(AdminLoginSucceeded $event)
    {
        $log= new Log();
        $log->user_id = $event->user_id;
        $log->login_at = time();
        $log->login_ip = get_client_ip();
        $log->agent = userBrowser();
        $log->url = Request::path();
        $log->save();
    }
}
