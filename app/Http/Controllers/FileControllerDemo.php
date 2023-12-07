<?php
namespace App\Http\Controllers;

use App\Imports\CSVImport;
use App\Imports\TestImport;
use App\Models\pProduct_up;
use Illuminate\Http\Request;
use App\Item;
use App\Models\pProduct;
use App\Models\pProduct_upload;
use App\Models\ProductNew;
use Excel;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;

class FileControllerDemo extends Controller

{
	/**
     * Return View file
     *
     * @var array
     */
	public function importExport()
	{
		return view('file_import_export');
	}

	/**
     * File Export Code
     *
     * @var array
     */

	public function downloadExcel(Request $request, $type){
		$data = Item::get()->toArray();

		return Excel::create('itsolutionstuff_example', function($excel) use ($data) {
			$excel->sheet('mySheet', function($sheet) use ($data)
	        {
				$sheet->fromArray($data);
	        });
		})->download($type);
	}

	/**
     * Import file into database Code
     *
     * @var array
     */
	public function importExcel(Request $request){
		if($request->hasFile('import_file')){
			// $path = $request->file('import_file')->getRealPath();
			$path1 = $request->file('import_file')->store('temp');
            $path=storage_path('app').'/'.$path1;

            // delete old data
            pProduct_upload::truncate();
            ProductNew::truncate();

            

            FacadesExcel::import(new TestImport,$path);
            // FacadesExcel::import(new TestImport,$request->file);


        }

		return back()->withSuccess('UPload Done !');
        // return redirect();
	}

    public function addUploadToMaster(){
        pProduct_upload::all()
        ->leftJoin('p_products', 'pProduct_upload.item_code', '=', 'pProducts.item_code')
        ->whereNull('p_products.item_code')->first(
            [
                'pProducts.item_code',
                'pProducts.form',
                'pProducts.glaze',
                'pProducts.bz',
                'pProducts.technique',
                'pProducts.collection',
                'pProducts.category',
                'pProducts.type',
                'pProducts.brand_name',
                'pProducts.product_description',
                'pProducts.color',
                'pProducts.finish_2',
                'pProducts.pre_order',
                // 'pProducts.promotion',
                // 'pProducts.discount',
                
            ]
        );
    }


}
