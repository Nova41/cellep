<?php get_header(); ?>

  <div class="flexfix"><div class="slider-grid grid-tiles__item">
    <section class="slider corte loading">
      <?php
      
        $slides = new WP_Query(array(
            'post_type'      => 'slide',
            'slider'         => 'home',
            'posts_per_page' => -1,
            'no_found_rows'  => true,
            'orderby'        => 'menu_order',
      	    'order'          => 'ASC',
          ));
          
        if ( $slides->have_posts() ) : while ( $slides->have_posts() ) : $slides->the_post();
        
          $type = get_field('tipo');
          
          switch ($type) {
            case 'Imagem':
              $image = get_field('imagem');
              $link = get_field('link');
              echo '<a href="'.$link.'"><img src="'.$image['url'].'"></a>';
              break;
            case 'Youtube':
              $video = get_field('youtube');
              parse_str(parse_url( $video, PHP_URL_QUERY ));
              $image = 'http://img.youtube.com/vi/'.$v.'/hqdefault.jpg';
              echo '<a class="video" href="'.$video.'"><img src="'.$image.'"></a>';
              break;
          }
        
        endwhile; endif;
        wp_reset_postdata();
      
      ?>
    </section><!-- /.slide -->
  </div><!-- /.slide-grid
  
  
--><div class="boxes-grid grid-tiles__item">
    <div class="boxes">
      <div class="grid-tiles">
        
        
      <?php
      $perfis = new WP_Query( array(
          'post_type'      => 'page',
          'home-box'       => 'show',
          'posts_per_page' => -1,
          'no_found_rows'  => true,
          'orderby'        => 'menu_order',
    	    'order'          => 'ASC'
        ) );
        
      if ( $perfis->have_posts() ) : while ( $perfis->have_posts() ) : $perfis->the_post();
        $slug = $post->post_name;
      ?>
        
          <section class="box box--<?php echo $slug; ?> grid-tiles__item corte">
            <div class="grid-interno perfil-grid">
              <header class="box__header grid-interno__item corte">
                <a href="#<?php  echo $slug; ?>" class="box__link">
                  <h2 class="box__title">
                    <?php the_title(); ?>
                  </h2>
                </a>
                <div class="box__course">
                <?php 
                  if ($slug === "para-mim") {
                      echo do_shortcode('[getpage slug="curso-para-adultos" template="simple"]');
                  } else if (($slug === "para-minha-escola") || ($slug == "para-minha-familia")) {
                      echo do_shortcode('[getpage slug="cursos-para-criancas-e-adolescentes" template="simple"]');
                  } else if ($slug === "para-minha-empresa") {
                      echo do_shortcode('[getpage slug="cursos-para-empresas" template="simple"]');
                  }
                ?>
                
                <?php get_template_part('login-registro'); ?>
                </div>
              </header><!--
     
           --><div class="content grid-interno__item loading"></div><!--
              
           --><aside class="box__aside grid-interno__item">
                
                <?php
                $args = array(
                  'showposts'     => -1,
                  'post_type'     => 'page',
                  'indice'        => 'show',
                  'post_parent'   => $perfis->post->ID,
                  'no_found_rows' => true,
                  'orderby'       => 'menu_order',
            	    'order'         => 'ASC'
                );
                $parent = new WP_Query($args);
                if ( $parent->have_posts() ) : while ( $parent->have_posts() ) : $parent->the_post();
                  $slug = $post->post_name;
                ?>
                
                  <a href="#<?php echo $slug; ?>" class="box__anchor box__anchor--<?php echo $slug; ?>  btn">
                    <?php the_title(); ?>
                  </a>
                
                <?php  
                endwhile; endif;
                wp_reset_postdata();
                ?>
                  
              </aside>
            </div>
          </section>
          
      <?php
      endwhile; endif;
      wp_reset_postdata();
      ?>
      
      </div><!-- /.grid-tiles -->
    </div><!-- /.boxes -->
  </div></div><!-- /.boxes-grid
  
  
--><div class="agende-grid grid-tiles__item">
    <section class="box box--agende-sua-visita grid-tiles__item corte">
      <div class="grid-interno perfil-grid">
        <header class="box__header grid-interno__item corte">
          <a href="#agende-sua-visita" class="box__link">
            <h2 class="box__title">
              Agende <span>sua</span> visita
            </h2>
          </a>
          <div class="box__course">
            <?php get_template_part('login-registro'); ?>
          </div>
        </header><!--
     
     --><div class="content grid-interno__item loading"></div>
      </div>
    </section>
  </div><!-- /.agende-grid 
  
  --><div class="institucional-grid grid-tiles__item">
      <section class="box box--institucional grid-tiles__item corte">
        <div class="grid-interno perfil-grid">
          <header class="box__header grid-interno__item corte">
            <a href="" class="box__link">
              <h2 class="box__title">
                Trabalhe <span>conosco</span>
              </h2>
            </a>
            <div class="box__course">
              <?php get_template_part('login-registro'); ?>
            </div>
          </header><!--
       
       --><div class="content grid-interno__item loading"></div><!--
            
       --><aside class="page-aside">
            <div class="timeline">
              
            <?php
              $timeline = new WP_Query( array(
                'pagename' => 'sobre-a-gente/nossa-historia'
              ) );
              
              if ( $timeline->have_posts() ) : while ( $timeline->have_posts() ) : $timeline->the_post();
                
                echo '<h2 class="page-title corte">'.get_the_title().'</h2>';
                the_content();
            
              endwhile; endif;
              wp_reset_postdata();
            ?>
              
            </div>
          </aside>
          
        </div>
      </section>
    </div><!-- /.institucionais-grid -->
   
<?php get_footer(); ?>