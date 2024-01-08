<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo esc_html( get_the_title() ); ?></title>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	<script crossorigin src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
	<script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
	<script type="text/javascript" src="<?php echo esc_url( plugins_url( '/xama/dist/app.js' ) ); ?>"></script>
</head>
<body>
	<div id="xama" data-id="<?php echo esc_attr( get_the_ID() ); ?>" data-url="<?php echo esc_url( home_url() ); ?>" data-user="<?php echo esc_attr( wp_get_current_user()->ID ); ?>" data-login="<?php echo esc_attr( wp_get_current_user()->user_login ); ?>"></div>
</body>
</html>
