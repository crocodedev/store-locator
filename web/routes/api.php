<?php

use App\Exceptions\ShopifyProductCreatorException;
use App\Lib\ProductCreator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Shopify\Auth\Session as AuthSession;
use Shopify\Clients\Rest;
use App\Http\Controllers\API\StoreController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return "Hello API";
});

Route::get('/products/create', function (Request $request) {
    /** @var AuthSession */
    $session = $request->get('shopifySession'); // Provided by the shopify.auth middleware, guaranteed to be active

    $success = $code = $error = null;
    try {
        ProductCreator::call($session, 5);
        $success = true;
        $code = 200;
        $error = null;
    } catch (\Exception $e) {
        $success = false;

        if ($e instanceof ShopifyProductCreatorException) {
            $code = $e->response->getStatusCode();
            $error = $e->response->getDecodedBody();
            if (array_key_exists("errors", $error)) {
                $error = $error["errors"];
            }
        } else {
            $code = 500;
            $error = $e->getMessage();
        }

        Log::error("Failed to create products: $error");
    } finally {
        return response()->json(["success" => $success, "error" => $error], $code);
    }
})->middleware('shopify.auth');

Route::get('/products/count', function (Request $request) {
    /** @var AuthSession */
    $session = $request->get('shopifySession'); // Provided by the shopify.auth middleware, guaranteed to be active

    $client = new Rest($session->getShop(), $session->getAccessToken());
    $result = $client->get('products/count');

    return response($result->getDecodedBody());
})->middleware('shopify.auth');

//Route::get('/store', [StoreController1::class, 'index'])->middleware('shopify.auth');

Route::post('/store/ids', [StoreController::class, 'destroyIds'])->middleware('shopify.auth');

Route::post('/store/status', [StoreController::class, 'statusIds'])->middleware('shopify.auth');

Route::resource('store', StoreController::class)->middleware('shopify.auth');

Route::get('template', [StoreController::class, 'getTemplate']);

Route::get('store-locator', function (Request $request) {
    $storeLocatorContent = \App\Repositories\StoreTemplate::get('classic', 'crocodeio.myshopify.com');
//
//    $metafield = (new \App\Repositories\Metafield($request->get('shop')))->set('classic', [
//        'html' => $storeLocatorContent
//    ]);

    return response($storeLocatorContent)->withHeaders(['Content-Type' => 'application/liquid']);
});

Route::get('import-file', [\App\Http\Controllers\API\ExpImpController::class, 'import'])->middleware('shopify.auth');

Route::post('export-file', [\App\Http\Controllers\API\ExpImpController::class, 'export'])->middleware('shopify.auth');
