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
    private $messageBoard;
    private $file;

    function __construct(){
        $this->file = "core.php";
        $this->writeDebug("Initializing <b>core</b>", $this->file);

        $this->config = new Config($this);
        $this->connectDatabase($this->config->getDBConfig());

        $this->handler = new Handler($this);

    }

    public function handle($page){
        $this->handler->handle($page);
        echo $this->handler->output();
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
        $this->db = new PDO("mysql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPass); // Create new PDO
        $this->writeDebug("Connected to database!", $this->file);

    }

    private function closeDatabase(){
        $this->db = null;
    }

}


