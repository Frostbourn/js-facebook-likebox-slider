<?php
/**
 * Facebook Likebox Slider
 * @license    GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @link       https://jsns.eu
 */

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

class modSlideLikebox {
	public static function getLikebox( $params )   {
		global $mainframe;
			$document = JFactory::getDocument();

#______________________MOBILE________________________
 
        if (trim($params->get('show_on_mobile')) == 1) {
            if (trim($params->get('twitter')) == 1) {
                $t = 1;
            } else {
                $t=0;
            }
            if (trim($params->get('facebook')) == 1) {
                $f = 1;
            } else {
                $f=0;
            }

            $sum = $f + $t;
            $mobile = '#social_mobile a {position: relative;float: left; width: calc(100% / ' . $sum . ');display:list-item; list-style-type: none;} #social_mobile a:focus, #social_mobile a:hover { width: calc(100% / ' . $sum . ');-moz-transition-property: none; -webkit-transition-property: none; -o-transition-property: none;transition-property: none;}';
            $document->addStyleDeclaration($mobile); ?>
			<div id="social_mobile">
				<div class="top-left">
					<?php
                    if (trim($params->get('facebook')) == 1) { ?>
						<a class="facebook pop-upper" href="https://www.facebook.com/<?php echo $params->get('profile_id') ?>" target="_blank">
							<i class="fa fa-facebook-f"></i>
						</a>
					<?php }
            		if (trim($params->get('twitter')) == 1) { ?>
						<a class="twitter pop-upper" href="https://twitter.com/<?php echo $params->get('twitter_login'); ?>" target="_blank">
							<i class="fa fa-twitter"></i>
						</a>
					<?php } ?>
				</div>
			</div>
		<?php }

#______________________DESKTOP________________________

			if (trim( $params->get( 'buttons_shape' ) ) == 1) {
				$buttons_shape = '.social_slider .facebook_icon, .social_slider .twitter_icon {border-radius: 7px 0 0 7px !important;}';
				$document->addStyleDeclaration( $buttons_shape );
			} 
			if (trim( $params->get( 'position' ) ) == 1) {
				$position1 = '.social_slider {position: fixed;left: -370px;top: 120px;z-index: 99997;-webkit-transition: left 1s ease-in-out; -moz-transition: left 1s ease-in-out;-o-transition: left 1s ease-in-out;transition: left 1s ease-in-out;}.social_slider:hover{ left: 0px;}';
				$document->addStyleDeclaration( $position1 );
			}
			else if (trim( $params->get( 'position' ) ) == 0) {
				$position0 = '.social_slider{position:fixed;right:-370px;top:120px;z-index:99997;-webkit-transition:right 1s ease-in-out;-moz-transition:right 1s ease-in-out;-o-transition:right 1s ease-in-out;transition:right 1s ease-in-out}.social_slider:hover{right:0}';
				$document->addStyleDeclaration( $position0 );
			}

			if (trim( $params->get( 'fbstyle' ) ) == 0) { ?>
				<div class="social_slider" style="top: <?php echo $params->get('margintop') ?> !important;">
			<?php } else if (trim( $params->get( 'fbstyle' ) ) == 1) { ?>
				<div class="social_slider" style="top: 0px !important;">
			<?php } 

				if (trim($params->get('facebook')) == 1) { ?>
					<input id="tab1" type="radio" name="tabs" checked />
					<label for="tab1" class="facebook_icon"  style="max-width: 32px;"></label>
				<?php } 

				if (trim($params->get('twitter')) == 1) { ?>
					<input id="tab2" type="radio" name="tabs" />
					<label for="tab2" class="twitter_icon" style="max-width: 32px;<?php if (trim($params->get('facebook')) == 0){ ?> top: 50px;<?php  if (trim($params->get('position')) == 1) { ?>left:370px;<?php } else {?>right:32px;<?php } } else { }?>"></label>
				<?php } 

				if (trim($params->get('facebook')) == 1){ ?>
					<section id="content1">
						<div class="facebook_box">
							<iframe src="https://www.facebook.com/plugins/page.php?href=https://www.facebook.com/<?php
									echo $params->get('profile_id');
									?>&tabs=timeline&width=350&height=470&small_header=false&adapt_container_width=false&hide_cover=false&show_facepile=true" width="350" height="470" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true">
							</iframe>
						</div>
					</section>
				<?php }

				if (trim($params->get('twitter')) == 1) { 
					if (trim($params->get('facebook')) == 0){ ?>
						<section id="content2" style="display: block;">
					<?php } else { ?>
						<section id="content2"> 
					<?php } ?>
							<div class="twitter_box">
								<a class="twitter-timeline" data-width="350" data-height="470" href="https://twitter.com/<?php
									echo $params->get('twitter_login');
									?>">Tweets by <?php
									echo $params->get('twitter_login');
								?></a><script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
							</div>
						</section>
				<?php } ?>
				<span class="copyrightlink">Designed with <span style="color: #f44336;">❤</span> by <a title="Projektowanie stron internetowych" target="_blank" href="http://jakubskowronski.com" rel="noopener noreferrer">jakubskowronski.com</a></span>
			</div>
<?php
	}
}
?>
