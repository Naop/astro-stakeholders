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
    
        if ( $args['order'] == 'score' ) :
    ?>
    <h2>Top Followers</h2>
    <?php
            $top_fb = stakeholders_top_result( 'social_networks_facebook-likes' );
            $top_ig = stakeholders_top_result( 'social_networks_instagram-followers' );
            $top_tw = stakeholders_top_result( 'social_networks_twitter-followers' );
            $top_yt = stakeholders_top_result( 'social_networks_youtube-subscribers' );
            
            echo '<div class="text-results" style="float: left; width: auto; margin-right: 100px;">';
            echo '<strong>' . __( 'Top Facebook Likes:', 'astro-stakeholders' ) . '</strong>';
            echo ' ' . stakeholders_top_result( 'social_networks_facebook-likes', true );
            echo ' - ' . number_format( $top_fb, 0, '.', ',' );
            echo '<br><strong>' . __( 'Top Instagram Followers:', 'astro-stakeholders' ) . '</strong>';
            echo ' ' . stakeholders_top_result( 'social_networks_instagram-followers', true );
            echo ' - ' . number_format( $top_ig, 0, '.', ',' );
            echo '<br><strong>' . __( 'Top Twitter Followers:', 'astro-stakeholders' ) . '</strong>';
            echo ' ' . stakeholders_top_result( 'social_networks_twitter-followers', true );            
            echo ' - ' . number_format( $top_tw, 0, '.', ',' );
            echo '<br><strong>' . __( 'Top YouTube Subscribers:', 'astro-stakeholders' ) . '</strong>';
            echo ' ' . stakeholders_top_result( 'social_networks_youtube-subscribers', true );            
            echo ' - ' . number_format( $top_yt, 0, '.', ',' );
            echo '</div>';       
    ?>
    <div class="chart-container" style="position: relative; height: 300px; width: 300px; float: left; margin-bottom: 50px;">
        <canvas id="top-followers-chart" width="100" height="100"></canvas>
    </div>
    <script>
        var ctx = document.getElementById("top-followers-chart");
        var topChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ["Facebook", "Instagram", "Twitter", "YouTube"],
                datasets: [{
                    data: [<?php echo $top_fb . ',' . $top_ig . ',' . $top_tw . ',' . $top_yt; ?>],
                    backgroundColor: [
                        'rgb(59, 89, 152)',
                        'rgb(138, 58, 185)',
                        'rgb(85, 172, 238)',
                        'rgb(255, 0, 0)'
                    ]
                }]
            }
        });
    </script>
    
    <h2>Total Followers</h2>
    <?php 
            $total_fb = stakeholders_total_result( 'social_networks_facebook-likes' );
            $total_ig = stakeholders_total_result( 'social_networks_instagram-followers' );
            $total_tw = stakeholders_total_result( 'social_networks_twitter-followers' );
            $total_yt = stakeholders_total_result( 'social_networks_youtube-subscribers' );
            
            echo '<div class="text-results" style="float: left; width: auto; margin-right: 100px;">';
            echo '<strong>' . __( 'Total Facebook Likes:', 'astro-stakeholders' ) . '</strong>';
            echo ' ' . number_format( $total_fb, 0, '.', ',' );
            echo '<br><strong>' . __( 'Total Instagram Followers:', 'astro-stakeholders' ) . '</strong>';
            echo ' ' . number_format( $total_ig, 0, '.', ',' );
            echo '<br><strong>' . __( 'Total Twitter Followers:', 'astro-stakeholders' ) . '</strong>';
            echo ' ' . number_format( $total_tw, 0, '.', ',' );
            echo '<br><strong>' . __( 'Total YouTube Subscribers:', 'astro-stakeholders' ) . '</strong>';
            echo ' ' . number_format( $total_yt, 0, '.', ',' );
            echo '</div>';  
    ?>
    <div class="chart-container" style="position: relative; height: 300px; width: 300px; float: left; margin-bottom: 50px;">
        <canvas id="total-followers-chart" width="100" height="100"></canvas>
    </div>
    <script>
        var ctx = document.getElementById("total-followers-chart");
        var totalChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ["Facebook", "Instagram", "Twitter", "YouTube"],
                datasets: [{
                    data: [<?php echo $total_fb . ',' . $total_ig . ',' . $total_tw . ',' . $total_yt; ?>],
                    backgroundColor: [
                        'rgb(59, 89, 152)',
                        'rgb(138, 58, 185)',
                        'rgb(85, 172, 238)',
                        'rgb(255, 0, 0)'
                    ]
                }]
            }
        });
    </script>  
    <?php
        endif;
    ?>

    <table class="data-container">
		<tbody>
			<tr>
				<th><?php _e( 'Nombre', 'astro-stakeholders' ); ?></th>
                <th><?php _e( 'Relation', 'astro-stakeholders' ); ?></th>;
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
            
            $relations = stakeholder_terms( 'stakeholder-relation' );

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
                    if ( $relations ) {
                        echo $relations;
                    }
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
