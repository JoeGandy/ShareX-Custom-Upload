<?php $config = include('config.php');
   	  session_start();
	  include('functions.php');
   if($config['enable_delete'] && $_GET['action'] && $_GET['action'] == 'delete') {
    if(file_exists($_GET['filename'])) {
    		unlink($_GET['filename']);
	    	$_SESSION['type'] = 'success';
	    	$_SESSION['message'] = 'You have successfully deleted <strong>'.$_GET['filename'].'</strong>';
			die(header('Location: index.php'));
	}else{
	    	$_SESSION['type'] = 'danger';
	    	$_SESSION['message'] = 'File Does Not Exist!';
			die(header("Location: index.php"));
		 }
																				  }
?>
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
			    $si_prefix = [ 'B', 'KB', 'MB', 'GB', 'TB', 'PB' , 'EB', 'ZB', 'YB' ];
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
					$files1 = preg_grep('/^([^.])/', scandir("."));
					$finfo = finfo_open(FILEINFO_MIME_TYPE);
					if(!empty($_SESSION)){
					echo displayAlert($_SESSION['message'] , $_SESSION['type']);
					session_destroy();
					}
					?>
				<br>

				<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
			        <thead>
			            <tr>
			                <th>FileName</th>
			                <th>Size (Bytes)</th>
			                <th>Date</th>
			                <th>Type</th>
					<?php if($config['enable_delete']){?>
					<th>Option</th>
					 <?php }?> 
			            </tr>
			        </thead>
			 
			        <tbody>
			        	<?php foreach($files1 as $file){
			        		 if(is_dir($file) || pathinfo($file, PATHINFO_EXTENSION) == 'php'){
								unset($files1[$key]);
							} else {?>
			            <tr>
			                <td><a target="_blank" href="<?php echo $config['output_url']; echo($file);?>"
							<?php if($config['enable_tooltip'] && isImage(finfo_file($finfo, $file))){?>
							data-toggle="tooltip" data-html="true" data-placement="right" title="<img src='<?php echo $config['output_url']; echo($file);?>' width='150px' alt='<?php echo($file);?>'>"
							<?php }?>><?php echo($file);?></a></td>
			                <td><?php echo filesize($file);?></td>
			                <td><?php echo date ("d M Y H:i", filemtime($file))?></td>
			                <td><?php echo pathinfo($file, PATHINFO_EXTENSION);?></td>
					 <?php if($config['enable_delete']){?>   
					<td><a href="index.php?action=delete&filename=<?php echo $file;?>" class="btn btn-danger"  onclick="return confirm('Are you sure you want to permanently delete this file (<?php echo($file);?>) ?');">delete file</a></td>
			            	<?php }?> 
					</tr>
			            <?php } }?>
			        </tbody>
			    </table>
			<?php }?>
		</div>
		<script src="https://code.jquery.com/jquery-3.3.1.min.js"  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
		<?php if($config['enable_tooltip']){?>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<?php }?>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
		<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type="text/javascript"></script>
		<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
		<script src="js/main.js" type="text/javascript"></script>
	</body>
</html>
