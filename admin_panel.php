<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "form_data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connection_error);
}

$sql = "SELECT id, film_number, email, negatives, address, status, previous_order_with_negatives_film_number FROM submissions ORDER BY id DESC";
$result = $conn->query($sql);

$sqlTotalSales = "SELECT SUM(price) AS totalSales FROM submissions";
$resultTotalSales = $conn->query($sqlTotalSales);

$totalSales = 0;
if ($resultTotalSales->num_rows > 0) {
	$rowTotalSales = $resultTotalSales->fetch_assoc();
	$totalSales = $rowTotalSales['totalSales'];
}

$ordersCount = []; // Для подсчета заказов по email
$ordersTypes = []; // Для подсчета типов заказов по email

while ($row = $result->fetch_assoc()) {
    $email = $row['email'];
    $negatives = $row['negatives'];

    if (!isset($ordersCount[$email])) {
        $ordersCount[$email] = 0;
        $ordersTypes[$email] = ['pickup' => 0, 'mail' => 0, 'none' => 0, 'with_order' => 0];
    }

    $ordersCount[$email]++;
    $ordersTypes[$email][$negatives]++;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="css/admin.css">

	<title>AdminHub</title>
</head>
<body>

	<!-- Modal -->
	<div id="myModal" class="modal">
	<div class="modal-content">
		<span class="close">&times;</span>
		<div class="modal-content-title">
			Order details
		</div>
		<div class="modal-content-data"></div>
		<select id="orderStatus">
			<option value="in progress">In Progress</option>
			<option value="complete">Complete</option>
		</select>
		<button id="saveChanges">Save</button>
	</div>
		</div>
	<!-- SIDEBAR -->
	<section id="sidebar" class="hide">
		<a href="#" class="brand">
			<i class='bx bx-film'></i>
			<span class="text">AdminHub</span>
		</a>
		<ul class="side-menu top">
			<li class="active">
				<a href="#">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="#">
					<i class='bx bxs-shopping-bag-alt' ></i>
					<span class="text">My Store</span>
				</a>
			</li>
			<li>
				<a href="#">
					<i class='bx bxs-doughnut-chart' ></i>
					<span class="text">Analytics</span>
				</a>
			</li>
			<li>
				<a href="#">
					<i class='bx bxs-message-dots' ></i>
					<span class="text">Message</span>
				</a>
			</li>
			<li>
				<a href="#">
					<i class='bx bxs-group' ></i>
					<span class="text">Team</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu">
			<li>
				<a href="#">
					<i class='bx bxs-cog' ></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<li>
				<a href="#" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->



	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			<a href="#" class="nav-link">Categories</a>
			<form action="#">
				<div class="form-input">
					<input type="search" placeholder="Search...">
					<button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
				</div>
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label>
			<a href="#" class="notification">
				<i class='bx bxs-bell' ></i>
				<span class="num">8</span>
			</a>
			<a href="#" class="profile">
				<img src="img/people.png">
			</a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Dashboard</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Home</a>
						</li>
					</ul>
				</div>
				<a href="#" class="btn-download">
					<i class='bx bxs-cloud-download' ></i>
					<span class="text">Download PDF</span>
				</a>
			</div>

			<ul class="box-info">
				<li>
					<i class='bx bxs-calendar-check' ></i>
					<span class="text">
						<h3 id="orderCount">1020</h3>
						<p>Orders</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-group' ></i>
					<span class="text">
						<h3 id="customersCount">2834</h3>
						<p>Customers</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-dollar-circle' ></i>
					<span class="text">
						<h3><?php echo $totalSales; ?> Kč</h3>
						<p>Total Sales</p>
					</span>
				</li>
			</ul>


			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Recent Orders</h3>
						<select id="statusFilter">
							<option value="all">All orders</option>
							<option value="in progress" selected>In progress</option>
							<option value="complete">Complete</option>
						</select>
						<i class='bx bx-search' ></i>
						<i class='bx bx-filter' ></i>
					</div>
					<table>
						<thead>
							<tr>
								<!-- <th>ID</th> -->
								<th>Status</th>
                                <th>Email</th>
								<th>Film Number</th>
                                <th>Negatives</th>
                                <th>Address</th>
								<th style='display: none;'>Total Orders</th>
            					<th style='display: none;'>Order Types</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$result->data_seek(0); // Переместите указатель результата в начало
						while ($row = $result->fetch_assoc()) {
							$email = $row['email'];
							$totalOrders = $ordersCount[$email]; // Общее количество заказов для email
							$orderTypesSummary = ""; // Строка для вывода подробностей типов заказов
							
							foreach ($ordersTypes[$email] as $type => $count) {
								if ($count > 0) { // Добавляем только те типы заказов, которые существуют
									$orderTypesSummary .= "$type: $count; ";
								}
							}

							$icon = !empty($row['previous_order_with_negatives_film_number']) ? "<i class='bx bx-package' style='font-size: 26px'></i>" : "";
							
							echo "<tr data-order-id='{$row['id']}'>
									<td><span class='status-circle' data-status='{$row['status']}'></span></td>
									<td>{$row['email']}</td>
									<td>{$row['film_number']}</td>
									<td>{$row['negatives']}</td>
									<td>{$row['address']}</td>
									<td style='display: none;' class='total-orders'>{$totalOrders}</td>
									<td style='display: none;' class='order-types'>{$orderTypesSummary}</td>
									<td style='display: none;' class='previous-order'>{$row['previous_order_with_negatives_film_number']}</td>
                					<td>{$icon}</td>	
									<td><button class='details-btn'>more</button></td>
								</tr>";
						}
						?>

            
						</tbody>
					</table>
				</div>
<!-- 				<div class="todo">
					<div class="head">
						<h3>Todos</h3>
						<i class='bx bx-plus' ></i>
						<i class='bx bx-filter' ></i>
					</div>
					<ul class="todo-list">
						<li class="completed">
							<p>Todo List</p>
							<i class='bx bx-dots-vertical-rounded' ></i>
						</li>
						<li class="completed">
							<p>Todo List</p>
							<i class='bx bx-dots-vertical-rounded' ></i>
						</li>
						<li class="not-completed">
							<p>Todo List</p>
							<i class='bx bx-dots-vertical-rounded' ></i>
						</li>
						<li class="completed">
							<p>Todo List</p>
							<i class='bx bx-dots-vertical-rounded' ></i>
						</li>
						<li class="not-completed">
							<p>Todo List</p>
							<i class='bx bx-dots-vertical-rounded' ></i>
						</li>
					</ul>
				</div> -->
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="js/admin.js"></script>
</body>
</html>

