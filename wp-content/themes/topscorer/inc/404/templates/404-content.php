<div id="qodef-404-page">
    <p class="qodef-404-tagline">
        <?php echo esc_html ( $tagline ); ?>
    </p>
    <h1 class="qodef-404-title">
        <?php echo esc_html ( $title ); ?>
    </h1>
    <p class="qodef-404-text">
        <?php echo esc_html ( $text ); ?>
    </p>
    <div class="qodef-404-button">
        <?php
        $button_params = array (
            'link'                   => esc_url ( home_url ( '/' ) ),
            'text'                   => esc_html ( $button_text ),
            'button_layout'          => 'outlined',
            'size'                   => 'large',
            'color'                  => esc_attr ( $button_color ),
            'background_color'       => esc_attr ( $button_background_color ),
            'border_color'           => esc_attr ( $button_border_color ),
            'hover_color'            => esc_attr ( $button_hover_color ),
            'hover_background_color' => esc_attr ( $button_background_hover_color ),
            'hover_border_color'     => esc_attr ( $button_border_hover_color ),
        );

        topscorer_render_button_element ( $button_params );
        ?>
    </div>
</div>