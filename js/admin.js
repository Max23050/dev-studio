document.addEventListener("DOMContentLoaded", function() {
	const emails = new Set();
	document.querySelectorAll("table tbody tr").forEach(row => {
		const email = row.cells[1].textContent;
		emails.add(email);
	})

	document.querySelector("#customersCount").textContent = emails.size;


	const detailButtons = document.querySelectorAll(".details-btn");

	detailButtons.forEach(function(button) {
		button.addEventListener("click", function() {
			openModal(this);
		})
	})

	const orderCount = document.querySelectorAll("table tbody tr").length;
	document.querySelector("#orderCount").textContent = orderCount;

	document.getElementById("statusFilter").addEventListener("change", function() {
		const selectedStatus = this.value;
		filterOrders(selectedStatus);
	})

	filterOrders("in progress");

})

function filterOrders(status) {
	const rows = document.querySelectorAll("table tbody tr");
	rows.forEach(function(row) {
		const rowStatus = row.querySelector(".status-circle").getAttribute("data-status");
		if (status === "all" || status === rowStatus) {
			row.style.display = ""; 
		} else {
			row.style.display = "none";
		}
	})
}

function openModal(button) {
	const row = button.parentNode.parentNode;
	const email = row.cells[1].innerText;
	const filmNumber = row.cells[2].innerText;
	const negatives = row.cells[3].innerText;
	const address = row.cells[4].innerText;
	const totalOrders = row.querySelector(".total-orders").innerText;
	const orderTypes = row.querySelector(".order-types").innerText;
	const previousNumber = row.querySelector('.previous-order').innerText;

	const modalContent = document.querySelector(".modal-content-data");

	let previousNumberHtml = previousNumber ? `<div class="modal-content-element">Previous film numbers: ${previousNumber}</div>` : "";

	modalContent.innerHTML = `<div class="modal-content-element">Email: ${email}</div>
	<div class="modal-content-element">Film Number: ${filmNumber}</div>
	<div class="modal-content-element">Negatives: ${negatives}</div>
	<div class="modal-content-element">Address: ${address}</div>
	<div class="modal-content-element">Total Orders: ${totalOrders}</div>
	<div class="modal-content-element">Order Types: ${orderTypes}</div>
	${previousNumberHtml}`;

	const orderId = row.getAttribute("data-order-id");
	document.getElementById('orderStatus').value = row.querySelector(".status-circle").getAttribute("data-status");
	
	document.getElementById('saveChanges').onclick = function() {
		const newStatus = document.getElementById('orderStatus').value;
		updateOrderStatus(orderId, newStatus);
	}

	document.getElementById("myModal").style.display = "block";
}

function updateOrderStatus(orderId, newStatus) {
	const xhr = new XMLHttpRequest();
	xhr.open("POST", "update_order_status.php", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function() {
		if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
			const statusCircle = document.querySelector("tr[data-order-id='" + orderId + "'] .status-circle");
			statusCircle.style.backgroundColor = newStatus === "complete" ? "#008000" : "#FFCE26";
			statusCircle.setAttribute("data-status", newStatus);

			filterOrders(document.getElementById("statusFilter").value);
		}
	}
	xhr.send("orderId=" + orderId + "&status=" + newStatus);
}

const span = document.querySelector(".close");
span.addEventListener('click', function() {
	document.getElementById("myModal").style.display = "none";
})

window.addEventListener('click', function(event) {
	const modal = document.getElementById("myModal");
	if (event.target == modal) {
		modal.style.display = "none";
	}
})



const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');

allSideMenu.forEach(item=> {
	const li = item.parentElement;

	item.addEventListener('click', function () {
		allSideMenu.forEach(i=> {
			i.parentElement.classList.remove('active');
		})
		li.classList.add('active');
	})
});




// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
	sidebar.classList.toggle('hide');
})







const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

searchButton.addEventListener('click', function (e) {
	if(window.innerWidth < 576) {
		e.preventDefault();
		searchForm.classList.toggle('show');
		if(searchForm.classList.contains('show')) {
			searchButtonIcon.classList.replace('bx-search', 'bx-x');
		} else {
			searchButtonIcon.classList.replace('bx-x', 'bx-search');
		}
	}
})





if(window.innerWidth < 768) {
	sidebar.classList.add('hide');
} else if(window.innerWidth > 576) {
	searchButtonIcon.classList.replace('bx-x', 'bx-search');
	searchForm.classList.remove('show');
}


window.addEventListener('resize', function () {
	if(this.innerWidth > 576) {
		searchButtonIcon.classList.replace('bx-x', 'bx-search');
		searchForm.classList.remove('show');
	}
})



const switchMode = document.getElementById('switch-mode');

switchMode.addEventListener('change', function () {
	if(this.checked) {
		document.body.classList.add('dark');
	} else {
		document.body.classList.remove('dark');
	}
})