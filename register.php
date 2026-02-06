<?php
include("header.php");
include("database.php");
?>
<style>
body {
	min-height: 100vh;
	background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
	background-attachment: fixed;
}
.register-container {
	display: flex;
	align-items: center;
	justify-content: center;
	min-height: 90vh;
}
.card.register-card {
	width: 100%;
	max-width: 520px;
	border-radius: 1.5rem;
	box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
	padding: 2.5rem 2.5rem 2rem 2.5rem;
	margin: 2rem 0;
	background: #fff;
	border: none;
	animation: fadeIn 1s;
}
@keyframes fadeIn {
	from { opacity: 0; transform: translateY(40px); }
	to { opacity: 1; transform: translateY(0); }
}
.register-card .card-title {
	font-size: 2rem;
	font-weight: 700;
	color: #2c5364;
	margin-bottom: 0.5rem;
}
.form-floating > label, .form-label {
	color: #2c5364;
	font-weight: 500;
}
.form-control, .form-select {
	border-radius: 1.2rem;
	border: 1.5px solid #b0bec5;
	font-size: 1.1rem;
	background-color: #f8fafc;
	box-shadow: 0 2px 8px rgba(44,83,100,0.04);
	margin-bottom: 1.2rem;
	transition: border-color 0.2s, box-shadow 0.2s;
}
.form-control:focus, .form-select:focus {
	border-color: #2c5364;
	box-shadow: 0 0 0 2px #2c536433;
	background-color: #fff;
}
.register-btn {
	width: 100%;
	padding: 0.75rem;
	border-radius: 1.2rem;
	background: linear-gradient(90deg, #0f2027 0%, #2c5364 100%);
	color: #fff;
	font-size: 1.2rem;
	font-weight: 600;
	border: none;
	box-shadow: 0 2px 8px rgba(44,83,100,0.08);
	transition: background 0.2s, box-shadow 0.2s;
}
.register-btn:hover {
	background: linear-gradient(90deg, #2c5364 0%, #0f2027 100%);
	box-shadow: 0 4px 16px rgba(44,83,100,0.12);
}
.danger.text-danger, .success.text-success {
	background: #ffeaea;
	border-radius: 0.7rem;
	padding: 0.7rem 1rem;
	margin-bottom: 1rem;
	color: #c0392b;
	font-weight: 500;
	text-align: center;
}
.success.text-success {
	background: #eaffea;
	color: #27ae60;
}
.register-logo {
	display: block;
	margin: 0 auto 1.2rem auto;
	width: 80px;
	height: 80px;
	object-fit: contain;
	border-radius: 50%;
	box-shadow: 0 2px 8px rgba(44,83,100,0.10);
}
</style>
<div class="register-container">
	<div class="card register-card">
		<img src="naf.png" alt="Logo" class="register-logo" />
		<h1 class="card-title text-center mb-3">Vehicle Registration</h1>
		<?php
		if (isset($_GET['usser'])) {
			$error = $_GET['usser'];
			if ($error == "exist") {
				echo '<div class="danger text-danger">User already registered</div>';
			} elseif ($error == "size") {
				echo '<div class="danger text-danger">File size too large</div>';
			} elseif ($error == "type") {
				echo '<div class="danger text-danger">File type not supported</div>';
			} elseif ($error == "success") {
				echo '<div class="success text-success text-center">Car successfully added</div>';
			}
		}
		?>
		<form method="POST" action="userreg.php" enctype="multipart/form-data" autocomplete="off">
			<div class="form-floating mb-3">
				<input type="text" class="form-control" id="vech_no" name="vech_no" placeholder="Vehicle Number" required>
				<label for="vech_no">Vehicle Number</label>
			</div>
			<div class="form-floating mb-3">
				<input type="text" class="form-control" id="vech_name" name="vech_name" placeholder="Vehicle Name" required>
				<label for="vech_name">Vehicle Name</label>
			</div>
			<div class="form-floating mb-3">
				<input type="text" class="form-control" id="vech_col" name="vech_col" placeholder="Color" required>
				<label for="vech_col">Vehicle Color</label>
			</div>
			<div class="mb-3">
				<label for="vec_cat" class="form-label">Category</label>
				<select class="form-select" id="vec_cat" name="vec_cat" required>
					<option value="Salon">Salon</option>
					<option value="Wagon">Wagon</option>
					<option value="Bus">Bus</option>
					<option value="Truck">Truck</option>
				</select>
			</div>
			<div class="mb-3">
				<label for="description" class="form-label">Vehicle Description</label>
				<textarea name="description" id="description" rows="4" class="form-control" placeholder="Enter description about vehicle here" required></textarea>
			</div>
			<div class="mb-4">
				<label for="file" class="form-label">Vehicle Image</label>
				<input type="file" name="file" id="file" class="form-control" required>
			</div>
			<button class="register-btn" type="submit" name="submit">Register Car</button>
		</form>
	<script src="bootstrap.bundle.min.js"></script>
	<script src="js/scripts.js"></script>
