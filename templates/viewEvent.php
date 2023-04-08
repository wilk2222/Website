<link rel="stylesheet" type="text/css" href="path/to/your/css/file.css">
<h1 id="event-title"><?php echo $event->event_title; ?></h1>
<h2 id="event-time"><strong>Event Time: <?php echo date("H:i", strtotime($event->event_date)); ?></strong></h2>
<h2 id="event-date"><strong>Event Date: <?php echo date("d/m/Y", strtotime($event->event_date)); ?></strong></h2>
<h2 id="event-location"><strong>Event Location: <?php echo $event->event_location; ?></strong></h2>
<h3 id="event-details">Details</h3>
<p id="event-summary"><?php echo $event->event_summary; ?></p>
<blockquote>
    <p id="post-date"><?php echo date("d/m/Y", strtotime($event->post_date)); ?></p>
</blockquote>
