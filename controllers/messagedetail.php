<?php
/*  FILENAME - SHORT DESCRIPTION
 *
 *  LONG DESCRIPTION 
 * 
 *  Operations Control Room App
 *  Miami - Smart Cities Hackathon @ FIU 03/06/15 - 03/08/15
 */

include './controllers/PageHandler.php';

class MessageDetail implements PageHandler{
    private $core;
    private $name = 'Details';

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
        $this->page = file_get_contents('./pages/details.html');
        $this->page = str_replace('<!-- HEADER -->', file_get_contents('./pages/header.html'), $this->page);

    }
}