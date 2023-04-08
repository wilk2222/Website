
      <div id="adminHeader">
        <p>You are logged in as <b><?php echo htmlspecialchars( $_SESSION['username']) ?></b>. <a href="admin.php?action=logout"?>Log out</a></p>
      </div>

      <h1>All Users</h1>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>


<?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>

      <table>
        <tr>
          <th>Username</th>
          <th>Email</th>
        </tr>

<?php foreach ( $results['user'] as $user ) { ?>

        <tr onclick="location='admin.php?action=editUser&amp;user_id=<?php echo $user->user_id?>'">
          <td><?php echo $user->username?></td>
          <td>
            <?php echo $user->email?>
          </td>
        </tr>

<?php } ?>

      </table>

      <p><a href="admin.php">Return</a></p>