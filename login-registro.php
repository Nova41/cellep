<div class="btns-login">
  <?php if (!is_user_logged_in()) { ?>
    <a href="#" class="btn--course open-login">LOGIN / CADASTRO</a>
    <a href="#" class="btn--course">
      <img src="wp-content/uploads/2015/09/ico-face.png" alt="ico-face" width="41" height="41" class="ico-social" />
      LOGIN VIA<br>
      FACEBOOK
    </a>
    <!--<a href="#" class="btn--course"><img src="/wp-content/uploads/2015/09/ico-google.png" alt="ico-google" width="41" height="41" class="ico-social" />LOGIN VIA<br>GOOGLE</a>-->
  <?php } else { ?>
    <span class="user-info">
      <strong>Logado como:</strong><br>
      <?php global $user_login;
        get_currentuserinfo();
        echo $user_login;
      ?>
    </span>
    <a href="<?php echo wp_logout_url(home_url()); ?>" class="btn--course btn--logout">Logout</a>
  <?php } ?>
</div>