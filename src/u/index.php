<?php $config = include('config.php'); ?>
<html>
	<head>
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
		<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
		<title><?php echo $config['page_title'];?></title>
	</head>
	<body>
		<div class="container">
			<h3 class="text-center"><?php echo $config['heading_text'];?></h3>

			<p class="text-center"><?php 
			    $si_prefix = [ 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' ];
			    $base = 1024;

			    $bytes = disk_free_space("/"); 
			    $class = min((int)log($bytes , $base) , count($si_prefix) - 1);
			    echo "Free space: ";
			    echo sprintf('%1.2f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class] . ' / ';

			    $bytes = disk_total_space("/");
			    $class = min((int)log($bytes , $base) , count($si_prefix) - 1);
			    echo sprintf('%1.2f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
			?>
			</p>
			<?php if(empty($config['allowed_ips']) || in_array($_SERVER['REMOTE_ADDR'], $config['allowed_ips'])){
					$ignore = ["index.php", "js", "css", ".", "..", "gallery.php", "img", "upload.php","config.php"];
					$files1 = scandir(".");
					?>
				<br>
				<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
			        <thead>
			            <tr>
			                <th>FileName</th>
			                <th>Size (Bytes)</th>
			                <th>Date</th>
			                <th>Type</th>
			            </tr>
			        </thead>
			 
			        <tbody>
			        	<?php foreach($files1 as $file){
			        		if(!in_array($file, $ignore)){?>
			            <tr>
			                <td><a target="_blank" href="<?php echo $config['output_url'];?><?php echo($file);?>"><?php echo($file);?></a></td>
			                <td><?php echo filesize($file);?></td>
			                <td><?php echo date ("d M Y H:i", filemtime($file))?></td>
			                <td><?php echo pathinfo($file, PATHINFO_EXTENSION);?></td>
			            </tr>
			            <?php } }?>
			        </tbody>
			    </table>
			<?php }?>
		</div>
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
		<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type="text/javascript"></script>
		<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
		<script src="js/main.js" type="text/javascript"></script>
	</body>
</html>
