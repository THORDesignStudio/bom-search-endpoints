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

  
  /**
   * NACUBO NOTES
   * Extend WP_REST_Controller
   */
  class NN_Posts_Controller extends WP_REST_Controller {
  
    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {
      $namespace = 'bom/v1';
      $path = 'nacubo-notes';

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
      $nn_array = [];
      $count = 0;
      $count_notes = 0;

      // NACUBO NOTES
      $nacubo_notes_query = new WP_Query( array(
        'posts_per_page'   => -1,
        'post_type'        => 'nacubo-notes',
        'orderby'          => 'title',
        'order'            => 'ASC'
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
          $nn_array[$nacubo_notes_key]['title'] = get_the_title();

          // NN DATE
          $nn_array[$nacubo_notes_key]['publication-date'] = get_the_date('Y-m-d', $post);

          // NACUBO NOTES ISSUE
          $term = get_field('issue');
          $nn_array[$nacubo_notes_key]['issue'] = $term->name;

          // NACUBO NOTES DEPARTMENT
          $nn_array[$nacubo_notes_key]['category'] = 'nacubo-notes';

          // NACUBO NOTES CONTENT
          if( have_rows('note_section') ) {
            while ( have_rows('note_section') ) : the_row();

              // Increment notes
              $count_notes++;
              
              //Make a key for each grouping of Nacubo Notes content
              $content_key = 'content-group-' . (string)$count_notes;  

              // NACUBO NOTES SUBTITLE
              $subtitle = get_sub_field('title');
              $nn_array[$nacubo_notes_key][$content_key]['subtitle'] = $subtitle;
              
              // NACUBO NOTES CONTENT
              $main_content = get_sub_field('content');
              $nn_array[$nacubo_notes_key][$content_key]['content'] = wp_strip_all_tags($main_content);

              // Cast $count_notes back to integer
              $count_notes = (int)$count_notes;

            endwhile;

            // Reset count_notes for next node
            $count_notes = 0;
          }

          // Reset counter back into an integer
          $count = (int)$count;
        } 

        return new WP_REST_Response($nn_array, 200);
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
  $nn_posts_server = new NN_Posts_Controller();
  $nn_posts_server->instantiate_rest_server();


  /**
   * BUSINESS INTEL
   * Extend WP_REST_Controller
   */
  class BI_Posts_Controller extends WP_REST_Controller {
  
    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {
      $namespace = 'bom/v1';
      $path = 'business-intel';

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
      $bi_array = [];
      $count = 0;
      $count_notes = 0;

      // BIZ INTEL
      $business_intel_query = new WP_Query( array(
        'posts_per_page'   => -1,
        'post_type'        => 'business-intel',
        'orderby'          => 'title',
        'order'            => 'ASC'
      ) );

      // Make sure query has content
      if ($business_intel_query->have_posts()) {

        // If query has content, run loop over issue item
        while($business_intel_query->have_posts()) {
          $business_intel_query->the_post();

          // Increment counter
          $count++;
          $business_intel_key = 'business-intel-' . (string)$count; 

          // BIZ INTEL PERMALINK
          $bi_array[$business_intel_key]['url'] = str_replace('\/', '/', get_the_permalink());

          // BIZ INTEL TITLE
          $bi_array[$business_intel_key]['title'] = get_the_title();

          // BIZ INTEL DATE
          $bi_array[$business_intel_key]['publication-date'] = get_the_date('Y-m-d', $post);

          // BIZ INTEL ISSUE
          $term = get_field('issue');
          $bi_array[$business_intel_key]['issue'] = $term->name;

          // BIZ INTEL DEPARTMENT
          $bi_array[$nacubo_notes_key]['category'] = 'business-intel';

          // BIZ INTEL CONTENT
          if( have_rows('note_section') ) {
            while ( have_rows('note_section') ) : the_row();

              // Increment notes
              $count_notes++;
              
              //Make a key for each grouping of BIZ INTEL content
              $content_key = 'content-group-' . (string)$count_notes;  

              // BIZ INTEL SUBTITLE
              $subtitle = get_sub_field('kicker');
              $bi_array[$business_intel_key][$content_key]['subtitle'] = $subtitle;
              
              // BIZ INTEL CONTENT
              $main_content = get_sub_field('content');
              $bi_array[$business_intel_key][$content_key]['content'] = preg_replace('/%u([0-9a-f]+)/', '&#x$1;', wp_strip_all_tags($main_content));

              // Cast $count_notes back to integer
              $count_notes = (int)$count_notes;

            endwhile;

            // Reset count_notes for next node
            $count_notes = 0;
          }

          // BIZ INTEL FAST FACTS
          $bi_array[$business_intel_key]['fast-fact'] = sanitize_text_field(wp_strip_all_tags(get_field('fast_fact'))); 

          // BIZ INTEL QUICK CLICKS
          $bi_array[$business_intel_key]['quick-clicks'] = sanitize_text_field(wp_strip_all_tags(get_field('quick_clicks'))); 

          // Reset counter back into an integer
          $count = (int)$count;
        } 

        return new WP_REST_Response($bi_array, 200);
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
  $bi_posts_server = new BI_Posts_Controller();
  $bi_posts_server->instantiate_rest_server();


  /**
   * DEPARTMENTS
   * Extend WP_REST_Controller
   */
  class Departments_Posts_Controller extends WP_REST_Controller {
  
    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {
      $namespace = 'bom/v1';
      $path = 'departments';

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
      $departments_array = [];
      $count = 0;
      $count_topics = 0;
      $count_sidebars = 0;
      $count_pullquotes = 0;

      // DEPARTMENTS QUERy
      $departments_query = new WP_Query( array(
        'posts_per_page'   => -1,
        'post_type'        => 'departments',
        'orderby'          => 'title',
        'order'            => 'ASC'
      ) );

      // Make sure query has content
      if ($departments_query->have_posts()) {

        // If query has content, run loop over issue item
        while($departments_query->have_posts()) {
          $departments_query->the_post();

          // Increment counter
          $count++;
          $departments_key = 'departments-' . (string)$count; 

          // DEPARTMENTS TITLE
          $departments_array[$departments_key]['title'] = get_the_title();

          // DEPARTMENTS SUBTITLE
          $departments_subtitle = get_field('subtitle');
          $departments_array[$departments_key]['subtitle'] = get_the_title();

          // DEPARTMENTS DATE
          $departments_array[$departments_key]['publication-date'] = get_the_date('Y-m-d', $post);

          // DEPARTMENTS ISSUE
          $department_issue = get_field('issue');
          $departments_array[$departments_key]['issue'] = $department_issue->name;

          // DEPARTMENTS DEPARTMENT
          // we're calling it category in the endpoint for consistency
          $department_term = get_field('department');
          $departments_array[$departments_key]['category'] = $department_term->name;

          // DEPARTMENTS AUTHOR
          $department_author_field = get_field('author');
          $department_author_field_final = "By "; // a string we'll add onto
        
          if ($department_author_field) {
            $users_count = count($department_author_field);
        
            for ($i = 0; $i < $users_count; $i++) {
              // If there is only one author OR it's the last author, print name only
              if ( ($i == 0 && $i == ($users_count - 1) ) || $i == ($users_count - 1)) {
                $department_author_field_final = $department_author_field_final . $department_author_field[$i]['display_name'];
              } else {
                $department_author_field_final = $department_author_field_final . $department_author_field[$i]['display_name'] . ' and ';
              }
            }
          } else {
            $department_author_field_final = $department_author_field_final . get_field('one_time_author');
          }
          $departments_array[$departments_key]['author'] = $department_author_field_final;

          // DEPARTMENT TOPICS
          $department_topic_objects = get_field('topics');

          // Loop over each department topic, adding into the big array we'll output as the API response
          if ($department_topic_objects) {
            foreach ($department_topic_objects as $topic_object) {
              $count_topics++;
              $topic_key = 'topic-' . (string)$count_topics;  
              $departments_array[$departments_key]['topic-group'][$topic_key] = $topic_object->name;
              $count_topics = (int)$count_topics;
            }

            // Reset count_sidebars for next node
            $count_topics = 0;
          }

          // DEPARTMENTS CONTENT
          $department_content = get_field('main_content_block');
          $departments_array[$departments_key]['content'] = wp_strip_all_tags($department_content);

          // DEPARTMENTS SIDEBARS
          if( have_rows('sidebars') ) {
            while ( have_rows('sidebars') ) : the_row();

              // Increment sidebars
              $count_sidebars++;
              
              //Make a key for each grouping of DEPARTMENTS content
              $sidebar_key = 'sidebar-group-' . (string)$count_sidebars;  

              // DEPARTMENTS SUBTITLE
              $sidebar_subtitle = get_sub_field('sidebar_title');
              $departments_array[$departments_key][$sidebar_key]['subtitle'] = $sidebar_subtitle;
              
              // DEPARTMENTS CONTENT
              $sidebar_main_content = get_sub_field('sidebar_body');
              $departments_array[$departments_key][$sidebar_key]['content'] = wp_strip_all_tags($sidebar_main_content);

              // Cast $count_sidebars back to integer
              $count_sidebars = (int)$count_sidebars;

            endwhile;

            // Reset count_sidebars for next node
            $count_sidebars = 0;
          }

          // DEPARTMENTS PULL QUOTES
          if( have_rows('pull_quotes') ) {
            while ( have_rows('pull_quotes') ) : the_row();

              // Increment notes
              $count_pullquotes++;
              
              //Make a key for each grouping of DEPARTMENTS content
              $pullquotes_key = 'pullquote-group-' . (string)$count_pullquotes;  

              // DEPARTMENTS SUBTITLE
              $pullquotes_subtitle = get_sub_field('pull_quote_text');
              $departments_array[$departments_key][$pullquotes_key]['content'] = $pullquotes_subtitle;
              
              // DEPARTMENTS CONTENT
              $pullquotes_main_content = get_sub_field('pull_quote_attribution');
              $departments_array[$departments_key][$pullquotes_key]['author'] = wp_strip_all_tags($pullquotes_main_content);

              // Cast $count_pullquotes back to integer
              $count_pullquotes = (int)$count_pullquotes;

            endwhile;

            // Reset count_pullquotes for next node
            $count_pullquotes = 0;
          }


          // Reset counter back into an integer
          $count = (int)$count;
        } 

        return new WP_REST_Response($departments_array, 200);
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
  $departments_posts_server = new Departments_Posts_Controller();
  $departments_posts_server->instantiate_rest_server();
?>