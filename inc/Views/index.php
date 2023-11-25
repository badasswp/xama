<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Xama</title>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	<script crossorigin src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
	<script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
	<script type="text/javascript" src="http://localhost:2468/wp-content/plugins/xama/dist/app.js"></script>
</head>
<body>
	<div id="xama" data-id="<?php global $post; echo $post->ID; ?>" data-url="<?php echo home_url(); ?>" data-user="<?php echo wp_get_current_user()->user_login; ?>" data-user-id="<?php echo wp_get_current_user()->ID; ?>"></div>
</body>
</html>
