<?php
if (!defined('ABSPATH')) {
	exit;
}

?>
<div class="wb_tab_panel wb_tab_panel_<?php echo esc_attr($tab_type);?>" title="<?php echo ($is_hidden_global_tab ? esc_attr__('This tab is not visible on the product page.', 'wb-custom-product-tabs-for-woocommerce') : '');?>">
	<div class="wb_tab_panel_hd">
		<span class="wb_tab_panel_hd_txt">
			<?php 
			if ( ! empty( $tab_nickname ) ) {
				echo esc_html( $tab_nickname . " ($tab_title)" );
			} else {
				echo esc_html( $tab_title );
			}

			if($is_hidden_global_tab)
			{
				?>
				<span class="wb_tab_panel_global_tab_not_published">
					⚠ <?php esc_html_e('Not published', 'wb-custom-product-tabs-for-woocommerce'); ?>						
				</span>
				<?php
			}
			?>	
		</span>	
		<?php
		if($tab_type=='local')
		{
		?>
			<div class="wb_tab_panel_delete" title="<?php esc_html_e('Delete tab', 'wb-custom-product-tabs-for-woocommerce'); ?>">
				<span class="dashicons dashicons-trash" style="line-height:30px;"></span>
			</div>
			<div class="wb_tab_panel_edit" title="<?php esc_html_e('Edit tab', 'wb-custom-product-tabs-for-woocommerce'); ?>">
				<span class="dashicons dashicons-edit" style="line-height:30px;"></span>
			</div>
		<?php
		}else
		{
			?>
			<a class="wb_tab_panel_global_tab_not_edit_link" href="<?php echo esc_url($tab_edit_url);?>" target="_blank">
				<?php esc_html_e('Edit', 'wb-custom-product-tabs-for-woocommerce'); ?> <span class="dashicons dashicons-external"></span>	
			</a>
			<div class="wb_tab_panel_global_tab_label"><?php esc_html_e('Global tab', 'wb-custom-product-tabs-for-woocommerce'); ?></div>
			<?php
		}
		?>	
	</div>
	<div class="wb_tab_panel_content">
		<?php
		if($tab_type=='local')
		{
		?>
			<div class="wb_tab_panel_frmgrp">
				<label><?php esc_html_e('Tab title', 'wb-custom-product-tabs-for-woocommerce');?><span class="woocommerce-help-tip" data-tip="<?php esc_attr_e('Title for tab', 'wb-custom-product-tabs-for-woocommerce'); ?>"></span></label>
				<input type="text" name="wb_tab[<?php echo esc_attr($key);?>][title]" class="wb_tabpanel_txt wb_tab_title_input" placeholder="<?php esc_attr_e('Title for tab', 'wb-custom-product-tabs-for-woocommerce'); ?>" value="<?php echo esc_attr($tab_title);?>">
				<div class="wb_tab_er"></div>
			</div>
			<div class="wb_tab_panel_frmgrp">
				<label><?php esc_html_e('Tab content', 'wb-custom-product-tabs-for-woocommerce');?><span class="woocommerce-help-tip" data-tip="<?php esc_attr_e('Content for tab', 'wb-custom-product-tabs-for-woocommerce'); ?>"></span></label>
				<textarea name="wb_tab[<?php echo esc_attr($key);?>][content]" class="wb_tabpanel_txtarea wb_tab_content_input" placeholder="<?php esc_attr_e('Content for tab', 'wb-custom-product-tabs-for-woocommerce'); ?>" rows="4"><?php echo esc_textarea(stripslashes($tab_content));?></textarea>
			</div>
			<div class="wb_tab_panel_frmgrp">
				<label><?php esc_html_e('Tab position', 'wb-custom-product-tabs-for-woocommerce');?><span class="woocommerce-help-tip" data-tip="<?php esc_attr_e('Tab position', 'wb-custom-product-tabs-for-woocommerce'); ?>"></span></label>
				<input type="number" min="0" step="1" name="wb_tab[<?php echo esc_attr($key);?>][position]" class="wb_tabpanel_txt wb_tab_position_input" placeholder="<?php esc_attr_e('Tab position', 'wb-custom-product-tabs-for-woocommerce'); ?>" value="<?php echo esc_attr($position);?>">
				<div class="wb_tab_er"></div>
			</div>
			<div class="wb_tab_panel_frmgrp">
				<label><?php esc_html_e('Tab nickname', 'wb-custom-product-tabs-for-woocommerce');?><span class="woocommerce-help-tip" data-tip="<?php esc_attr_e('Tab nickname', 'wb-custom-product-tabs-for-woocommerce'); ?>"></span></label>
				<input type="text" name="wb_tab[<?php echo esc_attr($key);?>][nickname]" class="wb_tabpanel_txt wb_tab_nickname_input" placeholder="<?php esc_attr_e('Tab nickname', 'wb-custom-product-tabs-for-woocommerce'); ?>" value="<?php echo esc_attr($tab_nickname);?>">
				<div class="wb_tab_er"></div>
			</div>
			<div class="wb_tab_panel_frmgrp">
				<label><?php esc_html_e('Tab slug', 'wb-custom-product-tabs-for-woocommerce');?><span class="woocommerce-help-tip" data-tip="<?php esc_attr_e('Tab slug', 'wb-custom-product-tabs-for-woocommerce'); ?>"></span></label>
				<input type="text" name="wb_tab[<?php echo esc_attr($key);?>][slug]" class="wb_tabpanel_txt wb_tab_slug_input" placeholder="<?php esc_attr_e('Tab slug', 'wb-custom-product-tabs-for-woocommerce'); ?>" value="<?php echo esc_attr($tab_slug);?>">
				<div class="wb_tab_er"></div>
			</div>
		<?php
		}
		?>
	</div>
</div>