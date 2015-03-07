<?php
/*  Handler - Handle a page based off a controller
 *
 *  LONG DESCRIPTION 
 * 
 *  Operations Control Room App
 *  Miami - Smart Cities Hackathon @ FIU 03/06/15 - 03/08/15
 */

class Handler {
    private $core;
    private $output;
    private $handler;
    function __construct($core){
        $this->core = $core;
    }

    function handle($page){
        if($this->addHandler($page)){
            $this->handler->handle();
            $this->output = $this->handler->output();

        } else {
            throw new RuntimeException("Handler for $page does not exist");
        }

    }

    function addHandler($page){
        if(file_exists("./controllers/$page.php")){
            include_once("./controllers/$page.php");
            $this->handler = new $page($this->core);
        } else {
            return false;
        }
        return true;
    }

    function output(){
        return $this->output;

    }

}