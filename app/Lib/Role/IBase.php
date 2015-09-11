<?php 
namespace App\Lib\Role;

interface IBase
{
	/**
	* 判断权限
	*
	* @param $role 需要的权限
	* @param $roles 拥有的权限
	*/
	function judge($role , $roles);
	
	/**
	* 合成
	*
	*/
	function compose();
}