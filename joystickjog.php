<?php
/*
Plugin Name: Joystick Jog
Plugin URI: http://github.com/FAB-UI-plugins/joystickjog
Version: 0.55
Description: Jog using ps3 controller
Author: Tom Haraldseid
Author URI: 
Plugin Slug: joystickjog
Icon: icon-fab-jog
*/
 

 
class Joystickjog extends Plugin {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('url');
		
		define('MY_PLUGIN_URL', site_url().'plugin/joystickjog/');
		define('MY_PLUGIN_PATH', PLUGINSPATH.'joystickjog/');

		
        //FLUSH SERIAL PORT BUFFER INPUT/OUTPUT
        $this->load->helper('print_helper');
        /** IF PRINTER IS BUSY I CANT JOG  */
        if(is_printer_busy()){
            redirect('dashboard');
        }
        /** LOAD HELPER */
        $this->load->helper('update_helper');
        $this->lang->load($_SESSION['language']['name'], $_SESSION['language']['name']);
        
	}

	public function index(){
		
		
        
		//carico X class database
		$this->load->database();
		$this->load->model('configuration');

        
        
        $this->configuration->save_confi_value('coordinates', 'relative');
        
        $data['_coordinates'] = $this->configuration->get_config_value('coordinates');
		$data['_motors']      = $this->configuration->get_config_value('motors');
        $data['_lights']      = $this->configuration->get_config_value('lights');
        
        
        $ext_temp = 0;
        $bed_temp = 0;
        $position = '';
        
		$data['_ext_temp']    = $ext_temp;
        $data['_bed_temp']    = $bed_temp;
        $data['_position']    = $position;
        
         

		$css_in_page = $this->load->view('index/css', '', TRUE);
		$js_in_page  = $this->load->view('index/js', $data, TRUE);

		$this->layout->add_css_in_page(array('data'=> $css_in_page, 'comment' => 'JOG CSS'));
		$this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => 'JOG JS'));

        $this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/noUiSlider.7.0.10/jquery.nouislider.all.min.js', 'comment' => 'javascript for the noUISlider'));
        $this->layout->add_css_file(array('src'=>'application/layout/assets/js/plugin/noUiSlider.7.0.10/jquery.nouislider.min.css', 'comment' => 'javascript for the noUISlider'));
		$this->layout->add_css_file(array('src'=>'application/layout/assets/js/plugin/noUiSlider.7.0.10/jquery.nouislider.pips.min.css', 'comment' => 'javascript for the noUISlider'));
         
        $this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/bootstrap-progressbar/bootstrap-progressbar.min.js', 'comment' => ''));
        
        
        $this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/knob/jquery.knob.min.js', 'comment'=>'KNOB'));
		
		$this->layout->set_compress(false);
	
		$this->layout->view('index/index', $data); 
	}
    
    
    
    
    public function setup(){
        
        $this->load->database();
		$this->load->model('configuration');

        $data['_unit']     = $this->configuration->get_config_value('unit');
		$data['_step']     = $this->configuration->get_config_value('step');
		$data['_feedrate'] = $this->configuration->get_config_value('feedrate');
        
        $js_in_page  = $this->load->view('setup/js', '', TRUE);
        $this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => 'JOG JS'));
        
        $this->layout->view('setup/index', $data);
    }
	
	
	public function manual(){
		
		//carico X class database
		$this->load->database();
		$this->load->model('codes');
		
		$g_codes = $this->codes->get_all('G');
		$m_codes = $this->codes->get_all('M');
		
		$data['gcodes'] = $g_codes;
		$data['mcodes'] = $m_codes;
		
		$this->load->view('manual/index', $data);
	}
	
	
	public function update()
	{
		$_is_internet_ok = is_internet_avaiable();
		
	
		if($_is_internet_ok){
			$cmd = 'sudo rm -r -f application/plugins/joystickjog/';
			echo shell_exec($cmd);
			$cmd = 'sudo git clone https://github.com/FAB-UI-plugins/joystickjog.git application/plugins/joystickjog';
			echo shell_exec($cmd);
		
		}
		redirect(MY_PLUGIN_URL);

	
	}





}

?>