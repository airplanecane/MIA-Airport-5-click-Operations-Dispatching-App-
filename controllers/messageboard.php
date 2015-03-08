<?php
/*  FILENAME - SHORT DESCRIPTION
 *
 *  LONG DESCRIPTION 
 * 
 *  Operations Control Room App
 *  Miami - Smart Cities Hackathon @ FIU 03/06/15 - 03/08/15
 */

include_once './controllers/PageHandler.php';
include_once './includes/classes/class.MessageType.php';
include_once './includes/classes/class.Message.php';
include_once './includes/classes/class.User.php';
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
        if($_GET['markdone'] || $_GET['markundone']){
           $this->handleInput();
        }

        $tableOfMessages = $this->getMessageTable();

        $this->page = file_get_contents('./pages/messageboard.html');
        $this->page = str_replace("<!-- HEADER -->", file_get_contents('./pages/header.html'), $this->page);
        $this->page = str_replace("<!-TABLE OF MESSAGES->", $tableOfMessages, $this->page);
        $this->page = str_replace("<!-- javascript -->", $this->getJS(), $this->page);
        $this->page = str_replace("<!-- Fill Departments -->", $this->generateDepartments(), $this->page);
    }

    private function getMessageTable()
    {
        $messages = $this->core->execute("SELECT * FROM `messages` ORDER BY `id` DESC");
        $messages = $messages->fetchAll(PDO::FETCH_CLASS, "Message");
        $messageTable = "";
       foreach($messages as $m){
           $m->setCore($this->core);

           if($m->getRead()){
               $timestamp = '<span style:"font-size: medium"> Pending </span>';
           } else {
               $timestamp = '<span style:"font-size: x-small">' . $m->getReadtime() . '</span>';
           }

           $messageTable .= '<div id="geolocation'. $m->getID() .'" class="popbox">
                                <div id="mapContainer'. $m->getID() .'" style="width:400px; height: 600px;"></div>
           <script>
                var map'. $m->getID() .' = new TMap(mapContainer'. $m->getID() .', '.$m->lat .', '.$m->long .', 20, mapLoaded, 1, true, "", "hybrid");

                function mapLoaded(){
                    var layer'. $m->getID().' = map'. $m->getID().'.AddLayer("Layer", "Simple Layer", true, false);
                    var marker'. $m->getID().' = layer'. $m->getID().'.AddMarker('.$m->lat .', '.$m->long .', "'.$m->getSimpleTimestamp().'");
                    marker'. $m->getID().'.SetMarkerStyle("MARKER_SIZE=20;MARKER_COLOR=0x0f0f0f;FONT=Times New Roman;FONT_COLOR=0xCCCCCC");

                }





           </script>
                            </div>';

           $messageTable .= '<tr>';
           if($m->hasGeoInfo()){
               $messageTable .= '
                    <td class="hidden-xs" style="word-wrap: break-word;overflow:hidden; padding-left:32px;">
                        <a href="#" class="popper" data-popbox="geolocation'. $m->getID() .'" >
                            <img src="./images/checkmark.png" style="height: 32px; width: 32px;"/>
                        </a>
                    </td>';

           } else {
               $messageTable .= '
                    <td class="hidden-xs" style="word-wrap: break-word;overflow:hidden; padding-left:32px;">
                            <img src="./images/cross.png" style="height: 32px; width: 32px;"/>
                    </td>';

           }
           $messageTable .= '<td style="word-wrap: break-word;overflow:hidden;">'. $m->getTimestamp()  .'</td>';
           $messageTable .= '<td style="word-wrap: break-word;overflow:hidden;">'. $m->getType() .'</td>';
           $messageTable .= '<td style="word-wrap: break-word;overflow:hidden;">'. $m->getMessage() .'</td>';
           $messageTable .= '<td style="word-wrap: break-word;overflow:hidden;">'. $timestamp .'</td>';
           //$messageTable .= '<td style="word-wrap: break-word;overflow:hidden;">';

           if($m->getRead()){
               $messageTable .= '<td class="visible-xs" style="height:100px;"><button type="button" style="transform: rotate(90deg);transform-origin: left;top 0;width:40px; height:100px; padding-top: 20px;" class="btn btn-success btn-circle" onclick="window.location=\'?page=messages&markdone='.$m->getId().'\'"><i class="fa fa-sign-in"></i></button></td>';
               $messageTable .= '<td class="hidden-xs"><button type="button" class="btn btn-success btn-circle" onclick="window.location=\'?page=messages&markdone='.$m->getId().'\'"><i class="fa fa-sign-in"></i></button></td>';
           } else {
               $messageTable .= '<td class="visible-xs" style="height:100px;"><button type="button" style="transform: rotate(90deg);transform-origin: left;width:40px; height:100px; padding-top: 20px;" class="btn btn-danger btn-circle" onclick="window.location=\'?page=messages&markundone='.$m->getId().'\'"><i class="fa fa-reply-all"></i></button></td>';
               $messageTable .= '<td class="hidden-xs"><button type="button" class="btn btn-danger navbar-btn btn-circle" onclick="window.location=\'?page=messages&markundone='.$m->getId().'\'"><i class="fa fa-reply-all"></i></button></td>';
           }
           $messageTable .= '</tr>';

       }

        return $messageTable;

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
    private function handleInput()
    {



        $db = $this->core->getDB();
        if($db == null){
            throw new PDOException();
        }

        $messages = $db->prepare("SELECT * FROM `messages` WHERE `id` = :id");


        if(isset($_GET['markdone'])){
            $messages->bindParam(':id', $_GET['markdone'], PDO::PARAM_INT);
            $messages->execute();
            $messages = $messages->fetchAll(PDO::FETCH_CLASS, "Message");
            $messages = $messages[0];

            if($messages == null){
                throw new PDOException();
            }

            $messages->setCore($this->core);
            $messages->setReader($_SESSION['userid']);
            $messages->setReadtime(date('Y-m-d G:i:s'));
            $messages->setUnread(false);
            $messages->insert($db);
            $this->core->updateCounter();



        } else if(isset($_GET['markundone'])){
            $messages->bindParam(':id', $_GET['markundone'], PDO::PARAM_INT);
            $messages->execute();
            $messages = $messages->fetchAll(PDO::FETCH_CLASS, "Message");
            $messages = $messages[0];

            if($messages == null){
                throw new PDOException();
            }

            $messages->setCore($this->core);
            $messages->setReader(-1);
            $messages->setUnread(true);
            $messages->insert($db);
            $this->core->updateCounter();

        }
        header('Location: ?page=messages');

    }

    private function getJS()
    {

        $js = '<script src="./scripts/table.js"></script>';

        return $js;

    }


}