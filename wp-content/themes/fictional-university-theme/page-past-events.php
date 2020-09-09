<?php
get_header();
pageBanner(array(
  'title' => 'Past Events',
  'subtitle' => 'A recap of our past events'
));
?>


<div class="container container--narrow page-section">
  <?php
  $today = date('Ymd');
  
  $pastEvents = new WP_Query(array(
    'paged' => get_query_var('paged', 1),   // This tells the custom query which page number of results it should be on. Default is 1, if cant 'paged'
    'post_type' => 'event',
    'meta_key' => 'event_date',
    'orderby' => 'meta_value_num',  // or just 'meta_value'
    'order' => 'ASC',
    'meta_query' => array(
      array(
        'key' => 'event_date',
        'compare' => '<',
        'value' => $today,
        'type' => 'numeric'
      )
    )
  ));

  while ($pastEvents->have_posts()) {
    $pastEvents->the_post();
    get_template_part('template-parts/content-event');
  }
  
  echo paginate_links(array(
    'total' => $pastEvents->max_num_pages,
  ));

  // echo paginate_links();   // Only works with default URL-based query
  ?>
</div>

<?php get_footer(); ?>