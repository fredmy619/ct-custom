<?php 
class CT_Options_Panel {
	/**
	* Options panel arguments.
	*/
	protected $args = [];
	protected $title = '';
	protected $slug = '';
	protected $option_name = '';
	protected $option_group_name = '';
	protected $user_capability = '';
	protected $settings = [];

	/**
	* Our class constructor.
	*/
	public function __construct( array $args, array $settings ) {
		$this->args              = $args;
		$this->settings          = $settings;
		$this->title             = $this->args['title'] ?? esc_html__( 'Options', 'text_domain' );
		$this->slug              = $this->args['slug'] ?? sanitize_key( $this->title );
		$this->option_name       = $this->args['option_name'] ?? sanitize_key( $this->title );
		$this->option_group_name = $this->option_name . '_group';
		$this->user_capability   = $args['user_capability'] ?? 'manage_options';

		add_action( 'admin_menu', [ $this, 'register_menu_page' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	/**
	* Register the new menu page.
	*/
	public function register_menu_page() {
		add_menu_page(
			$this->title,
			$this->title,
			$this->user_capability,
			$this->slug,
			[ $this, 'render_options_page' ]
		);
	}

	/**
	* Register the settings.
	*/
	public function register_settings() {
		register_setting( $this->option_group_name, $this->option_name, [
			'sanitize_callback' => [ $this, 'sanitize_fields' ],
			'default'           => $this->get_defaults(),
		] );

		add_settings_section(
			$this->option_name . '_sections',
			false,
			false,
			$this->option_name
		);

		foreach ( $this->settings as $key => $args ) {
			$type = $args['type'] ?? 'text';
			$callback = "render_{$type}_field";
			if ( method_exists( $this, $callback ) ) {
				$tr_class = '';
				
				add_settings_field(
					$key,
					$args['label'],
					[ $this, $callback ],
					$this->option_name,
					$this->option_name . '_sections',
					[
						'label_for' => $key,
						'class'     => $tr_class
					]
				);
			}
		}
	}

	/**
	* Saves our fields.
	*/
	public function sanitize_fields( $value ) {
		$value = (array) $value;
		$new_value = [];

		foreach ( $this->settings as $key => $args ) {
			$field_type = $args['type'];
			$new_option_value = $value[$key] ?? '';

			if ( $new_option_value ) {
				$sanitize_callback = $args['sanitize_callback'] ?? $this->get_sanitize_callback_by_type( $field_type );
				$new_value[$key] = call_user_func( $sanitize_callback, $new_option_value, $args );
			}
		}

		return $new_value;
	}

	/**
	* Returns sanitize callback based on field type.
	*/
	protected function get_sanitize_callback_by_type( $field_type ) {
		switch ( $field_type ) {
			case 'textarea':
				return 'wp_kses_post';
				break;
			case 'file':
					return 'sanitize_file_name';
					break;
			default:
			case 'text':
				return 'sanitize_text_field';
				break;
		}
	}

	/**
	* Returns default values.
	*/
	protected function get_defaults() {
		$defaults = [];

		foreach ( $this->settings as $key => $args ) {
			$defaults[$key] = $args['default'] ?? '';
		}
		return $defaults;
	}

	/**
	* Renders the options page.
	*/
	public function render_options_page() {
		if ( ! current_user_can( $this->user_capability ) ) {
			return;
		}

		if ( isset( $_GET['settings-updated'] ) ) {
			add_settings_error(
			$this->option_name . '_mesages',
			$this->option_name . '_message',
			esc_html__( 'Settings Saved', 'text_domain' ),
			'updated'
			);
		}

		settings_errors( $this->option_name . '_mesages' );

		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post" class="ct-options-form">
				<?php
					settings_fields( $this->option_group_name );
					do_settings_sections( $this->option_name );
					submit_button( 'Save Settings' );
				?>
			</form>
		</div>
		<?php
	}


	/**
	 * Returns an option value.
	 */
	protected function get_option_value( $option_name ) {
		$option = get_option( $this->option_name );

		if ( ! array_key_exists($option_name, $option)){
			return array_key_exists( 'default', $this->settings[$option_name] ) ? $this->settings[$option_name]['default'] : '';
		}
		return $option[$option_name];
	}

	/**
	 * Renders a image field.
	 */
	public function render_image_field( $args ) {
		$option_name = $args['label_for'];
		$value       = $this->get_option_value( $option_name );

		$hasImage = false;
		if (!is_null($value) && $value !== "" && $value > 0) {
			$hasImage   = true;
		}
		?>
			<div id="ct_logo_wrapper" style="margin-bottom: .5rem;">
				<?php if ($hasImage) { ?>
					<img class="logo" src="<?php echo esc_url($value);?>" width="172"/>
				<?php } ?>
			</div>
			<input type="hidden" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="<?php echo $this->option_name; ?>[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_attr( $value ); ?>" class="">
			<input id="upload_image_button" type="button" value="Upload Image">

			<script>
				jQuery(document).ready(function($){
					var custom_uploader;
					var input_id = $('#upload_image').attr('id');

					$('#upload_image_button').click(function(e) {
						e.preventDefault();
						if (custom_uploader) {
							custom_uploader.open();
							return;
						}
						custom_uploader = wp.media.frames.file_frame = wp.media({
							multiple: false,
							library: { type: 'image' },
							button:  { text: 'Select Image' },
							title: 'Select an image or enter an image URL.',
						});
						custom_uploader.on('select', function() {
							console.log(custom_uploader.state().get('selection').toJSON());
							attachment = custom_uploader.state().get('selection').first().toJSON();
							$('#ct_logo').val(attachment.url);
						});
						custom_uploader.open();
					});
				});
			</script>
		<?php
	}

	/**
	 * Renders a text field.
	 */
	public function render_text_field( $args ) {
		$option_name = $args['label_for'];
		$value       = $this->get_option_value( $option_name );
		$description = $this->settings[$option_name]['description'] ?? '';
		?>
			<input
				type="text"
				id="<?php echo esc_attr( $args['label_for'] ); ?>"
				name="<?php echo $this->option_name; ?>[<?php echo esc_attr( $args['label_for'] ); ?>]"
				value="<?php echo esc_attr( $value ); ?>">
			<?php if ( $description ) { ?>
				<p class="description"><?php echo esc_html( $description ); ?></p>
			<?php } ?>
		<?php
	}

	/**
	 * Renders a textarea field.
	 */
	public function render_textarea_field( $args ) {
		$option_name = $args['label_for'];
		$value       = $this->get_option_value( $option_name );
		$description = $this->settings[$option_name]['description'] ?? '';
		$rows        = $this->settings[$option_name]['rows'] ?? '4';
		$cols        = $this->settings[$option_name]['cols'] ?? '50';
		?>
			<textarea
				id="<?php echo esc_attr( $args['label_for'] ); ?>"
				rows="<?php echo esc_attr( absint( $rows ) ); ?>"
				cols="<?php echo esc_attr( absint( $cols ) ); ?>"
				name="<?php echo $this->option_name; ?>[<?php echo esc_attr( $args['label_for'] ); ?>]"><?php echo esc_attr( $value ); ?></textarea>
			<?php if ( $description ) { ?>
				<p class="description"><?php echo esc_html( $description ); ?></p>
			<?php } ?>
		<?php
	}
}

// Load the media uploader.
function load_wp_media_files() {
	wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'load_wp_media_files');

// Register new Options panel.
$panel_args = [
    'title'           => 'CT Options',
    'option_name'     => 'ct_options',
    'slug'            => 'ct-options-panel',
    'user_capability' => 'manage_options',
];

$prefix = 'ct_';
$panel_settings = [
	$prefix.'logo' => [
        'label'       => esc_html__( 'Logo', 'text_domain' ),
        'type'        => 'image',
	],
    $prefix.'phone_number' => [
        'label'       => esc_html__( 'Phone Number', 'text_domain' ),
        'type'        => 'text',
	],
	$prefix.'fax_number' => [
        'label'       => esc_html__( 'Fax Number', 'text_domain' ),
        'type'        => 'text',
    ],
	$prefix.'address_information' => [
        'label'       => esc_html__( 'Address Information', 'text_domain' ),
        'type'        => 'textarea',
    ],
	$prefix.'facebook_url' => [
        'label'       => esc_html__( 'Facebook Url', 'text_domain' ),
        'type'        => 'text',
    ],
	$prefix.'twitter_url' => [
        'label'       => esc_html__( 'Twitter Url', 'text_domain' ),
        'type'        => 'text',
    ],
	$prefix.'linkedin_url' => [
        'label'       => esc_html__( 'LinkedIn Url', 'text_domain' ),
        'type'        => 'text',
    ],
	$prefix.'pinterest_url' => [
        'label'       => esc_html__( 'Pinterest Url', 'text_domain' ),
        'type'        => 'text',
    ],
];

new CT_Options_Panel( $panel_args, $panel_settings );

