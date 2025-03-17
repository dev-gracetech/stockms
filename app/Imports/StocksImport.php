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
            'batch'           => $row['batch'], // Map 'batch' column
            'minimum_threshold' => $row['minimum_threshold'], // Map 'minimum_threshold' column
            'price'           => $row['price'], // Map 'price' column
            'selling_price'   => $row['selling_price'], // Map 'seling price' column
            'expiry_date'     => $row['expiry_date'] ?? null, // Map 'expiry_date' column
            'location'        => $row['location'] ?? null, // Map 'location' column
            'image'           => $row['image'] ?? null, // Map 'image' column (optional)
            'warehouse_id'    => $row['warehouse_id']
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'batch' => 'required|string|max:255',
            'minimum_threshold' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'warehouse_id' => 'required|exists:warehouses,id',
        ];
    }
}
