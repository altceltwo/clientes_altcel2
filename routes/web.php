<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('activations', 'ActivationController')->middleware('auth');
Route::resource('clients','ClientController')->middleware('auth');
Route::resource('petition','PetitionController')->middleware('auth');
Route::resource('portabilities','PortabilityController')->middleware('auth');
Route::resource('shipping','ShippingController')->middleware('auth');

Route::get('consult-cp','ShippingController@consultCP')->name('consultCP');

// OpenPay Routes
Route::post('/create-reference-openpay','ReferenceController@createReference')->name('create-reference.post');
Route::post('/webhook-openpay','WebhookController@notificationOpenPay');
Route::get('/webhook-openpay','WebhookController@openpayPays')->name('webhook-openpay.get')->middleware('auth');


// Client Routes
Route::get('/rechargeGenerate', 'ClientController@rechargeGenerateClient')->name('recharge-view-client.get')->middleware('auth');
Route::get('/clients-details/{id}', 'ClientController@clientDetails')->middleware('auth');
Route::get('/search/clients', 'ClientController@searchClients')->name('search-clients.get');
Route::get('/search/client-product', 'ClientController@searchClientProduct')->name('search-client-product.get');
Route::get('/generateReference/{id}/{type}/{user_id}','ClientController@generateReference')->middleware('auth');
Route::get('/surplusRates/{msisdn}','ClientController@surplusRates')->name('surplusRates')->middleware('auth');
Route::get('/surplusRatesDealer/{msisdn}','ClientController@surplusRatesDealer')->name('surplusRatesDealer')->middleware('auth');
Route::get('/surplusRatesDealerAPI/{msisdn}','ClientController@surplusRatesDealerAPI');
Route::get('/generateRecharge/{id}/{type}/{user_id}/{surplus_id}','ClientController@generateRecharge')->middleware('auth');
Route::get('/show-reference','ClientController@showReferenceClient');
Route::get('/show-product-details/{id_dn}/{id_pay}/{id_act}/{service}','ClientController@productDetails');
Route::get('/consultUF/{msisdn}','ClientController@getInfoUF')->name('consultUF.get')->middleware('auth');
Route::get('/recharge','ClientController@recharge')->name('recharge')->middleware('auth');
Route::get('/my-recharges','ClientController@myRecharges')->name('my-recharges')->middleware('auth');
Route::get('/my-charges','ClientController@myCharges')->name('my-charges')->middleware('auth');
Route::get('/my-changes','ClientController@myChanges')->name('my-changes')->middleware('auth');
Route::get('/monthly-payments/{msisdn}','ClientController@monthlyPayments')->name('monthlyPayments')->middleware('auth');
Route::get('/unbarring','ClientController@unbarring')->name('unbarring.get')->middleware('auth');
Route::get('/unbarring-pos','ClientController@unbarring');
Route::get('/change-product/{msisdn}','ClientController@changeProduct')->name('changeProduct')->middleware('auth');
Route::get('/contracts','ClientController@contractsView')->name('contracts')->middleware('auth');
Route::get('/my-movements','ClientController@myMovements')->name('my-movements')->middleware('auth');

// Dealers Routes
Route::get('/petition-activation','PetitionController@dealerActivation')->name('petition-activation')->middleware('auth');
Route::get('/other-petition-dealer','PetitionController@otherPetition')->name('other-petition')->middleware('auth');
Route::get('/petition-barring','PetitionController@dealerBarring')->name('petition-barring')->middleware('auth');



Route::post('/notifications-webhook', 'WebhookController@notificationWHk');
Route::get('/my-profile','UserController@myProfile')->name('myProfile');
Route::get('/update-my-profile','UserController@updateMyProfile')->name('update-my-profile');

// Card Payments
Route::get('/card-payment/{id}/{type}/{user_id}','OpenPayController@cardPayment');
Route::post('/send-card-payment','ReferenceController@createReference');

// API's Públicas
Route::post('/get-offers-rates-surplus-public','ApiController@getOffersRatesSurplus');
Route::post('/generateReferenceAPIPublic','ApiController@generateReference');
Route::post('/send-card-payment-public','ReferenceController@createReference');
Route::get('/get-offers-rates-diff-public','ApiController@getOffersRatesDiffPublic');
Route::post('/get-data-monthly-public','ClientController@getDataMonthly');
Route::post('/get-data-monthly-oreda-public','ClientController@getDataMonthlyOreda');
Route::get('/consultUF-public/{msisdn}','ClientController@getInfoUFPublic');
Route::get('/get-monthly-payment-public','ClientController@getMonthlyPayment');
Route::get('/verify-exists-msisdn/{msisdn}','ClientController@verifyExistsMSISDN');
Route::get('/get-rate-subsequent-payment','ClientController@getRateSubsequentPayment');
Route::get('/consult-imei','AltanController@consultIMEI');
Route::post('/multipayment-oreda','ReferenceController@multipaymentOreda');

// API's
Route::post('/createJWT','AuthJWTController@createJWT');
Route::post('/createJWTTen','AuthJWTController@createJWTTen');
Route::post('/saveIMG','ActivationController@saveIMG')->name('saveIMG.post');

// API's POS Altcel
Route::get('/recharge-pos-altcel','ClientController@rechargeAPI');
Route::post('/purchase-pos', 'ApiController@purchaseProductPos');
Route::get('/get-offers-rates-diff-pos','ApiController@getOffersRatesDiff');
Route::get('/get-offers-rates-activate','ApiController@getOffersRatesActivate');
Route::post('/change-product-pos','ApiController@changeProduct');
// Route::get('/monthly-payments-pos/{msisdn}','ClientController@monthlyPaymentsPos');
Route::post('/save-manual-pay-pos','ApiController@saveManualPay');

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('/get-offers-rates-surplus','ApiController@getOffersRatesSurplus');
    Route::post('/generateReferenceAPI','ApiController@generateReference');
    Route::post('/get-rates-alta', 'ApiController@getRatesAlta')->name('get-rates-alta.post');
    Route::post('/activationsGeneral', 'ApiController@preactivationAltan')->name('activation-general.post');
    Route::post('/cancelActivation', 'ApiController@cancelActivation')->name('cancelActivation');
    Route::post('/purchase', 'ApiController@purchaseProduct')->name('purchase');
    Route::post('/change-product','ApiController@changeProduct')->name('changeProduct.post');

    Route::get('/save-manual-pay','ApiController@saveManualPay')->name('save-manual-pay.get');
    Route::get('/get-offers-rates-diff','ApiController@getOffersRatesDiff')->name('getOffersRatesDiff.get');
});

Route::get('/descargarPDF/{datos}','PDFController@PDF')->name('descargarPDF.get');
Route::get('myReferences', 'ClientController@myReferences')->name('myReferences');
Route::get('/getDataContract','ClientController@getDataContract')->name('getDataContract');

Route::get('preactivation', 'ActivationController@index')->name('preactivation')->middleware('auth');

Route::get('/dealer-index','DealerController@index');
Route::get('/petitions-completed','PetitionController@completedPetitions')->name('petition.completed')->middleware('auth');
Route::get('/get-rates-petition','PetitionController@getRatesPetition')->name('petition.rates')->middleware('auth');
Route::get('/get-inventory-petition','PetitionController@getInventoryPetition')->name('petition.inventory')->middleware('auth');
Route::post('/petition-store-dealer','PetitionController@storeDealer')->name('petition.storeDealer')->middleware('auth');
Route::post('/send-other-petition','PetitionController@sendOtherPetition')->name('sendOtherPetition')->middleware('auth');
Route::get('/petitions-dealer','PetitionController@indexDealer')->name('indexDealer')->middleware('auth');
Route::get('/petitions-completed-dealer','PetitionController@completedPetitionsDealer')->name('petition.completedDealer')->middleware('auth');

Route::get('/save-pm','ClientController@savePersonaMoral')->name('savePM');

Route::post('/webhook-altan-redes','NotificationController@getData');
Route::post('/order-create','WebhookController@orderCreate');
Route::get('/current-operator','AltanController@currentOperator')->name('currentOperator');
Route::get('/msisdn-transitory','NumberController@getNumber')->name('msisdnTransitory');

Route::get('/get-activation-by-petition/{petition}', 'PetitionController@getActivation')->name('getActivation');
Route::get('/create-delivery-format/{activation}','PetitionController@createDeliveryFormat')->name('formatDelivery')->middleware('auth');

Route::post('/appUser',"ClientController@appUser");