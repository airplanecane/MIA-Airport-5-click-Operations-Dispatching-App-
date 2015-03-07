<?php
/*  FILENAME - SHORT DESCRIPTION
 *
 *  LONG DESCRIPTION 
 * 
 *  Operations Control Room App
 *  Miami - Smart Cities Hackathon @ FIU 03/06/15 - 03/08/15
 */

include_once './controllers/PageHandler.php';
include_once './includes/sharedclasses.php';
class MessageBoard implements PageHandler
{
    private $core;
    private $name = "MessageBoard";
    private $page;

    function __construct($core)
    {
        $this->core = $core;
        $this->core->writeDebug("Message Board Initializing", $this->name);


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
        $tableOfMessages = $this->getMessageTable();

        $this->page = file_get_contents('./pages/messageboard.html');
        $this->page = str_replace("<!-TABLE OF MESSAGES->", $tableOfMessages, $this->page);
        $this->page = str_replace("<!-- HEADER -->", file_get_contents('./pages/header.html'), $this->page);
    }

    private function getMessageTable()
    {
        $messages = $this->core->execute("SELECT * FROM `messages`");
        $messages = $messages->fetchAll(PDO::FETCH_CLASS, "Message");
        $messageTable = "";
       foreach($messages as $m){
           $m->setCore($this->core);

           if($m->getRead() == "No"){
               $timestamp = "Unread";
           } else {
               $timestamp = $m->getReadtime();
           }

           $messageTable .= '<tr>';
           $messageTable .= '<td>'. $m->getTimestamp()  .'</td>';
           $messageTable .= '<td>'. $m->getSenderName() .'</td>';
           $messageTable .= '<td>'. $m->getType() .'</td>';
           $messageTable .= '<td>'. $m->getMessage() .'</td>';
           $messageTable .= '<td>'. $timestamp .'</td>';
           $messageTable .= '<td>'. $m->getReaderName() .'</td>';
           $messageTable .= '<td><button type="button" class="btn btn-danger navbar-btn btn-circle" onclick="window.location=\'?\'">Mark Unread</button></td>';
           $messageTable .= '</tr>';

       }

        return $messageTable;

    }


}