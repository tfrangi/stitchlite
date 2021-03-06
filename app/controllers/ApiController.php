<?php

class ApiController extends BaseController {

	public function sync()
	{
		$owner = 'toto';
		
		$variants = $this -> fetch($owner);
		$updated = $this -> compare($variants);
		// print_d($updated);
		$this -> push($updated, $variants);
		return 'ok';
	}

	public function push($updated, $variants)
	{
		foreach ($updated as $Variant)
		{
			// print_d($Variant);
			// $Variant -> tryMerge($variants);
		}
	}

	public function fetch($owner)
	{
		$Shops = Shop::where('owner', $owner) -> get();
		$variants = array();
		foreach ($Shops as $Shop)
		{
			$variants = array_merge($variants, $Shop -> fetch());
		}
		// print_d($variants);
		return $variants;
	}

	public function compare($variants)
	{
		$updated = array();
		// $i = 0;
		// $tracks = array();
		foreach ($variants as $variant)
		{
			
			$Product = Product::select('products.*') -> leftJoin('product_shop', 'product_shop.product_id', '=', 'product_shop.shop_id') -> where('products.name', $variant['product_name']) -> first();

			// foreach ($tracks as $track)
			// {
			// 	if($variant['sku'] == $track['sku'] && $variant['product_id'])
			// }
			// $i++; // I incremente here the counter because of the "continue" few line below
			
			if(!empty($Product))
			{
				$Variant = Variant::where('sku', $variant['sku']) -> where('product_id', $Product -> id) -> first();
				if(empty($Variant)) $Variant = new Variant();
				elseif($Variant -> updated_at -> gt($variant['updated_at'])) continue;
			}
			else
			{
				$Product = new Product;
				$Product -> name = $variant['product_name'];
				$Product -> save();
				$Product -> shops() -> sync($Shop -> lists('id'));
				$Variant = new Variant();
			} 

			$Variant -> sku = $variant['sku'];
			$Variant -> price = $variant['price'];
			$Variant -> inventory = $variant['inventory'];
			$Variant -> product_id = $Product -> id;
			$Variant -> save();
			$Variant -> type = $variant['type'];
			$Variant -> original = $variant['original'];
			$updated[] = $Variant;
		}
		return $updated;
	}

	public function products()
	{
		return Product::all();
	}

	public function show(Product $Product)
	{
		return $Product;
	}

}
