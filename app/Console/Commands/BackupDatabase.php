<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database {--keep=14 : Jumlah backup terbaru yang dipertahankan}';

    protected $description = 'Backup database ke storage/app/backups (MySQL lewat mysqldump, SQLite dengan menyalin file)';

    public function handle(): int
    {
        $dir = storage_path('app/backups');
        File::ensureDirectoryExists($dir);

        $connection = config('database.default');
        $config = config("database.connections.{$connection}");
        $stamp = now()->format('Ymd-His');

        $target = match ($config['driver']) {
            'sqlite' => $this->backupSqlite($config, "{$dir}/db-{$stamp}.sqlite"),
            'mysql', 'mariadb' => $this->backupMysql($config, "{$dir}/db-{$stamp}.sql"),
            default => null,
        };

        if (! $target) {
            $this->error("Driver database '{$config['driver']}' belum didukung untuk backup otomatis.");

            return self::FAILURE;
        }

        $this->prune($dir, max(1, (int) $this->option('keep')));
        $this->info('Backup tersimpan: ' . $target);

        return self::SUCCESS;
    }

    private function backupSqlite(array $config, string $target): ?string
    {
        $source = $config['database'];

        if ($source === ':memory:') {
            // Basis data memori (dipakai pengujian) disalin lewat VACUUM INTO.
            DB::statement('VACUUM INTO ' . DB::getPdo()->quote($target));

            return $target;
        }

        return File::exists($source) && File::copy($source, $target) ? $target : null;
    }

    private function backupMysql(array $config, string $target): ?string
    {
        $process = new Process([
            'mysqldump',
            '--host=' . $config['host'],
            '--port=' . $config['port'],
            '--user=' . $config['username'],
            '--single-transaction', '--routines', '--no-tablespaces',
            '--result-file=' . $target,
            $config['database'],
        ], null, ['MYSQL_PWD' => (string) $config['password']], null, 300);

        $process->run();

        if (! $process->isSuccessful()) {
            File::delete($target);
            $this->error(trim($process->getErrorOutput()) ?: 'mysqldump gagal. Pastikan mysqldump terpasang dan ada di PATH.');

            return null;
        }

        return $target;
    }

    private function prune(string $dir, int $keep): void
    {
        $files = collect(File::files($dir))
            ->filter(fn ($f) => str_starts_with($f->getFilename(), 'db-'))
            ->sortByDesc(fn ($f) => $f->getFilename())
            ->values();

        $files->slice($keep)->each(fn ($f) => File::delete($f->getPathname()));
    }
}
