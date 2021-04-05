<div class="wrap">
	<h1><?= __( "WooCommerce Image Mapper", "cm-prod-img-map" ) ?></h1>
	<p><?= __( "Map images to SKUs or select a default placeholder as the featured image", "cm-prod-img-map" ) ?></p>
	<?php
	if ( isset( $_POST['submit_image_selector'] ) && isset( $_POST['image_attachment_id'] ) ) :
		update_option( 'placeholder_img_attachment_id', absint( $_POST['image_attachment_id'] ) );
	endif;

	wp_enqueue_media();

	?>
	<form method='post'>
		<h2>Placeholder Image</h2>
		<div class='image-preview-wrapper'>
			<img id='image-preview'
				 src='<?php echo wp_get_attachment_url( get_option( 'placeholder_img_attachment_id' ) ); ?>'
				 height='100'>
		</div>
		<input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload image' ); ?>"/>
		<input type='hidden' name='image_attachment_id' id='image_attachment_id'
			   value='<?php echo get_option( 'placeholder_img_attachment_id' ); ?>'>
		<input type="submit" name="submit_image_selector" value="Save" class="button-primary">
	</form>
	<hr>
	<h2>Begin Mapping</h2>
	<?php
	settings_fields( 'cm-prod-img-map-g2' );
	do_settings_sections( 'cm-prod-img-map-g2' );
	?>
	<div>
		<input type="checkbox" id="iUnderstand" name="iUnderstand">
		<label for="iUnderstand"> I understand that this will reset some or all of the images attached to the current
			products in the store</label>
	</div>
	<br>
	<div>
		<button class="button button-primary" id="beginMapping">Start Mapping</button>
	</div>
	<br>
	<h2>Mapping Progress</h2>
	<div id="progressContainer">

	</div>
</div>

