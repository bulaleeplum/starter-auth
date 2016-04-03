<?php

/**
 * core/MY_Controller.php
 *
 * Default application controller
 *
 * @author		JLP
 * @copyright           2010-2013, James L. Parry
 * ------------------------------------------------------------------------
 */
class Application extends CI_Controller {

	protected $data = array();   // parameters for view components
	protected $id; // identifier for our content

	/**
	 * Constructor.
	 * Establish view parameters & load common helpers
	 */

	function __construct()
	{
		parent::__construct();
		$this->data = array();
		$this->data['title'] = "Top Secret Government Site"; // our default title
		$this->errors = array();
		$this->data['pageTitle'] = 'welcome';   // our default page
	}

	/**
	 * Render this page
	 */
	function render()
	{
		$mychoices = array('menudata' => $this->makemenu());
		$this->data['sessionid'] = session_id();
		$this->data['menubar'] = $this->parser->parse('_menubar', $mychoices, true);
		$this->data['content'] = $this->parser->parse($this->data['pagebody'], $this->data, true);

		// finally, build the browser page!
		$this->data['data'] = &$this->data;
		$this->parser->parse('_template', $this->data);
	}

	// build menu choices depending on the user role
	function makemenu()
	{
		// get all menu items from config
		$choices = $this->config->item('menu_choices')['menudata'];
		$choice = array();

		$role = $this->session->userdata('userRole');
		$name = $this->session->userdata('userName');

		$choice[] = $choices[0];		// alpha page

		if ($role == null) {
			$choice[] = $choices[3];	// login
			return $choice;
		}
		if ($role == "user") {
			$choice[] = $choices[1];	// beta page
			$choice[] = $choices[4];	// logout
		}
		if ($role == "admin") {
			$choice[] = $choices[1];	// beta page
			$choice[] = $choices[2];	// gamma page
			$choice[] = $choices[4];	// logout
		}
		$choice[] = array('name' => 'Logged in as ' . $name, 'link' => '');

		return $choice;
	}

	function restrict($roleNeeded = null) {
		$userRole = $this->session->userdata('userRole');
		if ($roleNeeded != null) {
			if (is_array($roleNeeded)) {
				if (!in_array($userRole, $roleNeeded)) {
					redirect("/");
					return;
				}
			} else if ($userRole != $roleNeeded) {
				redirect("/");
				return;
			}
		}
	}

}

/* End of file MY_Controller.php */
/* Location: application/core/MY_Controller.php */