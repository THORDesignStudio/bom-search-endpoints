<?php

/**
 * Plugin Name: 	Business Officer Search Endpoints
 * Plugin URI:		http://www.thor-studio.com/
 * Description:	  Custom post-types, menus, filters, image-sizing and taxonomies for the Business Officer wordpress site.
 * Version:		    1.2.0
 * Author:			  John Serrao
 * Author URI:		https://www.thor.design
 * License:		    CC Attribution-ShareAlike License
 * License URI:	  https://creativecommons.org/licenses/by-sa/4.0/
 * 
 * 
 * #################################
 * ########### Changelog ###########
 * #################################
 * 
 * 0.1.0: Setup custom endpoint for all site articles, all issues
 * 1.0.0: Refactored into separate files for the endpoints
 * 1.1.0: Updated for Mindshift spec requests
 * 1.2.0: In all the endpoints, the publication-date date needs to be changed to be the issue date
 * 
 * 
 * ##################################
 * ########### Background ###########
 * ##################################
 * 
 * This plugin creates 4 endpoints ( in the namespace bom/v1):
 *  -features
 *  -departments
 *  -nacubo-notes
 *  -business-intel
 * 
 *  These endpoints map to the 4 main custom post-types in the BOM WordPress install.
 *  They are similar yet different in the data models so each deserves its own endpoint
 *
 *  Technically on the BOM website there are 5 departments (https://www.businessofficermagazine.org/department/)
 *    At the data level, there are 3: departments, nacubo-notes and business-intel
 *    The features and departments post-types have similar data models
 *    Nacubo Notes and Business Intel also have similar data models
 * 
 * 
 * #################################
 * ########### Namespace ###########
 * #################################
 * 
 * Namespace is:
 *  /wp-json/bom/v1
 * 
 * This plugin extends WP-API built into WordPress to create these endpoints.
 *  Read more here if you're interested: https://developer.wordpress.org/rest-api/
 * 
 * We're using WP_REST_Controller which automatically puts endpoints at /wp-json
 * We extend that with our own namespace of `bom` and then version with `v1`
 * You can't change `/wp-json` and should not change `bom` 
 * However, `v1` can be changed inside of each endpoint - and should be if you enhance any endpoint
 *  
 * 
 * ############################################
 * ########### Versioning Endpoints ###########
 * ############################################
 *  This plugin has only one folder right now: v1
 *  This folder contains a separate file for each endpoint
 *  It's important not to break the endpoints once they are in service
 *  Additional revisions to any v1 endpoint should be created in a v2 folder, etc   
 */


 /**
  * v1 Endpoints, listed alphabetically
  */

  // BUSINESS INTEL
  include(plugin_dir_path( __FILE__ ) . 'v1/business-intel.php');

  // DEPARTMENTS
  include(plugin_dir_path( __FILE__ ) . 'v1/departments.php');

  // FEATURES
  include(plugin_dir_path( __FILE__ ) . 'v1/features.php');

  // NACUBO NOTES
  include(plugin_dir_path( __FILE__ ) . 'v1/nacubo-notes.php');
?>