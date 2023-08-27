<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
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
    public function Pdf_orderinfo_test(Request $request)
    {
        $OrderId = $request->OrderID;

        $order = (Order::query()
                    ->where(['id' => $OrderId])
                    ->first());
                    // ->toArray();

        $pdf = Pdf::loadView('pdf.orderinfo_test',compact('order'));
        // $pdf = Pdf::loadView('pdf.orderinfo_test',$order);

        return $pdf->stream('orderinfo.pdf');
        // return $pdf->download('orderinfo.pdf');

    }
    public function Pdf_boxlabel_test(Request $request)
    {
        $OrderId = $request->OrderID;

        $order = (Order::query()
                    ->where(['id' => $OrderId])
                    ->first());
                    // ->toArray();

        $pdf = Pdf::loadView('pdf.shippingLabel',compact('order'));

        return $pdf->stream('boxlabel.pdf');
        // return $pdf->download('boxlabel.pdf');
    }
    public function Pdf_quotation_test(Request $request)
    {
        $OrderId = $request->OrderID;

        $order = (Order::query()
                    ->where(['id' => $OrderId])
                    ->first());
        
        $qty = OrderItem::query()
            ->where(['order_id' => $OrderId])
            ->sum('quantity');

        $pdf = Pdf::loadView('pdf.quotation',compact('order','qty'));

        return $pdf->stream('quotation.pdf');
        // return $pdf->download('quotation.pdf');
    }
    public function Pdf_invoice(Request $request)
    {
        $OrderId = $request->OrderID;

        $order = (Order::query()
                    ->where(['id' => $OrderId])
                    ->first());
        
        $qty = OrderItem::query()
            ->where(['order_id' => $OrderId])
            ->sum('quantity');

        $pdf = Pdf::loadView('pdf.invoice',compact('order','qty'));

        return $pdf->stream('invoice.pdf');
        // return $pdf->download('quotation.pdf');
    }
}
