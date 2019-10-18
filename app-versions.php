<?php
/*
  Plugin Name: App Versions
  Description: App Versions Project
  Author: Caroline Torres
  Version: 1.0
 */

if(!defined('ABSPATH')) exit;

class AppVersions{

	private $type               = 'appversion';
    private $slug               = 'appversions';
    private $name               = 'AppVersions';
    private $singular_name      = 'AppVersion';

    public function __construct() {
        add_action('init', array($this, 'register'));        
        add_action('wp_enqueue_scripts', array($this,'appversions_css_js')); 
        add_action('add_meta_boxes', array($this,'meta_box_arcf'));
        add_action('add_meta_boxes', array($this,'meta_box_date'));
        add_action('save_post', array($this,'save_post_data'));    
        add_filter('single_template', array($this,'single_template'));   
        add_filter('archive_template', array($this,'archive_template'));
    }

    //Template Single
    public function single_template($single_template) {
        global $post;
        if ($post->post_type == $this->type ) {
            $single_template = dirname( __FILE__ ) . '/templates/single-appversions.php';
        }
        return $single_template;
    }

    //Template Archive
    public function archive_template( $archive_template ) {
        global $post;
        if ( is_post_type_archive ( $this->type ) ) {
            $archive_template = dirname( __FILE__ ) . '/templates/archive-appversions.php';
        }
        return $archive_template;
    }

    //CSS Inclusions 
    public function appversions_css_js() {
        if ( is_post_type_archive ( $this->type ) || is_singular( $this->type ) ) {
            wp_enqueue_style('appversions-style', plugin_dir_url( __FILE__ ) . 'css/style.css');
            wp_enqueue_style('bootstrap_css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css', array(), 20141119);
        }
    }

    public function register() {
        $labels = array(
            'name'                  => $this->name,
            'singular_name'         => $this->singular_name,
            'add_new'               => 'Add New',
            'add_new_item'          => 'Add New '   . $this->singular_name,
            'edit_item'             => 'Edit '      . $this->singular_name,
            'new_item'              => 'New '       . $this->singular_name,
            'all_items'             => 'All '       . $this->name,
            'view_item'             => 'View '      . $this->name,
            'search_items'          => 'Search '    . $this->name,
            'not_found'             => 'No '        . strtolower($this->name) . ' found',
            'not_found_in_trash'    => 'No '        . strtolower($this->name) . ' found in Trash',
            'parent_item_colon'     => '',
            'menu_name'             => $this->name
        );
        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'query_var'             => true,
            'rewrite'               => array( 'slug' => $this->slug ),
            'capability_type'       => 'post',
            'has_archive'           => true,
            'hierarchical'          => true,
            'menu_position'         => 8,
            'supports'              => array( 'title', 'editor' ),
            'yarpp_support'         => true
        );
        register_post_type( $this->type, $args );
    }


    public function meta_box_date() {
        add_meta_box( 
            $this->name . 'Date', 
            'Release Date', 
            array($this, 'metabox_display_date'), 
            $this->type, 
            'side', 
            'default'
        ); 
    }

    public function meta_box_arcf() {
        add_meta_box( 
            $this->name . 'ARCF', 
            'Added, Removed, Changed, Fixed', 
            array($this, 'metabox_display_arcf'), 
            $this->type, 
            'normal', 
            'default'
        );  
    }

    public function metabox_display_date() {
         global $post;
    ?>      
        <div class="row">
            <div class="col-md-12">
                <input type="date" class="form-control" name="release_date" value="<?php echo get_post_meta($post->ID,'release_date',true); ?>" >
            </div>
        </div>
    <?php }


    public function metabox_display_arcf() {

        global $post;
        $repeatable_fields = get_post_meta($post->ID, 'repeatable_fields', true);
        wp_nonce_field( 'hhs_repeatable_meta_box_nonce', 'hhs_repeatable_meta_box_nonce' );

    ?>

        <script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ) . 'js/index.js' ?>"></script>

        <!-- ADDED -->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <table id="repeatable-fieldset-one-added" class="table table-borderless">
                        <thead>
                            <th colspan="2">Added</th>
                        </thead>
                        <tbody>
                            <?php                
                                if ( $repeatable_fields ) :                
                                foreach ( $repeatable_fields as $field ) {
                            ?>

                            <tr>
                                <td class="col-md-8"><input type="text" class="form-control" name="feature[]" value="<?php if($field['feature'] != '') echo esc_attr( $field['feature'] ); ?>" /></td> 
                                <td class="col-md-4"><a class="button remove-row-added" href="#">Remove</a></td>
                            </tr>

                            <?php }
                                else :
                                // show a blank one
                            ?>

                            <tr>
                                <td class="col-md-8"><input type="text" class="form-control" name="feature[]" /></td>                                             
                                <td class="col-md-4"><a class="button remove-row-added" href="#">Remove</a></td>
                            </tr>

                            <?php endif; ?>
                            
                            <!-- empty hidden one for jQuery -->
                            <tr class="empty-row-added screen-reader-text">
                                <td class="col-md-8"><input type="text" class="form-control" name="feature[]" /></td>                      
                                <td class="col-md-4"><a class="button remove-row-added" href="#">Remove</a></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                               <td class="col-md-12" colspan="2"><a id="add-row-added" class="button" href="#">Add another</a></td>    
                            </tr>
                        </tfoot>
                    </table>
                </div>    
            </div>    
        </div>  

        <!-- REMOVED -->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <table id="repeatable-fieldset-one-removed" class="table table-borderless">
                        <thead>
                            <th colspan="2">Removed</th>
                        </thead>
                        <tbody>
                            <?php                
                                if ( $repeatable_fields ) :                
                                foreach ( $repeatable_fields as $field ) {
                            ?>

                            <tr>
                                <td class="col-md-8"><input type="text" class="form-control" name="remove[]" value="<?php if($field['remove'] != '') echo esc_attr( $field['remove'] ); ?>" /></td> 
                                <td class="col-md-4"><a class="button remove-row-removed" href="#">Remove</a></td>
                            </tr>

                            <?php }
                                else :
                                // show a blank one
                            ?>

                            <tr>
                                <td class="col-md-8"><input type="text" class="form-control" name="remove[]" /></td>                                             
                                <td class="col-md-4"><a class="button remove-row-removed" href="#">Remove</a></td>
                            </tr>

                            <?php endif; ?>
                            
                            <!-- empty hidden one for jQuery -->
                            <tr class="empty-row-removed screen-reader-text">
                                <td class="col-md-8"><input type="text" class="form-control" name="remove[]" /></td>                      
                                <td class="col-md-4"><a class="button remove-row-removed" href="#">Remove</a></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                               <td class="col-md-12" colspan="2"><a id="add-row-removed" class="button" href="#">Add another</a></td>    
                            </tr>
                        </tfoot>
                    </table>
                </div>    
            </div>    
        </div>         

        <!-- CHANGED -->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <table id="repeatable-fieldset-one-changed" class="table table-borderless">
                        <thead>
                            <th colspan="2">Changed</th>
                        </thead>
                        <tbody>
                            <?php                
                                if ( $repeatable_fields ) :                
                                foreach ( $repeatable_fields as $field ) {
                            ?>

                            <tr>
                                <td class="col-md-8"><input type="text" class="form-control" name="change[]" value="<?php if($field['change'] != '') echo esc_attr( $field['change'] ); ?>" /></td> 
                                <td class="col-md-4"><a class="button remove-row-changed" href="#">Remove</a></td>
                            </tr>

                            <?php }
                                else :
                                // show a blank one
                            ?>

                            <tr>
                                <td class="col-md-8"><input type="text" class="form-control" name="change[]" /></td>                                            
                                <td class="col-md-4"><a class="button remove-row-changed" href="#">Remove</a></td>
                            </tr>

                            <?php endif; ?>
                            
                            <!-- empty hidden one for jQuery -->
                            <tr class="empty-row-changed screen-reader-text">
                                <td class="col-md-8"><input type="text" class="form-control" name="change[]" /></td>                      
                                <td class="col-md-4"><a class="button remove-row-changed" href="#">Remove</a></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                               <td class="col-md-12" colspan="2"><a id="add-row-changed" class="button" href="#">Add another</a></td>    
                            </tr>
                        </tfoot>
                    </table>
                </div>    
            </div>    
        </div>  
        

        <!-- FIXED -->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <table id="repeatable-fieldset-one-fixed" class="table table-borderless">
                        <thead>
                            <th colspan="2">Fixed</th>
                        </thead>
                        <tbody>
                            <?php                
                                if ( $repeatable_fields ) :                
                                foreach ( $repeatable_fields as $field ) {
                            ?>

                            <tr>
                                <td class="col-md-8"><input type="text" class="form-control" name="fix[]" value="<?php if($field['fix'] != '') echo esc_attr( $field['fix'] ); ?>" /></td> 
                                <td class="col-md-4"><a class="button remove-row-fixed" href="#">Remove</a></td>
                            </tr>

                            <?php }
                                else :
                                // show a blank one
                            ?>

                            <tr>
                                <td class="col-md-8"><input type="text" class="form-control" name="fix[]" /></td>                                             
                                <td class="col-md-4"><a class="button remove-row-fixed" href="#">Remove</a></td>
                            </tr>

                            <?php endif; ?>
                            
                            <!-- empty hidden one for jQuery -->
                            <tr class="empty-row-fixed screen-reader-text">
                                <td class="col-md-8"><input type="text" class="form-control" name="fix[]" /></td>                      
                                <td class="col-md-4"><a class="button remove-row-fixed" href="#">Remove</a></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                               <td class="col-md-12" colspan="2"><a id="add-row-fixed" class="button" href="#">Add another</a></td>    
                            </tr>
                        </tfoot>
                    </table>
                </div>    
            </div>    
        </div>  


        <style type="text/css">
            .box {
                background-color: #f5f5f5;
                display: block;
                overflow: hidden;
                margin: 0 0 20px 0;
                padding: 10px;
            }
            .table td, .table th {
                padding: 5px 10px !important;
                width: 100%;
                text-align: left;
            }
            .table td input {
                width: 100%;
            }
        </style>



    <?php }
        

    public function save_post_data( $post_id ) {

        if ( ! isset( $_POST['hhs_repeatable_meta_box_nonce'] ) ||
            ! wp_verify_nonce( $_POST['hhs_repeatable_meta_box_nonce'], 'hhs_repeatable_meta_box_nonce' ) )
            return;
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;
        
        if (!current_user_can('edit_post', $post_id))
            return;
        
        $old = get_post_meta($post_id, 'repeatable_fields', true);
        $new = array();
        
        $features     = $_POST['feature'];
        $remove       = $_POST['remove'];
        $changes      = $_POST['change'];
        $fixes        = $_POST['fix'];
        
        $fcount  = count( $features );
        $rcount  = count( $remove );
        $ccount  = count( $changes );
        $fxcount = count( $fixes );
        
        for ( $i = 0; $i < $fcount; $i++ ) {
            if ( $features[$i] != '' ) :
                $new[$i]['feature'] = stripslashes( strip_tags( $features[$i] ) );
            endif;
        }

        for ( $i = 0; $i < $rcount; $i++ ) {
            if ( $remove[$i] != '' ) :
                $new[$i]['remove'] = stripslashes( strip_tags( $remove[$i] ) );
            endif;
        }        

        for ( $i = 0; $i < $ccount; $i++ ) {
            if ( $changes[$i] != '' ) :
                $new[$i]['change'] = stripslashes( strip_tags( $changes[$i] ) );
            endif;
        }       

        for ( $i = 0; $i < $fxcount; $i++ ) {
            if ( $fixes[$i] != '' ) :
                $new[$i]['fix'] = stripslashes( strip_tags( $fixes[$i] ) );
            endif;
        }         

        if ( isset( $_POST['release_date'] ) ) {
            update_post_meta( $post_id, 'release_date', sanitize_text_field( $_POST['release_date'] ) );
        }

        if ( !empty( $new ) && $new != $old )
            update_post_meta( $post_id, 'repeatable_fields', $new );           
        elseif ( empty($new) && $old )
            delete_post_meta( $post_id, 'repeatable_fields', $old );
    }    

}

new AppVersions();
