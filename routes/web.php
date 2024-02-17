<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\pCollectionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\pProductController;
use App\Http\Controllers\pProductController_test;
use App\Http\Controllers\ProductUploadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\testapi;
use App\Http\Livewire\ShopScroll;
use App\Mail\WebhookMail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\kCheckoutController;
use App\Http\Controllers\CheckoutSummaryController;
use App\Http\Controllers\FileControllerDemo;
use App\Http\Controllers\MailTestController;
use App\Http\Controllers\PdfController;
use App\Mail\testmarkdown;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::post('/json', [testapi::class, 'getJSON'])->name('getjson');

Route::get('/lv', ShopScroll::class);

Route::get('/infi', [pProductController::class, 'infinit'])->name('product.infini');

Route::get('/bship', [ProfileController::class, 'billshipView'])->name('billship');

Route::get('/profile/company', [ProfileController::class, 'companyview'])->name('profile.company');

Route::get('/checkoutinfo', function(){
    return view('checkout.index');
});

Route::get('/chksumt', [kCheckoutController::class, 'chkout_summary_test'])->name('checkout.summary_t');

Route::get('/chksumt2', [CheckoutSummaryController::class, 'summary'])->name('checkout.summary_t2');

Route::middleware(['guestOrVerified'])->group(function () {
    // Route::get('/ppt', [pProductController::class, 'test'])->name('test');

    Route::get('/', [pProductController::class, 'home'])->name('test');

    Route::get('/shop', [pProductController::class, 'index'])->name('shop');
    // Route::get('/shop/f', [pProductController::class, 'qfilter'])->name('shopf'); error shopscroll to check
    Route::get('/shop/f', [pProductController::class, 'qfilter'])->name('shopf');
    Route::get('/shop/f2', [pProductController::class, 'qfilter2'])->name('shopf2');
    Route::get('/shop/{cat?}', [pProductController::class, 'catFilter'])->name('shop.cat');
    Route::get('/product/{product:item_code}', [pProductController::class, 'view'])->name('product.view');
    // Route::get('/product/{product:item_code}', [pProductController::class, 'view_test'])->name('product.view');

    Route::get('/collection', [pCollectionController::class, 'index'])->name('product.collection');
    Route::get('/collection/{col?}', [pCollectionController::class, 'view'])->name('product.collection.view');
    Route::get('/prem', [pCollectionController::class, 'prem'])->name('homeprem');


    

    Route::prefix('/cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{product:item_code}', [CartController::class, 'add'])->name('add');
        Route::post('/remove/{product:item_code}', [CartController::class, 'remove'])->name('remove');
        Route::post('/update-quantity/{product:item_code}', [CartController::class, 'updateQuantity'])->name('update-quantity');

        // Route::post('/add/{product:slug}', [CartController::class, 'add'])->name('add');
        // Route::post('/remove/{product:slug}', [CartController::class, 'remove'])->name('remove');
        // Route::post('/update-quantity/{product:slug}', [CartController::class, 'updateQuantity'])->name('update-quantity');
        
    });
});

Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/profile', [ProfileController::class, 'view'])->name('profile');
    // Route::post('/profile', [ProfileController::class, 'store'])->name('profile.update');  old address system
    Route::post('/profile', [ProfileController::class, 'store_new'])->name('profile.update');
    Route::post('/profile/password-update', [ProfileController::class, 'passwordUpdate'])->name('profile_password.update');
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout/{order}', [CheckoutController::class, 'checkoutOrder'])->name('cart.checkout-order');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('/orders/{order}', [OrderController::class, 'view'])->name('order.view');

    Route::post('/chksum', [kCheckoutController::class, 'chkout_summary'])->name('checkout.summary');
    Route::get('/chkstep1', [CheckoutSummaryController::class, 'chkout_step1'])->name('checkout.step1');
    // Route::get('/chkstep2', [CheckoutSummaryController::class, 'chkout_step2'])->name('checkout.step2');
    Route::get('/chkstep2', [CheckoutSummaryController::class, 'chkout_step2_v2'])->name('checkout.step2');
    Route::post('/chkstep3', [CheckoutSummaryController::class, 'chkout_step3'])->name('checkout.step3');
    Route::post('/payorder', [OrderController::class, 'payOrder'])->name('order.pay');

    // Route::post('/order-payment', [kCheckoutController::class, 'chkout_order'])->name('order.payment');
    Route::post('/order-payment/{order}', [kCheckoutController::class, 'chkout_order'])->name('order.payment');
    // Route::get('/testCreateSC/{OrderID}', [CheckoutSummaryController::class,'createSC'])->name('test.sc');


    // Route::post('/pay', [kCheckoutController::class, 'payment'])->name('payment');
    //  Quotation 
    Route::post('/kpayment', [kCheckoutController::class, 'kpayment'])->name('kpayment');
    Route::post('/quotation', [kCheckoutController::class, 'quotation'])->name('checkout.quotation');



});

// Route::post('/credit', [CheckoutController::class, 'credit'])->name('checkout.credit');

Route::post('/webhook/stripe', [CheckoutController::class, 'webhook']);

Route::post('/payment', [kCheckoutController::class, 'paymentresult'])->name('paymentresult');

//// k webhook notify
Route::post('/qr', [kCheckoutController::class, 'webhook'])->name('noti_qr');
Route::post('/credit', [kCheckoutController::class, 'webhook'])->name('noti_card');
Route::post('/walletali', [kCheckoutController::class, 'webhook'])->name('noti_ali');

Route::get('/paytest', function(){
    return view('checkout.paytest');
});

Route::get('/payqr', function(){
    return view('checkout.payQR');
})->name('payQR');

Route::get('/dd', function(){
    return view('test.dd');
})->name('dd');

// Route::get('/webhookemail', function(){
//     Mail::to('info@smooot.stu@gmail.com')->send(new WebhookMail());
//     return new WebhookMail();
// });

Route::get('/webhookmail',[kCheckoutController::class,'webhook']);


///////////////////////////////////////// Test

// Route::get('/productt/{product:item_code}', [pProductController::class, 'view_test'])->name('product.view_test');

// Route::get('/productstock/{product:item_code}', [pProductController::class, 'stockTest'])->name('product.view_test');
// Route::get('/allstock', [pProductController::class, 'getAllStockEnpro'])->name('product.stock_test');
// Route::get('/getsstocksdata', [pProductController::class, 'updateStockEnpro_v2']);

// Route::get('/allitemdata', [pProductController::class, 'getAllDataEnpro'])->name('product.data_test');
// Route::get('/getitemsdata', [pProductController::class, 'getAllDataEnpro_v2'])->name('product.getalldata');


// Route::post('/createsc', [CheckoutController::class, 'createSC'])->name('order.create_sc');



//////////////// test mail
Route::get('test/mailhub', [MailTestController::class, 'view']);
// Route::get('test/adcon_m', [MailTestController::class, 'admail'])->name('admail_control');

Route::post('test/mail/neworder', [MailTestController::class, 'newOrder_created'])->name('testmail_newOrder');
Route::post('test/mail/showroomorder', [MailTestController::class, 'showroomOrder'])->name('testmail_showroomOrder');
Route::post('test/mail/showroomorder_fin', [MailTestController::class, 'showroomOrder_final'])->name('showroomOrder_fin');

// Route::post('test/mail/paycom', [MailTestController::class, 'PaymentCompleted'])->name('mail.paycom');
Route::get('test/mail/paycom', [MailTestController::class, 'PaymentCompleted'])->name('mail.paycom');


Route::post('test/mail/orderShipped', [MailTestController::class, 'orderShipped'])->name('testmail_orderShipped');
Route::post('test/mail/quotation', [MailTestController::class, 'quotation'])->name('testmail_quotation');
Route::post('test/mail/pdf', [MailTestController::class, 'mailpdf_test2'])->name('testmail_pdf');

Route::post('test/mail/{payment}', [MailTestController::class, 'newOrder']);

Route::get('test/mkdown',function(){
    return new testmarkdown();
});

Route::get('generate-pdf',[PdfController::class,'generatePdf'])->name('generate-pdf');
// Route::post('generate-pdf',[MailTestController::class, 'PdfOrderinfo'])->name('pdf-orderinfo');

Route::get('/genpdf-oinfo/{OrderID}',[PdfController::class, 'Pdf_orderinfo_test'])->name('pdf-orderinfo');

Route::get('/genpdf-blabel/{OrderID}',[PdfController::class, 'Pdf_boxlabel_test'])->name('pdf-boxlabel');

Route::get('/genpdf-quo/{OrderID}',[PdfController::class, 'Pdf_quotation_test'])->name('pdf-quotation');

Route::get('/genpdf-inv/{OrderID}',[PdfController::class, 'Pdf_invoice'])->name('pdf-invoice');

Route::view('/pdf.invoice', 'pdf.invoice');

////// voucher
Route::post('/voucher',[CartController::class, 'voucher'])->name('cart-voucher');
Route::post('/t_discount',[CheckoutSummaryController::class, 'test_Discount'])->name('test-discount');



// Route::get('newstock',[ProductUploadController::class,'insertNewPtoStock']);

// Route::group(['middleware' => 'Admin'], function () {
Route::middleware(['admin'])->group(function () {

    Route::get('p_upload',[FileControllerDemo::class,'importExport'])->name('import.products');
    // Route::get('join',[FileControllerDemo::class,'addUploadToMaster']);
    
    //// fileupload
    Route::post('importExcel', [FileControllerDemo::class,'importExcel']);
    Route::get('compare',[ProductUploadController::class,'compare']);
    Route::get('addnewp',[ProductUploadController::class,'addNewPtoTables']);

//// stock data
    Route::get('/productstock/{product:item_code}', [pProductController::class, 'stockTest'])->name('product.view_test');
    Route::get('/getsstocksdata', [pProductController::class, 'updateStockEnpro_v2'])->name('product.updatestock_enpro');

    Route::get('/getitemsdata', [pProductController::class, 'getAllDataEnpro_v2'])->name('product.getalldata');

//// create sc
    Route::post('/createsc', [CheckoutSummaryController::class, 'createSC'])->name('order.create_sc');

//// mailhubg
    Route::get('test/adcon_m', [MailTestController::class, 'admail'])->name('admail_control');

});


require __DIR__ . '/auth.php';
