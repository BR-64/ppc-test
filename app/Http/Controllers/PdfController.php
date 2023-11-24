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

        $pdf = Pdf::loadView('pdf.orderinfo', $order);
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
        // return $pdf->download('orderinfo_'.$order['id'].'.pdf');

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
        // return $pdf->download('boxlabel_'.$order['id'].'.pdf');
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
        // return $pdf->download('quotation_'.$order['id'].'.pdf');
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
        
        $Items = OrderItem::query()
            ->where(['order_id' => $OrderId])
            ->get();

        // dd($Items);
        // dd($Items[1]->quantity);
        // dd($Items[0]['quantity']);

        foreach ($Items as $key=>$value){
            echo("<br>\n".$key."   ".$value->product->item_code);
        }
        // dd(count($Items));

        $item_sc=[];
        foreach ($Items as $Item=>$value){
            $item_sc[]=[
                'item_code'=>$value->product->item_code,
                'unit_code'=>'PCS',
                'qty'=>$value->quantity,
                'unit_price'=>$value->product->retail_price,
                // 'discount_amt'=>$discount
                'discount_amt'=>0
            ];
            // echo("<br>\n".$value->product->item_code);
            // var_dump($item_sc[$key]);
        }
        // foreach ($Items as $Item){
        //     echo("<br>\n".$Item->product->item_code);
        //     $item_sc[$Item->id]=[
        //         'item_code'=>$Item->product->item_code,
        //         'unit_code'=>'PCS',
        //         'qty'=>$Item->quantity,
        //         'unit_price'=>$Item->product->retail_price,
        //         // 'discount_amt'=>$discount
        //         'discount_amt'=>0
        //     ];
        // }

        // dd(count($item_sc));
        // dd($item_sc[7]);
        
        for ($i=0; $i<count($item_sc); $i++){
            var_dump($item_sc[$i]);
        };

            dd($item_sc[0]->product->item_code);
            // dd($Items->product->item_code);

        // dd($order['id']);

        $pdf = Pdf::loadView('pdf.invoice',compact('order','qty'));

        return $pdf->stream('invoice_'.$order['id'].'.pdf');
        // return $pdf->download('invoice_'.$order['id'].'.pdf');
    }
}
