<?php
/**
 * Plugin Functions
 *
 * @package Astro Stakeholders
 */

/**
 * Get a stakeholder's taxonomies.
 *
 * @param string  $taxonomy The slug for the desired taxnonomy.
 * @param boolean $children Wether to include children terms or not.
 *
 * @return string 			The list of terms with their respective links.
 */
function stakeholder_terms( $taxonomy, $children = false ) {
    $terms = get_the_terms( get_the_ID(), $taxonomy );	

    $terms_string = '';

    $term_items = array();

	if ( $terms && ! is_wp_error( $terms ) ) {		 
	    foreach ( $terms as $term ) {
	    	if ( $children == true || $term->parent == 0 ) {
		    	$term_link = get_term_link( $term );
		    	$term_items[] = '<a class="' . esc_attr( $taxonomy ) . '-link" href="' . esc_url( $term_link ) . '">' . esc_html( $term->name ) . '</a>';
	    	}
	    }

	    $terms_string = join( ' | ', $term_items );
    }

    return $terms_string;
}

/**
 * Display all URLs with a link.
 *
 * @param  string  $urls List of valid URLs, comma separated.	 
 * @return string 		 The number of likes or an error message.
 */
function stakeholder_link_urls( $urls ) {
	$urls = explode( ',', $urls );
	$output = '';

	foreach( $urls as $url ) {
		$output .= '<a href="' . esc_url( $url ) . '" rel="bookmark" target="_blank"><i class="fas fa-external-link-alt fa-lg"></i></a>';
    }

    return $output;
}

/**
 * Get a stakeholder's Facebook Likes.
 *
 * @param  string  $urls List of valid Facebook URL, comma separated.	 
 * @return string 		 The number of likes or an error message.
 */
function stakeholder_facebook_likes( $urls ) {

	$fb_urls = explode( ',', $urls );

	$likes = array();

	foreach( $fb_urls as $url ) {

		$fb = new \Facebook\Facebook([
			'app_id' => '521051611627486',
		  	'app_secret' => 'eb8fd30dc26f624b37c25cbb194e42bd',
		  	'default_graph_version' => 'v2.12',
		]);

		$fb_token = '521051611627486|eb8fd30dc26f624b37c25cbb194e42bd';

		$facebook_id = '';

		try {
		  	$response = $fb->get( '/?id=' . $url, $fb_token );
			$facebook_page = $response->getGraphPage()->getId();
		} catch(\Facebook\Exceptions\FacebookResponseException $e) {
		 	// When Graph returns an error
		  	$facebook_error = 'Graph returned an error: ' . $e->getMessage();
		} catch(\Facebook\Exceptions\FacebookSDKException $e) {
		  	// When validation fails or other local issues
		  	$facebook_error = 'Facebook SDK returned an error: ' . $e->getMessage();
		}

		if ( ! isset( $facebook_error ) ) {
			try {
			  	$response = $fb->get( '/' . $facebook_page . '?fields=fan_count', $fb_token );
				$response = (array) json_decode( $response->getBody() );
				$facebook_likes = $response['fan_count'];
			} catch(\Facebook\Exceptions\FacebookResponseException $e) {
			 	// When Graph returns an error
			  	$facebook_error = 'Graph returned an error: ' . $e->getMessage();
			} catch(\Facebook\Exceptions\FacebookSDKException $e) {
			  	// When validation fails or other local issues
			  	$facebook_error = 'Facebook SDK returned an error: ' . $e->getMessage();
			}		
		} else {
			$facebook_error = 'No Facebook page was found with that url.';
		}

		if ( ! isset( $facebook_error ) ) {
			$likes[] = $facebook_likes;
		} else {
			$likes[] = 0;
		}
	}

	if ( $likes ) {
		return array_sum( $likes );
	} else {
		return 0;
	}
}


/**
 * Get a stakeholder's Instagram Followers.
 *
 * @param  string  $instagram_urls List of valid Facebook URL, comma separated.	 
 * @return string 				   The number of followers or an error message.
 */
function stakeholder_instagram_followers( $instagram_urls ) {

	$urls = explode( ',', $instagram_urls );

	$followers = array();

	foreach( $urls as $url ) {
		$instagram_response = file_get_contents( $url . '?__a=1' );
		if ($instagram_response !== false) {
		    $data = json_decode($instagram_response, true);
		    if ($data !== null) {
		        $instagram_followers = $data['user']['followed_by']['count'];
		    }
		} else {
			$instagram_error = 'No Instagram page was found with that url.';
		}

		if ( isset( $instagram_followers ) ) {
			$followers[] = $instagram_followers;
		} else {
			$followers[] = 0;
		}
	}

	if ( $followers ) {
		return array_sum( $followers );
	} else {
		return 0;
	}
}


/**
 * Get a stakeholder's Twitter Followers.
 *
 * @param  string  $twitter_urls List of valid Facebook URL, comma separated.	 
 * @return string 				 The number of followers or an error message.
 */
function stakeholder_twitter_followers( $twitter_urls ) {

	$urls = explode( ',', $twitter_urls );

	$followers = array();

	foreach( $urls as $url ) {
		preg_match( "/^https?:\/\/(www\.)?twitter\.com\/(#!\/)?(?<name>[^\/]+)(\/\w+)*$/", $url, $matches );
		    $twitter_user = $matches['name'];
		$twitter_response = file_get_contents( 'https://cdn.syndication.twimg.com/widgets/followbutton/info.json?screen_names=' . $twitter_user );
		if ($twitter_response !== false) {
			$data = json_decode($twitter_response, true);
		    if ($data !== null) {
			    $twitter_followers = $data[0]['followers_count'];
		    }
		} else {
			$twitter_error = 'No Twitter user was found with that url.';
		}
		if ( isset( $twitter_followers ) ) {
			$followers[] = $twitter_followers;
		} else {
			$followers[] = 0;
		}
	}

	if ( $followers ) {
		return array_sum( $followers );
	} else {
		return 0;
	}
}


/**
 * Get a stakeholder's YouTube Subscribers.
 *
 * @param  string  $youtube_urls List of valid Facebook URL, comma separated.	 
 * @return string 				 The number of subscribers or an error message.
 */
function stakeholder_youtube_subscribers( $youtube_urls ) {

	$urls = explode( ',', $youtube_urls );

	$subscribers = array();

	foreach( $urls as $url ) {
		$youtube_regex = '~(?:http|https|)(?::\/\/|)(?:www.|)(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/ytscreeningroom\?v=|\/feeds\/api\/videos\/|\/user\S*[^\w\-\s]|\S*[^\w\-\s]))([\w\-]{24})[a-z0-9;:@#?&%=+\/\$_.-]*~i';
	 	$youtube_id = preg_replace( $youtube_regex, '$1', $url );
		$youtube_key = 'AIzaSyB2YaajEMRRPc_UDsS0sfZ9unLpDmU8qok';
		$youtube_response = file_get_contents( 'https://www.googleapis.com/youtube/v3/channels?part=statistics&id=' . $youtube_id . '&fields=items/statistics/subscriberCount&key=' . $youtube_key );
		if ($youtube_response !== false) {
			$data = json_decode($youtube_response, true);
		    if ($data !== null) {
				$youtube_subscribers = $data['items'][0]['statistics']['subscriberCount'];
		    }
		} else {
			$youtube_error = 'No YouTube channel was found with that url.';
		}
		if ( isset( $youtube_subscribers ) ) {
			$subscribers[] = $youtube_subscribers;
		} else {
			$subscribers[] = 0;
		}
	}

	if ( $subscribers ) {
		return array_sum( $subscribers );
	} else {
		return 0;
	}
}


/**
 * Get the top result of a specific social network in all of the stakeholders.
 *
 * @param  string  $meta A valid meta field.	 
 *
 * @return string 		 The top number of subscribers, followers or likes.
 */
function stakeholders_top_result( $meta, $name = false, $relation = '' ) {
	if ( $relation != '' ) {
		$args = array(
			'meta_key'		=> $meta,
			'post_type'		=> 'stakeholder',
			'posts_per_page' 	=> -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'stakeholder-relation',
					'field'    => 'slug',
					'terms'    => $relation,
				),
			),
		);
	} else {
		$args = array(
			'meta_key'		=> $meta,
			'post_type'		=> 'stakeholder',
			'posts_per_page' 	=> -1,
		);
	}

	$results = array();
	$stakeholders = array();

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
	    while ( $query->have_posts() ) {
	        $query->the_post();
	        $results[] = get_post_meta( get_the_ID(), $meta, true );
	        if ( $name == true ) {
	        	$stakeholders[] = '<a href="' . esc_url( get_permalink() ) . '" target="_blank">' . esc_html( get_the_title() ) . '</a>';
	        }
	    }
	}
	wp_reset_postdata();

	$top_result = max($results);
	$top_stakeholder = '';

	if ( ! empty( $results ) ) {
		if ( $name == true && ! empty( $stakeholders ) ) {
			for ($i = 0; $i < count($results); $i++) {
				if ( $results[$i] == $top_result ) {
					$top_stakeholder = $stakeholders[$i];
				}
			}
			return $top_stakeholder;
		} else {
			return $top_result;
		}
	} else {
		return 0;
	}
}

/**
 * Get the total result of a specific social network in all of the stakeholders.
 *
 * @param  string  $meta A valid meta field.	 
 *
 * @return string 		 The total number of subscribers, followers or likes.
 */
function stakeholders_total_result( $meta ) {
	$args = array(
		'meta_key'		=> $meta,
		'post_type'		=> 'stakeholder',
		'posts_per_page' 	=> -1,
	);

	$results = array();

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
	    while ( $query->have_posts() ) {
	        $query->the_post();
	        $results[] = get_post_meta( get_the_ID(), $meta, true );
	    }
	}
	wp_reset_postdata();

	if ( ! empty( $results ) ) {
		return( array_sum( $results ) );
	} else {
		return 0;
	}
}
