<?php
  /**
   * FEATURES
   * Extend WP_REST_Controller
   */
  class Features_Posts_Controller extends WP_REST_Controller {
  
    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {
      $namespace = 'bom/v1';
      $path = 'features';

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
     * FEATURES
     * Get a collection of all items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_items( $request ) {

      // Setup response array to hold all results
      $features_array = [];
      $count = 0;
      $count_topics = 0;
      $count_sidebars = 0;
      $count_pullquotes = 0;

      // DEPARTMENTS QUERY
      $features_query = new WP_Query( array(
        'posts_per_page'   => -1,
        'post_type'        => 'post',
        'orderby'          => 'title',
        'order'            => 'ASC'
      ) );

      // Make sure query has content
      if ($features_query->have_posts()) {

        // If query has content, run loop over issue item
        while($features_query->have_posts()) {
          $features_query->the_post();

          // Increment counter
          $count++;
          $features_key = 'features-' . (string)$count; 

          // FEATURES TITLE
          $features_array[$features_key]['title'] = get_the_title();

          // FEATURES SUBTITLE
          $features_subtitle = get_field('subtitle');
          $features_array[$features_key]['subtitle'] = get_the_title();

          // FEATURES DATE
          $features_array[$features_key]['publication-date'] = get_the_date('Y-m-d', $post);

          // FEATURES ISSUE
          $features_issue = get_field('issue');
          $features_array[$features_key]['issue'] = $features_issue->name;

          // FEATURES CATEGORY
          // we're calling it category in the endpoint for consistency
          $features_array[$features_key]['category'] = 'features';

          // FEATURES AUTHOR
          $features_author_field = get_field('author');
          $features_author_field_final = "By "; // a string we'll add onto
        
          if ($features_author_field) {
            $users_count = count($features_author_field);
        
            for ($i = 0; $i < $users_count; $i++) {
              // If there is only one author OR it's the last author, print name only
              if ( ($i == 0 && $i == ($users_count - 1) ) || $i == ($users_count - 1)) {
                $features_author_field_final = $features_author_field_final . $features_author_field[$i]['display_name'];
              } else {
                $features_author_field_final = $features_author_field_final . $features_author_field[$i]['display_name'] . ' and ';
              }
            }
          } else {
            $features_author_field_final = $features_author_field_final . get_field('one_time_author');
          }
          $features_array[$features_key]['author'] = $features_author_field_final;

          // DEPARTMENT TOPICS
          $features_topic_objects = get_field('topics');

          // Loop over each feature topic, adding into the big array we'll output as the API response
          if ($features_topic_objects) {
            foreach ($features_topic_objects as $topic_object) {
              $count_topics++;
              $topic_key = 'topic-' . (string)$count_topics;  
              $features_array[$features_key]['topic-group'][$topic_key] = $topic_object->name;
              $count_topics = (int)$count_topics;
            }

            // Reset count_sidebars for next node
            $count_topics = 0;
          }

          // FEATURES CONTENT
          $features_content = get_field('main_content_block');
          $features_array[$features_key]['content'] = wp_strip_all_tags($features_content);

          // FEATURES SIDEBARS
          if( have_rows('sidebars') ) {
            while ( have_rows('sidebars') ) : the_row();

              // Increment sidebars
              $count_sidebars++;
              
              //Make a key for each grouping of FEATURES content
              $sidebar_key = 'sidebar-group-' . (string)$count_sidebars;  

              // FEATURES SUBTITLE
              $sidebar_subtitle = get_sub_field('sidebar_title');
              $features_array[$features_key][$sidebar_key]['subtitle'] = $sidebar_subtitle;
              
              // FEATURES CONTENT
              $sidebar_main_content = get_sub_field('sidebar_body');
              $features_array[$features_key][$sidebar_key]['content'] = wp_strip_all_tags($sidebar_main_content);

              // Cast $count_sidebars back to integer
              $count_sidebars = (int)$count_sidebars;

            endwhile;

            // Reset count_sidebars for next node
            $count_sidebars = 0;
          }

          // FEATURES PULL QUOTES
          if( have_rows('pull_quotes') ) {
            while ( have_rows('pull_quotes') ) : the_row();

              // Increment notes
              $count_pullquotes++;
              
              //Make a key for each grouping of FEATURES content
              $pullquotes_key = 'pullquote-group-' . (string)$count_pullquotes;  

              // FEATURES SUBTITLE
              $pullquotes_subtitle = get_sub_field('pull_quote_text');
              $features_array[$features_key][$pullquotes_key]['content'] = $pullquotes_subtitle;
              
              // FEATURES CONTENT
              $pullquotes_main_content = get_sub_field('pull_quote_attribution');
              $features_array[$features_key][$pullquotes_key]['author'] = wp_strip_all_tags($pullquotes_main_content);

              // Cast $count_pullquotes back to integer
              $count_pullquotes = (int)$count_pullquotes;

            endwhile;

            // Reset count_pullquotes for next node
            $count_pullquotes = 0;
          }

          // Reset counter back into an integer
          $count = (int)$count;
        } 

        return new WP_REST_Response($features_array, 200);
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
  $features_posts_server = new Features_Posts_Controller();
  $features_posts_server->instantiate_rest_server();  
?>