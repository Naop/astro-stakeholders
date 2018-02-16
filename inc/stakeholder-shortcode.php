<?php
/**
 * Stakeholder Shortcode
 *
 * @package Astro Stakeholders
 */

add_shortcode('stakeholders', 'astro_stakeholders_shortcode');
function astro_stakeholders_shortcode( $atts ) {
    $args = shortcode_atts(array(
        'order' => '',
    ), $atts, 'stakeholders' );

    if ( $args['order'] == 'score' ) {
    	$query_args = array(
	        'post_type' 		=> 'stakeholder',
	        'posts_per_page' 	=> -1,
	        'meta_key'			=> 'evaluation_total-score',
	        'orderby'			=> 'meta_value_num',
	        'order'				=> 'DESC',
	    );
    } else {
    	$query_args = array(
	        'post_type' 		=> 'stakeholder',
	        'posts_per_page' 	=> -1,
	    );
    }



    $loop = new WP_Query($query_args); 

    ob_start();

    if( $loop->have_posts() ): 
    
        if ( $args['order'] == 'score' ) {
            echo '<p>';
            echo '<strong>' . __( 'Top Facebook Likes:', 'astro-stakeholders' ) . '</strong>';
            echo ' ' . number_format( stakeholders_top_result( 'social_networks_facebook-likes' ), 0, '.', ',' );
            echo '<br><strong>' . __( 'Top Instagram Followers:', 'astro-stakeholders' ) . '</strong>';
            echo ' ' . number_format( stakeholders_top_result( 'social_networks_instagram-followers' ), 0, '.', ',' );
            echo '<br><strong>' . __( 'Top Twitter Followers:', 'astro-stakeholders' ) . '</strong>';
            echo ' ' . number_format( stakeholders_top_result( 'social_networks_twitter-followers' ), 0, '.', ',' );
            echo '<br><strong>' . __( 'Top YouTube Subscribers:', 'astro-stakeholders' ) . '</strong>';
            echo ' ' . number_format( stakeholders_top_result( 'social_networks_youtube-subscribers' ), 0, '.', ',' );
            echo '</p>';
        }
    ?>
    
    <table class="data-container">
		<tbody>
			<tr>
				<th><?php _e( 'Nombre', 'astro-app' ); ?></th>
				<th><i class="fas fa-home fa-lg"></i></th>
				<th><i class="fab fa-facebook fa-lg"></i></th>
				<th><i class="fab fa-instagram fa-lg"></i></th>
				<th><i class="fab fa-twitter fa-lg"></i></th>
				<th><i class="fab fa-youtube fa-lg"></i></th>
				<th>
					<?php _e( 'Importance', 'astro-app' ); ?>
					<br><?php _e( '(Us)', 'astro-app' ); ?>
				</th>
				<th>
					<?php _e( 'Importance', 'astro-app' ); ?>
					<br><?php _e( '(Them)', 'astro-app' ); ?>
				</th>
				<th><?php _e( 'Presence', 'astro-app' ); ?></th>
				<th><?php _e( 'Total', 'astro-app' ); ?></th>
			</tr>
    <?php
        while( $loop->have_posts() ): $loop->the_post(); 

            $id = get_the_ID();
            
            $website = get_post_meta( get_the_ID(), 'contact_information_website', true );
            
            $fb_urls = get_post_meta( get_the_ID(), 'social_networks_facebook', true );
            $ig_urls = get_post_meta( get_the_ID(), 'social_networks_instagram', true );
            $tw_urls = get_post_meta( get_the_ID(), 'social_networks_twitter', true );
            $yt_urls = get_post_meta( get_the_ID(), 'social_networks_youtube', true );

            $fb_likes = get_post_meta( get_the_ID(), 'social_networks_facebook-likes', true );
            $ig_followers = get_post_meta( get_the_ID(), 'social_networks_instagram-followers', true );
            $tw_followers = get_post_meta( get_the_ID(), 'social_networks_twitter-followers', true );
            $yt_subscribers = get_post_meta( get_the_ID(), 'social_networks_youtube-subscribers', true );

            $score_1 = get_post_meta( get_the_ID(), 'evaluation_importance-us-score', true );
            $score_2 = get_post_meta( get_the_ID(), 'evaluation_importance-them-score', true );
            $score_3 = get_post_meta( get_the_ID(), 'evaluation_presence-score', true );
            $total_score = get_post_meta( get_the_ID(), 'evaluation_total-score', true );

    ?>
            <tr id="stakeholder-<?php echo $id; ?>">
                <td><?php
                	the_title( '<h3 class="stakeholder-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); 
                ?></td>
                <td><?php
                	if ( $website ) {
                		echo '<a href="' . esc_url( $website ) . '" target="_blank"><i class="fas fa-external-link-alt"></i></a>';
                	}
                ?></td>
                <td><?php
 	              	if ( $fb_urls ) {
                		echo stakeholder_link_urls( $fb_urls );
                	}
                    if ( $fb_likes ) {
                        echo '<br>' . esc_html( number_format( $fb_likes, 0, '.', ',' ) );
                    }
                ?></td>
                <td><?php
                	if ( $ig_urls ) {
                		echo stakeholder_link_urls( $ig_urls );
                	}
                    if ( $ig_followers ) {
                        echo '<br>' . esc_html( number_format( $ig_followers, 0, '.', ',' ) );
                    }
                ?></td>
                <td><?php
                	if ( $tw_urls ) {
                		echo stakeholder_link_urls( $tw_urls );
                	}
                    if ( $tw_followers ) {
                        echo '<br>' . esc_html( number_format( $tw_followers, 0, '.', ',' ) );
                    }
                ?></td>
                <td><?php
                	if ( $yt_urls ) {
                		echo stakeholder_link_urls( $yt_urls );
                	}
                    if ( $yt_subscribers ) {
                        echo '<br>' . esc_html( number_format( $yt_subscribers, 0, '.', ',' ) );
                    }
                ?></td>
                <td><?php
                    if ( $score_1 ) {
                    	echo esc_html( $score_1 );
                    }
                ?></td>
                <td><?php
                    if ( $score_2 ) {
                	   echo esc_html( $score_2 );
                    }
                ?></td>
                <td><?php
                    if ( $score_3 ) {
                    	echo esc_html( $score_3 );
                    }
                ?></td>
                <td><?php
                    if ( $total_score ) {
                    	echo esc_html( $total_score );
                    }
                ?></td>
            </tr>
    <?php   
        endwhile; 
    ?>
        </tbody>
    </table>
    <?php else : ?>
        <span><?php _e( 'No stakeholders were found.', 'astro-stakeholders' ); ?></span>
    <?php
    endif;
    $module = ob_get_contents();
    ob_end_clean();
    wp_reset_postdata();
    return $module;
}
