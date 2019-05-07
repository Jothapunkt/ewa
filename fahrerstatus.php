<?php	// UTF-8 marker äöüÄÖÜß€
/**
 * Class Fahrerstatus for the exercises of the EWA lecture
 * Demonstrates use of PHP including class and OO.
 * Implements Zend coding standards.
 * Generate documentation with Doxygen or phpdoc
 * 
 * PHP Version 5
 *
 * @category File
 * @package  Pizzaservice
 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de> 
 * @author   Ralf Hahn, <ralf.hahn@h-da.de> 
 * @license  http://www.h-da.de  none 
 * @Release  1.2 
 * @link     http://www.fbi.h-da.de 
 */

require_once './Page.php';

/**
 * This is a template for top level classes, which represent 
 * a complete web page and which are called directly by the user.
 * Usually there will only be a single instance of such a class. 
 * The name of the template is supposed
 * to be replaced by the name of the specific HTML page e.g. baker.
 * The order of methods might correspond to the order of thinking 
 * during implementation.
 
 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de> 
 * @author   Ralf Hahn, <ralf.hahn@h-da.de> 
 */
 
class Fahrerstatus extends Page
{
    // to do: declare reference variables for members 
    // representing substructures/blocks
    
    protected $_bestellungen = array();
    protected $_success = true;
    protected $_message = "";
    
    /**
     * Instantiates members (to be defined above).   
     * Calls the constructor of the parent i.e. page class.
     * So the database connection is established.
     *
     * @return none
     */
    protected function __construct() 
    {
        parent::__construct();
    }
    
    /**
     * Cleans up what ever is needed.   
     * Calls the destructor of the parent i.e. page class.
     * So the database connection is closed.
     *
     * @return none
     */
    protected function __destruct() 
    {
        parent::__destruct();
    }

    /**
     * Fetch all data that is necessary for later output.
     * Data is stored in an easily accessible way e.g. as associative array.
     *
     * @return none
     */
    protected function getViewData()
    {
        header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
        header("Expires: Sat, 01 Jul 2000 06:00:00 GMT"); // Datum in der Vergangenheit
        header("Cache-Control: post-check=0, pre-check=0", false); // fuer IE
        header("Pragma: no-cache");
        session_cache_limiter('nocache'); // VOR session_start()!
        session_cache_expire(0);
        
        session_start();

        $this->_database->query("SET NAMES utf8"); //Set database connection to UTF-8 (Necessary!)

        $sql = "SELECT * FROM `bestellung` WHERE NOT EXISTS (SELECT * FROM bestelltepizza WHERE bestelltepizza.fBestellungID=bestellung.BestellungID AND (bestelltepizza.Status='bestellt' OR bestelltepizza.Status='Im Ofen'));";
        
        if ($result = $this->_database->query($sql)) {
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $bestellung = array();
                    $bestellung["BestellungID"] = $row["BestellungID"];
                    $bestellid = $row["BestellungID"];
                    $bestellung["Adresse"] = htmlspecialchars_decode($row["Adresse"]);
                    
                    $pizzen = array();
                    $subsql = "SELECT PizzaID, PizzaName, Status FROM bestelltepizza inner join angebot on bestelltepizza.fPizzaNummer=angebot.PizzaNummer WHERE fBestellungID=$bestellid;";
                    if (!$subresult = $this->_database->query($subsql)) {
                        echo mysqli_error($this->_database);
                    }
                    while ($subrow = $subresult->fetch_assoc()) {
                        $pizza = array();
                        $pizza["PizzaID"] = $subrow["PizzaID"];
                        $pizza["PizzaName"] = $subrow["PizzaName"];
                        $pizza["Status"] = $subrow["Status"];
                        $pizzen[count($pizzen)] = $pizza;
                    }
                    
                    $bestellung["Pizzen"] = $pizzen;
                    
                    $this->_bestellungen[count($this->_bestellungen)] = $bestellung;
                }
            }
        }
        
    }
    
    /**
     * First the necessary data is fetched and then the HTML is 
     * assembled for output. i.e. the header is generated, the content
     * of the page ("view") is inserted and -if avaialable- the content of 
     * all views contained is generated.
     * Finally the footer is added.
     *
     * @return none
     */
    protected function generateView() 
    {
        $this->getViewData();
        //$this->generatePageHeader('Bestellungsverfolgung');
        // to do: call generateView() for all members
        echo (json_encode($this->_bestellungen));
        // to do: output view of this page
        //$this->generatePageFooter();
    }
    
    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If this page is supposed to do something with submitted
     * data do it here. 
     * If the page contains blocks, delegate processing of the 
	 * respective subsets of data to them.
     *
     * @return none 
     */
    protected function processReceivedData() 
    {
        parent::processReceivedData();
        // to do: call processReceivedData() for all members
    }

    /**
     * This main-function has the only purpose to create an instance 
     * of the class and to get all the things going.
     * I.e. the operations of the class are called to produce
     * the output of the HTML-file.
     * The name "main" is no keyword for php. It is just used to
     * indicate that function as the central starting point.
     * To make it simpler this is a static function. That is you can simply
     * call it without first creating an instance of the class.
     *
     * @return none 
     */    
    public static function main() 
    {
        try {
            $page = new Fahrerstatus();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

// This call is starting the creation of the page. 
// That is input is processed and output is created.
Fahrerstatus::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >