<?php
namespace App\Controller;

use App;
use App\Model\UserModel;
use App\Lib\ToList;

class AuthController extends Controller{
	
	protected $rules = array(
		'logout' => '1',
		'register' => '!',
		'*' => '?'
	);

	/**
	*登陆界面
	*/
	function indexAction(){
		if( App::$request->isPost() )
		{
			$post = App::$request->post('email,pwd');
			$error = $this->validata( $post , array(
				'email' => 'email|required',
				'pwd' => 'min:6|required'
			));
			if(is_bool($error))
			{
				$user = new UserModel();
				$result = $user->findByUser( $post );
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
				$list = new ToList();
				$this->send(array(
					'error' => $list -> tostring($error,',')
				));
			}
			//
		}
		
		$this->show('login',array(
			'title' => '登录',
			'email' => App::$request->post('email')
		));
	}

	/**
	*扫码登录界面
	*/
	function qrcodeAction()
	{
		$this->send(array('title'=>'扫扫二维码','img'=>''));
		$this->show('qrcode');
	}

	/**
	*执行登出操作
	*/
	function logoutAction()
	{
		App::session('user', '');
		App::redirect('/?c=auth');
	}

	/**
	*注册界面
	*/
	function registerAction()
	{
		if(App::$request->isPost() )
		{
			$post = App::$request->post('name,email,pwd');
			$error = $this->validata( $post , array(
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
				$id = $user -> fillWeb( $post );
				App::session( 'user', $id );
				App::redirect('?c=home');
			}
		}
		
		$this->show('register',array(
			'title' => '注册',
			'name' => App::$request->post('name'),
			'email' => App::$request->post('email')
		));
	}
} 