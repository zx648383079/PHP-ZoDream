<?php
namespace App\Controller;


class HomeController extends Controller{
	function index(){
		//Auth::user()?"":redirect("/?c=auth");
		$this->send('title','主页');
		$this->show();
	}
	
	/*     解压缩
	use Alchemy\Zippy\Zippy;

	$zippy = Zippy::load();
	$zippy->create('archive.zip', '/path/to/folder');
	
	$archive = $zippy->open('build.tar');
	
	// extract content to `/tmp`
	$archive->extract('/tmp');
	
	// iterates through members
	foreach ($archive as $member) {
		echo "archive contains $member \n";
	}
	*/
} 