<?php
/*  pagehandler.php - Provides an interface for custom page handlers
 *
 *  Provides an interface for custom page handlers
 * 
 *  Operations Control Room App
 *  Miami - Smart Cities Hackathon @ FIU 03/06/15 - 03/08/15
 */

interface PageHandler {

    /**
     * @param $core pointer to Core class
     */
    function __construct($core);

    /**
     * @return string Proper name of page being handled
     */
    function pageName();

    /**
     * @return string Full html output page
     */
    function output();

    /**
     * Handle and compile the page
     */
    function handle();
}