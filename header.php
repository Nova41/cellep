<!doctype html>
<html class="no-js">
  <head>
    <meta charset="utf-8">
    <title><?php echo implode(' ', explode(' | ', wp_title('|', false, 'right'))); ?><?php bloginfo('name'); ?></title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="Com Cel.Lep, seu mundo fica maior. Aprenda inglês e espanhol no Cel.Lep, o curso de idiomas com o maior índice de reconhecimento de qualidade de São Paulo. Visite uma de nossas unidades e descubra."/>
    <!--<meta name="robots" content="index, follow">-->
    <meta name="google-site-verification" content="google00b5df27e15424a7.html" />
    <meta property="og:title" content="Cel.Lep Idiomas | Os melhores cursos de Inglês e Espanhol de São Paulo"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="http://br.cellep.com/"/>
    <meta property="og:image" content="http://centraldoaluno2.cellep.com/images/logo.png"/>
    <meta property="og:site_name" content="Cel.Lep"/>
    <meta property="og:description" content="Com Cel.Lep, seu mundo fica maior. Aprenda inglês e espanhol no Cel.Lep, o curso de idiomas com o maior índice de reconhecimento de qualidade de São Paulo. Visite uma de nossas unidades e descubra."/>
    <meta name="keywords" content="Idioma, Cursos, Inglês, Espanhol, Família, Filhos, Vida, Certificados, Cambridge, Site"/>

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
    
  
  <!-- Google Tag Manager -->
  <noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-TZZX44"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-TZZX44');</script>
  <!-- End Google Tag Manager -->

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
    
    <a href="tel:01121253255" class="mobile-tel">11 2125.3255</a>
    
    <div class="form__register"><?php wp_login_form(); ?></div>
  </header>
  
  <main class="main">