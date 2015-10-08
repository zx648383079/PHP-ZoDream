<?php 
/*********************************
用户权限分组表
create table zx_groups ( 
	id int(11) not null AUTO_INCREMENT PRIMARY KEY, 
	name varchar(40), 
	roles varchar(255), 
	cdate int(11) 
)charset = utf8;
*********************************/
namespace App\Model;

use App\Lib\Object\OTime;

class GroupModel extends Model{
	protected $table = "groups";
	
	protected $fillable = array(
		'id',
		'name',
		'roles',
		'cdate'
	);
	
	public function addRoles($roles)
	{
		$model = $this->findOne(
			array(
				"roles = '{$roles}'"
			)
		);
		
		if(is_bool($model))
		{
			$id = $this->add(
				array(
					'roles' => $roles,
					'cdate' => OTime::Now()
				)
			);
		}else{
			$id = $model->id;
		}
		
		return $id;
	}
}