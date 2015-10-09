<!DOCTYPE html>
<html>
<?php 
	session_start();
	//session_destroy();
	
	print_r($_POST);
	$movie="";
	$day="";
	$time="";
	$reservation="";
	
	if(isset($_GET['deletekey']) && !empty($_GET['deletekey']))
	{
		if($_GET['deletekey'] == "all")
		{
			unset($_SESSION);
			session_destroy();
		}
		else
			unset($_SESSION['cart']['screenings'][(int) $_GET['deletekey']]);
	}
	
	if(isset($_POST) && !empty($_POST))
	{
		//movie details
		if(isset($_POST['movie'] ) && !empty($_POST['movie'] ) )
		if(isset($_POST['movie'] ) && !empty($_POST['movie'] ) )
		{
			$movie = $_POST['movie'];
		}
		if(isset($_POST['day'] ) && !empty($_POST['day'] ) )
		{
			$day = $_POST['day'];
		}
		if(isset($_POST['time'] ) && !empty($_POST['time'] ) )
		{
			$time = $_POST['time'];
		}
		
		function calcPrice($movie, $day, $time, $ticket)
		{
				if($day == 'Monday' || $day == 'Tuesday'
				   || ($day == 'Wednesday' && $time = "01:00 PM") 
				   || ($day == 'Thursday' && $time = "01:00 PM") 
				   || ($day == 'Friday' && $time = "01:00 PM"))
				{
					if($ticket == 'SA')
					{
						$price = (double) 12.00;
						return $price;
					}
					if($ticket == 'SP')
					{
						$price = (double) 10.00;
						return $price;
					}
					if($ticket == 'SC')
					{
						$price = (double) 8.00;
						return $price;
					}
					if($ticket == 'FA')
					{
						$price = (double) 25.00;
						return $price;
					}
					if($ticket == 'FC' || $ticket == 'B1' || $ticket == 'B2' || $ticket == 'B3')
					{
						$price = (double) 20.00;
						return $price;
					}
				}
			elseIf($day == 'Saturday' || $day == 'Sunday' 
			       || $day == 'Wednesday' || $day == 'Thursday' 
				   || $day == 'Friday')
			{
				if($ticket == 'SA')
					{
						$price = (double) 18.00;
						return $price;
					}
					if($ticket == 'SP')
					{
						$price = (double) 15.00;
						return $price;
					}
					if($ticket == 'SC')
					{
						$price = (double) 12.00;
						return $price;
					}
					if($ticket == 'FC')
					{
						$price = (double) 25.00;
						return $price;
					}
					if($ticket == 'FA' || $ticket == 'B1' || $ticket == 'B2' || $ticket == 'B3')
					{
						$price = (double) 30.00;
						return $price;
					}
			}
			
		}
		//seats
		$seats = array();
		if(isset($_POST['SA'] ) && !empty($_POST['SA'] ) && $_POST['SA'] != 0)
		{
			$seats['SA']['quantity'] = (int) $_POST['SA']; 
			$seats['SA']['price'] = calcPrice($movie, $day, $time, "SA") ;
			
		}
		if(isset($_POST['SP'] ) && !empty($_POST['SP'] ) && $_POST['SP'] != 0)
		{
			$seats['SP']['quantity'] = (int) $_POST['SP']; 
			$seats['SP']['price'] = calcPrice($movie, $day, $time, "SP"); 
			
		}
		if(isset($_POST['SC'] ) && !empty($_POST['SC'] ) && $_POST['SC'] != 0)
		{
			$seats['SC']['quantity'] = (int) $_POST['SC']; 
			$seats['SC']['price'] = calcPrice($movie, $day, $time, "SC"); 
			
		}
		if(isset($_POST['FA'] ) && !empty($_POST['FA'] ) && $_POST['FA'] != 0)
		{
			$seats['FA']['quantity'] = (int) $_POST['FA']; 
			$seats['FA']['price'] = calcPrice($movie, $day, $time, "FA") ; 
		}
		if(isset($_POST['FC'] ) && !empty($_POST['FC'] ) && $_POST['FC'] != 0)
		{
			$seats['FC']['quantity'] = (int) $_POST['FC']; 
			$seats['FC']['price'] = calcPrice($movie, $day, $time, "FC"); 
		}
		if(isset($_POST['B1'] ) && !empty($_POST['B1'] ) && $_POST['B1'] != 0)
		{
			$seats['B1']['quantity'] = (int) $_POST['B1']; 
			$seats['B1']['price'] = calcPrice($movie, $day, $time, "B1") ; 
		}
		if(isset($_POST['B2'] ) && !empty($_POST['B2'] ) && $_POST['B2'] != 0)
		{
			$seats['B2']['quantity'] = (int) $_POST['B2']; 
			$seats['B2']['price'] = calcPrice($movie, $day, $time, "B2"); 
		}
		if(isset($_POST['B3'] ) && !empty($_POST['B3'] ) && $_POST['B3'] != 0)
		{
			$seats['B3']['quantity'] = (int) $_POST['B3']; 
			$seats['B3']['price'] = calcPrice($movie, $day, $time, "B3") ; 
		}
		$subtotal = (double) 0;
		$total = (double) 0;
		foreach($seats as $value)
		{
			$subtotal += $value['price'] * $value['quantity'];
			$total += $subtotal;
		}
		
		$reservation = array('movie'=>$movie,
		                     'day'=>$day, 
							 'time'=>$time,
							 'seats'=>$seats,
							 'subtotal' => $subtotal);
		$loop = (int) 0;
		if(isset($_SESSION) && !empty($_SESSION))
		{
			foreach($_SESSION['cart']['screenings'] as $key => $value)
			{
				echo"<p>".$value['movie']."</p>";
				echo"<p>".$reservation['movie']."</p>";
				if($value['movie'] == $reservation['movie'] 
				&& $value['day'] == $reservation['day'] 
				&& $value['time'] == $reservation['time'] )
				{
					$_SESSION['cart']['screenings'][$key] = $reservation;
					++$loop;
				}
			}
			echo "<pre>";
		print_r($_SESSION);
		echo "</pre>";
		}
		if($loop == (int)0)
		{
		$_SESSION['cart']['screenings'][] = $reservation;
		}
	
	}
	
	if(isset($_SESSION) && !empty($_SESSION))
	{
		echo '
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

			<script>
				$(document).ready(function(){
					document.getElementById("empty").onclick = function(){ location.href="cart.php?deletekey=all" ;};
				});
				
			</script>
			';
	}
	
	$cssFile = "css/shoppingCart.css";
	require_once ("includes/header.php");
?>


	<div id="main">
		<div id="subheader">
		  <h2>Your Shopping Cart</h2>
		</div>
		  <?php
		  if(isset($_SESSION) && !empty($_SESSION))
			{
				$url = "https://".$_SERVER['SERVER_NAME']."/~e54061/wp/moviesJSON.php";
				$content = file_get_contents($url);
				$movies = json_decode($content, true);
				foreach($_SESSION['cart']['screenings'] as $key => $value)
				{
					echo "
					<div class='moviebox'>
						<h2>".$movies[$value['movie']]['title']."</h2>
						<h6> Showing at ".$value['day'].", ".$value['time']."</h6>
					
						<div class='category'>
							<h3>Ticket type</h3>
							<ul>";
						foreach($value['seats'] as $type => $seatArray)
						{
							if($type == "SA")
								echo "<li>Standard Adult</li>";
							if($type == "SP")
								echo "<li>Standard Concession</li>";
							if($type == "SC")
								echo "<li>Standard Child</li>";
							if($type == "FA")
								echo "<li>First Class Adult</li>";
							if($type == "FC")
								echo "<li>First Class Child</li>";
							if($type == "B1")
								echo "<li>Beanbag 1</li>";
							if($type == "B2")
								echo "<li>Beanbag 2</li>";
							if($type == "B3")
								echo "<li>Beanbag 3</li>";
						}
						echo "
							</ul>
						</div>
						<div class='category'>
							<h3>Cost</h3>
							<ul>
						";
						foreach($value['seats'] as $seat)
						{
							echo "<li> $".sprintf('%0.2f', $seat['price'])."</li>";
						}
						echo "
							</ul>
						</div>
						<div class='category'>
							<h3>Quantity</h3>
							<ul>
						";
						foreach($value['seats'] as $seat)
						{
							echo "<li>".$seat['quantity']."</li>";
						}
						echo "
							</ul>
						</div>
						<div class='category'>
							<h3>Ticket Subtotal</h3>
							<ul>
						";
						foreach($value['seats'] as $qty)
						{
							echo "<li> $".sprintf('%0.2f',$qty['quantity'] * $qty['price'])."</li>";
						}
						echo "
							</ul>
						</div>
						<h3>Subtotal: $<span class='subtotal'>".$value['subtotal']."</span></h3>
						<a href='cart.php?deletekey=".$key."'>Delete from Cart</a>
					</div>
						";
				}
				echo "
				<div id='checkoutbox'>
					<div id='infobox'>
						<div id='label'>
							<h6>TOTAL:</h6>";
				if(isset($_SESSION['voucher']) && !empty($_SESSION['voucher']))
				{
					echo"
							<h6>Meal and Movie Deal Voucher (12345-67890-ZI):</h6>
							<h3>GRAND TOTAL:</h3>";
				}
				echo
						"
						</div>
						<div id='value'>
							<h6>$119.00</h6>";
				if(isset($_SESSION['voucher']) && !empty($_SESSION['voucher']))
				{
					echo"
							<h6>20%</h6>
							<h3><span id='grandtotal'>$95.20</h3>";
				}
				echo"
						</div>
					</div>
					";
				if(!isset($_SESSION['voucher']) && empty($_SESSION['voucher']))
				{
					echo"
					<div id='voucherbox'>
						<h6>VOUCHER CODE:</h6>
						<form action='cart.php' method='post'>
							<input type='text' name='voucher' id='voucher'>
							<input type ='submit' value='Apply' id='apply'>
						</form>
					</div>";
				}
				echo"
					<div id='buttons'>
						<button type='button'>Checkout</button>
						<button type='button' id='empty'>Empty Cart</button>
					</div>
				</div>
				";
			}
				else {
					echo "
						<div class='moviebox'>
							<h2>Shopping cart is empty! </h2>
						</div>
					";
				}
		  ?>
		
	</div>


<?php require_once("includes/footer.php"); ?>

</html>
















