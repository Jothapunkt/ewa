<?php	// UTF-8 marker äöüÄÖÜß€
/**
 * Class Thankyou for the exercises of the EWA lecture
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
class Thankyou extends Page
{
    // to do: declare reference variables for members 
    // representing substructures/blocks
    protected $_angebote = array();
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
        // to do: instantiate members representing substructures/blocks
        
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
        $this->generatePageHeader('Ihre Bestellung');
        echo <<<EOT
<div class="row">
<div class="col-sm-4"></div>
<div class="col-sm-4">    
EOT;
        if ($this->_success) {
            echo <<<EOT
<div class="card pizza-card">
    <img class="img-card-top img-fluid pizza-image" src="images/pizzabaecker.png" alt="Pizzabäcker">
    <div class="card-body">
        <h5 class="card-title">Vielen Dank!</h5>
        <p>Wir haben Ihre Bestellung erhalten und bearbeiten sie!</p>
        <a class="form-control btn btn-success mb-2" href="kunde.php">Status der Bestellung verfolgen</a>
        <a class="form-control btn btn-success" href="bestellung.php">Neue Bestellung</a>
    </div>
</div>
EOT;
        } else {
            echo <<<EOT
<div class="card pizza-card">
    <div class="card-body">
        <h5 class="card-title">Ohje... Ein Fehler ist aufgetreten</h5>
        <p>{$this->_message}</p>
        <a class="form-control btn btn-success" href="bestellung.php">Erneut versuchen</a>
    </div>
</div>
EOT;
        }

    echo <<<EOT
</div>
<div class="col-sm-4"></div>
</div>
EOT;
        
        $this->generatePageFooter();
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
        
        session_start();
        
        if ($this->_database->connect_error) {
            die("Database Connection failed: " . $conn->connect_error);
        } 
        
        $this->_database->query("SET NAMES utf8"); //Set database connection to UTF-8 (Necessary!)
        
        if (!isset($_POST["address"]) || !isset($_POST["order"])) {
            $this->_success = false;
            $this->_message = "Ein Teil Ihrer Bestellung wurde nicht übermittelt. Das tut uns Leid. Bitte versuchen Sie es noch einmal!";
            return;
        }
        
        $addr = $this->_database->real_escape_string(htmlspecialchars($_POST["address"]));
        $orderItems = explode(",", $_POST["order"]);
        
        
        $sql = "INSERT INTO Bestellung (`BestellungID`, `Adresse`) VALUES (NULL, '$addr');";
        //echo $sql;
        if ($this->_database->query($sql)) {    
            $last_order = $this->_database->insert_id;
            //echo $last_order;
            
            $_SESSION["last_order"] = $last_order;
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($this->_database);
        }
        
        $itemids = array();
        
        for($i = 0; $i < count($orderItems); $i++) {
            $item = $this->_database->real_escape_string(htmlspecialchars($orderItems[$i]));
            
            $sql = "SELECT PizzaNummer FROM angebot WHERE PizzaName='$item';";#
            //echo $sql;
            
            if ($result = $this->_database->query($sql)) {
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $itemid = $row["PizzaNummer"];
                    //echo $itemid;
                    $itemids[count($itemids)] = $itemid;
                } else {
                    $this->_success = false;
                    $this->_message = "Eine Pizza in Ihrer Bestellung, 'Pizza " . htmlspecialchars($orderItems[$i]) . "' ist leider nicht verfügbar. Das tut uns Leid. Bitte versuchen Sie es noch einmal!";
                    return;
                }
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($this->_database);
            }
        }
        
        for ($i = 0; $i < count($itemids); $i++) {
            $pizzanummer = $itemids[$i];
            $sql = "INSERT INTO BestelltePizza (fBestellungID, fPizzaNummer, Status) VALUES ($last_order, $pizzanummer, 'bestellt');";
            //echo $sql;
            if (!$this->_database->query($sql)) {
                $this->_success = false;
                $this->_message = "Bei der Bearbeitung Ihrer Bestellung ist ein Fehler aufgetreten: '" . mysqli_error($this->_database) . "'. Das tut uns Leid. Bitte versuchen Sie es noch einmal!";
            }
        }
        
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
            $page = new Thankyou();
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
Thankyou::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >