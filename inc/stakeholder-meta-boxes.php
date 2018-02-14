<?php 
/**
 * Custom Meta Boxes for the "STAKEHOLDER" Post Type.
 *
 * @package Traffic Properties
 */

/**
 * "Contact Information" Meta Box
 */
class Stakeholder_Contact_Information_Meta_Boxe {
	private $screens = array(
		'Stakeholder',
	);

	public static function fields(){
		return
		    array(
				array(
					'id' 	=> 'website',
					'label' => __( 'Website', 'astro-stakeholders' ),
					'type' 	=> 'url',
				),
				array(
					'id' => 'representative-name',
					'label' => __( 'Representative Name', 'astro-stakeholders' ),
					'type' => 'text',
				),
				array(
					'id' => 'representative-phone',
					'label' => __( 'Representative Phone', 'astro-stakeholders' ),
					'type' => 'tel',
				),
			);
	}

	/**
	 * Class construct method. Adds actions to their respective WordPress hooks.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
	}

	/**
	 * Hooks into WordPress' add_meta_boxes function.
	 * Goes through screens (post types) and adds the meta box.
	 */
	public function add_meta_boxes() {
		foreach ( $this->screens as $screen ) {
			add_meta_box(
				'contact-information',
				__( 'Contact Information', 'astro-stakeholders' ),
				array( $this, 'add_meta_box_callback' ),
				$screen,
				'normal',
				'high'
			);
		}
	}

	/**
	 * Generates the HTML for the meta box
	 * 
	 * @param object $post WordPress post object
	 */
	public function add_meta_box_callback( $post ) {
		wp_nonce_field( 'contact_information_data', 'contact_information_nonce' );
		echo __( 'The stakeholder\'s contact details.', 'astro-stakeholders' );
		$this->generate_fields( $post );
	}

	/**
	 * Generates the field's HTML for the meta box.
	 */
	public function generate_fields( $post ) {
		$output = '';
		foreach ( $this->fields() as $field ) {
			$label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
			$db_value = get_post_meta( $post->ID, 'contact_information_' . $field['id'], true );
			switch ( $field['type'] ) {
				default:
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s">',
						$field['type'] !== 'color' ? 'class="regular-text"' : '',
						$field['id'],
						$field['id'],
						$field['type'],
						$db_value
					);
			}
			$output .= $this->row_format( $label, $input );
		}
		echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
	}

	/**
	 * Generates the HTML for table rows.
	 */
	public function row_format( $label, $input ) {
		return sprintf(
			'<tr><th scope="row">%s</th><td>%s</td></tr>',
			$label,
			$input
		);
	}
	/**
	 * Hooks into WordPress' save_post function
	 */
	public function save_post( $post_id ) {
		if ( ! isset( $_POST['contact_information_nonce'] ) )
			return $post_id;

		$nonce = $_POST['contact_information_nonce'];
		if ( !wp_verify_nonce( $nonce, 'contact_information_data' ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		foreach ( $this->fields() as $field ) {
			if ( isset( $_POST[ $field['id'] ] ) ) {
				switch ( $field['type'] ) {
					case 'email':
						$_POST[ $field['id'] ] = sanitize_email( $_POST[ $field['id'] ] );
						break;
					case 'text':
						$_POST[ $field['id'] ] = sanitize_text_field( $_POST[ $field['id'] ] );
						break;
				}
				update_post_meta( $post_id, 'contact_information_' . $field['id'], $_POST[ $field['id'] ] );
			} else if ( $field['type'] === 'checkbox' ) {
				update_post_meta( $post_id, 'contact_information_' . $field['id'], '0' );
			}
		}
	}
}
new Stakeholder_Contact_Information_Meta_Boxe;

/*
 * "Social Networks" Meta Box
 */
class Stakeholder_Social_Networks_Meta_Box {
	private $screens = array(
		'Stakeholder',
	);
	
	public static function fields(){
		return
		    array(
				array(
					'id' => 'facebook',
					'label' => __( 'Facebook', 'astro-stakeholders' ),
					'type' => 'url',
				),
				array(
					'id' => 'facebook-likes',
					'label' => '',
					'type' => 'hidden',
				),
				array(
					'id' => 'twitter',
					'label' => __( 'Twitter', 'astro-stakeholders' ),
					'type' => 'url',
				),
				array(
					'id' => 'twitter-followers',
					'label' => '',
					'type' => 'hidden',
				),
				array(
					'id' => 'instagram',
					'label' => __( 'Instagram', 'astro-stakeholders' ),
					'type' => 'url',
				),
				array(
					'id' => 'instagram-followers',
					'label' => '',
					'type' => 'hidden',
				),
				array(
					'id' => 'youtube',
					'label' => __( 'YouTube', 'astro-stakeholders' ),
					'type' => 'url',
				),
				array(
					'id' => 'youtube-subscribers',
					'label' => '',
					'type' => 'hidden',
				),
			);
	}

	/**
	 * Class construct method. Adds actions to their respective WordPress hooks.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
	}

	/**
	 * Hooks into WordPress' add_meta_boxes function.
	 * Goes through screens (post types) and adds the meta box.
	 */
	public function add_meta_boxes() {
		foreach ( $this->screens as $screen ) {
			add_meta_box(
				'social-networks',
				__( 'Social Networks', 'astro-stakeholders' ),
				array( $this, 'add_meta_box_callback' ),
				$screen,
				'normal',
				'high'
			);
		}
	}

	/**
	 * Generates the HTML for the meta box
	 * 
	 * @param object $post WordPress post object
	 */
	public function add_meta_box_callback( $post ) {
		wp_nonce_field( 'social_networks_data', 'social_networks_nonce' );
		echo __( 'The stakeholder\'s presence in social media.', 'astro-stakeholders' );
		$this->generate_fields( $post );
	}

	/**
	 * Generates the field's HTML for the meta box.
	 */
	public function generate_fields( $post ) {
		$output = '';
		foreach ( $this->fields() as $field ) {
			$label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
			$db_value = get_post_meta( $post->ID, 'social_networks_' . $field['id'], true );
			switch ( $field['type'] ) {
				case 'hidden':
					if ( ! $db_value ) {
						$db_value = '0';
					}
					$input = sprintf(
						'<input id="%s" name="%s" type="%s" value="%s">',
						$field['id'],
						$field['id'],
						$field['type'],
						$db_value
					);
					break;
				default:
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s">',
						$field['type'] !== 'color' ? 'class="regular-text"' : '',
						$field['id'],
						$field['id'],
						$field['type'],
						$db_value
					);
			}
			$output .= $this->row_format( $label, $input );
		}
		echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
	}

	/**
	 * Generates the HTML for table rows.
	 */
	public function row_format( $label, $input ) {
		return sprintf(
			'<tr><th scope="row">%s</th><td>%s</td></tr>',
			$label,
			$input
		);
	}
	/**
	 * Hooks into WordPress' save_post function
	 */
	public function save_post( $post_id ) {
		if ( ! isset( $_POST['social_networks_nonce'] ) )
			return $post_id;

		$nonce = $_POST['social_networks_nonce'];
		if ( !wp_verify_nonce( $nonce, 'social_networks_data' ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		foreach ( $this->fields() as $field ) {
			if ( isset( $_POST[ $field['id'] ] ) ) {
				switch ( $field['type'] ) {
					case 'email':
						$_POST[ $field['id'] ] = sanitize_email( $_POST[ $field['id'] ] );
						break;
					case 'text':
						$_POST[ $field['id'] ] = sanitize_text_field( $_POST[ $field['id'] ] );
						break;
					case 'hidden':
						if ( $field['id'] == 'facebook-likes' ) {
							if ( ! empty( $_POST['facebook'] ) ) {
								$_POST[ $field['id'] ] = stakeholder_facebook_likes( $_POST['facebook'] );
							} else {
								$_POST[ $field['id'] ] = 0;
							}
						}
						if ( $field['id'] == 'twitter-followers' ) {
							if (! empty( $_POST['twitter'] ) ) {
								$_POST[ $field['id'] ] = stakeholder_twitter_followers( $_POST['twitter'] );
							} else {
								$_POST[ $field['id'] ] = 0;
							}
						}
						if ( $field['id'] == 'instagram-followers' ) {
							if ( ! empty( $_POST['instagram'] ) ) {
								$_POST[ $field['id'] ] = stakeholder_instagram_followers( $_POST['instagram'] );
							} else {
								$_POST[ $field['id'] ] = 0;
							}
						}
						if ( $field['id'] == 'youtube-subscribers' ) {
							if ( ! empty( $_POST['youtube'] ) ) {
								$_POST[ $field['id'] ] = stakeholder_youtube_subscribers( $_POST['youtube'] );
							} else {
								$_POST[ $field['id'] ] = 0;
							}
						}
						break;
				}
				update_post_meta( $post_id, 'social_networks_' . $field['id'], $_POST[ $field['id'] ] );
			} else if ( $field['type'] === 'checkbox' ) {
				update_post_meta( $post_id, 'social_networks_' . $field['id'], '0' );
			}
		}
	}
}
new Stakeholder_Social_Networks_Meta_Box;

/**
 *  "To Do List" Meta Box
 */
class Stakeholder_To_Do_List_Meta_Box {
	private $screens = array(
		'Stakeholder',
	);
	
	public static function fields(){
		return
		    array(
				array(
					'id' => 'follow-on-twitter',
					'label' => __( 'Follow on Twitter', 'astro-stakeholders' ),
					'type' => 'checkbox',
				),
				array(
					'id' => 'subscribe-on-youtube',
					'label' => __( 'Subscribe on YouTube', 'astro-stakeholders' ),
					'type' => 'checkbox',
				),
				array(
					'id' => 'complete-evaluation',
					'label' => __( 'Complete Evaluation', 'astro-stakeholders' ),
					'type' => 'checkbox',
				),
			);
	}
	
	/**
	 * Class construct method. Adds actions to their respective WordPress hooks.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
	}

	/**
	 * Hooks into WordPress' add_meta_boxes function.
	 * Goes through screens (post types) and adds the meta box.
	 */
	public function add_meta_boxes() {
		foreach ( $this->screens as $screen ) {
			add_meta_box(
				'to-do-list',
				__( 'To Do List', 'astro-stakeholders' ),
				array( $this, 'add_meta_box_callback' ),
				$screen,
				'normal',
				'high'
			);
		}
	}

	/**
	 * Generates the HTML for the meta box
	 * 
	 * @param object $post WordPress post object
	 */
	public function add_meta_box_callback( $post ) {
		wp_nonce_field( 'to_do_list_data', 'to_do_list_nonce' );
		echo __( 'Tasks to be completed regarding the stakeholder.', 'astro-stakeholders' );
		$this->generate_fields( $post );
	}

	/**
	 * Generates the field's HTML for the meta box.
	 */
	public function generate_fields( $post ) {
		$output = '';
		foreach ( $this->fields() as $field ) {
			$label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
			$db_value = get_post_meta( $post->ID, 'to_do_list_' . $field['id'], true );
			switch ( $field['type'] ) {
				case 'checkbox':
					$input = sprintf(
						'<input %s id="%s" name="%s" type="checkbox" value="1">',
						$db_value === '1' ? 'checked' : '',
						$field['id'],
						$field['id']
					);
					break;
				default:
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s">',
						$field['type'] !== 'color' ? 'class="regular-text"' : '',
						$field['id'],
						$field['id'],
						$field['type'],
						$db_value
					);
			}
			$output .= $this->row_format( $label, $input );
		}
		echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
	}

	/**
	 * Generates the HTML for table rows.
	 */
	public function row_format( $label, $input ) {
		return sprintf(
			'<tr><th scope="row">%s</th><td>%s</td></tr>',
			$label,
			$input
		);
	}
	/**
	 * Hooks into WordPress' save_post function
	 */
	public function save_post( $post_id ) {
		if ( ! isset( $_POST['to_do_list_nonce'] ) )
			return $post_id;

		$nonce = $_POST['to_do_list_nonce'];
		if ( !wp_verify_nonce( $nonce, 'to_do_list_data' ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		foreach ( $this->fields() as $field ) {
			if ( isset( $_POST[ $field['id'] ] ) ) {
				switch ( $field['type'] ) {
					case 'email':
						$_POST[ $field['id'] ] = sanitize_email( $_POST[ $field['id'] ] );
						break;
					case 'text':
						$_POST[ $field['id'] ] = sanitize_text_field( $_POST[ $field['id'] ] );
						break;
				}
				update_post_meta( $post_id, 'to_do_list_' . $field['id'], $_POST[ $field['id'] ] );
			} else if ( $field['type'] === 'checkbox' ) {
				update_post_meta( $post_id, 'to_do_list_' . $field['id'], '0' );
			}
		}
	}
}
new Stakeholder_To_Do_List_Meta_Box;

/**
 *  "Evaluation" Meta Box
 */
class Stakeholder_Evaluation_Meta_Box {
	private $screens = array(
		'Stakeholder',
	);
	
	public static function fields(){
		return
		    array(		
		    	array(
					'id' => 'related-percentage',
					'label' => __( 'Related Percentage', 'astro-stakeholders' ),
					'description' => __( 'What percentage of their activities relates to us?', 'astro-stakeholders' ),
					'type' => 'number',
				),
				array(
					'id' => 'third-party-presence',
					'label' => __( 'Third Party Presence', 'astro-stakeholders' ),
					'description' => __( 'Check this if they DO NOT promote third parties in their communication.', 'astro-stakeholders' ),
					'type' => 'checkbox',
				),
				array(
					'id' => 'existing-partnership',
					'label' => __( 'Existing Partnership', 'astro-stakeholders' ),
					'description' => __( 'Check this if they have an exiting partnership with our competitiors.', 'astro-stakeholders' ),
					'type' => 'checkbox',
				),
				array(
					'id' => 'news-content',
					'label' => __( 'News Content', 'astro-stakeholders' ),
					'description' => __( 'Check this if they currently produce news content.', 'astro-stakeholders' ),
					'type' => 'checkbox',
				),
				array(
					'id' => 'related-importance',
					'label' => __( 'Related Importance', 'astro-stakeholders' ),
					'description' => __( 'Check this if they give NO importance to content related to us.', 'astro-stakeholders' ),			
					'type' => 'checkbox',
				),
				array(
					'id' => 'other-criteria',
					'label' => __( 'Other Criteria', 'astro-stakeholders' ),
					'description' => __( 'Check this if you consider other detrimental criteria.', 'astro-stakeholders' ),
					'type' => 'checkbox',
				),
				array(
					'id' => 'other-criteria-description',
					'label' => __( 'Other Criteria Description', 'astro-stakeholders' ),
					'description' => __( 'Specify the detrimental criteria.', 'astro-stakeholders' ),
					'type' => 'text',
				),
				array(
					'id' => 'importance-us-score',
					'label' => '',
					'description' => '',
					'type' => 'hidden',
				),
				array(
					'id' => 'importance-them-score',
					'label' => '',
					'description' => '',
					'type' => 'hidden',
				),
				array(
					'id' => 'presence-score',
					'label' => '',
					'description' => '',
					'type' => 'hidden',
				),
				array(
					'id' => 'total-score',
					'label' => '',
					'description' => '',
					'type' => 'hidden',
				),
			);
	}

	/**
	 * Class construct method. Adds actions to their respective WordPress hooks.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
	}

	/**
	 * Hooks into WordPress' add_meta_boxes function.
	 * Goes through screens (post types) and adds the meta box.
	 */
	public function add_meta_boxes() {
		foreach ( $this->screens as $screen ) {
			add_meta_box(
				'evaluation',
				__( 'Evaluation', 'astro-stakeholders' ),
				array( $this, 'add_meta_box_callback' ),
				$screen,
				'normal',
				'high'
			);
		}
	}

	/**
	 * Generates the HTML for the meta box
	 * 
	 * @param object $post WordPress post object
	 */
	public function add_meta_box_callback( $post ) {
		wp_nonce_field( 'evaluation_data', 'evaluation_nonce' );
		echo __( 'Criteria to follow for the stakeholder rankings.', 'astro-stakeholders' );
		$this->generate_fields( $post );
	}

	/**
	 * Generates the field's HTML for the meta box.
	 */
	public function generate_fields( $post ) {
		$output = '';
		foreach ( $this->fields() as $field ) {
			$label = '<label for="' . $field['id'] . '"><strong>' . $field['label'] . '</strong></label>';
			$db_value = get_post_meta( $post->ID, 'evaluation_' . $field['id'], true );
			switch ( $field['type'] ) {
				case 'checkbox':
					$input = sprintf(
						'<input %s id="%s" name="%s" type="checkbox" value="1">',
						$db_value === '1' ? 'checked' : '',
						$field['id'],
						$field['id']
					);
					break;
				case 'number':
					$input = sprintf(
						'<input class="small-text" id="%s" name="%s" type="%s" value="%s" class="small-text">',
						$field['id'],
						$field['id'],
						$field['type'],
						$db_value
					);
					break;
				case 'hidden':
					if ( ! $db_value ) {
						$db_value = '0';
					}
					$input = sprintf(
						'<input id="%s" name="%s" type="%s" value="%s">',
						$field['id'],
						$field['id'],
						$field['type'],
						$db_value
					);
					break;
				default:
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s">',
						$field['type'] !== 'color' ? 'class="regular-text"' : '',
						$field['id'],
						$field['id'],
						$field['type'],
						$db_value
					);
			}
			if ( $field['type'] != 'hidden' ) {
				$description = '<p>' . $field['description'] . '</p>';
				$output .= $this->row_format( $label, $input, $description );
			} else {
				$output .= $input;
			}
		}
		echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
	}

	/**
	 * Generates the HTML for table rows.
	 */
	public function row_format( $label, $input, $description ) {
		return sprintf(
			'<tr><td>%s %s</td><td>%s</td></tr>',
			$label,
			$description,
			$input
		);
	}

	/**
	 * Generates a score in terms of the stakeholder's importance for "us".
	 *
	 * @return int $score The calculated score. 
	 */
	public function stakeholder_importance_us() {
		if ( isset( $_POST['related-percentage'] ) ) {
			$score = intval( sanitize_text_field( $_POST['related-percentage'] ) ) / 10;
		} else {
			$score = 0;
		}
		
		return $score;
	}

	/**
	 * Generates a score in terms of "our" importance for the stakeholder.
	 *
	 * @return int $score The calculated score. 
	 */
	public function stakeholder_importance_them() {
		if ( isset( $_POST['complete-evaluation'] ) ) {
			$score = 10;
			if ( isset( $_POST['third-party-presence'] ) ) {
				$score = $score - 2;
			}
			if ( isset( $_POST['existing-partnership'] ) ) {
				$score = $score - 2;
			}
			if ( isset( $_POST['news-content'] ) ) {
				$score = $score - 2;
			}
			if ( isset( $_POST['related-importance'] ) ) {
				$score = $score - 2;
			}
			if ( isset( $_POST['other-criteria'] ) ) {
				$score = $score - 2;
			}
		} else {
			$score = 0;
		}
		
		return $score;
	}

	/**
	 * Generates a score in terms of the stakeholder's social media presence.
	 *
	 * @return int $score The calculated score. 
	 */
	public function stakeholder_presence() {

		$top_results = array(
			stakeholders_top_result( 'social_networks_facebook-likes' ),
			stakeholders_top_result( 'social_networks_twitter-followers' ),
			stakeholders_top_result( 'social_networks_instagram-followers' ),
			stakeholders_top_result( 'social_networks_youtube-subscribers' ),
		);

		$people = array(
			( isset( $_POST['facebook-likes'] ) ? $_POST['facebook-likes'] : 0 ),
			( isset( $_POST['twitter-followers'] ) ? $_POST['twitter-followers'] : 0 ),
			( isset( $_POST['instagram-followers'] ) ? $_POST['instagram-followers'] : 0 ),
			( isset( $_POST['youtube-subscribers'] ) ? $_POST['youtube-subscribers'] : 0 )
		);

		$scores = array();

		for ( $i = 0; $i < count( $top_results ); $i++ ) {
			if ( $top_results[$i] > 0 ) {
				$scores[] = round( ( 2.5 / $top_results[$i] ) * $people[$i], 2 );
			} else {
				$scores[] = 0;
			}
		}

		$score = round( array_sum( $scores ), 1 );
		
		return $score;
	}

	/**
	 * Hooks into WordPress' save_post function
	 */
	public function save_post( $post_id ) {
		if ( ! isset( $_POST['evaluation_nonce'] ) )
			return $post_id;

		$nonce = $_POST['evaluation_nonce'];
		if ( !wp_verify_nonce( $nonce, 'evaluation_data' ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		foreach ( $this->fields() as $field ) {
			if ( isset( $_POST[ $field['id'] ] ) ) {
				switch ( $field['type'] ) {
					case 'email':
						$_POST[ $field['id'] ] = sanitize_email( $_POST[ $field['id'] ] );
						break;
					case 'text':
						$_POST[ $field['id'] ] = sanitize_text_field( $_POST[ $field['id'] ] );
						break;
					case 'hidden':
						if ( $field['id'] == 'importance-us-score' ) {
							$_POST[ $field['id'] ] = $this->stakeholder_importance_us();
						}
						if ( $field['id'] == 'importance-them-score' ) {
							$_POST[ $field['id'] ] = $this->stakeholder_importance_them();
						}
						if ( $field['id'] == 'presence-score' ) {
							$_POST[ $field['id'] ] = $this->stakeholder_presence();
						}
						if ( $field['id'] == 'total-score' ) {
							$_POST[ $field['id'] ] = round( ( $_POST['importance-us-score'] + $_POST['importance-them-score'] + $_POST['presence-score'] ) / 3, 2 );
						}
						break;
				}
				update_post_meta( $post_id, 'evaluation_' . $field['id'], $_POST[ $field['id'] ] );
			} else if ( $field['type'] === 'checkbox' ) {
				update_post_meta( $post_id, 'evaluation_' . $field['id'], '0' );
			}
		}
	}
}
new Stakeholder_Evaluation_Meta_Box;