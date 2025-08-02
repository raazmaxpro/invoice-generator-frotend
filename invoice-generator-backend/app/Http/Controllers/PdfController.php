<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;


class PdfController extends Controller
{
    public function generate(Request $request)
    {
        // Log request (optional)
        Log::info('Generating PDF with request data', $request->all());

        $data = $request->validate([
            'client_name' => 'required|string',
            'client_email' => 'nullable|email',
            'items' => 'required|array',
            'subtotal' => 'required|numeric',
            'gst' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        $pdf = Pdf::loadView('pdf.invoice', ['data' => $data]);

        return $pdf->download('invoice.pdf'); // or ->stream() for preview
    }
}
