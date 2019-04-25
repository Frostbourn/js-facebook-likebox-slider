<?php
/**
 * Facebook Likebox Slider
 * @license    GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @link       https://jsns.eu
 */
 
//no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
 
// include the helper file
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php');

$slidelikebox = modSlideLikebox::getLikebox( $params );
require( JModuleHelper::getLayoutPath( 'mod_facebook_slide_likebox' ) );

?>