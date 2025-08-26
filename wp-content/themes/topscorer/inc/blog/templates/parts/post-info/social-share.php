<?php if ( topscorer_is_installed ( 'core' ) ) : ?>
    <div class="qodef-e-info-item qodef-e-info-social-share">
        <?php

        $params                = array();
        $params[ 'layout' ]    = 'list';
        $params[ 'icon_font' ] = 'elegant-icons';

        echo TopScorerCoreSocialShareShortcode ::call_shortcode ( $params );

        ?>

        <a href="javascript:void(0)">
        	<span class="qodef-e-title">
	        	<?php echo esc_html__( 'Share', 'topscorer' ) ?>
    	    </span>
        </a>
    </div>
<?php endif; ?>