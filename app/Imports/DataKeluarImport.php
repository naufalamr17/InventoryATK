<?php

namespace App\Imports;

use App\Models\Dataout;
use App\Models\InventoryTotal;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataKeluarImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $dataOut = new DataOut();
        $dataOut->periode = $row['period']; // Adjust to match your column name in the CSV or input data
        $dataOut->date = $row['date']; // Adjust to match your column name in the CSV or input data
        $dataOut->time = $row['time']; // Adjust to match your column name in the CSV or input data
        $dataOut->nik = $row['nik']; // Adjust to match your column name in the CSV or input data
        $dataOut->code = $row['kode']; // Adjust to match your column name in the CSV or input data
        $dataOut->qty = $row['qty']; // Adjust to match your column name in the CSV or input data
        $dataOut->pic = $row['pic']; // Adjust to match your column name in the CSV or input data

        $dataOut->save();

        $lastInventory = InventoryTotal::where('code', $dataOut->code)->first();

        if ($lastInventory) {
            // Update the existing InventoryTotal
            $lastInventory->qty -= $row['qty'];
            $lastInventory->save();
        } 
    }
}
