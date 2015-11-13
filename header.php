<?php
    // Verifica o tipo de requisição e se tem a variável 'code' na url
    if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['code'])){
	   // Informe o id da app
       $appId = '279745355533';
	   // Senha da app
       $appSecret = '6fb0e1a88845839421bd476c46a10a49';
	   // Url informada no campo "Site URL" 
       $redirectUri = urlencode('http://cellep-marcusmartini.c9users.io/');
	   // Obtém o código da query string
       $code = $_GET['code'];
	   // Monta a url para obter o token de acesso
       $token_url = "https://graph.facebook.com/oauth/access_token?"
       . "client_id=" . $appId . "&redirect_uri=" . $redirectUri
       . "&client_secret=" . $appSecret . "&code=" . $code;
       
       // Requisita token de acesso
       $response = @file_get_contents($token_url);
       
       if($response){
           $params = null;
           parse_str($response, $params);
           
           // Se veio o token de acesso
           if(isset($params['access_token']) && $params['access_token']){
             $graph_url = "https://graph.facebook.com/me?access_token=" 
             . $params['access_token'];
      
             // Obtém dados através do token de acesso
             $user = json_decode(file_get_contents($graph_url));
             
             // Se obteve os dados necessários
             if(isset($user->email) && $user->email){
               
               /*
                * Autenticação feita com sucesso.
                * Loga usuário na sessão. Substitua as linhas abaixo pelo seu código de registro de usuários logados
                */
               $_SESSION['user_data']['email'] = $user->email;
               $_SESSION['user_data']['name'] = $user->name;
               
               /*
                * Aqui você pode adicionar um código que cadastra o email do usuário no banco de dados
                * A cada requisição feita em páginas de área restrita você verifica se o email
                * que está na sessão é um email cadastrado no banco
                */
             }
           }else{
             $_SESSION['fb_login_error'] = 'Falha ao logar no Facebook';
           }
       }else{
           $_SESSION['fb_login_error'] = 'Falha ao logar no Facebook';
       }
    }else if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['error'])){
        $_SESSION['fb_login_error'] = 'Permissão não concedida';
    }
?>
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