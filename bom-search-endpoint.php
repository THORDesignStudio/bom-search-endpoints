<?php

/**
 * Plugin Name: 	Business Officer Search Endpoint
 * Plugin URI:		http://www.thor-studio.com/
 * Description:	  Custom post-types, menus, filters, image-sizing and taxonomies for the Business Officer wordpress site.
 * Version:		    0.1.0
 * Author:			  John Serrao
 * Author URI:		https://www.thor.design
 * License:		    CC Attribution-ShareAlike License
 * License URI:	  https://creativecommons.org/licenses/by-sa/4.0/
 * 
 * #################################
 * ########### Changelog ###########
 * #################################
 * 
 * 0.1.0: Setup custom endpoint for all site articles, all issues
 * 
 */

  // Extends existing WP_REST_Controller
  class All_Posts_Controller extends WP_REST_Controller {

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {
      $namespace = 'bom/v1';
      $path = 'all-content';

      register_rest_route( $namespace, '/' . $path, [
        array(
          'methods'             =>  WP_REST_Server::READABLE,
          'callback'            =>  array( $this, 'get_items' ),
          'permission_callback' =>  array( $this, 'get_items_permissions_check' )
        )
      ]);    
    }


    /**
     * Register REST server
     */
    public function instantiate_rest_server(){
      add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }


    /**
     * Check if a given request has access to get items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function get_items_permissions_check( $request ) {
      return true;
    }


    /**
     * Get a collection of all items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_items( $request ) {

      // Setup response array to hold all results
      $response = [];
      $count = 0;
      $count_notes = 0;

      // NACUBO NOTES
      $nacubo_notes_array = [];
      $nacubo_notes_query = new WP_Query( array(
        'posts_per_page'   => -1,
        'post_type'        => 'nacubo-notes',
        'orderby'          => 'title'
      ) );

      // Make sure query has content
      if ($nacubo_notes_query->have_posts()) {

        // If query has content, run loop over issue item
        while($nacubo_notes_query->have_posts()) {
          $nacubo_notes_query->the_post();

          // Increment counter
          $count++;
          $nacubo_notes_key = 'nacubo-notes-' . (string)$count; 

          // NACUBO NOTES TITLE
          $nacubo_notes_array[$nacubo_notes_key]['title'] = get_the_title();

          // NN DATE
          $nacubo_notes_array[$nacubo_notes_key]['publication-date'] = get_the_date('Y-m-d', $post);

          // NACUBO NOTES ISSUE
          $term = get_field('issue');
          $nacubo_notes_array[$nacubo_notes_key]['issue'] = $term->name;

          // NACUBO NOTES CONTENT
          if( have_rows('note_section') ) {
            while ( have_rows('note_section') ) : the_row();

              // Increment notes
              $count_notes++;
              
              //Make a key for each grouping of Nacubo Notes content
              $content_key = 'content-group-' . (string)$count_notes;  

              // NACUBO NOTES SUBTITLE
              $subtitle = get_sub_field('title');
              $nacubo_notes_array[$nacubo_notes_key][$content_key]['subtitle'] = $subtitle;
              
              // NACUBO NOTES CONTENT
              $main_content = get_sub_field('content');
              $nacubo_notes_array[$nacubo_notes_key][$content_key]['content'] = wp_strip_all_tags($main_content);

              // Cast $count_notes back to integer
              $count_notes = (int)$count_notes;

            endwhile;

            // Reset count_notes for next node
            $count_notes = 0;
          }

          // Reset counter back into an integer
          $count = (int)$count;
        } 

        return new WP_REST_Response($nacubo_notes_array, 200);
      } 
      
      // Error handling
      else {
        return new WP_Error( 
          'empty_category', 
          'No posts match the all posts group. Retry your API call.', 
          array(
            'status' => 404
          ) 
        );
      }
    }
  }

  // Instantiate controller class we just created
  $new_server = new All_Posts_Controller();
  $new_server->instantiate_rest_server();
?>