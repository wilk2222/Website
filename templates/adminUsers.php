<div id="adminHeader">
    <p>You are logged in as <b><?php echo htmlspecialchars( $_SESSION['username']) ?></b>. <a href="login.php?action=logout"?>Log out</a></p>
    </div>user

    <h1><?php echo $results['pageTitle']?></h1>

<form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
    <input type="hidden" name="user_id" value="<?php echo $results['user']->user_id ?>"/>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>

    <ul>

    <li>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo $results['user']->username; ?>" readonly>
    </li>

    <li>
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" value="<?php echo $results['user']->email; ?>" readonly>
    </li>

    <li>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo $results['user']->username; ?>" readonly>
    </li>
    <li>
    <label for="firstName">First Name:</label>
    <input type="text" id="firstName" name="firstName" value="<?php echo $results['user']->firstName; ?>" readonly>
    </li>
    <li>
    <label for="lastName">Last Name:</label>
    <input type="text" id="lastName" name="lastName" value="<?php echo $results['user']->lastName; ?>" readonly>
    </li>
    <li>
    <label for="nickname">Nickname:</label>
    <input type="text" id="nickname" name="nickname" value="<?php echo $results['user']->nickname; ?>" readonly>
    </li>
    <li>
    <label for="dob">Date of Birth:</label>
    <input type="text" id="dob" name="dob" value="<?php echo $results['user']->dob; ?>" readonly>
    </li>
    <li>
    <label for="phoneNo">Phone Number:</label>
    <input type="text" id="phoneNo" name="phoneNo" value="<?php echo $results['user']->phoneNo; ?>" readonly>
    </li>
    <li>
    <label for="postcode">Postcode:</label>
    <input type="text" id="postcode" name="postcode" value="<?php echo $results['user']->postcode; ?>" readonly>
    </li>
    <li>
    <label for="address">Address:</label>
    <input type="text" id="address" name="address" value="<?php echo $results['user']->address; ?>" readonly>
    </li>
    <li>
    <label for="surfAbility">Surfing Ability:</label>
    <input type="text" id="surfAbility" name="surfAbility" value="<?php echo $results['user']->surfAbility; ?>" readonly>
    </li>
    <li>
    <label for="notes">How Client Found Us:</label>
    <input type="text" id="notes" name="notes" value="<?php echo $results['user']->notes; ?>" readonly>
    </li>


    <li>
    <label for="membership_permission">Verified Member:</label>
    <select id="membership_permission" name="membership_permission">
    <option value="1" <?php echo ($results['user']->membership_permission == 1) ? 'selected' : ''; ?>>Yes</option>
    <option value="0" <?php echo ($results['user']->membership_permission == 0) ? 'selected' : ''; ?>>No</option>
    </select>
    </li>

    <li>
    <label for="admin_permission">Admin Privlages:</label>
    <select id="admin_permission" name="admin_permission">
    <option value="1" <?php echo ($results['user']->admin_permission == 1) ? 'selected' : ''; ?>>Yes</option>
    <option value="0" <?php echo ($results['user']->admin_permission == 0) ? 'selected' : ''; ?>>No</option>
    </select>
    </li>

    </ul>

    <div class="buttons">
          <input type="submit" name="saveChanges" value="Save Changes" />
          <input type="submit" formnovalidate name="cancel" value="Cancel" />
    </div>
    </form>

<?php if ( $results['user']->user_id ) { ?>
      <p><a href="admin.php?action=deleteUser&amp;user_id=<?php echo $results['user']->user_id ?>" onclick="return confirm('Delete This User?')">Delete This User</a></p>
<?php } ?>