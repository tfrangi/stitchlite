<?php

class ApiController extends BaseController {

	public function sync()
	{
		$owner = 'toto';
		// $shops = Shop::with('variant') -> get();
		// $shops = Shop::all();
		$Shops = Shop::with('products') -> where('owner', $owner) -> get();
		$variants = array();
		foreach ($Shops as $Shop)
		{
			$variants = array_merge($variants, $Shop -> fetch());
			// $data[$shop -> owner] = $shop -> fetch();
		}
		// $variants = array_flatten($data);
		// return $variants;
		// $Products = $shop -> products() -> with('variant');
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
			// $Product = $shop -> products() -> where('name', $product['product_name']) ->first();
			// $variant = $Product -> variants() -> where('sku', $product['sku']) -> first();
			// $product_id = Product::where('')
			// $Variant = Variant::firstOrNew(array('sku'=>$variant['sku'], 'product_id'=> $product_id));
			// print_d($Variant -> toArray());
			// if($Variant -> updated_at)
			
		}
		return 'ok';

		// return $data;
	}

}
