<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
		$this->dispatcher->forward(["controller" => "devsetup", "action" => "index"]);
    }

}

