<?PHP
header('Content-type: application/json');

$path = $_SERVER['DOCUMENT_ROOT'];

define('WP_USE_THEMES', false);
require($path.'/wp-blog-header.php');
header("HTTP/1.1 200 OK");

$data = array();

$args = array( 
  'post_type'      => 'unidades',
  'post_status'    => 'publish', 
  'posts_per_page' => -1,
  'no_found_rows'  => true
);

query_posts($args);

$output = array();
$i = 0;
    
  if ( have_posts() ) : while ( have_posts() ) : the_post();
  
    $title = get_the_title();
    $link = get_the_permalink();
    $fone = get_post_custom_values('telefone');
    $endereco = get_post_custom_values('endereco');
    $cep = get_post_custom_values('cep');
    $bairro = get_post_custom_values('bairro');
    $cidade = get_post_custom_values('cidade');
    $location = get_field('mapa');

    $data[$i] = array(
        'unidade'   => $title,
        'link'      => $link,
        'fone'      => $fone,
        'endereco'  => $endereco,
        'cep'       => $cep,
        'bairro'    => $bairro,
        'cidade'    => $cidade,
        'lat'       => round($location['lat'],5),
        'lng'       => round($location['lng'],5)
      );
      
    $i++;
    
  endwhile; endif;
  wp_reset_postdata();

  echo json_encode($data);
?>