<?php
  $page = get_page_by_path($partslug);
  $title = get_the_title($page->ID);
  $content = get_post_field('post_content', $page->ID);
  $permalink = get_permalink($page->ID);
  
  $grid = '<div class="infobox-grid">';
?>

<?php echo $current === 1 ? '<div class="grid-interno">'.$grid : '-->'.$grid; ?>
  
  <div data-url="<?php echo $permalink; ?>" class="infobox infobox--<?php echo basename(get_permalink()); ?> corte">
    <h3 class="infobox__title"><?php echo $title; ?></h3>
    <div class="infobox__content">
      <?php // echo $content; ?>
    </div>
  </div>

<?php echo $current === $count ? '</div></div><!-- /.grid-interno -->' : '</div><!--'; ?>