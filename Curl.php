<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Curl Class
 */
class Curl {

  protected $_ci;                   // CodeIgniter instance
	protected $_curl;                 // CodeIgniter instance
        protected $_post = array();
        protected $_get = array();
        protected $_headers = array();
        protected $_options = array();
        
        protected $_cookie_secret;

	function __construct()
	{
		$this->_ci = & get_instance();
                $this->_ci->load->helper('string');
                $this->_cookie_secret = random_string('numeric', 10).".txt";
               // $this->_cookie_secret = 'cookie.txt';
                echo '---'.$this->_cookie_secret.'---';
                
		log_message('debug', 'cURL Class Initialized');
	}
            
        function set_url($url)
        {
            $this->url = $url;
            
            if (empty($url)) $url = $this->url;
            $this->set_options(CURLOPT_URL, $url);
        }
        
	function set_post($post_data = '', $post_data_str = '')
        {
            if (! empty($post_data) && ! empty($post_data_str))
            {
                $this->_post[$post_data] = $post_data_str;
            }   
            
            if (is_array($post_data))
            {
                foreach($post_data as $key => $val)
                {
                    $this->_post[$key] = $val;
                }
            }
        }
        
	function set_get($get_data = '', $get_data_str = '')
        {
            if (! empty($get_data) && ! empty($get_data_str))
            {
                $this->_get[$get_data] = $get_data_str;
            }
            if (is_array($post_data))
            {
                foreach($get_data as $key => $val)
                {
                    $this->_get[$key] = $val;
                }
            }
        }
        
	function set_method($method = 'get')
        {
            if ($method == 'get' || $method == 'GET') {
               // curl_setopt($this->_curl, CURLOPT_POST, 0);
                $this->set_options(CURLOPT_POST, 0);
            }
            if ($method == 'post' || $method == 'POST') {
                //curl_setopt($this->_curl, CURLOPT_POST, 1);
                $this->set_options(CURLOPT_POST, 1);
            }
        }
        
	function set_headers($headers)
        {
            if (is_string($headers))
            {
                $this->_headers[] = $headers;
            }
            if (is_array($headers))
            {
                foreach($headers as $val)
                {
                    $this->_headers[] = $val;
                }
            }
            
        }
            
	function set_options($option, $value)
        {
            //curl_setopt($this->_curl, $option, $value);
            $this->_options[$option] = $value;
        }
        
        
        function _init()
        {
          //  $this->_post = array();
          //  $this->_headers = array();
           // $this->_options = array();
            
            //$referer = explode('?', $this->url); $referer = $referer[0];
            $host = preg_replace('/http:\/\//', '', $this->url);
            $host = explode('/', $host); $host = trim($host[0]);

         //   $this->set_options(CURLOPT_REFERER, $referer) 
            $this->set_options(CURLOPT_CONNECTTIMEOUT, 30);
            $this->set_options(CURLOPT_COOKIEJAR, $this->_cookie_secret);
            $this->set_options(CURLOPT_COOKIEFILE, $this->_cookie_secret);
            $this->set_options(CURLOPT_RETURNTRANSFER, true);
            $this->set_options(CURLINFO_HEADER_OUT, true);
            $this->set_options(CURLOPT_HEADER, false);

            $this->set_headers('Accept-Charset:windows-1251,utf-8;q=0.7,*;q=0.3');
            $this->set_headers('Accept-Language:ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4');
           // $this->set_headers('Content-Length:34');
           // $this->set_headers('Host:'.$host);
            $this->set_headers('Origin:http://'.$host);
            $this->set_headers('User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64; rv:20.0) Gecko/20100101 Firefox/20.0');  
            $this->set_headers('Content-Type:application/octet-stream');  
        }
        function get_cookie($cookie_name = '')
        {
            if (empty($cookie_name)) return file($this->_cookie_secret);

            foreach (file($this->_cookie_secret) as $line_num => $line) {
                if (preg_match('/'.$cookie_name.'/', $line)) {
                        $_bufer = explode('	',$line);
                        return  $_bufer[6];
                }
	}
        
   
        }
        
        function cookie_secret()
        {
            return $this->_cookie_secret;
        }
        
        function exec($url = '')
        {
            if (! empty($url)) $this->set_url($url);
            
            $this->_init();
            $this->_curl = curl_init();
            
            curl_setopt($this->_curl, CURLOPT_HTTPHEADER, $this->_headers); 
            curl_setopt($this->_curl, CURLOPT_POSTFIELDS, http_build_query($this->_post));
            curl_setopt_array( $this->_curl, $this->_options );
                
            $this->html = curl_exec($this->_curl);
        //   echo "\n ------------------------------- \n" ; print_r( curl_getinfo($this->_curl)); echo "\n ------------------------------- \n";
            $this->info = curl_getinfo($this->_curl);
            curl_close($this->_curl);  
            $this->_post = array();
            $this->_headers = array();
            $this->_options = array();   
            return $this->html;
            
        }
          
}
