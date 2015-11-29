<?php

class ApiController extends BaseController {

	public function sync()
	{
		$owner = 'toto';
		
		$variants = $this -> fetch($owner);
		$updated = $this -> compare($variants);
		print_d($updated);
		$this -> push($updated);
		return 'ok';
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
		foreach ($variants as $variant)
		{
			
			$Product = Product::select('products.*') -> leftJoin('product_shop', 'product_shop.product_id', '=', 'product_shop.shop_id') -> where('products.name', $variant['product_name']) -> first();
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
