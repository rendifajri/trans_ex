<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class SalesOrder extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model("SalesOrder_model");
		$this->load->model("Customer_model");
		$this->load->model("Item_model");
	}
	public function index()
	{
		$data['message'] = $this->session->flashdata('message');
		$data['jamak'] = $this->SalesOrder_model->getAll();
		$header['judul'] = 'SalesOrder';
		$this->load->view('header', $header);
		$this->load->view('SalesOrder/Index', $data);
		$this->load->view('footer');
	}
	public function CekKode($code=null, $id=null)
	{
		$cek = $this->SalesOrder_model->getByCode($code);
		if($cek->num_rows() > 0 && $id == null){
			$code = array(
						0 => 'Kode Sales Order sudah ada!'
					);
			$errors = array(
						'code' => $code
					);
			$hasil = array(
						'errors' => $errors,
						'status' => false
					);
		}
		else if($cek->num_rows() > 0 && $id != null && $cek->row()->id != $id){
			$code = array(
						0 => 'Kode Sales Order sudah ada!'
					);
			$errors = array(
						'code' => $code
					);
			$hasil = array(
						'errors' => $errors,
						'status' => false
					);
		}
		else{
			$hasil = array(
						'data' => null,
						'status' => true
					);
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($hasil));
	}
	public function Create()
	{
		$data['message'] = $this->session->flashdata('message');
		$data['code'] = $this->SalesOrder_model->getLastCode();
		$data['customer'] = $this->Customer_model->getAll();
		$data['item'] = $this->Item_model->getAll();
		$header['judul'] = 'SalesOrder - Tambah';
		$this->load->view('header', $header);
		$this->load->view('SalesOrder/Create', $data);
		$this->load->view('footer');
	}
	public function CreateDb()
	{
		$data = array(
			'code' => $this->input->post('code'),
			'customer_id' => $this->input->post('customer_id'),
			'date' => $this->input->post('date'),
			'paid' => $this->input->post('paid')
		);
		$cek = $this->SalesOrder_model->getByCode($this->input->post('code'));
		if($cek->num_rows() == 0){
			$sales_order_id = $this->SalesOrder_model->insert('sales_order', $data);

			$item_id = $this->input->post('item_id');
			$quantity = $this->input->post('quantity');
			$price = $this->input->post('price');
			for($i=0; $i<count($item_id); $i++){
				if($item_id[$i] != "" && $quantity[$i] != "" && $price[$i] != "" && $this->SalesOrder_model->checkSalesOrderItem($sales_order_id, $item_id[$i])){
					$data = array(
						'sales_order_id' => $sales_order_id,
						'item_id' => $item_id[$i],
						'quantity' => str_replace(',', '', $quantity[$i]),
						'price' => str_replace(',', '', $price[$i])
					);
					//var_dump($data);
					$this->SalesOrder_model->insert('sales_order_detail', $data);
				}
			}
		}
		echo "<script language=\"javascript\">window.open(\"".base_url()."index.php/SalesOrder/StrukPDF/".$sales_order_id."\", \"_blank\");</script>";
		$this->session->set_flashdata('message', 'Sales Order berhasil ditambahkan.');
		redirect('SalesOrder/Create', 'refresh');
	}
	public function View($id)
	{
		$data['message'] = $this->session->flashdata('message');
		
		$data['tunggal'] = $this->SalesOrder_model->get($id)->row();
		$data['jamak_detail'] = $this->SalesOrder_model->getDetail($id);
		
		$data['customer'] = $this->Customer_model->getAll();
		$data['item'] = $this->Item_model->getAll();
		$header['judul'] = 'SalesOrder - Lihat';
		$this->load->view('header', $header);
		$this->load->view('SalesOrder/View', $data);
		$this->load->view('footer');
	}
	public function StrukPDF($id)
	{
		if($this->session->userdata('id') === null)
			redirect('User/Login');
		$data['message'] = $this->session->flashdata('message');
		
		$data['tunggal'] = $this->SalesOrder_model->get($id)->row();
		$data['jamak_detail'] = $this->SalesOrder_model->getDetail($id);
		
		$this->load->view('SalesOrder/StrukPDF', $data);
	}
	public function Edit($id)
	{
		$data['message'] = $this->session->flashdata('message');
		
		$data['tunggal'] = $this->SalesOrder_model->get($id)->row();
		$data['jamak_detail'] = $this->SalesOrder_model->getDetail($id);

		$data['customer'] = $this->Customer_model->getAll();
		$data['item'] = $this->Item_model->getAll();
		$header['judul'] = 'SalesOrder - Ubah';
		$this->load->view('header', $header);
		$this->load->view('SalesOrder/Edit', $data);
		$this->load->view('footer');
	}
	public function EditDb()
	{
		$data = array(
			'code' => $this->input->post('code'),
			'customer_id' => $this->input->post('customer_id'),
			'date' => $this->input->post('date'),
			'paid' => $this->input->post('paid')
		);
		
		$condition['id'] = $this->input->post('id');
		$this->SalesOrder_model->update($data, $condition);
		$sales_order_id = $this->input->post('id');

		$item_id = $this->input->post('item_id');
		$quantity = $this->input->post('quantity');
		$price = $this->input->post('price');
		for($i=0; $i<count($item_id); $i++){
			if($item_id[$i] != "" && $quantity[$i] != "" && $price[$i] != "" && $this->SalesOrder_model->checkSalesOrderItem($sales_order_id, $item_id[$i])){
				$data = array(
					'sales_order_id' => $sales_order_id,
					'item_id' => $item_id[$i],
					'quantity' => str_replace(',', '', $quantity[$i]),
					'price' => str_replace(',', '', $price[$i])
				);
				//var_dump($data);
				$this->SalesOrder_model->insert('sales_order_detail', $data);
			}
		}
		echo "<script language=\"javascript\">window.open(\"".base_url()."index.php/SalesOrder/StrukPDF/".$sales_order_id."\", \"_blank\");</script>";
		$this->session->set_flashdata('message', 'Sales Order berhasil ditambahkan.');
		redirect('SalesOrder', 'refresh');
	}
	public function Delete($id)
	{
/*
SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE REFERENCED_TABLE_NAME = 'sales_order' AND TABLE_SCHEMA = 'trans_ex';

*/
		$bool_check = false;
		/*$bool_check = $bool_check ?: $this->SalesOrder_model->getTableBy('promo_free_sales_order', array('sales_order_id1' => $id))->num_rows() > 0;
		$bool_check = $bool_check ?: $this->SalesOrder_model->getTableBy('promo_free_sales_order', array('sales_order_id2' => $id))->num_rows() > 0;*/
		if(!$bool_check){
			$this->SalesOrder_model->delete($id);
			$this->session->set_flashdata('message', 'Sales Order berhasil dihapus.');
		}
		else
			$this->session->set_flashdata('message', 'Sales Order sedang digunakan.');
		redirect('SalesOrder');
	}
}
?>