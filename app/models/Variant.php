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
		        $changes[$key] = [
		            'old' => $original,
		            'new' => $value,
		        ];
		    }
		    $model -> updated = $changes;
		});
	}


	protected $dates = ['created_at', 'updated_at'];
	public $timestamps = true;

	public function product()
	{
		return $this -> belongsTo('Project');
	}
}
?>