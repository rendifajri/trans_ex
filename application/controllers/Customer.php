<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Customer extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model("Customer_model");
	}
	public function index()
	{
		$data['message'] = $this->session->flashdata('message');
		$data['jamak'] = $this->Customer_model->getAll();
		$header['judul'] = 'Customer';
		$this->load->view('header', $header);
		$this->load->view('Customer/Index', $data);
		$this->load->view('footer');
	}
	public function CreateDb()
	{
		$cek = $this->Customer_model->getByName($this->input->post('name'));
		if($cek->num_rows() > 0)
			$this->session->set_flashdata('message', 'Customer sudah ada.');
		else{
			$data = array(
				'name' => $this->input->post('name')
			);
			$this->Customer_model->insert($data);
			$this->session->set_flashdata('message', 'Customer berhasil ditambahkan.');
		}
		redirect('Customer');
	}
	public function EditDb()
	{
		$cek = $this->Customer_model->getByName($this->input->post('name'));
		if($cek->num_rows() > 0 && $cek->row()->id != $this->input->post('id'))
			$this->session->set_flashdata('message', 'Customer sudah ada.');
		else{
			$data = array(
				'name' => $this->input->post('name')
			);
			$condition['id'] = $this->input->post('id');
			$this->Customer_model->update($data, $condition);
			$this->session->set_flashdata('message', 'Customer berhasil diubah.');
		}
		redirect('Customer');
	}
	public function Delete($id)
	{
/*
SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE REFERENCED_TABLE_NAME = 'customer' AND TABLE_SCHEMA = 'rm_pos';

"sales_order"	"customer_id"	"sales_order_ibfk_1"	"customer"	"id"

*/
		$bool_check = false;
		$bool_check = $bool_check ?: $this->Customer_model->getTableBy('sales_order', array('customer_id' => $id))->num_rows() > 0;
		if(!$bool_check){
			$this->Customer_model->delete($id);
			$this->session->set_flashdata('message', 'Customer berhasil dihapus.');
		}
		else
			$this->session->set_flashdata('message', 'Customer sedang digunakan.');
		redirect('Customer');
	}
}
?>