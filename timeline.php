<?php
  $page = get_page_by_path($partslug);
  $title = get_the_title($page->ID);
  $content = get_post_field('post_content', $page->ID);
  $permalink = get_permalink($page->ID);
?>
  
  <div class="timeline__wrapper <?php echo $current === 1 ? 'timeline--first-date' : ''; ?>">
  	<a class="timeline__item" href="<?php echo $permalink; ?>" title="<?php echo $title; ?>">
      <?php echo $title; ?>
    </a><!-- /.timeline__item -->
  </div><!-- /.timeline__wrapper -->