<?php
/*  FILENAME - SHORT DESCRIPTION
 *
 *  LONG DESCRIPTION 
 * 
 *  Operations Control Room App
 *  Miami - Smart Cities Hackathon @ FIU 03/06/15 - 03/08/15
 */

include_once('./controllers/PageHandler.php');
include_once('./includes/sharedclasses.php');



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

        if(isset($_POST['submit-report'])){
            $this->handleSubmission();
        }


        $this->page = file_get_contents('./pages/dispatch.html');
        $departments = $this->generateDepartments();
        $this->page = str_replace("<!-- Fill Departments -->", $departments, $this->page);
        $this->page = str_replace("<!-- HEADER -->", file_get_contents('./pages/header.html'), $this->page);
    }

    function generateDepartments(){
        if($this->core != null){
            $departments = $this->core->execute("SELECT * FROM `messagetypes`");
            $departments = $departments->fetchAll(PDO::FETCH_CLASS, MessageType);
            $list = "";
            $n = 1;
            foreach($departments as $department){

                $list .= '<option id="department" value="' . $n .'">'.$department->getTypeDescription().'</option>';
                $n++;

            }
            return $list;

        } else {
            throw new RuntimeException();
        }
    }

    private function handleSubmission()
    {
        $dispatcher = 2;
        if($this->core != null){
            $db = $this->core->getDB();
            $sub = $db->prepare("INSERT INTO `airport`.`messages` (`id`, `sender`, `first_reader`,
                `type`, `message`, `timestamp`, `unread`, `readtime`)
                VALUES (NULL, :dispatcher, '0', :msgtype, :message, CURRENT_TIMESTAMP,
                '1', '0000-00-00 00:00:00.000000');");
            $sub->bindParam(':dispatcher', $dispatcher, PDO::PARAM_INT);
            $sub->bindParam(':msgtype', $_POST['selectbasic'], PDO::PARAM_INT);
            $sub->bindParam(':message', $_POST['incident-description'], PDO::PARAM_STR, 300);
            var_dump($sub->execute());

        } else {
            throw new RuntimeException();
        }
    }
}