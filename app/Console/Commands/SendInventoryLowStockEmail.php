<?php

namespace App\Console\Commands;

use App\Mail\InventoryNotification;
use App\Models\InventoryTotal;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SendInventoryLowStockEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:send-low-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily email for low stock items at 8 AM';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ambil data inventory yang qty-nya kurang dari atau sama dengan 5
        $inventoryTableData = InventoryTotal::select('code', 'name', 'qty', 'location', 'unit')
            ->orderBy('code', 'asc')
            ->where('qty', '<=', 5)
            ->get();

        // Jika ada data yang ditemukan, kirimkan email
        if ($inventoryTableData->isNotEmpty()) {
            $details = [
                'items' => $inventoryTableData
            ];

            // Mail::to(['Naufal.hidayatullah@mlpmining.com'])
            //     ->send(new InventoryNotification($details));
            Mail::to(['reggie.hendriyati@mlpmining.com', 'info@mlpmining.com'])
                ->cc(['endra.putra@mlpmining.com', 'muhamad.sayadih@mlpmining.com', 'Naufal.hidayatullah@mlpmining.com'])
                ->send(new InventoryNotification($details));

            $this->info('Low stock email has been sent successfully.');
        } else {
            $this->info('No low stock items found.');
        }
    }
}
