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

    function handle($page, $mobilePage = null){

        if($this->isMobileDevice() && $mobilePage != null){
            if(file_exists("./controllers/mobile$page.php")){
                $page = "Mobile$page";

            }
        }

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

    function isMobileDevice(){
        $aMobileUA = array(
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile'
        );

        //Return true if Mobile User Agent is detected
        foreach($aMobileUA as $sMobileKey => $sMobileOS){
            if(preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])){
                return true;
            }
        }

        return false;
    }
    function output(){
        return $this->output;

    }

}