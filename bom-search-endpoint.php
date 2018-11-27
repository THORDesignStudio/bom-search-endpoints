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
  
  add_action('rest_api_init', function () {
    register_rest_route( 'bom/v1', 'all-content', array(
      'methods'  => 'GET',
      'callback' => 'get_all_content'
    ));
  });

  function get_all_content() {
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
        $notes_note = get_field('note_section');
        $nacubo_notes_array[$nacubo_notes_key]['title'] = $notes_note[0]['title'];

        // NACUBO NOTES ISSUE
        $term = get_field('issue');
        $nacubo_notes_array[$nacubo_notes_key]['issue'] = $term->name;

        // NACUBO NOTES CONTENT
        if( have_rows('note_section') ) {
          while ( have_rows('note_section') ) : the_row();

            // Increment notes
            $count_notes++;
            
            // NACUBO NOTES SUBTITLE
            $subtitle = get_sub_field('title');
            $subtitle_key = 'subtitle-' . (string)$count_notes;  
            $nacubo_notes_array[$nacubo_notes_key][$subtitle_key] = $subtitle;
            
            // NACUBO NOTES CONTENT
            $main_content = get_sub_field('content');
            $main_content_key = 'content-' . (string)$count_notes;  
            $nacubo_notes_array[$nacubo_notes_key][$main_content_key] = wp_strip_all_tags($main_content);

            // Cast $count_notes back to integer
            $count_note = (int)$count_notes;

          endwhile;
        }

        // Reset counter back into an integer
        $count = (int)$count;
      } 

      // API RESPONSE
      $response = new WP_REST_Response($nacubo_notes_array);
      $response->set_status(200);      
      return $response;
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

?>