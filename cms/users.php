<?php

class user 
{

//Initialising event Parameters
public $user_id = null;
public $username = null;
public $email = null;
public $password = null;
public $firstName = null; 
public $lastName = null; 
public $nickname = null; 
public $dob = null;
public $phoneNo = null;
public $postcode = null; 
public $address = null; 
public $surfAbility = null;
public $notes = null; 
public $admin_permission = null;
public $membership_permission = null;


//adding each parameter to an array containing the data 
public function __construct( $data=array() ){
    if ( isset( $data['user_id'] ) ) $this->user_id = (int) $data['user_id'];
    if ( isset( $data['username'] ) ) $this->username = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['username'] );
    if ( isset( $data['password'] ) ) $this->password =  $data['password'] ;
    if ( isset( $data['email'] ) ) $this->email =  $data['email'] ;
    if ( isset( $data['firstName'] ) ) $this->firstName =  $data['firstName'] ;
    if ( isset( $data['lastName'] ) ) $this->lastName =  $data['lastName'] ;
    if ( isset( $data['nickname'] ) ) $this->nickname =  $data['nickname'] ;
    if ( isset( $data['dob'] ) ) $this->dob =  $data['dob'] ;
    if ( isset( $data['phoneNo'] ) ) $this->phoneNo =  $data['phoneNo'] ;
    if ( isset( $data['postcode'] ) ) $this->postcode =  $data['postcode'] ;
    if ( isset( $data['address'] ) ) $this->address =  $data['address'] ;
    if ( isset( $data['surfAbility'] ) ) $this->surfAbility =  $data['surfAbility'] ;
    if ( isset( $data['notes'] ) ) $this->notes =  $data['notes'] ;
    if ( isset( $data['admin'] ) ) $this->admin_permission =  (int) $data['admin'];
    if ( isset( $data['membership'] ) ) $this->membership_permission =  (int) $data['membership'];
}
//Storing Form data in an object 
public function storeFormValues ( $params ) {
    // Store all the parameters
    $this->__construct( $params );
  }

//Returns an user Object matching a given user ID
public static function getById( $id ) {
    $db_path = '../db/eventsDB.db';
    $conn = new PDO( 'sqlite:'.$db_path );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM user WHERE user_id = :id";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":id", $id, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch(PDO::FETCH_ASSOC);
    $conn = null;
    if ( $row ) return new User( $row );
}
//Returns Multiple user Objects Ordered by date added 
public static function getList($numRows=10000) {
    $db_path = '../db/eventsDB.db';
    $conn = new PDO( 'sqlite:'.$db_path );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM user
            ORDER BY user_id DESC LIMIT :numRows";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    while ( $row = $st->fetch(PDO::FETCH_ASSOC) ) {
      $user = new User( $row );
      $list[] = $user;
    }

    // Now get the total number of users that matched the criteria
    return (  $list );

  }
  public function update() {
    
    // Does the Code object have an ID?
    if ( is_null( $this->user_id ) ) trigger_error ( "Event::update(): Attempt to update an user object that does not have its ID property set.", E_USER_ERROR );
       
    // Update the User
        $db_path = '../db/eventsDB.db';
        $conn = new PDO( 'sqlite:'.$db_path );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE user SET membership = :membership_permission, admin = :admin_permission, notes = :notes WHERE user_id = :id";
        $st = $conn->prepare ( $sql );
        $st->bindValue( ":membership_permission", $this->membership_permission, PDO::PARAM_STR );
        $st->bindValue( ":admin_permission", $this->admin_permission, PDO::PARAM_STR );
        $st->bindValue( ":notes", $this->notes, PDO::PARAM_STR );
        $st->bindValue( ":id", $this->user_id, PDO::PARAM_INT );
        $st->execute();
        $this->user_id = $conn->lastInsertId();
        $conn = null;
      }

  public function insert(){
    // Insert the code
        $db_path = '../db/eventsDB.db';
        $conn = new PDO( 'sqlite:'.$db_path );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO user (username, email, password, firstName, lastName, nickname, dob, phoneNo, postcode, address, surfAbility, notes, admin, membership) VALUES (:username, :email, :password, :firstName, :lastName, :nickname, :dob, :phoneNo, :postcode, :address, :surfAbility, :notes, :admin, :membership)";
        $st = $conn->prepare ( $sql );
        $st->bindValue( ":username", $this->username, PDO::PARAM_STR );
        $st->bindValue( ":email", $this->email, PDO::PARAM_STR );
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        $st->bindValue( ":password", $hashedPassword, PDO::PARAM_STR );
        $st->bindValue( ":firstName", $this->firstName, PDO::PARAM_STR );
        $st->bindValue( ":lastName", $this->lastName, PDO::PARAM_STR );
        $st->bindValue( ":nickname", $this->nickname, PDO::PARAM_STR );
        $st->bindValue( ":dob", $this->dob, PDO::PARAM_STR );
        $st->bindValue( ":phoneNo", $this->phoneNo, PDO::PARAM_STR );
        $st->bindValue( ":postcode", $this->postcode, PDO::PARAM_STR );
        $st->bindValue( ":address", $this->address, PDO::PARAM_STR );
        $st->bindValue( ":surfAbility", $this->surfAbility, PDO::PARAM_STR );
        $st->bindValue( ":notes", $this->notes, PDO::PARAM_STR );
        $st->bindValue( ":admin", 0, PDO::PARAM_STR );
        $st->bindValue( ":membership", 0, PDO::PARAM_STR );


        $st->execute();
        $this->user_id = $conn->lastInsertId();
        $conn = null;
    }
    
    //Delete Event 
    public function delete() {
        // Does the Dicount object have an ID?
        if ( is_null( $this->user_id ) ) trigger_error ( "Event::delete(): Attempt to delete an user object that does not have its ID property set.", E_USER_ERROR );
        //Deletes Dicount from database
        $db_path = '../db/eventsDB.db';
        $conn = new PDO( 'sqlite:'.$db_path );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM user WHERE user_id = :id ";
        $st = $conn->prepare ( $sql );
        $st->bindValue( ":id", $this->user_id, PDO::PARAM_INT );
        $st->execute();
        $conn = null;
    }
  }