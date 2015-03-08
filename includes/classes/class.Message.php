<?php
/*  FILENAME - SHORT DESCRIPTION
 *
 *  LONG DESCRIPTION 
 * 
 *  Operations Control Room App
 *  Miami - Smart Cities Hackathon @ FIU 03/06/15 - 03/08/15
 */


date_default_timezone_set("EDT");

class Message
{
    private $core;

    private $sender, $first_reader;
    private $type, $typeid;
    private $message;
    private $timestamp;
    public $lat = 0.0, $long = 0.0;
    private $unread = true;
    private $readtime;
    private $hasGeo;

    function __construct()
    {
        $this->typeid = $this->type;
    }

    function getType()
    {
        if (!$this->type instanceof MessageType)
            $this->typeid = $this->type;
        if ($this->core != null) {
            $messageType = $this->core->execute("SELECT * FROM `messagetypes` WHERE `type` = $this->type");
            $messageType = $messageType->fetchAll(PDO::FETCH_CLASS, "MessageType");
        }

        $this->type = $messageType[0];

        if ($this->type != null) {
            return $this->type->getTypeDescription();
        } else {

            return "";
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @return mixed
     */
    public function getSenderName()
    {
        if ($this->core != null) {
            $name = $this->core->execute("SELECT * FROM `users` WHERE `id` = $this->sender");
            $name = $name->fetchAll(PDO::FETCH_CLASS, "User");
        }
        $this->sender = $name[0];

        return $this->sender->getName();
    }

    /**
     * @return mixed
     */
    public function getReaderName()
    {
        if ($this->core != null) {
            $name = $this->core->execute("SELECT * FROM `users` WHERE `id` = $this->first_reader");
            $name = $name->fetchAll(PDO::FETCH_CLASS, "User");
        }
        $this->reader = $name[0];
        if ($this->reader != null) {
            return $this->reader->getName();
        } else {
            return "Unread";
        }
    }


    /**
     * @param mixed $reader
     */
    public function setReader($reader)
    {
        $this->reader = $reader;
    }


    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }


    public function hasGeoInfo(){

        return (floor($this->lat) != 0 || floor($this->long) != 0);


    }

    public function getSimpleTimestamp(){

        return date('m/d g:i A', strtotime($this->timestamp));
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        if ($this->getRead()) {
            $timestamp =  '<span  class="hidden-xs"  style="color: darkred">' . date('m/d g:i A', strtotime($this->timestamp)) . '</span>';
            $timestamp .=  '<span class="visible-xs" style="color: darkred">' . date('g:i A', strtotime($this->timestamp)) . '</span>';
        } else {
            $timestamp = '<span class="hidden-xs" style="color: green">' . date('m/d g:i A', strtotime($this->timestamp)) . '</span>';
            $timestamp .= '<span class="visible-xs" style="color: green">' . date('g:i A', strtotime($this->timestamp)) . '</span>';
        }
        return $timestamp;
    }


    /**
     * @return mixed
     */
    public function getRead()
    {


        return $this->unread;
    }

    public function getUnread()
    {


        return !$this->unread;
    }

    /**
     * @param mixed $unread
     */
    public function setUnread($unread)
    {
        $this->unread = $unread;
    }

    /**
     * @return mixed
     */
    public function getReadtime()
    {
        return date('g:i A', strtotime($this->readtime));
    }

    /**
     * @param mixed $readtime
     */
    public function setReadtime($readtime)
    {
        $this->readtime = $readtime;
    }

    /**
     * @param mixed $core
     */
    public function setCore($core)
    {
        $this->core = $core;
    }


    public function insert($db)
    {

        $message = $db->prepare('
        UPDATE
             messages
        SET
             id            =  :id,
             sender        =  :sender,
             first_reader  =  :reader,
             type          =  :ttype,
             message       =  :message,
             lat           =  :lat,
             `long`          =  :long,
             timestamp     =  :ttimestamp,
             unread        =  :unread,
             readtime      =  CURRENT_TIMESTAMP

         WHERE id = :id');

        $message->bindParam(':id',      $this->getId(), PDO::PARAM_INT);
        $message->bindParam(':sender',  $this->getSender(), PDO::PARAM_INT);
        $message->bindParam(':reader',  $this->reader, PDO::PARAM_INT);
        $message->bindParam(':ttype',    $this->typeid, PDO::PARAM_INT);
        $message->bindParam(':message', $this->getMessage(), PDO::PARAM_STR);
        $message->bindParam(':lat',     $this->lat);
        $message->bindParam(':long',    $this->long);
        $message->bindValue(':ttimestamp', date('Y-m-d G:i:s', strtotime($this->timestamp)));
        $message->bindParam(':unread' , $this->getRead(), PDO::PARAM_BOOL);

        $message->execute();

    }

    /**
     * @return mixed
     */
    public function getFirstReader()
    {
        return $this->first_reader;
    }

}