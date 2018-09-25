<?php

class ModuleTypes extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $id;

    /**
     *
     * @var string
     * @Column(type="string", length=25, nullable=true)
     */
    protected $name;

    /**
     *
     * @var integer
     * @Column(type="integer", length=3, nullable=true)
     */
    protected $no_submodules;

    /**
     *
     * @var string
     * @Column(type="string", length=7, nullable=true)
     */
    protected $color;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field no_submodules
     *
     * @param integer $no_submodules
     * @return $this
     */
    public function setNoSubmodules($no_submodules)
    {
        $this->no_submodules = $no_submodules;

        return $this;
    }

    /**
     * Method to set the value of field color
     *
     * @param string $color
     * @return $this
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field no_submodules
     *
     * @return integer
     */
    public function getNoSubmodules()
    {
        return $this->no_submodules;
    }

    /**
     * Returns the value of field color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("cov_controll");
        $this->hasMany('id', 'Modules', 'id_module_type', ['alias' => 'Modules']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ModuleTypes[]|ModuleTypes|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ModuleTypes|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'module_types';
    }

}
