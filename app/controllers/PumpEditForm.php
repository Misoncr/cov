<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pumpEditForm
 *
 * @author Michal
 */

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Select;

class PumpEditForm extends Form{
	
	public function initialize() {
		
		$this->add(new \Phalcon\Forms\Element\Hidden("id"));
		$this->add(new \Phalcon\Forms\Element\Numeric("on_device_id"));
		$this->add(new \Phalcon\Forms\Element\Text("address"));
		
		$this->add(new Select(
				"id_device",
				Devices::find(),
				[
					"using" =>["id","name"]
				]
			)
		);
		
		$this->add(new Select(
				"id_pump_type",
				PumpTypes::find(),
				[
					"using" => ["id", "name"]
				]
			)
		);
		
		$this->add(new Select(
				"id_pump_subgroup",
				PumpSubgroups::find(),
				[
					"using" => ["id","name"]
				]
			)
		);
		
	}
}
