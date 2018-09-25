<?php

class Modules extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=12, nullable=false)
     */
    protected $id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $on_device_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $id_device;

    /**
     *
     * @var string
     * @Column(type="string", length=8, nullable=false)
     */
    protected $address;

    /**
     *
     * @var string
     * @Column(type="string", length=25, nullable=false)
     */
    protected $name;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $id_module_type;

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
     * Method to set the value of field on_device_id
     *
     * @param integer $on_device_id
     * @return $this
     */
    public function setOnDeviceId($on_device_id)
    {
        $this->on_device_id = $on_device_id;

        return $this;
    }

    /**
     * Method to set the value of field id_device
     *
     * @param integer $id_device
     * @return $this
     */
    public function setIdDevice($id_device)
    {
        $this->id_device = $id_device;

        return $this;
    }

    /**
     * Method to set the value of field address
     *
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

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
     * Method to set the value of field id_module_type
     *
     * @param integer $id_module_type
     * @return $this
     */
    public function setIdModuleType($id_module_type)
    {
        $this->id_module_type = $id_module_type;

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
     * Returns the value of field on_device_id
     *
     * @return integer
     */
    public function getOnDeviceId()
    {
        return $this->on_device_id;
    }

    /**
     * Returns the value of field id_device
     *
     * @return integer
     */
    public function getIdDevice()
    {
        return $this->id_device;
    }

    /**
     * Returns the value of field address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
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
     * Returns the value of field id_module_type
     *
     * @return integer
     */
    public function getIdModuleType()
    {
        return $this->id_module_type;
    }

    /**
     * Method to set the value of field adress
     *
     * @param string $adress
     * @return $this
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Returns the value of field adress
     *
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("cov_controll");
        $this->hasMany('id', 'Pumps', 'id_module', ['alias' => 'Pumps']);
        $this->belongsTo('id_device', '\Devices', 'id', ['alias' => 'Devices']);
        $this->belongsTo('id_module_type', '\ModuleTypes', 'id', ['alias' => 'ModuleTypes']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Modules[]|Modules|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Modules|\Phalcon\Mvc\Model\ResultInterface
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
        return 'modules';
    }
	
	public function validation(){
		$validator = new \Phalcon\Validation();
		
		$validator->add(["on_device_id", "id_device"], new Phalcon\Validation\Validator\Uniqueness(array("message" => "id na zariadení musí byť unikátne pre dané zariadenie")));
		
		return $this->validate($validator);
	}

}
