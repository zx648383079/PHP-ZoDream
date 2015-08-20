<?php
	namespace App\Controller;
	
	
	use App\Model\MessageModel;
	
	class MessageController extends Controller{
		
		function index(){
			//Auth::user()?"":redirect("/?c=auth");
			$this->send('title','消息');
			
			$message = new MessageModel();
			$data= $message->select();
			$this->send($data);
			$this->show('message');
		}
		
		function add()
		{
			$message = new MessageModel();
			$message->fill($_POST["type"],$_POST["content"],$_POST["id"]);
			
		}
	} 