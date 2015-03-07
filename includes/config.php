<?php
/*  config.php - Configuration file included by core.php
 *
 *  Provides functionality for getting configuration information such as database information
 *
 *  Operations Control Room App
 *  Miami - Smart Cities Hackathon @ FIU 03/06/15 - 03/08/15
 */


class Config {
    private $core;
    private $cfg;
    private $file;

    function __construct($core){
        include "conf.php";
        $this->cfg = $config;

        $this->core = $core;
        $this->file = "config.php";
        $this->core->writeDebug('Initializing <b>configuration</b>', $this->file);

    }

    public function getDBConfig(){
        if (!empty($this->cfg)) {
            return $this->cfg['database'];
        } else {
            throw new ErrorException("Config not found");
        }
    }

    public function isDebugging(){
        include "conf.php";
        return $config['debug'];
    }
}