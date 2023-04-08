

<div id="adminHeader">
    <p>You are logged in as <b><?php echo htmlspecialchars( $_SESSION['username']) ?></b>. <a href="admin.php?action=logout"?>Log out</a></p>
    </div>

<form class="admin-hub-form-container" method="post">
<div class="buttons">
          <input class="eventButton" type="submit" name="eventButton" value="Events" />
          <input class="codeButton" type="submit" name="codeButton" value="Discount Codes" />
          <input class="usersButton" type="submit" name="usersButton" value="Users" />
          <input class="productsButton" type="submit" name="productsButton" value="Products" />
          <input class="reviewsButton" type="submit" name="reviewsButton" value="Reviews" />
</div>
</form>
