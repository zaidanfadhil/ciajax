<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $title ?></title>
	<!-- CSS -->
	<!-- <link href="<?php echo base_url('asset/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css"> -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link href="<?= base_url('asset/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.28.1/sweetalert2.css">
</head>

<body>

	<div class="container" style="margin-top: 5%;">
		<a class="btn btn-success btn-icon-split addbtn" style="margin-bottom: 2%; float: right;">
			<i class="fas fa-plus fa-sm fa-fw" style="color: white;">
			</i> Add Data
		</a>
		<div class="table-responsive">
			<table class="table table-bordered text-center" cellspacing="0" width="100%" id="dataLelang">
				<thead class="bg-primary text-white">
					<tr>
						<th>Id Menu</th>
						<th>Nama Menu</th>
						<th>Tanggal Upload</th>
						<th>Harga Awal</th>
						<th>Deskripsi Menu</th>
						<th>Kategori Menu</th>
						<th>Foto Menu</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
	
	<script src="<?= base_url('asset/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('asset/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('asset/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('asset/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.28.1/sweetalert2.all.min.js"></script>

<script>


	$(document).ready(function() {
		// Ini untuk munculin data
		var datalelang = $('#dataLelang').DataTable({
			"processing": true,
			"ajax": "<?= base_url("index.php/homecontroller/dataBarang") ?>",
			"order": [],
		});

		// show modal
		$('.addbtn').on('click', function() {

			$('#addModal').modal('show');

		});

		// add function
		$(document).on('submit', '#formtambah', function(event) {
			event.preventDefault();
			var namabarang = $('#nmabarang').val();
			var hargabarang = $('#hrgbarang').val();
			var deskripsiitem = $('#deskitem').val();
			var kategoriitem = $('#ktgritem').val();
			var extension = $('#user_image').val().split('.').pop().toLowerCase();
			if (jQuery.inArray(extension, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
				alert("Invalid Image");
				$('#user_image').val('');
				return false;
			}

			if (namabarang != '' && hargabarang != '' && deskripsiitem != '' && kategoriitem != '') {
				$.ajax({
					type: "post",
					url: "<?= base_url("index.php/homecontroller/addData") ?>",
					beforeSend: function() {
						swal({
							title: 'Menunggu',
							html: 'Memproses data',
							
							onOpen: () => {
								swal.showLoading()
							}
							
						})
						console.log("error");
					},
					
					
					data: new FormData(this),
					contentType: false,
					processData: false,
					success: function() {
						swal({
							type: 'success',
							title: 'Tambah Barang',
							text: 'Anda Berhasil Menambah Barang'
						})
						$('#formtambah')[0].reset();
						$('#addModal').modal('hide');
						datalelang.ajax.reload(null, false);
					},
				});
			} else {
				Swal.fire({
					type: 'error',
					title: 'Oops...',
					text: 'Bother fields are required!',
				});
			}
		});

		// edit function
		$(document).on('submit', '#formedit', function(event) {
			event.preventDefault();
			var namabarang = $('#namabarang').val();
			var hargabarang = $('#hargabarang').val();
			var deskripsiitem = $('#deskripsiitem').val();
			var kategoriitem = $('#kategoriitem').val();
			var status = $('#status').val();
			var extension = $('#image').val().split('.').pop().toLowerCase();
			if (jQuery.inArray(extension, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
				alert("Invalid Image");
				$('#image').val('');
				return false;
			}

			if (namabarang != '' && hargabarang != '' && deskripsiitem != '' && kategoriitem != '' && status != '') {
				$.ajax({
					type: "post",
					url: "<?= base_url("index.php/homecontroller/editData") ?>",
					beforeSend: function() {
						swal({
							title: 'Menunggu',
							html: 'Memproses data',
							onOpen: () => {
								swal.showLoading()
							}
						})
					},
					data: new FormData(this),
					contentType: false,
					processData: false,
					success: function() {
						swal({
							type: 'success',
							title: 'Edit Barang',
							text: 'Anda Berhasil Mengedit Barang'
						})
						$('#formedit')[0].reset();
						$('#editModal').modal('hide');
						datalelang.ajax.reload(null, false);
					},
					error: function(xhr, ajaxOptions, thrownError) {
						console.log(xhr.responseText);
					}
				});
			} else {
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'Bother fields are required!',
				});
			}
		});

		// get barang
		$(document).on('click', '.editbtn', function() {
			var id_barang = $(this).attr("id");
			$.ajax({
				url: "<?= base_url("index.php/homecontroller/getIdBarang") ?>",
				type: "post",
				data: {
					id_barang: id_barang
				},
				dataType: "JSON",
				success: function(data) {
					$('#editModal').modal('show');
					$('#namabarang').val(data.nama_barang);
					$('#hargabarang').val(data.harga_awal);
					$('#deskripsiitem').val(data.deskripsi_barang);
					$('#kategoriitem').val(data.kategori_barang);
					$('#status').val(data.status);
					$('#id_barang').val(id_barang);
					$('#foto_barang').html(data.foto_barang);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					console.log(xhr.responseText);
				}
			});
		});

		// delete barang
		$(document).on('click', '.deletebtn', function() {
			var id_barang = $(this).attr("id");
			swal({
				title: 'Konfirmasi',
				text: "Apakah anda yakin ingin menghapus ",
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Hapus',
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				cancelButtonText: 'Tidak',
				reverseButtons: true
			}).then((result) => {
				if (result.value) {
					$.ajax({
						url: "<?= base_url('index.php/homecontroller/deleteBarang') ?>",
						type: "post",
						beforeSend: function() {
							swal({
								title: 'Menunggu',
								html: 'Memproses data',
								onOpen: () => {
									swal.showLoading()
								}
							})
						},
						data: {
							id_barang: id_barang
						},
						success: function(data) {
							swal(
								'Hapus',
								'Berhasil Terhapus',
								'success'
							)
							datalelang.ajax.reload(null, false)
						}
					});
				} else if (result.dismiss === swal.DismissReason.cancel) {
					swal(
						'Batal',
						'Anda membatalkan penghapusan',
						'error'
					)
				};
			});

		});
	});
</script>
</body>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Add Data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="formtambah" method="post">
				<div class="modal-body">
					<div class="form-group">
						<label>Nama menu</label>
						<input type="text" name="namabarang" id="nmabarang" class="form-control" placeholder="Enter Name Goods">
					</div>
					<div class="form-group">
						<label>Harga menu</label>
						<input type="text" name="hargabarang" id="hrgbarang" class="form-control" placeholder="Enter Price Goods">
					</div>
					<div class="form-group">
						<label>Deskripsi menu</label>
						<textarea type="text" name="deskripsiitem" id="deskitem" class="form-control" placeholder="Enter Description"></textarea>
					</div>
					<div class="form-group">
						<label>Kategori menu</label>
						<select class="custom-select drpdw" name="kategoriitem" id="ktgritem">
							<option selected>Select Category</option>
							<option value="Makanan">Makanan</option>
							<option value="Minuman">Minuman</option>
							
						</select>
					</div>
					<div class="form-group">
						<label>Gambar</label>
						<input type="file" name="user_image" id="user_image" class="form-control">
					</div>
					<input value="open" type="hidden" id="statusbarang" name="status" class="form-control">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<input type="hidden" name="action" class="btn btn-success" value="Add" />
					<input type="submit" value="Add" name="action" class="btn btn-success" />
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="formedit" method="post">
				<div class="modal-body">
					<div class="form-group">
						<label>Thumbnail</label>
						<span id="foto_barang"></span>
					</div>
					<div class="form-group">
						<label>Nama menu</label>
						<input type="text" name="namabarang" id="namabarang" class="form-control" placeholder="Enter Name Goods">
					</div>
					<div class="form-group">
						<label>Harga menu</label>
						<input type="text" name="hargabarang" id="hargabarang" class="form-control" placeholder="Enter Price Goods">
					</div>
					<div class="form-group">
						<label>Deskripsi menu</label>
						<textarea type="text" name="deskripsiitem" id="deskripsiitem" class="form-control" placeholder="Enter Description"></textarea>
					</div>
					<div class="form-group">
						<label>kategori menu</label>
						<select class="custom-select drpdw" name="kategoriitem" id="kategoriitem">
							<option selected>Select Category</option>
							<option value="Makanan">Makanan</option>
							<option value="Minuman">Minuman</option>
						</select>
					</div>
					<div class="form-group">
						<label>Gambar</label>
						<input type="file" name="user_image" id="image" class="form-control">
					</div>
					<div class="form-group">
						<label>Status</label>
						<select class="custom-select drpdw" name="status" id="status">
							<option selected>Status</option>
							<option value="open">Open</option>
							<option value="close">Close</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<input type="hidden" name="id_barang" id="id_barang" class="btn btn-success" value="" />
					<input type="hidden" name="action" class="btn btn-success" value="Edit" />
					<input type="submit" value="Edit" name="action" class="btn btn-success" />
				</div>
			</form>
		</div>
	</div>
</div>


<!-- Script -->


</html>
