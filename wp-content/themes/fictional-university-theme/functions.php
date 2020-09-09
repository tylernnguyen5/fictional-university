<?php

require get_theme_file_path('/inc/search-route.php');
require get_theme_file_path('/inc/like-route.php');

// ==============================================================

add_action('rest_api_init', 'universit_custom_rest');

function universit_custom_rest()
{
  // Add author name to the response
  register_rest_field('post', 'authorName', array(
    'get_callback' => function () {
      return get_the_author();
    }
  ));
  
  // Add user's notes count to the repsonse
  register_rest_field('note', 'userNoteCount', array(
    'get_callback' => function () {
      return count_user_posts(get_current_user_id(), 'note');
    }
  ));
}


// ==============================================================

function pageBanner($args = NULL)
{

  if (!isset($args['title'])) {
    $args['title'] = get_the_title();
  }

  if (!isset($args['subtitle'])) {
    $args['subtitle'] = get_field('page_banner_subtitle');
  }

  if (!isset($args['photo'])) {
    if (get_field('page_banner_background_image') and !is_archive() and !is_home()) {
      $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
    } else {
      $args['photo'] = get_theme_file_uri("/images/ocean.jpg");
    }
  }
?>

  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo'] ?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle'] ?></p>
      </div>
    </div>
  </div>

<?php }

// ==============================================================

add_action('wp_enqueue_scripts', 'university_files');

function university_files()
{
  wp_enqueue_style('custom-google-font', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

  wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyA-_c1xHDlkgde984cJ3-YRQaqopbI1L48', NULL, '1.0', true);


  if (strstr($_SERVER['SERVER_NAME'], 'fictional-university.local')) {    // If the current domain WP matches, that means it's running locally on a personally computer in a private sandbox
    wp_enqueue_script('main-university-js', 'http://localhost:3000/bundled.js', NULL, '1.0', true);
  } else {  // Running live on a public domain
    wp_enqueue_script('our-vendors-js', get_theme_file_uri('/bundled-assets/vendors~scripts.9678b4003190d41dd438.js'), NULL, '1.0', true);
    wp_enqueue_script('main-university-js', get_theme_file_uri('/bundled-assets/scripts.09d5ee7db4811ad83753.js'), NULL, '1.0', true);
    wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.09d5ee7db4811ad83753.css'));
  }

  // Set up dynamic root url
  wp_localize_script('main-university-js', 'universityData', array(
    'root_url' => get_site_url(),
    'nonce' => wp_create_nonce('wp_rest')
  ));
}

// ==============================================================

add_action('after_setup_theme', 'university_features');

function university_features()
{
  // register_nav_menu('headerMenuLocation', 'Header Menu Location');
  // register_nav_menu('footerLocationOne', 'Footer Location One');
  // register_nav_menu('footerLocationTwo', 'Footer Location Two');

  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_image_size('professorLandscape', 400, 260, true);
  add_image_size('professorPortrait', 480, 650, true);
  add_image_size('pageBanner', 1500, 350, true);
}

// ==============================================================

add_action('pre_get_posts', 'university_adjust_queries');

function university_adjust_queries($query)
{  // These are meant for archive-campus.php, archive-program.php and archive-event.php

  if (!is_admin() and is_post_type_archive('campus') and is_main_query()) {  // Only for the front-end, 'campus' post-typed and is manipulating a default query
    $query->set('posts_per_page', -1);  // On default, the query will pull in 10 records. Setting this to -1 to ensure all data will be returned
  }

  if (!is_admin() and is_post_type_archive('program') and is_main_query()) {  // Only for the front-end, 'program' post-typed and is manipulating a default query
    $query->set('orderby', 'title');
    $query->set('order', 'ASC');
    $query->set('posts_per_page', -1);
  }

  if (!is_admin() and is_post_type_archive('event') and is_main_query()) {  // Only for the front-end, 'event' post-typed and is manipulating a default query
    $today = date('Ymd');

    // Only shows upcoming event and excludes any events in the past
    $query->set('meta_key', 'event_date');
    $query->set('orderby', 'meta_value_num');
    $query->set('order', 'ASC');
    $query->set('meta_query', array(
      'key' => 'event_date',
      'compare' => '>=',
      'value' => $today,
      'type' => 'numeric'
    ));
  }
}

// ==============================================================

add_filter('acf/fields/google_map/api', 'universityMapKey');

function universityMapKey($api)
{
  $api['key'] = "AIzaSyA-_c1xHDlkgde984cJ3-YRQaqopbI1L48";

  return $api;
}

// ==============================================================

// Redirect subscriber accounts out of admin and onto homepage
add_action('admin_init', 'redirectSubsToFrontend');

function redirectSubsToFrontend()
{
  $ourCurrentUser = wp_get_current_user();

  if (count($ourCurrentUser->roles) == 1 and $ourCurrentUser->roles[0] == 'subscriber') {
    wp_redirect(site_url('/'));
    exit;
  }
}

// No admin bar for subscribers
add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar()
{
  $ourCurrentUser = wp_get_current_user();

  if (count($ourCurrentUser->roles) == 1 and $ourCurrentUser->roles[0] == 'subscriber') {
    show_admin_bar(false);
  }
}

// ==============================================================

// Customizing login screen

add_filter('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl()
{
  return esc_url(site_url('/'));
}

add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS()
{
  wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.09d5ee7db4811ad83753.css'));
  wp_enqueue_style('custom-google-font', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
}

add_filter('login_headertitle', 'ourLoginTitle');

function ourLoginTitle() { return get_bloginfo('name'); }

// ==============================================================

// Force note posts to be private
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);    // This wp hook applies when the user either creates or updates a post
                                                                // '10' is for specifying the priority. '2' is for specifying that our function is working with 2 parameters 

function makeNotePrivate($data, $postarr) {
  if ($data['post_type'] == 'note') {
    if (count_user_posts(get_current_user_id(), 'note') > 4 AND !$postarr['ID']) {    // [NOTE] A brand-new note does not have an ID until it is created
      die("You have reached your note limit.");
    }

    $data['post_content'] = sanitize_textarea_field($data['post_content']);
    $data['post_title'] = sanitize_textarea_field($data['post_title']);
  }

  if ($data['post_type'] == 'note' AND $data['post_status'] != 'trash') {
    $data['post_status'] = 'private';
  }

  return $data;
}

?>
