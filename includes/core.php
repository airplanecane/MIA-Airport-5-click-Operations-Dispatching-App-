<?php
/*  Core - Core file included by all other scripts
 *
 *  Provides functionality for everything that is needed in the application
 *
 * 
 *  Operations Control Room App
 *  Miami - Smart Cities Hackathon @ FIU 03/06/15 - 03/08/15
 */



$core = new Core();


class Core {
    private $db;


    function __construct(){
        if(!defined("DEBUG")){
            define("DEBUG", true);
        }




    }

    public static function writeDebug($str){
        if(DEBUG) {
            echo "<b>DEBUG MESSAGE:  </b>" . $str;
        }

    }

}


