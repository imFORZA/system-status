<?php

get_header();


if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();

		echo '<h1>' . the_title() . '</h1>';

		// the_time('F jS, Y');
		// the_time('g:i a');

		echo '<br />';

		$day = the_time('d');
		$month = the_time('m');
		$year = the_time('Y');
		echo '<a href="' . get_day_link( $year, $month, $day ) . '">' . get_day_link( $year, $month, $day ) . '</a>';

		echo '<br />';

		echo '<a href="' . get_month_link( $year, $month ) . '">' . get_month_link( $year, $month ) . '</a>';
		echo '<br />';

		echo '<a href="' . get_year_link( $year ) . '">' . get_year_link( $year ) . '</a>';

		the_content();

	} // Endwhile().
} // Endif().


?>

				<h2>Maintenance Report</h2>


<?php			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

				$args = array(
					'post_type' => 'ss-notice',
					'meta_key'     => 'notice_maintenance_id',
					'meta_value'   => get_the_ID(),
					'posts_per_page' => 500
				);
				$notices = new WP_Query( $args ); ?>

				<?php if ( $notices->have_posts() ) : ?>
				<?php while ( $notices->have_posts() ) : $notices->the_post(); ?>

				<div class="" style="clear:both;border:1px solid #ddd;margin:20px;">
				<?php the_title(); ?>
				<?php the_content(); ?>

				<small><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago - '; ?><?php echo the_time('D, M, Y'); ?></small>
				</div>

				<?php endwhile; endif; ?>

<?php


get_sidebar();
get_footer();
