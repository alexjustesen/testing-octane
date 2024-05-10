<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * About endpoint will return the current status of the application including it's version.
 *
 * Endpoint: /api/about
 */
Route::get('/about', function () {
    $output = new BufferedOutput();

    Artisan::call('about --json', [], $output);

    $response = json_decode($output->fetch(), true);

    return response()->json($response);
});
