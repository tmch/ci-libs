<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Viewer {

  var $doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	var $charset = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	var $title = 'site site';
		
	var $show_header = 1;
	var $show_sidebar = 1;
	var $show_footer = 1;
	var $sidebar_position = 'left';	
	
	var $tpl_folder = 'templates';
		
	function __construct()
	{
		$this->CI  =& get_instance();
		$this->CI->load->library('parser');	
	}
	
	function view($tpl = '', $data = '')
	{
		$this->_init($data);
		
		// *HEADER*
		if ( $this->show_header == 1 )
			$this->CI->parser->parse($this->tpl_folder."/header.tpl", $this->header_data);
		
		// *SIDEBAR - LEFT*
		if ( $this->show_sidebar == 1 && $this->sidebar_position == 'left' )
			$this->CI->parser->parse($this->tpl_folder."/sidebar.tpl", $this->sidebar_data);
		
		// *PAGE*
		if ( ! empty($tpl) )
			$this->CI->parser->parse($this->tpl_folder."/".$tpl.".tpl", $this->page_data);
	
		// *SIDEBAR - RIGHT*
		if ( $this->show_sidebar == 1 && $this->sidebar_position == 'right' )
			$this->CI->parser->parse($this->tpl_folder."/sidebar.tpl", $this->sidebar_data);
									
		// *FOOTER*
		if ( $this->show_footer == 1 )
			$this->CI->parser->parse($this->tpl_folder."/footer.tpl", $this->footer_data);
				
	}
	
	function _init($data = '')
	{
		if (! empty($data))
		{
			$this->header_data = (empty($data['header'])) ? array() : $data['header'];
			$this->page_data = (empty($data['page'])) ? array() : $data['page'];
			$this->sidebar_data = (empty($data['sidebar'])) ? array() : $data['sidebar'];
			$this->footer_data = (empty($data['footer'])) ? array() : $data['footer'];

			// install headrer
			if (empty($this->header_data['charset'])) $this->header_data['charset'] = $this->charset;
			if (empty($this->header_data['doctype'])) $this->header_data['doctype'] = $this->doctype;
			if (empty($this->header_data['title'])) $this->header_data['title'] = $this->title;
		}
	}
}
?>
