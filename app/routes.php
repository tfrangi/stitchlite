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

// Route::get('sync', 'ApiController@sync');

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

	print_d(Carbon::now());
	$date = new Carbon('2015-11-28T10:42:00-08:00');
	print_d($date);
	die();
	print_d(json_encode([
			'API_KEY' => '2be4dabd746cfd23a7cdfef172bf8db1', 
			'API_SECRET' => '1d9dc049a15d75eaa0e35446e822f27f', 
			'SHOP_DOMAIN' => 'tristan-fr.myshopify.com', 
			'ACCESS_TOKEN' => '1d9dc049a15d75eaa0e35446e822f27f'
		]));
	die();
	return Shop::all();

	// $domain='https://tristanfr.vendhq.com';
	// $username='Bearer'; 
	// $password='TQfJxARHtKnvM7AvVaTJ3gYJe3wzxQMqE1zNrL4J';

	// // Variable parts, depending on what you want to do
	// $url = '/api/products';
	// // $params = array('some_key' => 'some_value');

	// $ch = curl_init();

	// $opts = array(
	// CURLOPT_URL => $url,
	// CURLOPT_RETURNTRANSFER => 1,
	// CURLOPT_POST => 1,
	// // CURLOPT_POSTFIELDS => array('data' => json_encode($params)),
	// CURLOPT_TIMEOUT => 120,
	// CURLOPT_FAILONERROR => 1,
	// CURLOPT_HTTPAUTH => CURLAUTH_ANY, 
	// CURLOPT_USERPWD => "$username:$password" 
	// );

	// // Assign the cURL options
	// curl_setopt_array($ch, $opts);

	// // Get the response
	// $response = curl_exec($ch);
	// print_d($response);
	// var_dump($response);

	// // Close cURL
	// curl_close($ch);

	// // Execute the request & get the response
	// echo $response;


	// die();
	// private function invoke($url, $method, $data = null)
	// {

	//     $ch = curl_init($url);


	//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	//     $json = curl_exec($ch);

	//     $info = curl_getinfo($ch);
	//     echo '<pre>Curl Info<br>';
	//     var_dump($info);
	//     echo '</pre>';

	//     curl_close($ch);

	//     $json_output = json_decode($json, true);

	//     return $json_output;

	// }//end function

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
    $products=json_decode($products);
    print_d($products);
    die();
    return $products;


	$prefix     = 'tristanfr';
	$url = 'https://tristanfr.vendhq.com/api/products';
	return file_get_contents('https://tristanfr.vendhq.com/api/products');
	print_d(file_get_contents(filename));
	$body['code']           = 'l1YV96tal5oDaPJlryltq842ETJj620P6988xFDF';
	$body['client_id']      = 'GMsaNu6ekp2TY7pWmRIw3ZT9lwvKdULX';
	$body['client_secret']  = 'e65lUfjYFXe1GcDePvcjHPp7pCXc7uA4';
	$body['grant_type']     = 'authorization_code';
	$body['redirect_uri']   = 'http://localstitch/vend';

	// $response = $this->invoke($request_url, 'POST', $body);

	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$json = curl_exec($ch);

	$info = curl_getinfo($ch);
	print_d($info);
	// echo '<pre>Curl Info<br>';
	// var_dump($info);
	// echo '</pre>';

	curl_close($ch);

	$json_output = json_decode($json, true);
	return $json_output;
	die();


	$vend = new VendAPI('https://tristanfr.vendhq.com','Bearer','TQfJxARHtKnvM7AvVaTJ3gYJe3wzxQMqE1zNrL4J');
	$products = $vend->getProducts();
	print_d((array)$products);
	die();

	// print_d(App::make('ShopifyAPI'));
	$sh = App::make('ShopifyAPI', ['API_KEY' => '2be4dabd746cfd23a7cdfef172bf8db1', 'API_SECRET' => '1d9dc049a15d75eaa0e35446e822f27f', 'SHOP_DOMAIN' => 'tristan-fr.myshopify.com', 'ACCESS_TOKEN' => '1d9dc049a15d75eaa0e35446e822f27f']);
	// print_d($sh);
	$call = json_encode((array) $sh->call(['URL' => 'products.json', 'METHOD' => 'GET', 'DATA' => ['limit' => 5, 'published_status' => 'any']]));
	return json_decode($call, true);
	// print_d($call);
	// return 'hello world';
});
