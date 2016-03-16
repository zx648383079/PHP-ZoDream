<?php
namespace Service\WeChat;

use Domain\WeChat\Subscribe;
class HomeController extends Controller {
	function indexAction() {
		Subscribe::WeChat()->valid();
	}
}