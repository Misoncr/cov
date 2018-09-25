<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class DevicesController extends ControllerBase
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
     * Searches for devices
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Devices', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $devices = Devices::find($parameters);
        if (count($devices) == 0) {
            $this->flash->notice("The search did not find any devices");

            $this->dispatcher->forward([
                "controller" => "devices",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $devices,
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
     * Edits a device
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $device = Devices::findFirstByid($id);
            if (!$device) {
                $this->flash->error("device was not found");

                $this->dispatcher->forward([
                    'controller' => "devices",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $device->id;

            $this->tag->setDefault("id", $device->getId());
            $this->tag->setDefault("name", $device->getName());
            
        }
    }

    /**
     * Creates a new device
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "devices",
                'action' => 'index'
            ]);

            return;
        }

        $device = new Devices();
        $device->setId($this->request->getPost("id"));
        $device->setName($this->request->getPost("name"));
        

        if (!$device->save()) {
            foreach ($device->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "devices",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("device was created successfully");

        $this->dispatcher->forward([
            'controller' => "devices",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a device edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "devices",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $device = Devices::findFirstByid($id);

        if (!$device) {
            $this->flash->error("device does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "devices",
                'action' => 'index'
            ]);

            return;
        }

        $device->setId($this->request->getPost("id"));
        $device->setName($this->request->getPost("name"));
        

        if (!$device->save()) {

            foreach ($device->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "devices",
                'action' => 'edit',
                'params' => [$device->id]
            ]);

            return;
        }

        $this->flash->success("device was updated successfully");

        $this->dispatcher->forward([
            'controller' => "devices",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a device
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $device = Devices::findFirstByid($id);
        if (!$device) {
            $this->flash->error("device was not found");

            $this->dispatcher->forward([
                'controller' => "devices",
                'action' => 'index'
            ]);

            return;
        }

        if (!$device->delete()) {

            foreach ($device->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "devices",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("device was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "devices",
            'action' => "index"
        ]);
    }

}
