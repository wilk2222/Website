<?php
//Class to handle Products
class Product 
{

//Initialising Shop Product Parameters
public $product_id = null;
public $title = null;
public $price = null;
public $description = null;

//adding each parameter to an array containing the data 
public function __construct( $data=array() ){
    if ( isset( $data['product_id'] ) ) $this->product_id = (int) $data['product_id'];
    if ( isset( $data['title'] ) ) $this->title = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['title'] );
    if ( isset( $data['price'] ) ) $this->price =  $data['price'] ;
    if ( isset( $data['description'] ) ) $this->description = $data['description'];
}
//Storing Form data in an object 
public function storeFormValues ( $params ) {
    // Store all the parameters
    $this->__construct( $params );
  }

//Returns a Product Object matching a given product ID
public static function getById( $id ) {
    $db_path = '../db/eventsDB.db';
    $conn = new PDO( 'sqlite:'.$db_path );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM products WHERE product_id = :product_id";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":product_id", $id, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch(PDO::FETCH_ASSOC);
    $conn = null;
    if ( $row ) return new Product( $row );
}
//Returns Multiple Product Objects Ordered by product_id 
public static function getList($numRows=10000) {
    $db_path = '../db/eventsDB.db';
    $conn = new PDO( 'sqlite:'.$db_path );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM products
            ORDER BY product_id DESC LIMIT :numRows";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    while ( $row = $st->fetch(PDO::FETCH_ASSOC) ) {
      $product = new Product( $row );
      $list[] = $product;
    }

    // Now get the total number of discounts that matched the criteria
    return (  $list );
  }

//Inserting Data to the Database

public function insert(){
        $db_path = '../db/eventsDB.db';
        $conn = new PDO( 'sqlite:'.$db_path );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO products (title, price, description) VALUES (:title, :price, :description)";
        $st = $conn->prepare ( $sql );
        $st->bindValue( ":title", $this->title, PDO::PARAM_STR );
        $st->bindValue( ":price", $this->price, PDO::PARAM_STR );
        $st->bindValue( ":description", $this->description, PDO::PARAM_STR );
        $st->execute();
        $conn = null;
    } 
    
    //Update Current Code 
    
    public function update() {
    
    // Does the Code object have an ID?
    if ( is_null( $this->product_id ) ) trigger_error ( "Event::update(): Attempt to update an Discount object that does not have its ID property set.", E_USER_ERROR );
       
    // Update the Code
        $db_path = '../db/eventsDB.db';
        $conn = new PDO( 'sqlite:'.$db_path );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE products SET title = :title, price=:price, description=:description WHERE product_id=:product_id";
        $st = $conn->prepare ( $sql );
        $st->bindValue( ":title", $this->title, PDO::PARAM_STR );
        $st->bindValue( ":price", $this->price, PDO::PARAM_STR );
        $st->bindValue( ":description", $this->description, PDO::PARAM_STR );
        $st->bindValue( ":product_id", $this->product_id, PDO::PARAM_INT );
        $st->execute();
        $this->product_id = $conn->lastInsertId();
        $conn = null;
      }
    
    //Delete Event 
    public function delete() {
        // Does the Product object have an ID?
        if ( is_null( $this->product_id ) ) trigger_error ( "Event::delete(): Attempt to delete an Discount object that does not have its ID property set.", E_USER_ERROR );
        //Deletes Product from database
        $db_path = '../db/eventsDB.db';
        $conn = new PDO( 'sqlite:'.$db_path );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM products WHERE product_id = :product_id ";
        $st = $conn->prepare ( $sql );
        $st->bindValue( ":product_id", $this->product_id, PDO::PARAM_INT );
        $st->execute();
        $conn = null;
    }
    }
