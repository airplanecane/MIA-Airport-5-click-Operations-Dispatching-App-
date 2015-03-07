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
    }

    private function getMessageTable()
    {
        $messages = $this->core->execute("SELECT * FROM `messages`");
        $messages = $messages->fetchAll(PDO::FETCH_CLASS, "Message");

       foreach($messages as $m){
            $this->core->writeDebug("New message");
           $m->setCore($this->core);
           echo "Sender: " . $m->getSenderName() . "<br>";
           echo "Read  : " . $m->getRead() . "<br>";
           echo "Read By : " . $m->getReaderName();
           if($m->getRead() == "Yes"){
               echo " at " . $m->getReadTime();
           }
           echo "<br>Department : " . $m->getType() . "<br>";
           echo "Message : <br> " . $m->getMessage() . "<br><br><hr>";


       }


    }


}