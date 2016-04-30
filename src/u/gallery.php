
<html>
	<head>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
		<link href="https://cdn.datatables.net/1.10.9/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.5.7/slick.css"/>
		<link href="css/main.css" rel="stylesheet" type="text/css"/>

		<title>Joe - Uploads</title>
	</head>
	<body>
		<?php if($_SERVER['REMOTE_ADDR'] == "5.179.100.252"){?>
					<?php
					$ignore = Array("index.php", "js", "css", ".", "..", "gallery.php", "img");
					$files1 = scandir(".");
					?>
				<a href="/">Back to homepage</a>
				<br>


		    	<div class="main_slider">
			        	<?php foreach($files1 as $file){
			        		if(!in_array($file, $ignore)){?>
				            	<div>
				                	<a href="http://jiy.io/<?php echo($file);?>" target="_blank"><img data-u="image" src="http://jiy.io/<?php echo($file);?>" /></a>
				            	</div>
			            <?php } }?>
		        </div>

		<?php }?>

		<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.5.7/slick.min.js"></script>
	    <script>
		    $(document).ready(function(){
			  $('.main_slider').slick({});

			  $(".slick-prev").text("<<");
			  $(".slick-next").text(">>");
			});
	    </script>
		<script src="js/main.js" type="text/javascript"></script>
	</body>
</html>