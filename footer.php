		</main><!-- /.main -->

    <footer class="footer" role="contentinfo">
      
      <div class="logo-cambridge">
  		  <img src="<?php bloginfo('template_url');?>/assets/images/cambridge-logo.png"></img>
  		</div>
      
      <nav class="nav nav--bottom" id="nav--bottom" role="navigation">
        <?php
          $walker = new data_type_walker();
  
          $menuParameters = array (
            'menu'            => 'menu-bottom',
            'menu_class'      => 'main-menu',
            'container'       => false,
            'depth'           => 2,
            'walker'          => $walker
          );
          wp_nav_menu($menuParameters);
        ?>
      </nav>
    </footer>

    <!-- WARNING: All scripts are included via functions.php -->

		<!--[if lt IE 9 ]>
		  <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
		  <script> /* global CFInstall */
		    window.attachEvent('onload',function(){
		      CFInstall.check({
		        mode:'overlay'
		      });
		    });
		  </script>
		<![endif]-->

		<?php wp_footer(); ?>

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
    <script> /* global ga */
        (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
        function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
        e=o.createElement(i);r=o.getElementsByTagName(i)[0];
        e.src='//www.google-analytics.com/analytics.js';
        r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
        ga('create','UA-42988139-1');ga('send','pageview');
    </script>

	</body>
</html>
