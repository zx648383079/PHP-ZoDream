<?php 
	/*********************************
	用户表的连接
	*********************************/
	namespace App\Model;
	
	//use App\Lib\PdoSql;
	use App\Lib\CommonSql;
	
	abstract class Model extends CommonSql{
		/*查询到的数据*/
		protected $models;
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
		 * 设置bool值
		 *
		 * @param string $filed
		 * @param string $where
		 * @return int
         */
		public function updateBool($filed , $where )
		{
			$sql = "UPDATE {$this->table} SET {$filed} = CASE WHEN {$filed} = 1 THEN 0 ELSE 1 END WHERE ";
			$sql .= $where;
			return $this->execute($_sql)->rowCount();
		}
		
		/**
		 * int加
		 *
		 * @param string $filed
		 * @param string $where
		 * @param string $num
		 * @return int
         */
		public function updateOne( $filed , $where ,$num = 1)
		{
			$sql = "UPDATE {$this->table} SET {$filed} = {$filed} + {$sum} WHERE ";
			$sql .= $where;
			return $this->execute($_sql)->rowCount();
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
				$this->models = $arr[0];
			}
		}
		
		public function hasOne($model , $key , $forkey = 'id')
		{
			$table = new $model();
			$table->assignRow($forkey , $this->$key);
			return $table;
		}
		
		/*
		* 魔术变量
		* 指定获取的数据来源
		*
		*
		*/
		public function __get($name)
		{
			if(isset($this->$name))
			{
				return $this->$name;
			}else if(isset($this->models[$name])){
				return isset($this->models[$name]);
			}else{
				return null;
			}
		}
	}