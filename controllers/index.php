<?php
/*  FILENAME - SHORT DESCRIPTION
 *
 *  LONG DESCRIPTION 
 * 
 *  Operations Control Room App
 *  Miami - Smart Cities Hackathon @ FIU 03/06/15 - 03/08/15
 */

include_once './controllers/PageHandler.php';
class index implements PageHandler {
    private $name = "Index";
    private $page;
    private $core;

    function __construct($core){
        $this->core = $core;
    }

    /**
     * @return string Proper name of page being handled
     */
    function pageName()
    {
        return $this->name;
    }

    /**
     * @return string Full html output page
     */
    function output()
    {
        return $this->page;
    }

    /**
     * Handle and compile the page
     */
    function handle()
    {
        if($_GET['logged-in'] == 1) {
            echo '<script>window.location.replace("?page=messages");</script>';
        } else {
            $this->page = file_get_contents("./pages/login.html");


        }

    }
}