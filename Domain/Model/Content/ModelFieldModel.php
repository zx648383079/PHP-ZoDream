<?php
namespace Domain\Model\Content;

use Domain\Model\Model;
class ModelFieldModel extends Model {
	protected $table = 'model_field';
	
	protected $fillAble = array(
		'model_id',
		'field',
		'name',
		'type',
		'length',
		'formtype',
		'setting',
		'position',
		'tips',
		'pattern'
	);
}