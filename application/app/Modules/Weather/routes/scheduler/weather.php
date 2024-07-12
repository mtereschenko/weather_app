<?php

use App\Modules\Weather\Jobs\CollectBroadcast;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new CollectBroadcast)->hourly();
