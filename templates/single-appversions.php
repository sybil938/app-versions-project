<?php get_header(); ?>

<div class="container">
	<div id="single-appversions" class="row">

		<div class="col-md-12" >	

			<div class="table-responsive">
  				<table class="table table-striped">
					<tr>
						<td><div class="title">Version</div></td>
						<td><?php the_title(); ?></td>
					</tr>	

					<?php while ( have_posts() ) : the_post(); ?>
					<?php if (!empty(the_content())) : ?>
					<tr>
						<td><div class="title">Description</div></td>
						<td><?php the_content(); ?></td>											
					</tr>
					<?php endif; ?>	
					<?php endwhile; ?>

					<tr>
						<td><div class="title">Release date</div></td>
						<td><?php echo get_post_meta($post->ID,'release_date',true); ?></td>
					</tr>


					<?php $repeatable_fields = get_post_meta($post->ID, 'repeatable_fields', true);  if ( $repeatable_fields ) : ?>

						<?php if ($repeatable_fields[0]['feature'] != NULL): ?>
				        <tr>
				        	<td><div class="title">Added</div></td>
					        <td>
					        	<ul>
					        		<?php foreach ( $repeatable_fields as $field ) { ?>
					            	<?php if($field['feature'] != '') echo '<li>'. esc_attr( $field['feature'] ) . '</li>'; ?>
						        	<?php } ?>
						    </ul>
							</td>
						</tr>
						<?php endif; ?>	
						

						<?php if ($repeatable_fields[0]['remove'] != NULL): ?>
				        <tr>
				        	<td><div class="title">Removed</div></td>
					        <td>
					        	<ul>
					        		<?php foreach ( $repeatable_fields as $field ) { ?>
					            	<?php if($field['remove'] != '') echo '<li>'. esc_attr( $field['remove'] ) . '</li>'; ?>
						        	<?php } ?>
						    </ul>
							</td>
						</tr>
						<?php endif; ?>	


						<?php if ($repeatable_fields[0]['change'] != NULL): ?>
				        <tr>
				        	<td><div class="title">Changed</div></td>
					        <td>
					        	<ul>
					        		<?php foreach ( $repeatable_fields as $field ) { ?>
					            	<?php if($field['change'] != '') echo '<li>'. esc_attr( $field['change'] ) . '</li>'; ?>
						        	<?php } ?>
						    </ul>
							</td>
						</tr>
						<?php endif; ?>	  


						<?php if ($repeatable_fields[0]['fix'] != NULL): ?>
				        <tr>
				        	<td><div class="title">Fixed</div></td>
					        <td>
					        	<ul>
					        		<?php foreach ( $repeatable_fields as $field ) { ?>
					            	<?php if($field['fix'] != '') echo '<li>'. esc_attr( $field['fix'] ) . '</li>'; ?>
						        	<?php } ?>
						    </ul>
							</td>
						</tr>
						<?php endif; ?>			
						

					<?php endif; ?>			

				</table>	


				
									

				


				
					
					
				
				
		



			</div>
			
		</div>	

	</div>
</div>	

<?php get_footer(); ?>
