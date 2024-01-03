<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sign Up | <?php echo bloginfo(); ?></title>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	<link href="<?php echo plugins_url(); ?>/xama/inc/Views/assets/styles.css" rel="stylesheet" />
</head>

<?php
if ( isset( $_GET['id'] ) ) {
	$id = $_GET['id'];
}
?>

<body>
	<form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<h1>
			<?php echo esc_html__( get_the_title( $id ) ?: 'Sign Up', 'xama' ); ?>
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
		<p id="connect">
			<label><a href="<?php echo home_url() . '/login?id=' . $id; ?>"><?php echo esc_html__( 'Already have an account?', 'xama' ); ?></a></label>
		</p>
		<?php wp_nonce_field( 'xama_action', 'xama_nonce' ); ?>
	</form>
</body>
</html>
