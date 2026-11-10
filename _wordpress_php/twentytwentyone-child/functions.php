<?php
// tell wp via api to call our function to include parent theme css file
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

// our function for parent theme css
function enqueue_parent_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}

// tell wp via api to call our function to register new REST endpoint URL
add_action('rest_api_init', 'register_my_route');

// our function for new REST endpoint URL
function register_my_route() {
    register_rest_route( 
        'twentytwentyone-child/v1', 
        '/latest-posts/(?P<category_id>\d+)',
        array(
            'methods'  => 'GET',
            'callback' => 'get_latest_posts_by_category'
        )
    );
}

// our function that returns content for the new REST endpoint URL
function get_latest_posts_by_category($request) {
    $args = array(
        'category' => $request['category_id']
    );

    $posts = get_posts($args);

    if (empty($posts)) {
        return new WP_Error( 'empty_category', 'There are no posts to display', array('status' => 404) );

    }

    $response = new WP_REST_Response($posts);
    $response->set_status(200);

    return $response;
}
?>