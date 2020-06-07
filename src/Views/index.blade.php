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
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

	    <!-- Styles -->
	    <style>
	        html, body {
	            background-color: #fff;
	            color: #636b6f;
	            font-family: 'Nunito', sans-serif;
	            /*font-weight: 200;*/
	            font-size: 14px;
	            height: 100vh;
	            margin: 0;
	        }

	        .full-height {
	            height: 100vh;
	        }

	        .flex-center {
	            align-items: center;
	            display: flex;
	            justify-content: center;
	        }

	        .position-ref {
	            position: relative;
	        }

	        .top-right {
	            position: absolute;
	            right: 10px;
	            top: 18px;
	        }

	        .content {
	            text-align: center;
	        }

	        .title {
	            font-size: 84px;
	        }

	        .links > a {
	            color: #636b6f;
	            padding: 0 25px;
	            font-size: 13px;
	            font-weight: 600;
	            letter-spacing: .1rem;
	            text-decoration: none;
	            text-transform: uppercase;
	        }

	        .m-b-md {
	            margin-bottom: 30px;
	        }
	    </style>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h4 class="py-4">Activities</h4>
					<table class="table audit-trail">
						<thead>
							<tr>
								<th>S/N</th>
								<th>User</th>
								<th>Route</th>
								<th>Details</th>
								<th>IP (Addr)</th>
								<th>Browser Information</th>
								<th>Last Seen</th>
							</tr>
						</thead>
						<tbody class="load-audit-events"></tbody>
					</table>

					<h4 class="py-4">
						
						<span class="float-right">
							<a href="javascript:void(0);" onclick="deleteLog(1)" class="btn btn-link">
							    delete all
							</a>
							<a href="javascript:void(0);" onclick="deleteLog(2)" class="btn btn-link">
							    delete last week
							</a>
							<a href="javascript:void(0);" onclick="deleteLog(3)" class="btn btn-link">
							    delete last month
							</a>
						</span>
					</h4>
				</div>
			</div>
		</div>

		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

		<!-- Popper JS -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

		<!-- Latest compiled JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
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
								<td>${val.ip}</td>
								<td><i class="fa fa-tv"></i> ${val.browser}</td>
								<td>
									<i class="fa clock-o"></i> ${val.last_seen}
									<br />
									${val.date_seen}
								</td>
							</tr>
						`);
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