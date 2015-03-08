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
