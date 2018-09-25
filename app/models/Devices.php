<?php

class Devices extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
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
     * @var double
     * @Column(type="double", nullable=true)
     */
    protected $latitude;

    /**
     *
     * @var double
     * @Column(type="double", nullable=true)
     */
    protected $longitude;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $last_checked;

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
     * Method to set the value of field latitude
     *
     * @param double $latitude
     * @return $this
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Method to set the value of field longitude
     *
     * @param double $longitude
     * @return $this
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Method to set the value of field last_checked
     *
     * @param integer $last_checked
     * @return $this
     */
    public function setLastChecked($last_checked)
    {
        $this->last_checked = $last_checked;

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
     * Returns the value of field latitude
     *
     * @return double
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Returns the value of field longitude
     *
     * @return double
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Returns the value of field last_checked
     *
     * @return integer
     */
    public function getLastChecked()
    {
        return $this->last_checked;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("cov_controll");
        $this->hasMany('id', 'Modules', 'id_device', ['alias' => 'Modules']);
        $this->hasMany('id', 'Pumps', 'id_device', ['alias' => 'Pumps']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Devices[]|Devices|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Devices|\Phalcon\Mvc\Model\ResultInterface
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
        return 'devices';
    }

}
