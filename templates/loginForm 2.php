<div class="form-container">
  <form action="login.php?action=login" method="POST">
    <input type="hidden" name="login" value="true" />

    <?php if (isset($results['errorMessage'])) { ?>
      <div class="errorMessage">
        <?php echo $results['errorMessage'] ?>
      </div>
    <?php } ?>

    <h2>Login</h2>
    <div>
      <label for="username" class="usernameLabel">Username</label>
      <input class="usernameInput" type="text" name="username" id="username" placeholder="Your username" required autofocus maxlength="20" />
    </div>

    <div>
      <label for="password" class="passwordLabel">Password</label>
      <input class="passwordInput" type="password" name="password" id="password" placeholder="Your password" required maxlength="20" />
    </div>

    <input class="submitButton" type="submit" name="login" value="Login">
  </form>
</div>