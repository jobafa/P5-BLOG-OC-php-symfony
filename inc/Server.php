<?php
namespace Inc;

class Server
{
    private $server;
    

    public function __construct()
    {
        $this->server = (isset($_SERVER)) ? $_SERVER : null;
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
            return (isset($this->server["$key"])) ? $this->server["$key"] : null;
        } else {
            return $this->server;
        }
    }
    

}
