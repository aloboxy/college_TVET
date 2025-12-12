<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';
    protected $description = 'Backup the MySQL database and keep only the last 30 backups';

    public function handle()
    {
        $config = config('database.connections.mysql');
        $backupDir = storage_path('app/backups');

        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $date = date('Y-m-d_His');
        $filename = "backup_{$date}.sql";
        $filepath = "{$backupDir}/{$filename}";

        $command = sprintf(
            'mysqldump --single-transaction --quick --lock-tables=false --user=%s --password=%s --host=%s %s > %s',
            escapeshellarg($config['username']),
            escapeshellarg($config['password']),
            escapeshellarg($config['host']),
            escapeshellarg($config['database']),
            escapeshellarg($filepath)
        );

        $output = [];
        $returnVar = 0;
        exec($command . ' 2>&1', $output, $returnVar);

        if ($returnVar !== 0) {
            $this->error("Backup failed:\n" . implode("\n", $output));
            return 1;
        }

        $this->info("Database backup created: {$filename}");

        // Keep only the last 30
        $files = glob("{$backupDir}/backup_*.sql");
        if (count($files) > 30) {
            usort($files, function ($a, $b) {
                return filemtime($a) - filemtime($b);
            });
            $toDelete = array_slice($files, 0, count($files) - 30);
            foreach ($toDelete as $file) {
                unlink($file);
            }
        }

        return 0;
    }
}
