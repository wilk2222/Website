<?php

// Class to handle Reviews
class Review {

    // Initializing Review Parameters
    public $review_id = null;
    public $product_id = null;
    public $user_id = null;
    public $rating = null;
    public $review_text = null;
    public $review_date = null;
    public $sentiment;
    public $compound_score;
    public $needs_review = 0;

    // Adding each parameter to an array containing the data
    public function __construct( $data = array() ) {
        if ( isset( $data['review_id'] ) ) $this->review_id = (int) $data['review_id'];
        if ( isset( $data['product_id'] ) ) $this->product_id = (int) $data['product_id'];
        if ( isset( $data['user_id'] ) ) $this->user_id = (int) $data['user_id'];
        if ( isset( $data['rating'] ) ) $this->rating = (int) $data['rating'];
        if ( isset( $data['review_text'] ) ) $this->review_text = $data['review_text'];
        if ( isset( $data['review_date'] ) ) $this->review_date = $data['review_date'];
        if ( isset( $data['sentiment'] ) ) $this->sentiment = $data['sentiment'];
        if ( isset( $data['compound_score'] ) ) $this->compound_score = (float) $data['compound_score'];
    }

    // Storing Form data in an object
    public function storeFormValues( $params ) {
        // Store all the parameters
        $this->__construct( $params );
    }

    // Inserting Data to the Database
    public function insert() {
        $db_path = '../db/reviewsDB.db';
        $conn = new PDO( 'sqlite:' . $db_path );
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        // Calculate the normalized compound score
        $normalized_compound_score = ($this->compound_score + 1) / 2 * 5;

        // Check if the rating is within 20% of the compound score
        $difference = abs($this->rating - $normalized_compound_score);
        $needs_review = $difference > 1 ? 1 : 0;

        $sql = "INSERT INTO reviews (product_id, user_id, rating, review_text, sentiment, compound_score, needs_review) VALUES (:product_id, :user_id, :rating, :review_text, :sentiment, :compound_score, :needs_review)";
        $st = $conn->prepare( $sql );
        $st->bindValue( ":product_id", $this->product_id, PDO::PARAM_INT );
        $st->bindValue( ":user_id", $this->user_id, PDO::PARAM_INT );
        $st->bindValue( ":rating", $this->rating, PDO::PARAM_INT );
        $st->bindValue( ":review_text", $this->review_text, PDO::PARAM_STR );
        $st->bindValue( ":sentiment", $this->sentiment, PDO::PARAM_STR );
        $st->bindValue( ":compound_score", $this->compound_score, PDO::PARAM_STR );
        $st->bindValue( ":needs_review", $this->needs_review, PDO::PARAM_INT );
        $st->execute();
        $conn = null;
    }

    // Returns Multiple Review Objects for a specific product ordered by review_date
    public static function getReviewsByProductId($product_id) {
        $db_path = '../db/reviewsDB.db';
        $conn = new PDO( 'sqlite:' . $db_path );
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $sql = "SELECT * FROM reviews WHERE product_id = :product_id ORDER BY review_date DESC LIMIT 3";

        $st = $conn->prepare( $sql );
        $st->bindValue( ":product_id", $product_id, PDO::PARAM_INT );
        $st->execute();
        $list = array();

        while ( $row = $st->fetch( PDO::FETCH_ASSOC ) ) {
            $review = new Review( $row );
            $list[] = $review;
        }

        return $list;
    }

    public static function getList() {
        $db_path = '../db/reviewsDB.db';
        $conn = new PDO( 'sqlite:' . $db_path );
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $sql = "SELECT * FROM reviews ORDER BY review_date DESC";

        $st = $conn->query( $sql );
        $list = array();

        while ( $row = $st->fetch( PDO::FETCH_ASSOC ) ) {
            $review = new Review( $row );
            $list[] = $review;
        }

        return $list;
    }

    public static function getAllReviews() {
        $db_path = '../db/reviewsDB.db';
        $conn = new PDO( 'sqlite:' . $db_path );
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $sql = "SELECT * FROM reviews ORDER BY review_date DESC";

        $st = $conn->prepare( $sql );
        $st->execute();
        $list = array();

        while ( $row = $st->fetch( PDO::FETCH_ASSOC ) ) {
            $review = new Review( $row );
            $list[] = $review;
        }

        return $list;
    }

    public static function deleteReview($review_id) {
        $db_path = '../db/reviewsDB.db';
        $conn = new PDO('sqlite:' . $db_path);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM reviews WHERE review_id = :review_id";
        $st = $conn->prepare($sql);
        $st->bindValue(":review_id", $review_id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }

    public static function getApprovedReviewsByProductId($product_id) {
        $db_path = '../db/reviewsDB.db';
        $conn = new PDO('sqlite:' . $db_path);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM reviews WHERE product_id = :product_id AND needs_review = 0";
        $st = $conn->prepare($sql);
        $st->bindValue(":product_id", $product_id, PDO::PARAM_INT);
        $st->execute();
        $rows = $st->fetchAll();

        $reviews = array();
        foreach ($rows as $row) {
            $review = new Review($row);
            $reviews[] = $review;
        }

        $conn = null;
        return $reviews;
    }

    // Update and delete methods can be added similarly to the Product class if needed
}
