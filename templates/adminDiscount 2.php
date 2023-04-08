
    <div id="adminHeader">
    <p>You are logged in as <b><?php echo htmlspecialchars( $_SESSION['username']) ?></b>. <a href="login.php?action=logout"?>Log out</a></p>
    </div>

    <h1><?php echo $results['pageTitle']?></h1>

<form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
    <input type="hidden" name="code_id" value="<?php echo $results['discount']->code_id ?>"/>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>

    <ul>

    <li>
    <label for="code_company">Company:</label>
    <input type="text" id="code_company" name="code_company" value="<?php echo $results['discount']->code_company; ?>">
    </li>

    <li>
    <label for="code_content">Code:</label>
    <input type="text" id="code_content" name="code_content" value="<?php echo $results['discount']->code_content; ?>">
    </li>

    <li>
    <label for="code_display">Display Code:</label>
    <select id="form_code_display" name="code_display">
    <option value="1" <?php echo ($results['discount']->code_display == 1) ? 'selected' : ''; ?>>Yes</option>
    <option value="0" <?php echo ($results['discount']->code_display == 0) ? 'selected' : ''; ?>>No</option>
    </select>
    </li>
  
    </ul>

    <div class="buttons">
          <input type="submit" name="saveChanges" value="Save Changes" />
          <input type="submit" formnovalidate name="cancel" value="Cancel" />
    </div>
    </form>

<?php if ( $results['discount']->code_id ) { ?>
      <p><a href="admin.php?action=deleteCode&amp;code_id=<?php echo $results['discount']->code_id ?>" onclick="return confirm('Delete This Code?')">Delete This Code</a></p>
<?php } ?>

