<?php 
namespace App\Lib\Web;

class WRequest implements IBase 
{
	public $posts;
	
	public $gets;
	
	public $error = FALSE;
	
	public function __construct()
	{
		$this->gets = $_GET;
		$this->posts = $_POST;
	}
	
	public function get($name = null , $default = null)
	{
		if($name === null)
		{
			return $this->gets;
		}
		$arr = explode(',',$name);
		
		return $this->getVal($arr, $gets, $default);
	}
	
	public function post($name = null , $default = null)
	{
		if($name === null)
		{
			return $this->posts;
		}
		$arr = explode(',',$name);
		
		return $this->getVal($arr, $posts , $default);
	}
	
	public function delete()
	{
		
	}
	
	public function put()
	{
		
	}
	
	public function isPost()
	{
		return !empty($_POST);
	}
	
	public function __get($name)
	{
		$arr = explode('_',$name);
		
		return $this->getVal($arr, array_merge($gets,$posts));
	}
	
	private function getVal($names, $values, $default = null)
	{
		$this->error = FALSE;
		$arr = array();
		
		foreach ($names as $name) 
		{
			if(isset($values[$name]))
			{
				$arr[$name] = $values[$name];
			} else
			{
				$this->error[] = $name;
				$arr[$name] = $default;
			}
		}
		
		if(count($arr) == 1)
		{
			$arr = $arr[0];
		}
		
		return $arr;
	}
	
	private function safeCheck()
	{
		
	}
}