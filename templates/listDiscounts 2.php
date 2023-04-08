

<div id="adminHeader">
<p>You are logged in as <b><?php echo htmlspecialchars( $_SESSION['username']) ?></b>. <a href="admin.php?action=logout"?>Log out</a></p>
</div>

<h1>All Discount Codes</h1>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>


<?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>

      <table>
        <tr>
          <th>Company</th>
          <th>Code</th>
        </tr>

<?php foreach ( $results['discount'] as $discount ) { ?>

        <tr onclick="location='admin.php?action=editCode&amp;code_id=<?php echo $discount->code_id?>'">
          <td><?php echo $discount->code_company?></td>
          <td>
            <?php echo $discount->code_content?>
          </td>
        </tr>

<?php } ?>

      </table>


      <p><a href="admin.php?action=addCode">Add a New Code</a></p>
      <p><a href="admin.php">Return</a></p>

