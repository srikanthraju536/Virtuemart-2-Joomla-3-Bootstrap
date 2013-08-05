<?php defined ( '_JEXEC' ) or die ();

// add messgae in bootstrap style
$app   = JFactory::getApplication();
$messages = $app->getMessageQueue();
// html code for front-end administration
$view=jrequest::getWord('view');
$task =jrequest::getWord('task');
if ($pid = jrequest::getInt('virtuemart_product_id'))
	$link = 'productdetails&virtuemart_product_id='.$pid;
elseif ($cid = jrequest::getInt('virtuemart_category_id'))
	$link = 'category&virtuemart_category_id='.$cid;
else $link ='virtuemart';
JHtml::_('script', 'system/core.js', false, true);
$document = JFactory::getDocument();
JHtml::_('jquery.ui');
vmJsApi::js ('jquery.ui.autocomplete.html');
$document->addStyleSheet(JURI::root(true).'/administrator/components/com_virtuemart/assets/css/admin.styles.css');
$j = "
	jQuery(function() {
		jQuery( '#virtuemartSave').click(function(e){
			e.preventDefault();
			jQuery( '#media-dialog' ).remove();
			document.adminForm.task.value='apply';
			document.adminForm.submit();
			return false;
		});
		jQuery('link[rel=stylesheet][href*=\"template\"]').remove();
	});
" ;
$document->addScriptDeclaration ( $j);
$document->addStyleDeclaration('
 body,.vmadmin{width:100%;background-color:#fff !important;background-image:none !important;margin:0px;}
 #system-message-container { display: none; }
/*#toolbar {padding-left: 10px;}
.vm2admin #adminForm input[type="text"] {width: auto;}
.vm2admin .navbar{margin-bottom:0px}
#system-message .message > ul {list-style: none outside none;}
#system-message dt { display: none; }
#system-message-container dl, #system-message-container dd{margin:0px} */
');
?>

<div class="vm2admin row-fluid">
	<?php if (count($messages) ) {
		foreach ($messages as $message ) {
			
		
			?>
			<div class="alert alert-<?php echo $message['type'] ?>">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<?php echo $message['message'] ?>
			</div>
			<?php 
		}
	} ?>
	<div class="header">
		<div class="container-fluid">
			<h1 class="page-title"><?php echo $document->getTitle(); ?>
				<div class="nav pull-right">
					<a class="btn" href="<?php echo jRoute::_('index.php?option=com_virtuemart&view='.$link ) ?>"><?php echo jText::_('COM_VIRTUEMART_CLOSE') ?></a>
				</div>
				<div class="clearfix"></div>
			</h1>

		</div>
	</div>
	<div class="subhead-collapse">
		<div class="subhead">
			<div class="container-fluid">
				<div id="container-collapse" class="container-collapse"></div>
				<div class="row-fluid">
					<div class="span12">
						<?php $toolbar = JToolbar::getInstance('toolbar')->render('toolbar'); 
							echo $toolbar;
						?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div  class="row-fluid">