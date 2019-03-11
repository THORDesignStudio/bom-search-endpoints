<?php
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

          // NACUBO NOTES ABSTRACT
          $nn_array[$nacubo_notes_key]['abstract'] = null;

          // NACUBO NOTES AUTHOR
          $nn_array[$nacubo_notes_key]['author'] = null;

          // NACUBO NOTES CATEGORY
          $nn_array[$nacubo_notes_key]['category'] = 'nacubo-notes';

          // NACUBO NOTES CONTENT
          $nn_array[$nacubo_notes_key]['content'] = null;

          // NACUBO NOTES IMAGE
          $nn_array[$nacubo_notes_key]['image'] = null;

          // NACUBO NOTES ISSUE
          $term = get_field('issue');
          $nn_array[$nacubo_notes_key]['issue'] = $term->name;

          // NACUBO NOTES MODIFIED DATE
          $nn_array[$nacubo_notes_key]['modified-date'] = get_the_modified_date('Y-m-d', $post);

          // NACUBO NOTES PUBLICATION DATE
          // as of v1.2, publication data is now the same as issue date
          // $nn_array[$nacubo_notes_key]['publication-date'] = get_the_date('Y-m-d', $post);
          $nn_array[$nacubo_notes_key]['publication-date'] = $term->name;

          // NACUBO NOTES TITLE
          $nn_array[$nacubo_notes_key]['title'] = get_the_title();

          // BIZ INTEL TOPICS
          $nn_array[$nacubo_notes_key]['topic-group'] = null;
          
          // NACUBO NOTES URL
          $nn_array[$nacubo_notes_key]['url'] = get_the_permalink();


          // NACUBO NOTES CONTENT
          if( have_rows('note_section') ) {
            while ( have_rows('note_section') ) : the_row();

              // Increment notes
              $count_notes++;
              
              //Make a key for each grouping of Nacubo Notes content
              $content_key = 'content-group-' . (string)$count_notes;  

              // NACUBO NOTES ABSTRACT
              $nn_array[$nacubo_notes_key][$content_key]['abstract'] = substr(wp_strip_all_tags(get_sub_field('content')), 0, 255) . ' ...';;

              // NACUBO NOTES AUTHOR
              $nn_array[$nacubo_notes_key][$content_key]['author'] = 'NACUBO Editorial Team';

              // NACUBO NOTES CATEGORY
              $nn_array[$nacubo_notes_key][$content_key]['category'] = 'nacubo-notes';

              // NACUBO NOTES CONTENT
              $main_content = get_sub_field('content');
              $nn_array[$nacubo_notes_key][$content_key]['content'] = wp_strip_all_tags($main_content);

              // NACUBO NOTES IMAGE
              $primaryImage[$content_key] = get_sub_field('image');
              if($primaryImage) {
                $sizedImage = wp_get_attachment_image_src( $primaryImage[$content_key], 'small' );
                $nn_array[$nacubo_notes_key][$content_key]['image-url'] = $sizedImage[0];
              }

              // NACUBO NOTES ISSUE
              $nn_array[$nacubo_notes_key][$content_key]['issue'] = $nn_array[$nacubo_notes_key]['issue']; 

              // NACUBO NOTES MODIFIED DATE
              $nn_array[$nacubo_notes_key][$content_key]['modified-date'] = $nn_array[$nacubo_notes_key]['modified-date'];

              // NACUBO NOTES PUBLICATION DATE
              // as of v1.2, publication data is now the same as issue date
              // $nn_array[$nacubo_notes_key][$content_key]['publication-date'] = $nn_array[$nacubo_notes_key]['publication-date'];
              $nn_array[$nacubo_notes_key][$content_key]['publication-date'] = $nn_array[$nacubo_notes_key]['issue']; 

              // NACUBO NOTES TITLE
              $title[$content_key] = get_sub_field('title');
              $nn_array[$nacubo_notes_key][$content_key]['title'] = $title[$content_key];
        
              // NACUBO NOTES TOPICS
              $nn_array[$nacubo_notes_key][$content_key]['topic-group'] = null;

              // NACUBO NOTES URL
              $headline_cleaned = strtolower($title[$content_key]);
              $headline_cleaned = preg_replace("/[^a-z0-9_\s-]/", "", $headline_cleaned); // remove non-alphanumerics
              $headline_cleaned = preg_replace("/[\s-]+/", " ", $headline_cleaned); // cleans up whitespace
              $headline_cleaned = preg_replace("/[\s_]/", "-", $headline_cleaned); // convert whitespace + underscore to dash
              $headline_url = get_the_permalink() . '#' . $headline_cleaned;
              $nn_array[$nacubo_notes_key][$content_key]['url'] = $headline_url;
              
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
?>