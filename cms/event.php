<?php

//Class to handle events 

class Event 
{

//Initialising event Parameters
public $event_id = null;
public $event_title = null;
public $event_location = null;
public $event_date = null;
public $event_summary = null;
public $post_date = null;
public $post_display = null;
public $image_extension = "";

//adding each parameter to an array containing the data 
public function __construct( $data=array() ){
    if ( isset( $data['event_id'] ) ) $this->event_id = (int) $data['event_id'];
    if ( isset( $data['event_title'] ) ) $this->event_title = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['event_title'] );
    if ( isset( $data['event_location'] ) ) $this->event_location =  $data['event_location'] ;
    if ( isset( $data['event_date'] ) ) $this->event_date =  $data['event_date'];
    if ( isset( $data['event_summary'] ) ) $this->event_summary =  $data['event_summary'];
    if ( isset( $data['post_date'] ) ) $this->post_date =  $data['post_date'];
    if ( isset( $data['post_display'] ) ) $this->post_display = (int) $data['post_display'];
    if ( isset( $data['image_extension'] ) ) $this->image_extension = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['image_extension'] );
}

//Storing Form data in an object 
public function storeFormValues ( $params ) {
    // Store all the parameters
    $this->__construct( $params );
  }


//Returning Event Data from the Database
//Returns an Event Object matching a given event ID
public static function getById( $id ) {
    $db_path = '../db/eventsDB.db';
    $conn = new PDO( 'sqlite:'.$db_path );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM events WHERE event_id = :id";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":id", $id, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch(PDO::FETCH_ASSOC);
    $conn = null;
    if ( $row ) return new Event( $row );
}
//Returns Multiple Event Objects Ordered by Date
public static function getList($numRows=10000) {
    $db_path = '../db/eventsDB.db';
    $conn = new PDO( 'sqlite:'.$db_path );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM events
            ORDER BY post_date DESC LIMIT :numRows";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    while ( $row = $st->fetch(PDO::FETCH_ASSOC) ) {
      $event = new Event( $row );
      $list[] = $event;
    }

    // Now get the total number of events that matched the criteria
    return (  $list );
  }

//Dealing with images 

//Stores Images from edit form 
  public function storeUploadedImage( $image ) {

    if ( $image['error'] == UPLOAD_ERR_OK )
    {
      // Does the Event object have an ID?
      if ( is_null( $this->event_id ) ) trigger_error( "Event::storeUploadedImage(): Attempt to upload an image for an Event object that does not have its ID property set.", E_USER_ERROR );

      // Delete any previous image(s) for this Event
      $this->deleteImages();

      // Get and store the image filename extension
      $this->image_extension = strtolower( strrchr( $image['name'], '.' ) );

      // Store the image

      $tempFilename = trim( $image['tmp_name'] ); 

      if ( is_uploaded_file ( $tempFilename ) ) {
        if ( !( move_uploaded_file( $tempFilename, $this->getImagePath() ) ) ) trigger_error( "Event::storeUploadedImage(): Couldn't move uploaded file.", E_USER_ERROR );
        if ( !( chmod( $this->getImagePath(), 0666 ) ) ) trigger_error( "Event::storeUploadedImage(): Couldn't set permissions on uploaded file.", E_USER_ERROR );
      }

      // Get the image size and type
      $attrs = getimagesize ( $this->getImagePath() );
      $imageWidth = $attrs[0];
      $imageHeight = $attrs[1];
      $imageType = $attrs[2];

      // Load the image into memory
      switch ( $imageType ) {
        case IMAGETYPE_GIF:
          $imageResource = imagecreatefromgif ( $this->getImagePath() );
          break;
        case IMAGETYPE_JPEG:
          $imageResource = imagecreatefromjpeg ( $this->getImagePath() );
          break;
        case IMAGETYPE_PNG:
          $imageResource = imagecreatefrompng ( $this->getImagePath() );
          break;
        default:
          trigger_error ( "Event::storeUploadedImage(): Unhandled or unknown image type ($imageType)", E_USER_ERROR );
      }

      // Copy and resize the image to create the thumbnail
      $thumbHeight = intval ( $imageHeight / $imageWidth * EVENT_THUMB_WIDTH );
      $thumbResource = imagecreatetruecolor ( EVENT_THUMB_WIDTH, $thumbHeight );
      imagecopyresampled( $thumbResource, $imageResource, 0, 0, 0, 0, EVENT_THUMB_WIDTH, $thumbHeight, $imageWidth, $imageHeight );

      // Save the thumbnail
      switch ( $imageType ) {
        case IMAGETYPE_GIF:
          imagegif ( $thumbResource, $this->getImagePath( IMG_TYPE_THUMB ) );
          break;
        case IMAGETYPE_JPEG:
          imagejpeg ( $thumbResource, $this->getImagePath( IMG_TYPE_THUMB ), JPEG_QUALITY );
          break;
        case IMAGETYPE_PNG:
          imagepng ( $thumbResource, $this->getImagePath( IMG_TYPE_THUMB ) );
          break;
        default:
          trigger_error ( "Evemt::storeUploadedImage(): Unhandled or unknown image type ($imageType)", E_USER_ERROR );
      }

      $this->update();
    }
  }


  /**
  * Deletes any images and/or thumbnails associated with the Event
  */

  public function deleteImages() {

    // Delete all fullsize images for this event
    foreach (glob( EVENT_IMAGE_PATH . "/" . IMG_TYPE_FULLSIZE . "/" . $this->id . ".*") as $filename) {
      if ( !unlink( $filename ) ) trigger_error( "Event::deleteImages(): Couldn't delete image file.", E_USER_ERROR );
    }
    
    // Delete all thumbnail images for this event
    foreach (glob( EVENT_IMAGE_PATH . "/" . IMG_TYPE_THUMB . "/" . $this->id . ".*") as $filename) {
      if ( !unlink( $filename ) ) trigger_error( "Event::deleteImages(): Couldn't delete thumbnail file.", E_USER_ERROR );
    }

    // Remove the image filename extension from the object
    $this->image_extension = "";
  }


  /**
  * Returns the relative path to the article's full-size or thumbnail image
  *
  * @param string The type of image path to retrieve (IMG_TYPE_FULLSIZE or IMG_TYPE_THUMB). Defaults to IMG_TYPE_FULLSIZE.
  * @return string|false The image's path, or false if an image hasn't been uploaded
  */

  public function getImagePath( $type=IMG_TYPE_FULLSIZE ) {
    return ( $this->event_id && $this->image_extension ) ? ( EVENT_IMAGE_PATH . "/$type/" . $this->event_id . $this->image_extension ) : false;
  }



//Inserting Data to the Database

public function insert(){
// Insert the Event
    $current_date = date('Y-m-d');
    $db_path = '../db/eventsDB.db';
    $conn = new PDO( 'sqlite:'.$db_path );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO events (event_title, event_location, event_date, event_summary, post_date, post_display) VALUES (:title, :event_location, :event_date, :summary, :post_date, :post_display)";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":title", $this->event_title, PDO::PARAM_STR );
    $st->bindValue( ":summary", $this->event_summary, PDO::PARAM_STR );
    $st->bindValue( ":event_location", $this->event_location, PDO::PARAM_STR );
    $st->bindValue( ":event_date", $this->event_date, PDO::PARAM_STR );
    $st->bindValue( ":post_date", $current_date, PDO::PARAM_STR );
    $st->bindValue( ":post_display", $this->post_display, PDO::PARAM_STR );
    $st->execute();
    $this->event_id = $conn->lastInsertId();
    $conn = null;
} 

//Update Current Event 

public function update() {

// Does the Event object have an ID?
if ( is_null( $this->event_id ) ) trigger_error ( "Event::update(): Attempt to update an Event object that does not have its ID property set.", E_USER_ERROR );
   
// Update the Event
    $current_date = date('Y-m-d');
    $db_path = '../db/eventsDB.db';
    $conn = new PDO( 'sqlite:'.$db_path );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE events SET event_title = :title, event_location=:event_location, event_date=:event_date, event_summary=:summary, post_date = :post_date, post_display=:post_display WHERE event_id=:id";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":title", $this->event_title, PDO::PARAM_STR );
    $st->bindValue( ":summary", $this->event_summary, PDO::PARAM_STR );
    $st->bindValue( ":event_location", $this->event_location, PDO::PARAM_STR );
    $st->bindValue( ":event_date", $this->event_date, PDO::PARAM_STR );
    $st->bindValue( ":post_date", $current_date, PDO::PARAM_STR );
    $st->bindValue( ":post_display", $this->post_display, PDO::PARAM_STR );
    $st->bindValue( ":id", $this->event_id, PDO::PARAM_INT );
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
  }

//Delete Event 
public function delete() {
    // Does the Event object have an ID?
    if ( is_null( $this->event_id ) ) trigger_error ( "Event::delete(): Attempt to delete an Event object that does not have its ID property set.", E_USER_ERROR );
    //Deletes Event from database
    $db_path = '../db/eventsDB.db';
    $conn = new PDO( 'sqlite:'.$db_path );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DELETE FROM events WHERE event_id = :id ";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":id", $this->event_id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
}
}
