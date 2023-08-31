<?php

namespace Rabsana\Psp\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rabsana\Psp\Models\Invoice;

class InvoiceController extends Controller
{

    public function index(Request $request)
    {
        $invoicesQuery = Invoice::latest()
            ->search($request)
            ->status($request->status)
            ->token($request->token)
            ->with('merchant')
            ->with('user')
            ->with('currency');
        $invoices = $invoicesQuery
            ->paginate($request->get('perPage', 15))
            ->appends($request->all());

        $report = collect($invoicesQuery->get())->groupBy('currency_id')->map(function($item){
            $item = [
                'name' => $item[0]->currency->name,
                'sum' => collect($item)->sum('qty'),
                'icon' => $item[0]->currency->icon_link
            ];
            return $item;
        });

        $statuses = Invoice::getInvoiceStatuses();
        

        return view("rabsana-psp::admin.invoice.index", [
            'invoices' => $invoices,
            'report' => $report,
            'statuses' => $statuses
        ]);
    }
}
