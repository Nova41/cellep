<!doctype html>
<html class="no-js">
  <head>
    <meta charset="utf-8">
    <title><?php echo implode(' ', explode(' | ', wp_title('|', false, 'right'))); ?><?php bloginfo('name'); ?></title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link href="//www.google-analytics.com" rel="dns-prefetch">
    <link href="//ajax.googleapis.com" rel="dns-prefetch">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

    <!-- ADAPTIVE IMAGES -->
    <script>
      /* global devicePixelRatio */
      document.cookie='resolution='+Math.max(screen.width,screen.height)+("devicePixelRatio" in window ? ","+devicePixelRatio : ",1")+'; path=/';
    </script>

    <!-- Main Stylesheet -->
    <link href="<?php bloginfo('template_url');?>/assets/css/style.min.css" rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.5.6/slick.css"/>
    
    <!-- Favicons -->
    <link rel="shortcut icon" href="<?php bloginfo('template_url');?>/favicon.ico">
    <link rel="apple-touch-icon" href="<?php bloginfo('template_url');?>/apple-touch-icon-precomposed.png">
    
    <?php wp_head(); ?>

  </head>

  <body <?php body_class(); ?>>

  <header class="header" role="banner" id="header">

    <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>#home">
      <?php require_once('svg-logo.php'); ?>
    </a>
    
    <nav class="nav nav--main" id="nav--main" role="navigation">
      <?php
        $walker = new data_type_walker();

        $menuParameters = array (
          'menu'            => 'menu-top',
          'menu_class'      => 'main-menu',
          'container'       => false,
          'depth'           => 2,
          'walker'          => $walker
        );
        wp_nav_menu($menuParameters);
      ?>
    </nav>
    
    <?php 
      $formpage = do_shortcode('[getpage slug="fale-conosco" template="clicktocall"]');
      echo do_shortcode($formpage);
    ?>
    
    <a href="tel:01131235537" class="mobile-tel">11 3123.5537</a>
    
    <div class="form__register"><?php wp_login_form(); ?></div>
  </header>
  
  <main class="main">