<?php
/*  FILENAME - SHORT DESCRIPTION
 *
 *  LONG DESCRIPTION
 *
 *  Operations Control Room App
 *  Miami - Smart Cities Hackathon @ FIU 03/06/15 - 03/08/15
 */

include_once './controllers/PageHandler.php';
include_once './controllers/MessageBoard.php';
include_once './includes/classes/class.MessageType.php';
include_once './includes/classes/class.Message.php';
include_once './includes/classes/class.User.php';

class MobileMessageBoard extends MessageBoard implements PageHandler {

    private $page = "";

    /**
     * @param $core pointer to Core class
     */
    function __construct($core)
    {

    }

    /**
     * @return string Proper name of page being handled
     */
    function pageName()
    {
        return "Mobile" . parent::pageName();
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
        $this->page .= file_get_contents('./pages/mobilemessageboard.html');
    }



}