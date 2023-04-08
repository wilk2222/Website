
    <div id="adminHeader">
    <p>You are logged in as <b><?php echo htmlspecialchars( $_SESSION['username']) ?></b>. <a href="login.php?action=logout"?>Log out</a></p>
    </div>

    <h1><?php echo $results['pageTitle']?></h1>

<form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
    <input type="hidden" name="event_id" value="<?php echo $results['event']->event_id ?>"/>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>

    <ul>

    <li>
    <label for="event_title">Event Title:</label>
    <input type="text" id="form_event_title" name="event_title" value="<?php echo $results['event']->event_title; ?>">
    </li>

    <li>
    <label for="event_location">Event Location:</label>
    <input type="text" id="form_event_location" name="event_location" value="<?php echo $results['event']->event_location; ?>">
    </li>

    <li>
    <label for="event_date">Event Date:</label>
    <input type="datetime-local" id="form_event_date" name="event_date" value="<?php echo date('Y-m-d\TH:i:s', strtotime($results['event']->event_date)); ?>">
    </li>

    <li>
    <label for="event_summary">Event Summary:</label>
    <textarea id="event_summary" name="event_summary"><?php echo $results['event']->event_summary; ?></textarea>
    </li>
    
    <li>
    <label for="post_date">Post Date:</label>
    <input type="date" id="form_post_date" name="post_date" value="<?php echo date('Y-m-d', strtotime($results['event']->post_date)); ?>"readonly>
    </li>

    <li>
    <label for="post_display">Display Post:</label>
    <select id="form_post_display" name="post_display">
    <option value="1" <?php echo ($results['event']->post_display == 1) ? 'selected' : ''; ?>>Yes</option>
    <option value="0" <?php echo ($results['event']->post_display == 0) ? 'selected' : ''; ?>>No</option>
    </select>
    </li>
  
    </ul>

    <div class="buttons">
          <input type="submit" name="saveChanges" value="Save Changes" />
          <input type="submit" formnovalidate name="cancel" value="Cancel" />
    </div>
    </form>

<?php if ( $results['event']->event_id ) { ?>
      <p><a href="admin.php?action=deleteEvent&amp;event_id=<?php echo $results['event']->event_id ?>" onclick="return confirm('Delete This Event?')">Delete This Event</a></p>
<?php } ?>