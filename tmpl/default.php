<?php
/**
 * JS Social Tabs Slider
 * @license    GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @link       https://jsns.eu
 */

	defined( '_JEXEC' ) or die( 'Restricted access' );
	$document = JFactory::getDocument();
	echo $slidelikebox;

	$document->addStyleSheet(JURI::root() . 'modules/mod_facebook_slide_likebox/tmpl/css/style.min'.'.css', 'text/css', null, array() );
	
	if (trim( $params->get( 'position' ) ) == 0){
		$document->addStyleSheet(JURI::root() . 'modules/mod_facebook_slide_likebox/tmpl/css/right.min'.'.css?123', 'text/css', null, array() );
	}
	else if (trim( $params->get( 'position' ) ) == 1){
		$document->addStyleSheet(JURI::root() . 'modules/mod_facebook_slide_likebox/tmpl/css/left.min'.'.css', 'text/css', null, array() );
	}
?>
