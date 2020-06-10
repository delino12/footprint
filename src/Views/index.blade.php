<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>
			Laravel - Footprint
		</title>

		<!-- Fonts -->
	    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
	    <!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="{{ asset('codedreamer/css/bootstrap.min.css') }}">
		<link rel="stylesheet" href="{{ asset('codedreamer/css/datatable.min.css') }}">
		<link rel="stylesheet" href="{{ asset('codedreamer/css/footprint.min.css') }}">

		<style type="text/css">
			.dt-buttons .dt-button {
				padding: 0.5rem;
				border: 1px solid #2a2dad;
				border-radius: 2px;
				font-size: 10px;
				color: #2a2dad;
				background-color: transparent;
				cursor: pointer;
			}

			.dt-buttons .dt-button:hover {
				padding: 0.5rem;
				border: 1px solid #999;
				border-radius: 2px;
				font-size: 10px;
			}
		</style>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h4 class="py-4">Activities</h4>
					<div class="table-responsive data-table">
						<table class="table" id="footprint-table">
							<thead>
								<tr>
									<th>S/N</th>
									<th>User</th>
									<th>Route</th>
									<th>Details</th>
									<th>IP (Addr)</th>
									<th>OS / Client Browser</th>
									<th>Last Seen</th>
								</tr>
							</thead>
							<tbody class="load-audit-events"></tbody>
						</table>

						<h4 class="py-4">
							
							<span class="float-right">
								<a href="javascript:void(0);" onclick="deleteLog(1)" class="btn btn-outline-primary">
								    <i class="fa fa-trash"></i> all
								</a>
								<a href="javascript:void(0);" onclick="deleteLog(2)" class="btn btn-outline-primary">
								    <i class="fa fa-trash"></i> last week
								</a>
								<a href="javascript:void(0);" onclick="deleteLog(3)" class="btn btn-outline-primary">
								    <i class="fa fa-trash"></i> last month
								</a>
							</span>
						</h4>
					</div>
				</div>
			</div>
		</div>

		<!-- Js libraries -->
		<script src="{{ asset('codedreamer/js/jquery.min.js') }}"></script>
		<script src="{{ asset('codedreamer/js/popper.min.js') }}"></script>
		<script src="{{ asset('codedreamer/js/bootstrap.min.js') }}"></script>
		<script src="{{ asset('codedreamer/js/font-awesome.min.js') }}"></script>

		<script src="{{ asset('codedreamer/js/jquery.datatable.min.js') }}"></script>
		<script src="{{ asset('codedreamer/js/bootstrap.datatable.min.js') }}"></script>
		<script src="{{ asset('codedreamer/js/datatable.buttons.min.js') }}"></script>
		<script src="{{ asset('codedreamer/js/jszip.min.js') }}"></script>
		<script src="{{ asset('codedreamer/js/pdfmake.min.js') }}"></script>
		<script src="{{ asset('codedreamer/js/vfs_fonts.js') }}"></script>
		<script src="{{ asset('codedreamer/js/buttons.html5.min.js') }}"></script>
		<script type="text/javascript">
			// load module
			fetchAuditTrails();

			function showAuditTrailDetails(file_name, file_path) {
				// body...
				$(".preview_details").html(``);
				$("#view-audit-trail-modal").modal();
			}

			function showViewEventByModal(event_by) {
				// console log data
				console.log(event_by);
				fetchAuditTrailsByUserId(event_by);
				$("#view-audit-trail-by-user-modal").modal();
			}

			function fetchAuditTrails() {
				fetch("{{url('footprint/all')}}", {
					method: "GET",
				}).then(r => {
					return r.json();
				}).then(results => {
					// console.log(results);
					let sn = 0;
					$(".load-audit-events").html("");
					$.each(results, function(index, val) {
						sn++;
						$(".load-audit-events").append(`
							<tr>
								<td>${sn}</td>
								<td>
									<a href="javascript:void(0);" onclick="showViewEventByModal('${val.EventByID}')" class="">
										<i class="fa fa-user"></i>
										${val.by} 
										<span class="text-warning">
											(${val.email})
										</span>
									</a>
								</td>
								<td>${val.page}</td>
								<td>
									<span class="text-primary">${val.details}</span>
								</td>
								<td><i class="fa fa-globe"></i> ${val.ip}</td>
								<td><i class="fa fa-tv"></i> ${val.browser}</td>
								<td>
									<i class="fa fa-clock"></i> ${val.last_seen}
									<br />
									${val.date_seen}
								</td>
							</tr>
						`);
					});

					$("#footprint-table").DataTable({
				        dom: 'Bfrtip',
				        buttons: [
				            'copyHtml5',
				            'excelHtml5',
				            'csvHtml5',
				            'pdfHtml5'
				        ]
				    });
				}).catch(err => {
					console.log(err);
				});
			}

			function fetchAuditTrailsByUserId(user_id) {
				fetch("{{url('footprint/one')}}/"+user_id, {
					method: "GET",
				}).then(r => {
					return r.json();
				}).then(results => {
					console.log(results);
					let sn = 0;
					$(".load-audit-by-user-events").html("");
					$.each(results, function(index, val) {
						sn++;
						$(".load-audit-by-user-events").append(`
							<tr>
								<td>${sn}</td>
								<td>
									<i class="fa fa-user"></i>
									${val.EventBy} 
									<span class="text-warning">
										(${val.EventEmail})
									</span>
								</td>
								<td>${val.EventDetails}</td>
								<td>
									${val.EventBy} is active on <span class="text-warning">${val.EventDetails}</span>
								</td>
								<td>${val.EventIp}</td>
								<td><i class="fa fa-tv"></i> ${val.EventBrowser}</td>
								<td>${val.created_at}</td>
							</tr>
						`);

						$("#activity_table").dataTable();
					});
				}).catch(err => {
					console.log(err);
				});
			}

			function deleteLog(action) {
				var _token = '{{ csrf_token() }}';
				var query = {_token, action}

				fetch(`{{ url('footprint') }}`, {
					method: 'DELETE',
					headers: {
						'Content-Type': 'application/json',
					},
					body: JSON.stringify(query)
				}).then(r => {
					return r.json();
				}).then(results => {
					console.log(results);
					if(results.status == "success"){
						fetchAuditTrails();
					}
				}).catch(err => {
					console.log(err);
				});
			}
		</script>
	</body>
</html>