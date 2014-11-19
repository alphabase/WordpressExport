<?php
$config['hostname'] = 'HOSTNAME';
$config['username'] = 'USERNAME';
$config['password'] = 'PASSWORD';
$config['database'] = 'DATABASE';

$mysqli = new mysqli($config['hostname'], $config['username'], $config['password'], $config['database']);
$items = array();
$result = $mysqli->query('SELECT *, UNIX_TIMESTAMP(post_date) as unix FROM wp1_posts WHERE post_status = \'publish\' AND post_type != \'nav_menu_item\' ORDER BY post_date ASC');
while ($row = $result->fetch_object()) {
	$comments = $mysqli->query('SELECT *, UNIX_TIMESTAMP(comment_date) as unix FROM wp1_comments WHERE comment_post_ID = \''.$row->ID.'\'');
	while ($comment = $comments->fetch_object()) {
		$row->comments[] = $comment;
	}
	$items[] = $row;
}

setlocale(LC_ALL, 'nl_NL');
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta name="robots" content="noindex, nofollow" />

<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" />
<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<script src="//code.jquery.com/jquery-2.1.1.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

<style>
@media all {

.header { width:100%; }
.page-break	{ display:block; page-break-before:always; }

h2 { font-size:3em; }
p { font-size:1.2em; }

}
</style>

</head>

<body>
<div class="container">
<?php foreach ($items as $item): ?>
<div class="page-break">
	<h2><?php echo $item->post_title;?></h2>
	<p><strong><?php echo ucfirst(strftime('%A %e %B %Y, %H:%M:%S', $item->unix));?></strong></p>
	<p><?php echo nl2br($item->post_content);?></p>
<?php if (isset($item->comments)):?>
	<h3>Comments</h3>
<?php foreach ($item->comments as $comment):?>
	<h4><?php echo $comment->comment_author?> op <?php echo ucfirst(strftime('%A %e %B %Y, %H:%M:%S', $comment->unix));?></h4>
	<p><?php echo nl2br($comment->comment_content);?></p>
<?php endforeach?>
<?php endif?>
</div>
<?php endforeach; ?>
</div>

</body>
</html>