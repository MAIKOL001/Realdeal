<?php

namespace App\Livewire\Tables;

use Livewire\Component;
use Livewire\WithPagination;
use Revolution\Google\Sheets\Facades\Sheets;

class ImportTable extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $sortField = 'invoice_no';
    public $sortAsc = false;

    public $header = [];
    public $dataRows = [];

    public function mount()
    {
        // Fetch the data from Google Sheets
        $sheet = Sheets::spreadsheet('1-ALjh_euUbhg8uwzSKEmcbA5Wjn_mCCyjpDBo3We76A')->sheet('Sheet1')->all();

        // Check if the sheet is not empty
        if (!empty($sheet)) {
            // Access the first element (header row) using array indexing
            $this->header = $sheet[0];

            // Extract data rows (excluding the header)
            $this->dataRows = array_slice($sheet, 1);
        }
    }

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function render()
    {
        // Example search and sort logic for the Google Sheets data
        $dataRows = collect($this->dataRows);

        if ($this->search) {
            $dataRows = $dataRows->filter(function ($row) {
                return strpos(strtolower(implode(' ', $row)), strtolower($this->search)) !== false;
            });
        }

        if ($this->sortField) {
            $dataRows = $dataRows->sortBy($this->sortField, SORT_REGULAR, !$this->sortAsc);
        }

        $dataRows = $dataRows->slice(0, $this->perPage);

        return view('livewire.tables.import-table', [
            'orders' => $dataRows
        ]);
    }
}
