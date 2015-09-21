<?php
namespace App\Controller;

use App\App;
use App\Lib\File\FDir;
use App\Model\QuoteModel;

class HomeController extends Controller{
	function index(){
		
		$imagesUrl = FDir::findDir('asset/img');
		
		foreach ( $imagesUrl as $img ) {
			$images[] = "'" . APP_URL . $img . "'";
		}
		
		$upload = App::config('upload.savepath');
		
		$_quote = new QuoteModel();
		$quotes = $_quote->findAll();
		
		foreach($quotes as $key => $value){
			$value['audiourl'] = APP_URL . $upload . $value['filename'];
			$value['afterimg'] = APP_URL . $upload . $value['fileafter'];
			$value['beforeimg'] = APP_URL . $upload . $value['filebefore'];
			
			$list[] = $value;
		}
		
		$p = array();
		$p['images'] = implode(",", $images);
		$p['list'] = $list;
		
		$this->send('title','主页');
		$this->show('index',$p);
	}
} 