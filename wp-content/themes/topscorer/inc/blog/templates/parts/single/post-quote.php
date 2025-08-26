<article <?php post_class( 'qodef-blog-item qodef-e' ); ?>>
    <div class="qodef-e-inner">
		<?php
		// Include post format part
		topscorer_template_part( 'blog', 'templates/parts/post-format/quote' );
		?>
        <div class="qodef-e-content">
            <div class="qodef-e-text">
				<?php
				// Include post content
				the_content();

				// Hook to include additional content after blog single content
				do_action( 'topscorer_action_after_blog_single_content' );
				?>
            </div>
            <div class="qodef-e-info qodef-info--bottom">
                <div class="qodef-e-info-left">
					<?php
					// Include post tags info
					topscorer_template_part( 'blog', 'templates/parts/post-info/tags' );
					?>
                </div>
                <div class="qodef-e-info-right">
					<?php
					// Include post share share
					topscorer_template_part( 'blog', 'templates/parts/post-info/social-share' );
					?>
                </div>
            </div>
        </div>
    </div>
</article>