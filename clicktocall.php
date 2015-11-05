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