<?php

/*
	Plugin Name: Glyphicons for SuperCPT
	Plugin URI: http://github.com/mboynes/super-cpt-glyphics/
	Description: A custom integration of the Glyphicons icon library for SuperCPT. Requires purchase of icons from http://glyphicons.com/
	Version: 0.1
	Author: Matthew Boynes
	Copyright 2011-2013 Shared and distributed between Matthew Boynes and Union Street Media

	GNU General Public License, Free Software Foundation <http://creativecommons.org/licenses/GPL/2.0/>
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

if ( !class_exists( 'SCPT_Glyphicons' ) ) :

class SCPT_Glyphicons {

	private static $instance;

	public $styles = array();

	public $plugin_url;

	private function __construct() {
		/* Don't do anything, needs to be initialized via instance() method */
	}

	public function __clone() { wp_die( "Please don't __clone SCPT_Glyphicons" ); }

	public function __wakeup() { wp_die( "Please don't __wakeup SCPT_Glyphicons" ); }

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new SCPT_Glyphicons;
			self::$instance->setup();
		}
		return self::$instance;
	}

	public function setup() {
		$this->plugin_url = plugins_url( '', __FILE__ );
		add_filter( 'scpt_plugin_icon_glyphicons', array( $this, 'set_glyphicons_icon' ), 10, 3 );
		add_action( 'admin_print_styles', array( $this, 'add_styles' ) );
		add_action( 'scpt_plugin_icon_demos', array( $this, 'icon_demo' ) );
	}


	/**
	 * Add styles to the site <head> if applicable
	 *
	 * @return void
	 */
	public function add_styles() {
		if ( !empty( $this->styles ) ) :
			?>
			<style type="text/css">
				<?php do_action( 'scpt_plugin_icon_css' ) ?>
			</style>
			<?php
		endif;
	}


	/**
	 * Set an icon for a post type from the Font Awesome library
	 *
	 * @param string $none 'none', shouldn't be changed here.
	 * @param array $icon the array argument passed to Super_Custom_Post_Type::set_icon()
	 * @param string $post_type
	 * @return string
	 */
	public function set_glyphicons_icon( $none, $icon, $post_type ) {
		if ( isset( $icon['name'] ) ) {
			$this->register_glyphicons( $post_type );
			$cache_key = 'scpt-icon-' . md5( serialize( $icon ) );
			if ( false === ( $content = get_transient( $cache_key ) ) ) {
				$content = $this->get_glyphicons_icon( $icon['name'] );
				set_transient( $cache_key, $content, DAY_IN_SECONDS );
			}
			$this->styles['glyphicons']['rules'][] = "#adminmenu #menu-posts-{$post_type} div.wp-menu-image:before { content: '{$content}' !important; }";
		}
		return $none;
	}


	/**
	 * We're going to be using Font Awesome for icons, so prepare the CSS that will be injected into the page
	 *
	 * @param string $post_type
	 * @return void
	 */
	public function register_glyphicons( $post_type ) {
		$this->styles['glyphicons']['types'][] = $post_type;
		$dir = SCPT_PLUGIN_URL;
		if ( !isset( $this->styles['glyphicons']['base'] ) ) {
			$this->styles['glyphicons']['base'] = "
			@font-face { font-family: 'Glyphicons'; src: url('{$this->plugin_url}/font/glyphicons-regular.eot'); src: url('{$this->plugin_url}/font/glyphicons-regular.eot?#iefix') format('embedded-opentype'), url('{$this->plugin_url}/font/glyphicons-regular.woff') format('woff'), url('{$this->plugin_url}/font/glyphicons-regular.ttf') format('truetype'), url('{$this->plugin_url}/font/glyphicons-regular.svg#glyphicons_halflingsregular') format('svg'); font-weight: normal; font-style: normal; }
			%s { font-family: Glyphicons !important; -webkit-font-smoothing: antialiased; background: none; *margin-right: .3em; }
			%s { font-family: Glyphicons !important; }
			";
			add_action( 'scpt_plugin_icon_css', array( $this, 'output_glyphicons' ) );
		}
	}


	/**
	 * Output relevant styles for Font Awesome
	 * @return type
	 */
	public function output_glyphicons() {
		$normal = $before = array();
		foreach ( $this->styles['glyphicons']['types'] as $post_type ) {
			$temp = "#adminmenu #menu-posts-{$post_type} div.wp-menu-image";
			$normal[] = $temp;
			$before[] = $temp . ':before';
		}
		printf( $this->styles['glyphicons']['base'], implode( ',', $normal ), implode( ',', $before ) );
		foreach ( $this->styles['glyphicons']['rules'] as $rule ) {
			echo $rule;
		}
	}


	/**
	 * Return the appropriate character for a given Font Awesome icon
	 *
	 * @param string $icon
	 * @return string
	 */
	public function get_glyphicons_icon( $icon ) {

		switch ( $icon ) {

			case 'glass': return '\e001';
			case 'leaf': return '\e002';
			case 'dog': return '\e003';
			case 'user': return '\e004';
			case 'girl': return '\e005';
			case 'car': return '\e006';
			case 'user_add': return '\e007';
			case 'user_remove': return '\e008';
			case 'film': return '\e009';
			case 'magic': return '\e010';
			case 'envelope': return '\2709';
			case 'camera': return '\e012';
			case 'heart': return '\e013';
			case 'beach_umbrella': return '\e014';
			case 'train': return '\e015';
			case 'print': return '\e016';
			case 'bin': return '\e017';
			case 'music': return '\e018';
			case 'note': return '\e019';
			case 'heart_empty': return '\e020';
			case 'home': return '\e021';
			case 'snowflake': return '\2744';
			case 'fire': return '\e023';
			case 'magnet': return '\e024';
			case 'parents': return '\e025';
			case 'binoculars': return '\e026';
			case 'road': return '\e027';
			case 'search': return '\e028';
			case 'cars': return '\e029';
			case 'notes_2': return '\e030';
			case 'pencil': return '\270F';
			case 'bus': return '\e032';
			case 'wifi_alt': return '\e033';
			case 'luggage': return '\e034';
			case 'old_man': return '\e035';
			case 'woman': return '\e036';
			case 'file': return '\e037';
			case 'coins': return '\e038';
			case 'airplane': return '\2708';
			case 'notes': return '\e040';
			case 'stats': return '\e041';
			case 'charts': return '\e042';
			case 'pie_chart': return '\e043';
			case 'group': return '\e044';
			case 'keys': return '\e045';
			case 'calendar': return '\e046';
			case 'router': return '\e047';
			case 'camera_small': return '\e048';
			case 'dislikes': return '\e049';
			case 'star': return '\e050';
			case 'link': return '\e051';
			case 'eye_open': return '\e052';
			case 'eye_close': return '\e053';
			case 'alarm': return '\e054';
			case 'clock': return '\e055';
			case 'stopwatch': return '\e056';
			case 'projector': return '\e057';
			case 'history': return '\e058';
			case 'truck': return '\e059';
			case 'cargo': return '\e060';
			case 'compass': return '\e061';
			case 'keynote': return '\e062';
			case 'paperclip': return '\e063';
			case 'power': return '\e064';
			case 'lightbulb': return '\e065';
			case 'tag': return '\e066';
			case 'tags': return '\e067';
			case 'cleaning': return '\e068';
			case 'ruller': return '\e069';
			case 'gift': return '\e070';
			case 'umbrella': return '\2602';
			case 'book': return '\e072';
			case 'bookmark': return '\e073';
			case 'wifi': return '\e074';
			case 'cup': return '\e075';
			case 'stroller': return '\e076';
			case 'headphones': return '\e077';
			case 'headset': return '\e078';
			case 'warning_sign': return '\e079';
			case 'signal': return '\e080';
			case 'retweet': return '\e081';
			case 'refresh': return '\e082';
			case 'roundabout': return '\e083';
			case 'random': return '\e084';
			case 'heat': return '\e085';
			case 'repeat': return '\e086';
			case 'display': return '\e087';
			case 'log_book': return '\e088';
			case 'adress_book': return '\e089';
			case 'building': return '\e090';
			case 'eyedropper': return '\e091';
			case 'adjust': return '\e092';
			case 'tint': return '\e093';
			case 'crop': return '\e094';
			case 'vector_path_square': return '\e095';
			case 'vector_path_circle': return '\e096';
			case 'vector_path_polygon': return '\e097';
			case 'vector_path_line': return '\e098';
			case 'vector_path_curve': return '\e099';
			case 'vector_path_all': return '\e100';
			case 'font': return '\e101';
			case 'italic': return '\e102';
			case 'bold': return '\e103';
			case 'text_underline': return '\e104';
			case 'text_strike': return '\e105';
			case 'text_height': return '\e106';
			case 'text_width': return '\e107';
			case 'text_resize': return '\e108';
			case 'left_indent': return '\e109';
			case 'right_indent': return '\e110';
			case 'align_left': return '\e111';
			case 'align_center': return '\e112';
			case 'align_right': return '\e113';
			case 'justify': return '\e114';
			case 'list': return '\e115';
			case 'text_smaller': return '\e116';
			case 'text_bigger': return '\e117';
			case 'embed': return '\e118';
			case 'embed_close': return '\e119';
			case 'table': return '\e120';
			case 'message_full': return '\e121';
			case 'message_empty': return '\e122';
			case 'message_in': return '\e123';
			case 'message_out': return '\e124';
			case 'message_plus': return '\e125';
			case 'message_minus': return '\e126';
			case 'message_ban': return '\e127';
			case 'message_flag': return '\e128';
			case 'message_lock': return '\e129';
			case 'message_new': return '\e130';
			case 'inbox': return '\e131';
			case 'inbox_plus': return '\e132';
			case 'inbox_minus': return '\e133';
			case 'inbox_lock': return '\e134';
			case 'inbox_in': return '\e135';
			case 'inbox_out': return '\e136';
			case 'cogwheel': return '\e137';
			case 'cogwheels': return '\e138';
			case 'picture': return '\e139';
			case 'adjust_alt': return '\e140';
			case 'database_lock': return '\e141';
			case 'database_plus': return '\e142';
			case 'database_minus': return '\e143';
			case 'database_ban': return '\e144';
			case 'folder_open': return '\e145';
			case 'folder_plus': return '\e146';
			case 'folder_minus': return '\e147';
			case 'folder_lock': return '\e148';
			case 'folder_flag': return '\e149';
			case 'folder_new': return '\e150';
			case 'edit': return '\e151';
			case 'new_window': return '\e152';
			case 'check': return '\e153';
			case 'unchecked': return '\e154';
			case 'more_windows': return '\e155';
			case 'show_big_thumbnails': return '\e156';
			case 'show_thumbnails': return '\e157';
			case 'show_thumbnails_with_lines': return '\e158';
			case 'show_lines': return '\e159';
			case 'playlist': return '\e160';
			case 'imac': return '\e161';
			case 'macbook': return '\e162';
			case 'ipad': return '\e163';
			case 'iphone': return '\e164';
			case 'iphone_transfer': return '\e165';
			case 'iphone_exchange': return '\e166';
			case 'ipod': return '\e167';
			case 'ipod_shuffle': return '\e168';
			case 'ear_plugs': return '\e169';
			case 'phone': return '\e170';
			case 'step_backward': return '\e171';
			case 'fast_backward': return '\e172';
			case 'rewind': return '\e173';
			case 'play': return '\e174';
			case 'pause': return '\e175';
			case 'stop': return '\e176';
			case 'forward': return '\e177';
			case 'fast_forward': return '\e178';
			case 'step_forward': return '\e179';
			case 'eject': return '\e180';
			case 'facetime_video': return '\e181';
			case 'download_alt': return '\e182';
			case 'mute': return '\e183';
			case 'volume_down': return '\e184';
			case 'volume_up': return '\e185';
			case 'screenshot': return '\e186';
			case 'move': return '\e187';
			case 'more': return '\e188';
			case 'brightness_reduce': return '\e189';
			case 'brightness_increase': return '\e190';
			case 'circle_plus': return '\e191';
			case 'circle_minus': return '\e192';
			case 'circle_remove': return '\e193';
			case 'circle_ok': return '\e194';
			case 'circle_question_mark': return '\e195';
			case 'circle_info': return '\e196';
			case 'circle_exclamation_mark': return '\e197';
			case 'remove': return '\e198';
			case 'ok': return '\e199';
			case 'ban': return '\e200';
			case 'download': return '\e201';
			case 'upload': return '\e202';
			case 'shopping_cart': return '\e203';
			case 'lock': return '\e204';
			case 'unlock': return '\e205';
			case 'electricity': return '\e206';
			case 'ok_2': return '\e207';
			case 'remove_2': return '\e208';
			case 'cart_out': return '\e209';
			case 'cart_in': return '\e210';
			case 'left_arrow': return '\e211';
			case 'right_arrow': return '\e212';
			case 'down_arrow': return '\e213';
			case 'up_arrow': return '\e214';
			case 'resize_small': return '\e215';
			case 'resize_full': return '\e216';
			case 'circle_arrow_left': return '\e217';
			case 'circle_arrow_right': return '\e218';
			case 'circle_arrow_top': return '\e219';
			case 'circle_arrow_down': return '\e220';
			case 'play_button': return '\e221';
			case 'unshare': return '\e222';
			case 'share': return '\e223';
			case 'chevron-right': return '\e224';
			case 'chevron-left': return '\e225';
			case 'bluetooth': return '\e226';
			case 'euro': return '\20AC';
			case 'usd': return '\e228';
			case 'gbp': return '\e229';
			case 'retweet_2': return '\e230';
			case 'moon': return '\e231';
			case 'sun': return '\2609';
			case 'cloud': return '\2601';
			case 'direction': return '\e234';
			case 'brush': return '\e235';
			case 'pen': return '\e236';
			case 'zoom_in': return '\e237';
			case 'zoom_out': return '\e238';
			case 'pin': return '\e239';
			case 'albums': return '\e240';
			case 'rotation_lock': return '\e241';
			case 'flash': return '\e242';
			case 'google_maps': return '\e243';
			case 'anchor': return '\2693';
			case 'conversation': return '\e245';
			case 'chat': return '\e246';
			case 'male': return '\e247';
			case 'female': return '\e248';
			case 'asterisk': return '\002A';
			case 'divide': return '\00F7';
			case 'snorkel_diving': return '\e251';
			case 'scuba_diving': return '\e252';
			case 'oxygen_bottle': return '\e253';
			case 'fins': return '\e254';
			case 'fishes': return '\e255';
			case 'boat': return '\e256';
			case 'delete': return '\e257';
			case 'sheriffs_star': return '\e258';
			case 'qrcode': return '\e259';
			case 'barcode': return '\e260';
			case 'pool': return '\e261';
			case 'buoy': return '\e262';
			case 'spade': return '\e263';
			case 'bank': return '\e264';
			case 'vcard': return '\e265';
			case 'electrical_plug': return '\e266';
			case 'flag': return '\e267';
			case 'credit_card': return '\e268';
			case 'keyboard-wireless': return '\e269';
			case 'keyboard-wired': return '\e270';
			case 'shield': return '\e271';
			case 'ring': return '\02DA';
			case 'cake': return '\e273';
			case 'drink': return '\e274';
			case 'beer': return '\e275';
			case 'fast_food': return '\e276';
			case 'cutlery': return '\e277';
			case 'pizza': return '\e278';
			case 'birthday_cake': return '\e279';
			case 'tablet': return '\e280';
			case 'settings': return '\e281';
			case 'bullets': return '\e282';
			case 'cardio': return '\e283';
			case 't-shirt': return '\e284';
			case 'pants': return '\e285';
			case 'sweater': return '\e286';
			case 'fabric': return '\e287';
			case 'leather': return '\e288';
			case 'scissors': return '\e289';
			case 'bomb': return '\e290';
			case 'skull': return '\e291';
			case 'celebration': return '\e292';
			case 'tea_kettle': return '\e293';
			case 'french_press': return '\e294';
			case 'coffe_cup': return '\e295';
			case 'pot': return '\e296';
			case 'grater': return '\e297';
			case 'kettle': return '\e298';
			case 'hospital': return '\e299';
			case 'hospital_h': return '\e300';
			case 'microphone': return '\e301';
			case 'webcam': return '\e302';
			case 'temple_christianity_church': return '\e303';
			case 'temple_islam': return '\e304';
			case 'temple_hindu': return '\e305';
			case 'temple_buddhist': return '\e306';
			case 'bicycle': return '\e307';
			case 'life_preserver': return '\e308';
			case 'share_alt': return '\e309';
			case 'comments': return '\e310';
			case 'flower': return '\2698';
			case 'baseball': return '\e312';
			case 'rugby': return '\e313';
			case 'ax': return '\e314';
			case 'table_tennis': return '\e315';
			case 'bowling': return '\e316';
			case 'tree_conifer': return '\e317';
			case 'tree_deciduous': return '\e318';
			case 'more_items': return '\e319';
			case 'sort': return '\e320';
			case 'filter': return '\e321';
			case 'gamepad': return '\e322';
			case 'playing_dices': return '\e323';
			case 'calculator': return '\e324';
			case 'tie': return '\e325';
			case 'wallet': return '\e326';
			case 'piano': return '\e327';
			case 'sampler': return '\e328';
			case 'podium': return '\e329';
			case 'soccer_ball': return '\e330';
			case 'blog': return '\e331';
			case 'dashboard': return '\e332';
			case 'certificate': return '\e333';
			case 'bell': return '\e334';
			case 'candle': return '\e335';
			case 'pushpin': return '\e336';
			case 'iphone_shake': return '\e337';
			case 'pin_flag': return '\e338';
			case 'turtle': return '\e339';
			case 'rabbit': return '\e340';
			case 'globe': return '\e341';
			case 'briefcase': return '\e342';
			case 'hdd': return '\e343';
			case 'thumbs_up': return '\e344';
			case 'thumbs_down': return '\e345';
			case 'hand_right': return '\e346';
			case 'hand_left': return '\e347';
			case 'hand_up': return '\e348';
			case 'hand_down': return '\e349';
			case 'fullscreen': return '\e350';
			case 'shopping_bag': return '\e351';
			case 'book_open': return '\e352';
			case 'nameplate': return '\e353';
			case 'nameplate_alt': return '\e354';
			case 'vases': return '\e355';
			case 'bullhorn': return '\e356';
			case 'dumbbell': return '\e357';
			case 'suitcase': return '\e358';
			case 'file_import': return '\e359';
			case 'file_export': return '\e360';
			case 'bug': return '\e361';
			case 'crown': return '\e362';
			case 'smoking': return '\e363';
			case 'cloud-upload': return '\e364';
			case 'cloud-download': return '\e365';
			case 'restart': return '\e366';
			case 'security_camera': return '\e367';
			case 'expand': return '\e368';
			case 'collapse': return '\e369';
			case 'collapse_top': return '\e370';
			case 'globe_af': return '\e371';
			case 'global': return '\e372';
			case 'spray': return '\e373';
			case 'nails': return '\e374';
			case 'claw_hammer': return '\e375';
			case 'classic_hammer': return '\e376';
			case 'hand_saw': return '\e377';
			case 'riflescope': return '\e378';
			case 'electrical_socket_eu': return '\e379';
			case 'electrical_socket_us': return '\e380';
			case 'pinterest': return '\e381';
			case 'dropbox': return '\e382';
			case 'google_plus': return '\e383';
			case 'jolicloud': return '\e384';
			case 'yahoo': return '\e385';
			case 'blogger': return '\e386';
			case 'picasa': return '\e387';
			case 'amazon': return '\e388';
			case 'tumblr': return '\e389';
			case 'wordpress': return '\e390';
			case 'instapaper': return '\e391';
			case 'evernote': return '\e392';
			case 'xing': return '\e393';
			case 'zootool': return '\e394';
			case 'dribbble': return '\e395';
			case 'deviantart': return '\e396';
			case 'read_it_later': return '\e397';
			case 'linked_in': return '\e398';
			case 'forrst': return '\e399';
			case 'pinboard': return '\e400';
			case 'behance': return '\e401';
			case 'github': return '\e402';
			case 'youtube': return '\e403';
			case 'skitch': return '\e404';
			case 'foursquare': return '\e405';
			case 'quora': return '\e406';
			case 'badoo': return '\e407';
			case 'spotify': return '\e408';
			case 'stumbleupon': return '\e409';
			case 'readability': return '\e410';
			case 'facebook': return '\e411';
			case 'twitter': return '\e412';
			case 'instagram': return '\e413';
			case 'posterous_spaces': return '\e414';
			case 'vimeo': return '\e415';
			case 'flickr': return '\e416';
			case 'last_fm': return '\e417';
			case 'rss': return '\e418';
			case 'skype': return '\e419';
			case 'e-mail': return '\e420';

		}
	}


	/**
	 * Output icons in the demo grid
	 *
	 * @return void
	 */
	public function icon_demo() {
		$icons = array(
			'glass',
			'leaf',
			'dog',
			'user',
			'girl',
			'car',
			'user_add',
			'user_remove',
			'film',
			'magic',
			'envelope',
			'camera',
			'heart',
			'beach_umbrella',
			'train',
			'print',
			'bin',
			'music',
			'note',
			'heart_empty',
			'home',
			'snowflake',
			'fire',
			'magnet',
			'parents',
			'binoculars',
			'road',
			'search',
			'cars',
			'notes_2',
			'pencil',
			'bus',
			'wifi_alt',
			'luggage',
			'old_man',
			'woman',
			'file',
			'coins',
			'airplane',
			'notes',
			'stats',
			'charts',
			'pie_chart',
			'group',
			'keys',
			'calendar',
			'router',
			'camera_small',
			'dislikes',
			'star',
			'link',
			'eye_open',
			'eye_close',
			'alarm',
			'clock',
			'stopwatch',
			'projector',
			'history',
			'truck',
			'cargo',
			'compass',
			'keynote',
			'paperclip',
			'power',
			'lightbulb',
			'tag',
			'tags',
			'cleaning',
			'ruller',
			'gift',
			'umbrella',
			'book',
			'bookmark',
			'wifi',
			'cup',
			'stroller',
			'headphones',
			'headset',
			'warning_sign',
			'signal',
			'retweet',
			'refresh',
			'roundabout',
			'random',
			'heat',
			'repeat',
			'display',
			'log_book',
			'adress_book',
			'building',
			'eyedropper',
			'adjust',
			'tint',
			'crop',
			'vector_path_square',
			'vector_path_circle',
			'vector_path_polygon',
			'vector_path_line',
			'vector_path_curve',
			'vector_path_all',
			'font',
			'italic',
			'bold',
			'text_underline',
			'text_strike',
			'text_height',
			'text_width',
			'text_resize',
			'left_indent',
			'right_indent',
			'align_left',
			'align_center',
			'align_right',
			'justify',
			'list',
			'text_smaller',
			'text_bigger',
			'embed',
			'embed_close',
			'table',
			'message_full',
			'message_empty',
			'message_in',
			'message_out',
			'message_plus',
			'message_minus',
			'message_ban',
			'message_flag',
			'message_lock',
			'message_new',
			'inbox',
			'inbox_plus',
			'inbox_minus',
			'inbox_lock',
			'inbox_in',
			'inbox_out',
			'cogwheel',
			'cogwheels',
			'picture',
			'adjust_alt',
			'database_lock',
			'database_plus',
			'database_minus',
			'database_ban',
			'folder_open',
			'folder_plus',
			'folder_minus',
			'folder_lock',
			'folder_flag',
			'folder_new',
			'edit',
			'new_window',
			'check',
			'unchecked',
			'more_windows',
			'show_big_thumbnails',
			'show_thumbnails',
			'show_thumbnails_with_lines',
			'show_lines',
			'playlist',
			'imac',
			'macbook',
			'ipad',
			'iphone',
			'iphone_transfer',
			'iphone_exchange',
			'ipod',
			'ipod_shuffle',
			'ear_plugs',
			'phone',
			'step_backward',
			'fast_backward',
			'rewind',
			'play',
			'pause',
			'stop',
			'forward',
			'fast_forward',
			'step_forward',
			'eject',
			'facetime_video',
			'download_alt',
			'mute',
			'volume_down',
			'volume_up',
			'screenshot',
			'move',
			'more',
			'brightness_reduce',
			'brightness_increase',
			'circle_plus',
			'circle_minus',
			'circle_remove',
			'circle_ok',
			'circle_question_mark',
			'circle_info',
			'circle_exclamation_mark',
			'remove',
			'ok',
			'ban',
			'download',
			'upload',
			'shopping_cart',
			'lock',
			'unlock',
			'electricity',
			'ok_2',
			'remove_2',
			'cart_out',
			'cart_in',
			'left_arrow',
			'right_arrow',
			'down_arrow',
			'up_arrow',
			'resize_small',
			'resize_full',
			'circle_arrow_left',
			'circle_arrow_right',
			'circle_arrow_top',
			'circle_arrow_down',
			'play_button',
			'unshare',
			'share',
			'chevron-right',
			'chevron-left',
			'bluetooth',
			'euro',
			'usd',
			'gbp',
			'retweet_2',
			'moon',
			'sun',
			'cloud',
			'direction',
			'brush',
			'pen',
			'zoom_in',
			'zoom_out',
			'pin',
			'albums',
			'rotation_lock',
			'flash',
			'google_maps',
			'anchor',
			'conversation',
			'chat',
			'male',
			'female',
			'asterisk',
			'divide',
			'snorkel_diving',
			'scuba_diving',
			'oxygen_bottle',
			'fins',
			'fishes',
			'boat',
			'delete',
			'sheriffs_star',
			'qrcode',
			'barcode',
			'pool',
			'buoy',
			'spade',
			'bank',
			'vcard',
			'electrical_plug',
			'flag',
			'credit_card',
			'keyboard-wireless',
			'keyboard-wired',
			'shield',
			'ring',
			'cake',
			'drink',
			'beer',
			'fast_food',
			'cutlery',
			'pizza',
			'birthday_cake',
			'tablet',
			'settings',
			'bullets',
			'cardio',
			't-shirt',
			'pants',
			'sweater',
			'fabric',
			'leather',
			'scissors',
			'bomb',
			'skull',
			'celebration',
			'tea_kettle',
			'french_press',
			'coffe_cup',
			'pot',
			'grater',
			'kettle',
			'hospital',
			'hospital_h',
			'microphone',
			'webcam',
			'temple_christianity_church',
			'temple_islam',
			'temple_hindu',
			'temple_buddhist',
			'bicycle',
			'life_preserver',
			'share_alt',
			'comments',
			'flower',
			'baseball',
			'rugby',
			'ax',
			'table_tennis',
			'bowling',
			'tree_conifer',
			'tree_deciduous',
			'more_items',
			'sort',
			'filter',
			'gamepad',
			'playing_dices',
			'calculator',
			'tie',
			'wallet',
			'piano',
			'sampler',
			'podium',
			'soccer_ball',
			'blog',
			'dashboard',
			'certificate',
			'bell',
			'candle',
			'pushpin',
			'iphone_shake',
			'pin_flag',
			'turtle',
			'rabbit',
			'globe',
			'briefcase',
			'hdd',
			'thumbs_up',
			'thumbs_down',
			'hand_right',
			'hand_left',
			'hand_up',
			'hand_down',
			'fullscreen',
			'shopping_bag',
			'book_open',
			'nameplate',
			'nameplate_alt',
			'vases',
			'bullhorn',
			'dumbbell',
			'suitcase',
			'file_import',
			'file_export',
			'bug',
			'crown',
			'smoking',
			'cloud-upload',
			'cloud-download',
			'restart',
			'security_camera',
			'expand',
			'collapse',
			'collapse_top',
			'globe_af',
			'global',
			'spray',
			'nails',
			'claw_hammer',
			'classic_hammer',
			'hand_saw',
			'riflescope',
			'electrical_socket_eu',
			'electrical_socket_us',
			'pinterest',
			'dropbox',
			'google_plus',
			'jolicloud',
			'yahoo',
			'blogger',
			'picasa',
			'amazon',
			'tumblr',
			'wordpress',
			'instapaper',
			'evernote',
			'xing',
			'zootool',
			'dribbble',
			'deviantart',
			'read_it_later',
			'linked_in',
			'forrst',
			'pinboard',
			'behance',
			'github',
			'youtube',
			'skitch',
			'foursquare',
			'quora',
			'badoo',
			'spotify',
			'stumbleupon',
			'readability',
			'facebook',
			'twitter',
			'instagram',
			'posterous_spaces',
			'vimeo',
			'flickr',
			'last_fm',
			'rss',
			'skype',
			'e-mail',
		);
		?>
		<style type="text/css">
			@font-face { font-family: 'Glyphicons'; src: url('<?php echo $this->plugin_url ?>/font/glyphicons-regular.eot'); src: url('<?php echo $this->plugin_url ?>/font/glyphicons-regular.eot?#iefix') format('embedded-opentype'), url('<?php echo $this->plugin_url ?>/font/glyphicons-regular.woff') format('woff'), url('<?php echo $this->plugin_url ?>/font/glyphicons-regular.ttf') format('truetype'), url('<?php echo $this->plugin_url ?>/font/glyphicons-regular.svg#glyphicons_halflingsregular') format('svg'); font-weight: normal; font-style: normal; }
			#glyphicons_icons dt:before { font-family: Glyphicons !important; -webkit-font-smoothing: antialiased; *margin-right: .3em; }
			<?php foreach ( $icons as $icon ) : ?>
			.glyphicons-icon-<?php echo $icon ?>:before { content: '<?php echo $this->get_glyphicons_icon( $icon ) ?>'; }
			<?php endforeach ?>
		</style>
		<h2 style="clear:both">Glyphicons Library</h2>
		<div id="glyphicons_icons">
			<?php foreach ( $icons as $icon ) : ?>
				<dl><dt class="glyphicons-icon-<?php echo $icon ?>"></dt><dd><?php echo $icon ?></dd></dl>
			<?php endforeach ?>
		</div>
		<?php
	}

}

function SCPT_Glyphicons() {
	return SCPT_Glyphicons::instance();
}
SCPT_Glyphicons();

endif;