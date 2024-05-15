<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install a fresh version of the app.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if (! $this->option('force')) {
            $this->newLine(2);

            $this->info("Running the install will reset all of the application's data.");
            $this->warn('!!! ALL OF THE DATA WILL BE DELETED !!!');

            if (! $this->confirm('Do you wish to continue?')) {
                $this->info('Install cancelled.');

                return;
            }
        }

        $this->info('Starting to install the application...');

        $this->newLine();

        $this->line('⏳ Reloading Octane...');

        try {
            Artisan::call('octane:reload');
        } catch (\Throwable $th) {
            $this->error('❌ There was an issue reloading Octane, check the logs.');

            return;
        }

        $this->line('✅ Octane reloaded');

        $this->newLine();

        $this->line('⏳ Clearing cached config, routes and views...');

        try {
            Artisan::call('optimize:clear');
        } catch (\Throwable $th) {
            $this->error('❌ There was an issue clearing the cache, check the logs.');

            return;
        }

        $this->line('✅ Cache cleared');

        $this->newLine();

        $this->line('⏳ Migrating the database...');

        try {
            Artisan::call('migrate:fresh', [
                '--force' => true,
            ]);
        } catch (\Throwable $th) {
            $this->error('❌ There was an issue migrating the database, check the logs.');

            return;
        }

        $this->line('✅ Database migrated');

        $this->newLine();

        $this->line('⏳ Adding the test user...');

        User::create([
            'name' => 'John Test',
            'email' => 'john@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        $this->line('✅ User added');

        $this->newLine();

        $this->line('🚀 Finished installing the application!');
    }
}
