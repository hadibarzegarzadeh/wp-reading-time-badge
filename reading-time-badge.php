<?php
/**
 * Plugin Name: Reading Time Badge
 * Description: Shows estimated reading time before post content. Shortcode: [reading_time].
 * Version: 1.0.0
 * Author: hadibarzegarzadeh
 * License: GPL-2.0-or-later
 * Text Domain: reading-time-badge
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function rtb_calculate_minutes( $content ) {
    $words = str_word_count( wp_strip_all_tags( $content ) );
    return max( 1, (int) ceil( $words / 200 ) );
}

function rtb_badge( $post_id = null ) {
    $post = get_post( $post_id );
    if ( ! $post ) {
        return '';
    }
    $minutes = rtb_calculate_minutes( $post->post_content );
    return '<p class="rtb-badge">' . sprintf( esc_html__( 'Reading time: %d min', 'reading-time-badge' ), $minutes ) . '</p>';
}

add_shortcode( 'reading_time', function () {
    return rtb_badge();
} );

add_filter( 'the_content', function ( $content ) {
    if ( is_singular( 'post' ) && in_the_loop() && is_main_query() ) {
        return rtb_badge() . $content;
    }
    return $content;
} );
