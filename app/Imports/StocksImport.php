<?php

namespace App\Imports;

use App\Models\Stock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StocksImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Stock([
            'name'            => $row['name'], // Map 'name' column
            'batch'           => $row['batch'] ?? null, // Map 'batch' column
            'quantity'        => $row['quantity'] ?? null, // Map 'quantity' column
            'minimum_threshold' => $row['minimum_threshold'] ?? null, // Map 'minimum_threshold' column
            'price'           => $row['price'], // Map 'price' column
            'selling_price'   => $row['selling_price'], // Map 'seling price' column
            'expiry_date'     => isset($row['expiry_date']) ? \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['expiry_date'])) : null, // Map 'expiry_date' column
            'location'        => $row['location'] ?? null, // Map 'location' column
            'image'           => $row['image'] ?? null, // Map 'image' column (optional)
            'warehouse_id'    => $row['warehouse_id'],
            'category_id'     => $row['category_id'] ?? null
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'batch' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0',
            'minimum_threshold' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'warehouse_id' => 'required|exists:warehouses,id',
        ];
    }
}
