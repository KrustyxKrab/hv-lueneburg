<?php

// Include page footer top area
topscorer_template_part( 'footer', 'templates/parts/footer-top-area' );

if ( topscorer_is_installed( 'core' ) ) {
	// Include page footer bottom area
	topscorer_template_part( 'footer', 'templates/parts/footer-bottom-area' );
}