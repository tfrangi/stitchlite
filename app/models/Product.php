<?php 
class Product extends BaseModel
{

	protected $with = ['variants'];

	public function shops()
	{
		return $this -> belongsToMany('Shop')->withTimestamps();
	}

	public function variants()
	{
		return $this -> hasMany('Variant');
	}
}
?>