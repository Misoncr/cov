<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class PumpTypesController extends ControllerBase
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
     * Searches for pump_types
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'PumpTypes', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $pump_types = PumpTypes::find($parameters);
        if (count($pump_types) == 0) {
            $this->flash->notice("The search did not find any pump_types");

            $this->dispatcher->forward([
                "controller" => "pump_types",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $pump_types,
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
     * Edits a pump_type
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $pump_type = PumpTypes::findFirstByid($id);
            if (!$pump_type) {
                $this->flash->error("pump_type was not found");

                $this->dispatcher->forward([
                    'controller' => "pump_types",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $pump_type->id;

            $this->tag->setDefault("id", $pump_type->getId());
            $this->tag->setDefault("name", $pump_type->getName());
            $this->tag->setDefault("manufacturer", $pump_type->getManufacturer());
            
        }
    }

    /**
     * Creates a new pump_type
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "pump_types",
                'action' => 'index'
            ]);

            return;
        }

        $pump_type = new PumpTypes();
        $pump_type->setName($this->request->getPost("name"));
        $pump_type->setManufacturer($this->request->getPost("manufacturer"));
        

        if (!$pump_type->save()) {
            foreach ($pump_type->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "pump_types",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("pump_type was created successfully");

        $this->dispatcher->forward([
            'controller' => "pump_types",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a pump_type edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "pump_types",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $pump_type = PumpTypes::findFirstByid($id);

        if (!$pump_type) {
            $this->flash->error("pump_type does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "pump_types",
                'action' => 'index'
            ]);

            return;
        }

        $pump_type->setName($this->request->getPost("name"));
        $pump_type->setManufacturer($this->request->getPost("manufacturer"));
        

        if (!$pump_type->save()) {

            foreach ($pump_type->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "pump_types",
                'action' => 'edit',
                'params' => [$pump_type->id]
            ]);

            return;
        }

        $this->flash->success("pump_type was updated successfully");

        $this->dispatcher->forward([
            'controller' => "pump_types",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a pump_type
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $pump_type = PumpTypes::findFirstByid($id);
        if (!$pump_type) {
            $this->flash->error("pump_type was not found");

            $this->dispatcher->forward([
                'controller' => "pump_types",
                'action' => 'index'
            ]);

            return;
        }

        if (!$pump_type->delete()) {

            foreach ($pump_type->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "pump_types",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("pump_type was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "pump_types",
            'action' => "index"
        ]);
    }

}
