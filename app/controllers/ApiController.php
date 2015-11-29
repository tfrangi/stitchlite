<?php

class ApiController extends BaseController {

	public function sync()
	{
		$owner = 'toto';
		
		$Shops = Shop::where('owner', $owner) -> get();
		$variants = array();
		foreach ($Shops as $Shop)
		{
			$variants = array_merge($variants, $Shop -> fetch());
		}
		
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
		return 'ok';
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
