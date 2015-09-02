<?php 
namespace App\Lib;
/**
* SQL语句的拼接和过滤
*
*
*/
class SQL_Join{
	
	/********
	SQL中的关键字数组
	*********/
	const SQL_KEYS = array(/*'show','alter','drop','create,'*/'select','update','set','delete','insert','from','values','left','right','inner','exec','where','and','or','group','having','order','asc','desc','limit');
	/**
	 * 公有构造函数
	 *
	 * @access public
	 *
	 */
	public function __construct()
	{
		
	}

	/**
	 * 根据数组获取SQL语句
	 *
	 * @access public
	 *
	 * @param array $param 要执行的数组
	 * @param bool $sort 是否先进行排序.
	 * @return array 返回排序的数组
	 */
	public function getSQL($param,$sort=FALSE)
	{
		if($sort)
		{
			$param=$this->sortarr($param,self::SQL_KEYS);
		}
		
		return $this->sqlCheck($param);
	}
	
	/**
	 * 根据SQL关键字拼接语句
	 *
	 * @access private
	 *
	 * @param string $key 关键字.
	 * @param string|array $value 值.
	 * @return string 返回拼接后的SQL语句,
	 */
	private function sqlJoin($key,$value)
	{
		$result=' ';
		switch($key)
		{
			/*case 'show':
				$result.='SHOW '.$this->sqlCheck($value);
				break;
			case 'create':
				$result.='CREATE TABLE '.$this->sqlCheck($value,',');
				break;
			case 'alter':
				$result.='ALTER TABLE '.sqlCheck($value,',');
				break;
			case 'drop':
				$result.='DROP TABLE '.sqlCheck($value,',');
				break;*/
			case 'exec':
				$result.='EXEC '.$this->sqlCheck($value);
				break;
			case 'select':
				$result.='SELECT '.$this->sqlCheck($value,',');
				break;
			case 'from':
				$result.='FROM '.$this->sqlCheck($value,',');
				break;
			case 'update':
				$result.='UPDATE '.$this->sqlCheck($value,',');
				break;
			case 'set':
				$result.='SET '.$this->sqlCheck($value,',');
				break;
			case 'delete':
				$result.='DELETE FROM '.$this->sqlCheck($value,',');
				break;
			case 'insert':
				$result.='INSERT INTO '.$this->sqlCheck($value);
				break;
			case 'values':
				$result.='VALUES '.$this->sqlCheck($value,',');
				break;
			case 'limit':
				$result.='LIMIT '.$this->sqlCheck($value,',');
				break;
			case 'order':
				$result.='ORDER BY '.$this->sqlCheck($value,',');
				break;
			case 'group':
				$result.='GROUP BY '.$this->sqlCheck($value,',');
				break;
			case 'having':
				$result.='HAVING '.$this->sqlCheck($value);
				break;
			case 'where':
				$result.='WHERE '.$this->sqlCheck($value,' AND ');
				break;
			case 'or':
				$result.='OR '.$this->sqlCheck($value);
				break;
			case 'and':
				$result.='AND '.$this->sqlCheck($value);
				break;
			case 'desc':
				$result.=$this->sqlCheck($value,',').' DESC';
				break;
			case 'asc':
				$result.=$this->sqlCheck($value,',').' ASC';
				break;
			default:															//默认为是这些关键词 'left','right','inner'
				$result.=strtoupper($key).' JOIN '.$this->sqlCheck($value,' ON ');
				break;
		}
		
		return $result;
	}
	
	/**
	 * SQL关键字检测
	 *
	 * @access private
	 *
	 * @param string|array $value 要检查的语句或数组.
	 * @param string $link 数组之间的连接符.
	 * @return string 返回拼接的语句,
	 */
	private function sqlCheck($value,$link=' ')
	{
		
		$result='';
		
		if(is_array($value))
		{
			foreach($value as $key=>$v)
			{
				$space=' ';
				
				//把关键字转换成小写进行检测
				$low=strtolower($key);
				if(in_array($low,self::SQL_KEYS,true))
				{
					$space.=$this->sqlJoin($low,$v);
				}else{
					if(is_numeric($key))
					{
						if(empty($result))
						{
							$space.=$this->sqlCheck($v);
						}else{
							$space.=$link.$this->sqlCheck($v);
						}
					}else{
						$space.=$key.$link.$this->sqlCheck($v);
					}
				}
				
				$result.=$space;
			}
			
		}else{
			$unsafe=self::SQL_KEYS;
			array_push($unsafe,';');                        //替换SQL关键字和其他非法字符，
			$safe=$this->safeCheck($value,'\'',$unsafe,' ');
			$safe=$this->safeCheck($value,'"',$unsafe,' ');
			$result.=$safe;
		}
		
		$result=preg_replace('/\s+/', ' ', $result);
		$result =str_replace("WHERE AND","WHERE",$result);
		$result =str_replace("WHERE OR","WHERE",$result);
		
		return $result;
	}
	
	 /**
	 * 检查是否是字符串语句
	 *
	 * @access private
	 *
	 * @param string $unsafe 要检查的语句.
	 * @param string $scope 排除语句的标志.
	 * @param string|array $find 要查找的关键字.
	 * @param string|array $enresplace 替换的字符或数组.
	 * @return string 返回完成检查的语句,
	 */
	private function safeCheck($unsafe,$scope,$find,$enresplace)
	{
		$safe='';
		$arr=explode($scope,$unsafe);
		$len=count($arr);
		if($len==1)
		{
			$safe=$unsafe;
		}else{
			foreach($arr as $key=>$val)
			{
				if($key%2==0)
				{
					$low=strtolower($val);                      //转化为小写
					$safe.=str_replace($find,$enresplace,$low);
				}else{
					//如果排除标志不是成对出现，默认在最后加上
					$safe.=$scope.$val.$scope;
				}
			}
		}
		
		return $safe;
	}
	/**
	 * 根据关键字排序，不是在关键字上往后移
	 *
	 * @access private
	 *
	 * @param array $arr 要排序的数组.
	 * @param array $keys 关键字数组.
	 * @return array 返回排序的数组,
	 */
	private function sortarr($arr,$keys)
	{
		$keyarr=$noarr=array();
		foreach($keys as $key=>$value)
		{
			if(isset($arr[$value]))
			{
				$keyarr[$value]=$arr[$value];
			}
		}
		
		foreach($arr as $key=>$value)
		{
			if(!in_array($key,$keys))
			{
				$noarr[$key]=$value;
			}
		}
		
		return array_merge($keyarr,$noarr);
	}
	
}