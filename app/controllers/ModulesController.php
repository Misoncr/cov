<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class ModulesController extends ControllerBase
{
	public function initialize() {
		 parent::initialize();
		$this->view->setTemplateAfter("crud_common_template");
	}
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
		$this->dispatcher->forward(["action" => "search"]);
    }
    /**
     * Searches for modules
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Modules', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "on_device_id";

        $modules = Modules::find($parameters);
        if (count($modules) == 0) {
            $this->flash->notice("The search did not find any modules");

            $this->dispatcher->forward([
                "controller" => "modules",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $modules,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a module
     *
     * @param string $on_device_id
     */
    public function editAction($on_device_id)
    {
        if (!$this->request->isPost()) {

            $module = Modules::findFirstByon_device_id($on_device_id);
            if (!$module) {
                $this->flash->error("module was not found");

                $this->dispatcher->forward([
                    'controller' => "modules",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->on_device_id = $module->on_device_id;

            $this->tag->setDefault("on_device_id", $module->getOnDeviceId());
            $this->tag->setDefault("id_device", $module->getIdDevice());
            $this->tag->setDefault("address", $module->getAddress());
            $this->tag->setDefault("name", $module->getName());
            $this->tag->setDefault("id_module_type", $module->getIdModuleType());
            
        }
    }

    /**
     * Creates a new module
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "modules",
                'action' => 'index'
            ]);

            return;
        }

        $module = new Modules();
        $module->setOnDeviceId($this->request->getPost("on_device_id"));
        $module->setIdDevice($this->request->getPost("id_device"));
        $module->setAddress($this->request->getPost("address"));
        $module->setName($this->request->getPost("name"));
        $module->setIdModuleType($this->request->getPost("id_module_type"));
        

        if (!$module->save()) {
            foreach ($module->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "modules",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("module was created successfully");

        $this->dispatcher->forward([
            'controller' => "modules",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a module edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "modules",
                'action' => 'index'
            ]);

            return;
        }

        $on_device_id = $this->request->getPost("on_device_id");
        $module = Modules::findFirstByon_device_id($on_device_id);

        if (!$module) {
            $this->flash->error("module does not exist " . $on_device_id);

            $this->dispatcher->forward([
                'controller' => "modules",
                'action' => 'index'
            ]);

            return;
        }

        $module->setOnDeviceId($this->request->getPost("on_device_id"));
        $module->setIdDevice($this->request->getPost("id_device"));
        $module->setAddress($this->request->getPost("address"));
        $module->setName($this->request->getPost("name"));
        $module->setIdModuleType($this->request->getPost("id_module_type"));
        

        if (!$module->save()) {

            foreach ($module->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "modules",
                'action' => 'edit',
                'params' => [$module->on_device_id]
            ]);

            return;
        }

        $this->flash->success("module was updated successfully");

        $this->dispatcher->forward([
            'controller' => "modules",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a module
     *
     * @param string $on_device_id
     */
    public function deleteAction($on_device_id)
    {
        $module = Modules::findFirstByon_device_id($on_device_id);
        if (!$module) {
            $this->flash->error("module was not found");

            $this->dispatcher->forward([
                'controller' => "modules",
                'action' => 'index'
            ]);

            return;
        }

        if (!$module->delete()) {

            foreach ($module->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "modules",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("module was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "modules",
            'action' => "index"
        ]);
    }

}
