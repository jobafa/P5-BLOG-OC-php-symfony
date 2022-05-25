<?php

namespace Inc;

/**
 * Class File
 * Manage $_FILES variables 
 */
class File
{
    private $file;

    public function __construct()
    {
        $this->file = $_FILES;
    }

    public function set($id, $name, $value)
    {
        $this->file[$id][$name] = $value;
    }

    public function get($id, $name = null)
    {
        if ($name != null)
        {
            if(isset($this->file[$id][$name])) 
            {
            return $this->file[$id][$name];
            }
        }
        else
        {
            if(isset($this->file[$id])) 
            {
            return $this->file[$id];
            }
        }
        
    }

    public function all()
    {
        return $this->file;
    }
}