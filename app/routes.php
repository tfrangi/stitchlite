<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::any('/api/sync', 'ApiController@sync');

Route::get('api/products', 'ApiController@products');

Route::model('Product', 'Product');
Route::get('api/products/{Product}', 'ApiController@show');

Route::get('/vend', function()
{
	$context = [
        'http' => [
            'method' => 'GET',
            'header' => ['Authorization: Bearer TQfJxARHtKnvM7AvVaTJ3gYJe3wzxQMqE1zNrL4J', 'Content-type: application/x-www-form-urlencoded' ]
        ]
    ];
    $context = stream_context_create($context);

    // Make the API call to receive a full product listing
    $products = file_get_contents('https://tristanfr.vendhq.com/api/products', false, $context);

    // Convert the JSON string $products to an object so we can call properties from it
    $products=json_decode($products, true);
    return $products;
    print_d($products);
});
Route::get('/shopify', function()
{
	// print_d(App::make('ShopifyAPI'));
	$sh = App::make('ShopifyAPI', ['API_KEY' => '2be4dabd746cfd23a7cdfef172bf8db1', 'API_SECRET' => '1d9dc049a15d75eaa0e35446e822f27f', 'SHOP_DOMAIN' => 'tristan-fr.myshopify.com', 'ACCESS_TOKEN' => '1d9dc049a15d75eaa0e35446e822f27f']);
	// print_d($sh);
	$call = json_encode((array) $sh->call(['URL' => 'products.json', 'METHOD' => 'GET', 'DATA' => ['limit' => 5, 'published_status' => 'any']]));
	return json_decode($call, true);
});

Route::get('check', function()
{
	return 'hello world';
});
