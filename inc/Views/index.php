<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Xama</title>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	<script crossorigin src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
	<script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
	<script type="text/javascript" src="http://localhost:2048/wp-content/plugins/xama/dist/app.js"></script>
	<style type="text/css">
		body {
			font-family: 'Inter', 'sans-serif';
			-webkit-font-smoothing: antialiased;
			-moz-osx-font-smoothing: grayscale;
			text-rendering: optimizelegibility;
		}
	</style>
</head>
<body id="<?php global $post; echo $post->ID; ?>">
	<div id="app"></div>
</body>
</html>