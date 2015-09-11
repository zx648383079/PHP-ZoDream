<?php
namespace App\Controller;

use App\App;
use App\Model\UserModel;
use App\Lib\ToList;

class AuthController extends Controller{
	
	protected $rules = array(
		'logout' => '1',
		'*' => '?'
	);

	/**
		*登陆界面
		*/
	function index(){
		$post = $_POST;
		if(!empty($post))
		{
			$error = $this->validata($post,array(
				'email' => 'email|required',
				'pwd' => 'min:6|required'
			));
			if(is_bool($error))
			{
				$user = new UserModel();
				$result = $user->findByUser($post['email'],$post['pwd']);
				if(!is_bool($result))
				{
					App::session('user', $result );
					App::redirect('?c=home');
					exit;
				}else{
					$this->send(array(
					'error' => '邮箱不存在或密码有误！'
				));
				}
			}else{
				$this->send(array(
					'error' => (new ToList()) -> tostring($error,',')
				));
			}
			//
		}
		
		$this->show('login',array(
			'title' => '登录',
			'email' => isset($post['email'])?$post['email']:''
		));
	}

	/**
		*扫码登录界面
		*/
	function qrcode()
	{
		$this->send(array('title'=>'扫扫二维码','img'=>''));
		$this->show('qrcode');
	}

	/**
		*执行登出操作
		*/
	function logout()
	{
		App::session('user', '');
		App::redirect('/?c=auth');
	}

	/**
		*注册界面
		*/
	function register()
	{
		$post = $_POST;
		if(!empty($post))
		{
			$error = $this->validata($post, array(
				'name' => 'required',
				'email' =>'unique:users|email|required',
				'pwd' => 'confirm:cpwd|min:6|required'
			));
			
			if(!is_bool($error))
			{
				$this->send(array(
					'error' => $error
				));
			}else{
				$user = new UserModel();
				$id = $user -> fillWeb($post['name'], $post['email'], $post['pwd']);
				App::session('user', $id );
				App::redirect('?c=home');
				
			}
		}
		
		$this->show('register',array(
			'title' => '注册',
			'name' => isset($post['name'])?$post['name']:'',
			'email' => isset($post['email'])?$post['email']:''
		));
	}
} 