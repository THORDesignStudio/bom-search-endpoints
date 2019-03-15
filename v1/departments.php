<?php
  /**
   * DEPARTMENTS Endpoint
   */

  /**
   * include for date_conversion utility
   */
  include_once(plugin_dir_path( __DIR__ ) . 'util/date-converter.php');

  /**
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

          // DEPARTMENTS ABSTRACT
          $departments_array[$departments_key]['abstract'] = substr(wp_strip_all_tags(get_field('main_content_block')), 0, 255) . ' ...';

          // DEPARTMENTS AUTHOR
          $department_author_field = get_field('author');
          $department_author_field_single = get_field('one_time_author');
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
          } elseif ($department_author_field_single) {
            $department_author_field_final = $department_author_field_final . $department_author_field_single;
          } else {
            $department_author_field_final = null;
          }
          $departments_array[$departments_key]['author'] = $department_author_field_final;

          // DEPARTMENTS CATEGORY
          // we're calling it category in the endpoint for consistency
          $department_term = get_field('department');
          $departments_array[$departments_key]['category'] = $department_term->name;

          // DEPARTMENTS CONTENT
          $department_content = get_field('main_content_block');
          $departments_array[$departments_key]['content'] = wp_strip_all_tags($department_content);

          // DEPARTMENT IMAGE
          $primaryImage = get_field('header_image');
          $sizedImage = wp_get_attachment_image_src( $primaryImage, 'small' );
          $departments_array[$departments_key]['image-url'] = $sizedImage[0];

          // DEPARTMENTS ISSUE
          $department_issue = get_field('issue');
          $departments_array[$departments_key]['issue'] = $department_issue->name;

          // DEPARTMENTS MODIFIED DATE
          $departments_array[$departments_key]['modified-date'] = get_the_modified_date('Y-m-d', $post);

          // DEPARTMENTS PUBLICATION DATE
          // as of v1.2, publication data is now the same as issue date
          // $departments_array[$departments_key]['publication-date'] = get_the_date('Y-m-d', $post);
          $departments_array[$departments_key]['publication-date'] = date_conversion( $department_issue->name );

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
          } else {
            $departments_array[$departments_key]['topic-group'] = null;
          }

          // DEPARTMENTS TITLE
          $departments_array[$departments_key]['title'] = get_the_title();

          // DEPARTMENTS URL
          $departments_array[$departments_key]['url'] = get_the_permalink();

          // DEPARTMENTS SIDEBARS
          if( have_rows('sidebars') ) {
            while ( have_rows('sidebars') ) : the_row();

              // Increment sidebars
              $count_sidebars++;
              
              //Make a key for each grouping of DEPARTMENTS content
              $sidebar_key = 'sidebar-group-' . (string)$count_sidebars;  
              
              // DEPARTMENTS CONTENT
              $sidebar_main_content = get_sub_field('sidebar_body');
              $departments_array[$departments_key][$sidebar_key]['content'] = wp_strip_all_tags($sidebar_main_content);

              // DEPARTMENTS TITLE
              $sidebar_subtitle = get_sub_field('sidebar_title');
              $departments_array[$departments_key][$sidebar_key]['title'] = $sidebar_subtitle;

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

              // DEPARTMENTS PQ AUTHOR
              $pullquotes_main_content = get_sub_field('pull_quote_attribution');

              if($pullquotes_main_content) {
                $departments_array[$departments_key][$pullquotes_key]['author'] = wp_strip_all_tags($pullquotes_main_content);
              } else {
                $departments_array[$departments_key][$pullquotes_key]['author'] = null;
              }

              // DEPARTMENTS PQ CONTENT
              $pullquotes_subtitle = get_sub_field('pull_quote_text');
              $departments_array[$departments_key][$pullquotes_key]['content'] = $pullquotes_subtitle;

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