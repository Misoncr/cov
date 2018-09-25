<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GraphsController
 *
 * @author Michal
 */
class GraphsController extends ControllerBase{
	
	private $activeDevice;
	
	public function initialize() {
		
		parent::initialize();
		
		$this->assets->addJs("https://cdn.plot.ly/plotly-latest.min.js",false);
		$this->assets->addJs("js/graphs.js");
	}
	
	//put your code here
	public function indexAction($id){
		$devices = Devices::find();
		$id = isset($id) ? $id : $devices[0]->id;
		
		$this->view->devices = $devices;
		$this->view->active =  $id;
		$this->session->set('activeDevice', $id);
		
		// get ac modules
		$query = $this->modelsManager->createQuery("SELECT m.id, m.on_device_id, m.name AS module_name, m.id_module_type, mt.name AS type_name, mt.no_submodules, mt.color FROM modules AS m 
				JOIN ModuleTypes AS mt on m.id_module_type = mt.id 
				WHERE m.id_device = :id_device: AND m.id_module_type = 1
				ORDER BY m.id_module_type ASC, m.on_device_id ASC");
		$ac_modules = $query->execute([
			"id_device" => $id,
		]);
		$this->view->ac_modules = array();
		$this->view->ac_modules = $ac_modules;
	}
	
	public function fetchAction(){
		$request = $this->request;
		if($request->isPost()){
			if($request->isAjax()){
				$cname = $_POST["cname"];
				$client = new InfluxDB\Client($this->config->influx->host, $this->config->influx->port);
				// fetch the database
				$database = $client->selectDB('cov_data_2');

				// executing a query will yield a resultset object
				$result = $database->query('select * from CURRENTS_AC LIMIT 5');

				// get the points from the resultset yields an array
				$points = $result->getPoints();
				
				foreach($points as &$point){
					$splitTime = explode("T", $point["time"]);
					$splitDate = explode("-",$splitTime[0]);
					$splitHourMinSec = explode(".",$splitTime[1]); // treba $splitHourMinSec[0]
					$point["time"] = $splitTime[0]." ".$splitHourMinSec[0];
				}
				echo json_encode($points);
			}
		}
		$this->view->disable();
	}
	
	public function TEMPERATURE_AC_Action(){
		$this->view->disable();
		$request = $this->request;
		if($request->isPost()){
			if($request->isAjax()){
				$mode = $_POST["mode"];
				$device = $this->session->has('activeDevice') ? $this->session->get('activeDevice') : 1;
				$device = 8;	// COMMENT FOR PRODUCTION !!!!!!!!!!!!!
				$where_clause = "WHERE id_zariadenie = '".$device."'"; 
				$limit = "LIMIT 5";
				
				if($mode == "last_day"){
					$where_clause .= " AND time > (now() - 1d)";
				}
				elseif($mode == "refresh"){
					$where_clause .= " AND time > (now() - 10s)";
				}
				
				$query = 'select * from TEMPERATURE_AC '.$where_clause;
				
				// communication with database
				$client = new InfluxDB\Client($this->config->influx->host, $this->config->influx->port);
				// fetch the database
				$database = $client->selectDB($this->config->influx->database);
				// executing a query will yield a resultset object
				$result = $database->query($query);
				// get the points from the resultset yields an array
				$points = $result->getPoints();
				
				// parse queried data
				$timeData = array();
				$valueData = array();
				foreach($points as &$point){
					$splitTime = explode("T", $point["time"]);
					$splitDate = explode("-",$splitTime[0]);
					$splitHourMinSec = explode(".",$splitTime[1]); // treba $splitHourMinSec[0]
					$point["time"] = $splitTime[0]." ".$splitHourMinSec[0];
					$timeData[] = $point["time"];
					$valueData[0][] = $point["rele1"];
				}
				$finalData = [$timeData,$valueData];
				echo json_encode($finalData);
			}
		}	
	}
	
	
	public function CURRENTS_AC_Action(){
		
		$request = $this->request;
		if($request->isPost()){
			if($request->isAjax()){
				$mode = $_POST["mode"];
				$module = $_POST["module"]; 
				$module = 1;	// COMMENT FOR PRODUCTION !!!!!!!!!!!!!
				$device = $this->session->has('activeDevice') ? $this->session->get('activeDevice') : 1;
				$device = 8;	// COMMENT FOR PRODUCTION !!!!!!!!!!!!!
				$where_clause = "WHERE id_zariadenie = '".$device."'"; 
				$limit = "LIMIT 5";
				
				if(isset($module))
					$where_clause .= " AND id_modul = '".$module."'";
				if($mode == "last_day"){
					$where_clause .= " AND time > (now() - 1d)";
				}
				elseif($mode == "refresh"){
					$where_clause .= " AND time > (now() - 10s)";
				}
				
				$query = 'select * from CURRENTS_AC '.$where_clause;
				
				// communication with database
				$client = new InfluxDB\Client($this->config->influx->host, $this->config->influx->port);
				// fetch the database
				$database = $client->selectDB($this->config->influx->database);
				// executing a query will yield a resultset object
				$result = $database->query($query);
				// get the points from the resultset yields an array
				$points = $result->getPoints();
				
				// parse queried data
				$timeData = array();
				$valueData = array();
				foreach($points as &$point){
					$splitTime = explode("T", $point["time"]);
					$splitDate = explode("-",$splitTime[0]);
					$splitHourMinSec = explode(".",$splitTime[1]); // treba $splitHourMinSec[0]
					$point["time"] = $splitTime[0]." ".$splitHourMinSec[0];
					$timeData[] = $point["time"];
					$valueData[0][] = $point["rele1"];
					$valueData[1][] = $point["rele2"];
					$valueData[2][] = $point["rele3"];
					$valueData[3][] = $point["rele4"];
				}
				$finalData = [$timeData,$valueData];
				echo json_encode($finalData);
			}
		}
		$this->view->disable();
	}
}
