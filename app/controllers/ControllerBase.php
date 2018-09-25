<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{

	public function initialize(){
		$this->assets->addCss("css/font-awesome.min.css");
		$this->assets->addCss("css/nprogress.css");
		$this->assets->addCss("css/green.css");
		$this->assets->addCss("css/bootstrap-progressbar-3.3.4.min.css");
		$this->assets->addCss("css/jqvmap.min.css");
		$this->assets->addCss("css/custom.css");
		$this->assets->addCss("css/custom_2.css");
		
		$this->assets->addJs("js/fastclick.js");
		$this->assets->addJs("js/nprogress.js");
		$this->assets->addJs("js/Chart.min.js");
		$this->assets->addJs("js/bootstrap-progressbar.min.js");
		$this->assets->addJs("js/icheck.min.js");
		$this->assets->addJs("js/skycons.js");
		$this->assets->addJs("js/custom.js");
		$this->assets->addJs("js/custom_2.js");
	}
}
