<?php
class MessageType{
    public $type;
    public $typedescription;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getTypeDescription()
    {
        return $this->typedescription;
    }
}
class User {
    public $id;
    public $name;
    public $barcodeID;
    public $pin;
    public $type;
    public $lastlogin;
    public $ip;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getLastlogin()
    {
        return $this->lastlogin;
    }

    /**
     * @param mixed $lastlogin
     */
    public function setLastlogin($lastlogin)
    {
        $this->lastlogin = $lastlogin;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

}
class Message
{
    private $core;

    private $sender, $first_reader;
    private $type;
    private $message;
    private $timestamp;
    private $unread;
    private $readtime;

    function __construct()
    {

    }

    function getType()
    {
        if (!$this->type instanceof MessageType)

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

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return mixed
     */
    public function getRead()
    {
        if ($this->unread) {
            $read = "No";
        } else {
            $read = "Yes";
        }

        return $read;
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
        return date('M j Y g:i A', strtotime($this->readtime));
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

}

?>