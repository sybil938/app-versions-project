<?php get_header(); ?>

<div id="archive-appversions" class="container">
	<div class="row">
		    

		<div class="col-md-12">
			<ul class="timeline">    

				<?php $current_page = get_query_var('paged', 1); ?>
		
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

					<li class="event" title="<?php echo the_title(); ?>">

						<?php if( ($current_page == 0 || $current_page == 1) && ($wp_query->current_post + 1) < ($wp_query->post_count) ) : ?>
							<div class="latest-release">Latest release</div>
						<?php endif;  ?>

						<div class="title"><a href="<?php echo the_permalink(); ?>"><?php the_title(); ?></a></div>	
							
						<?php $repeatable_fields = get_post_meta($post->ID, 'repeatable_fields', true);  if ( $repeatable_fields ) : ?>	

							<?php if ($repeatable_fields[0]['feature'] != NULL) { echo '<div class="sub-title">Added</div>'; } ?>
							<ul><?php foreach ( $repeatable_fields as $field ) { ?>	
					            <?php if($field['feature'] != '') echo '<li>'. esc_attr( $field['feature'] ) . '</li>'; ?>
					        <?php } ?></ul>

							<?php if ($repeatable_fields[0]['remove'] != NULL) { echo '<div class="sub-title">Removed</div>'; } ?>
					        <ul><?php foreach ( $repeatable_fields as $field ) { ?>
					            <?php if($field['remove'] != '') echo '<li>'. esc_attr( $field['remove'] ) . '</li>'; ?>
					        <?php } ?></ul> 				        

							<?php if ($repeatable_fields[0]['change'] != NULL) { echo '<div class="sub-title">Changed</div>'; } ?>
					        <ul><?php foreach ( $repeatable_fields as $field ) { ?>
					            <?php if($field['change'] != '') echo '<li>'. esc_attr( $field['change'] ) . '</li>'; ?>
					        <?php } ?></ul> 

							<?php if ($repeatable_fields[0]['fix'] != NULL) { echo '<div class="sub-title">Fixed</div>'; } ?>
					        <ul><?php foreach ( $repeatable_fields as $field ) { ?>
					            <?php if($field['fix'] != '') echo '<li>'. esc_attr( $field['fix'] ) . '</li>'; ?>
					        <?php } ?></ul>  
					        
						<?php endif; ?>

					</li>
		
				<?php endwhile; ?> 
			</ul>
		</div>

		<div class="col-md-12">
			<div class="navigation">
				<?php 				

				 	if ( $current_page == 0 || $current_page == 1 ){
				    	echo '<div class="first-page">';
				    	echo next_posts_link('Next');
				    	echo '</div>';
				 	} else if ($current_page == $wp_query->max_num_pages) {
						echo '<div class="last-page">';
						echo previous_posts_link('Previous');
						echo '</div>';
					} else {
						echo '<div class="middle-page">';
						posts_nav_link('&nbsp;','Previous','Next');
						echo '</div>';
					}
				?>
			</div>
		</div>	

		<?php endif; ?>
	

		<div class="col-md-12">
			<p class="mt-5 mb-5 pt-5 pl-3">We value your feedback, so if you have something to share then email us at <a href="mailto:sybil938@gmail.com">sybil938@gmail.com</a>.</p>
		</div>	

		
	</div>
</div>	

<?php get_footer(); ?>
