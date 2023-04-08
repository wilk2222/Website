
<div id="adminHeader">
<p>You are logged in as <b><?php echo htmlspecialchars( $_SESSION['username']) ?></b>. <a href="admin.php?action=logout"?>Log out</a></p>
</div>

<h1>All Shop Items</h1>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>


<?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>

      <table>
        <tr>
          <th>Item Name</th>
          <th>Item Description</th>
        </tr>

<?php foreach ( $results['product'] as $product ) { ?>

        <tr onclick="location='admin.php?action=editProduct&amp;product_id=<?php echo $product->product_id?>'">
          <td><?php echo $product->title?></td>
          <td>
            <?php echo $product->description?>
          </td>
        </tr>

<?php } ?>

      </table>


      <p><a href="admin.php?action=addProduct">Add a New Product</a></p>
      <p><a href="admin.php">Return</a></p>

