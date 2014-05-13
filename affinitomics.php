<?php
/*
Plugin Name: Affinitomics
Plugin URI: http://prefrent.com
Description: Apply Affinitomic Descriptors, Draws, and Distance to Posts and Pages.  Shortcode to display Affinitomic relationships. Google CSE with Affinitomics.
Version: 0.6.04 Beta
Author: Prefrent
Author URI: http://prefrent.com
*/

/*
Affinitomics (Wordpress Plugin)
Copyright (C) 2014 Prefrent
*/

// +----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License, version 2, as  |
// | published by the Free Software Foundation.                           |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program; if not, write to the Free Software          |
// | Foundation, Inc., 51 Franklin St, Fifth Floor, Boston,               |
// | MA 02110-1301 USA                                                    |
// +----------------------------------------------------------------------+


wp_enqueue_style( 'afpost-style', plugins_url('affinitomics.css', __FILE__) );

/*
----------------------------------------------------------------------
META BOXES FOR POST CLASSIFICATION (descriptors, draw, and distance)
----------------------------------------------------------------------
*/

/* Meta Box */
add_action( 'add_meta_boxes', 'afpost_add_custom_box' );

/* Save Action */
add_action( 'save_post', 'afpost_save_postdata' );

/* Page Types to Apply Affinitomics */
$screens = array();
if (get_option('af_post_type_affinitomics','true') == 'true') $screens[] = 'archetype';
if (get_option('af_post_type_posts','false') == 'true') $screens[] = 'post';
if (get_option('af_post_type_pages','false') == 'true') $screens[] = 'page';

/* Add Affinitomic Elements to Post and Page edit screens */
function afpost_add_custom_box() {
    global $screens;
    foreach ($screens as $screen) {
        add_meta_box(
            'afpost_id',
            'Affinitomic Elements',
            'afpost_inner_custom_box',
            $screen
        );
    }
}

/* Prints the box content */
function afpost_inner_custom_box( $post ) {

  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'afpost_noncename' );

  // Get post meta values
  $afpost_descriptors_value = get_post_meta( $post->ID, '_afpost_descriptors', true );
  $afpost_draw_value = get_post_meta( $post->ID, '_afpost_draw', true );
  $afpost_distance_value = get_post_meta( $post->ID, '_afpost_distance', true );

  // Get POST Count
  $count_posts = wp_count_posts();
  if ($count_posts->publish > 1000) {

    echo '<h1>Affinitomic License Exceeded.</h1>';
    echo '<p>Please Contact Prefrent For Expanded Post Coverage.</p>';
    echo '<br style="clear:both;" />';

  } else {

    echo '<div class="afpost_metabox">';
    echo '<label for="afpost_descriptors">';
    echo '<strong>Descriptors</strong> (similar to \'tags\')';
    echo '</label><br>';
    echo '<textarea id="afpost_descriptors" name="afpost_descriptors">'.esc_attr($afpost_descriptors_value).'</textarea>';
    echo '<p><strong>Syntax:</strong> These are the similar to \'tags\' in Wordpress.</p>';
    echo '</div>';

    echo '<div class="afpost_metabox">';
    echo '<label for="afpost_draw">';
    echo '<strong>Draw</strong> (positive relationships)';
    echo '</label><br>';
    echo '<textarea id="afpost_draw" name="afpost_draw">'.esc_attr($afpost_draw_value).'</textarea>';
    echo '<p><strong>Syntax:</strong> Draws are generally preceded with a \'plus\' and written with a magnitude from one to five as a suffix, with each draw seperated by a comma. If a magnitude is not present, a magnitude of one will be assumed.</p>';
    echo '</div>';

    echo '<div class="afpost_metabox">';
    echo '<label for="afpost_distance">';
    echo '<strong>Distance</strong> (negative relationships)';
    echo '</label><br>';
    echo '<textarea id="afpost_distance" name="afpost_distance">'.esc_attr($afpost_distance_value).'</textarea>';
    echo '<p><strong>Syntax:</strong> Distances are generally preceded with a \'minus\' and written with a magnitude from one to five as a suffix, with each distance seperated by a comma. If a magnitude is not present, a magnitude of one will be assumed.</p>';
    echo '</div>';
    echo '<br style="clear:both" />';
  }
}

/* Save Custom DATA */
function afpost_save_postdata( $post_id ) {

  // First we need to check if the current user is authorised to do this action.
  if ( 'page' == $_POST['post_type'] ) {
    if ( ! current_user_can( 'edit_page', $post_id ) )
        return;
  } else {
    if ( ! current_user_can( 'edit_post', $post_id ) )
        return;
  }

  // NONCE
  if ( ! isset( $_POST['afpost_noncename'] ) || ! wp_verify_nonce( $_POST['afpost_noncename'], plugin_basename( __FILE__ ) ) ) {
    return;
  }

  // Get Post ID
  $post_ID = $_POST['post_ID'];

  // Sanitize
  $afpost_descriptors = sanitize_text_field( $_POST['afpost_descriptors'] );
  $afpost_draw = sanitize_text_field( $_POST['afpost_draw'] );
  $afpost_distance = sanitize_text_field( $_POST['afpost_distance'] );

  // Save Meta DATA
  add_post_meta($post_ID, '_afpost_descriptors', $afpost_descriptors, true) or update_post_meta($post_ID, '_afpost_descriptors', $afpost_descriptors);
  add_post_meta($post_ID, '_afpost_draw', $afpost_draw, true) or update_post_meta($post_ID, '_afpost_draw', $afpost_draw);
  add_post_meta($post_ID, '_afpost_distance', $afpost_distance, true) or update_post_meta($post_ID, '_afpost_distance', $afpost_distance);

  // Affinitomic ID
  $afid = get_post_meta($post_ID, 'afid', true);

  // Categories String
  $cat_string = '';

  // Save Data To Prefrent Cloud
  global $af_flag;
  if ($af_flag == 0) {
    $cat_string = '';
    $categories = get_the_category($id);
    if ($categories) {
      $cats = array();
      foreach($categories as $cat) {
        $cats[] = $cat->term_id;
      }
      $cat_string = implode(",", $cats);
    }
    $affinitomics = array(
      'url' =>  get_permalink($post_ID),
      'title' => get_the_title($post_ID),
      'descriptors' => $afpost_descriptors,
      'draw' => $afpost_draw,
      'distance' => $afpost_distance,
      'domain' => get_option('af_domain'),
      'key' => get_option('af_key'),
      'uid' => $post_ID,
      'category' => $cat_string
    );
    if ($afid) $affinitomics['afid'] = $afid; 
    $af_cloud_url = get_option('af_cloud_url');
    $request = curl_request($af_cloud_url.'/v1/post/affinitomics/', $affinitomics);
    $af = json_decode($request, true);
    if (isset($af['data']['objectId'])) {
      update_post_meta($post_ID, 'afid', $af['data']['objectId']);
    }
  }
  $af_flag = 1;

  // Update tags
  if (get_option('af_tag_descriptors','true') == 'true') {
    if ($afpost_descriptors != '') {
      wp_set_post_terms( $post_ID, $afpost_descriptors, 'post_tag', false );
    }
    if ($afpost_draw != '') {
      $afpost_draw = str_replace(range(0,9),'',$afpost_draw);
      $afpost_draw = str_replace('+','',$afpost_draw);
      wp_set_post_terms( $post_ID, $afpost_draw, 'draw', false );
    }
    if ($afpost_distance != '') {
      $afpost_distance = str_replace(range(0,9),'',$afpost_distance);
      $afpost_distance = str_replace('-','',$afpost_distance);
      wp_set_post_terms( $post_ID, $afpost_distance, 'distance', false );
    }
  }

}

/*
----------------------------------------------------------------------
CUSTOM TAXONOMY
----------------------------------------------------------------------
*/

// Register Custom Taxonomy
function draw_taxonomy()  {
    $labels = array(
        'name'                       => _x( 'Positive Relationships', 'Taxonomy General Name', 'text_domain' ),
        'singular_name'              => _x( 'Draw', 'Taxonomy Singular Name', 'text_domain' ),
        'menu_name'                  => __( 'Draw', 'text_domain' ),
        'all_items'                  => __( 'All Draws', 'text_domain' ),
        'parent_item'                => __( 'Parent Draw', 'text_domain' ),
        'parent_item_colon'          => __( 'Parent Draw:', 'text_domain' ),
        'new_item_name'              => __( 'New Draw', 'text_domain' ),
        'add_new_item'               => __( 'Add New Draw', 'text_domain' ),
        'edit_item'                  => __( 'Edit Draw', 'text_domain' ),
        'update_item'                => __( 'Update Draw', 'text_domain' ),
        'separate_items_with_commas' => __( 'Separate draws with commas', 'text_domain' ),
        'search_items'               => __( 'Search draws', 'text_domain' ),
        'add_or_remove_items'        => __( 'Add or remove draws', 'text_domain' ),
        'choose_from_most_used'      => __( 'Choose from the most used draws', 'text_domain' ),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );

    global $screens;
    register_taxonomy( 'draw', $screens, $args );
}

// Hook into the 'init' action
add_action( 'init', 'draw_taxonomy', 0 );


// Register Custom Taxonomy
function distance_taxonomy()  {
    $labels = array(
        'name'                       => _x( 'Negative Relationships', 'Taxonomy General Name', 'text_domain' ),
        'singular_name'              => _x( 'Distance', 'Taxonomy Singular Name', 'text_domain' ),
        'menu_name'                  => __( 'Distance', 'text_domain' ),
        'all_items'                  => __( 'All Distances', 'text_domain' ),
        'parent_item'                => __( 'Parent Distance', 'text_domain' ),
        'parent_item_colon'          => __( 'Parent Distance:', 'text_domain' ),
        'new_item_name'              => __( 'New Distance', 'text_domain' ),
        'add_new_item'               => __( 'Add New Distance', 'text_domain' ),
        'edit_item'                  => __( 'Edit Distance', 'text_domain' ),
        'update_item'                => __( 'Update Distance', 'text_domain' ),
        'separate_items_with_commas' => __( 'Separate Distances with commas', 'text_domain' ),
        'search_items'               => __( 'Search Distance', 'text_domain' ),
        'add_or_remove_items'        => __( 'Add or remove Distance', 'text_domain' ),
        'choose_from_most_used'      => __( 'Choose from the most used Distance', 'text_domain' ),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );

    global $screens;
    register_taxonomy( 'distance', $screens, $args );
}

// Hook into the 'init' action
add_action( 'init', 'distance_taxonomy', 0 );

/*
----------------------------------------------------------------------
Register "Archetype" post type
----------------------------------------------------------------------
*/
// Register Custom Post Type
function arche_type() {

  $labels = array(
    'name'                => _x( 'Archetypes', 'Post Type General Name', 'text_domain' ),
    'singular_name'       => _x( 'Archetype', 'Post Type Singular Name', 'text_domain' ),
    'menu_name'           => __( 'Affinitomics&trade;', 'text_domain' ),
    'parent_item_colon'   => __( 'Parent Archetype:', 'text_domain' ),
    'all_items'           => __( 'All Archetypes', 'text_domain' ),
    'view_item'           => __( 'View Archetype', 'text_domain' ),
    'add_new_item'        => __( 'Add New Archetype', 'text_domain' ),
    'add_new'             => __( 'New Archetype', 'text_domain' ),
    'edit_item'           => __( 'Edit Archetype', 'text_domain' ),
    'update_item'         => __( 'Update Archetype', 'text_domain' ),
    'search_items'        => __( 'Search Archetypes', 'text_domain' ),
    'not_found'           => __( 'No archetypes found', 'text_domain' ),
    'not_found_in_trash'  => __( 'No archetypes found in Trash', 'text_domain' ),
  );
  $args = array(
    'label'               => __( 'archetype', 'text_domain' ),
    'description'         => __( 'Archetype information pages', 'text_domain' ),
    'labels'              => $labels,
    'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', 'post-formats', ),
    'taxonomies'          => array( 'category', 'post_tag', 'discriptor', 'draw', 'distance' ),
    'hierarchical'        => false,
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'menu_position'       => 5,
    'menu_icon'           => '/wp-content/plugins/affinitomics/affinitomics-favicon.png',
    'can_export'          => true,
    'has_archive'         => true,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'capability_type'     => 'post',
  );
  register_post_type( 'archetype', $args );

}

// Hook into the 'init' action
add_action( 'init', 'arche_type', 0 );

/*
----------------------------------------------------------------------
RELATED POSTS SHORTCODE
Example: [afview], [afview affinitomics="+foo -bar"], [afview limit="4"], or [afview category_id="42"].
----------------------------------------------------------------------
*/

//register shortcode
add_shortcode("afview", "afview_handler");

//handle shortcode
function afview_handler( $atts, $content = null ) {
    $afview_output = afview_function($atts);
    return $afview_output;
}

//process shortcode
function afview_function($atts) {
  global $screens;
  extract( shortcode_atts( array(
      //'title' => null,
      'affinitomics' => null,
      'display_title' => 'true',
      'limit' => 10,
      'debug' => false,
      'category_id' => null,
      'post_type' => null
  ), $atts ) );

  // Start output
  $afview_output = '';
  $post_id = get_the_ID();
  $afid = get_post_meta($post_id, 'afid', true);
  $af_domain = get_option('af_domain');
  $af_key = get_option('af_key');

  // Find Related Elements    
  if ($afid) {
    $af_cloud_url = get_option('af_cloud_url');
    $af_cloud = $af_cloud_url.'/v1/related/affinitomics/?afid='.$afid.'&domain='.$af_domain.'&key='.$af_key;
    if ($affinitomics) {
      $af_cloud = $af_cloud . '&af=' . rawurlencode($affinitomics);
    }
    $request = file_get_contents($af_cloud, false); 
    $af = json_decode($request, true);
    $afview_output .= '<!-- '.$af_cloud.' -->';
  }
  // HTML Output
  if ($af['list']) {
    if ($display_title == 'true') {
      if ($affinitomics) {
        $afview_output .= '<h2>Related Items: '. $affinitomics;
      } else {
        $afview_output .= '<h2>Related Items: '. $af['list'];
      }
      $afview_output .= ' <i>(sorted by Affinitomic concordance)</i></h2>';
    }
    // Loop Thru Elements
    $html_list = '<ul>';
    $html_list_count = 0;
    foreach ($af['related'] as $raf) {
      $process_element = true;
      // Unique Identifier?
      if (!isset($raf['element']['uid'])) {
        $process_element = false;
      }
      // Valid Post?
      $post_title = get_post_field('post_title', $raf['element']['uid']);
      if (is_wp_error($post_title)) {
        $process_element = false;
      }
      // Categories
      $raf_cats = array();
      if ($raf['element']['category']) $raf_cats = explode(',',$raf['element']['category']);
      // Filter by Category?
      if ($category_id && ( !in_array($category_id, $raf_cats)) ) {
        $process_element = false;
      }
      // Process Element?
      if ($process_element) {
        if ($html_list_count < $limit) {
          $html_list_count++;
          $html_list .= '<li><a href="'.$raf['element']['url'].'">'.$raf['element']['title'].'</a> ('.$raf['score'].')</li>';
        }
      }
    }
    $html_list .= '</ul>';
    $afview_output .= $html_list;
  }
  return $afview_output;
}
/*
End Affinitomics Commercial Code
*/
/*
----------------------------------------------------------------------
Administration and Settings Menu
----------------------------------------------------------------------
*/

add_action( 'admin_menu', 'af_plugin_menu' );
function af_plugin_menu() {
  // Rename Tags to Descriptors
  remove_submenu_page( 'edit.php?post_type=archetype', 'edit-tags.php?taxonomy=post_tag&amp;post_type=archetype' );
  add_submenu_page( 'edit.php?post_type=archetype', 'Descriptors', 'Descriptors', 'manage_options', 'edit-tags.php?taxonomy=post_tag&amp;post_type=archetype');

  // Add Custom Sub Menus
  add_submenu_page( 'edit.php?post_type=archetype', 'Settings', 'Settings', 'manage_options', 'affinitomics', 'af_plugin_options');
  add_action( 'admin_init', 'af_register_settings' );
  add_submenu_page( 'edit.php?post_type=archetype', 'Cloud Export', 'Cloud Export', 'manage_options', 'afcloudify', 'af_plugin_export'); 
}
/*
Affinitomics Commercial Code
*/
function af_plugin_export() {
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }

  // Export to Cloud
  if (isset($_POST['af_cloudify']) && $_POST['af_cloudify'] == 'true')  {
    echo '<h1>Export</h1>';
    global $screens;
    $args = array(
      'post_type' => $screens,
      'category'=>$category_id,
      'posts_per_page' => -1
    );
    $posts_array = get_posts($args);
    echo '<ol>';
    foreach($posts_array as $post) {
      $id = $post->ID;
      $afid = get_post_meta($id, 'afid', true);
      if ($afid == false) next;
      $cat_string = '';
      $categories = get_the_category($id);
      if ($categories) {
        $cats = array();
        foreach($categories as $cat) {
          $cats[] = $cat->term_id;
        }
        $cat_string = implode(",", $cats);
      }
      $affinitomics = array(
        'url' =>  get_permalink($id),
        'title' => get_the_title($id),
        'descriptors' => get_post_meta($id, '_afpost_descriptors', true),
        'draw' => get_post_meta($id, '_afpost_draw', true),
        'distance' => get_post_meta($id, '_afpost_distance', true),
        'afid' => $afid,
        'domain' => get_option('af_domain'),
        'key' => get_option('af_key'),
        'uid' => $id,
        'category' => $cat_string
      );
      if ($affinitomics['descriptors'] || $affinitomics['draw'] || $affinitomics['distance']) {
        $af_cloud_url = get_option('af_cloud_url');
        $request = curl_request($af_cloud_url.'/v1/post/affinitomics/', $affinitomics); 
        $af = json_decode($request, true);
        echo '<li>';
        print_r($request);
        print_r($affinitomics);
        echo '</li>';
        // Update Post
        if (isset($af['data']['objectId'])) {
          update_post_meta($id, 'afid', $af['data']['objectId']);
        }
      }
    }
    echo '</ol>';
  }

  // Default View
  echo '<div class="wrap">';
  echo '<h2>Affinitomics Cloud Export</h2>';
  echo '<form method="post" action="">';
  settings_fields( 'af-cloud-settings-group' );
  do_settings_sections( 'af-cloud-settings-group' );
  $af_cloudify = get_option( 'af_cloudify', '' );
  if ($af_cloudify == 'true') $cloud_checked = 'checked="checked"';
  echo '<h4>Migrate Affinitomics to the Cloud?</h4>';
  echo '<input type="checkbox" name="af_cloudify" value="true" '.$cloud_checked.'/> Make it So!';
  submit_button('Export');
  echo '</form>';
  echo '</div>';
}

function af_plugin_options() {
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  echo '<div class="wrap">';
  echo '<h2>Affinitomics Plugin Settings</h2>';
  echo '<form method="post" action="options.php">';
  settings_fields( 'af-settings-group' );
  do_settings_sections( 'af-settings-group' );
  $count_posts = wp_count_posts();
  if ($count_posts->publish > 1000) {
    echo '<h3>Affinitomic License Exceeded.</h3>';
    echo '<p>Please Contact Prefrent For Expanded Post Coverage.</p>';
    echo '<hr />';
  }
/*
Affinitomics Commercial Code
*/

  $af_cloud_url = get_option('af_cloud_url', '');
  echo '<h4>Affinitomics&trade; API URL</h4>';
  echo '<input type="text" name="af_cloud_url" value="'.$af_cloud_url.'" />';

  $af_key = get_option('af_key', '');
  echo '<h4>Affinitomics&trade; API Key</h4>';
  echo '<input type="text" name="af_key" value="'.$af_key.'" />';

  $af_domain = get_option('af_domain', '');
  echo '<h4>Affinitomics&trade; Account Domain Name</h4>';
  echo '<input type="text" name="af_domain" value="'.$af_domain.'" />';
  echo '<h4>Need an API key? Get it <a href="http://prefrent.com/get-api-key/" target="_blank">here</a></h4>';

  $af_post_type_affinitomics = get_option('af_post_type_affinitomics');
  $af_post_type_posts = get_option('af_post_type_posts');
  $af_post_type_pages = get_option('af_post_type_pages');
  $af_post_type_affinitomics_checked = '';
  $af_post_type_posts_checked = '';
  $af_post_type_pages_checked = '';
  if ($af_post_type_affinitomics == 'true') $af_post_type_affinitomics_checked = 'checked="checked"';
  if ($af_post_type_pages == 'true') $af_post_type_pages_checked = 'checked="checked"';
  if ($af_post_type_posts == 'true') $af_post_type_posts_checked = 'checked="checked"';
  echo '<h3>To which Post-types would you like to apply your Affinitomics&trade;?</h3>';
  echo '<input type="checkbox" name="af_post_type_affinitomics" value="true" '.$af_post_type_affinitomics_checked.' /> Affinitomic&trade; Archetypes<br />';
  echo '<input type="checkbox" name="af_post_type_posts" value="true" '.$af_post_type_posts_checked.'/> Posts<br />';
  echo '<input type="checkbox" name="af_post_type_pages" value="true" '.$af_post_type_pages_checked.'/> Pages<br />';

  $af_tag_descriptors = get_option( 'af_tag_descriptors', 'true' );
  $true_checked = '';
  $false_checked = '';
  if ($af_tag_descriptors == 'true') $true_checked = 'checked="checked"';
  if ($af_tag_descriptors == 'false') $false_checked = 'checked="checked"';
// echo '<h3>Apply Affinitomics to Category Tags?</h3>';
//  echo '<input type="radio" name="af_tag_descriptors" value="true" '.$true_checked.'/> Yes<br />';
//  echo '<input type="radio" name="af_tag_descriptors" value="false"  '.$false_checked.'/> No<br />';

  $af_jumpsearch = get_option( 'af_jumpsearch', 'false' );
  $true_checked = '';
  $false_checked = '';
  if ($af_jumpsearch == 'true') $true_checked = 'checked="checked"';
  if ($af_jumpsearch == 'false') $false_checked = 'checked="checked"';
  echo '<h3>JumpSearch <span style="font-size:0.8em;font-weight:normal">( search using Affinitomics&trade; as context )</span></h3>';
  echo '<input type="radio" name="af_jumpsearch" value="true" '.$true_checked.'/> Yes<br />';
  echo '<input type="radio" name="af_jumpsearch" value="false" '.$false_checked.'/> No<br />';

  $af_google_cse_key = get_option('af_google_cse_key', '');
  echo '<h4>Google&trade; API Key</h4>';
  echo '<input type="text" name="af_google_cse_key" value="'.$af_google_cse_key.'" /> (<a href="https://cloud.google.com/console" target="_new">not sure what this is?</a>)';

  $af_google_cse_id = get_option('af_google_cse_id', '');
  echo '<h4>Google&trade; Custom Search Engine ID</h4>';
  echo '<input type="text" name="af_google_cse_id" value="'.$af_google_cse_id.'" /> (<a href="https://developers.google.com/custom-search/" target="_new">not sure what this is?</a>)';

  $af_jumpsearch_post_type_affinitomics = get_option('af_jumpsearch_post_type_affinitomics');
  $af_jumpsearch_post_type_posts = get_option('af_jumpsearch_post_type_posts');
  $af_jumpsearch_post_type_pages = get_option('af_jumpsearch_post_type_pages');
  $af_jumpsearch_post_type_affinitomics_checked = '';
  $af_jumpsearch_post_type_posts_checked = '';
  $af_jumpsearch_post_type_pages_checked = '';
  if ($af_jumpsearch_post_type_affinitomics == 'true') $af_jumpsearch_post_type_affinitomics_checked = 'checked="checked"';
  if ($af_jumpsearch_post_type_posts == 'true') $af_jumpsearch_post_type_posts_checked = 'checked="checked"';
  if ($af_jumpsearch_post_type_pages == 'true') $af_jumpsearch_post_type_pages_checked = 'checked="checked"';
  echo '<h4>Which Pages or Post-types should have a JumpSearch field?</h4>';
  echo '<input type="checkbox" name="af_jumpsearch_post_type_affinitomics" value="true"  '.$af_jumpsearch_post_type_affinitomics_checked.' /> Affinitomic&trade; Archetypes<br />';
  echo '<input type="checkbox" name="af_jumpsearch_post_type_posts" value="true" '.$af_jumpsearch_post_type_posts_checked.'/> Posts<br />';
  echo '<input type="checkbox" name="af_jumpsearch_post_type_pages" value="true" '.$af_jumpsearch_post_type_pages_checked.'/> Pages<br />';

  $af_jumpsearch_location = get_option( 'af_jumpsearch_location', 'bottom' );
  $top_checked = '';
  $bottom_checked = '';
  if ($af_jumpsearch_location == 'top') $top_checked = 'checked="checked"';
  if ($af_jumpsearch_location == 'bottom') $bottom_checked = 'checked="checked"';
  echo '<h4>Where on Pages or Post-types should the JumpSearch field appear?</h4>';
  echo '<input type="radio" name="af_jumpsearch_location" value="top" '.$top_checked.'/> Top of the Page or Post<br />';
  echo '<input type="radio" name="af_jumpsearch_location" value="bottom" '.$bottom_checked.'/> Bottom of the Page or Post<br />';

  submit_button();
  echo '</form>';
  echo '</div>';

  echo '<hr/>';
  echo '<a href="http://plugins.prefrent.com/"><img src="http://prefrent.com/wp-content/assets/affinitomics-by.png" height="30" width="191"/></a>';
}

function af_register_settings() {
  register_setting('af-settings-group', 'af_cloud_url');
  register_setting('af-settings-group', 'af_domain');
  register_setting('af-settings-group', 'af_key');
  register_setting('af-settings-group', 'af_post_type_affinitomics');
  register_setting('af-settings-group', 'af_post_type_posts');
  register_setting('af-settings-group', 'af_post_type_pages');
  register_setting('af-settings-group', 'af_tag_descriptors');
  register_setting('af-settings-group', 'af_jumpsearch');
  register_setting('af-settings-group', 'af_google_cse_key');
  register_setting('af-settings-group', 'af_google_cse_id');
  register_setting('af-settings-group', 'af_jumpsearch_post_type_affinitomics');
  register_setting('af-settings-group', 'af_jumpsearch_post_type_posts');
  register_setting('af-settings-group', 'af_jumpsearch_post_type_pages');
  register_setting('af-settings-group', 'af_jumpsearch_location');
  register_setting('af-cloud-settings-group', 'af_cloudify');
}

/*
----------------------------------------------------------------------
Google Search with Affinitomics
----------------------------------------------------------------------
----------------------------------------------------------------------
Search HTML Produced by Google CSE:
----------------------------------------------------------------------
<div id="af-search">
      <h2>Search Using Affinitomic Profile:</h2>
      <form action="" method="post" name="afsearch">
          <input type="hidden" name="a" id="a" value="%22nokia%22+%22microsoft%22+-%22apple%22+-%22google%22+-%22tim+cook%22">
          <input type="text" name="q" id="q" value="joe">
          <input type="submit">
      </form>
      <ul id="search-content">
        <li><a href="#">result 1</a></li>
        <li><a href="#">result 2</a></li>
        <li><a href="#">result 3</a></li>
        <li><a href="#">result 4</a></li>
        <li><a href="#">result 5</a></li>
        <li><a href="#">result 6</a></li>
        <li><a href="#">result 7</a></li>
      </ul>
  </div>

----------------------------------------------------------------------
  CSS Styling Examples:
----------------------------------------------------------------------
  #af-search h2 {background-color:magenta;}
  #search-content  {background-color:green;}
*/
if (get_option('af_jumpsearch') == 'true') {
  add_filter( 'the_content', 'af_search_content_filter', 20 );
}
function af_search_content_filter( $content ) {

  if ( is_singular() ) {
    $cse = '';
    $cse .= '<script>';
    // Search Engine ID
    $cse .= "var cx = '" . get_option('af_google_cse_id') . "';";
    // API Key
    $cse .= "var key = '" . get_option('af_google_cse_key') . "';";
      $q = '';
      if (isset($_REQUEST['q'])) {
        $q = htmlspecialchars(strip_tags($_REQUEST['q']));
        $cse .= 'var q = "' . $q . '";';
      } else {
        $cse .= 'var q = "";';
      }
      $a = '';
      if (isset($_REQUEST['a'])) {
        $a = htmlspecialchars(strip_tags($_REQUEST['a']));
        $cse .= 'var a = "' . $a . '";';
      } else {
        $cse .= 'var a = "";';
      }
    $cse .= '</script>';

    $post_id = get_the_ID();

    // Get Affinitomic Meta Data
    $descriptors_meta = get_post_meta($post_id, '_afpost_descriptors', true);
    $draw_meta = get_post_meta($post_id, '_afpost_draw', true);
    $distance_meta = get_post_meta($post_id, '_afpost_distance', true);

    // Use Meta Data to Build Affinitomic Search String
    $affinitomics = '';
    if ($descriptors_meta != '') {
      $affinitomics = $descriptors_meta;
    }
    if ($draw_meta != '') {
      if ($affinitomics == '') {
        $affinitomics = $draw_meta;
      } else {
        $affinitomics .= ', ' . $draw_meta;
      }
    }
    if ($distance_meta != '') {
      if ($affinitomics == '') {
        $affinitomics = $distance_meta;
      } else {
        $affinitomics .= ', ' . $distance_meta;
      }
    }

    // Clean Up Search String For Google
    $affinitomics = str_replace(range(0,9),'',$affinitomics);
    $affinitomics = str_replace('+','',$affinitomics);
    $affinitomics = str_replace(',','%22%22',$affinitomics);
    $affinitomics = str_replace('%22 ','%22',$affinitomics);
    $affinitomics = str_replace('%22%22','%22+%22',$affinitomics);
    $affinitomics = str_replace(' ','+',$affinitomics);
    $affinitomics = str_replace('%22-','-%22',$affinitomics);
    $affinitomics = '%22' . $affinitomics . '%22';

    if ($affinitomics != '') {
      $cse .= '<div>&nbsp;</div>';
      $cse .= '<div id="af-search">';
      $cse .= '<h2>Search Using Affinitomic Profile:</h2>';
      $cse .= '<form action="" method="post" name="afsearch">';
      $cse .= '<input type="hidden" name="a" id="a" value="' . $affinitomics .'" />';
      $cse .= '<input type="text" name="q" id="q" value="'. $q . '"/> ';
      $cse .= '<input type="submit"/>';
      $cse .= '</form><br />';
      $cse .= '<ul id="search-content"></ul>';
    }

    if (isset($_REQUEST['q'])) {
      /*
      <script>
          function gcs(response) {
            //console.log(JSON.stringify(response.searchInformation));
            if ((typeof response != 'undefined') && (response.searchInformation.totalResults > 0)){
              for (var i = 0; i < response.items.length; i++) {
                  var item = response.items[i];
                  document.getElementById("search-content").innerHTML += "<li><a href='" + item.link + "'>" + item.htmlTitle + "</a></li>";
              }
            } else {
                  document.getElementById("search-content").innerHTML += "<li>No results found.</li>";
            }
          }
          document.write("<script src='"+"https://www.googleapis.com/customsearch/v1?key="+key+"&cx="+cx+"&q="+q+" "+a+"&callback=gcs"+"'><\/script>");
      </script>
      */
      $cse .= "<script>\n";
      $cse .= "function gcs(response) {\n";
      $cse .= "//console.log(JSON.stringify(response.searchInformation));\n";
      $cse .= "if ((typeof response != 'undefined') && (response.searchInformation.totalResults > 0)){\n";
      $cse .= "for (var i = 0; i < response.items.length; i++) {\n";
      $cse .= "var item = response.items[i];\n";
      $cse .= "document.getElementById(\"search-content\").innerHTML += \"<li><a href='\" + item.link + \"'>\" + item.htmlTitle + \"</a></li>\";\n";
      $cse .= "}\n";
      $cse .= "} else {\n";
      $cse .= 'document.getElementById("search-content").innerHTML += "<li>No results found.</li>";';
      $cse .= "}\n";
      $cse .= "}\n";
      $cse .= "document.write(\"<script src='\"+\"https://www.googleapis.com/customsearch/v1?key=\"+key+\"&cx=\"+cx+\"&q=\"+q+\" \"+a+\"&callback=gcs\"+\"'><\/script>\");\n";
      $cse .= "</script>\n";
    }
    $cse .= '</div><!-- af-search -->';


    $modified_content = '';
    if (get_option('af_jumpsearch_location') == 'top') $modified_content .= $cse;
    $modified_content .= $content;
    if (get_option('af_jumpsearch_location') == 'bottom') $modified_content .= $cse;
    return $modified_content;
  } else {
    return $content;
  } /* is_single() */
}
/*
End Affinitomics Commercial Code
*/

// CURL Request Function
function curl_request($url,$postdata=false) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_HEADER, false); 
  curl_setopt($ch, CURLINFO_HEADER_OUT, false);
  curl_setopt($ch, CURLOPT_VERBOSE, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
  curl_setopt($ch, CURLOPT_URL, $url);
  if ($postdata) {
    //urlify the data for the POST
    foreach($postdata as $key=>$value) { $fields_string .= rawurlencode($key).'='.rawurlencode($value).'&'; }
    rtrim($fields_string, '&');
    curl_setopt($ch,CURLOPT_POST, count($postdata));
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  }
  $response = curl_exec($ch);
  curl_close($ch);
  return $response;
}

?>
