<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class PumpsController extends ControllerBase
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
     * Searches for pumps
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Pumps', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $pumps = Pumps::find($parameters);
        if (count($pumps) == 0) {
            $this->flash->notice("The search did not find any pumps");

            $this->dispatcher->forward([
                "controller" => "pumps",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $pumps,
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
     * Edits a pump
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $pump = Pumps::findFirstByid($id);
            if (!$pump) {
                $this->flash->error("pump was not found");

                $this->dispatcher->forward([
                    'controller' => "pumps",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $pump->id;

            $this->tag->setDefault("id", $pump->getId());
            $this->tag->setDefault("id_pump_type", $pump->getIdPumpType());
            $this->tag->setDefault("id_device", $pump->getIdDevice());
            $this->tag->setDefault("address", $pump->getAddress());
            $this->tag->setDefault("id_pump_subgroup", $pump->getIdPumpSubgroup());
            
        }
    }

    /**
     * Creates a new pump
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "pumps",
                'action' => 'index'
            ]);

            return;
        }

        $pump = new Pumps();
        $pump->setId($this->request->getPost("id"));
        $pump->setIdPumpType($this->request->getPost("id_pump_type"));
        $pump->setIdDevice($this->request->getPost("id_device"));
        $pump->setAddress($this->request->getPost("address"));
        $pump->setIdPumpSubgroup($this->request->getPost("id_pump_subgroup"));
        

        if (!$pump->save()) {
            foreach ($pump->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "pumps",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("pump was created successfully");

        $this->dispatcher->forward([
            'controller' => "pumps",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a pump edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "pumps",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $pump = Pumps::findFirstByid($id);

        if (!$pump) {
            $this->flash->error("pump does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "pumps",
                'action' => 'index'
            ]);

            return;
        }

        $pump->setId($this->request->getPost("id"));
        $pump->setIdPumpType($this->request->getPost("id_pump_type"));
        $pump->setIdDevice($this->request->getPost("id_device"));
        $pump->setAddress($this->request->getPost("address"));
        $pump->setIdPumpSubgroup($this->request->getPost("id_pump_subgroup"));
        

        if (!$pump->save()) {

            foreach ($pump->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "pumps",
                'action' => 'edit',
                'params' => [$pump->id]
            ]);

            return;
        }

        $this->flash->success("pump was updated successfully");

        $this->dispatcher->forward([
            'controller' => "pumps",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a pump
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $pump = Pumps::findFirstByid($id);
        if (!$pump) {
            $this->flash->error("pump was not found");

            $this->dispatcher->forward([
                'controller' => "pumps",
                'action' => 'index'
            ]);

            return;
        }

        if (!$pump->delete()) {

            foreach ($pump->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "pumps",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("pump was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "pumps",
            'action' => "index"
        ]);
    }

}
