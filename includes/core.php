<?php
/*  Core.php - Core file included by all other scripts
 *
 *  Provides functionality for everything that is needed in the application
 *
 *  Operations Control Room App
 *  Miami - Smart Cities Hackathon @ FIU 03/06/15 - 03/08/15
 */



include_once('./includes/config.php');
include_once('./includes/handler.php');
$core = new Core();

class Core {
    private $config;
    private $db;
    private $dateTime;
    private $errors = "";
    private $file;

    function __construct(){
        date_default_timezone_set("EDT");
        $this->file = "core.php";
        $this->writeDebug("Initializing <b>core</b>", $this->file);

        $this->config = new Config($this);
        $this->connectDatabase($this->config->getDBConfig());


        $this->writeDebug("Starting to handle files", $this->file);
        $this->handler = new Handler($this);

        $this->writeDebug("Starting to set up date time", $this->file);
       // $this->dateTime = new DateTime("now", new DateTimeZone($this->config->getTimeZone()));

        $this->writeDebug("Core set up successfully", $this->file);
    }

    public function updateCounter(){
        $tmpFile = fopen('counter.json', "w") or die('error opening temporary files');

        $lastUpdate = '{ "time":' . microtime(true)  .' }';

        fwrite($tmpFile, $lastUpdate);
        fclose($tmpFile);

        $tmpFile = null;

    }

    public function getDateTime(){
        return $this->dateTime;
    }
    public function error($error, $errorTitle = "Error!"){

        $this->errors .= '
        <div class="modal fade" id="errorModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">'.$errorTitle.'</h4>
                    </div>
                    <div class="modal-body">
                        <p>'.$error.'</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" ><a href="?page=messages">Close</a></button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        ';



    }

    public function handle($page, $mobile = false){
        $this->writeDebug("Attempting to handle $page with mobile ($mobile)");
        $this->handler->handle($page, $mobile);
        echo $this->handler->output() . $this->errors;
    }

    public function login($barcodeNumber, $userPin){
        //TODO: Implement login with barcode and pin

    }

    public function logout(){
        //TODO: Implement logout function, destroy sessions
    }

    public function isLoggedIn(){
        //TODO: Implement session management login / logout functionality
        return true;
    }


    public static function writeDebug($str, $file = "DEBUG"){
        if(Config::isDebugging()) {
            echo "<span style=\"font-size: small; \"><b>$file : </b> $str <br /></span>";
        }

    }

    public function getDB(){
        return $this->db;

    }

    /**
     * Prepare and execute a query on the database
     * @param $query MySQL query to be executed
     */
    public function execute($query){
        if($this->db != null){
            $query = $this->db->prepare($query);
            $query->execute();

        }
        return $query;

    }
    private function connectDatabase($dbConfig){
        $dbName = $dbConfig['name'];
        $dbUser = $dbConfig['username'];
        $dbPass = $dbConfig['password'];
        $dbHost = $dbConfig['host'];
        $dbPort = $dbConfig['port'];

        $this->writeDebug("Connecting to database $dbName with username $dbUser", $this->file);
        try{
            $this->db = new PDO("mysql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPass);
        } catch(Exception $e) {
            die("Unable to establish database link!");
        }

    }


}


