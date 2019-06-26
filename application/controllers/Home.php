<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
 
	public function index()
	{
		$this->load->view('layout/header');
		$this->load->view('web/index');
		$this->load->view('layout/footer');
	}
	public function aboutUs()
	{
		$this->load->view('layout/header');
		$this->load->view('web/aboutUs');
		$this->load->view('layout/footer');
	}
	public function WhatweDo()
	{
		$this->load->view('layout/header');
		$this->load->view('web/blog');
		$this->load->view('layout/footer');
	}
	public function Donate()
	{
		
		$this->load->view('layout/header');
		$this->load->view('web/donate');
		$this->load->view('layout/footer');
	}
	
	public function JoinUs()
	{
		
		$this->load->view('layout/header');
		$this->load->view('web/contact');
		$this->load->view('layout/footer');
	}
	
	public function Pressrelease()
	{
		
		$this->load->view('layout/header');
		$this->load->view('web/index');
		$this->load->view('layout/footer');
	}
	
	public function ContactUs()
	{
		$this->load->view('layout/header');
		$this->load->view('web/contact');
		$this->load->view('layout/footer');
	}
	
}
