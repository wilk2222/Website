

      <div id="adminHeader">
        <p>You are logged in as <b><?php echo htmlspecialchars( $_SESSION['username']) ?></b>. <a href="admin.php?action=logout"?>Log out</a></p>
      </div>

      <h1>All Events</h1>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>


<?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>

      <table>
        <tr>
          <th>Event Date</th>
          <th>Event</th>
        </tr>

<?php foreach ( $results['events'] as $event ) { ?>

        <tr onclick="location='admin.php?action=editEvent&amp;event_id=<?php echo $event->event_id?>'">
          <td><?php echo date($event->event_date)?></td>
          <td>
            <?php echo $event->event_title?>
          </td>
        </tr>

<?php } ?>

      </table>


      <p><a href="admin.php?action=addEvent">Add a New Event</a></p>
      <p><a href="admin.php">Return</a></p>

