<?php
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;

/**
 * Description of Devsetup
 *
 * @author Michal
 */
class DevsetupController extends ControllerBase{
	
	public function indexAction(){
		$data = array();
		$devices = Devices::find();
		foreach ($devices as $device){
			$entry = new stdClass();
			
			// fetch all modules
			$query = $this->modelsManager->createQuery("SELECT m.id, m.on_device_id, m.name AS module_name, m.id_module_type, mt.name AS type_name, mt.no_submodules, mt.color FROM modules AS m 
					JOIN ModuleTypes AS mt on m.id_module_type = mt.id 
					WHERE m.id_device = :id_device:
					ORDER BY m.id_module_type ASC, m.on_device_id ASC");
			$modules = $query->execute([
				"id_device" => $device->id,
			]);
			
			// fetch module count
			$query = $this->modelsManager->createQuery("SELECT mt.name, COUNT(m.id_module_type) AS count, mt.color FROM modules AS m
					JOIN moduletypes AS mt ON m.id_module_type = mt.id 
					WHERE m.id_device = :id_device:
					GROUP BY mt.id");
			$module_count = $query->execute([
				"id_device" => $device->id,
			]);
			
			// fetch pump count
			$query = $this->modelsManager->createQuery("SELECT COUNT(p.id_pump_subgroup) AS count, ps.color, ps.name, ps.id  FROM pumps AS p
					JOIN pumpsubgroups AS ps ON ps.id = p.id_pump_subgroup
					WHERE p.id_device = :id_device:
					GROUP BY ps.id");
			$pumpgroup_count = $query->execute([
				"id_device" => $device->id,
			]);
			
			// fetch all pumps
			$pumps = array();
			foreach($pumpgroup_count as $group){
				$query = $this->modelsManager->createQuery("SELECT p.id, p.on_device_id, p.address, p.id_pump_subgroup, ps.name AS ps_name, ps.color, p.id_pump_type, pt.name AS pt_name, pt.manufacturer FROM pumps AS p
					JOIN pumpsubgroups AS ps ON ps.id = p.id_pump_subgroup
					JOIN pumptypes AS pt ON pt.id = p.id_pump_type
					WHERE p.id_device = :id_device: AND p.id_pump_subgroup = :id_subgroup:
					ORDER BY p.id_pump_subgroup ASC, p.id_pump_type ASC");
				$pumps[$group->id] = $query->execute([
					"id_device" => $device->id,
					"id_subgroup" => $group->id
				]);
			}
			
			$entry->form = new Form($device);
			$entry->form->add(new \Phalcon\Forms\Element\Hidden("id"));
			$entry->form->add(new Text('name'));
			
			$entry->device = $device;
			$entry->module_count = $module_count;
			$entry->modules = $modules;
			$entry->pumps = $pumps;
			$entry->pumpgroup_count = $pumpgroup_count;
			
			$data[] = $entry;
		}
		$this->view->data = $data;
		
		
		// create pump forms
		$pumpForms = array();
		foreach(Pumps::find() as $item){
			$pumpForms[$item->id_device][$item->id] = new PumpEditForm($item);
		}
		$this->view->pumpForms = $pumpForms;
		
		//create module forms
		$moduleForms = array();
		foreach (Modules::find() as $item){
			$moduleForms[$item->id] = new ModuleForm($item);
		}
		$this->view->moduleForms = $moduleForms;
	}
	
	public function addPumpAction($id_device){
		$pmp = new Pumps();
		$pmp->setIdDevice($id_device);
		$last_module = Pumps::find([
			"id_device = '".$id_device."'",
			"order" => "on_device_id  desc",
			"limit" => 1
		]);
		$id = 0;
		foreach ($last_module as $item){
			$id = $item->on_device_id;
		}
		$id+=1;
		$pmp->setOnDeviceId($id);
		$first_type = PumpSubgroups::findFirst();
		$pmp->setIdPumpSubgroup($first_type->id);
		
		if ($pmp->create() === false) {
			$messages = $pmp->getMessages();
			foreach ($messages as $message) {
				$this->flashSession->error($message);
			}
			return $this->response->redirect("devsetup/index");
		}
		
		$this->response->redirect("devsetup/index");
	}
	
	public function addModuleAction($id_device){
		$module = new Modules();
		$module->setIdDevice($id_device);
		$last_module = Modules::find([
			"id_device = '".$id_device."'",
			"order" => "on_device_id  desc",
			"limit" => 1
		]);
		$id = 0;
		foreach ($last_module as $item){
			$id = $item->on_device_id;
		}
		$id+=1;
		$module->setOnDeviceId($id);
		$module->setOnDeviceId($id);
		$this->flashSession->error(var_dump($last_module));
		$module->setName("new module");
		
		$first_type = ModuleTypes::findFirst();
		$module->setIdModuleType($first_type->id);
		
		if ($module->create() === false) {
			$messages = $module->getMessages();
			foreach ($messages as $message) {
				$this->flashSession->error($message);
			}
			return $this->response->redirect("devsetup/index");
		}
		
		$this->response->redirect("devsetup/index");
	}
	
	public function addDeviceAction(){
		$last_device = Devices::find([
			"order" => "id  desc",
			"limit" => 1
		]);
		
		$device = new Devices();
		$id = $last_device[0]->id + 1;
		$device->setId($id);
		$device->setName("new device");
		
		if ($device->create() === false) {
			$messages = $device->getMessages();
			foreach ($messages as $message) {
				$this->flashSession->error($message);
			}
			return $this->response->redirect("devsetup/index");
		}
		
		$this->response->redirect("devsetup/index");
	}
	
	public function updateDeviceAction($id){
		if (!$this->request->isPost()) {
			return $this->dispatcher->forward(
				[
					"controller" => "devsetup",
					"action"     => "index",
				]
			);
		}
		
		$device = Devices::findFirst([
			"conditions" => "id = :id:",
			"bind" => ["id" => $id]
		]);
		
		if (!$device) {
			$this->flashSession->error(
				"zadiadenie neexistuje"
			);

			return $this->response->redirect("devsetup/index");
		}
		
		$form = new Form($device);
		$form->add(new \Phalcon\Forms\Element\Hidden("id"));
		$form->add(new Text('name'));
		$data = $this->request->getPost();

		if (!$form->isValid($data)) {
			$messages = $form->getMessages();
			foreach ($messages as $message) {
				$this->flashSession->error($message);
			}
			return $this->response->redirect("devsetup/index");
		}
		
		if ($device->save() === false) {
			$messages = $device->getMessages();
			foreach ($messages as $message) {
				$this->flashSession->error($message);
			}
			return $this->response->redirect("devsetup/index");
		}
		$form->clear();

		$this->flashSession->success(
			"Údaje boli úspešne upravené"
		);
		
		$this->response->redirect("devsetup/index");
	}
	
	public function updatePumpAction($id){
		if (!$this->request->isPost()) {
			return $this->dispatcher->forward(
				[
					"controller" => "devsetup",
					"action"     => "index",
				]
			);
		}
		
		$pump = Pumps::findFirst([
			"conditions" => "id = :id:",
			"bind" => ["id" => $id]
		]);
		
		if (!$pump) {
			$this->flashSession->error(
				"Pumpa neexistuje"
			);

			return $this->response->redirect("devsetup/index");
		}
		
		$form = new PumpEditForm($pump);

		$data = $this->request->getPost();

		if (!$form->isValid($data)) {
			$messages = $form->getMessages();

			foreach ($messages as $message) {
				$this->flashSession->error($message);
			}

			return $this->response->redirect("devsetup/index");
		}
		
		if ($pump->save() === false) {
			$messages = $pump->getMessages();

			foreach ($messages as $message) {
				$this->flashSession->error($message);
			}

			return $this->response->redirect("devsetup/index");
		}

		$form->clear();

		$this->flashSession->success(
			"Údaje boli úspešne upravené"
		);
		
		$this->response->redirect("devsetup/index");
	}
	
	public function updateModuleAction($id){
		if (!$this->request->isPost()) {
			return $this->dispatcher->forward(
				[
					"controller" => "devsetup",
					"action"     => "index",
				]
			);
		}
		
		$module = Modules::findFirst([
			"conditions" => "id = :id:",
			"bind" => ["id" => $id]
		]);
		
		if (!$module) {
			$this->flashSession->error(
				"Modul neexistuje"
			);

			return $this->response->redirect("devsetup/index");
		}
		
		$form = new ModuleForm($module);

		$data = $this->request->getPost();

		if (!$form->isValid($data)) {
			$messages = $form->getMessages();

			foreach ($messages as $message) {
				$this->flashSession->error($message);
			}

			return $this->response->redirect("devsetup/index");
		}
		
		if ($module->save() === false) {
			$messages = $module->getMessages();

			foreach ($messages as $message) {
				$this->flashSession->error($message);
			}

			return $this->response->redirect("devsetup/index");
		}

		$form->clear();

		$this->flashSession->success(
			"Údaje boli úspešne upravené"
		);
		
		$this->response->redirect("devsetup/index");
	}
	
	 /**
     * Deletes a device
     *
     * @param string $id
     */
    public function deleteDeviceAction($id)
    {
        $device = Devices::findFirstByid($id);
        if (!$device) {
            $this->flashSession->error("zariadenie nenájdené");

            $this->dispatcher->forward([
                'controller' => "devsetup",
                'action' => 'index'
            ]);

            return;
        }

        if (!$device->delete()) {

            foreach ($device->getMessages() as $message) {
                $this->flashSession->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "devsetup",
                'action' => 'index'
            ]);

            return;
        }

        $this->flashSession->success("zariadenie vymazané");

        $this->dispatcher->forward([
            'controller' => "devsetup",
                'action' => 'index'
        ]);
    }
	
	/**
     * Deletes a module
     *
     * @param string $id
     */
    public function deleteModuleAction($id)
    {
        $device = Modules::findFirstByid($id);
        if (!$device) {
            $this->flashSession->error("modul nenájdený");

            $this->dispatcher->forward([
                'controller' => "devsetup",
                'action' => 'index'
            ]);

            return;
        }

        if (!$device->delete()) {

            foreach ($device->getMessages() as $message) {
                $this->flashSession->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "devsetup",
                'action' => 'index'
            ]);

            return;
        }

        $this->flashSession->success("modul vymazaný");

        $this->dispatcher->forward([
            'controller' => "devsetup",
			'action' => 'index'
        ]);
    }
	
	/**
     * Deletes a pump
     *
     * @param string $id
     */
    public function deletePumpAction($id)
    {
        $device = Pumps::findFirstByid($id);
        if (!$device) {
            $this->flashSession->error("čerpadlo nenájdené");

            $this->dispatcher->forward([
                'controller' => "devsetup",
                'action' => 'index'
            ]);

            return;
        }

        if (!$device->delete()) {

            foreach ($device->getMessages() as $message) {
                $this->flashSession->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "devsetup",
                'action' => 'index'
            ]);

            return;
        }

        $this->flashSession->success("čerpadlo vymazané");

        $this->dispatcher->forward([
            'controller' => "devsetup",
            'action' => "index"
        ]);
    }
	
}
