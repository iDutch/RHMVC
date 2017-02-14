<?php

namespace System\Core\RHMVC;

use InvalidArgumentException;


class ServiceContainer
{

    private static $instance = null;

    protected $values        = array();
    protected $shared        = array();
    protected $instances     = array();

    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function set($key, $value, $shared = false)
    {
        $this->values[$key] = $value;
        $this->shared[$key] = $shared;
    }

    public function get($key)
    {
        if(!isset($this->values[$key]))
            throw new InvalidArgumentException(sprintf("Value %s has not been set", $key));

        $value = $this->values[$key];

        // Als het een service betreft
        if(is_callable($value)) {
            // Als de service gedeeld is en al eerder opgebouwd
            if($this->shared[$key] && isset($this->instances[$key])) {
                return $this->instances[$key];
            }

            // Creëer de service door de factor aan te roepen
            $instance = $value($this);

            // Sla gedeelde services op
            if($this->shared[$key]) {
                $this->instances[$key] = $instance;
            }
            return $instance;

        } else { // Als het een parameter betreft
            return $value;
        }
    }

    // Maak een gedeelde service niet-gedeeld of vice versa
    public function setShared($key, $shared)
    {
        $this->shared[$key] = $shared;
    }

}
