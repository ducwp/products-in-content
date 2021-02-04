<?php
/*
Plugin Name: Products in content
Plugin URI: https://vlink.asia
Description: This plugin help you insert a list of products into your post content.
Version: 1.0
Author: Duc Nguyen
Author URI: https://www.facebook.com/ducwp
License: GPLv2 or later
Text Domain: products-in-content
*/

// Create Shortcode pi_content
// Use the shortcode: [pi_content ids="1,2,3,4,5"]
function create_picontent_shortcode($atts) {
	// Attributes
	$atts = shortcode_atts(
		array(
			'ids' => '',
		),
		$atts,
		'pi_content'
	);

	$ids = array_filter( explode(',', $atts['ids'] ) );
  $the_query = new WP_Query( array( 'post_type' => 'product', 'post__in' => $ids ) );

  $output = '';

  if ( $the_query->have_posts() ) {
      $output .= '<ul class="pi-content-products">';
      while ( $the_query->have_posts() ) {
          $the_query->the_post();
          $output .= '<li><a href="'. get_permalink() .'" title="'.the_title_attribute( 'echo=0' ).'" rel="bookmark">';
          $output .= get_the_post_thumbnail( $post_id, array(100, 100), array( 'class' => 'alignleft' ) );
          $output .= get_the_title();
          $rprice = get_post_meta( get_the_ID(), '_regular_price', true );
          $sprice = get_post_meta( get_the_ID(), '_sale_price', true );

          $html_price = ($sprice!=='') ? '<del>'.wc_price($rprice).'</del>'.wc_price($sprice) : wc_price($rprice);

          $output .= '<span class="pi-content-price">'.$html_price.'</span>';
          $output .=  '</a></li>';
      }
      $output .= '</ul>';
  } else {
      $output .= 'no posts found';
  }

  wp_reset_postdata();

  return $output;

}
add_shortcode( 'pi_content', 'create_picontent_shortcode' );


function pi_content_scripts() {

	wp_enqueue_style( 'pi-content-style', esc_url( plugins_url( 'css/style.css', __FILE__ ) ), array(), null );
}
add_action( 'wp_enqueue_scripts', 'pi_content_scripts', 30 );
