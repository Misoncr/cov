<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModuleForm
 *
 * @author Michal
 */
use Phalcon\Forms\Element\Select;

class ModuleForm extends \Phalcon\Forms\Form{
	
	public function initialize(){
		
		$this->add(new \Phalcon\Forms\Element\Hidden("id"));
		$this->add(new \Phalcon\Forms\Element\Numeric("on_device_id"));
		$this->add(new \Phalcon\Forms\Element\Text("address"));
		$this->add(new \Phalcon\Forms\Element\Text("name"));
		
		$this->add(new Select(
				"id_device",
				Devices::find(),
				[
					"using" =>["id","name"]
				]
			)
		);
		
		$this->add(new Select(
				"id_module_type",
				ModuleTypes::find(),
				[
					"using" => ["id", "name"]
				]
			)
		);
		
	}
}
