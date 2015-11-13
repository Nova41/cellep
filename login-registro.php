<div class="btns-login">
  <?php if ((!is_user_logged_in()) && ((!isset($_SESSION['user_data']) && empty($_SESSION['user_data'])))) { ?>
    <a href="#" class="btn--course open-login">LOGIN / CADASTRO</a>
    <a class="btn--course" href="https://www.facebook.com/dialog/oauth?client_id=279745355533&scope=email&redirect_uri=<?php echo urlencode('http://cellep-marcusmartini.c9users.io/') ?>">LOGIN FACEBOOK</a>
  <?php } else { ?>
    <span class="user-info">
      <strong>Logado como:</strong><br>
      <?php global $user_login;
        get_currentuserinfo();
        echo $user_login;
      ?>
      <strong><?php echo $_SESSION['user_data']['name'] ?></strong>
    </span>
    <a href="<?php echo wp_logout_url(home_url()); ?>" class="btn--course btn--logout">Logout</a>
  <?php } ?>
  
  <?php if(isset($_SESSION['fb_login_error']) && $_SESSION['fb_login_error']): ?>
          <p class="message"><?php echo $_SESSION['fb_login_error'] ?></p>
  <?php unset($_SESSION['fb_login_error']); endif; ?>
 
</div>