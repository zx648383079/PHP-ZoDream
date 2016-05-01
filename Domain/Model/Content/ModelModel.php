<?php
namespace Domain\Model\Content;

use Domain\Model\Model;
class ModelModel extends Model {
	protected $table = 'model';
	
	protected $fillAble = array(
		'name',
		'tablename',
		'type',
		'categorytpl',
		'listtpl',
		'showtpl',
		'setting'
	);
}