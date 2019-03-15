<?php
  /**
   * BUSINESS INTEL Endpoint
   */

  /**
   * include for date_conversion utility
   */
  include_once(plugin_dir_path( __DIR__ ) . 'util/date-converter.php');

  /**
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

          // BIZ INTEL ABSTRACT
          $bi_array[$business_intel_key]['abstract'] = null;

          // BIZ INTEL AUTHOR
          $bi_array[$business_intel_key]['author'] = null;

          // BIZ INTEL CATEGORY
          $bi_array[$business_intel_key]['category'] = 'business-intel';

          // BIZ INTEL CONTENT
          $bi_array[$business_intel_key]['content'] = null;
          
          // BIZ INTEL IMAGE
          $bi_array[$business_intel_key]['image'] = null;
        
          // BIZ INTEL ISSUE
          $term = get_field('issue');
          $bi_array[$business_intel_key]['issue'] = $term->name;

          // BIZ INTEL MODIFIED DATE
          $bi_array[$business_intel_key]['modified-date'] = get_the_modified_date('Y-m-d', $post);
          
          // BIZ INTEL PUBLICATION DATE
          // as of v1.2, publication date is now the same as issue date
          // $bi_array[$business_intel_key]['publication-date'] = get_the_date('Y-m-d', $post);
          $bi_array[$business_intel_key]['publication-date'] = date_conversion( $term->name );

          // BIZ INTEL TITLE
          $bi_array[$business_intel_key]['title'] = get_the_title();

          // BIZ INTEL TOPICS
          $bi_array[$business_intel_key]['topic-group'] = null;

          // BIZ INTEL URL
          $bi_array[$business_intel_key]['url'] = get_the_permalink();          

          // BIZ INTEL CONTENT
          if( have_rows('note_section') ) {
            while ( have_rows('note_section') ) : the_row();

              // Increment notes
              $count_notes++;
              
              // Make a key for each grouping of BIZ INTEL content
              $content_key = 'content-group-' . (string)$count_notes;  

              // BIZ INTEL ABSTRACT
              $bi_array[$business_intel_key][$content_key]['abstract'] = substr(wp_strip_all_tags(get_sub_field('content')), 0, 255) . ' ...';

              // BIZ INTEL AUTHOR
              $bi_author_field = get_sub_field('author');
              $bi_author_field_single = get_sub_field('one_time_author');
              $bi_author_field_final = "By "; // a string we'll add onto
            
              if ($bi_author_field) {
                $users_count = count($bi_author_field);
            
                for ($i = 0; $i < $users_count; $i++) {
                  // If there is only one author OR it's the last author, print name only
                  if ( ($i == 0 && $i == ($users_count - 1) ) || $i == ($users_count - 1)) {
                    $bi_author_field_final = $bi_author_field_final . $bi_author_field[$i]['display_name'];
                  } else {
                    $bi_author_field_final = $bi_author_field_final . $bi_author_field[$i]['display_name'] . ' and ';
                  }
                }
              } elseif ($bi_author_field_single) {
                $bi_author_field_final = $bi_author_field_final . $bi_author_field_single;
              } else {
                $bi_author_field_final = 'NACUBO Editorial Team';
              }
              $bi_array[$business_intel_key][$content_key]['author'] = $bi_author_field_final;

              // BIZ INTEL CATEGORY
              $bi_array[$business_intel_key][$content_key]['category'] = 'business-intel';

              // BIZ INTEL CONTENT
              $bi_array[$business_intel_key][$content_key]['content'] = wp_strip_all_tags(get_sub_field('content'));

              // BIZ INTEL IMAGE
              $primaryImage[$content_key] = get_sub_field('image');
              if($primaryImage) {
                $sizedImage = wp_get_attachment_image_src( $primaryImage[$content_key], 'small' );
                $bi_array[$business_intel_key][$content_key]['image-url'] = $sizedImage[0];
              } else {
                $bi_array[$business_intel_key][$content_key]['image-url'] = null;
              }

              // BIZ INTEL ISSUE
              $bi_array[$business_intel_key][$content_key]['issue'] = $bi_array[$business_intel_key]['issue'];

              // BIZ INTEL MODIFIED DATE
              $bi_array[$business_intel_key][$content_key]['modified-date'] = $bi_array[$business_intel_key]['modified-date'];
          
              // BIZ INTEL PUBLICATION DATE
              // as of v1.2, publication date is now the same as issue date
              // $bi_array[$business_intel_key][$content_key]['publication-date'] = $bi_array[$business_intel_key]['publication-date'];
              $bi_array[$business_intel_key][$content_key]['publication-date'] = date_conversion( $bi_array[$business_intel_key]['issue'] );

              // BIZ INTEL TITLE
              $bi_subtitle = get_sub_field('title'); // kicker is really a topic tag for Bom
              $bi_array[$business_intel_key][$content_key]['title'] = $bi_subtitle;

              // BIZ INTEL TOPICS
              $bi_topics = get_sub_field('kicker');

              if ($bi_topics) {
                $subtitle = ucwords(strtolower($bi_topics)); // kicker is really a topic tag for Bom
                $bi_array[$business_intel_key][$content_key]['topic-group']['topic-1'] = $subtitle;
              } else {
                $bi_array[$business_intel_key][$content_key]['topic-group']['topic-1'] = null;
              }              

              // BIZ INTEL URL
              // basically getting the title of sub-articles and formatting it into a URL
              $headline_cleaned = strtolower($bi_subtitle);
              $headline_cleaned = preg_replace("/[^a-z0-9_\s-]/", "", $headline_cleaned); // remove non-alphanumerics
              $headline_cleaned = preg_replace("/[\s-]+/", " ", $headline_cleaned); // cleans up whitespace
              $headline_cleaned = preg_replace("/[\s_]/", "-", $headline_cleaned); // convert whitespace + underscore to dash
              $headline_url = get_the_permalink() . '#' . $headline_cleaned;
              $bi_array[$business_intel_key][$content_key]['url'] = $headline_url;

              // Cast $count_notes back to integer
              $count_notes = (int)$count_notes;

            endwhile;

            // Reset count_notes for next node
            $count_notes = 0;
          }

          // BIZ INTEL FAST FACTS - repeat of a lot of the same info, but in the fast facts array
          // as of v1.2, publication date is now the same as issue date
          $bi_array[$business_intel_key]['fast-fact']['abstract'] = substr(sanitize_text_field(wp_strip_all_tags(get_field('fast_fact'))), 0, 255) . ' ...';
          $bi_array[$business_intel_key]['fast-fact']['author'] = 'NACUBO Editorial Team';
          $bi_array[$business_intel_key]['fast-fact']['category'] = $bi_array[$business_intel_key]['category'];
          $bi_array[$business_intel_key]['fast-fact']['content'] = sanitize_text_field(wp_strip_all_tags(get_field('fast_fact'))); 
          $bi_array[$business_intel_key]['fast-fact']['image'] = null;
          $bi_array[$business_intel_key]['fast-fact']['issue'] = $bi_array[$business_intel_key]['issue'];
          $bi_array[$business_intel_key]['fast-fact']['modified-date'] = $bi_array[$business_intel_key]['modified-date'];
          $bi_array[$business_intel_key]['fast-fact']['publication-date'] = date_conversion( $bi_array[$business_intel_key]['issue'] );
          $bi_array[$business_intel_key]['fast-fact']['title'] = $bi_array[$business_intel_key]['title'] . " - Fast Fact";
          $bi_array[$business_intel_key]['fast-fact']['topics'] = null;
          $bi_array[$business_intel_key]['fast-fact']['url'] = $bi_array[$business_intel_key]['url'] . "#fast-fact";

          // BIZ INTEL QUICK CLICKS - repeat of a lot of the same info, but in the quick clicks array
          // as of v1.2, publication date is now the same as issue date
          $bi_array[$business_intel_key]['quick-clicks']['abstract'] = substr(sanitize_text_field(wp_strip_all_tags(get_field('quick_clicks'))), 0, 255) . ' ...';
          $bi_array[$business_intel_key]['quick-clicks']['author'] = 'NACUBO Editorial Team';
          $bi_array[$business_intel_key]['quick-clicks']['category'] = $bi_array[$business_intel_key]['category'];
          $bi_array[$business_intel_key]['quick-clicks']['content'] = sanitize_text_field(wp_strip_all_tags(get_field('quick_clicks'))); 
          $bi_array[$business_intel_key]['quick-clicks']['image'] = null;
          $bi_array[$business_intel_key]['quick-clicks']['issue'] = $bi_array[$business_intel_key]['issue'];
          $bi_array[$business_intel_key]['quick-clicks']['modified-date'] = $bi_array[$business_intel_key]['modified-date'];
          $bi_array[$business_intel_key]['quick-clicks']['publication-date'] = date_conversion( $bi_array[$business_intel_key]['issue'] );
          $bi_array[$business_intel_key]['quick-clicks']['title'] = $bi_array[$business_intel_key]['title'] . " - Quick Clicks";
          $bi_array[$business_intel_key]['quick-clicks']['topics'] = null;
          $bi_array[$business_intel_key]['quick-clicks']['url'] = $bi_array[$business_intel_key]['url'] . "#quick-clicks";

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