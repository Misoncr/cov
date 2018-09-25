<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class ModuleTypesController extends ControllerBase
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
     * Searches for module_types
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'ModuleTypes', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $module_types = ModuleTypes::find($parameters);
        if (count($module_types) == 0) {
            $this->flash->notice("The search did not find any module_types");

            $this->dispatcher->forward([
                "controller" => "module_types",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $module_types,
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
     * Edits a module_type
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $module_type = ModuleTypes::findFirstByid($id);
            if (!$module_type) {
                $this->flash->error("module_type was not found");

                $this->dispatcher->forward([
                    'controller' => "module_types",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $module_type->id;

            $this->tag->setDefault("id", $module_type->getId());
            $this->tag->setDefault("name", $module_type->getName());
            $this->tag->setDefault("no_submodules", $module_type->getNoSubmodules());
            $this->tag->setDefault("color", $module_type->getColor());
            
        }
    }

    /**
     * Creates a new module_type
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "module_types",
                'action' => 'index'
            ]);

            return;
        }

        $module_type = new ModuleTypes();
        $module_type->setName($this->request->getPost("name"));
        $module_type->setNoSubmodules($this->request->getPost("no_submodules"));
        $module_type->setColor($this->request->getPost("color"));
        

        if (!$module_type->save()) {
            foreach ($module_type->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "module_types",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("module_type was created successfully");

        $this->dispatcher->forward([
            'controller' => "module_types",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a module_type edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "module_types",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $module_type = ModuleTypes::findFirstByid($id);

        if (!$module_type) {
            $this->flash->error("module_type does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "module_types",
                'action' => 'index'
            ]);

            return;
        }

        $module_type->setName($this->request->getPost("name"));
        $module_type->setNoSubmodules($this->request->getPost("no_submodules"));
        $module_type->setColor($this->request->getPost("color"));
        

        if (!$module_type->save()) {

            foreach ($module_type->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "module_types",
                'action' => 'edit',
                'params' => [$module_type->id]
            ]);

            return;
        }

        $this->flash->success("module_type was updated successfully");

        $this->dispatcher->forward([
            'controller' => "module_types",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a module_type
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $module_type = ModuleTypes::findFirstByid($id);
        if (!$module_type) {
            $this->flash->error("module_type was not found");

            $this->dispatcher->forward([
                'controller' => "module_types",
                'action' => 'index'
            ]);

            return;
        }

        if (!$module_type->delete()) {

            foreach ($module_type->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "module_types",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("module_type was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "module_types",
            'action' => "index"
        ]);
    }

}
