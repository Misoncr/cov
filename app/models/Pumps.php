<?php

class Pumps extends \Phalcon\Mvc\Model
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
     * @Column(type="integer", length=12, nullable=false)
     */
    protected $on_device_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=true)
     */
    protected $id_pump_type;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $id_device;

    /**
     *
     * @var string
     * @Column(type="string", length=8, nullable=true)
     */
    protected $address;

    /**
     *
     * @var integer
     * @Column(type="integer", length=2, nullable=true)
     */
    protected $id_pump_subgroup;

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
     * Method to set the value of field id_pump_type
     *
     * @param integer $id_pump_type
     * @return $this
     */
    public function setIdPumpType($id_pump_type)
    {
        $this->id_pump_type = $id_pump_type;

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
     * Method to set the value of field id_pump_subgroup
     *
     * @param integer $id_pump_subgroup
     * @return $this
     */
    public function setIdPumpSubgroup($id_pump_subgroup)
    {
        $this->id_pump_subgroup = $id_pump_subgroup;

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
     * Returns the value of field id_pump_type
     *
     * @return integer
     */
    public function getIdPumpType()
    {
        return $this->id_pump_type;
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
     * Returns the value of field id_pump_subgroup
     *
     * @return integer
     */
    public function getIdPumpSubgroup()
    {
        return $this->id_pump_subgroup;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("cov_controll");
        $this->belongsTo('id_pump_subgroup', '\PumpSubgroups', 'id', ['alias' => 'PumpSubgroups']);
        $this->belongsTo('id_pump_type', '\PumpTypes', 'id', ['alias' => 'PumpTypes']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Pumps[]|Pumps|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Pumps|\Phalcon\Mvc\Model\ResultInterface
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
        return 'pumps';
    }
	
	public function validation(){
		$validator = new \Phalcon\Validation();
		
		$validator->add(["on_device_id", "id_device"], new Phalcon\Validation\Validator\Uniqueness(array("message" => "id na zariadení musí byť unikátne pre dané zariadenie")));
		
		return $this->validate($validator);
	}

}
