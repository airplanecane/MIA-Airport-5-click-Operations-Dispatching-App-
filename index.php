<?php
/*  Index.php - Main View
 *
 *  Provides login to panel with no other functionality until logged in
 *  When logged in provides access to the interface to report.
 *
 *  Operations Control Room App
 *  Miami - Smart Cities Hackathon @ FIU 03/06/15 - 03/08/15
 */


session_start();

include_once 'includes/core.php';

$core->writeDebug("Initialization Complete", "index.php");

if(isset($_GET['error'])){
    $core->error($_GET['error']);
}


switch($_GET['page']){

    case 'messages':
        $core->handle('messageboard');
        break;
    case 'dispatch':
        $core->handle('dispatch');
        break;

    default:
        $core->handle('index');
        break;
}

