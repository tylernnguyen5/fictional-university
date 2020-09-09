<?php 
get_header(); 
pageBanner(array(
  'title' => 'All Events',
  'subtitle' => 'See what is going on in our world.',
  // 'photo' => 'https://images.unsplash.com/photo-1593642634367-d91a135587b5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1050&q=80'
));
?>


<div class="container container--narrow page-section">
  <?php
  while (have_posts()) {
    the_post(); 
    get_template_part('template-parts/content-event');
  }
  echo paginate_links();
  ?>

  <hr class="section-break">
  <p>Looking for a recap of past events? <a href="<?php echo site_url('/past-events')?>">Check out our past events archive</a></p>

</div>

<?php get_footer(); ?>