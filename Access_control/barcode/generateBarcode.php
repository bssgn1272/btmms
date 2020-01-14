<?php 
error_reporting(0);
require_once "/home/daemon30000/Documents/Access_control/index.php";
include('header.php');
?>
<title> API</title>
<script>
	
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}

</script>
<style>
img.barcode {
    border: 1px solid #ccc;
    padding: 20px 10px;
    border-radius: 5px;
}
</style>
<?php //include('container.php');
?>
<div class="container">
		
	<br>
	<br>
	<div class="row">	
		<div class="col-md-4">
			<form  action="" method="post">
				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label>Passenger Name</label>
							<input type="text" name="name" class="form-control" value="<?php echo @$_POST['name'];?>">
						</div>
					</div>		
				</div>	
				

<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label>Date</label>
							<input type="text" name="date" class="form-control" value="<?php echo @$_POST['date'];?>">
						</div>
					</div>		
				</div>	


			

				<div class="row">
				
				</div>	
				<div class="row">
					<div class="col-md-7">
						<input type="hidden" name="barcodeSize" id="barcodeSize" value="20">
						<input type="hidden" name="printText" id="printText" value="true">
						<input type="submit" name="generateBarcode" class="btn btn-success form-control" value="submit">
					</div>
				</div>
			</form>
		</div>
		
		 <?php
			if(isset($_POST['generateBarcode'])) {
				?>

<div class="col-md-4" id="printableArea">
				<?php
				$name = $_POST['name'];
				$date=$_POST['date'];

				$data = new AccesControlModule();

              $image=$data->generateBarcodeImage($date);
//$result = $data->getBarcode($date);

				$IMAGEPATH = "http://localhost/Barcode/images";
		      $path = "/var/www/html/Barcode/images/";

                         $filepath=$path.$date;
                       $file = $IMAGEPATH."/".$date;
			          imagepng($image,$filepath);
					echo '<h4>Barcode:</h4>';
		              for($i=0;$i<4;$i++){

		              ?>
				<img class="barcode" alt="Barcode" src="<?php echo $file;?>"/>
				<br/>
				
				<br/>
                          
					<?php
					imagedestroy($image);
				}
				?>

</div>
	<input type="button" class="btn btn-success form-control" onclick="printDiv('printableArea')" value="Print" />

				<?php
			}
	
			?>
		
		
	</div>		
	<div style="margin:50px 0px 0px 0px;">
				
	</div>
</div>
<?php include('footer.php');?>
