<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{

    public function applyVoucher(Request $request){
        $applyVoucher = $request->voucher;

        $voucher=false;
        if(isset($request->voucher)){
            try{
                Voucher::query()->where(['code'=>$applyVoucher])

            }
        }

    }
    //
}
