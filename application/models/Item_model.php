<?php
class Item_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}
	
	function getAll()
	{
		$this->db->order_by('name asc');
		$this->db->select("*");
		$this->db->from('item');
		
		return $this->db->get();
	}
	
	function get($id)
	{
		$this->db->where('item.id', $id);
		$this->db->select("*");
		$this->db->from("item");
		
		return $this->db->get();
	}
	
	function getByBarcode($barcode)
	{
		$this->db->where('barcode', $barcode);
		$this->db->select("*");
		$this->db->from("item");
		
		return $this->db->get();
	}

	function getTableBy($table, $data)
	{
		$this->db->where($data);
		$this->db->select("*");
		$this->db->from($table);
		
		return $this->db->get();
	}

	function insert($data)
	{
		$this->db->insert('item', $data);
		$insert_id = $this->db->insert_id();

		return $insert_id;
	}

	function update($data, $condition)
	{
		$this->db->where($condition);
		$this->db->update('item', $data);
	}
	
	function delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('item');
	}
}
?>