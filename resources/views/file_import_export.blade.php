<html lang="en">

<head>

	<title>Import Prempracha</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" >

</head>


<body>

<br/>

<br/>

	<div class="container">		
		<div class="panel panel-primary">
		  <div class="panel-heading">
		    <h3 class="panel-title" style="padding:12px 0px;font-size:25px;"><strong>Prempracha : import export csv or excel file into database</strong></h3>
		  </div>

		  <div class="panel-body">
		  		@if ($message = Session::get('success'))
					<div class="alert alert-success" role="alert">
						{{ Session::get('success') }}
					</div>
				@endif

				@if ($message = Session::get('error'))
					<div class="alert alert-danger" role="alert">
						{{ Session::get('error') }}
					</div>
				@endif

				<h3><1> Import File Form:</h3>

				<form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 20px;" action="{{ URL::to('importExcel') }}" class="form-horizontal" method="post" enctype="multipart/form-data">

					<input type="file" name="import_file" />
					{{ csrf_field() }}
					<br/>
					<button class="btn btn-primary">Import CSVFile</button>
				</form>
				<br/>

				<h3><2> Compare with existing products:</h3>
				<form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 20px;" action="{{ URL::to('compare') }}" class="form-horizontal" method="get" enctype="multipart/form-data">
					<p>compare 'upload products' from file in <1> with 'products' in web database,</p>
					<p>then get stock data and item data of new products from enpro</p>

					<button type="submit" class="btn btn-primary"  >
						Compare products
					</button>
				</form>
				<br/>

				<h3><3> Import new products:</h3>
				<form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 20px;" action="{{ URL::to('addnewp') }}" class="form-horizontal" method="get" enctype="multipart/form-data">
					<p>import 'new products' which is result of <2> into 'products' in web database</p>

					<button type="submit" class="btn btn-primary"   onclick="clicked(event)">
						Import new products
					</button>
				</form>
		    	

		    	{{-- <h3>Import File From Database:</h3>

		    	<div style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 20px;"> 		

			    	<a href="{{ url('downloadExcel/xls') }}"><button class="btn btn-success btn-lg">Download Excel xls</button></a>

					<a href="{{ url('downloadExcel/xlsx') }}"><button class="btn btn-success btn-lg">Download Excel xlsx</button></a>

					<a href="{{ url('downloadExcel/csv') }}"><button class="btn btn-success btn-lg">Download CSV</button></a>

		    	</div> --}}


		  </div>

		</div>

	</div>

	<script>

	// function clicked(e)
    // {
    //     if(!confirm('Do you want to compare products?')) {
    //         e.preventDefault();
    //     }
    // }

	function clicked(e)
    {
        if(!confirm('Do you want to import new products?')) {
            e.preventDefault();
        }
    }

    </script>


</body>


</html>