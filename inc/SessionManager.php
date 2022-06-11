<?php
namespace Inc;

class SessionManager
{
   private $session;
   static $instance;

   static function getInstance()
   {
        if(!self::$instance){
            self::$instance = new SessionManager($_SESSION);
        }
        return self::$instance;
    }

   /* public function __construct($session)
    {
        session_start();
    }
*/
    public function __construct($session)
    {
        $this->session = $session;
    }

    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function get($name)
    {
        if(isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
    }


    public function sessionvarUnset($name)
    {
        unset($_SESSION[$name]);
    }

    public function sessionUnset()
    {
        $_SESSION = array();
    }

    public function sessionDestroy()
    {
        session_destroy();
    }

  /*   public function show($name)
    {
        if(isset($_SESSION[$name]))
        {
            $key = $this->get($name);
            $this->remove($name);
            return $key;
        }
    }*/

}
