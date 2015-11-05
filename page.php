<?php get_header(); ?> 
 
  <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
  		
  <header class="page-header">
    <h1 class="page-title"><span class="clip ajax-title"><?php the_title();?></span></h1>
  </header><!--
  
--><section class="content-grid">
    <article class="content">
  		<div class="ajax-content">
  			<?php the_content();?>
  		</div>
  	</article>
  </section><?php echo is_page('sobre-a-gente') ? '<!--' : ''; ?>
  
  <?php endwhile; ?>
  
  
  <?php if(is_page('sobre-a-gente')) { ?>
      
  --><aside class="page-aside">
    <div class="timeline">
      
    <?php
      $timeline = new WP_Query( array(
        'pagename' => 'sobre-a-gente/nossa-historia'
      ) );
      
      if ( $timeline->have_posts() ) : while ( $timeline->have_posts() ) : $timeline->the_post();
        
        echo '<h2 class="page-title page-title--mobile corte">'.get_the_title().'</h2>';
        the_content();
    
      endwhile; endif;
      wp_reset_postdata();
    ?>
      
    </div>
  </aside>
      
  <?php } ?>

 
<?php get_footer(); ?>