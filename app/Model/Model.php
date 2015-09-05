<?php 
	/*********************************
	用户表的连接
	*********************************/
	namespace App\Model;
	
	use App\Lib\PdoSql;
	
	abstract class Model extends PdoSql{

		/**
		 * 填充数据
		 *
		 * @return int
         */
		public function fill()
		{
			$param = func_get_args();
			
			if(count($param) == 1)
			{
				$param=array_shift($param);
			}
			$arr = array_combine($this->fillable,$param);
			if(!isset($arr['cdate']) || empty($arr['cdate']))
			{
				$arr['cdate'] = time();
			}
			
			return $this->add($arr);
		}

		/**
		 * 根据id 查找值
		 *
		 * @param $id
		 * @return mixed
         */
		public function findById($id)
		{
			$sql = "SELECT * FROM {$this->table} WHERE id = {$id}";
			return $this->execute($_sql)->fetchObject();
		}

		/**
		 * 返回Object
		 *
		 * @param string $param
		 * @param string $filed
		 * @return array
         */
		public function findObject($param = '' , $filed = '*')
		{
			$sql = array(
				'select' => $filed,
				'from' => $this->table
			);
			
			if(!empty($param))
			{
				$sql['where'] = $param;
			}
			return $this->query( $sql, FALSE);;
		}

		/**
		 * 返回array
		 *
		 * @param string $param
		 * @param string $filed
		 * @return array
         */
		public function findList($param = '' , $filed = '*')
		{
			$_stmt = $this->findObject($param , $filed);  
			$_result = array(); 
			foreach ($_stmt as $key => $value) {
				foreach ($value as $_key => $_value) {
					$_result[$key][$_key] = $_value;
				}
			}
			return $_result;  
		}
		
		public function assignRow($_key , $_value){
			$arr = $this->findList("{$_key} = '{$_value}'");
			
			if(count($arr) > 0)
			{
				$str = '';
				foreach ($arr[0] as $key => $value) {
					$str.='$this->'.$key.' = "'.$value.'";';
				}
				eval($str);
			}
		}
		
		public function hasOne($model , $key , $forkey = 'id')
		{
			$table = new $model();
			$str = '$table->assignRow($forkey , $this->'.$key.');';
			eval($str);
			return $table;
		}
	}