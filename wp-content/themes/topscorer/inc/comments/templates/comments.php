<div id="qodef-page-comments">
	<?php if ( have_comments() ) {
		$comments_number = get_comments_number();
		?>
        <div id="qodef-page-comments-list" class="qodef-m">
            <h3 class="qodef-m-title"><?php echo sprintf( _n( '%s Comment', '%s Comments', $comments_number, 'topscorer' ), $comments_number ); ?></h3>
            <ul class="qodef-m-comments">
				<?php wp_list_comments( array_unique( array_merge( array( 'callback' => 'topscorer_get_comments_list_template' ), apply_filters( 'topscorer_filter_comments_list_template_callback', array() ) ) ) ); ?>
            </ul>

			<?php if ( get_comment_pages_count() > 1 ) { ?>
                <div class="qodef-m-pagination qodef--wp">
					<?php the_comments_pagination( array(
						'prev_text'          => esc_html__( '', 'topscorer' ),
						'next_text'          => esc_html__( '', 'topscorer' ),
						'before_page_number' => '0'
					) ); ?>
                </div>
			<?php } ?>
        </div>
	<?php } ?>
	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) { ?>
        <p class="qodef-page-comments-not-found"><?php esc_html_e( 'Comments are closed.', 'topscorer' ); ?></p>
	<?php } ?>

    <div id="qodef-page-comments-form">
		<?php
		$args = array(
			'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
			'title_reply_after'  => '</h3>'
		);

		comment_form( apply_filters( 'topscorer_filter_comment_form_args', $args ) ); ?>
    </div>
</div>