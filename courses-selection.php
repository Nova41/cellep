<?php
  $page = get_page_by_path($partslug);
  $title = get_the_title($page->ID);
  $permalink = get_permalink($page->ID);
  $option = '<option value="'.$permalink.'">'.$title.'</option>';
  
  if ($current === 1) {
    echo '<div class="coursebox"><select name="selecao" class="coursebox__select">';
    echo $option;
  } else if ($current === $count) {
    echo $option;
    echo '</select>';
    echo '<div name="conteudo" class="coursebox__content corte"></div></div>';
  } else {
    echo $option;
  }
?>
    
  