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


// ************ Force Display Name as Full Name ************ //

//Sets the user's display name (always) to first name last name, when it's avail.
function force_full_name($user_id) {
  $data = get_userdata($user_id);
  wp_update_user( array (
    'ID' => $user_id, 
    'display_name' => "$data->first_name $data->last_name"
  ));
}
add_action( 'user_register', 'force_full_name' );


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
  wp_enqueue_script( 'conversao', get_template_directory_uri() . '/assets/js/conversao.js', array('jquery'), false, true );
}
add_action( 'wp_enqueue_scripts', 'site_scripts' );

?>
