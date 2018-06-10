<?php
	 require("lib/base.php");
	 $base_obj = new base();	 
	
	if($_POST['cus_name']!="" && $_POST['phone_no']!="" && $_POST['order_no']!="" && $_POST['scheduled_date']!=""){
		$insert_sql= "insert into  xpo_delivery_details ( Phone_No, Customer_Name, Order_No, schd_date,  created_date) values('".$_POST['phone_no']."', '".$_POST['cus_name']."', '".$_POST['order_no']."', '".$_POST['scheduled_date']."', DATE_ADD(now(), INTERVAL '5:30' HOUR_MINUTE))";
		
	   $base_obj->_Exe_Query($insert_sql);
	}
	
	$sql = "select xpo_id,Phone_No, Customer_Name, Order_No, schd_date, rescheduled_date, comments,  created_date, last_update,call_status from  xpo_delivery_details order by xpo_id";
	 $data = $base_obj->_Fetch_Data($sql);
	 
?>

<!DOCTYPE html>
<html>


<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>XPO</title>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
	<script type="text/javascript" src="main.js?v=1.1"></script>
	<style>
		#dataTable thead {
			background-color: #777;
			color: #fff;
		}

		#dataTable td,
		th {
			padding: 7px 5px !important;
		}

		nav {
			height: 50px !important;
			line-height: 50px !important;
		}

		#myInput {
			background-image: url('assets/searchicon.png');
			/* Add a search icon to input */
			background-position: 10px 12px;
			/* Position the search icon */
			background-repeat: no-repeat;
			/* Do not repeat the icon image */
			width: calc(100% - 42px);
			/* Full-width */
			font-size: 16px;
			/* Increase font-size */
			padding: 3px 0px 3px 40px;
			/* Add some padding */
			border: 1px solid #ddd;
			/* Add a grey border */
			margin-bottom: 12px;
			/* Add some space below the input */
		}
	</style>


</head>

<body>
	<div class="navbar-fixed ">
		<nav>
			<div class="nav-wrapper orange darken-4 z-depth-3">
				<a href="#!" class="brand-logo">&nbsp XPO</a>
			</div>
		</nav>
	</div>

	<div class="row">
		<div class="col s12 ">
			<div class="card-panel">
				<form method="POST" action="home.php">
					<div class="row">
						<div class="input-field col s3">
							<input id="cus_name" name="cus_name" Placeholder="Enter Customer Name" type="text" class="validate">
							<label for="cus_name">Customer Name</label>
						</div>
						<div class="input-field col s3">
							<input id="phone_no" name="phone_no" Placeholder="Enter Phone Number" type="text" class="validate">
							<label for="phone_no">Phone Number</label>
						</div>
						<div class="input-field col s3">
							<input id="order_no" name="order_no" Placeholder="Enter Order Number" type="text" class="validate">
							<label for="order_no">Order Number</label>
						</div>
						<div class="input-field col s3">
							<input id="scheduled_date" name="scheduled_date" Placeholder="Enter Scheduled Date" type="text" class="datepicker">
							<label for="scheduled_date">Scheduled Date</label>
						</div>
					</div>

					<button type="submit" class="btn-smalll waves-effect waves-light btn">
						<i class="material-icons left">library_add</i>ADD</button>

				</form>
			</div>


			<div style="width:100%;margin-top:15px;">
				<input type="text" id="myInput" onkeyup="searchFunction()" placeholder="Search by Customer Name">
			</div>
			<div id="table_view" style=" width:100%;">
				<table cellpadding="0" cellspacing="0" border="1" class="display responsive-table striped bordered" id="dataTable">
					<thead>
						<tr>
							<th>S.NO</th>
							<th>Phone Number</th>
							<th>Customer Name</th>
							<th>Order Number</th>
							<th>Scheduled Date</th>
							<th>Rescheduled Date</th>
							<th>Comments</th>
							<th>Created Date</th>
							<th>Last Update</th>
							<th>Call Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
				
				 for($i=0;$i<count($data);$i++){
					 
					$table_row = "<tr>";
					$table_row .="<td>".$data[$i]['xpo_id']."</td>";
					$table_row .="<td>".$data[$i]['Phone_No']."</td>";
					$table_row .="<td>".$data[$i]['Customer_Name']."</td>";
					$table_row .="<td>".$data[$i]['Order_No']."</td>";
					$table_row .="<td>".$data[$i]['schd_date']."</td>";
					$table_row .="<td>".$data[$i]['rescheduled_date']."</td>";
					$table_row .="<td>".$data[$i]['comments']."</td>";
					$table_row .="<td>".$data[$i]['created_date']."</td>";
					$table_row .="<td>".$data[$i]['last_update']."</td>";
					$table_row .="<td>".$data[$i]['call_status']."</td>";
					$table_row .='<td align="right"><i  style="cursor: pointer;" onclick="initiateCall(\''.$data[$i]['Phone_No'].'\', \''.$data[$i]['Order_No'].'\')" class="small material-icons ">call</i></td>';
					 $table_row .= "</tr>";
					 
					 echo $table_row;
				 }
				 ?>

					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>

</html>