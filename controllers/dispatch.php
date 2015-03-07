<?php
/*  FILENAME - SHORT DESCRIPTION
 *
 *  LONG DESCRIPTION 
 * 
 *  Operations Control Room App
 *  Miami - Smart Cities Hackathon @ FIU 03/06/15 - 03/08/15
 */

include_once('./controllers/PageHandler.php');

class Dispatch implements PageHandler {
    private $name = "Message Dispatch Page";
    private $core;
    private $page;

    /**
     * @param $core pointer to Core class
     */
    function __construct($core)
    {
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
        $this->page = file_get_contents('./pages/dispatch.html');
        $departments = $this->generateDepartments();
        $this->page = str_replace("<!-- Fill Departments -->", $departments, $this->page);

    }

    function generateDepartments(){
        if($this->core != null){
            $departments = $this->core->execute("SELECT * FROM `messagetypes`");
            $departments = $departments->fetchAll(PDO::FETCH_CLASS)


        } else {
            throw new RuntimeException();
        }
    }
}