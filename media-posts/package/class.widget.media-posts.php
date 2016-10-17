<?php

/*-------------------------------------------*/
/*  Side Post list widget
/*-------------------------------------------*/
class WP_Widget_media_post extends WP_Widget {

	public $taxonomies = array( 'category' );

	function __construct() {
		global $vk_ltg_media_posts_textdomain;
		$widget_name = 'LTG ' . __( 'Media Posts', $vk_ltg_media_posts_textdomain );
		parent::__construct(
			'ltg_media_posts_media_post',
			$widget_name,
			array( 'description' => __( 'It is a widget that displays the post list. Various shapes can be selected.', $vk_ltg_media_posts_textdomain ) )
		);
	}

	function widget( $args, $instance ) {
		global $vk_ltg_media_posts_textdomain;
		if ( ! isset( $instance['format'] ) ) { $instance['format'] = 0; }

		echo $args['before_widget'];
		echo '<div class="'.$instance['format'].'">';
		$title_icon = ( isset( $instance['title_icon'] ) && $instance['title_icon'] ) ? $instance['title_icon'] : '';
		
		if ( isset( $instance['label'] ) && $instance['label'] ) {
			if ( $title_icon ) echo '<div class="icon_exist">';
			echo $args['before_title'];
			if ( $title_icon ) {
				echo '<i class="fa fa-'.$title_icon.'" aria-hidden="true"></i>';
			}
			echo $instance['label'];
			echo $args['after_title'];
			if ( $title_icon ) echo '</div><!-- [ /.icon_exist ] -->';
		} else if ( !isset( $instance['label'] ) ) {
			echo $args['before_title'];
			_e( 'Recent Posts', $vk_ltg_media_posts_textdomain );
			echo $args['after_title'];
		}
		
		// $count      = ( isset( $instance['count'] ) && $instance['count'] ) ? $instance['count'] : 10;
		$post_type  = ( isset( $instance['post_type'] ) && $instance['post_type'] ) ? $instance['post_type'] : 'post';

		if ( $instance['format'] ) { $this->_taxonomy_init( $post_type ); }

		$p_args = array(
			'post_type' => $post_type,
			'paged' => 1,
		);

		if ( isset( $instance['terms'] ) && $instance['terms'] ) {
			$taxonomies = get_taxonomies( array() );
			$p_args['tax_query'] = array(
				'relation' => 'OR',
			);
			$terms_array = explode( ',', $instance['terms'] );
			foreach ( $taxonomies as $taxonomy ) {
				$p_args['tax_query'][] = array(
					'taxonomy' => $taxonomy,
					'field' => 'id',
					'terms' => $terms_array,
				);
			}
		}

		global $wp_query;

		$p_args['posts_per_page'] = ( isset( $instance['count'] ) && $instance['count'] ) ? mb_convert_kana( $instance['count'], "n" ) : 6;
		$p_args['offset']         = ( isset( $instance['offset'] ) && $instance['offset'] ) ? mb_convert_kana( $instance['offset'], "n" ) : '';

		$wp_query = new WP_Query( $p_args );
		if ( $wp_query->have_posts() ) :

				if ( ! $instance['format'] || $instance['format'] == 'image_1st' ) {
					global $wp_query;
					$count = 1;
					/*
					1 左
					2 右
					3 右
					4 左 +
					5 左
					6 右
					7 左 +
					8 左
					9 右
					4 と 4に3の倍数を足した数の場合は改行
					*/
					while ( $wp_query->have_posts() ) : $wp_query->the_post();
						$media_post_class = ( $count == 1 ) ? ' image_card first' : ' image_card normal';

						if ( ( $count % 3 ) != 0 && $count != 2 ){
							$media_post_class .= ' left' ;
						}
						if ( 
							$count == 4 || 
							( ( $count - 4 ) % 3 == 0  )
							){
							$media_post_class .= ' clear' ;
						}
						Ltg_Media_Post_Item::media_post( $media_post_class, $instance );
						$count++;
					endwhile;
				} else {
					$patterns = Lightning_media_posts::patterns();
					echo '<div class="'.$patterns[$instance['format']]['class_outer'].'">';
					while ( $wp_query->have_posts() ) : $wp_query->the_post();
						echo '<div class="'.$patterns[$instance['format']]['class_post_outer'].'">';
						Ltg_Media_Post_Item::media_post( $patterns[$instance['format']]['class_post_item'], $instance );
						echo '</div>';
					endwhile;
					echo '</div>';
				}

		endif;

		echo '</div>';
		echo $args['after_widget'];

		wp_reset_postdata();
		wp_reset_query();

	} // widget($args, $instance)


	function _taxonomy_init( $post_type ) {
		if ( $post_type == 'post' ) { return; }
		$this->taxonomies = get_object_taxonomies( $post_type );
	}

	function taxonomy_list( $post_id = 0, $before = ' ', $sep = ',', $after = '' ) {
		if ( ! $post_id ) { $post_id = get_the_ID(); }

		$taxo_catelist = array();

		foreach ( $this->taxonomies as $taxonomy ) {
			$terms = get_the_term_list( $post_id, $taxonomy, $before, $sep , $after );
			if ( $terms ) { $taxo_catelist[] = $terms; }
		}

		if ( count( $taxo_catelist ) ) { return join( $taxo_catelist, $sep ); }
		return '';
	}

	function form( $instance ) {
		global $vk_ltg_media_posts_textdomain;
		$defaults = array(
			'title_icon' => '',
			'count'      => 6,
			'offset'     => '',
			'label'      => __( 'Recent Posts', $vk_ltg_media_posts_textdomain ),
			'post_type'  => 'post',
			'terms'      => '',
			'format'     => '0',
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		//タイトル ?>
		<br />
		<label for="<?php echo $this->get_field_id( 'label' );  ?>"><?php _e( 'Title:' ); ?></label><br/>
		<input type="text" id="<?php echo $this->get_field_id( 'label' ); ?>-title" name="<?php echo $this->get_field_name( 'label' ); ?>" value="<?php echo $instance['label']; ?>" />
        <br/><br />

        <?php echo _e( 'Title icon:' , $vk_ltg_media_posts_textdomain );?>
        <?php 
        $name = $this->get_field_name( 'title_icon' );
        $current = $instance['title_icon'];
        Vk_Font_Awesome_Selector::selectors( $name, $current ); ?>

		<?php echo _e( 'Display Format', $vk_ltg_media_posts_textdomain ); ?>:<br/>
		<ul>

		<?php
		$patterns = Lightning_media_posts::patterns();

		foreach ( $patterns as $key => $value ) {
			$checked = ( $instance['format'] == $key ) ? ' checked' : '' ;
			echo '<li><label><input type="radio" name="'.$this->get_field_name( 'format' ).'" value="'.$key.'"'.$checked.' />'.$value['label'].'</label></li>';
		
		}
		?>

		</ul>
        <br/>

		<?php //表示件数 ?>
		<label for="<?php echo $this->get_field_id( 'count' );  ?>"><?php _e( 'Display count',$vk_ltg_media_posts_textdomain ); ?>:</label><br/>
		<input type="text" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" value="<?php echo $instance['count']; ?>" />
        <br /><br />

		<?php //オフセット件数 ?>
		<label for="<?php echo $this->get_field_id( 'offset' );  ?>"><?php _e( 'Offset count',$vk_ltg_media_posts_textdomain ); ?>:</label><br/>
		<input type="text" id="<?php echo $this->get_field_id( 'offset' ); ?>" name="<?php echo $this->get_field_name( 'offset' ); ?>" value="<?php echo $instance['offset']; ?>" />
        <br />
        <?php _e( 'If you skip 2 posts and you want to display from 3rd post, please enter a 2.', $vk_ltg_media_posts_textdomain ); ?>
        <br /> <br />

		 <?php //NEWアイコン表示期間
		 $new_icon_display = ( isset( $instance['new_icon_display'] ) ) ? $instance['new_icon_display'] : 7 ;
		 ?>
		<label for="<?php echo $this->get_field_id( 'new_icon_display' );  ?>"><?php _e( 'Number of days to display the New icon', $vk_ltg_media_posts_textdomain ) ?>:</label><br/>
		<input type="text" id="<?php echo $this->get_field_id( 'new_icon_display' ); ?>" name="<?php echo $this->get_field_name( 'new_icon_display' ); ?>" value="<?php echo $new_icon_display; ?>" />
        <br /><br />

		<?php //投稿タイプ ?>
		<label for="<?php echo $this->get_field_id( 'post_type' ); ?>"><?php _e( 'Slug for the post type you want to display', $vk_ltg_media_posts_textdomain ) ?>:</label><br />
		<input type="text" id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>" value="<?php echo esc_attr( $instance['post_type'] ) ?>" />
        <br/><br/>

		<?php // Terms ?>
		<label for="<?php echo $this->get_field_id( 'terms' ); ?>"><?php _e( 'Category(Term) ID', $vk_ltg_media_posts_textdomain ) ?>:</label><br />
		<input type="text" id="<?php echo $this->get_field_id( 'terms' ); ?>" name="<?php echo $this->get_field_name( 'terms' ); ?>" value="<?php echo esc_attr( $instance['terms'] ) ?>" /><br />
		<?php _e( 'If you need filtering by category(term), add the category ID separate by ",".', $vk_ltg_media_posts_textdomain );
		echo '<br/>';
		_e( 'If empty this area, I will do not filtering.', $vk_ltg_media_posts_textdomain );
		echo '<br/><br/>';
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title_icon']       = $new_instance['title_icon'];
		$instance['format']           = ! empty( $new_instance['format'] ) ? strip_tags( $new_instance['format'] ) : 'image_1st';
		$instance['new_icon_display'] = ! empty( $new_instance['new_icon_display'] ) ? mb_convert_kana ( $new_instance['new_icon_display'] , "n" ) : 7;
		$instance['count']            = $new_instance['count'];
		$instance['offset']           = $new_instance['offset'];
		$instance['label']            = $new_instance['label'];
		$instance['post_type']        = ! empty( $new_instance['post_type'] ) ? strip_tags( $new_instance['post_type'] ) : 'post';
		$instance['terms']            = preg_replace( '/([^0-9,]+)/', '', $new_instance['terms'] );
		return $instance;
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget("WP_Widget_media_post");' ) );
