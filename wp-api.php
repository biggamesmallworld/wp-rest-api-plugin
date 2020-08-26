<?php 
/**
* Plugin Name: REST API Customizer
* Plugin URI: https://www.willnahmens.com
* Version 1.0.0
* Author: Will Nahmens
* Author URI: https://www.willnahmens.com
*/


function bbjo_posts() {
    $args = [
        'numberposts' => 99,
        'post_type' => 'post'
    ];

    $posts = get_posts($args);

    $data = [];

    $i = 0;

    foreach($posts as $post) {
        $data[$i]['len_posts'] = count($posts);
        $data[$i]['id'] = $post->ID;
        $data[$i]['title'] = $post->post_title;
        $data[$i]['content'] = $post->post_content;
        $data[$i]['slug'] = $post->post_name;
        $data[$i]['featured_image']['thumbnail'] = get_the_post_thumbnail_url($post->ID, 'thumbnail'); 
        $data[$i]['featured_image']['medium'] = get_the_post_thumbnail_url($post->ID, 'medium');
        $data[$i]['featured_image']['large'] = get_the_post_thumbnail_url($post->ID, 'large'); 
        $i++;
        
    }

    return $data;

}

function bbjo_post($slug) {

    $args = [
        'name' => $slug['slug'],
        'post_type' => 'post',
    ];

    $post = get_posts($args);

    $data = [];

    $data['len_posts'] = count($posts);
    $data['id'] = $post[0]->ID;
    $data['title'] = $post[0]->post_title;
    $data['content'] = $post[0]->post_content;
    $data['slug'] = $post[0]->post_name;
    $data['featured_image']['thumbnail'] = get_the_post_thumbnail_url($post[0]->ID, 'thumbnail'); 
    $data['featured_image']['medium'] = get_the_post_thumbnail_url($post[0]->ID, 'medium');
    $data['featured_image']['large'] = get_the_post_thumbnail_url($post[0]->ID, 'large'); 

    return $data;

}

function bbjo_get_prof_by_id($user_name) {

    $user = get_user_by('slug', $user_name['user_name']);

    $args = [
        'post_type' => 'players',
        'author' => $user->ID,
    ];

    $post = get_posts($args);

    $data = [];

    $data['id'] = $post[0]->ID;
    $data['title'] = $post[0]->post_title;
    /*$data['content'] = $post[0]->post_content;
    $data['slug'] = $post[0]->post_name;
    $data['featured_image']['thumbnail'] = get_the_post_thumbnail_url($post[0]->ID, 'thumbnail'); 
    $data['featured_image']['medium'] = get_the_post_thumbnail_url($post[0]->ID, 'medium');
    $data['featured_image']['large'] = get_the_post_thumbnail_url($post[0]->ID, 'large'); 
    $data['first_name'] = get_field('first_name', $post[0]->ID);
    $data['last_name'] = get_field('last_name', $post[0]->ID);
    $data['primary_position'] = get_field('player_position', $post[0]->ID);*/


    return $data;

}



add_action('rest_api_init', function() {
    register_rest_route('bbjo/v1', 'posts', [
        'methods' => 'GET',
        'callback' => 'bbjo_posts',
    ]);

    register_rest_route('bbjo/v1', 'posts/(?P<slug>[a-zA-Z0-9-]+)', [
        'methods' => 'GET',
        'callback' => 'bbjo_post',
    ]);

    register_rest_route('bbjo/v1', 'profiles/(?P<user_name>[a-zA-Z0-9-]+)', [
        'methods' => WP_REST_Server::ALLMETHODS,
        'callback' => 'bbjo_get_prof_by_id',
    ]);
});