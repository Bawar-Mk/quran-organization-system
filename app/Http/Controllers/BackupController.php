<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class BackupController extends Controller
{
    // فۆڵدەری پاراستن لە درایڤی D (دەتوانیت ناوەکەی بگۆڕیت بۆ ناوی ڕێکخراوەکەت)
    private $backupPath = 'D:\Oraganaization_Renwar_Backups';

    private function checkAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'تەنها بەڕێوەبەر دەتوانێت ئەم بەشە ببینێت.');
        }
    }

    public function index()
    {
        $this->checkAdmin();

        $allTables = array_map('current', DB::select('SHOW TABLES'));

        $excludedTables = [
            'cache',
            'cache_locks',
            'failed_jobs',
            'jobs',
            'job_batches',
            'sessions',
        ];

        $tables = array_filter($allTables, function ($table) use ($excludedTables) {
            return !in_array($table, $excludedTables);
        });

        if (!File::exists($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }

        $allImportantTablesCount = count($tables);
        $files = File::files($this->backupPath);

        $backups = array_map(function ($file) use ($allImportantTablesCount) {
            $tableNames = [];
            $isFull = false;
            $hasMeta = false;

            if ($handle = fopen($file->getPathname(), 'r')) {
                for ($i = 0; $i < 15 && !feof($handle); $i++) {
                    $line = fgets($handle);
                    if (strpos($line, '-- [TYPE: FULL]') !== false) {
                        $isFull = true;
                        $hasMeta = true;
                    } elseif (strpos($line, '-- [TYPE: SELECTED]') !== false) {
                        $isFull = false;
                        $hasMeta = true;
                    } elseif (strpos($line, '-- [TABLES:') !== false) {
                        $tablesList = trim(str_replace('-- [TABLES:', '', $line), " ]\r\n");
                        if (!empty($tablesList)) {
                            $tableNames = explode(', ', $tablesList);
                        }
                    }
                }

                if (!$hasMeta) {
                    rewind($handle);
                    while (($line = fgets($handle)) !== false) {
                        if (preg_match('/CREATE TABLE IF NOT EXISTS `(.*?)`|DROP TABLE IF EXISTS `(.*?)`;/', $line, $matches)) {
                            $tableName = !empty($matches[1]) ? $matches[1] : $matches[2];
                            if (!in_array($tableName, $tableNames)) {
                                $tableNames[] = $tableName;
                            }
                        }
                    }
                    $isFull = (count($tableNames) >= $allImportantTablesCount);
                }

                fclose($handle);
            }

            $tablesCount = count($tableNames);

            return [
                'name' => $file->getFilename(),
                'size' => round($file->getSize() / 1024, 2) . ' KB',
                'date' => Carbon::createFromTimestamp($file->getMTime())->timezone('Asia/Baghdad')->format('Y-m-d h:i A'),
                'sort_date' => Carbon::createFromTimestamp($file->getMTime())->timezone('Asia/Baghdad')->format('Y-m-d H:i:s'),
                'is_full' => $isFull,
                'tables_count' => $tablesCount,
                'tables_list' => implode(', ', $tableNames),
            ];
        }, $files);

        usort($backups, function ($a, $b) {
            return strtotime($b['sort_date']) - strtotime($a['sort_date']);
        });

        return view('backup.index', compact('tables', 'backups'));
    }

    public function createBackup(Request $request)
    {
        $this->checkAdmin();

        $tablesToBackup = $request->input('tables', []);

        $allTables = array_map('current', DB::select('SHOW TABLES'));
        $excludedTables = ['cache', 'cache_locks', 'failed_jobs', 'jobs', 'job_batches', 'sessions'];
        $importantTables = array_filter($allTables, function ($table) use ($excludedTables) {
            return !in_array($table, $excludedTables);
        });

        if (empty($tablesToBackup)) {
            $tablesToBackup = $importantTables;
        }

        $isFull = (count($tablesToBackup) >= count($importantTables));
        $typeTag = $isFull ? 'FULL' : 'SELECTED';
        $tablesStr = implode(', ', $tablesToBackup);

        $sql = "-- باکئەپی سیستەم\n";
        $sql .= "-- بەروار: " . now()->timezone('Asia/Baghdad')->format('Y-m-d h:i A') . "\n";
        $sql .= "-- [TYPE: $typeTag]\n";
        $sql .= "-- [TABLES: $tablesStr]\n\n";

        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tablesToBackup as $table) {
            $createTable = DB::select("SHOW CREATE TABLE `$table`")[0]->{'Create Table'};

            $createTable = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $createTable);

            $sql .= $createTable . ";\n\n";

            $rows = DB::table($table)->get();
            foreach ($rows as $row) {
                $rowArray = (array) $row;
                $values = array_map(function ($value) {
                    return is_null($value) ? "NULL" : "'" . addslashes($value) . "'";
                }, array_values($rowArray));

                $sql .= "INSERT IGNORE INTO `$table` VALUES (" . implode(", ", $values) . ");\n";
            }
            $sql .= "\n\n";
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        $fileName = 'backup_' . now()->timezone('Asia/Baghdad')->format('Y_m_d_h_i_A') . '.sql';

        if (!File::exists($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }

        File::put($this->backupPath . '\\' . $fileName, $sql);

        return back()->with('success', "زانیارییەکان بە سەرکەوتوویی لە درایڤی D پاشەکەوت کران.");
    }

    public function restoreBackup(Request $request)
    {
        $this->checkAdmin();

        $fileName = $request->input('file_name');
        $filePath = $this->backupPath . '\\' . $fileName;

        if (!File::exists($filePath)) {
            return back()->with('error', 'فایلی باکئەپەکە نەدۆزرایەوە.');
        }

        $sql = File::get($filePath);

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::unprepared($sql);
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return back()->with('success', 'زانیارییەکان بە سەرکەوتوویی گەڕێندرانەوە و بۆ کۆتایی خشتەکان زیاد کران.');
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return back()->with('error', 'کێشە لە گەڕاندنەوەدا هەبوو: ' . $e->getMessage());
        }
    }

    public function download(string $file)
    {
        $this->checkAdmin();

        $filePath = $this->backupPath . '\\' . $file;
        if (File::exists($filePath)) {
            return response()->download($filePath);
        }
        return back()->with('error', 'فایلەکە بوونی نییە.');
    }

    public function openFolder()
    {
        $this->checkAdmin();

        if (!File::exists($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            exec('explorer "' . str_replace('/', '\\', $this->backupPath) . '"');
        }

        return back()->with('success', 'فۆڵدەری باکئەپەکان کرایەوە.');
    }
}
