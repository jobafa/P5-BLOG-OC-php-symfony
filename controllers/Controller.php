<?php
namespace Controllers;
use Inc\MessageDisplay;
//use Inc\FileUpload;

abstract class Controller
{
	 
	 protected $model;
	 protected $modelName;
    protected $messagedisplay;

     public function __construct(){

        $this->model = new $this->modelName();
        $this->$messagedisplay = new \Inc\MessageDisplay();
        //$this->$fileupload = new \Inc\FileUpload();

     }
}