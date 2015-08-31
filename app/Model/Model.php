<?php 
	/*********************************
	用户表的连接
	*********************************/
	namespace App\Model;
	
	use App\Lib\PdoSql;
	
	abstract class Model extends PdoSql{
		
		public function fill()
		{
			$param = func_get_args();
			
			if(count($param) == 1)
			{
				$param=array_shift($param);
			}
			
			$arr = array_combine($this->fillable,$param);
			date_default_timezone_set('Etc/GMT-8');     //这里设置了时区
			$arr['cdate'] = date("Y-m-d H:i:s");
			return $this->add($arr);
		}
	}