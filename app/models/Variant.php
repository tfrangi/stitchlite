<?php 
class Variant extends BaseModel
{

	protected static function boot()
	{
		parent::boot();

		static::updated(function($model)
		{
		    $changes = array();
		    foreach($model -> getDirty() as $key => $value)
		    {
		        $original = $model->getOriginal($key);
		        if($key != 'updated_at')$changes[$key] = $value;
		    }
		    $model -> updated = $changes;
		});
	}


	protected $dates = ['created_at', 'updated_at'];
	public $timestamps = true;


	public function tryMerge($variants)
	{
		foreach ($variants as $variant)
		{
			if($variant['sku'] == $this -> sku && $variant['product_name'] == $this -> product -> name && $variant['type'] != $this -> type)
			{
				// foreach ($this -> updated as $key => $value)
				// {
				// 	if($key !== 'updated_at')
				// 	{
						
				// 	}
				// }

				$param['product'] = array(
						'id'=>$variant['original']['id'], 
						'variants'=>array(
							'id'=> $variant['original']['variant']['id']
							)+$this -> updated, 
					);
				$shop = Shop::where('short_name', $variant['type']) -> first();
				$method = 'push'.ucfirst($shop -> short_name);
				$shop -> $method($param);
				
			}
		}
	}

	public function product()
	{
		return $this -> belongsTo('Product');
	}
}
?>