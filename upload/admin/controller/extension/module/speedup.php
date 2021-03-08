<?php
class ControllerExtensionModuleSpeedup extends Controller {
	private $error = array();
	public $i = 0;
	private $permission = array(
		'common/folder_manager',
	);
	function __construct($registry){
		parent::__construct($registry);
		$this->load->model('user/user_group');
	}
	public function index(){
        $data = $this->language->load('extension/module/speedup_cache');
        require_once(DIR_SYSTEM . 'library/speedup_cache.php');
        $pagecache = new SpeedUpCache();
        $vals=$pagecache->Settings();
        foreach (array_keys($vals) as $key) {
            if ($vals[$key] === true) {
                $vals[$key]='true';
            }
            if ($vals[$key] === false) {
                $vals[$key]='false';
            }
            $data[$key]=$vals[$key];
        }
		$this->load->language('extension/module/speedup');
		$title = strip_tags($this->language->get('heading_title'));
		$this->document->setTitle($title);
		$editt = array("".DIR_APPLICATION."../config.php","".DIR_APPLICATION."../.htaccess","".DIR_APPLICATION."../index.php","".DIR_APPLICATION."config.php");	
		$data['pageerror'] = array();
		foreach ($editt as $value) {
			if (!is_writable($value)) {
			   	$data['pageerror'][] =  'The file is not writable '.str_replace("admin/../","",$value);
			}		
		}
		$this->document->addScript('view/javascript/jquery/imagecrusher/imagecrusher.js');

		$this->load->model('setting/setting');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if(empty($this->request->post['module_speedup_cache_expire_time'])){
				$this->request->post['module_speedup_cache_expire_time'] = 14400;
			}

			$speedup_const = DIR_SYSTEM.'library/minify/speedup_const.php';
			$content = '<?php'.PHP_EOL;
			if(isset($this->request->post['module_speedup_compression'])){
				$compressionLevel = $this->request->post['module_speedup_compression'];
				$content .= "define('CACHE_COMPRESSION', {$compressionLevel});".PHP_EOL;
			}
			if(isset($this->request->post['module_speedup_cache_expire_time'])){
				$expiretime = $this->request->post['module_speedup_cache_expire_time'];
				$content .= "define('CACHE_EXPIRE', {$expiretime});".PHP_EOL;
			}
			$language = $this->config->get('config_language');
			$currency = $this->config->get('config_currency');
			$content .= "define('SP_language', '{$language}');".PHP_EOL;
			$content .= "define('SP_currency', '{$currency}');".PHP_EOL;

			file_put_contents($speedup_const, $content);

			if($this->request->post['module_speedup_status']){
				if(file_exists(DIR_APPLICATION."../vqmod/xml/Speed_UP.xml_")){
					rename(DIR_APPLICATION."../vqmod/xml/Speed_UP.xml_",DIR_APPLICATION."../vqmod/xml/Speed_UP.xml");
				}
			}else{
				rename(DIR_APPLICATION."../vqmod/xml/Speed_UP.xml",DIR_APPLICATION."../vqmod/xml/Speed_UP.xml_");
			}
			$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '".(int)$this->request->post['module_speedup_product_count']."' WHERE `key` = 'config_product_count'");
			$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '".(int)$this->request->post['module_speedup_compression']."' WHERE `key` = 'config_compression'");
			$timezone = $this->request->post['module_speedup_timezone'];
                date_default_timezone_set($timezone);
                $now = new DateTime();
                $mins = $now->getOffset() / 60;
                $sgn = ($mins < 0 ? -1 : 1);
                $mins = abs($mins);
                $hrs = floor($mins / 60);
                $mins -= $hrs * 60;
                $offset = sprintf('%+d:%02d', $hrs * $sgn, $mins);
			$this->request->post['module_speedup_database_time_zone'] = $offset;

			$this->model_setting_setting->editSetting('module_speedup', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			if(isset($this->request->get['stay'])){
				$this->response->redirect($this->url->link('extension/module/speedup', 'user_token=' . $this->session->data['user_token'], true));
			}else{
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
			}
		}

		if ($this->config->get('module_speedup_cache_expire_time')) {
			$data['module_speedup_cache_expire_time'] = $this->config->get('module_speedup_cache_expire_time');
		} else {
			$data['module_speedup_cache_expire_time'] =  14400;
		}

		$data['success'] = '';
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}

		// Speed Up
		$data['text_on'] = $this->language->get('text_on');
		$data['text_off'] = $this->language->get('text_off');
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_compress'] = $this->language->get('tab_compress');
		$data['tab_image'] = $this->language->get('tab_image');
		$data['tab_database'] = $this->language->get('tab_database');
		$data['tab_speed_test'] = $this->language->get('tab_speed_test');
		$data['tab_cache'] = $this->language->get('tab_cache');
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');	

		// Compress
		$data['image_crusher_title'] = $this->language->get('image_crusher_title');
		$data['image_crusher_intro'] = $this->language->get('image_crusher_intro');
		$data['image_crusher_image_manager_text'] = $this->language->get('image_crusher_image_manager_text');
		$data['image_crusher_excluded_filetypes'] = $this->language->get('image_crusher_excluded_filetypes');
		$data['image_crusher_switch_title'] = $this->language->get('image_crusher_switch_title');
		$data['image_crusher_switch_text'] = $this->language->get('image_crusher_switch_text');
		$data['image_crusher_switch_on'] = $this->language->get('image_crusher_switch_on');
		$data['image_crusher_switch_off'] = $this->language->get('image_crusher_switch_off');
		$data['image_crusher_compression_level_title'] = $this->language->get('image_crusher_compression_level_title');
		$data['image_crusher_compression_level_text'] = $this->language->get('image_crusher_compression_level_text');
		$data['image_crusher_compression_level_label'] = $this->language->get('image_crusher_compression_level_label');
		$data['image_crusher_default_compression_level'] = $this->language->get('image_crusher_default_compression_level');
		$data['image_crusher_existing_image_compression_level'] = $this->language->get('image_crusher_existing_image_compression_level');
		$data['image_crusher_existing_images_title'] = $this->language->get('image_crusher_existing_images_title');
		$data['image_crusher_existing_images_text'] = $this->language->get('image_crusher_existing_images_text');
		$data['image_crusher_existing_images_warning_title'] = $this->language->get('image_crusher_existing_images_warning_title');
		$data['image_crusher_existing_images_warning_text'] = $this->language->get('image_crusher_existing_images_warning_text');
		$data['image_crusher_existing_images_image_folder_text'] = $this->language->get('image_crusher_existing_images_image_folder_text');	
		$data['image_crusher_existing_images_image_folder_placeholder_text'] = $this->language->get('image_crusher_existing_images_image_folder_placeholder_text');
		$data['image_crusher_existing_images_submit_button'] = $this->language->get('image_crusher_existing_images_submit_button');
		$data['image_crusher_existing_images_popup_text_1'] = $this->language->get('image_crusher_existing_images_popup_text_1');
		$data['image_crusher_existing_images_popup_text_2'] = $this->language->get('image_crusher_existing_images_popup_text_2');

		// Speed Test
		$data['text_api_key'] = $this->language->get('text_api_key');
		$data['text_url'] = $this->language->get('text_url');
		$data['text_filter_third_party_resource'] = $this->language->get('text_filter_third_party_resource');
		$data['text_locale'] = $this->language->get('text_locale');
		$data['text_rule'] = $this->language->get('text_rule');
		$data['text_screenshot'] = $this->language->get('text_screenshot');
		$data['text_strategy'] = $this->language->get('text_strategy');
		$data['text_fields'] = $this->language->get('text_fields');
		$data['text_true'] = $this->language->get('text_true');
		$data['text_false'] = $this->language->get('text_false');
		$data['text_desktop'] = $this->language->get('text_desktop');
		$data['text_mobile'] = $this->language->get('text_mobile');
		$data['text_result_text'] = $this->language->get('text_result_text');
		$data['note_api_key'] = $this->language->get('note_api_key');
		$data['note_url'] = $this->language->get('note_url');
		$data['note_filter_third_party_resource'] = $this->language->get('note_filter_third_party_resource');
		$data['note_locale'] = $this->language->get('note_locale');
		$data['note_rule'] = $this->language->get('note_rule');
		$data['note_screenshot'] = $this->language->get('note_screenshot');
		$data['note_strategy'] = $this->language->get('note_strategy');
		$data['note_fields'] = $this->language->get('note_fields');
		$data['btn_execute'] = $this->language->get('btn_execute');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		//get the user user_token to pass to the tpl.
		$data['user_token'] = $this->session->data['user_token'];

		if ($this->config->get('module_speedup_image_optimise_on') === 'on') {
			$data['image_crusher_switch_setting_on'] = 'selected="selected"';
			$data['image_crusher_switch_setting_off'] = '';
		} else {
			$data['image_crusher_switch_setting_on'] = '';
			$data['image_crusher_switch_setting_off'] = 'selected="selected"';
		} 
		if ($this->config->get('module_speedup_existing_image_compression_level')) {
			$data['image_crusher_existing_image_compression_level'] = $this->config->get('module_speedup_existing_image_compression_level');
		} else {
			$data['image_crusher_existing_image_compression_level'] =  $this->language->get('image_crusher_existing_image_compression_level');
		}
		if ($this->config->get('module_speedup_compression_level')) {
			$data['image_crusher_default_compression_level'] = $this->config->get('module_speedup_compression_level');
		} else {
			$data['image_crusher_default_compression_level'] =  $this->language->get('image_crusher_default_compression_level');
		}
		if (isset($this->request->post['module_speedup_timezone'])) {
			$data['speedup_timezone'] = $this->request->post['module_speedup_timezone'];
		} else {
			$data['speedup_timezone'] = $this->config->get('module_speedup_timezone');
		}
		$data['timezones'] = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

		$lan = array('heading_title','text_edit','text_enabled','text_disabled','button_save_stay','button_save_exit','entry_status','entry_minify_css','entry_minify_js','entry_minify_html','entry_image_dimensions','entry_clear_cache','entry_clear_cache_tip','entry_optimize_table','entry_compress_css_js','entry_image_lazyload','entry_url_alias_cache','entry_product_count','entry_default_time_zone','entry_compression','entry_defer','entry_notfound_page','button_save','button_cancel','entry_db_cache','entry_page_cache_expire_time','entry_page_cache','entry_clear_db_cache','entry_clear_page_cache');
		foreach ($lan as $v) $data[$v] = $this->language->get($v);
		$data['entry_optimize_table'] = strip_tags($data['entry_optimize_table']);
		$data['error_warning'] = '';
		if (isset($this->error['warning'])) $data['error_warning'] = $this->error['warning'];
		
		$data['error_api_key'] = '';
		if (isset($this->error['api_key'])) $data['error_api_key'] = $this->error['api_key'];

		$data['error_url'] = '';
		if (isset($this->error['url'])) $data['error_url'] = $this->error['url'];

		$data['breadcrumbs'] = array(array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		),$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		),$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/speedup', 'user_token=' . $this->session->data['user_token'], true)
		));

		//$data['cssjs'] = $this->displaysub($this->getAllCssJs());
		$data['action'] = $this->url->link('extension/module/speedup', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		$data['stay_action'] = $this->url->link('extension/module/speedup', 'user_token=' . $this->session->data['user_token'].'&stay=1', true);
		$data['clear_cache'] = html_entity_decode($this->url->link('extension/module/speedup/clear_cache', 'user_token=' . $this->session->data['user_token'], true));
		$data['optimize_table'] = html_entity_decode($this->url->link('extension/module/speedup/optimize_table', 'user_token=' . $this->session->data['user_token'], true));
		$data['compress_css_js'] = html_entity_decode($this->url->link('extension/module/speedup/compress_css_js', 'user_token=' . $this->session->data['user_token'], true));

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		$data['speedup_status'] = 0;
		$data['speedup_defer'] = $this->config->get('module_speedup_defer');

		$filed = array('module_speedup_status','module_speedup_notfound_page','module_speedup_minify_css','module_speedup_minify_js','module_speedup_minify_html','module_speedup_image_lazyload','module_speedup_image_dimensions','module_speedup_url_alias_cache','module_speedup_compression_level','module_speedup_compression','module_speedup_defer','module_speedup_existing_image_compression_level','module_speedup_db_cache','module_speedup_page_cache');
		if ($this->request->server['REQUEST_METHOD'] == 'POST')
			foreach ($filed as $v) $data[$v] = $this->request->post[$v];
		else foreach ($filed as $v) $data[$v] = $this->config->get($v);

		if (isset($this->request->post['module_speedup_test_api_key'])) {
			$data['speedup_test_api_key'] = $this->request->post['module_speedup_test_api_key'];
		} else {
			$data['speedup_test_api_key'] = $this->config->get('module_speedup_test_api_key');
		}

		if (isset($this->request->post['module_speedup_test_url'])) {
			$data['speedup_test_url'] = $this->request->post['module_speedup_test_url'];
		} else {
			$data['speedup_test_url'] = $this->config->get('module_speedup_test_url');
		}

		if (isset($this->request->post['module_speedup_test_resources'])) {
			$data['speedup_test_resources'] = $this->request->post['module_speedup_test_resources'];
		} else {
			$data['speedup_test_resources'] = $this->config->get('module_speedup_test_resources');
		}
		
		if (isset($this->request->post['module_speedup_test_screenshot'])) {
			$data['speedup_test_screenshot'] = $this->request->post['module_speedup_test_screenshot'];
		} else {
			$data['speedup_test_screenshot'] = $this->config->get('module_speedup_test_screenshot');
		}

		if (isset($this->request->post['module_speedup_test_strategy'])) {
			$data['speedup_test_strategy'] = $this->request->post['module_speedup_test_strategy'];
		} else {
			$data['speedup_test_strategy'] = $this->config->get('module_speedup_test_strategy');
		}

		$data['speedup_test_fields'] = 'formattedResults,id,invalidRules,kind,pageStats,responseCode,ruleGroups,screenshot,title,version';
		//$data['compatstatus']=$this->compatstatus();
		$data['timezonee']=date_default_timezone_get();
		$data['module_speedup_product_count'] = $this->config->get('config_product_count');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/speedup', $data));
	}
	public function clear_cache() { 
		if($this->request->post['action']){
			$action = $this->request->post['action'];
			if($action == 'CSS_JS'){
				$path =  realpath(dirname(DIR_APPLICATION))."/cache/*";
				$files = glob($path);
				foreach ($files as $f) unlink($f);
			}
			if($action == 'DATABASE'){
				$path =  realpath(DIR_SYSTEM)."/speedup_cache/db_cache/*";
				$files = glob($path);
				foreach ($files as $f) unlink($f);
			}
		echo "success";
		die;
		}
	}

	public function optimize_table(){
		$result = $this->db->query('show tables');
		$data['tables'] = array();
		foreach ($result->rows as $row) {
			$key = $row[key($row)];
			if(false !== strpos($key,DB_PREFIX)) $data['tables'][ $key ] = null;
		}
		foreach ($data['tables'] as $table => $value) {
			$result = $this->db->query('optimize table '. $table);	
			$data['tables'][$table] = $result->rows[0]['Msg_text'];
		}
		header("Content-Type: application/json; charset=UTF-8");
		echo json_encode($data); 
		die;
	}
	public function compress_css_js(){ 
		$files = $this->request->post['compressfile'];
		$json['files'] = array();
		foreach ($files as $file) {
			$speedup = new speedup($this->registry); 
            $this->registry->set('speedup', $speedup);
			$success = $this->speedup->compresscssjs($file);
			if($success) $json['files'][] = "<span style='color:green;'>".$file." was successfully compressed!</span><br>";
		    else $json['files'][] = "<span style='color:red;'>$file cannot be compressed.</span><br>";
		}
		header("Content-Type: application/json; charset=UTF-8");
		echo json_encode($json); 
		die;
	}
	public function imagescompress(){
		error_reporting(0);
		ini_set('max_execution_time',28000);
		set_time_limit(28000);
		ini_set('memory_limit', -1);
		$json = array();
		$folder = $this->request->post['imageFolder'];
		$folder = DIR_IMAGE . $folder;
		$realpath = realpath($folder);
		if (!file_exists($realpath)) $json['error'] = "<span class='red'><strong>" . $folder . "</strong> is not a valid directory name. Please check it exists!</span>";		

		$images = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($realpath));
		$quality = $this->request->post['existingImageSlider'];
		foreach($images as $image){
			if(!is_file($image)) continue;
		    $imagerealpath = realpath($image); 
		    $success = $this->resizeImage($imagerealpath,$imagerealpath,$quality);
		    //$success = $this->compressImg($image,$imagerealpath,$quality);
		    if($success) $json['files'][] = "<span style='color:green;'>".$imagerealpath." was successfully compressed!</span>";
		    else $json['files'][] = "<span style='color:red;'>$image cannot be compressed.</span>";
		}
		header("Content-Type: application/json; charset=UTF-8");
		echo json_encode($json);
		die;
	}
	public function resizeImage($SrcImage,$DestImage,$Quality){
       list($iWidth,$iHeight,$type) = @getimagesize($SrcImage);
       $NewCanves = imagecreatetruecolor($iWidth, $iHeight);
       $mine_type = strtolower(image_type_to_mime_type($type));
       switch($mine_type){
           case 'image/jpeg':
               $NewImage = imagecreatefromjpeg($SrcImage);
               $ImageQuality = $this->getCompressionLevel($Quality);
               break;
           case 'image/png':
               $NewImage = imagecreatefrompng($SrcImage);
               $ImageQuality = $this->getCompressionLevelForPng($Quality);
               break;
           case 'image/gif':
               $NewImage = imagecreatefromgif($SrcImage);
               $ImageQuality = $this->getCompressionLevel($Quality);
               break;
           default:
               return false;
       }
       imagealphablending($NewCanves, false);
       imagesavealpha($NewCanves, true);
       if(imagecopyresampled($NewCanves, $NewImage,0, 0, 0, 0, $iWidth, $iHeight, $iWidth, $iHeight)){
           if($mine_type == 'image/png' && is_callable('imagepng') && imagepng($NewCanves,$DestImage,$ImageQuality,PNG_ALL_FILTERS)){
               imagedestroy($NewCanves);
               return true;
           }else if(imagejpeg($NewCanves,$DestImage,$ImageQuality)){
               imagedestroy($NewCanves);
               return true;
           }
       }
       return false;
   	}
	public function compressPng($originalImage, $newImageName, $quality) {
		$imageSize = getimagesize($originalImage);
		$imageWidth = $imageSize[0];
		$imageHeight = $imageSize[1];
		$sourceImage = imagecreatefrompng($originalImage);
		$destination = imagecreatetruecolor($imageWidth, $imageHeight);
		$palette = (imagecolortransparent($sourceImage)<0);
		$pngQuality = 9;
		if (!$palette || (ord(file_get_contents ($originalImage, false, null, 25, 1)) & 4)) {
			if(($transparentColour = imagecolorstotal($sourceImage)) && $transparentColour<=256)
				imagetruecolortopalette($destination, false, $transparentColour);
				imagealphablending($destination, false);
				$alpha = imagecolorallocatealpha($destination, 0, 0, 0, 127);
				imagefill($destination, 0, 0, $alpha);
				imagesavealpha($destination, true);
		}
		imagecopyresampled($destination, $sourceImage, 0, 0, 0, 0, $imageWidth, $imageHeight, $imageSize[0], $imageSize[1]);
		if ((ord(file_get_contents ($originalImage, false, null, 25, 1)) & 4)) {
			$dx = min(max(floor($imageWidth/50), 1), 10);
			$dy = min(max(floor($imageHeight/50), 1), 10);

			$palette = true;
			for($x = 0; $x < $imageWidth; $x = $x + $dx) {
				for($y = 0; $y < $imageHeight; $y = $y + $dy) {
					$col = imagecolorsforindex($destination, imagecolorat($destination,$x,$y));
					if ($col['alpha'] > 13) {
						$palette = false;
						break 2;
					}
				}
			}
		}
		if ($palette) {
			imagetruecolortopalette($destination, false, 256);
			$pngQuality = $this->getCompressionLevelForPng($quality);
		}
		return imagepng($destination, $newImageName, $pngQuality, PNG_ALL_FILTERS);
	}
	public function getCompressionLevel($qualitySetting) {
		$compressionLevel = intval($qualitySetting);
		$quality_array = array(1=>96,2=>94,3=>88,4=>82,5=>76,6=>70,7=>64,8=>58,9=>52,10=>46);
		return (isset($quality_array[$compressionLevel]) ? $quality_array[$compressionLevel] : 70);
	}
	public function getCompressionLevelForPng($qualitySetting){
		$compressionLevel = intval($qualitySetting);
	    $quality_array = array(1=>8,2=>8,3=>7,4=>7,5=>6,6=>5,7=>4,8=>4,9=>3,10=>2);
	    return (isset($quality_array[$compressionLevel]) ? $quality_array[$compressionLevel] : 3);
	}
	public function install() {
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', $this->permission[0]);
   		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', $this->permission[0]);

		$data = array(
			'module_speedup_compression'		=>	9,
			'module_speedup_cache_expire_time'	=>	14400,
		);
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('module_speedup', $data);
		
		$speedup_const = DIR_SYSTEM."library/minify/speedup_const.php";
		$language 		= $this->config->get('config_language');
		$currency 		= $this->config->get('config_currency');
		file_put_contents($speedup_const, "<?php define('SP_language' ,'{$language}');define('SP_currency', '{$currency}');define('CACHE_COMPRESSION', {$data['module_speedup_compression']});define('CACHE_EXPIRE', {$data['module_speedup_cache_expire_time']});");



		$catalog_config = str_replace('admin/', 'config.php', DIR_APPLICATION);
		$content = trim(file_get_contents($catalog_config));
		$replace = $content . PHP_EOL."require_once('".$speedup_const."');";
		file_put_contents($catalog_config, $replace);

		$admin_config = DIR_APPLICATION .'config.php';
		$content2 = trim(file_get_contents($admin_config));
		$replace2 = $content2 . PHP_EOL."require_once('".$speedup_const."');";
		file_put_contents($admin_config, $replace2);
		
		$db = DB_DATABASE;
		$pre = DB_PREFIX;
		$index = array( 
			'api' => array('api_id'),
			'affiliate' => array('email'),
			'affiliate_activity' => array('affiliate_id'),
			'affiliate_login' => array('email','ip'),
			'affiliate_transaction' => array('order_id','affiliate_id'),
			'api_ip' => array('api_id','ip'),
			'api_session' => array('api_id','ip'),
			'attribute' => array('attribute_group_id'),
			'attribute_description' => array('language_id'),
			'attribute_group_description' => array('language_id'),
			'banner_imaged' => array('banner_id'),
			'banner_image_descriptiond' => array('language_id','banner_id'),
			'cartd' => array('product_id'),
			'category' => array('parent_id'),
			'category_descriptiond' => array('language_id','name'),
			'category_to_layoutd' => array('layout_id'),
			'cod_request' => array('customer_id','transaction_id'),
			'country' => array('iso_code_2'),
			'coupon' => array('name'),
			'coupond' => array('code'),
			'coupon_history' => array('coupon_id','order_id','customer_id'),
			'coupon_product' => array('coupon_id','product_id'),
			'currency' => array('code'),
			'customer' => array('customer_group_id','store_id','email','address_id'),
			'customer_activity' => array('customer_id','ip'),
			'customer_group_description' => array('name','language_id'),
			'customer_history' => array('customer_id'),
			'customer_ip' => array('customer_id','ip'),
			'customer_login' => array('email','ip'),
			'customer_online' => array('customer_id'),
			'customer_reward' => array('customer_id','order_id'),
			'customer_transaction' => array('customer_id'),
			'customer_transactiond' => array('order_id'),
			'customer_wishlist' => array('product_id'),
			'custom_field_customer_group' => array('customer_group_id'),
			'custom_field_description' => array('language_id'),
			'custom_field_value' => array('custom_field_id'),
			'custom_field_value_description' => array('language_id','custom_field_id'),
			'download_description' => array('language_id'),
			'event' => array('code'),
			'extension' => array('type','code'),
			'filter' => array('filter_group_id'),
			'filter_group_description' => array('language_id'),
			'geo_zone' => array('name'),
			'information_description' => array('language_id','title'),
			'information_to_layout' => array('store_id','layout_id'),
			'information_to_store' => array('store_id'),
			'language' => array('name','code'),
			'layout' => array('name'),
			'layout_module' => array('layout_id','code','position'),
			'layout_route' => array('layout_id','store_id','route'),
			'length_class_description' => array('language_id'),
			'manufacturer' => array('name'),
			'manufacturer_to_store' => array('store_id'),
			'marketing' => array('name','code'),
			'modification' => array('name','code'),
			'module' => array('name','code'),
			'option' => array('type'),
			'option_description' => array('language_id','name'),
			'option_value' => array('option_id'),
			'option_value_description' => array('language_id','option_id','name'),
			'order' => array('store_id','invoice_no','customer_id','email','payment_method','order_status_id','affiliate_id','language_id','ip'),
			'orderd' => array('customer_group_id'),
			'order_custom_field' => array('order_id','custom_field_id','custom_field_value_id'),
			'order_fraud' => array('customer_id'),
			'order_history' => array('order_id','order_status_id'),
			'order_option' => array('order_id','order_product_id','product_option_id','product_option_value_id','name'),
			'order_product' => array('order_id','product_id','name','model'),
			'order_recurring' => array('order_id','product_id'),
			'order_recurring_transaction' => array('order_recurring_id'),
			'order_status' => array('language_id','name'),
			'order_total' => array('order_id','code'),
			'order_voucher' => array('order_id','voucher_id','code'),
			'order_voucherd' => array('to_email'),
			'productd' => array('model','sku','quantity','stock_status_id','manufacturer_id','price','tax_class_id','weight_class_id','length_class_id'),
			'product_attributed' => array('attribute_id','language_id'),
			'product_descriptiond' => array('language_id','name'),
			'product_discountd' => array('product_id','customer_group_id'),
			'product_filter' => array('filter_id'),
			'product_image' => array('product_id'),
			'product_option' => array('product_id','option_id'),
			'product_option_value' => array('product_option_id','product_id','option_id','option_value_id'),
			'product_related' => array('related_id'),
			'product_reward' => array('product_id','customer_group_id'),
			'product_special' => array('product_id','customer_group_id'),
			'product_to_category' => array('category_id'),
			'product_to_download' => array('download_id'),
			'product_to_layout' => array('store_id','layout_id'),
			'product_to_store' => array('store_id'),
			'return' => array('order_id','product_id','customer_id','email','model'),
			'return_action' => array('language_id','name'),
			'return_history' => array('return_id','return_status_id'),
			'return_reason' => array('language_id','name'),
			'return_status' => array('language_id','name'),
			'review' => array('product_id','customer_id'),
			'setting' => array('store_id','code','key'),
			'stock_status' => array('language_id','name'),
			'stored' => array('name'),
			'tax_class' => array('title'),
			'tax_rate_to_customer_group' => array('customer_group_id'),
			'tax_rule' => array('tax_class_id','tax_rate_id'),
			'upload' => array('code'),
			'url_alias' => array('query','keyword'),
			'user' => array('user_group_id','username','email','code','ip'),
			'user_group' => array('name'),
			'voucher' => array('order_id','code','to_email'),
			'voucher_history' => array('voucher_id','order_id'),
			'voucher_theme_description' => array('language_id','name'),
			'weight_class_description' => array('language_id','title'),
			'zone' => array('country_id','name','code'),
			'zone_to_geo_zone' => array('country_id','zone_id','geo_zone_id')
		);
		$TABLES = $this->db->query("SELECT TABLE_NAME FROM information_schema.tables WHERE TABLE_SCHEMA =  '{$db}'");
		$Findex= array();
		if($TABLES->num_rows){
			foreach ($TABLES->rows as $v) {
				$TN = str_replace($pre,'',$v['TABLE_NAME']);
				if(isset($index[$TN])) $Findex[$pre.$TN] = $index[$TN];
			}
		}
		$str = '';$i = 1;
		foreach ($Findex as $key => $v) {
			$columns = implode("','",$v);
			if($i != 1) $str .= " OR ";
			$str .= " (TABLE_NAME = '{$key}' AND COLUMN_NAME IN ('{$columns}') )";
			$i++;
		}
		if($str) $str = " AND ({$str})";
		$str = "SELECT TABLE_NAME,COLUMN_NAME FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = '{$db}' {$str}";
		$INDEXS = $this->db->query($str);
		if($INDEXS->num_rows){
			foreach ($INDEXS->rows as $v){
				$TN = $v['TABLE_NAME'];
				$CN = $v['COLUMN_NAME'];
				if(false !== $key = array_search($CN,$Findex[$TN])) unset($Findex[$TN][$key]);
			}
		}
		$Findex = array_filter($Findex);
		foreach ($Findex as $k =>  $v)
			foreach ($v as $c)
				$this->db->query("ALTER TABLE  `{$k}` ADD INDEX (`{$c}`)");
	}
	public function getAllCssJs(){
		$dir = DIR_CATALOG;
		$realpath = realpath($dir);
		$folders = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($realpath));
		foreach($folders as $folder){
			if(!is_file($folder)) continue;
		    $imagerealpath = realpath($folder);
		    $ex = pathinfo($imagerealpath, PATHINFO_EXTENSION);
		    if(!in_array($ex,array('css','js'))) continue;
		    $files[] = $imagerealpath;
		}
		$full = array();
		foreach ($files as $v) {
		$v = str_replace('\\', '/', $v);
			$name = str_replace($dir,'',$v); 
			$parts = explode('/',$name);
			$str = $dir;
			$tmpstr = '';
			foreach ($parts as $part) {
				eval('$check = isset($full'.$tmpstr.');');
				if(is_dir($str.'/'.$part)){
					$tmpstr .= '["'.$part.'"]';
					if(!$check){
						eval('$full'.$tmpstr.' = array();');
					}
				}else if(is_file($str.'/'.$part)){
					eval('$full'.$tmpstr.'[] = "'.$str.'/'.$part.'";');
				}
				$str .= '/'.$part;
			}
		}
		//return $full;
		echo $this->displaysub($full);
	}
	public function test_results(){
		error_reporting(0);
		$data = array();
		$this->load->model('setting/setting');
		$this->load->language('extension/module/speedup');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_api_key'] = $this->language->get('text_api_key');
		$data['text_url'] = $this->language->get('text_url');
		$data['text_filter_third_party_resource'] = $this->language->get('text_filter_third_party_resource');
		$data['text_possible_optimisation'] = $this->language->get('text_possible_optimisation');
		$data['text_optimisation_found'] = $this->language->get('text_optimisation_found');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_table_size'] = $this->language->get('text_table_size');
		$data['text_field_content_type'] = $this->language->get('text_field_content_type');
		$data['text_field_content_size'] = $this->language->get('text_field_content_size');
		$data['text_field_content_size'] = $this->language->get('text_field_content_size');
		$data['text_table_request'] = $this->language->get('text_table_request');
		$data['text_field_content_request'] = $this->language->get('text_field_content_request');
		
		if(!empty($this->request->get['url'])){
			$url = $this->request->get['url'];
		}else{
			$url = HTTP_CATALOG;
		}
		$resources = $this->request->get['resources'];
		$screenshot = $this->request->get['screenshot'];
		$strategy = $this->request->get['strategy'];
		$fields = 'formattedResults,id,invalidRules,kind,pageStats,responseCode,ruleGroups,screenshot,title,version';

		if(!empty($this->request->get['api'])){
			$api = $this->request->get['api'];
		}else{
			$api = '';
		}

		$contents = file_get_contents("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=".$url."&filter_third_party_resources=".$resources."&screenshot=".$screenshot."&strategy=".$strategy."&fields=".$fields."&key=".$api);
		$contents = json_decode($contents,1);
		if(!$contents){
			$data['errors'] = 'Enter Valid Website URL Or API Key';
		}else{

			$data['website'] = $contents['id'];
			$data['website_status'] = $contents['responseCode'];
			$data['website_title'] = $contents['title'];

			if($contents['responseCode'] > '200' || $contents['responseCode'] < '204'){
				$data['label'] = 'label-success';
			}else if($contents['responseCode'] > '404' || $contents['responseCode'] < '450'){
				$data['label'] = 'label-warning';
			}else if($contents['responseCode'] > '500' || $contents['responseCode'] < '550'){
				$data['label'] = 'label-danger';
			}else if($contents['responseCode'] > '300' || $contents['responseCode'] < '350'){
				$data['label'] = 'label-info';
			}


			if($contents['ruleGroups']['SPEED']['score'] > 0 && $contents['ruleGroups']['SPEED']['score'] < 50){
				$data['score_msg'] = 'poor';
			} else if($contents['ruleGroups']['SPEED']['score'] >= 50 && $contents['ruleGroups']['SPEED']['score'] <= 80){
				$data['score_msg'] = 'good';
			} else if($contents['ruleGroups']['SPEED']['score'] > 80){
				$data['score_msg'] = 'excellent';
			}
			$data['score'] = $contents['ruleGroups']['SPEED']['score'];
			$data['page_size_stats'] = array(
				'Requests'			=>	isset($contents['pageStats']['totalRequestBytes']) ? $this->size($contents['pageStats']['totalRequestBytes']) : 0,
				'Image'				=>	isset($contents['pageStats']['totalRequestBytes']) ? $this->size($contents['pageStats']['imageResponseBytes']) : 0,
				'Other'				=>	isset($contents['pageStats']['totalRequestBytes']) ? $this->size($contents['pageStats']['otherResponseBytes']) : 0,
				'Script'			=>	isset($contents['pageStats']['totalRequestBytes']) ? $this->size($contents['pageStats']['javascriptResponseBytes']) : 0,
				'CSS'				=>	isset($contents['pageStats']['totalRequestBytes']) ? $this->size($contents['pageStats']['cssResponseBytes']) : 0,
				'HTML'				=>	isset($contents['pageStats']['totalRequestBytes']) ? $this->size($contents['pageStats']['htmlResponseBytes']) : 0,
			);
			$data['page_request_stats'] = array(
				'Resources'			=>	isset($contents['pageStats']['numberResources']) ? $contents['pageStats']['numberResources'] : 0,
				'Static Resources'	=>	isset($contents['pageStats']['numberResources']) ? $contents['pageStats']['numberStaticResources'] : 0,
				'JS Resources'		=>	isset($contents['pageStats']['numberResources']) ? $contents['pageStats']['numberJsResources'] : 0,
				'CSS Resources'		=>	isset($contents['pageStats']['numberResources']) ? $contents['pageStats']['numberCssResources'] : 0,
				'Hosts'				=>	isset($contents['pageStats']['numberResources']) ? $contents['pageStats']['numberHosts'] : 0,
			);

			$data['possible_optimisation'] = array();
			$data['optimisation_found'] = array();
			foreach ($contents['formattedResults']['ruleResults'] as $rule){
				if(isset($rule['urlBlocks'])){
					$data['possible_optimisation'][] = array(
						'rulename'	=>	isset($rule['localizedRuleName']) ? $rule['localizedRuleName'] : '',
						'summary'	=>	isset($rule['summary']) ? $this->summary($rule['summary']) : '',
						'urlblocks'	=>	isset($rule['urlBlocks']) ? $this->urlblocks($rule['urlBlocks']) : '',
					);
				}else{
					$data['optimisation_found'][] = array(
						'rulename'	=>	isset($rule['localizedRuleName']) ? $rule['localizedRuleName'] : '',
						'summary'	=>	isset($rule['summary']) ? $this->summary($rule['summary']) : '',
					);
				}
			}

			if(isset($contents['screenshot'])){
			 	$d = $contents['screenshot']['data'];
			 	$type = $contents['screenshot']['mime_type'];
			 	$image = $this->data_uri($d,$type);
				$data['image'] = $image;
			}
		}
		echo $this->load->view('extension/module/page_speed_test_results',$data);
	}
	protected function summary($summary){
		if(isset($summary['args'])){
			$str = $summary['format'];
            preg_match_all('/\{\{[^\}]*\}\}/',$str,$match);
                foreach ($summary['args'] as $ar){
                    $key = '{{'.$ar['key'].'}}';
                    if(in_array($key,$match[0])){
                        $str = str_replace($key,$ar['value'],$str);
                    }else{
                        $bkey = '{{BEGIN_LINK}}';
                        $ekey = '{{END_LINK}}';
                        $bvalue = '<a href="'.$ar['value'].'" target="_blank">';
                        $evalue = '</a>';
                        $str = str_replace($bkey,$bvalue,$str);
                        $str = str_replace($ekey,$evalue,$str);
                    }
                }    
            } else{
        	$str = $summary['format'];
        }
        return $str;
	}
	protected function urlblocks($urlblocks){
		$header = array();
		foreach ($urlblocks as $url) {
			if(isset($url['header']['args'])){
				$str = $url['header']['format'];
	            preg_match_all('/\{\{[^\}]*\}\}/',$str,$match);
                foreach ($url['header']['args'] as $ar){
                    $key = '{{'.$ar['key'].'}}';
                    if(in_array($key,$match[0])){
                        $str = str_replace($key,$ar['value'],$str);
                    }else{
                        $bkey = '{{BEGIN_LINK}}';
                        $ekey = '{{END_LINK}}';
                        $bvalue = '<a href="'.$ar['value'].'" target="_blank">';
                        $evalue = '</a>';
                        $str = str_replace($bkey,$bvalue,$str);
                        $str = str_replace($ekey,$evalue,$str);
                    }
                }
				$urlss = array();
				if(isset($url['urls'])){
	                foreach ($url['urls'] as $urls) {
		                $urlstr = $urls['result']['format'];
			            preg_match_all('/\{\{[^\}]*\}\}/',$urlstr,$match);
		                foreach ($urls['result']['args'] as $ar){
		                    $key = '{{'.$ar['key'].'}}';
		                    if(in_array($key,$match[0])){
		                        $urlstr = str_replace($key,$ar['value'],$urlstr);
		                    }else{
		                        $bkey = '{{BEGIN_LINK}}';
		                        $ekey = '{{END_LINK}}';
		                        $bvalue = '<a href="'.$ar['value'].'" target="_blank">';
		                        $evalue = '</a>';
		                        $urlstr = str_replace($bkey,$bvalue,$urlstr);
		                        $urlstr = str_replace($ekey,$evalue,$urlstr);
		                    }
		                }
	                	$urlss[] = $urlstr;
	                }
				}
                $header['link'][] = array(
                	'link'	=> $str,
                	'urls'	=> $urlss,
                );

            }else{
            	$str = $url['header']['format'];
            	$header['header']=$str;
            }
		}
        return $header;
	}
	protected function data_uri($file, $mime){
		$data    = str_replace('_','/',$file);
		$data    = str_replace('-','+',$data);
		$decoded = base64_decode($data);
		file_put_contents('../image/google_screenshort.jpg',$decoded,LOCK_EX);
		$random = '../image/google_screenshort.jpg?'.rand(000,999);
		$img = $random;
		return $img;
	}
	protected function size($size){
	    if ($size < 1024) {
	        return "{$size} Bytes";
	    } elseif ($size < 1048576) {
	        $size_kb = round($size/1024);
	        return "{$size_kb} KB";
	    } else {
	        $size_mb = round($size/1048576, 1);
	        return "{$size_mb} MB";
	    }
	}
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/speedup')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
	
	protected function displaysub($sub = array()) {
		$html = '<ul class="directory-ul">';
		foreach ($sub as $name => $file){
			if (is_array($file) && $file){
	    		$html .= '<li>
		            <div class="colleps open">-</div>
		            <label for="'.$this->i.'" class="folder">
		            <span>'.$name.'</span>
		            </label>'.$this->displaysub($file).'
		        </li>';

	    	}else{
		        $html .= '<li>
		            <label for="file'.$this->i.'">
		              <input type="checkbox" id="file'.$this->i.'" value="'.$file.'" name="compressfile[]" class="compressfiles" />
		              <span>'.basename($file).'</span>
		            </label>
		        </li>';
		        $this->i++;
	    	}
		}
	    $html .= '</ul>';
	    return $html;
	}
	
	// Speedup Cache Functions
	public function pathindexphp() {
        $path=dirname(DIR_APPLICATION) .'/' . 'index.php';
        return $path; 
    }
	public function stats() {
        require_once(DIR_SYSTEM . 'library/speedup_cache.php');
        $pagecache = new SpeedUpCache();
        $vals=$pagecache->Settings();
        $expire=$vals['expire'];
        $cachefolder=$vals['cachefolder'];
        $range=array( '0','1','2','3','4','5','6','7',
                      '8','9','a','b','c','d','e','f');
        $stats['totalf']=0;
        $stats['totalfe']=0;
        $stats['totalfv']=0;
        $stats['totalb']=0;
        $stats['totalbe']=0;
        $stats['totalbv']=0;
        foreach ($range as $f) {
            foreach ($range as $s) {
                $dname=$cachefolder . $f . '/' . $s;
                if (is_dir($dname) && @$dir=opendir($dname)) {
                    while (false !== ($file = readdir($dir))) {
                       $fpath=$dname . '/' . $file; 
                       if (is_file($fpath)) {
                           $fstats=stat($fpath);
                           $sizemb=number_format($fstats['size']/1048576,2);
                           $ctime=$fstats['ctime'];
                           $stats['totalb']+=$sizemb;
                           $stats['totalf']+=1;
                           if ($ctime+$expire < time()) {
                               $status='expired';
                               $stats['totalbe']+=$sizemb;
                               $stats['totalfe']+=1;
                           } else {
                               $status='valid';
                               $stats['totalbv']+=$sizemb;
                               $stats['totalfv']+=1;
                           }
                       }
                    }
                }
            }
        }
        $stats['totalb']=number_format($stats['totalb'],2);
        $stats['totalbe']=number_format($stats['totalbe'],2);
        $stats['totalbv']=number_format($stats['totalbv'],2);
        $stats['success']='ok';
        $this->response->setOutput(json_encode($stats));
    } 
    public function octlversion() {
        $varray=explode('.',VERSION);
        return $varray[0] . '.' . $varray[1];
    }
    public function isreadable() {
        $filepath=$this->pathindexphp();
        $dirpath=dirname($this->pathindexphp());
        if (!is_file($filepath)) {
            return array(false,
                "[$filepath] ". $this->language->get('speedup_cache_not_exist')
            );
        }
        if (!is_readable($filepath)) {
            return array(false,
                "[$filepath] " . $this->language->get('speedup_cache_not_readable')
            );
        }
        return array(true,
            "[$filepath] " . $this->language->get('speedup_cache_readable')
        );
    }
    public function iswriteable() {
        $filepath=$this->pathindexphp();
        $dirpath=dirname($this->pathindexphp());
        if (!is_writable($filepath)) {
            return array(false,
                "[$filepath] " . $this->language->get('speedup_cache_not_writeable')
            );
        }
        if (!is_writable($dirpath)) {
            return array(false,
                "[$dirpath] " . $this->language->get('speedup_cache_not_writeable')
            );
        }
        return array(true,
            "[$filepath] " . $this->language->get('speedup_cache_writeable')
        );
    }
    public function compatstatus() {
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
             $phpcompat='supported (PHP 5.4 or greater)';
        } else {
             $phpcompat='unsupported (PHP 5.4 or greater is recommended)';
        }
        $phpsapi=php_sapi_name();
        if ($phpsapi == 'apache2handler') {
            $phpsapi='apache2handler (mod_php)';
            $sapicompat=$this->language->get('speedup_cache_sapi_mod_php');
        } elseif ($phpsapi == 'cgi-fcgi') {
            if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
                $sapicompat=$this->language->get('speedup_cache_sapi_fcgi');
            } else {
                $sapicompat=$this->language->get('speedup_cache_sapi_fcgi_oldphp');
            }
        } elseif ($phpsapi == 'litespeed') {
                $sapicompat=$this->language->get('speedup_cache_sapi_litespeed');
        } else {
            $sapicompat=$this->language->get('speedup_cache_sapi_not_tested');
        }
        return "<table class='table'><thead>".
               "<tr><td>Component</td><td>Detected</td>".
               "<td>Status</td></tr></thead><tbody><tr>".
               "<td>PHP</td><td>" . phpversion() . "</td>".
               "<td>$phpcompat</td></tr>".
               "<tr><td>SAPI</td><td>" . $phpsapi . "</td>".
               "<td>$sapicompat</td></tr>".
               "</tbody></table>";
    }
    public function statusindexphp() {
        $this->language->load('extension/module/speedup_cache');
        $check=$this->isreadable();
        if ($check[0] == false) {
            return $check;
        }
        $fp=fopen($this->pathindexphp(),'r');
        $pgcount=0;$topmarker=false;$bottommarker=false;
        $desiredcount=count($this->topcode()) + count($this->bottomcode());
        if ($this->octlversion() >= 2.3) {
            $bmatch='#start\(.catalog.\);#';
        } else if ($this->octlversion() >= 2.2) {
            $bmatch='#DIR_SYSTEM.*framework\.php#';
        } else {
            $bmatch='#^\$response->output\(\);.*$#';
        }
        while(!feof($fp)) {
            $line=fgets($fp);
            if (preg_match('#^// Install\s*$#',$line))  {
                $topmarker=true;
            }
            if (preg_match($bmatch,$line))  {
                $bottommarker=true;
            }
            if (preg_match('#//SPEEDUPCACHE#',$line)) {
                $pgcount++;
            } 
        }
        fclose($fp);
        if ($topmarker == false) {
            return(array('error',
                $this->language->get('speedup_cache_err_topmarker') 
            ));
        }
        if ($bottommarker == false) {
            return(array('error',
                 $this->language->get('speedup_cache_err_bottommarker') 
            ));
        }
        if ($pgcount == 0) {
            return(array('disabled',
                $this->language->get('speedup_cache_pagecache_disabled'))
            );
        } else if ($pgcount == $desiredcount) {
            return(array('enabled',
                $this->language->get('speedup_cache_pagecache_enabled'))
            );
        } else {
            $error=sprintf($this->language->get('speedup_cache_count_error'),
                $pgcount,$desiredcount,$this->pathindexphp);
            return(array('error', $error));
        }
    }
    public function topcode() {
        return(array(
          'require_once(DIR_SYSTEM . \'library/speedup_cache.php\');' ,
          '$pagecache = new SpeedUpCache();' ,
          'if ($pagecache->ServeFromCache()) {' ,  
          '    // exit here if we served this page from the cache' ,
          '    return;',
          '}'
        ));
    }
    public function bottomcode() {
        return(array(
          'if ($pagecache->OkToCache()) {' ,
          '    $pagecache->CachePage($response);',
          '}'
        ));
    }
    public function enable() {
        $status=$this->statusindexphp();
        if ($this->octlversion() < 2.3) {
            $this->response->setOutput(json_encode(
                array('error' => $this->language->get('speedup_cache_only_2_3_supported')) 
            ));
            return;
        }
        if ($status[0] == 'enabled') {
            $this->response->setOutput(json_encode(
                array('error' => $this->language->get('speedup_cache_already_enabled')) 
            ));
            return;
        } elseif ($status[0] == 'false') {
            $this->response->setOutput(json_encode(
                array('error' => 
                    $this->language->get('speedup_cache_enable_error'). $status[1]) 
                )
            );
            return;
        } elseif  ($status[0] != 'disabled') {
            $this->response->setOutput(json_encode(
                array('error' => 
                    $this->language->get('speedup_cache_enable_error') . $status[0]) 
                )
            );
            return;
        }
        $status=$this->iswriteable();
        if ($status[0] != true) {
            $this->response->setOutput(json_encode(
                array('error' => 
                    $this->language->get('speedup_cache_enable_error') . $status[1]) 
                )
            );
            return;
        }
        $tempfile=$this->pathindexphp() . '.tmp';
        $out=@fopen($tempfile,'w');
        $in=@fopen($this->pathindexphp(),'r');
        if ($this->octlversion() >= 2.3) {
            $bmatch='#start\(.catalog.\);#';
        } else if ($this->octlversion() >= 2.2) {
            $bmatch='#DIR_SYSTEM.*framework\.php#';
        } else {
            $bmatch='#^\$response->output\(\);.*$#';
        }
        while(!feof($in)) {
            $line=fgets($in);
            if (preg_match('#^// Install\s*$#',$line))  {
                foreach ($this->topcode() as $code) {
                    fwrite($out,str_pad($code,60) . "    //SPEEDUPCACHE\n");
                }
                fwrite($out,$line);
            } elseif (preg_match($bmatch,$line))  {
                fwrite($out,$line);
                if (substr($line,-1) != "\n") {
                    fwrite($out,"\n");
                }
                foreach ($this->bottomcode() as $code) {
                    fwrite($out,str_pad($code,60) . "    //SPEEDUPCACHE\n");
                }
            } else {
                fwrite($out,$line);
            }
        }
        fclose($out);
        fclose($in);
        rename($tempfile,$this->pathindexphp()); 
        // clear cache if apc is in use (in case apc.stat == 1)
        if (function_exists('apc_clear_cache')) {
            apc_clear_cache();
        } 
        $status=$this->statusindexphp();
        $this->response->setOutput(json_encode(
                array($status[0] => $status[1]) 
        ));
    }
    public function jsonstatusindexphp() {
        $status=$this->statusindexphp();
        $this->response->setOutput(json_encode(
                array('status' => $status[0],
                      'detail' => $status[1]
                ) 
        ));
    }
    public function disable($quiet=false) {
        $status=$this->statusindexphp();
        if ($status[0] == 'disabled') {
            $this->response->setOutput(json_encode(
                array('error' => $this->language->get('speedup_cache_already_disabled'))
            ));
            return;
        } elseif ($status[0] == 'false') {
            $this->response->setOutput(json_encode(
                array('error' => 
                  $this->language->get('speedup_cache_disable_error') . $status[1])
            ));
            return;
        } elseif  ($status[0] != 'enabled') {
            $this->response->setOutput(json_encode(
                array('error' => $this->language->get('speedup_cache_disable_error') . 
                    $status[0])
            ));
            return;
        }
        $status=$this->iswriteable();
        if ($status[0] != true) {
            $this->response->setOutput(json_encode(
                array('error' => $this->language->get('speedup_cache_disable_error') . 
                    $status[1])
            ));
            return;
        }
        $tempfile=$this->pathindexphp() . '.tmp';
        $out=@fopen($tempfile,'w');
        $in=@fopen($this->pathindexphp(),'r');
        while(!feof($in)) {
            $line=fgets($in);
            if (!preg_match('#//SPEEDUPCACHE#',$line))  {
                fwrite($out,$line);
            } 
        }
        fclose($out);
        fclose($in);
        rename($tempfile,$this->pathindexphp());
        // clear cache if apc is in use (in case apc.stat == 1)
        if (function_exists('apc_clear_cache')) {
            apc_clear_cache();
        } 
        $status=$this->statusindexphp();
        if ($quiet == false) {
            $this->response->setOutput(json_encode(
                array($status[0] => $status[1]) 
            ));
        }
    }
    public function purge() {
        $this->language->load('extension/module/speedup_cache');
        $which=$this->request->get['which'];
        if ($which != "all" && $which != "expired") {
           $this->response->setOutput(json_encode(
                array('error' => 'invalid value for which')
           ));
           return;
        }
        require_once(DIR_SYSTEM . 'library/speedup_cache.php');
        $pagecache = new SpeedUpCache();
        $vals=$pagecache->Settings();
        $expire=$vals['expire'];
        $cachefolder=$vals['cachefolder'];
        $range=array( '0','1','2','3','4','5','6','7',
                      '8','9','a','b','c','d','e','f');
        $count=0;
        foreach ($range as $f) {
            foreach ($range as $s) {
                $dname=$cachefolder . $f . '/' . $s;
                if (is_dir($dname) && @$dir=opendir($dname)) {
                    while (false !== ($file = readdir($dir))) {
                       // only purge files that end in .cache
                       if (substr($file,-6) == '.cache') {
                           $fpath=$dname . '/' . $file;
                           if ($which == 'all') {
                               unlink($fpath);
                               $count++;
                           } elseif ($which == 'expired') {
                               if (filectime($fpath)+$expire < time()) {
                                   unlink($fpath);
                                   $count++;
                               }
                           }
                       }
                    }
                }
            }
        }
        $message=sprintf($this->language->get('speedup_cache_purged'),$count);
        $this->response->setOutput(json_encode(
            array('success' => $message)
        ));
    }
    public function uninstall() {
        $this->disable(true);
		$configexpiretime = $this->config->get('module_speedup_cache_expire_time');
        $compressionLevel = $this->config->get('module_speedup_compression');

        $speedup_const = DIR_SYSTEM."library/minify/speedup_const.php";
        $catalog_config = str_replace('admin/', 'config.php', DIR_APPLICATION);
		$file_content = trim(file_get_contents($catalog_config));
        $replace = str_replace("require_once('".$speedup_const."');", "", $file_content);
        file_put_contents($catalog_config, $replace);
        
		$admin_config = DIR_APPLICATION .'config.php';
		$file_content2 = trim(file_get_contents($admin_config));
        $replace2 = str_replace("require_once('".$speedup_const."');", "", $file_content2);
        file_put_contents($admin_config, $replace2);

    }
}
