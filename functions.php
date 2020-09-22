<?php

/**
 * Load parent theme style
 */
add_action( 'wp_enqueue_scripts', 'jnews_child_enqueue_parent_style' );

function jnews_child_enqueue_parent_style()
{
	
	wp_enqueue_script('jquery');
    wp_enqueue_style( 'jnews-parent-style', get_parent_theme_file_uri('/style.css'));
}

/**
 * Add support for multiple authors.
 */
add_filter('jnews_module_post_meta_1', 'publishpress_authorspost_meta_1', 10, 3);

function publishpress_authorspost_meta_1( $output, $post, $moduleView ) {
	$output = '';

	if ( get_theme_mod( 'jnews_show_block_meta', true ) ) {
		$comment    = jnews_get_comments_number( $post->ID );
		$view_count = jnews_meta_views( $post->ID );

		// author detail
		if (!function_exists('get_multiple_authors')) {
			$author        = $post->post_author;
			$author_url    = get_author_posts_url( $author );
			$author_name   = get_the_author_meta( 'display_name', $author );
			$author_avatar = $avatar ?
				'<div class="jeg_author_avatar">
					' . get_avatar( get_the_author_meta( 'ID', $post->post_author ), 80, null, get_the_author_meta( 'display_name', $post->post_author ) ) . '
				</div>' : '';
		}


		$trending = ( vp_metabox( 'jnews_single_post.trending_post', null, $post->ID ) ) ? "<div class=\"jeg_meta_trending\"><a href=\"" . get_the_permalink( $post ) . "\"><i class=\"fa fa-bolt\"></i></a></div>" : "";

		if ( jnews_is_review( $post->ID ) ) {
			$rating = jnews_generate_rating( $post->ID, 'jeg_landing_review' );

			$output .= "<div class=\"jeg_post_meta\">";
			$output .= $trending;
			$output .= get_theme_mod( 'jnews_show_block_meta_rating', true ) ? $rating : "";

            if (function_exists('get_multiple_authors')) {
				$authors = get_multiple_authors();

				$output .= "<div class=\"jeg_meta_author\"><span class=\"by\">" . jnews_return_translation( 'by', 'jnews', 'by' ) . "</span>";

				foreach ($authors as $author) {
					$output .= "<a href=\"{$author->link}\">{$author->display_name}</a>";
				}

				$output .= '</div>';
            } else {
				$output .= get_theme_mod( 'jnews_show_block_meta_author', true ) ? (jnews_check_coauthor_plus() ? "<div class=\"jeg_meta_author coauthor\">" . jnews_get_author_coauthor( $post->ID, false, 'by', 1 ) . "</div>" : "<div class=\"jeg_meta_author\"><span class=\"by\">" . jnews_return_translation( 'by', 'jnews', 'by' ) . "</span> <a href=\"{$author_url}\">{$author_name}</a></div>" ) : "";
			}

			
			$output .= "</div>";
		} else {
			$output .= "<div class=\"jeg_post_meta\">";
			$output .= $trending;

			if (function_exists('get_multiple_authors')) {
				$authors = get_multiple_authors();

				$output .= "<div class=\"jeg_meta_author\"><span class=\"by\">" . jnews_return_translation( 'by', 'jnews', 'by' ) . "</span>";

				foreach ($authors as $author) {
					$output .= $author->get_avatar(80) . " <a href=\"{$author->link}\">{$author->display_name}</a>";
				}

				$output .= '</div>';
            } else {
				$output .= get_theme_mod( 'jnews_show_block_meta_author', true ) ? ( jnews_check_coauthor_plus() ? "<div class=\"jeg_meta_author coauthor\">" . jnews_get_author_coauthor( $post->ID, $avatar, 'by', 1 ) . "</div>" : "<div class=\"jeg_meta_author\">" . $author_avatar . "<span class=\"by\">" . jnews_return_translation( 'by', 'jnews', 'by' ) . "</span> <a href=\"{$author_url}\">{$author_name}</a></div>" ) : "";
			}

			$output .= get_theme_mod( 'jnews_show_block_meta_date', true ) ? "<div class=\"jeg_meta_date\"><a href=\"" . get_the_permalink( $post ) . "\"><i class=\"fa fa-clock-o\"></i> " . $moduleView->format_date( $post ) . "</a></div>" : "";
			$output .= get_theme_mod( 'jnews_show_block_meta_comment', true ) ? "<div class=\"jeg_meta_comment\"><a href=\"" . jnews_get_respond_link( $post->ID ) . "\" ><i class=\"fa fa-comment-o\"></i> {$comment} </a></div>" : "";
			$output .= get_theme_mod( 'jnews_show_block_meta_views', false ) ? "<div class=\"jeg_meta_views\"><a href=\"" . get_the_permalink( $post->ID ) . "\" ><i class=\"fa fa-eye\"></i> {$view_count} </a></div>" : "";
			$output .= "</div>";
		}
	}

	return $output;
}