<?php
namespace Inc;

class Server
{
    private $_SERVER;
    

    public function __construct()
    {
        $this->_SERVER = (isset($_SERVER)) ? $_SERVER : null;
    }
    /**
     * Returns a key from the superglobal,
     * as it was at the time of instantiation.
     *
     * @param $key
     * @return mixed
     */
    public function get_SERVER($key = null)
    {
        if (null !== $key) {
            return (isset($this->_SERVER["$key"])) ? $this->_SERVER["$key"] : null;
        } else {
            return $this->_SERVER;
        }
    }
    

}
