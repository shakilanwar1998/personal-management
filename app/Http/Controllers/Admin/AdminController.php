<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\YearService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class AdminController extends Controller
{
    /**
     * Period filter shared by list pages ("year" query param):
     *   ''      → the year picked in the top bar
     *   'all'   → no date limit
     *   '2025'  → that calendar year
     *   'custom'→ use the from / to params
     */
    protected function applyPeriod(Builder $query, Request $request, string $column = 'date'): Builder
    {
        $period = (string) $request->input('year', '');

        if ($period === 'custom') {
            return $query
                ->when($request->filled('from'), fn ($q) => $q->whereDate($column, '>=', $request->input('from')))
                ->when($request->filled('to'), fn ($q) => $q->whereDate($column, '<=', $request->input('to')));
        }

        if ($period === 'all') {
            return $query;
        }

        $year = ctype_digit($period) ? (int) $period : YearService::selected();

        return $query->whereYear($column, $year);
    }

    /** The calendar year the page's charts should use. */
    protected function chartYear(Request $request): int
    {
        $period = (string) $request->input('year', '');

        return ctype_digit($period) ? (int) $period : YearService::selected();
    }

    protected function sortColumn(Request $request, array $allowed, string $default): array
    {
        $sort = in_array($request->input('sort'), $allowed, true) ? $request->input('sort') : $default;
        $dir = $request->input('dir') === 'asc' ? 'asc' : 'desc';

        return [$sort, $dir];
    }

    protected function perPage(Request $request): int
    {
        $n = (int) $request->input('per_page', 25);

        return in_array($n, [10, 25, 50, 100], true) ? $n : 25;
    }

    protected function csv(string $filename, array $header, iterable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($header, $rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM so Excel shows ৳ and Bangla text correctly
            fputcsv($out, $header);
            foreach ($rows as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    protected function bulkIds(Request $request): array
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
        ]);

        return $data['ids'];
    }
}
