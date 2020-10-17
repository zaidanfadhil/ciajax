<?php

defined('BASEPATH') or exit('No direct script access allowed');

class modelsystem extends CI_Model
{
	// get barang
	public function get_barang()
	{
		$data = $this->db->get('tb_barang');
		return $data->result_array();
	}

	// get id barang
	public function getIdBarang($id_barang)
	{
		$this->db->where("id_barang", $id_barang);
		$query = $this->db->get('tb_barang');
		return $query->result();
	}

	// update barang
	public function update_data($where, $data, $table)
	{
		$this->db->where($where);
		$this->db->update($table, $data);
	}

	// delete barang
	function hapus_data($where, $table)
	{
		$this->db->where($where);
		$this->db->delete($table);
	}
}
