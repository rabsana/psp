<?php

namespace Rabsana\Psp\Console\Commands;

use Illuminate\Console\Command;
use Rabsana\Psp\Models\Invoice;

class ExpireInvoiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'psp-invoices:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "This command expire the invoices";



    public function handle()
    {
        $expireDate = date('Y-m-d H:i:s', strtotime("-" . config("rabsana-psp.invoiceLifeTime", 10) . " minutes"));

        $invoices = Invoice::where('created_at', '<', $expireDate)
            ->status(Invoice::CREATED)
            ->get();

        if (filled($invoices)) {

            $ids = collect($invoices)->pluck('id')->toArray();
            Invoice::whereIn('id', $ids)->update([
                'status' => Invoice::EXPIRED
            ]);

            // 
        }
    }
}
