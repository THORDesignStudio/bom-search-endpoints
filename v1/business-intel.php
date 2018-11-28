<?php
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
          $bi_array[$business_intel_key]['url'] = get_the_permalink();

          // BIZ INTEL TITLE
          $bi_array[$business_intel_key]['title'] = get_the_title();

          // BIZ INTEL DATE
          $bi_array[$business_intel_key]['publication-date'] = get_the_date('Y-m-d', $post);

          // BIZ INTEL ISSUE
          $term = get_field('issue');
          $bi_array[$business_intel_key]['issue'] = $term->name;

          // BIZ INTEL DEPARTMENT
          $bi_array[$business_intel_key]['category'] = 'business-intel';

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
?>