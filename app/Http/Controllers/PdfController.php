<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    //
    public function generatePdf(Request $request)
    {
        $OrderId = $request->OrderID;

        $order = Order::query()
                    ->where(['id' => $OrderId])
                    ->first();

        $pdf = Pdf::loadView('pdf.orderinfo', $order);
        return $pdf->download('invoice.pdf');

        // $pdf = App::make('dompdf.wrapper');
        // $pdf->loadHTML('<h1>Hello Prempracha</h1>');
        // return $pdf->stream();
    }

    public function Pdf_orderinfo(Request $request)
    {
        $OrderId = $request->OrderID;

        $order = (Order::query()
                    ->where(['id' => $OrderId])
                    ->first())
                    ->toArray();

        // dd($order);

        $pdf = Pdf::loadView('pdf.orderinfo_test', $order);
        return $pdf->download('orderinfo.pdf');

    }
}
