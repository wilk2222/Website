<div id="adminHeader">
    <p>You are logged in as <b><?php echo htmlspecialchars( $_SESSION['username']) ?></b>. <a href="login.php?action=logout"?>Log out</a></p>
    </div>

    <h1><?php echo $results['pageTitle']?></h1>

<form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
    <input type="hidden" name="product_id" value="<?php echo $results['product']->product_id ?>"/>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>

    <ul>

    <li>
    <label for="title">Product:</label>
    <input type="text" id="title" name="title" value="<?php echo $results['product']->title; ?>">
    </li>

    <li>
    <label for="description">Description:</label>
    <input type="text" id="description" name="description" value="<?php echo $results['product']->description; ?>">
    </li>

    <li>
    <label for="price">Price:</label>
    <input type="number" min="0.00" max="1000000.00" step="0.01" id="price" name="price" value="<?php echo $results['product']->price; ?>">
    </li>
  
    </ul>

    <div class="buttons">
          <input type="submit" name="saveChanges" value="Save Changes" />
          <input type="submit" formnovalidate name="cancel" value="Cancel" />
    </div>
    </form>

<?php if ( $results['product']->product_id ) { ?>
      <p><a href="admin.php?action=deleteProduct&amp;product_id=<?php echo $results['product']->product_id ?>" onclick="return confirm('Delete This Product?')">Delete This Product</a></p>
<?php } ?>

