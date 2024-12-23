<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Blossom_Wedding
 */

get_header(); ?>
		<?php 
	        /**
	         * Post Per Page Count
	         * 
	         * @hooked blossom_wedding_posts_per_page_count
	        */
	        do_action( 'blossom_wedding_before_posts_content' );
        ?>       
        <main id="main" class="site-main">
			<?php
			if ( have_posts() ) :

				/* Start the Loop */
				while ( have_posts() ) : the_post();

					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/content', get_post_format() );
				endwhile;
			else :
				get_template_part( 'template-parts/content', 'none' );
			endif; ?>
		</main><!-- #main -->
        
        <?php
        /**
         * After Posts hook
         * @hooked blossom_wedding_navigation - 15
        */
        do_action( 'blossom_wedding_after_posts_content' );
        ?>
        
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
