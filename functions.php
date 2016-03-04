<?php


// ******************* Sidebars ****************** //

/*if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => 'Pages',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	));
}*/

// ******************* Login / Registration ****************** //

// Import the registration class
include_once('classes/registration.php');


// ******************* Add Custom Menus ****************** //

add_theme_support('menus');


// ************** Filter the_title for break ************** //

function remove_menus(){
  remove_menu_page( 'edit.php' );                   //Posts
  remove_menu_page( 'edit-comments.php' );          //Comments
}
add_action( 'admin_menu', 'remove_menus' );



// ************** Filter the_title for break ************** //

function filter_title( $title ) {
    $substrings = explode( ' | ', $title );
    $title = '';
    
    foreach ($substrings as $key => $s) {
      $title .= ($key % 2 === 0) ? $s : ' <span>' . $s . '</span>';
    }
    
    $substrings = explode( '|', $title );
    $title = '';
    
    foreach ($substrings as $key => $s) {
      $title .= ($key % 2 === 0) ? $s : '<span>' . $s . '</span>';
    }
    
    return $title;
}
if (!is_admin()) add_filter( 'the_title', 'filter_title');


// ******************* Add Custom Walker for Nav ****************** //

class data_type_walker extends Walker_Nav_Menu {
  function start_el(&$output, $item, $depth, $args) {
    global $wp_query;
    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

    $class_names = $value = '';

    $classes = empty( $item->classes ) ? array() : (array) $item->classes;

    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
    $class_names = ' class="'. esc_attr( $class_names ) . '"';

    $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

    $attributes  = ! empty( $item->attr_title ) ? ' title="'   . esc_attr( $item->attr_title     ) .'"' : '';
    $attributes .= ! empty( $item->target )     ? ' target="'  . esc_attr( $item->target         ) .'"' : '';
    $attributes .= ! empty( $item->xfn )        ? ' rel="'     . esc_attr( $item->xfn            ) .'"' : '';
    $attributes .= ! empty( $item->url )        ? ' href="'    . esc_url(  $item->url            ) .'"' : '';

    $prepend = '';
    $append = '';

    if($depth != 0) {
      $description = $append = $prepend = "";
    }

    $item_output = $args->before;
    $item_output .= '<a'. $attributes .' ">';
    $item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
    $item_output .= $description.$args->link_after;
    $item_output .= '</a>';
    $item_output .= $args->after;

    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  }
}

// ******************* CF7 Clone Fields ****************** //

wpcf7_add_shortcode('clonefields', 'wpcf7_clonefields_handler', true);
function wpcf7_clonefields_handler($atts, $content = null) {
  
  return '<div id="clone-it">'.do_shortcode($content).'</div>';
}
 
add_filter( 'wpcf7_form_elements', 'mycustom_wpcf7_form_elements' );
function mycustom_wpcf7_form_elements( $form ) {
	return do_shortcode($form);
}


// ******************* CF7 [submit] to <button> ****************** //

function cellep_wpcf7_submit_button() {
  if(function_exists('wpcf7_remove_shortcode')) {
    wpcf7_remove_shortcode('submit');
    remove_action( 'admin_init', 'wpcf7_add_tag_generator_submit', 55 );
    $cellep_cf7_module = 'cf7/submit-button.php';
    require_once $cellep_cf7_module;
  }
}
add_action('after_setup_theme','cellep_wpcf7_submit_button');


// ******************* CF7 checkbox and radio ****************** //

function cellep_wpcf7_checkbox_radio() {
  if(function_exists('wpcf7_remove_shortcode')) {
    wpcf7_remove_shortcode('checkbox');
    wpcf7_remove_shortcode('checkbox*');
    wpcf7_remove_shortcode('radio');
    remove_action( 'admin_init', 'wpcf7_add_tag_generator_checkbox_and_radio', 55 );
    $cellep_cf7_module = 'cf7/checkbox.php';
    require_once $cellep_cf7_module;
  }
}
add_action('after_setup_theme','cellep_wpcf7_checkbox_radio');


// ******************* Site_url Shortcode ****************** //

function siteurl_shortcode( $atts ) {
  extract(shortcode_atts(array(
    'dir' => '',
  ), $atts));
  return site_url($dir);
}
add_shortcode('siteurl', 'siteurl_shortcode');


// ******************* Add Bloginfo Shortcode ****************** //

function bloginfo_shortcode( $atts ) {
extract(shortcode_atts(array(
    'key' => '',
  ), $atts));
  return get_bloginfo($key);
}
add_shortcode('bloginfo', 'bloginfo_shortcode');


// ************* Add GetPage & GetChildren Shortcodes ************** //

function getpage_shortcode( $atts ) {
  extract(shortcode_atts(array(
    'slug' => '',
    'template' => ''
  ), $atts));
  
  ob_start();
  set_query_var('partslug', $slug);
  get_template_part($template);
  return ob_get_clean();
}
add_shortcode('getpage', 'getpage_shortcode');

function getchildren_shortcode( $atts ) {
  extract(shortcode_atts(array(
    'parent' => '',
    'template' => '',
    'top' => 0
  ), $atts));
  
  if ( $top === 'false' ) $top = false; // just to be sure...
  $top = (bool) $top;
  
  $parentPage = get_page_by_path($parent);
	
  ob_start();
  $child_pages = new WP_Query( array(
      'post_type'      => 'page',
      'posts_per_page' => -1,
      'post_parent'    => $parentPage->ID, // enter the post ID of the parent page
      'no_found_rows'  => true, // no pagination necessary so improve efficiency of loop
      'orderby' => 'menu_order',
	    'order'   => 'ASC',
    ) );
    
  if ( $child_pages->have_posts() ) : while ( $child_pages->have_posts() ) : $child_pages->the_post();
  
    set_query_var('top', $top);
    set_query_var('partslug', basename(get_permalink()));
    set_query_var('current', $child_pages->current_post + 1);
    set_query_var('count', $child_pages->post_count);
    get_template_part($template);
  
  endwhile; endif;
  wp_reset_postdata();
      
  return ob_get_clean();
}
add_shortcode('getchildren', 'getchildren_shortcode');

function getcustomtype_shortcode( $atts ) {
  extract(shortcode_atts(array(
    'type' => '',
    'template' => ''
  ), $atts));
	
  ob_start();
  $custom_posts = new WP_Query( array(
      'post_type'      => $type,
      'posts_per_page' => -1,
      'no_found_rows'  => true, // no pagination necessary so improve efficiency of loop
      'orderby' => 'title',
	    'order'   => 'ASC',
    ) );
    
  if ( $custom_posts->have_posts() ) : while ( $custom_posts->have_posts() ) : $custom_posts->the_post();
  
    set_query_var('partslug', basename(get_permalink()));
    set_query_var('current', $custom_posts->current_post + 1);
    set_query_var('count', $custom_posts->post_count);
    get_template_part($template);
  
  endwhile; endif;
  wp_reset_postdata();
      
  return ob_get_clean();
}
add_shortcode('getcustomtype', 'getcustomtype_shortcode');


// ************** Page Slug Body Class *************** //

function add_slug_body_class( $classes ) {
  global $post;
  
  if ( isset( $post ) ) {
    $classes[] = $post->post_type . '-' . $post->post_name;
  }
  
  return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );


// ******************* Restrict Admin ****************** //

function restrict_admin() {
	if ( !current_user_can( 'manage_options' ) && '/wp-admin/admin-ajax.php' != $_SERVER['PHP_SELF'] ) {
    wp_redirect( site_url() );
	}
}
add_action( 'admin_init', 'restrict_admin', 1 );


// ******************* Email Authentication ****************** //

remove_filter('authenticate', 'wp_authenticate_username_password', 20);
add_filter('authenticate', function($user, $email, $password){

    //Check for empty fields
  if(empty($email) || empty ($password)){        
    //create new error object and add errors to it.
    $error = new WP_Error();

    if(empty($email)){ //No email
        $error->add('empty_username', __('O campo e-mail está vazio.'));
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ //Invalid Email
        $error->add('invalid_username', __('e-mail inválido.'));
    }

    if(empty($password)){ //No password
        $error->add('empty_password', __('O campo senha está vazio.'));
    }

    return $error;
  }

  //Check if user exists in WordPress database
  $user = get_user_by('email', $email);

  //bad email
  if(!$user){
    $error = new WP_Error();
    $error->add('invalid', __('Os dados digitados são inválidos.'));
    return $error;
  }
  else{ //check password
    if(!wp_check_password($password, $user->user_pass, $user->ID)){ //bad password
      $error = new WP_Error();
      $error->add('invalid', __('Os dados digitados são inválidos.')); 
      return $error;
    } else {
      return $user; //passed
    }
  }
}, 20, 3);



// ******************* User Login ****************** //

function get_token_url($isLink = false) {
  // Informe o id da app
  $appId = '279745355533';
  // Senha da app
  $appSecret = '6fb0e1a88845839421bd476c46a10a49';
  // Url informada no campo "Site URL" 
  $redirectUri = site_url('/');
  // Obtém o código da query string
  $code = $_GET['code'];
  
  $scopes = array(
    'email',
    'user_birthday'
  );
  
  if (!$isLink) {
    $returnUrl =  'https://graph.facebook.com/oauth/access_token?'.
                  'client_id='.$appId.
                  '&redirect_uri='.urlencode($redirectUri).
                  '&client_secret='.$appSecret.
                  '&code='.$code;
  } else if ($isLink === 'redirect'){
    $returnUrl =  $redirectUri;
  } else {
    $returnUrl =  'https://www.facebook.com/dialog/oauth?'.
                  'client_id='.$appId.
                  '&display=popup'.
                  '&scope='.join(',', $scopes).
                  '&redirect_uri='.urlencode($redirectUri);
  }
  
  return $returnUrl;
};

/**
 * Programmatically logs a user in
 * 
 * @param string $username
 * @return bool True if the login was successful; false if it wasn't
 */
function programmatic_login($username) {
  $user = get_user_by('login', $username);

  // Redirect URL //
  if ( !is_wp_error( $user ) )
  {
    wp_clear_auth_cookie();
    wp_set_current_user ( $user->ID );
    wp_set_auth_cookie  ( $user->ID );

    $redirect_to = get_token_url('redirect');
    wp_safe_redirect($redirect_to);
    exit();
  }
}


add_action('init', function(){
  
  // Verifica o tipo de requisição e se tem a variável 'code' na url
  if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['code'])) {
    
    $token_url = get_token_url();
    
    // Requisita token de acesso
    $response = @file_get_contents($token_url);
    
    if($response) {
      $params = null;
      parse_str($response, $params);
    
      // Se veio o token de acesso
      if(isset($params['access_token']) && $params['access_token']) {
      $graph_url = "https://graph.facebook.com/me?access_token=" 
      .$params['access_token'];
    
        // Obtém dados através do token de acesso
        $user = json_decode(file_get_contents($graph_url));
        
        // Se obteve os dados necessários
        if(isset($user->email) && $user->email){
    
          /*
          * Autenticação feita com sucesso.
          * Loga usuário na sessão. Substitua as linhas abaixo pelo seu código de registro de usuários logados
          */
          $_SESSION['user_data']['email']         = $user->email;
          $_SESSION['user_data']['id']            = $user->id;
          $_SESSION['user_data']['first_name']    = $user->first_name;
          $_SESSION['user_data']['last_name']     = $user->last_name;
          $_SESSION['user_data']['name']          = $user->name;
          $_SESSION['user_data']['link']          = $user->link;
          
          $wp_user = get_user_by('email', $_SESSION['user_data']['email']);
          
          $basedata = array(
            'first_name'           => $_SESSION['user_data']['first_name'],
            'last_name'            => $_SESSION['user_data']['last_name'],
            'show_admin_bar_front' => 'false'
          );
          
          if(!$wp_user) { // New user
            $basedata['user_pass']      = $_SESSION['user_data']['id'];
            $basedata['display_name']   = $_SESSION['user_data']['name'];
            $basedata['nickname']       = $_SESSION['user_data']['name'];
            $basedata['user_email']     = $_SESSION['user_data']['email'];
            $basedata['user_login']     = $_SESSION['user_data']['email'];
            $wp_user = wp_insert_user($basedata);
            
            echo 'new user id: '.$wp_user;
          } else { // Existing user
            $basedata['ID'] = $wp_user->ID;
            $wp_user = wp_update_user($basedata);
          }
          
          update_user_meta($wp_user, 'facebook', $_SESSION['user_data']['link']);
          
          // User Login
          $user_data = get_userdata($wp_user);
          $logged = programmatic_login($user_data->user_login);
        }
      } else {
        $_SESSION['fb_login_error'] = 'Falha ao logar no Facebook';
      }
    } else {
      $_SESSION['fb_login_error'] = 'Falha ao logar no Facebook';
    }
  } else if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['error'])){
    $_SESSION['fb_login_error'] = 'Permissão não concedida';
  }
  
});
  
add_action('init', function(){
  
  // not the login request
  if(!isset($_POST['action']) || $_POST['action'] !== 'user_login')
    return;

  $creds = array();
	$creds['user_login'] = $_POST['email'];
	$creds['user_password'] = $_POST['senha'];
	$creds['remember'] = true;
	$user = wp_signon( $creds, false );
	if ( is_wp_error($user) ) {
		echo $user->get_error_message();
	} else {
		header('Location: ' . $_POST['redirect_to']);
    exit;
	}
});



// ******************* User Register ****************** //
$fields = array();
$errors = new WP_Error();
add_action('init', cr($fields, $errors));

function cr(&$fields, &$errors) {
  
  // Check args and replace if necessary
  if (!is_array($fields))     $fields = array();
  if (!is_wp_error($errors))  $errors = new WP_Error;
  
  // Check for form submit
  if (isset($_POST['action']) && $_POST['action'] === 'user_register') {
    
    // Get fields from submitted form
    $fields = cr_get_fields();
    
    // Validate fields and produce errors
    if (cr_validate($fields, $errors)) {
      
      // Santitize fields
      cr_sanitize($fields);
      
      // If successful, register user
      $basedata = array(
        'display_name'         => $fields['display_name'],
        'nickname'             => $fields['nickname'],
        'first_name'           => $fields['display_name'],
        'user_login'           => $fields['user_login'],
        'user_email'           => $fields['user_email'],
        'user_pass'            => $fields['user_pass'],
        'show_admin_bar_front' => $fields['show_admin_bar_front']
      );
      
      $user_id = wp_insert_user($basedata);
      update_user_meta($user_id, 'phone1', $fields['telefone']);
      update_user_meta($user_id, 'zip', $fields['cep']);
      update_user_meta($user_id, 'addr1', $fields['endereco']);
      update_user_meta($user_id, 'nascimento', $fields['nascimento']);
      
      $creds = array();
    	$creds['user_login'] = $fields['user_email'];
    	$creds['user_password'] = $fields['user_pass'];
    	$creds['remember'] = true;
    	$user = wp_signon( $creds, false );
    	if ( is_wp_error($user) ) {
    		echo $user->get_error_message();
    	} else {
    	  // Clear field data
        $fields = array(); 
    		
    		header('Location: ' . site_url('/'));
        exit;
    	}
    }
  }
}

function cr_sanitize(&$fields) {
  $fields['display_name']    = isset($fields['display_name'])      ? sanitize_text_field($fields['display_name']) : '';
  $fields['nickname']        = isset($fields['nickname'])          ? sanitize_text_field($fields['nickname']) : '';
  $fields['user_login']      = isset($fields['user_login'])        ? sanitize_email($fields['user_login']) : '';
  $fields['user_email']      = isset($fields['user_email'])        ? sanitize_email($fields['user_email']) : '';
  $fields['user_pass']       = isset($fields['user_pass'])         ? esc_attr($fields['user_pass']) : '';
  $fields['telefone']        = isset($fields['telefone'])          ? sanitize_text_field($fields['telefone']) : '';
  $fields['cep']             = isset($fields['cep'])               ? sanitize_text_field($fields['cep']) : '';
  $fields['endereco']        = isset($fields['endereco'])          ? sanitize_text_field($fields['endereco']) : '';
  $fields['nascimento']      = isset($fields['nascimento'])        ? sanitize_text_field($fields['nascimento']) : '';
}

function cr_get_fields() {
  return array(
    'display_name'    => isset($_POST['nome'])            ? $_POST['nome'] : '',
    'nickname'        => isset($_POST['nome'])            ? $_POST['nome'] : '',
    'user_login'      => isset($_POST['email'])           ? $_POST['email'] : '',
    'user_email'      => isset($_POST['email'])           ? $_POST['email'] : '',
    'user_pass'       => isset($_POST['senha'])           ? $_POST['senha'] : '',
    'telefone'        => isset($_POST['telefone'])        ? $_POST['telefone'] : '',
    'cep'             => isset($_POST['cep'])             ? $_POST['cep'] : '',
    'endereco'        => isset($_POST['endereco'])        ? $_POST['endereco'] : '',
    'nascimento'      => isset($_POST['nascimento'])      ? $_POST['nascimento'] : '',
    'show_admin_bar_front' => 'false'
  );
}

function cr_validate(&$fields, &$errors) {
  
  // Make sure there is a proper wp error obj
  // If not, make one
  if (!is_wp_error($errors))  $errors = new WP_Error;
  
  // Validate form data
  if (empty($fields['display_name']) || empty($fields['user_email']) || empty($fields['user_pass'])) {
    $errors->add('field', 'Os campos nome, email e senha são obrigatórios');
  }

  if (strlen($fields['user_pass']) < 5) {
    $errors->add('user_pass', 'A senha deve ter mais de 5 dígitos');
  }

  if (!is_email($fields['user_email'])) {
    $errors->add('email_invalid', 'O email digitado não é válido');
  }

  if (email_exists($fields['user_email'])) {
    $errors->add('email', 'O email digitado já está em uso');
  }
  
  // If errors were produced, fail
  if (count($errors->get_error_messages()) > 0) {
    return false;
  }
  
  // Else, success!
  return true;
}



///////////////
// SHORTCODE //
///////////////

// The callback function for the [cr] shortcode
function cr_cb() {
  // Buffer output
  ob_start();
  
  // Custom registration, go!
  
  ?>
  <form name="form-cadastro" class="form-cadastro" action="" method="POST">
    <input name="action" type="hidden" value="user_register" />
    <li class="form-line"><label for="nome">Nome*</label><span class="wpcf7-form-control-wrap"><input name="nome" type="text" value="<?php echo (isset($_POST['display_name']) ? $_POST['display_name'] : '') ?>" size="40" required/></span>
    <li class="form-line"><label for="email">Email*</label><span class="wpcf7-form-control-wrap"><input name="email" type="email" value="<?php echo (isset($_POST['user_email']) ? $_POST['user_email'] : '') ?>" size="40" required placeholder="email@email.com"/></span>
    <li class="form-line"><label for="senha">Senha*</label><span class="wpcf7-form-control-wrap"><input name="senha" type="password" value="<?php echo (isset($_POST['user_pass']) ? $_POST['user_pass'] : '') ?>" size="40" required/></span>
    <li class="form-line"><label for="telefone">Telefone*</label><span class="wpcf7-form-control-wrap"><input name="telefone" class="telefone" value="<?php echo (isset($_POST['telefone']) ? $_POST['telefone'] : '') ?>" size="40" required placeholder="__ ____.____"/></span>
    <li class="form-line"><label for="cep">CEP</label><span class="wpcf7-form-control-wrap"><input name="cep" type="text" value="<?php echo (isset($_POST['cep']) ? $_POST['cep'] : '') ?>" class="cep" size="40" placeholder="_____-___"/></span>
    <li class="form-line"><label for="endereco">Endereço</label><span class="wpcf7-form-control-wrap"><input name="endereco" type="text" class="endereco" value="<?php echo (isset($_POST['endereco']) ? $_POST['endereco'] : '') ?>" size="40"/></span>
    <li class="form-line"><label for="nascimento">Data de nascimento</label><span class="wpcf7-form-control-wrap"><input name="nascimento" type="text" class="data-nascimento" value="<?php echo (isset($_POST['nascimento']) ? $_POST['nascimento'] : '') ?>" size="40" placeholder="__/__/____"/></span>
    <li class="form-line"><button type="submit" class="wpcf7-form-control btn">Enviar</button>
  </form>
  <?php
  
  // Return buffer
  return ob_get_clean();
}
add_shortcode('cr', 'cr_cb');



// ******************* Include jQuery Properly ****************** //

function my_jquery_enqueue() {
   wp_deregister_script('jquery');
   wp_deregister_script( 'jquery-core' );
   wp_register_script('jquery', "//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js", false, null);
   wp_enqueue_script('jquery');
}
if (!is_admin()) add_action("wp_enqueue_scripts", "my_jquery_enqueue", 11);

function plugin_scripts() {
  wp_enqueue_script( 'gmaps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCOwDT3o9y5ZGI3Ona04aqxDBukMhYoths', array('jquery'), false, true );
  wp_enqueue_script( 'scrollTo', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/2.1.0/jquery.scrollTo.js', array('jquery'), false, true );
  wp_enqueue_script( 'maskedinput', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js', array('jquery'), false, true );
  wp_enqueue_script( 'slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.5/slick.min.js', array('jquery'), false, true );
  wp_enqueue_script( 'hashchange', get_template_directory_uri() . '/assets/plugins/jquery.hashchange.min.js', array('jquery'), false, true );
}
add_action( 'wp_enqueue_scripts', 'plugin_scripts' );

function site_scripts() {
  wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/assets/components/modernizr/modernizr.js', false, true );
  wp_enqueue_script( 'script', get_template_directory_uri() . '/assets/js/scripts.min.js', array('jquery'), false, true );

}
add_action( 'wp_enqueue_scripts', 'site_scripts' );


function get_client_ip() {
     $ipaddress = '';
     if ($_SERVER['HTTP_CLIENT_IP'])
         $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
     else if($_SERVER['HTTP_X_FORWARDED_FOR'])
         $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
     else if($_SERVER['HTTP_X_FORWARDED'])
         $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
     else if($_SERVER['HTTP_FORWARDED_FOR'])
         $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
     else if($_SERVER['HTTP_FORWARDED'])
         $ipaddress = $_SERVER['HTTP_FORWARDED'];
     else if($_SERVER['REMOTE_ADDR'])
         $ipaddress = $_SERVER['REMOTE_ADDR'];
     else
         $ipaddress = 'UNKNOWN';

     return $ipaddress;
}
add_shortcode('show_ip', 'get_client_ip');


?>
