<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/footer-fix.css">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="header" class="clearfix">
    <div class="container">
        <div class="col-group">
            <?php get_template_part('template-parts/site-logo'); ?>
			<?php if ( has_nav_menu( 'primary' ) ) : ?>
                <div>
					<?php wp_nav_menu( array(
						'theme_location'  => 'primary',
						'container'       => 'nav',
						'container_class' => 'clearfix',
						'container_id'    => 'nav-menu',
					) ); ?>
                </div>
			<?php endif; ?>
        </div>
    </div>
</header>

<div id="body">
    <div class="container">
        <div class="col-group">
