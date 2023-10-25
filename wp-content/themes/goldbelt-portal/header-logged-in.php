<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1,shrink-to-fit=no">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(array('dashboard-main')); ?>>
<?php wp_body_open(); ?>
<nav class="navbar navbar-expand-lg navbar-light px-5 navbar-web-nav">
	<div class="container">
		<a class="navbar-brand" href="<?php echo home_url(); ?>"
			><img class="logo-web" src="<?php echo GHF_IMAGES ?>/Logo.png"
		/></a>
		<div class="collapse navbar-collapse" id="navbarText">
			<ul class="navbar-nav nav-web mr-auto ml-auto">
				<?php 
					global $GHFThemeHelper;
					$GHFThemeHelper->ghf_render_menu();
				?>
			</ul>
			<a href="<?php echo wp_logout_url(home_url()); ?>" class="logout-btn">Logout</a>
		</div>
	</div>
</nav>
    