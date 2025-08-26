<?php if ( comments_open() ) { ?>
	<div class="qodef-e-info-item qodef-e-info-comments">
		<a itemprop="url" class="qodef-e-info-comments-link" href="<?php comments_link(); ?>">
			<?php comments_number( '0 ' . esc_html__( 'Comments', 'topscorer' ), '1 ' . esc_html__( 'Comment', 'topscorer' ), '% ' . esc_html__( 'Comments', 'topscorer' ) ); ?>
		</a>
	</div>
<?php } ?>