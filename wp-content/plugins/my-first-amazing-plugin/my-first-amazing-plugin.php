<?php 

/*
Plugin name: My First Amazing Plugin
Description: This plugin helps display the number of programs dynamiclly on the About Us page.
*/ 

add_filter('the_content', 'amazingContentEdits');

function amazingContentEdits($content) {
  $content = str_replace('ipsum', '*****', $content);
  return $content;
}

add_shortcode('programCount', 'programCountFunc');

function programCountFunc() {
  $programs = new WP_Query(array(
    'post_type' => 'program',
    'post_per_page' => -1
  ));

  return count($programs->get_posts());
}