<?php
	ob_start();
	session_start();
	require_once 'config/connect.php';
	if(!isset($_SESSION['customer']) & empty($_SESSION['customer'])){
		header('location: login.php');
	}
include 'inc/header.php'; 
include 'inc/nav.php'; 
$uid = $_SESSION['customerid'];
$cart = $_SESSION['cart'];

if(isset($_POST) & !empty($_POST)){
	if($_POST['agree'] == true){
		$country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
		$fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING);
		$lname = filter_var($_POST['lname'], FILTER_SANITIZE_STRING);
		$company = filter_var($_POST['company'], FILTER_SANITIZE_STRING);
		$address1 = filter_var($_POST['address1'], FILTER_SANITIZE_STRING);
		$address2 = filter_var($_POST['address2'], FILTER_SANITIZE_STRING);
		$city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
		$state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
		$phone = filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT);
		$payment = filter_var($_POST['payment'], FILTER_SANITIZE_STRING);
		$zip = filter_var($_POST['zipcode'], FILTER_SANITIZE_NUMBER_INT);

		//Checking if user meta exist
		$sql = "SELECT * FROM usersmeta WHERE uid=$uid";
		$res = mysqli_query($connection, $sql);
		$r = mysqli_fetch_assoc($res);
		$count = mysqli_num_rows($res);

		//If user meta found started here
		if($count == 1){
			// If user meta is found update usermeta table

			//update data in usersmeta table
			$usql = "UPDATE usersmeta SET country='$country', firstname='$fname', lastname='$lname', address1='$address1', address2='$address2', city='$city', state='$state',  zip='$zip', company='$company', mobile='$phone' WHERE uid=$uid";
			$ures = mysqli_query($connection, $usql) or die(mysqli_error($connection));
			if($ures){

				$total = 0;
				foreach ($cart as $key => $value) {
					//echo $key . " : " . $value['quantity'] ."<br>";
					$ordsql = "SELECT * FROM products WHERE id=$key";
					$ordres = mysqli_query($connection, $ordsql);
					$ordr = mysqli_fetch_assoc($ordres);

					$total = $total + ($ordr['price']*$value['quantity']);
				}

				 $iosql = "INSERT INTO orders (uid, totalprice, orderstatus, paymentmode) VALUES ('$uid', '$total', 'Order Placed', '$payment')";
				$iores = mysqli_query($connection, $iosql) or die(mysqli_error($connection));
				if($iores){
					//echo "Order inserted, insert order items <br>";
					$orderid = mysqli_insert_id($connection);
					foreach ($cart as $key => $value) {
						//echo $key . " : " . $value['quantity'] ."<br>";
						$ordsql = "SELECT * FROM products WHERE id=$key";
						$ordres = mysqli_query($connection, $ordsql);
						$ordr = mysqli_fetch_assoc($ordres);

						$pid = $ordr['id'];
						$productprice = $ordr['price'];
						$quantity = $value['quantity'];


						$orditmsql = "INSERT INTO orderitems (pid, orderid, productprice, pquantity) VALUES ('$pid', '$orderid', '$productprice', '$quantity')";
						$orditmres = mysqli_query($connection, $orditmsql) or die(mysqli_error($connection));
						//if($orditmres){
							//echo "Order Item inserted redirect to my account page <br>";
						//}
					}
				}
				unset($_SESSION['cart']);
				header("location: my-account.php");
			}
		} //If user meta found submitting query ends here //


			//If user meta not found the submit new meta to new user
			else{
			//insert data in usersmeta table
			$isql = "INSERT INTO usersmeta (country, firstname, lastname, address1, address2, city, state, zip, company, mobile, uid) VALUES ('$country', '$fname', '$lname', '$address1', '$address2', '$city', '$state', '$zip', '$company', '$phone', '$uid')";
			$ires = mysqli_query($connection, $isql) or die(mysqli_error($connection));
			if($ires){

				$total = 0;
				foreach ($cart as $key => $value) {
					//echo $key . " : " . $value['quantity'] ."<br>";
					$ordsql = "SELECT * FROM products WHERE id=$key";
					$ordres = mysqli_query($connection, $ordsql);
					$ordr = mysqli_fetch_assoc($ordres);

					$total = $total + ($ordr['price']*$value['quantity']);
				}

				echo $iosql = "INSERT INTO orders (uid, totalprice, orderstatus, paymentmode) VALUES ('$uid', '$total', 'Order Placed', '$payment')";
				$iores = mysqli_query($connection, $iosql) or die(mysqli_error($connection));
				if($iores){
					//echo "Order inserted, insert order items <br>";
					$orderid = mysqli_insert_id($connection);
					foreach ($cart as $key => $value) {
						//echo $key . " : " . $value['quantity'] ."<br>";
						$ordsql = "SELECT * FROM products WHERE id=$key";
						$ordres = mysqli_query($connection, $ordsql);
						$ordr = mysqli_fetch_assoc($ordres);

						$pid = $ordr['id'];
						$productprice = $ordr['price'];
						$quantity = $value['quantity'];


						$orditmsql = "INSERT INTO orderitems (pid, orderid, productprice, pquantity) VALUES ('$pid', '$orderid', '$productprice', '$quantity')";
						$orditmres = mysqli_query($connection, $orditmsql) or die(mysqli_error($connection));
						//if($orditmres){
							//echo "Order Item inserted redirect to my account page <br>";
						//}
					}
				}
				unset($_SESSION['cart']);
				header("location: my-account.php");
			}

		}// //If user meta not found the submit new meta to new user ends here //
	}

}

$sql = "SELECT * FROM usersmeta WHERE uid=$uid";
$res = mysqli_query($connection, $sql);
$r = mysqli_fetch_assoc($res);
?>

	
	<!-- SHOP CONTENT -->
	<section id="content">
		<div class="content-blog">
					<div class="page_header text-center">
						<h2>Shop - Checkout</h2>
						<p>Get the best kit for smooth shave</p>
					</div>
<form method="post" action="">
<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="billing-details">
						<h3 class="uppercase">Billing Details</h3>
						<div class="space30"></div>
							<label class="">Country </label>
							<select name="country" class="form-control">
				                <option value="Afghanistan">Afghanistan</option>
				                <option value="Åland Islands">Åland Islands</option>
				                <option value="Albania">Albania</option>
				                <option value="Algeria">Algeria</option>
				                <option value="American Samoa">American Samoa</option>
				                <option value="Andorra">Andorra</option>
				                <option value="Angola">Angola</option>
				                <option value="Anguilla">Anguilla</option>
				                <option value="Antarctica">Antarctica</option>
				                <option value="Antigua and Barbuda">Antigua and Barbuda</option>
				                <option value="Argentina">Argentina</option>
				                <option value="Armenia">Armenia</option>
				                <option value="Aruba">Aruba</option>
				                <option value="Australia">Australia</option>
				                <option value="Austria">Austria</option>
				                <option value="Azerbaijan">Azerbaijan</option>
				                <option value="Bahamas">Bahamas</option>
				                <option value="Bahrain">Bahrain</option>
				                <option value="Bangladesh">Bangladesh</option>
				                <option value="Barbados">Barbados</option>
				                <option value="Belarus">Belarus</option>
				                <option value="Belgium">Belgium</option>
				                <option value="Belize">Belize</option>
				                <option value="Benin">Benin</option>
				                <option value="Bermuda">Bermuda</option>
				                <option value="Bhutan">Bhutan</option>
				                <option value="Bolivia">Bolivia</option>
				                <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
				                <option value="Botswana">Botswana</option>
				                <option value="Bouvet Island">Bouvet Island</option>
				                <option value="Brazil">Brazil</option>
				                <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
				                <option value="Brunei Darussalam">Brunei Darussalam</option>
				                <option value="Bulgaria">Bulgaria</option>
				                <option value="Burkina Faso">Burkina Faso</option>
				                <option value="Burundi">Burundi</option>
				                <option value="Cambodia">Cambodia</option>
				                <option value="Cameroon">Cameroon</option>
				                <option value="Canada">Canada</option>
				                <option value="Cape Verde">Cape Verde</option>
				                <option value="Cayman Islands">Cayman Islands</option>
				                <option value="Central African Republic">Central African Republic</option>
				                <option value="Chad">Chad</option>
				                <option value="Chile">Chile</option>
				                <option value="China">China</option>
				                <option value="Christmas Island">Christmas Island</option>
				                <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
				                <option value="Colombia">Colombia</option>
				                <option value="Comoros">Comoros</option>
				                <option value="Congo">Congo</option>
				                <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
				                <option value="Cook Islands">Cook Islands</option>
				                <option value="Costa Rica">Costa Rica</option>
				                <option value="Cote D'ivoire">Cote D'ivoire</option>
				                <option value="Croatia">Croatia</option>
				                <option value="Cuba">Cuba</option>
				                <option value="Cyprus">Cyprus</option>
				                <option value="Czech Republic">Czech Republic</option>
				                <option value="Denmark">Denmark</option>
				                <option value="Djibouti">Djibouti</option>
				                <option value="Dominica">Dominica</option>
				                <option value="Dominican Republic">Dominican Republic</option>
				                <option value="Ecuador">Ecuador</option>
				                <option value="Egypt">Egypt</option>
				                <option value="El Salvador">El Salvador</option>
				                <option value="Equatorial Guinea">Equatorial Guinea</option>
				                <option value="Eritrea">Eritrea</option>
				                <option value="Estonia">Estonia</option>
				                <option value="Ethiopia">Ethiopia</option>
				                <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
				                <option value="Faroe Islands">Faroe Islands</option>
				                <option value="Fiji">Fiji</option>
				                <option value="Finland">Finland</option>
				                <option value="France">France</option>
				                <option value="French Guiana">French Guiana</option>
				                <option value="French Polynesia">French Polynesia</option>
				                <option value="French Southern Territories">French Southern Territories</option>
				                <option value="Gabon">Gabon</option>
				                <option value="Gambia">Gambia</option>
				                <option value="Georgia">Georgia</option>
				                <option value="Germany">Germany</option>
				                <option value="Ghana">Ghana</option>
				                <option value="Gibraltar">Gibraltar</option>
				                <option value="Greece">Greece</option>
				                <option value="Greenland">Greenland</option>
				                <option value="Grenada">Grenada</option>
				                <option value="Guadeloupe">Guadeloupe</option>
				                <option value="Guam">Guam</option>
				                <option value="Guatemala">Guatemala</option>
				                <option value="Guernsey">Guernsey</option>
				                <option value="Guinea">Guinea</option>
				                <option value="Guinea-bissau">Guinea-bissau</option>
				                <option value="Guyana">Guyana</option>
				                <option value="Haiti">Haiti</option>
				                <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
				                <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
				                <option value="Honduras">Honduras</option>
				                <option value="Hong Kong">Hong Kong</option>
				                <option value="Hungary">Hungary</option>
				                <option value="Iceland">Iceland</option>
				                <option value="India">India</option>
				                <option value="Indonesia">Indonesia</option>
				                <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
				                <option value="Iraq">Iraq</option>
				                <option value="Ireland">Ireland</option>
				                <option value="Isle of Man">Isle of Man</option>
				                <option value="Israel">Israel</option>
				                <option value="Italy">Italy</option>
				                <option value="Jamaica">Jamaica</option>
				                <option value="Japan">Japan</option>
				                <option value="Jersey">Jersey</option>
				                <option value="Jordan">Jordan</option>
				                <option value="Kazakhstan">Kazakhstan</option>
				                <option value="Kenya">Kenya</option>
				                <option value="Kiribati">Kiribati</option>
				                <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
				                <option value="Korea, Republic of">Korea, Republic of</option>
				                <option value="Kuwait">Kuwait</option>
				                <option value="Kyrgyzstan">Kyrgyzstan</option>
				                <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
				                <option value="Latvia">Latvia</option>
				                <option value="Lebanon">Lebanon</option>
				                <option value="Lesotho">Lesotho</option>
				                <option value="Liberia">Liberia</option>
				                <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
				                <option value="Liechtenstein">Liechtenstein</option>
				                <option value="Lithuania">Lithuania</option>
				                <option value="Luxembourg">Luxembourg</option>
				                <option value="Macao">Macao</option>
				                <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
				                <option value="Madagascar">Madagascar</option>
				                <option value="Malawi">Malawi</option>
				                <option value="Malaysia">Malaysia</option>
				                <option value="Maldives">Maldives</option>
				                <option value="Mali">Mali</option>
				                <option value="Malta">Malta</option>
				                <option value="Marshall Islands">Marshall Islands</option>
				                <option value="Martinique">Martinique</option>
				                <option value="Mauritania">Mauritania</option>
				                <option value="Mauritius">Mauritius</option>
				                <option value="Mayotte">Mayotte</option>
				                <option value="Mexico">Mexico</option>
				                <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
				                <option value="Moldova, Republic of">Moldova, Republic of</option>
				                <option value="Monaco">Monaco</option>
				                <option value="Mongolia">Mongolia</option>
				                <option value="Montenegro">Montenegro</option>
				                <option value="Montserrat">Montserrat</option>
				                <option value="Morocco">Morocco</option>
				                <option value="Mozambique">Mozambique</option>
				                <option value="Myanmar">Myanmar</option>
				                <option value="Namibia">Namibia</option>
				                <option value="Nauru">Nauru</option>
				                <option value="Nepal">Nepal</option>
				                <option value="Netherlands">Netherlands</option>
				                <option value="Netherlands Antilles">Netherlands Antilles</option>
				                <option value="New Caledonia">New Caledonia</option>
				                <option value="New Zealand">New Zealand</option>
				                <option value="Nicaragua">Nicaragua</option>
				                <option value="Niger">Niger</option>
				                <option value="Nigeria">Nigeria</option>
				                <option value="Niue">Niue</option>
				                <option value="Norfolk Island">Norfolk Island</option>
				                <option value="Northern Mariana Islands">Northern Mariana Islands</option>
				                <option value="Norway">Norway</option>
				                <option value="Oman">Oman</option>
				                <option value="Pakistan">Pakistan</option>
				                <option value="Palau">Palau</option>
				                <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
				                <option value="Panama">Panama</option>
				                <option value="Papua New Guinea">Papua New Guinea</option>
				                <option value="Paraguay">Paraguay</option>
				                <option value="Peru">Peru</option>
				                <option value="Philippines">Philippines</option>
				                <option value="Pitcairn">Pitcairn</option>
				                <option value="Poland">Poland</option>
				                <option value="Portugal">Portugal</option>
				                <option value="Puerto Rico">Puerto Rico</option>
				                <option value="Qatar">Qatar</option>
				                <option value="Reunion">Reunion</option>
				                <option value="Romania">Romania</option>
				                <option value="Russian Federation">Russian Federation</option>
				                <option value="Rwanda">Rwanda</option>
				                <option value="Saint Helena">Saint Helena</option>
				                <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
				                <option value="Saint Lucia">Saint Lucia</option>
				                <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
				                <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
				                <option value="Samoa">Samoa</option>
				                <option value="San Marino">San Marino</option>
				                <option value="Sao Tome and Principe">Sao Tome and Principe</option>
				                <option value="Saudi Arabia">Saudi Arabia</option>
				                <option value="Senegal">Senegal</option>
				                <option value="Serbia">Serbia</option>
				                <option value="Seychelles">Seychelles</option>
				                <option value="Sierra Leone">Sierra Leone</option>
				                <option value="Singapore">Singapore</option>
				                <option value="Slovakia">Slovakia</option>
				                <option value="Slovenia">Slovenia</option>
				                <option value="Solomon Islands">Solomon Islands</option>
				                <option value="Somalia">Somalia</option>
				                <option value="South Africa">South Africa</option>
				                <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
				                <option value="Spain">Spain</option>
				                <option value="Sri Lanka">Sri Lanka</option>
				                <option value="Sudan">Sudan</option>
				                <option value="Suriname">Suriname</option>
				                <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
				                <option value="Swaziland">Swaziland</option>
				                <option value="Sweden">Sweden</option>
				                <option value="Switzerland">Switzerland</option>
				                <option value="Syrian Arab Republic">Syrian Arab Republic</option>
				                <option value="Taiwan, Province of China">Taiwan, Province of China</option>
				                <option value="Tajikistan">Tajikistan</option>
				                <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
				                <option value="Thailand">Thailand</option>
				                <option value="Timor-leste">Timor-leste</option>
				                <option value="Togo">Togo</option>
				                <option value="Tokelau">Tokelau</option>
				                <option value="Tonga">Tonga</option>
				                <option value="Trinidad and Tobago">Trinidad and Tobago</option>
				                <option value="Tunisia">Tunisia</option>
				                <option value="Turkey">Turkey</option>
				                <option value="Turkmenistan">Turkmenistan</option>
				                <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
				                <option value="Tuvalu">Tuvalu</option>
				                <option value="Uganda">Uganda</option>
				                <option value="Ukraine">Ukraine</option>
				                <option value="United Arab Emirates">United Arab Emirates</option>
				                <option value="United Kingdom">United Kingdom</option>
				                <option value="United States">United States</option>
				                <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
				                <option value="Uruguay">Uruguay</option>
				                <option value="Uzbekistan">Uzbekistan</option>
				                <option value="Vanuatu">Vanuatu</option>
				                <option value="Venezuela">Venezuela</option>
				                <option value="Viet Nam">Viet Nam</option>
				                <option value="Virgin Islands, British">Virgin Islands, British</option>
				                <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
				                <option value="Wallis and Futuna">Wallis and Futuna</option>
				                <option value="Western Sahara">Western Sahara</option>
				                <option value="Yemen">Yemen</option>
				                <option value="Zambia">Zambia</option>
				                <option value="Zimbabwe">Zimbabwe</option>
							</select>
							<div class="clearfix space20"></div>
							<div class="row">
								<div class="col-md-6">
									<label>First Name </label>
									<input name="fname" class="form-control" placeholder="" value="<?php if(!empty($r['firstname'])){ echo $r['firstname']; } elseif(isset($fname)){ echo $fname; } ?>" type="text">
								</div>
								<div class="col-md-6">
									<label>Last Name </label>
									<input name="lname" class="form-control" placeholder="" value="<?php if(!empty($r['lastname'])){ echo $r['lastname']; }elseif(isset($lname)){ echo $lname; } ?>" type="text">
								</div>
							</div>
							<div class="clearfix space20"></div>
							<label>Company Name</label>
							<input name="company" class="form-control" placeholder="" value="<?php if(!empty($r['company'])){ echo $r['company']; }elseif(isset($company)){ echo $company; } ?>" type="text">
							<div class="clearfix space20"></div>
							<label>Address </label>
							<input name="address1" class="form-control" placeholder="Street address" value="<?php if(!empty($r['address1'])){ echo $r['address1']; } elseif(isset($address1)){ echo $address1; } ?>" type="text">
							<div class="clearfix space20"></div>
							<input name="address2" class="form-control" placeholder="Apartment, suite, unit etc. (optional)" value="<?php if(!empty($r['address2'])){ echo $r['address2']; }elseif(isset($address2)){ echo $address2; } ?>" type="text">
							<div class="clearfix space20"></div>
							<div class="row">
								<div class="col-md-4">
									<label>City </label>
									<input name="city" class="form-control" placeholder="City" value="<?php if(!empty($r['city'])){ echo $r['city']; }elseif(isset($city)){ echo $city; } ?>" type="text">
								</div>
								<div class="col-md-4">
									<label>State</label>
									<input name="state" class="form-control" value="<?php if(!empty($r['state'])){ echo $r['state']; }elseif(isset($state)){ echo $state; } ?>" placeholder="State" type="text">
								</div>
								<div class="col-md-4">
									<label>Postcode </label>
									<input name="zipcode" class="form-control" placeholder="Postcode / Zip" value="<?php if(!empty($r['zip'])){ echo $r['zip']; }elseif(isset($zip)){ echo $zip; } ?>" type="text">
								</div>
							</div>
							<div class="clearfix space20"></div>
							<label>Phone </label>
							<input name="phone" class="form-control" id="billing_phone" placeholder="" value="<?php if(!empty($r['mobile'])){ echo $r['mobile']; }elseif(isset($phone)){ echo $phone; } ?>" type="text">
						
					</div>
				</div>
				
			</div>
			
			<div class="space30"></div>
			<h4 class="heading">Your order</h4>
			
			<table class="table table-bordered extra-padding">
				<tbody>
					<tr>
						<th>Cart Subtotal</th>
						<td><span class="amount">£190.00</span></td>
					</tr>
					<tr>
						<th>Shipping and Handling</th>
						<td>
							Free Shipping				
						</td>
					</tr>
					<tr>
						<th>Order Total</th>
						<td><strong><span class="amount">£190.00</span></strong> </td>
					</tr>
				</tbody>
			</table>
			
			<div class="clearfix space30"></div>
			<h4 class="heading">Payment Method</h4>
			<div class="clearfix space20"></div>
			
			<div class="payment-method">
				<div class="row">
					
						<!-- <div class="col-md-4">
							<input name="payment" id="radio1" class="css-checkbox" type="radio" value="cod"><span>Cash On Delivery</span>
							<div class="space20"></div>
							<p>Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won't be shipped until the funds have cleared in our account.</p>
						</div>
						<div class="col-md-4">
							<input name="payment" id="radio2" class="css-checkbox" type="radio"><span value="cheque">Cheque Payment</span>
							<div class="space20"></div>
							<p>Please send your cheque to BLVCK Fashion House, Oatland Rood, UK, LS71JR</p>
						</div> -->
						<div class="col-md-4">
							<input name="payment" id="radio3" class="css-checkbox" type="radio"><span value="paypal">PayStack</span>
							<div class="space20"></div>
							<p>Pay via PayStack; you can pay with your credit card.</p>
						</div>
					
				</div>
				<div class="space30"></div>
				
					<input name="agree" id="checkboxG2" class="css-checkbox" type="checkbox" value="true"><span>I've read and accept the <a href="#">terms &amp; conditions</a></span>
				
				<div class="space30"></div>
				<input type="submit" class="button btn-lg" value="Pay Now">
			</div>
		</div>		
</form>		
		</div>
	</section>
	
<?php include 'inc/footer.php' ?>
