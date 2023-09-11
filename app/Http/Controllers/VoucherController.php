<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use DateTime;
use Illuminate\Http\Request;

class VoucherController extends Controller
{

    public function applyVoucher(Request $request){
        $applyVoucher = $request->voucher;

        $time=DateTime();
        $voucher=false;
        if(isset($request->voucher)){
            try{
                Voucher::query()->where(
                    ['code'=>$applyVoucher],
                ['valid_until'],'>',);

            } catch(){}
        }

    }
    //
}
