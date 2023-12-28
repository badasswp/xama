<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sign Up | <?php echo bloginfo(); ?></title>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	<style type="text/css">
		body {
			margin: 0;
			padding: 0;
			background: #ECECEC;
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100vh;
		}

		form {
			background: #FFF;
			width: 400px;
			border-radius: 15px;
			padding: 50px;
			box-sizing: border-box;
			display: flex;
			flex-direction: column;
			gap: 5px;
		}

		h1 {
			margin-top: 0;
			margin-bottom: 0;
			font-family: Inter, Arial;
			font-size: 36px;
		}

		p {
			font-family: Inter, Arial;
			font-size: 15px;
		}

		p label {
			display: block;
			font-family: Inter, Arial;
			font-size: 15px;
			font-weight: 700;
			letter-spacing: -0.25px;
		}

		p input {
			padding: 15px 20px;
			border: 1px solid #CCC;
			border-radius: 5px;
			width: 100%;
			box-sizing: border-box;
			font-family: Inter, Arial;
			font-size: 15px;
			margin-top: 10px;
		}

		button {
			background: #000;
			color: #fff;
			font: 500 15px/24px Inter, Arial;
			border: none !important;
			border-radius: 5px;
			padding: 15px 30px;
			cursor: pointer;
			transition: all .3s;
			margin-right: auto;
		}

		button:hover {
			background: #F00;
		}

		#error {
 			background: #ECECEC;
			font-size: 10px !important;
			padding: 0 20px;
		}

		#error p {
			font-size: 12px;
		}

		#fullname,
		#username {
			margin-bottom: 0;
		}
	</style>
</head>
<body>
	<form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<h1>
			<?php echo esc_html__( 'Sign Up', 'xama' ); ?>
		</h1>
		<p style="opacity: 0.5">
			<?php echo esc_html__( 'Please register with your User details..', 'xama' ); ?>
		</p>
		<?php if ( isset( $_POST['http_error_msgs'] ) ) { ?>
		<div id="error">
		<?php
			foreach ( $_POST['http_error_msgs'] as $msg ) {
				echo '<p>' . esc_html__( $msg, 'xama' ) . '</p>';
			}
		?>
		</div>
		<?php } ?>
		<p id="fullname">
			<label><?php echo esc_html__( 'Full Name:', 'xama' ); ?></label>
			<input type="text" placeholder="John Doe" name="xama_fullname" required />
		</p>
		<p id="username">
			<label><?php echo esc_html__( 'Email Address:', 'xama' ); ?></label>
			<input type="email" placeholder="you@email.com" name="xama_username" required />
		</p>
		<p id="password">
			<label><?php echo esc_html__( 'Password:', 'xama' ); ?></label>
			<input type="password" placeholder="**********" name="xama_password" required />
		</p>
		<button type="submit" name="xama_signup">
			<?php echo esc_html__( 'Get Started', 'xama' ); ?>
		</button>
		<?php wp_nonce_field( 'xama_action', 'xama_nonce' ); ?>
	</form>
</body>
</html>
