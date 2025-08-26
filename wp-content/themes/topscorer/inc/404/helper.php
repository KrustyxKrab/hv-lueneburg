<?php

if ( ! function_exists ( 'topscorer_set_404_page_inner_classes' ) ) {
    /**
     * Function that return classes for the page inner div from header.php
     *
     * @param $classes string
     *
     * @return string
     */
    function topscorer_set_404_page_inner_classes ( $classes ) {

        if ( is_404() ) {
            $classes = 'qodef-content-full-width';
        }

        return $classes;
    }

    add_filter ( 'topscorer_filter_page_inner_classes', 'topscorer_set_404_page_inner_classes' );
}

if ( ! function_exists ( 'topscorer_get_404_page_parameters' ) ) {
    /**
     * Function that set 404 page area content parameters
     */
    function topscorer_get_404_page_parameters() {

        $params = array (
            'tagline'                       => esc_html__( 'Error 404', 'topscorer' ),
            'title'                         => esc_html__( 'Error Page', 'topscorer' ),
            'text'                          => esc_html__( 'Oops! The page you are looking for does not exist. It might have been moved or deleted.', 'topscorer' ),
            'button_text'                   => esc_html__( 'Back to home', 'topscorer' ),
            'button_color'                  => '#0d0d0d',
            'button_background_color'       => 'transparent',
            'button_border_color'           => '#e8e8e9',
            'button_hover_color'            => '#ffffff',
            'button_background_hover_color' => '#fe5900',
            'button_border_hover_color'     => '#fe5900',
        );

        return apply_filters ( 'topscorer_filter_404_page_template_params', $params );
    }
}
