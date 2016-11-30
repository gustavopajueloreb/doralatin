<?php
/* ----------------------------------------------------------------------
	Plugin Name:	WIP - Funciones
	Plugin URI:		http://www.webideaperu.net
	Description:	Funciones necesarias para el funcionamiento de los temas
	Version:		1.2.WIP-20160219
	Author:			Gustavo Pajuelo Vargas
	Author URI:		http://www.webideaperu.net
 * ---------------------------------------------------------------------- */

/**
 * Table of contents
 *
 *		System
 *		Optimization
 *		Security
 *		
 */

/*-----------------------------------------------------------------------------------*/
/*		System
/*-----------------------------------------------------------------------------------*/

add_action('wp_enqueue_scripts', 'wip_scripts');
function wip_scripts() {
	wp_enqueue_style( 'custom-css', plugin_dir_url(__FILE__) . "/wip-funciones.css", false, GENERATE_VERSION, 'all' );
}

/**
 * Measurement in footer
 *-------------------------------------------------*
 * @link http://codex.wordpress.org/Plugin_API/Action_Reference/wp_footer
 */
function wip_funciones_measurement_in_footer() {
	// if user administrator
	if (current_user_can('administrator')) {
		
$rebistaversion = 'General 2.2.3-VI';

		echo '<style>.wip_ {	clear: both;	background: #ccc;	padding: 10px 150px;	height: 60px;	line-height: 25px;	}</style>
			<div class="wip_"><strong>Modelo ' . $rebistaversion . '</strong><br />' . round(memory_get_usage() / 1048576,2) . ' MB. '. get_num_queries() .' consultas. '. timer_stop() .' segundos.</div>';
	}
}
add_action('wp_footer', 'wip_funciones_measurement_in_footer', 100);


/**
 *	Link Back to Your WordPress Site from Copy & Pasted Text
 *-------------------------------------------------*
 * @link http://wpmu.org/wordpress-copied-text-link-back/
 */
function wip_funciones_add_copyright_text() {
	if (is_single()) { ?>

<script type='text/javascript'>
function wip_funciones_add_link() {
    if (
window.getSelection().containsNode(
document.getElementsByClassName('entry-content')[0], true)) {
    var body_element = document.getElementsByTagName('body')[0];
    var selection;
    selection = window.getSelection();
    var oldselection = selection
    var pagelink = "<br /><br /> Fuente: <a href='<?php echo get_permalink(get_the_ID()); ?>'><?php echo get_permalink(get_the_ID()); ?></a>";
    var copy_text = selection + pagelink;
    var new_div = document.createElement('div');
    new_div.style.left='-99999px';
    new_div.style.position='absolute';

    body_element.appendChild(new_div );
    new_div.innerHTML = copy_text ;
    selection.selectAllChildren(new_div );
    window.setTimeout(function() {
        body_element.removeChild(new_div );
    },0);
}
}
document.oncopy = wip_funciones_add_link;
</script>
<?php
	}
}
add_action( 'wp_head', 'wip_funciones_add_copyright_text');



/*-----------------------------------------------------------------------------------*/
/*		Optimization
/*-----------------------------------------------------------------------------------*/
/**
 * Loading jQuery from the Google CDN EN EVALUACION
 *-------------------------------------------------*
 */
// register from google and for footer
function wip_funciones_jquery() {
	if (!is_admin()) {
		// remove the default jQuery script
		wp_deregister_script('jquery');
		  
		// register the Google hosted Version
		wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"), false, '');
		//wp_register_script('jquery-ui', ("http://code.jquery.com/ui/1.8.20/jquery-ui.js"), false, '');
		  
		// add it back into the queue
		wp_enqueue_script('jquery');
		//wp_enqueue_script('jquery-ui');
		}
	}
// even more smart jquery inclusion :)
add_action('init', 'wip_funciones_jquery');

/**
 *	Better jpg quality
 *-------------------------------------------------*
 */
function wip_funciones_jpeg_quality($arg) {
	return (int)100;
}
add_filter('jpeg_quality', 'wip_funciones_jpeg_quality');



/**
 *	Set the post revisions limit
 *-------------------------------------------------*
 */
if (!defined('WP_POST_REVISIONS')) define('WP_POST_REVISIONS', 5);


/*-----------------------------------------------------------------------------------*/
/*			Security
/*-----------------------------------------------------------------------------------*/
/**
 *	Change login errors EN EVALUACION
 *-------------------------------------------------*
 */
function wip_funciones_wrong_login() {
	return 'Wrong username or password.';
}
add_filter('login_errors', 'wip_funciones_wrong_login');


/**
 *	Disable File Editor
 *-------------------------------------------------*
 */
define('DISALLOW_FILE_EDIT', true);


/**
 * Disable updates for plugins
 *-------------------------------------------------*
 */
// Disable all the Nags & Notifications
function remove_core_updates(){
global $wp_version;return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
}
add_filter('pre_site_transient_update_core','remove_core_updates');
add_filter('pre_site_transient_update_plugins','remove_core_updates');
add_filter('pre_site_transient_update_themes','remove_core_updates');


/**
 *	Remove unnecessary meta elements from head
 *-------------------------------------------------*
 */
remove_action('wp_head', 'rsd_link');					//<link rel="EditURI" type="application/rsd+xml" title="RSD" href="/wp/xmlrpc.php?rsd" />
//remove_action('wp_head', 'wp_generator');				//<meta name="generator" content="Bluefish 2.2.5" />
remove_action('wp_head', 'feed_links', 2);			//rel="alternate" type="application/rss+xml" //feed y comments feed
remove_action('wp_head', 'wp_shortlink_wp_head');	//rel='shortlink'
remove_action('wp_head', 'wlwmanifest_link');		//rel="wlwmanifest" type="application/wlwmanifest+xml"
remove_action('wp_head', 'feed_links_extra', 3);	//rel="alternate" type="application/rss+xml" title="sitios de pruebas &raquo; otro post Comments Feed" href="http://127.0.0.1/wordpress3.4/otro-post/feed/" /

/* Removes prev and next article links */
add_filter( 'index_rel_link', '__return_false' );
add_filter( 'parent_post_rel_link', '__return_false' );
add_filter( 'start_post_rel_link', '__return_false' );
add_filter( 'previous_post_rel_link', '__return_false' );
add_filter( 'next_post_rel_link', '__return_false' );

/* Remove the WordPress Version Number - The Right Way */
function remove_wp_version() { return ''; }
add_filter('the_generator', 'remove_wp_version');

/* Stop Adding Functions Below this Line */

/*
 * WIP Functions for web url - GUSTAVO
 */

if ( function_exists('bootstrap_script') ):
	/**
	 * DENIED
	 */
	add_action('wp_enqueue_scripts', 'bootstrap_script' );
	function bootstrap_script() {
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', false);
		wp_enqueue_style( 'bootstrap-theme', get_template_directory_uri() . '/css/bootstrap-theme.min.css', false);
		wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'));
	}
endif; 

if ( ! function_exists('wip_create_cpt') ):
/**
 * Crear CUSTOM POST TYPE chapter
 */
add_action('init', 'wip_create_cpt');
function wip_create_cpt() {
    register_post_type('chapter',
            // CPT Options
            array(
        'labels' => array(
            'name' => __('Capítulos'),
            'singular_name' => __('Capítulo'),
            'all_items' => __('Todos los capítulos'),
            'add_new_item' => __('Añadir nuevo capítulo'),
            'add_new' => __('Añadir nuevo'),
            'edit_item' => __('Editar capítulo'),
            'search_items' => __('Buscar capítulos'),
            'view_item' => __('Ver capítulo')
        ),
        'public' => true,
        'has_archive' => true,
        // Que mostrar por defecto en el edit.php
        'supports' => array('title', 'editor', 'thumbnail', 'comments', 'revisions'),
        'rewrite' => array('slug' => 'capitulo'), //importante
        'menu_icon' => 'dashicons-format-video'
            )
    );
}
endif;

if (function_exists('wip_create_cpt')) {
/**
 * Registrando una nueva taxonomia con jerarquía similar a "CATEGORIA": season, ...
 */
add_action('init', 'wip_create_hierarchical_taxonomy', 0);
    function wip_create_hierarchical_taxonomy() {
        //first do the translations part for GUI
        $labels = array(
            'name' => _x('Temporada', 'taxonomy general name'),
            'singular_name' => _x('Temporada', 'taxonomy singular name'),
            'search_items' => __('Buscar temporadas'),
            'all_items' => __('Todas las temporadas'),
            'parent_item' => __('Temporada padre'),
            'parent_item_colon' => __('Temporada padre:'),
            'edit_item' => __('Editar temporada'),
            'update_item' => __('Actualizar temporada'),
            'add_new_item' => __('Añadir nueva temporada'),
            'new_item_name' => __('Nuevo nombre de temporada'),
            'menu_name' => __('Temporadas'),
        );
        // Now register the taxonomy
        register_taxonomy('season', array('chapter'), array(
            'hierarchical' => true, //comportamiento de "categoria"
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'meta_box_cb' => 'season_meta_box', //creando meta box particular, funcion
            'rewrite' => array('slug' => 'temporada'),
        ));
    }  
}

if (function_exists('wip_create_hierarchical_taxonomy')) {
    /**
     * Display taxonomy meta box and save the taxonomy meta box results.
     */
    //Season
    function season_meta_box($post) {
        $terms = get_terms('season', array('hide_empty' => false));
        $post = get_post();
        $season = wp_get_object_terms($post->ID, 'season', array('orderby' => 'term_id', 'order' => 'ASC'));
        $name = '';
        if (!is_wp_error($season)) {
            if (isset($season[0]) && isset($season[0]->name)) {
                $name = $season[0]->name;
            } else {
        ?>
	        <label title='nothing'>
	            <input type="radio" name="season" value=null <?php checked(null, $name); ?>>
	            <span>Nothing</span>
	        </label><br>  	
        <?php }
        } 
        foreach ($terms as $term) { ?>
            <label title='<?php esc_attr_e($term->name); ?>'>
                <input type="radio" name="season" value="<?php esc_attr_e($term->name); ?>" <?php checked($term->name, $name); ?>>
                <span><?php esc_html_e($term->name); ?></span>
            </label><br>
            <?php
        }
    }

    if (function_exists('season_meta_box')) {
    	add_action('save_post', 'season_save_meta_box');
        function season_save_meta_box($post_id) {
            $post = get_post($post_id);
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }
            if ($post->post_type != 'chapter') {
                return;
            }
            $season = sanitize_text_field($_POST['season']);
            $term = get_term_by('name', $season, 'season');
            if (!empty($term) && !is_wp_error($term)) {
                wp_set_object_terms($post_id, $term->term_id, 'season', false);
            }
        }      
    }
}

if (function_exists('season_meta_box')):
/**
 * Remove action row "Quick edit" for chapter
 */
add_filter('post_row_actions','wip_chapter_action_row', 10, 2);
function wip_chapter_action_row($actions, $post){
	if ($post->post_type =="chapter"){
	    unset( $actions['inline hide-if-no-js'] );
	}
	return $actions;
}

/**
 * Remove bulk action "Edit" for chapter
 */
add_filter('bulk_actions-edit-chapter','wip_chapter_bulk_actions');
function wip_chapter_bulk_actions($actions){
	unset( $actions['edit'] );
	return $actions;
}
endif; 

if ( class_exists( 'RW_Meta_Box' ) ):
add_filter( 'rwmb_meta_boxes', 'wip_register_meta_boxes' );
function wip_register_meta_boxes( $meta_boxes ) {
    $prefix = 'wip';
   	
    $fields = array(
    		array(
                'name'  		=> __( 'Número de capítulo', 'generatepress' ),
                'id'    		=> $prefix . '_num',
                'type'  		=> 'number',
                'std'   		=> '',
                'class' 		=> '',
                'step'			=> 1,
                'min'			=> 1,
                'attributes'	=> array( 'required' => true )
            ),
            array(
                'type'  		=> 'divider',
                'std'   		=> '',
                'class' 		=> '',
            ),
            array(
                'name'  		=> __( 'Fecha de estreno', 'generatepress' ),
                'id'    		=> $prefix . '_date',
                'type' 			=> 'date',
                'std'   		=> '',
                'class' 		=> '',
                'js_options'	=> array( 'dateFormat' => 'dd/mm/yy' )
            ),
            array(
                'type'  		=> 'divider',
                'std'   		=> '',
                'class' 		=> '',
            ),
            array(
                'name'  		=> __( 'Video URL', 'generatepress' ),
                'id'    		=> $prefix . '_video_url',
                'type' 			=> 'text',
                'std'   		=> '',
                'placeholder' 	=> 'http://',
                'class' 		=> '',
                'clone' 		=> true,
                'attributes'	=> array( 'required' => true )
            ),
    );
    $meta_boxes[] = array(
        'id'         => $prefix . '_additional',
        'title'      => __( 'Chapter Information', 'generatepress' ),
        'post_types' => array( 'chapter' ),
        'context'    => 'normal',
        'priority'   => 'high',
        'fields' 	 => $fields
    );

    return $meta_boxes;
}	
endif;

if (function_exists('wip_register_meta_boxes')):
/**
 * Add script after wip_additional metabox for chapter post
 */
add_action( 'rwmb_after_wip_additional', 'wip_additional_script' );
function wip_additional_script() {
	global $post;
?>
	<script type="text/javascript">	
		(function( $ ){
		    $.fn.num_unique = function() {
				$.post(
					ajaxurl,
					{
						'action' : 'wip-num-unique',
						'term' : $('input[name=season]:checked').val(),
						'post_id' : <?php echo $post->ID; ?>
					}, function(data) {
						var caps = data;
						var repeat = false;

				    	for (var i = 0; i < caps.length; i++) {
				    		if (caps[i] == $("#wip_num").val()) { repeat=true; };
				    	};
				    	if (repeat) { 
				    		alert('Inserte un numero de capítulo único');
				    		$("#wip_num").val("");
				    		repeat = false; 
				    	};
					}
				).fail(function(xhr, ajaxOptions, thrownError) { 
					alert(thrownError); //alert any HTTP error
				});
		    }; 
		})( jQuery );

		jQuery(function($) {
			$("#wip_num").num_unique();
		    $("#wip_num").focusout(function(){
		    	$(this).num_unique();
		    });
		    $("input[name=season]").change(function(){
		    	$("#wip_num").num_unique();
		    });
		});
	</script>
<?php
}

/**
 * Add function event for ajax for wip_additional metabox script
 */
add_action( 'wp_ajax_nopriv_wip-num-unique', 'wip_num_unique' );
add_action( 'wp_ajax_wip-num-unique', 'wip_num_unique' );
function wip_num_unique() {
	//sanitize post value
	$superTerm = $_POST["term"];
	$superPostId = $_POST["post_id"];

	//throw HTTP error if page number is not valid
	if(!is_string($superTerm) && !is_numeric($superPostId)){
		header('HTTP/1.1 500 Invalid page number!');
		exit();
	}

	$caps = array();
	$term = get_term_by('name', $superTerm, 'season');

	$post_arr = get_posts( array(
  			'post_type' => 'chapter',
  			'exclude' => $superPostId,
			'tax_query' => array(
				array(
				    'taxonomy' => 'season',
				    'field' => 'id',
				    'terms' => $term->term_id,
				    'include_children' => false
				)
			)
	));
	foreach ($post_arr as $t_post) {
		array_push($caps, get_post_meta( $t_post->ID, 'wip_num', true ));
	}
	echo json_encode($caps);
	exit();
}
endif;

if (function_exists('wip_create_cpt')):
/**
 * Custom title for chapter post type
 */
add_filter( 'the_title', 'wip_chapter_title', 10, 2);
function wip_chapter_title($title, $id) {
    if( get_post_type($id) == 'chapter' && in_the_loop() ) {
		return "Capítulo " . get_post_meta( $id, 'wip_num', true ) . " - " . $title;
	}
    return $title;
}
endif;

/**
 * GENERATEPRESS - After header
 */
add_action('generate_after_header_content', 'wip_header_content');
function wip_header_content() {
	?>
	<div class="header-png"></div>
	<?php
}

/**
 * Add widgets
 */
include_once(get_template_directory() . '/widgets/class-wip-widget-recent-chapters.php');
include_once(get_template_directory() . '/widgets/class-facebook-widget.php');

function wpb_set_post_views($postID) {
    $count_key = 'wpb_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
//To keep the count accurate, lets get rid of prefetching
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

add_action( 'wp_head', 'wpb_track_post_views');
function wpb_track_post_views ($post_id) {
    if ( !is_single() ) return;
    if ( empty ( $post_id) ) {
        global $post;
        $post_id = $post->ID;    
    }
    wpb_set_post_views($post_id);
}


?>