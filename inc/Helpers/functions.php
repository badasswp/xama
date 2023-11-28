<?php
/**
 * Helper Functions.
 *
 * These functions utilize the core class methods and provides
 * an easy way for users to access the resources within those
 * classes without needing to look up the API.
 *
 * @package Xama
 */

/**
 * Get all quizzies.
 *
 * @since 1.0.0
 *
 * @return array
 */
function xama_get_quizzies(): array {
	$cache_key = 'xama_cache_quizzies';

	if ( ! wp_cache_get( $cache_key ) ) {
		$posts = \Xama\Posts\Quiz::get_posts();
		wp_cache_set( $cache_key, $posts );

		return (array) $posts;
	}

	return (array) wp_cache_get( $cache_key );
}

/**
 * Get Questions belonging to ID.
 *
 * @since 1.0.0
 *
 * @param integer $id Post ID.
 * @return array
 */
function xama_get_questions( $id ): array {
	if ( ! isset( $id ) ) {
		return [];
	}

	$cache_key = 'xama_cache_questions_' . (int) $id;

	if ( ! wp_cache_get( $cache_key ) ) {
		$posts = \Xama\Posts\Question::get_posts_by_id( $id );
		wp_cache_set( $cache_key, $posts );

		return (array) $posts;
	}

	return (array) wp_cache_get( $cache_key );
}

/**
 * Return number of Questions belonging to ID.
 *
 * @since 1.0.0
 *
 * @param integer $id
 * @return integer
 */
function xama_get_count_questions( $id ): int {
	if ( ! isset( $id ) ) {
		return 0;
	}

	return (int) count( xama_get_questions( $id ) );
}
