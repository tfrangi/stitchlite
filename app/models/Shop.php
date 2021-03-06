<?php 
class Shop extends BaseModel
{
	public function fetch()
	{
		$func = 'fetch'.ucfirst($this -> short_name);
		return $this -> $func();
	}

	public function fetchShopify()
	{
		$this -> formatCredentials();
		$sh = App::make('ShopifyAPI', $this -> credentials);
		$products = json_decode(json_encode($sh->call(['URL' => 'products.json', 'METHOD' => 'GET'])),true)['products'];
		$variants = array();
		$map = ['sku', 'price', 'inventory', 'product_name'];
		// foreach (array_flatten(array_fetch($products, 'variants')) as $variant)
		foreach ($products as $product)
		{
			// print_d($product);
			foreach ($product['variants'] as $variant)
			{
				$variants[] = array(
						'inventory'=> $variant['inventory_quantity'], 
						'price'=>$variant['price'],
						'sku'=>$variant['sku'],
						'product_name'=>strtolower($product['handle']),
						'updated_at'=>new Carbon($variant['updated_at']),
						'type'=> $this -> short_name,
						'original'=> array_only($product, ['id', 'title']) + ['variant'=>$variant],
					);
			}
		}
		// print_d($variants);
		// die();
		return $variants;  //'DATA' => ['limit' => 5, 'published_status' => 'any']

	}

	public function fetchVend()
	{
		$this -> formatCredentials();
		$context = [
			'http' => [
				'method' => 'GET',
				'header' => ['Authorization: Bearer '.$this -> credentials['token'], 'Content-type: application/x-www-form-urlencoded' ]
			]
		];
		$context = stream_context_create($context);

		// Make the API call to receive a full product listing
		$products = file_get_contents('https://'.$this -> credentials['domain'].'.vendhq.com/api/products', false, $context);

		// Convert the JSON string $products to an object so we can call properties from it
		$products=json_decode($products, true)['products'];
		$variants = array();
		foreach ($products as $product)
		{
			$count = isset($product['inventory'][0]['count']) ? (int) $product['inventory'][0]['count'] : 0;
			$updated_at = new Carbon($product['updated_at']);
			$variants[] = array(
					'sku'=>$product['sku'],
					'price'=>$product['price'],
					'product_name'=>strtolower($product['handle']),
					'inventory'=> $count,
					'updated_at'=>$updated_at -> subHours(8),
					'type'=> $this -> short_name,
					'original'=> $products,
				);
		}
		// $products = array_pluck((array)$products, ['handle', 'sku']);
		// print_d($variants);
		// die();
		return $variants;
	}

	public function pushShopify($param)
	{
		print_d($param);
		$this -> formatCredentials();
		$sh = App::make('ShopifyAPI', $this -> credentials);
		$push = $sh->call(['URL' => '/admin/products/'.$param['product']['id'].'.json', 'METHOD' => 'PUT', 'DATA'=>$param]);
		print_d($push);
	}

	public function formatCredentials()
	{
		$this -> credentials = (array)json_decode($this -> credentials);
	}


	public function products()
	{
		return $this -> belongsToMany('Product');
	}

	public function variants()
	{
		return $this -> hasManyThrough('Variant', 'Product');
	}
}
?>