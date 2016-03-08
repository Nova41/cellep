<?php
  $page = get_page_by_path($partslug);
  $title = get_the_title($page->ID);
  $content = get_post_field('post_content', $page->ID);
  $permalink = get_permalink($page->ID);
?>
<div class="click-to-call__form">
    <a href="#" class="click-to-call close__form">X</a>
  <div class="content__form"><strong><h5><?php echo $title; ?></h5></strong><br /><?php echo $content; ?></div>
</div>
<div class="click-to-call-camp__form">
   <img src="/wp-content/themes/cellep/assets/images/logo.png"  class="logo-landing"/>
    <a href="#" class="click-to-call-camp close-camp__form">X</a>
    <div class="content-camp__form"><strong><h5><?php echo $title; ?></h5></strong><br /><?php echo $content; ?>
    <a href="#" class="btn btn__landing">Ir direto para o site</a></div>
</div>