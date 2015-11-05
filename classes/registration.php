<?php
  set_include_path(get_include_path() . PATH_SEPARATOR . 'wp-content/themes/cellep/google-api/src');

  class UserLoginOrRegistration {
  	function __construct() {
  		$this->app_id = '279745355533'; // FB App ID
      $this->app_secret = '6fb0e1a88845839421bd476c46a10a49'; // FB App Secret
      $this->g_app_id = '353316530389-gu9i2va9q1rv59b6ovmoqp55ltd6h4m0.apps.googleusercontent.com'; // Google App ID
    	$this->g_app_secret = 'BbCLoXHrKSuaYoMsNIjfL0hE'; // Google App Secret
    	$this->current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; // Current page url
  	}

    function fb_login_url() {
  		if( empty( $_REQUEST['code'] ) ) {
  			$_SESSION['state'] = md5( uniqid( rand(), TRUE ) ); // CSRF protection
  			$dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" . $this->app_id . "&redirect_uri=" . urlencode( $this->current_url ) . "&state=" . $_SESSION['state'] . '&scope=email'; // FB login url
  
  			echo $dialog_url;
  		}
  	}

    function g_login_url() {
      $client = new Google_Client();
      $client->setClientId($this->g_app_id);
      $client->setClientSecret($this->g_app_secret);
      $client->setRedirectUri($this->current_url);
      $client->addScope("email");
      $client->addScope("profile");
      
      $service = new Google_Service_Oauth2($client);
    }
  	
  	function authenticate_user() {
  	  echo '<script>';
			  echo 'alert("session: '.$_SESSION['state'].' request: '.$_REQUEST['state'].'")';
			echo '</script>';
  			
  			
  		if( $_SESSION['state'] && ( $_SESSION['state'] === $_REQUEST['state'] ) ) {
  			
  			// handle the user login
  			$token_url = "https://graph.facebook.com/oauth/access_token?"
      		. "client_id=" . $this->app_id . "&redirect_uri=" . urlencode( strstr( $this->current_url, '?', true ) )
      		. "&client_secret=" . $this->app_secret . "&code=" . $_REQUEST['code'];
      
      	$response = file_get_contents( $token_url );
      	$params = null;
        parse_str( $response, $params );

      	$graph_url = "https://graph.facebook.com/me?access_token=" . $params['access_token'];
      	$user = json_decode( file_get_contents( $graph_url ) );
      
      	if( !email_exists( $user->email ) ) {
      		$wp_user_id = wp_create_user( $user->username, md5( ( $user->id . $user->email ) ), $user->email );
      
      		if ( is_wp_error( $wp_user_id ) ) {
      			$error_message = $wp_user_id->get_error_code();
      			// There was an error creating this user provide error message
      		}
      		else {
      			update_user_meta( $wp_user_id, 'first_name', $user->first_name ); // update first name
      			update_user_meta( $wp_user_id, 'last_name', $user->last_name ); // update last name
      			update_user_meta( $wp_user_id, 'gender', $user->gender ); // add users gender
      
      			// The following is used to login the user to wordpress
      			$creds['user_login'] = $user->username;
      			$creds['user_password'] = md5( ( $user->id . $user->email ) );
      			$creds['remember'] = true;
      
      			$wp_user = wp_signon( $creds, false );
      
      			if ( is_wp_error( $wp_user ) ) {
      				$error_message = $wp_user->get_error_code();
      				// If there was a login error provide user with message
      			}
      
      			// In order for login to correctly set cookies, ect... we do a safe redirect
      			wp_redirect( strstr( $this->current_url, '?', true) );
      			exit;
      		}
      	}
      	else {
      		$user_obj = get_user_by( 'email', $user->email ); // get user by email
      
      		// Use creds to sign in the user
      		$creds['user_login'] = $user_obj->data->user_login;
      		$creds['user_password'] = md5( ( $user->id . $user->email ) );
      		$creds['remember'] = true;
      
      		$wp_user = wp_signon( $creds, false );
      
      		if ( is_wp_error( $wp_user ) ) {
      			$error_message = $wp_user->get_error_code();
      			// If there is a signin error provide message to user
      		}
      
      		// In order for login to correctly set cookies, ect... we do a safe redirect
      		wp_redirect( strstr( $this->current_url, '?', true) );
      		exit;
      	}
  		}
  		elseif( $_REQUEST['error'] ) {
  			$error_reason = $_REQUEST['error_reason'];
  			$error = $_REQUEST['error'];
  			$error_description = $_REQUEST['error_description'];
  
  			// Provide the user with some sort of error message
  		}
  		else {
  			// The state does not match. You may be a victim of CSRF.
  		}
  	}

  	function init_sessions() {
  		if( !session_id() ) {
  			session_start();
  		}
  	}
  }
  
  add_action( 'init', 'init_registration' );
  function init_registration() {
  	global $registration;
  	$registration = new UserLoginOrRegistration();
  }
?>