<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'client_name' => 'required|string',
            'client_email' => 'nullable|email',
            'items' => 'required|array',
            'subtotal' => 'required|numeric',
            'tax' => 'nullable|numeric',
            'total' => 'required|numeric',
        ]);

        $invoice = Invoice::create([
            'client_name' => $data['client_name'],
            'client_email' => $data['client_email'],
            'items' => json_encode($data['items']),
            'subtotal' => $data['subtotal'],
            'tax' => $data['tax'] ?? 0,
            'total' => $data['total'],
        ]);

        return response()->json(['message' => 'Invoice saved', 'id' => $invoice->id], 201);
    }

    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        return response()->json($invoice);
    }

    public function download($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->items = json_decode($invoice->items, true);

        $pdf = Pdf::loadView('invoice.pdf', compact('invoice'));
        return $pdf->download('invoice.pdf');
    }
}
