<?php

namespace App\Http\Livewire\SuperAdmin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class SqlConsole extends Component
{
    public $sqlQuery = '';
    public $sqlResult = [];
    public $sqlColumns = [];
    public $sqlRowsAffected = null;
    public $sqlError = null;
    public $sqlExecutedAt = null;
    public $sqlExportReady = false;
    public $sqlPage = 1;
    public $sqlPerPage = 500;
    public $sqlTotalRows = null;
    public $sqlLastQuery = null;
    public $sqlPaginated = false;

    public function mount()
    {
        if (!isAdmin()) {
            abort(403, 'Acesso restrito.');
        }
    }

    public function render()
    {
        return view('livewire.super-admin.sql-console')
            ->layout('layouts.app-pep-auth');
    }

    private function sqlLogger()
    {
        return Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/sql-execute.log'),
        ]);
    }

    public function runSql()
    {
        if (!isAdmin()) {
            abort(403, 'Acesso restrito.');
        }

        $this->sqlError = null;
        $this->sqlResult = [];
        $this->sqlColumns = [];
        $this->sqlRowsAffected = null;
        $this->sqlExecutedAt = now()->toDateTimeString();
        $this->sqlExportReady = false;
        $this->sqlTotalRows = null;
        $this->sqlPaginated = false;

        $query = $this->sanitizeQuery($this->sqlQuery ?? '');

        if ($query === '') {
            $this->sqlError = 'Informe uma consulta SQL.';
            return;
        }

        $firstToken = strtolower(strtok($query, " \t\n\r\0\x0B"));
        $selectTokens = ['select', 'show', 'describe', 'with'];
        $paginatedTokens = ['select', 'with'];

        try {
            if (in_array($firstToken, $selectTokens, true)) {
                if (in_array($firstToken, $paginatedTokens, true)) {
                    $this->sqlPage = 1;
                    $this->sqlLastQuery = $query;
                    $this->runSelectQuery($query, true);
                } else {
                    $rows = DB::select($query);
                    $this->sqlResult = array_map(function ($row) {
                        return (array) $row;
                    }, $rows);

                    if (!empty($this->sqlResult)) {
                        $this->sqlColumns = array_keys($this->sqlResult[0]);
                    }
                    $this->sqlExportReady = !empty($this->sqlResult);
                    $this->sqlTotalRows = count($this->sqlResult);
                    $this->sqlPaginated = false;
                    $this->sqlLastQuery = null;

                    $this->sqlLogger()->info('SuperAdmin SQL executado (select)', [
                        'user_id' => auth()->id(),
                        'user_email' => auth()->user()->email ?? null,
                        'executed_at' => $this->sqlExecutedAt,
                        'query' => $query,
                        'rows' => count($this->sqlResult),
                    ]);
                }
            } else {
                if (in_array($firstToken, ['insert', 'update', 'delete'], true)) {
                    $this->sqlRowsAffected = DB::affectingStatement($query);
                } else {
                    DB::statement($query);
                    $this->sqlRowsAffected = 0;
                }
                $this->sqlExportReady = false;
                $this->sqlLastQuery = null;
                $this->sqlPaginated = false;

                $this->sqlLogger()->info('SuperAdmin SQL executado (statement)', [
                    'user_id' => auth()->id(),
                    'user_email' => auth()->user()->email ?? null,
                    'executed_at' => $this->sqlExecutedAt,
                    'query' => $query,
                    'rows_affected' => $this->sqlRowsAffected,
                ]);
            }
        } catch (\Throwable $e) {
            $this->sqlError = $e->getMessage();
            $this->sqlExportReady = false;
            $this->sqlLogger()->error('SuperAdmin SQL falhou', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email ?? null,
                'executed_at' => $this->sqlExecutedAt,
                'query' => $query,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function clearSql()
    {
        $this->sqlQuery = '';
        $this->sqlResult = [];
        $this->sqlColumns = [];
        $this->sqlRowsAffected = null;
        $this->sqlError = null;
        $this->sqlExecutedAt = null;
        $this->sqlExportReady = false;
        $this->sqlPage = 1;
        $this->sqlTotalRows = null;
        $this->sqlLastQuery = null;
        $this->sqlPaginated = false;
    }

    public function nextPage()
    {
        if (!$this->sqlPaginated || !$this->sqlLastQuery) {
            return;
        }

        $totalPages = $this->sqlTotalRows ? (int) ceil($this->sqlTotalRows / $this->sqlPerPage) : null;
        if ($totalPages && $this->sqlPage >= $totalPages) {
            return;
        }

        $this->sqlPage++;
        $this->sqlExecutedAt = now()->toDateTimeString();
        $this->runSelectQuery($this->sqlLastQuery, false);
    }

    public function previousPage()
    {
        if (!$this->sqlPaginated || !$this->sqlLastQuery) {
            return;
        }

        if ($this->sqlPage <= 1) {
            return;
        }

        $this->sqlPage--;
        $this->sqlExecutedAt = now()->toDateTimeString();
        $this->runSelectQuery($this->sqlLastQuery, false);
    }

    private function sanitizeQuery(string $query): string
    {
        $query = trim($query);
        return rtrim($query, "; \n\r\t");
    }

    private function runSelectQuery(string $query, bool $refreshTotal): void
    {
        $this->sqlError = null;
        $this->sqlResult = [];
        $this->sqlColumns = [];
        $this->sqlRowsAffected = null;
        $this->sqlExportReady = false;
        $this->sqlPaginated = true;

        $page = max(1, (int) $this->sqlPage);
        $perPage = max(1, (int) $this->sqlPerPage);
        $offset = ($page - 1) * $perPage;

        $paginatedQuery = "select * from ({$query}) as sql_console_query limit {$perPage} offset {$offset}";
        $rows = DB::select($paginatedQuery);
        $this->sqlResult = array_map(function ($row) {
            return (array) $row;
        }, $rows);

        if (!empty($this->sqlResult)) {
            $this->sqlColumns = array_keys($this->sqlResult[0]);
        }

        if ($refreshTotal || $this->sqlTotalRows === null) {
            $countQuery = "select count(*) as total from ({$query}) as sql_console_count";
            $countResult = DB::select($countQuery);
            $this->sqlTotalRows = (int) ($countResult[0]->total ?? 0);
        }

        $this->sqlExportReady = !empty($this->sqlResult);

        $this->sqlLogger()->info('SuperAdmin SQL executado (select)', [
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email ?? null,
            'executed_at' => $this->sqlExecutedAt,
            'query' => $query,
            'rows' => count($this->sqlResult),
            'page' => $this->sqlPage,
            'per_page' => $this->sqlPerPage,
            'total_rows' => $this->sqlTotalRows,
        ]);
    }

    public function exportSql()
    {
        if (!isAdmin()) {
            abort(403, 'Acesso restrito.');
        }

        if (empty($this->sqlResult)) {
            $this->sqlError = 'Não há resultados para exportar.';
            return;
        }

        $filename = 'sql_result_' . now()->format('Ymd_His') . '.csv';
        $columns = $this->sqlColumns;
        $rows = $this->sqlResult;

        return response()->streamDownload(function () use ($columns, $rows) {
            $file = fopen('php://output', 'w');

            // BOM UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, $columns, ';');

            foreach ($rows as $row) {
                $line = [];
                foreach ($columns as $column) {
                    $line[] = data_get($row, $column);
                }
                fputcsv($file, $line, ';');
            }

            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
