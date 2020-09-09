<?php

add_action('rest_api_init', 'universityLikeRoutes');

function universityLikeRoutes() {
  register_rest_route('university/v1', 'manageLike', array(
    'methods' => 'POST',
    'callback' => 'createLike'
  ));

  register_rest_route('university/v1', 'manageLike', array(
    'methods' => 'DELETE',
    'callback' => 'deleteLike'
  ));
}

function createLike($data) {
  if (is_user_logged_in()) {    // If the user has logged in 
    $professor = sanitize_text_field($data['professorId']);

    $existQuery = new WP_Query(array(
      'author' => get_current_user_id(),
      'post_type' => 'like',
      'meta_query' => array(
        array(
          'key' => 'liked_professor_id',
          'compare' => '=',
          'value' => $professor
        )
      )
    ));

    // Enforcing to be able to like each professor once for each user
    if ($existQuery->found_posts == 0 AND get_post_type($professor) == 'professor') {    // If the current user has not liked the current professor
      return wp_insert_post(array(
            'post_type' => 'like',
            'post_status' => 'publish',
            'post_title' => 'Like Post Type',
            'meta_input' => array(
                              'liked_professor_id' => $professor
                            )
      ));
    } else { 
      die("Invalid professor ID");
    }
    
  } else {  // If the user has NOT logged in
    die("Only logged in user can create a like.");
  }

}

function deleteLike($data) {
  $likeId = sanitize_text_field($data['like']);   // The $data['like'] is passed when sent from the Like.js file
  
  if (get_current_user_id() == get_post_field('post_author', $likeId) AND 
    get_post_type($likeId) == 'like') {
      wp_delete_post($likeId, true);
      return "Congrats, you just deleted a like.";
  } else {
    die("You do not have permission to delete that.");
  }

}

?>