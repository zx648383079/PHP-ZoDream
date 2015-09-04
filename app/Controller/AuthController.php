<?php
	namespace App\Controller;

	use App\Main;
	use App\Model\UserModel;
	use App\Lib\ToList;
	
	class AuthController extends Controller{
		
		protected $rules = array(
			'logout' => '1'	
		);
		
		function index(){
			$post = $_POST;
			if(empty($post))
			{
				$this->show('login',['title' => '登录']);
			}else{
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
						Main::session('user', $result );
						Main::redirect('?c=admin');
					}else{
						$this->show('login',array(
						'error' => '邮箱不存在或密码有误！',
						'email' => isset($error['email'])?'':$post['email']
					));
					}
				}else{
					$this->show('login',array(
						'error' => (new ToList()) -> tostring($error,','),
						'email' => isset($error['email'])?'':$post['email']
					));
				}
				//
			}
		}
		
		function qrcode()
		{
			$this->send(array('title'=>'扫扫二维码','img'=>''));
			$this->show('qrcode');
		}
		
		function logout()
		{
			Main::out('gg');
			$_SESSION['user'] = null;
			Main::redirect('/?c=auth');
		}
		
		function register()
		{
			$post = $_POST;
			if(empty($post))
			{
				$this->show('register',['title' => '注册']);
			}else{
				$error = $this->validata($post, array(
					'name' => 'required',
					'email' =>'unique:user|email|required',
					'pwd' => 'confirm:cpwd|min:6|required'
				));
				
				if(!is_bool($error))
				{
					$this->ajaxJson($error);
				}
			}
			
		}
	} 