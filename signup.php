<?
		require "navbar.php";
		require_once 'behind/login.bhd.php';
?>
    <main>
		<div>		
			<p></p>
		</div>
			<div class="container-fluid bg-light">
   			 <section class="container">
					<div class="container-page">		
						<div class="col-md-6">
							<h3 class="dark-grey">Registration</h3>
							<?php
								if (isset($_GET['error'])){
									if($_GET['error'] == "invalid_email") {
										echo '<p class="text-danger"> Please enter a valid email</p>';
									}else if($_GET['error'] == "invalid_username") {
										echo '<p class="text-danger"> Please enter a valid username</p>';
									}else if($_GET['error'] == "password_mismatch") {
										echo '<p class="text-danger"> Password Mismatch</p>';
									}else if($_GET['error'] == "emptyfields") {
										echo '<p class="text-danger"> Please enter all fields</p>';
									}
								} else if (isset($_GET['result'])=="success"){
									echo '<p class="text-success"> Registered successfully</p>';
								}
							?>
							<form action="behind/signup.bhd.php" method="post">

							<div class="form-group col-lg-6">
								<label>Email Address</label>
								<input type="text" name="email" class="form-control">
							</div>

							<div class="form-group col-lg-12">
								<label>Name</label>
								<input type="text" name="name" class="form-control">
							</div>

							<div class="form-group col-lg-12">
								<label>Username</label>
								<input type="text" name="username" class="form-control">
							</div>
							
							<div class="form-group col-lg-6">
								<label>Password</label>
								<input type="password" name="password" class="form-control">
							</div>
							
							<div class="form-group col-lg-6">
								<label>Repeat Password</label>
								<input type="password" name="passwordRpt" class="form-control">
							</div>

							<div class="form-group col-lg-6">
							<label>Continent</label>
							<select id="continent" name="continent">
							<option value="America">America</option>
							<option value="Africa">Africa</option>
							<option value="Asia">Asia</option>
							<option value="Europe">Europe</option>
							<option value="Antarctica">Antarctica</option>
							<option value="Oceania">Oceania</option>
							</select>
							</div>

							<div class="form-group col-lg-6">
							<label>Age Group</label>
							<select id="age" name="age">
							<option value="10s">10s</option>
							<option value="20s">20s</option>
							<option value="30s">30s</option>
							<option value="40s">40s</option>
							<option value="50s">50s</option>
							<option value="60s">60s</option>
							<option value="70s">70s</option>
							<option value="80s">80s</option>
							<option value="90s">90s</option>
							</select>
							</div>
					
						<div class="form-group col-md-6">
							<button type="submit" name="createCustomer" class="btn btn-secondary">Register</button>
						</div>

						</form>	
					</div>
				</section>
				<div>		
    </main>

