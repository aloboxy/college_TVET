<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Http\Request;

class BackUpController extends Controller
{
    //
   public function backup()
    {
        $dbName = env('DB_DATABASE');
        $backupPath = storage_path('app/backups');

        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $timestamp = Carbon::now()->format('Y_m_d_His');
        $filename = "backup_{$timestamp}.sql";
        $filePath = $backupPath . '/' . $filename;

        $tables = DB::select('SHOW TABLES');
        $tableKey = 'Tables_in_' . $dbName;

        $sql = "-- Database backup for {$dbName} on {$timestamp}\n\nSET FOREIGN_KEY_CHECKS=0;\n\n";

       foreach ($tables as $tableObj) {
                $row = (array) $tableObj;
                $table = reset($row);

                // Structure
                $createTable = DB::select("SHOW CREATE TABLE `{$table}`");
                $createStmt = $createTable[0]->{'Create Table'} . ";\n\n";
                $sql .= "-- Structure for table `{$table}`\n";
                $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";
                $sql .= $createStmt;

                // Data
                $rows = DB::table($table)->get();
                if ($rows->count()) {
                    $sql .= "-- Data for table `{$table}`\n";
                    foreach ($rows as $row) {
                        $columns = array_map(fn($col) => "`$col`", array_keys((array)$row));
                        $values = array_map(fn($val) => is_null($val) ? 'NULL' : DB::getPdo()->quote($val), array_values((array)$row));
                        $sql .= "INSERT INTO `{$table}` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $sql .= "\n";
                }
        }


        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        // Save SQL file
        File::put($filePath, $sql);

        // Delete old backups
        $files = collect(File::files($backupPath))->sortBy(fn($file) => $file->getCTime());
        if ($files->count() > 30) {
            $files->take($files->count() - 30)->each(fn($file) => File::delete($file->getPathname()));
        }

        return response()->json(['status' => 'success', 'message' => "Backup created: {$filename}"]);
    }
}
