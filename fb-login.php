<?php 
  require 'fb-sdk/facebook.php';

  $facebook = new Facebook(array(
    'appId'  => '279745355533',
    'secret' => '6fb0e1a88845839421bd476c46a10a49',
  ));
  
  // Get User ID
  $user = $facebook->getUser();
  
  if ($user) {
    try {
      // Proceed knowing you have a logged in user who's authenticated.
      $user_profile = $facebook->api('/me');
    } catch (FacebookApiException $e) {
      error_log($e);
      $user = null;
    }
  }
  
  if ($user) {
    $logoutUrl = $facebook->getLogoutUrl();
  } else {
    $login_url = $facebook->getLoginUrl(array(
      'scope' => 'email'
    ));
  }
  
  // echo $loginUrl ? '<a href="'.$loginUrl.'">login</a><br>' : '<a href="'.$logoutUrl.'">logout</a>';
  
  /*echo '<pre>';
    print_r($user_profile);
  echo '</pre>';*/
?>