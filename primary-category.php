<?php
/* Plugin Name: Primary Category
   Plugin URI: http://konkurrer.com/
   Description: Allows publishers to select a primary category/taxonomy for Posts/CPT
   Version: 1.0
   Author: pratik028
   Text Domain: primary-category
   Domain Path: /languages
   License: GPL
   License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

function pc_load_scripts() {
    // scripts
    wp_enqueue_script(array(
        'jquery',
    ));
    wp_enqueue_script( 'primar-caetgory-script', plugins_url( '/admin/js/primary-category.js', __FILE__ ), array( 'jquery' ) );
    wp_enqueue_style( 'primary-category-style', plugins_url( '/admin/css/primary-category.css', __FILE__ ) );
}

add_action( 'admin_enqueue_scripts', 'pc_load_scripts' );


function primary_category_footer_callback() {

    global $post_type, $post;
    if(!empty($post)) {
        $taxonomies = get_object_taxonomies( $post_type, 'objects' );
        if(!empty($taxonomies)) {
        ?>
            <!--Used to add Make-primary/Primary button in Category metabox-->
            <script type="text/html" id="pc-make-primary-wrapper">
                <a class="pc-make-primary-term" href="#"><?php _e('Make primary', 'primary-category'); ?></a>
                <span class="pc-primary-term hidden"><?php _e('Primary', 'primary-category'); ?></span>
            </script>

            <?php
            foreach($taxonomies as $taxonomy) {
                if($taxonomy->hierarchical != '') {
                    $primary_term = get_post_meta($post->ID, '_primary_'. $taxonomy->name .'_id', true);
            ?>
                    <!-- Used to add hidden selected taxonomy term value for each taxonomy -->
                    <script type="text/html" id="pc-primary-<?php echo $taxonomy->name; ?>-input">
                        <input type="hidden" class="pc-primary-term" id="pc-primary-<?php echo esc_attr($taxonomy->name) ?>-term" name="primary-<?php echo esc_attr($taxonomy->name) ?>-term" value="<?php echo esc_attr($primary_term); ?>" />
                            <?php wp_nonce_field( 'save-primary-term', 'pc_primary_'.$taxonomy->name.'_nonce' ); ?>
                    </script>

    <?php       }
            }

        }
    }

}

add_action('admin_footer', 'primary_category_footer_callback');

function primary_category_save_post_callback( $post_id ) {

    // If this is just a revision, don't send the email.
    if ( wp_is_post_revision( $post_id ) || defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        return;

    $post_type  = get_post_type( $post_id );
    $taxonomies = get_object_taxonomies( $post_type, 'objects' );

    foreach($taxonomies as $taxonomy) {

        $primary_term = filter_input( INPUT_POST, 'primary-' . $taxonomy->name . '-term', FILTER_SANITIZE_NUMBER_INT );
        
        if ( null !== $primary_term && check_admin_referer( 'save-primary-term', 'pc_primary_' . $taxonomy->name . '_nonce' ) ) {
            update_post_meta( $post_id, '_primary_'.$taxonomy->name.'_id', $primary_term ); // store primary category id in post_meta table
        }

    }

}
add_action( 'save_post', 'primary_category_save_post_callback' );

function get_primary_taxonomy_term($post_id, $taxonomy) {
    
    if( $post_id && $taxonomy ) {
        $primary_term_id = get_post_meta($post_id, '_primary_'.$taxonomy.'_id', true);
    }
    return $primary_term_id;

}