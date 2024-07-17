<?php

namespace App\Imports;

use App\Models\Inventory;
use App\Models\InventoryTotal;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class YourDataImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Generate unique code based on category and iteration
        // $lastInventory = Inventory::where('category', strtoupper($row['category']))
        //     ->orderBy('id', 'desc')
        //     ->first();

        // $nextId = $lastInventory ? intval(substr($lastInventory->code, -3)) + 1 : 1;
        // $code = strtoupper($row['category']) . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        // Assuming Inventory model is used for storing inventory data
        $inventory = new Inventory();
        $inventory->location = ucwords(strtolower($row['location']));
        $inventory->period = $row['period']; // Adjust to match your column name in the CSV or input data
        $inventory->date = $row['date']; // Adjust to match your column name in the CSV or input data
        $inventory->time = $row['time']; // Adjust to match your column name in the CSV or input data
        $inventory->pic = $row['pic']; // Adjust to match your column name in the CSV or input data
        $inventory->qty = $row['qty']; // Adjust to match your column name in the CSV or input data
        $inventory->price = $row['price']; // Adjust to match your column name in the CSV or input data
        $inventory->category = strtoupper($row['category']);
        $inventory->name = $row['name']; // Adjust to match your column name in the CSV or input data
        $inventory->unit = $row['unit']; // Adjust to match your column name in the CSV or input data
        $inventory->vendor_id = $row['vendor']; // Adjust to match your column name in the CSV or input data
        $inventory->code = $row['kode'];

        // Save the inventory
        $inventory->save();

        $lastInventory = InventoryTotal::where('code', $inventory->code)->first();

        if ($lastInventory) {
            // Update the existing InventoryTotal
            $lastInventory->qty += $row['qty'];
            $lastInventory->save();
        } else {
            // Create a new InventoryTotal if none exists
            $inventoryTotal = new InventoryTotal();
            $inventoryTotal->code = $row['kode'];
            $inventoryTotal->qty = $row['qty'];
            $inventoryTotal->location = ucwords(strtolower($row['location']));
            $inventoryTotal->name = $row['name'];
            $inventoryTotal->unit = $row['unit'];
            $inventoryTotal->save();
        }
    }
}
