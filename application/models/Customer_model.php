<?php
class Customer_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}
	
	function getAll()
	{
		$this->db->order_by('name asc');
		$this->db->select("customer.*");
		$this->db->from('customer');
		
		return $this->db->get();
	}
	
	function get($id)
	{
		$this->db->where('customer.id', $id);
		$this->db->select("customer.*");
		$this->db->from("customer");
		
		return $this->db->get();
	}

	function getTableBy($table, $data)
	{
		$this->db->where($data);
		$this->db->select("*");
		$this->db->from($table);
		
		return $this->db->get();
	}
	
	function getByName($name)
	{
		$this->db->where('name', $name);
		$this->db->select("*");
		$this->db->from("customer");
		
		return $this->db->get();
	}

	function insert($data)
	{
		$this->db->insert('customer', $data);
		$insert_id = $this->db->insert_id();

		return $insert_id;
	}

	function update($data, $condition)
	{
		$this->db->where($condition);
		$this->db->update('customer', $data);
	}
	
	function delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('customer');
	}
}
?>