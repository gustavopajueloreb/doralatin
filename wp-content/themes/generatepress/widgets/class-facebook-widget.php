<?php
/**
 * Widget API: Fb_Widget class
 *
 * @package Generatpress
 * @subpackage Widgets
 * @since 1.0.0
 */
class Fb_Widget extends WP_Widget 
{
	// Constructor que se llamara cuando se inicialice el Widget
    function Fb_Widget()
    {	
		//nombre de clase, descripcion, y titulo.
        $widget_ops = array('classname' => 'Fb_Widget', 'description' => 'Agrega un plugin page asincrono de facebook' );
        $this->WP_Widget('Fb_Widget', 'FB Plugin page', $widget_ops);
    }
	
	//se encarga de guardar en la base de datos la configuración establecida para el Widget.
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['align'] = strip_tags($new_instance['align']);
		$instance['href']  = strip_tags($new_instance['href'] );
		$instance['width'] = strip_tags($new_instance['width']);
		$instance['height'] = strip_tags($new_instance['height']);
		$instance['show_cover'] = strip_tags($new_instance['show_cover']);
		$instance['show_facepile'] = strip_tags($new_instance['show_facepile']);
		$instance['show_posts'] = strip_tags($new_instance['show_posts']);
		$instance['language'] = strip_tags($new_instance['language']);

		return $instance; 
	}
	
	/*function activate($instance)
	{
		/ Comprobamos si existe opciones para este Widget, si no existe las creamos por el contrario actualizamos
		if( ! get_option($instance) )
			add_option($instance, $aData);
		else
			update_option($instance , $adata);
	}
		
	function deactivate($instance)
	{
		// Cuando se desactive el plugin se eliminaran todas las filas de la DB que le sirven a este plugin
		delete_option($instance);
	}*/
	
    // Panel de control que se mostrara abajo de nuestro Widget en el panel de configuración de Widgets
	function form($instance)
	{	
		//datos por defecto
		$aData = array(
			'title'         => 'Síguenos en Facebook',
			'href'          => 'https://www.facebook.com/Aeropuertos.Net',
			'width'         => '100%',
			'height'        => '250',
			'show_cover'    => '0',
			'show_facepile' => '1',
			'show_posts'    => '0',
			'align'         => 'initial',
			'language'		=> 'es_LA'
		);
		
		$instance = wp_parse_args( (array) $instance, $aData);
		
		// Mostraremos un formulario en HTML para modificar los valores del Widget
		$title = strip_tags( $instance['title'] );

		/**
		 * The URL of the Facebook Page (required)
		 *
		 * @var $href string This is the only required value.
		 */
		$href = strip_tags( $instance['href'] );
		/**
		 * The maximum pixel height of the plugin.
		 * Min. is 130
		 *
		 * @var $height array Defaults to 500.
		 */
		$height = range( 125, 800, 25 );

		/**
		 * Show cover photo in the header
		 */
		$show_cover = array( 'true' => 'Yes', 'false' => 'No' );

		/**
		 * Show profile photos when friends like this
		 */
		$show_facepile = array( 'true' => 'Yes', 'false' => 'No' );

		/**
		 * Show posts from the Page's timeline.
		 */
		$show_posts = array( 'true' => 'Yes', 'false' => 'No' );

		/**
		 * Alignment of the widget.
		 *
		 * @var $align array Allows initial, left, center, and right text-align.
		 */
		$align = array( 'initial' => 'None', 'left' => 'Left', 'center' => 'Center', 'right' => 'Right' );

		/**
		 * Facebook wants to be difficult and use the term "Hide Cover" instead of show cover.
		 */
		$reverse_boolean = array ( 0 => 'Yes', 1 => 'No' );

		$boolean = array( 1 => 'Yes', 0 => 'No' );

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Titulo: </label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'href' ); ?>">Url de fan page: </label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'href' ); ?>" name="<?php echo $this->get_field_name( 'href' ); ?>" value="<?php echo esc_attr( $instance['href'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('width'); ?>">Ancho (% o px): </label>
			<input class="fanbox_width" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo esc_attr($instance["width"]); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'height' ); ?>">Alto: </label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>">
				<?php foreach ( $height as $val ): ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $instance['height'], $val ); ?>><?php echo esc_html( $val ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_cover' ); ?>">Ver foto portada?: </label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'show_cover' ); ?>" name="<?php echo $this->get_field_name( 'show_cover' ); ?>">
				<?php foreach ( $reverse_boolean as $key => $val ): ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $instance['show_cover'], $key ); ?>><?php echo esc_html( $val ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_facepile' ); ?>">Ver caras?: </label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'show_facepile' ); ?>" name="<?php echo $this->get_field_name( 'show_facepile' ); ?>">
				<?php foreach ( $boolean as $key => $val ): ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $instance['show_facepile'], $key ); ?>><?php echo esc_html( $val ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_posts' ); ?>">Ver publicaciones?: </label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'show_posts' ); ?>" name="<?php echo $this->get_field_name( 'show_posts' ); ?>">
				<?php foreach ( $boolean as $key => $val ): ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $instance['show_posts'], $key ); ?>><?php echo esc_html( $val ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'align' ); ?>">Alineado: </label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'align' ); ?>" name="<?php echo $this->get_field_name( 'align' ); ?>">
				<?php foreach ( $align as $key => $val ): ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $instance['align'], $key ); ?>><?php echo esc_html( $val ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
	<?php
	}

    // Metodo que se llamara cuando se visualize el Widget en pantalla
    function widget($args, $instance)
    {
		if (!wp_script_is( 'fpp-fb-root', 'enqueued' )) {
			wp_enqueue_script( 'fpp-fb-root', get_template_directory_uri(). '/js/facebook-page-root.js' , array( 'jquery' ) );
		}
		
        echo $args["before_widget"];
        echo $args["before_title"] . apply_filters( 'widget_title', $instance['title'] ) . $args["after_title"];
		
		$output = '';

		//* Wrapper for alignment
		$output .= '<div id="facebook-widget" style="text-align:' . esc_attr( $instance['align'] ) . '; ';
		$output .= 'width:' . esc_attr( $instance['width'] )  . ';">';
		//* Main Facebook Feed             
		$output .= '<div class="fb-page" ';
		$output .= 'data-href="' . esc_attr( $instance['href'] ) . '" ';
		//$output .= 'data-width="' . esc_attr( $instance['width'] ) . '" ';
		$output .= 'data-height="' . esc_attr( $instance['height'] ) . '" ';
		$output .= 'data-hide-cover="' . esc_attr( $instance['show_cover'] ) . '" ';
		$output .= 'data-show-facepile="' . esc_attr( $instance['show_facepile'] ) . '" ';
		$output .= (esc_attr( $instance['show_posts'] )==1) ? 'data-tabs="timeline" >' : '>' ;
		$output .= '</div>';

		// end wrapper
		$output .= '</div>';

		echo $output;
        echo $args["after_widget"];
    }
}

add_action( 'widgets_init', create_function( '', 'register_widget( "Fb_Widget" );' ) );
?>