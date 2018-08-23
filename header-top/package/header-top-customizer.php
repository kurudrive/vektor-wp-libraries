<?php

/*
このファイルの元ファイルは
https://github.com/vektor-inc/vektor-wp-libraries
にあります。
修正の際は上記リポジトリのデータを修正してください。
編集権限を持っていない方で何か修正要望などありましたら
各プラグインのリポジトリにプルリクエストで結構です。

*/

add_action( 'customize_register', 'lightning_header_top_customize_register' );
function lightning_header_top_customize_register( $wp_customize ) {

	class Custom_Text_Control_a extends WP_Customize_Control {
		public $type        = 'customtext';
		public $description = ''; // we add this for the extra description
		public function render_content() {
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
			<span><?php echo esc_html( $this->description ); ?></span>
		</label>
		<?php
		}
	}

	/*-------------------------------------------*/
	/*	Design setting
	/*-------------------------------------------*/
	$wp_customize->add_section(
		'lightning_header_top', array(
			'title'    => __( 'Lightning Header top settings', 'vk_header_top_textdomain' ),
			'priority' => 450,
		)
	);
	// $veu_options = get_option( 'vkExUnit_contact' );
	// $default_btn_txt = ( isset( $veu_options['short_text'] ) && $veu_options['short_text'] ) ? $veu_options['short_text'] : __( '', 'ligthning' );
	// $default_btn_url = ( isset( $veu_options['contact_link'] ) && $veu_options['contact_link'] ) ? esc_url( $veu_options['contact_link'] ) : '';
	// $default_tel_number = ( isset( $veu_options['tel_number'] ) && $veu_options['tel_number'] ) ? esc_html( $veu_options['tel_number'] ) : '';
	$default_btn_txt    = '';
	$default_btn_url    = '';
	$default_tel_number = '';

	// header_top_hidden
	$wp_customize->add_setting(
		'lightning_theme_options[header_top_hidden]', array(
			'default'           => false,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lightning_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'header_top_hidden', array(
			'label'    => __( 'Hide header top area', 'vk_header_top_textdomain' ),
			'section'  => 'lightning_header_top',
			'settings' => 'lightning_theme_options[header_top_hidden]',
			'type'     => 'checkbox',
			'priority' => 10,
		)
	);
	// $wp_customize->selective_refresh->add_partial(
	// 	'lightning_theme_options[header_top_hidden]', array(
	// 		'selector'        => '.headerTop_description',
	// 		'render_callback' => '',
	// 	)
	// );

	if ( apply_filters( 'header-top-contact', true ) ) {
		$wp_customize->add_setting(
			'lightning_theme_options[header_top_contact_txt]', array(
				'default'           => $default_btn_txt,
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'header_top_contact_txt', array(
				'label'    => __( 'Contact button text', 'vk_header_top_textdomain' ),
				'section'  => 'lightning_header_top',
				'settings' => 'lightning_theme_options[header_top_contact_txt]',
				'type'     => 'text',
				'priority' => 10,
			)
		);

		$wp_customize->add_setting(
			'lightning_theme_options[header_top_contact_url]', array(
				'default'           => $default_btn_url,
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			new Custom_Text_Control_a(
				$wp_customize, 'header_top_contact_url', array(
					'label'       => __( 'Contact button link url', 'vk_header_top_textdomain' ),
					'section'     => 'lightning_header_top',
					'settings'    => 'lightning_theme_options[header_top_contact_url]',
					'type'        => 'text',
					'priority'    => 11,
					'description' => __( 'Ex : http:www.aaa.com/contact/', 'vk_header_top_textdomain' ),
				)
			)
		);
	}

	if ( apply_filters( 'header-top-tel', true ) ) {
		$wp_customize->add_setting(
			'lightning_theme_options[header_top_tel_number]', array(
				'default'           => $default_tel_number,
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'header_top_tel_number', array(
				'label'    => __( 'Contact Tel Number', 'vk_header_top_textdomain' ),
				'section'  => 'lightning_header_top',
				'settings' => 'lightning_theme_options[header_top_tel_number]',
				'type'     => 'text',
				'priority' => 12,
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'lightning_theme_options[header_top_tel_number]', array(
				'selector'        => '.headerTop_tel_wrap',
				'render_callback' => '',
			)
		);
	}

}
