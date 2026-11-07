<?php

namespace App\Providers;

use Illuminate\Console\Events\CommandStarting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

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
        // Template Berry memakai Bootstrap 5, jadi pagination mengikuti Bootstrap.
        Paginator::useBootstrapFive();

        // Pastikan schema PostgreSQL custom sudah ada sebelum migrasi berjalan.
        Event::listen(CommandStarting::class, function (CommandStarting $event) {
            $migrationCommands = [
                'migrate',
                'migrate:fresh',
                'migrate:install',
                'migrate:refresh',
                'migrate:reset',
                'migrate:rollback',
                'migrate:status',
            ];

            if (in_array($event->command, $migrationCommands, true)) {
                $this->ensurePostgresSchema();
            }
        });
    }

    /**
     * Buat schema PostgreSQL dari DB_SCHEMA jika belum tersedia.
     */
    private function ensurePostgresSchema(): void
    {
        $connectionName = config('database.default');
        $connection = config("database.connections.{$connectionName}");

        if (($connection['driver'] ?? null) !== 'pgsql') {
            return;
        }

        $searchPath = $connection['search_path'] ?? 'public';

        foreach (preg_split('/\s*,\s*/', (string) $searchPath) ?: [] as $schema) {
            $schema = trim($schema, " \t\n\r\0\x0B\"'");

            if ($schema === '' || $schema === 'public' || $schema === '$user') {
                continue;
            }

            $quotedSchema = '"'.str_replace('"', '""', $schema).'"';

            DB::connection($connectionName)->statement("create schema if not exists {$quotedSchema}");
        }
    }
}
