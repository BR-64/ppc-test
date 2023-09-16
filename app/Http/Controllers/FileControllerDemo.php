<?php
namespace App\Http\Controllers;

use App\Imports\TestImport;
use Illuminate\Http\Request;
use App\Item;
use App\Models\pProduct;
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
			$path = $request->file('import_file')->getRealPath();

            pProduct::truncate();
            FacadesExcel::import(new TestImport,$path);

			// $data = Excel::load($path, function($reader) {})->get();

			// if(!empty($data) && $data->count()){
			// 	foreach ($data->toArray() as $key => $value) {
			// 		if(!empty($value)){
			// 			foreach ($value as $v) {		
			// 				$insert[] = ['title' => $v['title'], 'description' => $v['description']];
			// 			}
			// 		}
			// 	}


			// 	if(!empty($insert)){
			// 		Item::insert($insert);
			// 		return back()->with('success','Insert Record successfully.');
			// 	}
			// }
		}
		// return back()->with('error','Please Check your file, Something is wrong there.');
		return back()->withSuccess('Done !');

	}
}
