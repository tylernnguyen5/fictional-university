<?php
get_header();

$homepageEvents = new WP_Query(array(
  'posts_per_page' => 2,
  'post_type' => 'event'
));

while (have_posts()) {
  the_post(); 
  pageBanner();
  ?>

  <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
      <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Programs</a> <span class="metabox__main"><?php the_title() ?></span></p>
    </div>

    <div class="generic-content"><?php the_field('main_body_content') ?></div>

    <?php

    // Professor(s)
    $relatedProfessors = new WP_Query(array(
      'posts_per_page' => -1,   // All posts returned if set to -1
      'post_type' => 'professor',
      'orderby' => 'title',  // or just 'meta_value'
      'order' => 'ASC',
      'meta_query' => array(
        array(  // Filters for event(s) that relates to the current program
          'key' => 'related_programs',
          'compare' => 'LIKE',  // == contains
          'value' => '"' . get_the_ID() . '"'
        )
      )
    ));

    if ($relatedProfessors->have_posts()) {
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">' . get_the_title() . ' Professors</h2>';
      
      echo '<ul class="professor-cards">';
      while ($relatedProfessors->have_posts()) {
        $relatedProfessors->the_post(); ?>

        <li class="professor-card__list-item">
          <a class="professor-card" href="<?php the_permalink() ?>">
            <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape') ?>">
            <span class="professor-card__name"><?php the_title() ?></span>
          </a>
        </li>

      <?php }
      echo '</ul>';
    }

    wp_reset_postdata();

    // Upcoming Events
    $today = date('Ymd');
    $homepageEvents = new WP_Query(array(
      'posts_per_page' => 2,   // All posts returned if set to -1
      'post_type' => 'event',
      'meta_key' => 'event_date',
      'orderby' => 'meta_value_num',  // or just 'meta_value'
      'order' => 'ASC',
      'meta_query' => array(
        array(  // Filters for upcoming event(s)
          'key' => 'event_date',
          'compare' => '>=',
          'value' => $today,
          'type' => 'numeric'
        ),
        array(  // Filters for event(s) that relates to the current program
          'key' => 'related_programs',
          'compare' => 'LIKE',  // == contains
          'value' => '"' . get_the_ID() . '"'
        )
      )
    ));

    if ($homepageEvents->have_posts()) {
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';

      while ($homepageEvents->have_posts()) {
        $homepageEvents->the_post();
        get_template_part('template-parts/content-event');
      }
    }

    wp_reset_postdata();

    // Related Campus(es)
    $relatedCampuses = get_field('related_campuses');

    if ($relatedCampuses) {
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">' . get_the_title() . ' is available at these campuses:</h2>';

      echo '<ul class="min-list link-list">';
      foreach ($relatedCampuses as $campus) {
        ?> <li><a href="<?php echo get_the_permalink($campus) ?>"><?php echo get_the_title($campus) ?></a></li> <?php
      }
      echo '</ul>';
    }


    ?>

  </div>

<?php }

get_footer();
?>