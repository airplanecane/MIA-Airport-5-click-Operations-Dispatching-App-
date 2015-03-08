<?php
/*  FILENAME - SHORT DESCRIPTION
 *
 *  LONG DESCRIPTION 
 * 
 *  Operations Control Room App
 *  Miami - Smart Cities Hackathon @ FIU 03/06/15 - 03/08/15
 */

include_once('./controllers/PageHandler.php');
include_once './includes/classes/class.MessageType.php';
include_once './includes/classes/class.Message.php';
include_once './includes/classes/class.User.php';

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
        //$this->page = str_replace('<!-- javascript -->', $this->getJS(), $this->page);
    }

    function generateDepartments(){
        if($this->core != null){
            $departments = $this->core->execute("SELECT * FROM `messagetypes`");
            $departments = $departments->fetchAll(PDO::FETCH_CLASS, MessageType);
            $list = "";
            $n = 0;
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
        //$this->checkCaptcha();

        if($_POST['incident-description'] == null){
            $this->core->error('You need to have a description to submit!');
            echo '<script>window.location.replace("?page=dispatch");</script>';
            return;
        }
        $dispatcher = 2;
        if($this->core != null){
            $db = $this->core->getDB();
            $sub = $db->prepare("INSERT INTO `airport`.`messages` (`id`, `sender`, `first_reader`,
                `type`, `message`, `lat`, `long`, `timestamp`, `unread`, `readtime`)
                VALUES (NULL, :dispatcher, '0', :msgtype, :message, :lat, :long, CURRENT_TIMESTAMP,
                '1', '0000-00-00 00:00:00.000000');");
            $sub->bindParam(':dispatcher', $dispatcher, PDO::PARAM_INT);
            $sub->bindParam(':msgtype', $_POST['selectbasic'], PDO::PARAM_INT);
            $sub->bindParam(':message', $_POST['incident-description'], PDO::PARAM_STR, 160);
            $sub->bindParam(':lat', $_POST['lat']);
            $sub->bindParam(':long', $_POST['long']);
            $sub->execute();
            $this->core->updateCounter();
            echo '<script>window.location.replace("?page=messages");</script>';
        } else {
            throw new RuntimeException();
        }
    }

    private function getJS()
    {
        $js = "<script src='https://www.google.com/recaptcha/api.js'></script>";
        return $js;
    }

    private function checkCaptcha()
    {
        include './includes/recaptchalib.php';
        $secret =  "6Le_IwMTAAAAAOFN_16QeJ6QvEArFo0gpH6S3QA2";
        $resp = null;
        $error = null;
        $reCaptcha = new ReCaptcha($secret);

        if ($_POST["g-recaptcha-response"]) {
            $resp = $reCaptcha->verifyResponse(
                $_SERVER["REMOTE_ADDR"],
                $_POST["g-recaptcha-response"]
            );
        }
        if (!($resp != null && $resp->success)) {

            die('<script>window.location.replace("?page=dispatch&error=Invalid Captcha");</script>');
        }
    }
}