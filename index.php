<!doctype html>
<html>
<head>
	<title>PHP REST API MySQL AJAX jQuery CRUD updated with security adudit!</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

	<style>
		.input-group {
			margin: 10px 0px 10px 0px;
		}
		.input-group label {
			display: block;
			text-align: left;
			margin: 3px;
		}
		.input-group input {
			height: 30px;
			width: 300px;
			padding: 5px 10px;
			font-size: 16px;
			border-radius: 5px;
			border: 1px solid gray;
		}
		.btn {
			padding: 10px;
			font-size: 15px;
			color: white;
			background: #5F9EA0;
			border: none;
			border-radius: 5px;
		}
        .maincss{text-align: center;
            align-content: center;
            width: 100%;
            height: inherit;
            
        }
	</style>
	<script type="text/javascript">
		$(document).ready(function() {
			$.getJSON('http://localhost/API/read.php', function(json) {
				var tr=[];
				for (var i = 0; i < json.length; i++) {
					tr.push('<tr>');
					tr.push('<td>' + json[i].id + '</td>');
					tr.push('<td>' + json[i].name + '</td>');
					tr.push('<td><button class=\'edit\'>Edit</button>&nbsp;&nbsp;<button class=\'delete\' id=' + json[i].id + '>Delete</button></td>');
					tr.push('</tr>');
				}
				$('table').append($(tr.join('')));
			});
			
			$(document).delegate('#addNew', 'click', function(event) {
				event.preventDefault();
                var name1 = $('#name').val().trim();
                var name = name1.replace(/[_-]/g, " ");
                if(name == null || name == "") {
					alert("Company Name is required");
					return;
		        }else{
                        $.ajax({
                        type: "POST",
                        contentType: "application/json; charset=utf-8",
                        url: "http://localhost/API/create.php",
                        data: JSON.stringify({'name': name}),
                        cache: false,
                        success: function(result) {
                        //inserted
                        var result = JSON.parse(result);
                        if(result.statusCode==200){
                            alert('Company added successfully');
                            location.reload(true);						
                        }
                        else if(result.statusCode==201){
                            alert('Company is already exist!');
                            location.reload(true);
                        }
                        else if(result.statusCode==202){
                            alert('Company is blank!');
                            location.reload(true);
                        }
                    }
				}); 
            }
			});
        
			
			$(document).delegate('.delete', 'click', function() { 
				if (confirm('Do you really want to delete record?')) {
					var id = $(this).attr('id');
					var parent = $(this).parent().parent();
					$.ajax({
						type: "DELETE",
						url: "http://localhost/API/delete.php?id=" + id,
						cache: false,
						success: function() {
							parent.fadeOut('slow', function() {
								$(this).remove();
							});
							location.reload(true)
						},
						error: function() {
							alert('Error deleting record');
						}
					});
				}
			});
			
			$(document).delegate('.edit', 'click', function() {
				var parent = $(this).parent().parent();
				
				var id = parent.children("td:nth-child(1)");
				var name = parent.children("td:nth-child(2)");
				var buttons = parent.children("td:nth-child(3)");
				
				name.html("<input type='text' id='txtName' value='" + name.html() + "'/>");
				buttons.html("<button id='save'>Save</button>&nbsp;&nbsp;<button class='delete' id='" + id.html() + "'>Delete</button>");
			});
			
			$(document).delegate('#save', 'click', function() {
				var parent = $(this).parent().parent();
				
				var id = parent.children("td:nth-child(1)");
				var name = parent.children("td:nth-child(2)");
				var buttons = parent.children("td:nth-child(3)");
				
				$.ajax({
					type: "PUT",
					contentType: "application/json; charset=utf-8",
					url: "http://localhost/API/update.php",
					data: JSON.stringify({'id' : id.html(), 'name' : name.children("input[type=text]").val()}),
					cache: false,
					success: function() {
						name.html(name.children("input[type=text]").val());
						buttons.html("<button class='edit' id='" + id.html() + "'>Edit</button>&nbsp;&nbsp;<button class='delete' id='" + id.html() + "'>Delete</button>");
					},
					error: function() {
						alert('Error updating record');
					}
				});
			});

		});
	</script>
</head>
<body>

	<h2>PHP REST API MySQL AJAX jQuery CRUD</h2>
	
	<h3>Add a New Company</h3>
	<div class="input-group">
		<label>Company Name</label>
		<input type="text" id="name" name="name" value="" maxlength="50">
	</div>
	<div class="input-group">
		<button class="btn" type="button" id="addNew">Save</button>
	</div>
	<p id="err" style="color: brown;"></p>
	<p/>

	<table border="1" cellspacing="0" cellpadding="5" style="align-self: center;">
		<tr>
			<th>Id</th>
			<th>Name</th>
			<th>Actions</th>
		</tr>
	</table>

</body>
</html>