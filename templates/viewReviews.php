<!-- Display the admin header with the logged-in user's name and a logout link -->
<div id="adminHeader">
    <p>You are logged in as <b><?php echo htmlspecialchars($_SESSION['username']) ?></b>. <a href="admin.php?action=logout">Log out</a></p>
</div>

<!-- Display the page title -->
<h1>All Reviews</h1>

<!-- Display error messages, if any -->
<?php if (isset($_GET['status']) && $_GET['status'] == 'reviewDeleted') { ?>
        <div class="statusMessage">Review successfully deleted.</div>
<?php } ?>

<!-- Display status messages, if any -->
<?php if (isset($results['statusMessage'])) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>

<!-- Display reviews in a table -->
<table>
    <tr>
        <th>Review ID</th>
        <th>Product ID</th>
        <th>User ID</th>
        <th>Rating</th>
        <th>Review Text</th>
        <th>Review Date</th>
        <th>Review Sentiment</th>
        <th>Compound Score</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <!-- Loop through the reviews and display them in table rows -->
    <?php foreach ($results['reviews'] as $review) { ?>
        <tr <?php echo $review->needs_review ? 'class="needs-review"' : ''; ?>>
            <td><?php echo $review->review_id ?></td>
            <td><?php echo $review->product_id ?></td>
            <td><?php echo $review->user_id ?></td>
            <td><?php echo $review->rating ?></td>
            <td><?php echo $review->review_text ?></td>
            <td><?php echo date("d/m/Y", strtotime($review->review_date)); ?></td>
            <td><?php echo $review->sentiment ?></td>
            <td><?php echo $review->compound_score ?></td>
            <td><?php echo $review->needs_review ? 'Pending' : 'Approved'; ?></td>
            <td>
                <?php if (!$review->needs_review) { ?>
                    <form method="post" action="admin.php?action=deleteReview" onsubmit="return confirm('Are you sure you want to delete this review?')">
                        <input type="hidden" name="review_id" value="<?php echo $review->review_id ?>">
                        <input type="submit" name="delete_review" value="Delete">
                    </form>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>

<!-- Display a link to return to the previous admin page -->
<p><a href="admin.php">Return</a></p>
