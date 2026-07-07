<?php

/**
 * @package    JS Like Box Slider
 * @license    GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @link       https://jsns.eu
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

modSlideLikebox::getLikebox($params);

class modSlideLikebox
{
	public static function getLikebox($params)
	{
		$document = Factory::getApplication()->getDocument();

		$facebook_id = $params->get('facebook_login');
		$twitter_id =  $params->get('twitter_login');
		$font_awesome_cdn = $params->get('fa_cdn');

		$api_url = trim((string) $params->get('api_url'));
		$api_key = trim((string) $params->get('api_key'));
		$api_user_id = trim((string) $params->get('api_user_id'));

		$document->addStyleSheet(Uri::root() . 'modules/mod_facebook_slide_likebox/tmpl/css/style.min.css');
		if ($font_awesome_cdn == 1) {
			$document->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css');
		}

		#______________________MOBILE________________________
		if (trim($params->get('show_on_mobile')) == 1)
		{
			?>
			<div class="jssocial_mobile_view">
				<div class="buttons_container">
				<?php
					$sum = 0;
					if (!empty($facebook_id))
					{
						$sum++;
						$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
						$iPad    = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
						$Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");

						if ($iPhone || $iPad)
						{
							$fb_url = 'fb://profile/' . $facebook_id;
						}
						else if ($Android) {
							$fb_url = 'fb://page/' . $facebook_id;
						}
						else
						{
							$fb_url = 'https://facebook.com/' . $facebook_id;
						}
						?>
						<a class="facebook" href="<?php echo $fb_url ?>" target="_blank">
							<i class="fab fa-facebook-f"></i>
						</a>
						<?php
					}
					if (!empty($twitter_id))
					{
						$sum++;
						?>
						<a class="twitter" href="https://twitter.com/<?php echo $twitter_id ?>" target="_blank">
							<i class="fab fa-x-twitter"></i>
						</a>
						<?php
					}
					$mobile_style = '.jssocial_mobile_view a, .jssocial_mobile_view a:focus, .jssocial_mobile_view a:hover { width: calc(100% / ' . $sum . ');}';
					$document->addStyleDeclaration($mobile_style);
				?>
				</div>
			</div>
			<?php
		}

		#______________________DESKTOP________________________
		?>
		<div class="jssocial_desktop_view" style="top: <?php echo $params->get('margin_top') ?> !important;">
		<?php
			if (!empty($facebook_id))
			{
				?>
				<input id="facebookTab" type="radio" name="tabs" checked />
				<label for="facebookTab" class="facebook_icon" style="max-width: 32px;">
					<span>facebook</span>
					<i class="fab fa-facebook-f"></i>
				</label>
				<section id="facebookContent">
					<div class="facebook_box">
						<?php
							$fb_fallback = stripos($facebook_id, 'http') === 0
								? $facebook_id
								: 'https://www.facebook.com/' . $facebook_id;
							echo self::renderFeed(
								'facebook',
								self::getFeed('facebook', $facebook_id, $api_url, $api_key, $api_user_id),
								$fb_fallback,
								$facebook_id
							);
						?>
					</div>
				</section>
				<?php
			}
			if (!empty($twitter_id))
			{
				?>
				<input id="twitterTab" type="radio" name="tabs" />
				<label for="twitterTab" class="twitter_icon" style="max-width: 32px;">
					<span>x.com</span>
					<i class="fab fa-x-twitter"></i>
				</label>
				<section id="twitterContent">
					<div class="twitter_box">
						<?php
							echo self::renderFeed(
								'twitter',
								self::getFeed('twitter', $twitter_id, $api_url, $api_key, $api_user_id),
								'https://x.com/' . ltrim((string) $twitter_id, '@'),
								$twitter_id
							);
						?>
					</div>
				</section>
				<?php
			}
			if (trim($params->get('position')) == 1)
			{
				if (trim($params->get('buttons_shape')) == 1)
				{
					$buttons_shape = '.jssocial_desktop_view .facebook_icon, .jssocial_desktop_view .twitter_icon {border-radius: 0 7px 7px 0 !important;}';
					$document->addStyleDeclaration($buttons_shape);
				}
				$position_left = '.jssocial_desktop_view {left:-370px;}.jssocial_desktop_view:hover{transform: translateX(370px);}.jssocial_desktop_view .facebook_icon{float:right;right:-31px; clear: right;}.jssocial_desktop_view .twitter_icon{float:right; clear: right;right:-31px}';
				$document->addStyleDeclaration($position_left);
			}
			else
			{
				if (trim($params->get('position')) == 0)
				{
					if (trim($params->get('buttons_shape')) == 1)
					{
						$buttons_shape = '.jssocial_desktop_view .facebook_icon, .jssocial_desktop_view .twitter_icon {border-radius: 7px 0 0 7px !important;}';
						$document->addStyleDeclaration($buttons_shape);
					}
					$position_right = '.jssocial_desktop_view {right:-370px;}.jssocial_desktop_view:hover{transform: translateX(-370px);} .jssocial_desktop_view .facebook_icon{float:left;left:-31px; clear: left;}.jssocial_desktop_view .twitter_icon{float:left;left:-31px; clear: left;}';
					$document->addStyleDeclaration($position_right);
				}
			}
			?>
			<div class="copyrightlink">Designed with
				<span style="color: #f44336;">❤</span> by
				<a title="Joomla Extensions" target="_blank" href="https://jsns.eu" rel="noopener">jsns.eu</a>
			</div>
		</div>
		<?php
	}

	/**
	 * Fetch a normalized feed ({profile, posts}) from the Social Feed API.
	 *
	 * The call is made server-side so the API key never reaches the browser.
	 * Results are cached on disk; the last successful response is reused
	 * whenever a new fetch fails or the API is still warming up.
	 *
	 * @param   string  $platform    'facebook' or 'twitter'.
	 * @param   string  $identifier  Page id/handle for the platform.
	 * @param   string  $apiUrl      Base URL of the Social Feed API.
	 * @param   string  $apiKey      API key.
	 * @param   string  $userId      Subscription user ID.
	 *
	 * @return  array|null  {platform, profile, posts} or null when unavailable.
	 */
	private static function getFeed($platform, $identifier, $apiUrl, $apiKey, $userId)
	{
		$identifier = trim((string) $identifier);

		if ($identifier === '' || $apiUrl === '' || $apiKey === '' || $userId === '') {
			return null;
		}

		$cacheDir  = JPATH_CACHE . '/mod_facebook_slide_likebox';
		$cacheFile = $cacheDir . '/' . $platform . '_' . md5(strtolower($identifier)) . '.json';
		$ttl       = 1800; // 30 minutes

		if (is_file($cacheFile) && (time() - filemtime($cacheFile)) < $ttl) {
			$cached = json_decode(file_get_contents($cacheFile), true);

			if (is_array($cached)) {
				return $cached;
			}
		}

		if ($platform === 'facebook') {
			$endpoint = rtrim($apiUrl, '/') . '/facebook?page_id=' . rawurlencode($identifier);
		} else {
			$endpoint = rtrim($apiUrl, '/') . '/x?username=' . rawurlencode(ltrim($identifier, '@'));
		}
		$endpoint .= '&user_id=' . rawurlencode($userId);

		$data = null;

		if (function_exists('curl_init')) {
			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_URL            => $endpoint,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_TIMEOUT        => 12,
				CURLOPT_HTTPHEADER     => array(
					'X-API-Key: ' . $apiKey,
					'User-Agent: JSLikeBoxSlider/1.0',
				),
			));

			$response = curl_exec($ch);
			$code     = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			if ($response !== false && $code === 200) {
				$json = json_decode($response, true);

				if (isset($json['data']) && is_array($json['data'])) {
					$data = $json['data'];
				}
			}
		}

		// Cache only responses that actually carry posts.
		if (is_array($data) && !empty($data['posts'])) {
			if (!is_dir($cacheDir)) {
				@mkdir($cacheDir, 0755, true);
			}

			@file_put_contents($cacheFile, json_encode($data));

			return $data;
		}

		// Fetch failed or API still warming up: serve the last successful data.
		if (is_file($cacheFile)) {
			$stale = json_decode(file_get_contents($cacheFile), true);

			if (is_array($stale)) {
				return $stale;
			}
		}

		return is_array($data) ? $data : null;
	}

	/**
	 * Render a feed tab: profile header + posts, with a graceful "follow"
	 * fallback when no posts are available.
	 *
	 * @param   string      $platform    'facebook' or 'twitter'.
	 * @param   array|null  $data        Feed data from getFeed().
	 * @param   string      $fallbackUrl Profile URL used for the follow button.
	 * @param   string      $identifier  Raw identifier (fallback label).
	 *
	 * @return  string  HTML markup.
	 */
	private static function renderFeed($platform, $data, $fallbackUrl, $identifier)
	{
		$isX     = ($platform === 'twitter' || $platform === 'x');
		$profile = (is_array($data) && isset($data['profile']) && is_array($data['profile'])) ? $data['profile'] : array();
		$posts   = (is_array($data) && isset($data['posts']) && is_array($data['posts'])) ? $data['posts'] : array();

		$username  = (isset($profile['username']) && $profile['username'] !== '') ? $profile['username'] : (string) $identifier;
		$name      = (isset($profile['display_name']) && $profile['display_name'] !== '') ? $profile['display_name'] : $username;
		$handle    = $username;
		$avatar    = isset($profile['avatar']) ? (string) $profile['avatar'] : '';
		$followers = isset($profile['followers']) ? (int) $profile['followers'] : 0;
		$verified  = !empty($profile['verified']);
		$profUrl   = isset($profile['url']) && $profile['url'] !== '' ? $profile['url'] : $fallbackUrl;

		$icon      = $isX ? 'fa-x-twitter' : 'fa-facebook-f';
		$accent    = $isX ? '#000000' : '#1877f2';
		$followTxt = $isX ? 'Follow on X' : 'Follow on Facebook';

		ob_start();
		?>
		<div class="jssocial_feed" style="--jssocial-accent: <?php echo $accent; ?>;">
			<style>
				.jssocial_feed{display:flex;flex-direction:column;height:500px;max-height:500px;background:#fff}
				.jssocial_feed *{box-sizing:border-box}
				.jssocial_head{display:flex;align-items:center;gap:10px;padding:12px;border-bottom:1px solid #eee}
				.jssocial_head_avatar{width:44px;height:44px;border-radius:50%;object-fit:cover;flex:0 0 44px;background:#eee}
				.jssocial_head_ph{width:44px;height:44px;border-radius:50%;flex:0 0 44px;display:flex;align-items:center;justify-content:center;color:#fff;background:var(--jssocial-accent)}
				.jssocial_head_info{min-width:0;flex:1}
				.jssocial_head_name{display:flex;align-items:center;gap:4px;font-weight:700;font-size:14px;color:#0f1419;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
				.jssocial_head_name a{color:inherit;text-decoration:none}
				.jssocial_head_verified{color:#1d9bf0;font-size:13px}
				.jssocial_head_sub{font-size:12px;color:#536471;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
				.jssocial_posts{flex:1;overflow-y:auto;overflow-x:hidden}
				.jssocial_post{display:block;padding:12px;border-bottom:1px solid #f0f0f0;color:#0f1419;text-decoration:none}
				.jssocial_post:hover{background:#f7f9f9}
				.jssocial_post_text{margin:0 0 8px;font-size:14px;line-height:1.4;word-wrap:break-word;overflow-wrap:anywhere}
				.jssocial_post_img{width:100%;max-height:180px;object-fit:cover;border-radius:10px;margin:0 0 8px;display:block}
				.jssocial_post_meta{display:flex;justify-content:space-between;align-items:center;font-size:12px;color:#536471}
				.jssocial_post_stats i{margin-left:8px}
				.jssocial_empty{flex:1;display:flex;align-items:center;justify-content:center;padding:20px;text-align:center;color:#536471;font-size:14px}
				.jssocial_follow{display:block;margin:10px;padding:10px;text-align:center;background:var(--jssocial-accent);color:#fff;border-radius:9999px;text-decoration:none;font-weight:600}
				.jssocial_follow:hover{opacity:.85}
			</style>
			<div class="jssocial_head">
				<?php if ($avatar !== '') : ?>
					<img class="jssocial_head_avatar" src="<?php echo htmlspecialchars($avatar); ?>" alt="" loading="lazy" />
				<?php else : ?>
					<span class="jssocial_head_ph"><i class="fab <?php echo $icon; ?>"></i></span>
				<?php endif; ?>
				<div class="jssocial_head_info">
					<div class="jssocial_head_name">
						<a href="<?php echo htmlspecialchars($profUrl); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars($name); ?></a>
						<?php if ($verified) : ?><i class="fas fa-circle-check jssocial_head_verified"></i><?php endif; ?>
					</div>
					<div class="jssocial_head_sub">
						<?php echo $isX ? '@' . htmlspecialchars($handle) : htmlspecialchars($handle); ?><?php if ($followers > 0) : ?> · <?php echo htmlspecialchars(self::formatCount($followers)); ?> followers<?php endif; ?>
					</div>
				</div>
			</div>
			<?php if (!empty($posts)) : ?>
				<div class="jssocial_posts">
					<?php foreach ($posts as $post) : ?>
						<?php
							$purl     = isset($post['url']) && $post['url'] !== '' ? $post['url'] : $profUrl;
							$caption  = isset($post['caption']) ? (string) $post['caption'] : '';
							$image    = isset($post['image']) ? (string) $post['image'] : '';
							$created  = isset($post['created_at']) ? (string) $post['created_at'] : '';
							$likes    = isset($post['likes']) ? (int) $post['likes'] : 0;
							$comments = isset($post['comments']) ? (int) $post['comments'] : 0;
							$shares   = isset($post['shares']) ? (int) $post['shares'] : 0;
						?>
						<a class="jssocial_post" href="<?php echo htmlspecialchars($purl); ?>" target="_blank" rel="noopener">
							<?php if ($image !== '') : ?>
								<img class="jssocial_post_img" src="<?php echo htmlspecialchars($image); ?>" alt="" loading="lazy" />
							<?php endif; ?>
							<?php if ($caption !== '') : ?>
								<p class="jssocial_post_text"><?php echo nl2br(htmlspecialchars($caption)); ?></p>
							<?php endif; ?>
							<div class="jssocial_post_meta">
								<span class="jssocial_post_date"><?php echo htmlspecialchars(self::formatDate($created)); ?></span>
								<span class="jssocial_post_stats">
									<?php if ($isX) : ?>
										<i class="fas fa-retweet"></i> <?php echo self::formatCount($shares); ?>
										<i class="fas fa-heart"></i> <?php echo self::formatCount($likes); ?>
									<?php else : ?>
										<i class="fas fa-thumbs-up"></i> <?php echo self::formatCount($likes); ?>
										<i class="fas fa-comment"></i> <?php echo self::formatCount($comments); ?>
									<?php endif; ?>
								</span>
							</div>
						</a>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<div class="jssocial_empty">
					<p>Latest posts are unavailable right now. Visit the profile instead.</p>
				</div>
			<?php endif; ?>
			<a class="jssocial_follow" href="<?php echo htmlspecialchars($profUrl); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars($followTxt); ?></a>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Format a timestamp into a short, human readable date.
	 *
	 * @param   string  $raw  Raw created_at value.
	 *
	 * @return  string  Formatted date or empty string.
	 */
	private static function formatDate($raw)
	{
		$raw = (string) $raw;

		if ($raw === '') {
			return '';
		}

		$timestamp = is_numeric($raw) ? (int) $raw : strtotime($raw);

		return $timestamp ? date('M j, Y', $timestamp) : '';
	}

	/**
	 * Compact large numbers, e.g. 28671583 -> "28.7M".
	 *
	 * @param   int  $n  Value to format.
	 *
	 * @return  string
	 */
	private static function formatCount($n)
	{
		$n = (int) $n;

		if ($n >= 1000000) {
			return rtrim(rtrim(number_format($n / 1000000, 1), '0'), '.') . 'M';
		}
		if ($n >= 1000) {
			return rtrim(rtrim(number_format($n / 1000, 1), '0'), '.') . 'K';
		}

		return (string) $n;
	}
}
?>
