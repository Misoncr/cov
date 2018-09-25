<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class PumpSubgroupsController extends ControllerBase
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
     * Searches for pump_subgroups
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'PumpSubgroups', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $pump_subgroups = PumpSubgroups::find($parameters);
        if (count($pump_subgroups) == 0) {
            $this->flash->notice("The search did not find any pump_subgroups");

            $this->dispatcher->forward([
                "controller" => "pump_subgroups",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $pump_subgroups,
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
     * Edits a pump_subgroup
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $pump_subgroup = PumpSubgroups::findFirstByid($id);
            if (!$pump_subgroup) {
                $this->flash->error("pump_subgroup was not found");

                $this->dispatcher->forward([
                    'controller' => "pump_subgroups",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $pump_subgroup->id;

            $this->tag->setDefault("id", $pump_subgroup->getId());
            $this->tag->setDefault("name", $pump_subgroup->getName());
            $this->tag->setDefault("color", $pump_subgroup->getColor());
            
        }
    }

    /**
     * Creates a new pump_subgroup
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "pump_subgroups",
                'action' => 'index'
            ]);

            return;
        }

        $pump_subgroup = new PumpSubgroups();
        $pump_subgroup->setName($this->request->getPost("name"));
        $pump_subgroup->setColor($this->request->getPost("color"));
        

        if (!$pump_subgroup->save()) {
            foreach ($pump_subgroup->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "pump_subgroups",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("pump_subgroup was created successfully");

        $this->dispatcher->forward([
            'controller' => "pump_subgroups",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a pump_subgroup edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "pump_subgroups",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $pump_subgroup = PumpSubgroups::findFirstByid($id);

        if (!$pump_subgroup) {
            $this->flash->error("pump_subgroup does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "pump_subgroups",
                'action' => 'index'
            ]);

            return;
        }

        $pump_subgroup->setName($this->request->getPost("name"));
        $pump_subgroup->setColor($this->request->getPost("color"));
        

        if (!$pump_subgroup->save()) {

            foreach ($pump_subgroup->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "pump_subgroups",
                'action' => 'edit',
                'params' => [$pump_subgroup->id]
            ]);

            return;
        }

        $this->flash->success("pump_subgroup was updated successfully");

        $this->dispatcher->forward([
            'controller' => "pump_subgroups",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a pump_subgroup
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $pump_subgroup = PumpSubgroups::findFirstByid($id);
        if (!$pump_subgroup) {
            $this->flash->error("pump_subgroup was not found");

            $this->dispatcher->forward([
                'controller' => "pump_subgroups",
                'action' => 'index'
            ]);

            return;
        }

        if (!$pump_subgroup->delete()) {

            foreach ($pump_subgroup->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "pump_subgroups",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("pump_subgroup was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "pump_subgroups",
            'action' => "index"
        ]);
    }

}
