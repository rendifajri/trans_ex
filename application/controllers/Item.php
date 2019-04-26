<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Item extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model("Item_model");
	}
	public function index()
	{
		$data['message'] = $this->session->flashdata('message');
		$data['jamak'] = $this->Item_model->getAll();
		$header['judul'] = 'Item';
		$this->load->view('header', $header);
		$this->load->view('Item/Index', $data);
		$this->load->view('footer');
	}
	public function CreateDb()
	{
		$cek = $this->Item_model->getByBarcode($this->input->post('barcode'));
		if($cek->num_rows() > 0)
			$this->session->set_flashdata('message', 'Item sudah ada.');
		else{
			$data = array(
				'name' => $this->input->post('name'),
				'barcode' => $this->input->post('barcode'),
				'price' => str_replace(',', '', $this->input->post('price'))
			);
			$this->Item_model->insert($data);
			$this->session->set_flashdata('message', 'Item berhasil ditambahkan.');
		}
		redirect('Item');
	}
	public function EditDb()
	{
		$cek = $this->Item_model->getByBarcode($this->input->post('barcode'));
		if($cek->num_rows() > 0 && $cek->row()->id != $this->input->post('id'))
			$this->session->set_flashdata('message', 'Item sudah ada.');
		else{
			$data = array(
				'name' => $this->input->post('name'),
				'barcode' => $this->input->post('barcode'),
				'price' => str_replace(',', '', $this->input->post('price'))
			);
			$condition['id'] = $this->input->post('id');
			$this->Item_model->update($data, $condition);
			$this->session->set_flashdata('message', 'Item berhasil diubah.');
		}
		redirect('Item');
	}
	public function Delete($id)
	{
/*
SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE REFERENCED_TABLE_NAME = 'item' AND TABLE_SCHEMA = 'trans_ex';

"sales_order_detail"	"item_id"	"sales_order_detail_ibfk_2"	"item"	"id"

*/
		$bool_check = false;
		$bool_check = $bool_check ?: $this->Customer_model->getTableBy('sales_order_detail', array('item_id' => $id))->num_rows() > 0;
		if(!$bool_check){
			$this->Item_model->delete($id);
			$this->session->set_flashdata('message', 'Item berhasil dihapus.');
		}
		else
			$this->session->set_flashdata('message', 'Item sedang digunakan.');
		redirect('Item');
	}
}
?>