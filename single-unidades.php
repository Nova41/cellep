<?php get_header(); ?>

		
		<header class="page-header">
      <h1 class="page-title">Unidades</h1>
    </header><!--
    
  --><section class="content-grid">
      <article class="content">
		    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		    
		    <p>Digite o seu CEP e encontre a nossa unidade mais próxima de você.</p>

        <?php echo do_shortcode('[contact-form-7 id="368" title="Unidades"]'); ?>
    		
    		<h2 class="title-units"><?php $cidade = get_post_custom_values('cidade');echo $cidade[0];?> </h2>
			
  			<div class="infobox__grid infobox--open">
    			<div class="infobox infobox--open">
    				
    				<h3 class="infobox__title"><?php the_title(); ?></h3>
    				
    				<div class="infobox__content ajax-content">
    					
    					<div class="grid-interno">
    					  <div class="grid-interno__item units__desc">
        					<p>
        					  <?php $fone = get_post_custom_values('telefone');echo $fone[0];?><br>
        					  <?php $endereco = get_post_custom_values('endereco');echo $endereco[0];?><br>
        					  <?php $cep = get_post_custom_values('cep');echo $cep[0];?><br>
        					  <?php $bairro = get_post_custom_values('bairro');echo $bairro[0];?>
        					 - <?php $cidade = get_post_custom_values('cidade');echo $cidade[0];?><br>
        					 <?php $horario = get_post_custom_values('horario');echo $horario[0];?><br>
        					</p>
      					</div><!--
      					
      					<?php 
      						$location = get_field('mapa');
      						if( !empty($location) ):
      					?>
      					
      			 --><div class="grid-interno__item units__map">
      						<div class="acf-map">
      							<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
      						</div>
      					</div>				
    					</div>
    				</div>
    			</div>
  			</div>
			
  			<?php endif; ?>
		    <?php endwhile; ?>
    		
    		<button class="btn btn--voltar">voltar</button>
    	</article>
    </section>

<?php get_footer(); ?>