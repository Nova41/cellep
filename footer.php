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
    <!-- Google Code for Cellep Conversion Page
    In your html page, add the snippet and call
    goog_report_conversion when someone clicks on the
    chosen link or button. -->
    <!-- Google Code for Cadastro Conversion Page
    In your html page, add the snippet and call
    goog_report_conversion when someone clicks on the
    chosen link or button. -->
    
    <script type="text/javascript">
    /*global chama_func_email*/
    chama_func_email = function() {
      //alert();
      jQuery('.tag_email_read').load('/wp-content/themes/cellep/assets/js/functions/read_file.php');
      jQuery('.tag_email_read_email').load('/wp-content/themes/cellep/assets/js/functions/read_file_email.php');
      jQuery('.tag_email_write').load('/wp-content/themes/cellep/assets/js/functions/write_file_email.php');
    }
    /*global goog_snippet_vars*/
    /*global goog_report_conversion*/
      /* <![CDATA[ */
      goog_snippet_vars = function() {
        var w = window;
        w.google_conversion_id = 952506272;
        w.google_conversion_label = "p0J_CL72lVoQoK-YxgM";
        w.google_remarketing_only = false;
      }
      // DO NOT CHANGE THE CODE BELOW.
      goog_report_conversion = function(url) {
        goog_snippet_vars();
        window.google_conversion_format = "3";
        window.google_is_call = true;
        var opt = new Object();
        opt.onload_callback = function() {
        if (typeof(url) != 'undefined') {
          window.location = url;
        }
      }
      var conv_handler = window['google_trackConversion'];
      if (typeof(conv_handler) == 'function') {
        conv_handler(opt);
      }
      chama_func_email();
    }
    /* ]]> */
    </script>
    <script type="text/javascript"
      src="//www.googleadservices.com/pagead/conversion_async.js">
    </script>
    <div class="tag_email_read"></div>
    <div class="tag_email_read_email"></div>
    <div class="tag_email_write"></div>
	</body>
</html>