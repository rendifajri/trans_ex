<?php
class SalesOrder_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}
	
	function getAll()
	{
		$this->db->group_by('sales_order.id');
		$this->db->order_by('sales_order.date desc, sales_order.code desc');
		$this->db->select("sales_order.*, customer.name customer_name, SUM(price * quantity) total");
		$this->db->from('sales_order');
		$this->db->join('sales_order_detail', 'sales_order.id = sales_order_detail.sales_order_id', 'left');
		$this->db->join('customer', 'sales_order.customer_id = customer.id', 'left');
		
		return $this->db->get();
	}
	
	function get($id)
	{
		$this->db->where('id', $id);
		$this->db->select("*");
		$this->db->from("sales_order");
		
		return $this->db->get();
	}

	function getDetail($id)
	{
		$this->db->order_by('id');
		$this->db->where('sales_order.id', $id);
		$this->db->select("sales_order_detail.*, date, name");
		$this->db->from('sales_order');
		$this->db->join('sales_order_detail', 'sales_order.id = sales_order_detail.sales_order_id', 'left');
		$this->db->join('item', 'item.id = sales_order_detail.item_id', 'left');
		
		return $this->db->get();
	}
	
	function getByCode($code)
	{
		$this->db->where('code', $code);
		$this->db->select("*");
		$this->db->from("sales_order");
		
		return $this->db->get();
	}
	
	function getLastCode()
	{
		$this->db->where("code LIKE 'SO%'", null, false);
		$this->db->select("CONCAT('SO', LPAD(SUBSTRING(IFNULL(MAX(code), 0), 3, 3)+1, 3, 0)) code", false);
		$this->db->from("sales_order");
		
		return $this->db->get()->row()->code;
	}

	function getTableBy($table, $data)
	{
		$this->db->order_by('id');
		$this->db->where($data);
		$this->db->select("*");
		$this->db->from($table);
		
		return $this->db->get();
	}
	
	function checkSalesOrderItem($sales_order_id, $item_id)
	{
		$this->db->where('sales_order_id', $sales_order_id);
		$this->db->where('item_id', $item_id);
		$this->db->select("*");
		$this->db->from("sales_order_detail");
		
		return $this->db->get()->num_rows() == 0;
	}

	function insert($table, $data)
	{
		$this->db->insert($table, $data);
		$insert_id = $this->db->insert_id();

		return $insert_id;
	}

	function update($data, $condition)
	{
		$this->db->where($condition);
		$this->db->update('sales_order', $data);
	}

	function delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('sales_order');
	}

	function deleteTable($table, $id)
	{
		$this->db->where('sales_order_id', $id);
		$this->db->delete($table);
	}
}
?>