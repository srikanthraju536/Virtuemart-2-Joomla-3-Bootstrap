<?php
/**
*
* Handle the Product Custom Fields
*
* @package	VirtueMart
* @subpackage Product
* @author RolandD, Patrick khol, Val�rie Isaksen
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: product_edit_waitinglist.php 2978 2011-04-06 14:21:19Z alatak $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if (isset($this->product->customfields_fromParent)) { ?>
	<label><?php echo JText::_('COM_VIRTUEMART_CUSTOM_SAVE_FROM_CHILD');?><input type="checkbox" name="save_customfields" value="1" /></label>
<?php } else {
	?> <input type="hidden" name="save_customfields" value="1" />
<?php }  ?>
<div id="customfieldsTable" width="100%">
	<?php
			$i=0;
			$tables= array('categories'=>'','products'=>'','kit'=>'','fields'=>'','cart'=>'');
			if (isset($this->product->customfields)) {
				foreach ($this->product->customfields as $customfield) {
					// if ($customfield->is_cart_attribute) $cartIcone=  'default';
					// else  $cartIcone= 'default-off';
					if ($customfield->field_type == 'Z') {
						// R: related categories
						$tables['categories'] .=  '
							<div class="vm_thumb_image">
								<span>'.$customfield->display.'</span>'.
								VirtueMartModelCustomfields::setEditCustomHidden($customfield, $i)
							  .'<div class="icon-remove"></div>
							</div>';

					} elseif ($customfield->field_type == 'R') {
					// R: related products
						$tables['products'] .=  '
							<div class="vm_thumb_image">
								<span>'.$customfield->display.'</span>'.
								VirtueMartModelCustomfields::setEditCustomHidden($customfield, $i)
							  .'<div class="icon-remove"></div>
							</div>';

					} elseif ($customfield->field_type == 'K') {
					// K: product kit
						$tables['kit'] .=  '
							<div class="vm_thumb_image">
								<span>'.$customfield->display.'</span>'.
								VirtueMartModelCustomfields::setEditCustomHidden($customfield, $i)
							  .'<div class="icon-remove  pull-right"></div>
							</div>';

					} elseif ($customfield->field_type == 'G') {
						// no display (group of) child , handled by plugin;
					} else {
						if ($customfield->custom_tip) $tip = ' class="hasTooltip" title="'.$customfield->custom_tip.'"';
						else $tip ='';
						// make 2 table. Cart options and datas
						$tbName = $customfield->is_cart_attribute ? 'cart' : 'fields' ;
						$tables[$tbName] .= '<tr class="removable">
							<td class="hidden-phone"><span class="icon-menu"></span>
								<input class="ordering" type="hidden" value="'.$customfield->ordering.'" name="field['.$i .'][ordering]" />
							</td>
							<td><div '.$tip.'>'.JText::_($customfield->custom_title).'<div>'.
								($customfield->custom_field_desc ? '<small>'.$customfield->custom_field_desc.'</small>' : '').'
							</td>
							<td>'.$customfield->display.'</td>
							<td class="hidden-phone">
							'.JText::_($this->fieldTypes[$customfield->field_type]).'
							'.VirtueMartModelCustomfields::setEditCustomHidden($customfield, $i).'
							</td>
							<td><span class="icon-remove hasTooltip" title="'. JText::_('JTOOLBAR_REMOVE', true) .'"></span></td>
							
						 </tr>';
						}

					$i++;
				}
			}

			 $emptyTable = '
				<tr class="custom-empty">
					<td colspan="7">'.JText::_( 'COM_VIRTUEMART_CUSTOM_NO_TYPES').'</td>
				<tr>';
			?>
			<fieldset>
				<legend><?php echo JText::_('COM_VIRTUEMART_RELATED_CATEGORIES'); ?></legend>
				<?php echo JText::_('COM_VIRTUEMART_CATEGORIES_RELATED_SEARCH'); ?>
				<div class="jsonSuggestResults input-append" style="width: 100%" id="relatedcategories-div">
					<input type="text" size="40" name="search" id="relatedcategoriesSearch" value="" />
					<button class="reset-value btn"><?php echo JText::_('COM_VIRTUEMART_RESET') ?></button>
				</div>
				<div id="custom_categories"><?php echo  $tables['categories']; ?></div>
			</fieldset>
			<fieldset>
				<legend><?php echo JText::_('COM_VIRTUEMART_RELATED_PRODUCTS'); ?></legend>
				<?php echo JText::_('COM_VIRTUEMART_PRODUCT_RELATED_SEARCH'); ?>
				<div class="jsonSuggestResults input-append" style="width: 100%" id="relatedproducts-div">
					<input type="text" size="40" name="search" id="relatedproductsSearch" value="" />
					<button class="reset-value btn"><?php echo JText::_('COM_VIRTUEMART_RESET') ?></button>
				</div>
				<div id="custom_products"><?php echo  $tables['products']; ?></div>
			</fieldset>
			<fieldset>
				<legend><?php echo JText::_('COM_VIRTUEMART_PRODUCT'); ?> KIT(Bundle)</legend>
				<?php echo JText::sprintf('JSEARCH_TITLE',JText::_('com_virtuemart_PRODUCT') ); ?>
				<div class="jsonSuggestResults input-append" style="width: 100%" id="productkit-div">
					<input type="text" size="40" name="search" id="productkitSearch" value="" />
					<button class="reset-value btn"><?php echo JText::_('COM_VIRTUEMART_RESET') ?></button>
				</div>
				<div id="custom_productkit"><?php echo  $tables['kit']; ?></div>
			</fieldset>
			<?php if ($this->customsList) { ?>
				<fieldset >
					<legend><?php echo JText::_('COM_VIRTUEMART_CUSTOM_FIELD_TYPE' );?></legend>
					<div class="inline"><?php echo  $this->customsList; ?></div>
					<h3><?php echo JText::_('COM_VIRTUEMART_CUSTOM' );?></h3>
					<table id="custom_fields" class="adminlist table table-striped" cellspacing="0" cellpadding="0">
						<thead>
						<tr>
							<th class="hidden-phone" width="1%"><i class="icon-menu-2 hasTooltip" title="<?php echo JText::_('JGRID_HEADING_ORDERING',true); ?>"></i></th>
							<th><?php echo JText::_('COM_VIRTUEMART_TITLE');?></th>
							<th colspan="2"><?php echo JText::_('COM_VIRTUEMART_VALUE');?></th>
							<th class="hidden-phone"><?php echo JText::_('COM_VIRTUEMART_TYPE');?></th>
							<th width="1%"></th>
						</tr>
						</thead>
						<tbody id="custom_field">
							<?php
							if ($tables['fields']) echo $tables['fields'] ;
							else echo $emptyTable;
							?>
						</tbody>
					</table>
					<!-- custom_fields cart-->
					<h3><?php echo JText::_('COM_VIRTUEMART_CUSTOM_IS_CART_ATTRIBUTE');?></h3>
					<table id="cart_attributes" class="adminlist table table-striped" cellspacing="0" cellpadding="0">
						<thead>
						<tr>
							<th class="hidden-phone" width="1%"><i class="icon-menu-2 hasTooltip" title="<?php echo JText::_('JGRID_HEADING_ORDERING',true); ?>"></i></th>
							<th><?php echo JText::_('COM_VIRTUEMART_TITLE');?></th>
							<th><?php echo JText::_('COM_VIRTUEMART_VALUE');?></th>
							<th><?php echo JText::_('COM_VIRTUEMART_CART_PRICE');?></th>
							<th class="hidden-phone"><?php echo JText::_('COM_VIRTUEMART_TYPE');?></th>
							<th width="1%"></th>
						</tr>
						</thead>
						<tbody id="cart_attribute">
							<?php
							if ($tables['cart']) echo $tables['cart'] ;
							else echo $emptyTable;
							?>
						</tbody>
					</table><!-- custom_fields -->
				</fieldset>
			<?php } else { ?>
				
			<?php } ?>
<div style="clear:both;"></div>
</div>

<script type="text/javascript">
	nextCustom = <?php echo $i ?>;

	jQuery(document).ready(function(){
		jQuery('#custom_field,#cart_attribute').sortable({handle: ".icon-menu", axis: "y",cursor: "move" });
		// Need to declare the update routine outside the sortable() function so
		// that it can be called when adding new customfields
		jQuery('#custom_field,#cart_attribute').bind('sortupdate', function(event, ui) {
			jQuery(this).find('.ordering').each(function(index,element) {
				jQuery(element).val(index);
				//console.log(index+' ');

			});
		});
	});
	jQuery('select#customlist').chosen().change(function() {
		selected = jQuery(this).val();
		if (!selected) return;
		jQuery.getJSON('<?php echo $this->jsonPath ?>index.php?option=com_virtuemart&tmpl=component&view=product&task=getData&format=json&type=fields&id='+selected+'&row='+nextCustom+'&virtuemart_product_id=<?php echo $this->product->virtuemart_product_id; ?>',
		function(data) {
			
			jQuery.each(data.value, function(index, value){
				jQuery("#"+index+' .custom-empty').remove();
				jQuery("#"+index).append(value).find('.hasTooltip').tooltip();
				jQuery('#'+index).trigger('sortupdate');
			});
		});
		nextCustom++;
	});
	jQuery('input#relatedproductsSearch').autocomplete({
		// source: 'index.php?option=com_virtuemart&view=product&task=getData&tmpl=component&format=json&type=relatedproducts&row='+nextCustom,
		source: function( request, response ) {
			jQuery.getJSON( 'index.php?option=com_virtuemart&view=product&task=getData&tmpl=component&format=json&type=relatedproducts&row='+nextCustom,
				request, function( data, status, xhr ) {
				// relatedproducts[ term ] = data;
				response( data );
			});
		},
		select: function(event, ui){
			jQuery("#custom_products").append(ui.item.label);
			nextCustom++;
			return false;
		},
		appendTo: "#relatedproducts-div",
		minLength:1,
		html: true
	});
	jQuery('#productkitSearch').autocomplete({
		// source: 'index.php?option=com_virtuemart&view=product&task=getData&tmpl=component&format=json&type=relatedproducts&row='+nextCustom,
		source: function( request, response ) {
			jQuery.getJSON( 'index.php?option=com_virtuemart&view=product&task=getData&tmpl=component&format=json&type=relatedproducts&kit=1&row='+nextCustom,
				request, function( data, status, xhr ) {
				// relatedproducts[ term ] = data;
				response( data );
			});
		},
		select: function(event, ui){
			jQuery("#custom_productkit").append(ui.item.label);
			nextCustom++;
			return false;
		},
		appendTo: "#productkit-div",
		minLength:1,
		html: true
	});
	jQuery('input#relatedcategoriesSearch').autocomplete({

		// source: 'index.php?option=com_virtuemart&view=product&task=getData&tmpl=component&format=json&type=relatedcategories&row='+nextCustom,
		source: function( request, response ) {
			jQuery.getJSON( 'index.php?option=com_virtuemart&view=product&task=getData&tmpl=component&format=json&type=relatedcategories&row='+nextCustom,
				request, function( data, status, xhr ) {
				response( data );
			});
		},
		select: function(event, ui){
			jQuery("#custom_categories").append(ui.item.label);
			nextCustom++;
			return false;
		},
		appendTo: "#relatedcategories-div",
		minLength:1,
		html: true
	});
	jQuery('#relatedproducts-div,#relatedcategories-div','#productkit-div').delegate('a','click',function() { return false });
	jQuery('#adminForm').on('click','.removable .icon-remove',function() {
		var toRemove = jQuery(this).closest('.removable'); main = toRemove.parent();
		if (main.attr('id') == 'pricesort' && main.children('.removable').length == 1 ) return;
		else console.log(main.attr('id'));
		jQuery(this).closest('.removable').fadeOut(  function() {
			// Animation complete.
			$(this).remove();
		});
	});

function removeParent() {jQuery('#customfieldsParent').remove();
 }

	jQuery('#customfieldsTable').find('input').each(function(i){
		current = jQuery(this);
	current.click(function(){
			jQuery('#customfieldsParent').remove();
		});
});


</script>