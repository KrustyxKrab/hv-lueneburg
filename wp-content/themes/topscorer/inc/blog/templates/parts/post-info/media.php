<div class="qodef-e-media">
	<?php switch ( get_post_format() ) {
		case 'gallery':
			topscorer_template_part( 'blog', 'templates/parts/post-format/gallery' );
			break;
		case 'video':
			topscorer_template_part( 'blog', 'templates/parts/post-format/video' );
			break;
		case 'audio':
			topscorer_template_part( 'blog', 'templates/parts/post-format/audio' );
			break;
		default:
			topscorer_template_part( 'blog', 'templates/parts/post-info/image' );
			break;
	} ?>
</div>