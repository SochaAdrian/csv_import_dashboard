<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use PDO;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment(['local', 'testing']) && !file_exists(storage_path('logs/import_logs.sqlite'))) {
            touch(storage_path('logs/import_logs.sqlite'));
            $schema = 'CREATE TABLE import_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            import_id INTEGER,
            row_number INTEGER,
            value TEXT,
            error_message TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );';
            $pdo = new PDO('sqlite:' . storage_path('logs/import_logs.sqlite'));
            $pdo->exec($schema);
        }

        Vite::prefetch(concurrency: 3);
    }

}
