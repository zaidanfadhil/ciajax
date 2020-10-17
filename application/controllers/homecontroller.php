<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HomeController extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('modelsystem');
	}

	public function index()
	{
		$data['title'] = "CRUD AJAX";
		$this->load->view('tableajax', $data);
	}

	// function untuk menampilkan data
	public function dataBarang()
	{
		$databarang = $this->modelsystem->get_barang();
		foreach ($databarang as $value) {
			$tbody = array();
			$tbody[] = $value['id_barang'];
			$tbody[] = $value['nama_barang'];
			$tbody[] = $value['tanggal_upload'];
			$harga = "Rp." . $value['harga_awal'];
			$tbody[] = $harga;
			$tbody[] = $value['deskripsi_barang'];
			$tbody[] = $value['kategori_barang'];
			$img = "<img style='width: 100%;' src='http://localhost/ciajax/asset/fotobarang/" . $value['foto_barang'] . "' ?>";
			$tbody[] = $img;
			$tbody[] = $value['status'];
			$btn = "<button type='button' class='btn btn-primary btn-icon-split editbtn' name='editbtn' data-toggle='modal' id=" . $value['id_barang'] . " 	style='padding-right: 6%;'>
						<span class='icon text-white'>
							<i class='fas fa-edit'></i>
						</span>
						<span class='text'>Edit Data</span>
						</button>
						<button type='button' data-toggle='modal' name='deletebtn' id=" . $value['id_barang'] . " class='btn btn-danger btn-icon-split mt-2 deletebtn'>
							<span class='icon text-white'>
								<i class='fas fa-trash'></i>
							</span>
							<span class='text'>Delete Data</span>
						</button>";
			$tbody[] = $btn;
			$data[] = $tbody;
		}
		if ($databarang) {
			echo json_encode(array('data' => $data));
		} else {
			echo json_encode(array('data' => 0));
		}
	}

	// function upload image
	public function upload_image()
	{
		if (isset($_FILES['user_image'])) {
			$extension = explode('.', $_FILES['user_image']['name']);
			$new_name = rand() . '.' . $extension[1];
			$destination = './asset/fotobarang/' . $new_name;
			move_uploaded_file($_FILES['user_image']['tmp_name'], $destination);
			return $new_name;
		}
	}

	// function add data
	public function addData()
	{
		if ($_POST["action"] == "Add") {
			$data = array(
				'id_barang' => "",
				'nama_barang' => $this->input->post('namabarang'),
				'harga_awal' => $this->input->post('hargabarang'),
				'deskripsi_barang' => $this->input->post('deskripsiitem'),
				'kategori_barang' => $this->input->post('kategoriitem'),
				'foto_barang' => $this->upload_image(),
				'status' => $this->input->post('status')
			);
			$this->db->set('tanggal_upload', 'NOW()', FALSE);
			$this->db->insert('tb_barang', $data);
			echo 'Data Inserted';
		}
	}

	// function get id barang
	public function getIdBarang()
	{
		$output = array();
		$data = $this->modelsystem->getIdBarang($_POST["id_barang"]);
		foreach ($data as $row) {
			$output['nama_barang'] = $row->nama_barang;
			$output['harga_awal'] = $row->harga_awal;
			$output['deskripsi_barang'] = $row->deskripsi_barang;
			$output['kategori_barang'] = $row->kategori_barang;
			if ($row->foto_barang != '') {
				$output['foto_barang'] = '<img style="width: 100%;" src="' . base_url() . 'asset/fotobarang/' . $row->foto_barang . '" /><input type="hidden" name="hidden_barang_image" value="' . $row->foto_barang . '"/>';
			} else {
				$output['foto_barang'] = '<input type="hidden" name="hidden_barang_image" value=""/>';
			}
			$output['status'] = $row->status;
		}
		echo json_encode($output);
	}

	// function edit barang
	public function editData()
	{
		if ($_POST["action"] == "Edit") {
			$idbarang = $this->input->post('id_barang');
			$data = array(
				'nama_barang' => $this->input->post('namabarang'),
				'harga_awal' => $this->input->post('hargabarang'),
				'deskripsi_barang' => $this->input->post('deskripsiitem'),
				'kategori_barang' => $this->input->post('kategoriitem'),
				'foto_barang' => $this->upload_image(),
				'status' => $this->input->post('status')
			);

			$where = array(
				'id_barang' => $idbarang
			);

			$this->db->set('tanggal_upload', 'NOW()', FALSE);
			$this->modelsystem->update_data($where, $data, 'tb_barang');
			echo 'Data Updated';
		}
	}

	// function delete barang
	public function deleteBarang()
	{
		$idbarang = $_POST['id_barang'];
		$where = array(
			'id_barang' => $idbarang
		);

		$this->modelsystem->hapus_data($where, 'tb_barang');
	}
}
