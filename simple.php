<?php
  $page = get_page_by_path($partslug);
  $title = get_the_title($page->ID);
  $content = apply_filters('the_content', get_post_field('post_content', $page->ID));
  $permalink = get_permalink($page->ID);
  
  if ($top) {
    echo '<h2 class="part-title" id="part--'.$partslug.'">'.$title.'</h2>';
  } else {
    echo '<h3 class="part-subtitle">'.$title.'</h3>';
  }
?>

  <?php echo $content ?>