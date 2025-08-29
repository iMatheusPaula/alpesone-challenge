<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:alpes-one-sync')->Hourly();
