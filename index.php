<?
### Config ###

# Title
$page_title = "Listing";
# Name of files to exclude from listing
$files_ignore = array("index.php", "styles.css");
# Specifies how long a file stays fresh (highlighted badge)
$fresh_file_seconds = 86400;
# Date format at date badge
$date_format = "d.m. Y H:i";

?>
<html>
	<head>
		<title><? echo $page_title ?></title>
	    <meta charset="UTF-8">
	    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
	    <link href="styles.css" rel="stylesheet">
	</head>
<body>
	<div class="wrapper">
		<div class="container">
			<div class="row">
				<div class="col-md-2">
	                <form method="post" action="index.php">
	                    <button name="sort" type="submit" value="sort_date" class="btn btn-default">↑ date</button>
	                    <button name="sort" type="submit" value="sort_filename" class="btn btn-default">↑ filename</button>
	                </form>
				</div>
				<div class="col-md-10">
					<div class="btn-group">
		                <form method="post" action="index.php">
		                	<button name="filter" type="submit" value="" class="btn btn-default">*</button>
<?
		                    $extension_array = array();
		                    $mask = '*.*';
		                    foreach (glob($mask) as $file) {
		                    	if (in_array($file, $files_ignore)){
		                    		continue;
		                    	}
		                        $extension = explode(".",basename($file)); 
		                        if (!in_array($extension[1],$extension_array)){
		                            array_push($extension_array,$extension[1]);
		                            echo '<button name="filter" type="submit" value="'.$extension[1].'" class="btn btn-default">*.'.$extension[1].'</button>'."\n";;     
		                        }
		                    }
?>
						</form>	
					</div>
				</div>
		    </div>  

			<div class="row">
				<div class="col-md-12">
					<div class="list-group">
<?
		            $filter = filter_input(INPUT_POST,"filter");

		            if(!empty($filter)){
		            	$mask = '*.'.$filter;
		            }
		           
		            $files = glob($mask);
		            
		            if (filter_input(INPUT_POST,"sort")!="sort_date"){
		    	                usort($files, function($a, $b) {
		                        return filemtime($a) < filemtime($b);
		                });
		            }
		            
		            if(count($files)>0){
		                foreach ($files as $file) {	
		                	if (in_array($file, $files_ignore)){
		                    		continue;
		                    }	
							$date_badge = '<span class="badge '.((time() - filemtime($file) < $fresh_file_seconds) ? "fresh" : "").'">'.date($date_format,filemtime($file));
		            		echo '<a class="list-group-item" href="'.basename($file). '">'.basename($file).$date_badge.'</span></a>'."\n";
	  	                }
		            }
?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>