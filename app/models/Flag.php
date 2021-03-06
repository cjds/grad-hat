<?php
class Flag extends Eloquent{

	protected $table='flags';
 	protected $primaryKey   = 'flag_id';

 	public function moderator(){
		return $this->belongsTo('User','moderator_id');
	}
	
	public function creator(){
		return $this->belongsTo('User','creator_id');
	}
	
	public function post(){
		return $this->belongsTo('Post','post_id');
	}
}

?>