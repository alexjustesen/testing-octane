<?php

use App\Models\User;
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

Route::get('/users', function () {
    $users = User::query()
        ->select(['id', 'name', 'email', 'created_at', 'updated_at'])
        ->paginate();

    return $users;
});

Route::get('/users/{user}', function (int $id) {
    $user = User::query()
        ->select(['id', 'name', 'email', 'created_at', 'updated_at'])
        ->firstWhere('id', $id);

    if (is_null($user)) {
        return response()->json([
            'message' => 'User not found.',
        ], 404);
    }

    return $user;
});
