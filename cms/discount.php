<?php
//Class to handle discount codes
class Discount 
{

//Initialising event Parameters
public $code_id = null;
public $code_company = null;
public $code_content = null;
public $code_display = null;

//adding each parameter to an array containing the data 
public function __construct( $data=array() ){
    if ( isset( $data['code_id'] ) ) $this->code_id = (int) $data['code_id'];
    if ( isset( $data['code_company'] ) ) $this->code_company = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['code_company'] );
    if ( isset( $data['code_content'] ) ) $this->code_content =  $data['code_content'] ;
    if ( isset( $data['code_display'] ) ) $this->code_display =  (int) $data['code_display'];
}
//Storing Form data in an object 
public function storeFormValues ( $params ) {
    // Store all the parameters
    $this->__construct( $params );
  }

//Returns an Discount Object matching a given discount ID
public static function getById( $id ) {
    $db_path = '../db/eventsDB.db';
    $conn = new PDO( 'sqlite:'.$db_path );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM discounts WHERE code_id = :id";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":id", $id, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch(PDO::FETCH_ASSOC);
    $conn = null;
    if ( $row ) return new Discount( $row );
}
//Returns Multiple Discount Objects Ordered by date added 
public static function getList($numRows=10000) {
    $db_path = '../db/eventsDB.db';
    $conn = new PDO( 'sqlite:'.$db_path );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM discounts
            ORDER BY code_id DESC LIMIT :numRows";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    while ( $row = $st->fetch(PDO::FETCH_ASSOC) ) {
      $discount = new Discount( $row );
      $list[] = $discount;
    }

    // Now get the total number of discounts that matched the criteria
    return (  $list );
  }

//Inserting Data to the Database

public function insert(){
    // Insert the code
        $current_date = date('Y-m-d');
        $db_path = '../db/eventsDB.db';
        $conn = new PDO( 'sqlite:'.$db_path );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO discounts (code_company, code_content, code_display) VALUES (:company, :content, :code_display)";
        $st = $conn->prepare ( $sql );
        $st->bindValue( ":company", $this->code_company, PDO::PARAM_STR );
        $st->bindValue( ":content", $this->code_content, PDO::PARAM_STR );
        $st->bindValue( ":code_display", $this->code_display, PDO::PARAM_STR );
        $st->execute();
        $this->code_id = $conn->lastInsertId();
        $conn = null;
    } 
    
    //Update Current Code 
    
    public function update() {
    
    // Does the Code object have an ID?
    if ( is_null( $this->code_id ) ) trigger_error ( "Event::update(): Attempt to update an Discount object that does not have its ID property set.", E_USER_ERROR );
       
    // Update the Code
        $current_date = date('Y-m-d');
        $db_path = '../db/eventsDB.db';
        $conn = new PDO( 'sqlite:'.$db_path );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE discounts SET code_company = :company, code_content=:content, code_display=:code_display WHERE code_id=:id";
        $st = $conn->prepare ( $sql );
        $st->bindValue( ":company", $this->code_company, PDO::PARAM_STR );
        $st->bindValue( ":content", $this->code_content, PDO::PARAM_STR );
        $st->bindValue( ":code_display", $this->code_display, PDO::PARAM_STR );
        $st->bindValue( ":id", $this->code_id, PDO::PARAM_INT );
        $st->execute();
        $this->code_id = $conn->lastInsertId();
        $conn = null;
      }
    
    //Delete Event 
    public function delete() {
        // Does the Dicount object have an ID?
        if ( is_null( $this->code_id ) ) trigger_error ( "Event::delete(): Attempt to delete an Discount object that does not have its ID property set.", E_USER_ERROR );
        //Deletes Dicount from database
        $db_path = '../db/eventsDB.db';
        $conn = new PDO( 'sqlite:'.$db_path );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM discounts WHERE code_id = :id ";
        $st = $conn->prepare ( $sql );
        $st->bindValue( ":id", $this->code_id, PDO::PARAM_INT );
        $st->execute();
        $conn = null;
    }
    }
