<?php 
class Product extends BaseModel
{
	public function shops()
	{
		return $this -> belongsToMany('Shop')->withTimestamps();
	}
}
?>