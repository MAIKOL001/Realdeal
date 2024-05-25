<?php

namespace App\Services;

use Revolution\Google\Sheets\Facades\Sheets;
use Illuminate\Support\Collection;

class ImportService
{
    public function getImportData(string $search, string $sortField, bool $sortAsc, int $perPage): Collection
    {
        $sheet = Sheets::spreadsheet('1-ALjh_euUbhg8uwzSKEmcbA5Wjn_mCCyjpDBo3We76A')->sheet('Sheet1')->all();

        // Extract headers from the first row, handle potential empty sheets
        $headers = array_shift($sheet) ?? [];

        $data = collect($sheet)->filter(function ($row) use ($search) {
            // Implement search logic based on your needs (e.g., search across multiple columns)
            return empty($search) || stristr(implode($row), $search);
        })->take($perPage);

        // Optional sorting based on sortField and sortAsc
        if ($sortField && in_array($sortField, $headers)) {
            $data = $data->sortBy($sortField, SORT_NATURAL | ($sortAsc ? SORT_ASC : SORT_DESC));
        }

        return $data->map(function ($row) use ($headers) {
            // Combine row data with headers for an associative array structure
            return array_combine($headers, $row);
        });
    }
}
