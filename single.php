<?php get_header(); ?> 
 
  <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
  		
  <header class="page-header">
    <h1 class="page-title"><span class="clip"><?php the_title();?></span></h1>
  </header><!--
  
--><section class="content-grid">
    <article class="content">
  		<div class="ajax-content">
  			<?php the_content();?>
  		</div>
  		<button class="btn btn--voltar">voltar</button>
  	</article>
  </section>
  
  <?php endwhile; ?>

 
<?php get_footer(); ?>