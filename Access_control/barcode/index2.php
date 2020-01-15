<?php 
include('header.php');
?>
<title> API</title>
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
			<form  action="/home/daemon30000/Documents/Access_control/API/createLuggage.php" method="post">
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
							<label>weight</label>
							<input type="text" name="weight" class="form-control" value="<?php echo @$_POST['weight'];?>">
						</div>
					</div>		
				</div>



<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label>cost</label>
							<input type="text" name="cost" class="form-control" value="<?php echo @$_POST['cost'];?>">
						</div>
					</div>		
				</div>



<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label>Description of luggage</label>
							<input type="text" name="luggage" class="form-control" value="<?php echo @$_POST['luggage'];?>">
						</div>
					</div>		
				</div>




				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label>recipientID</label>
							<input type="text" name="recipientID" class="form-control" value="<?php echo @$_POST['recipientID'];?>">
						</div>
					</div>		
				</div>


<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label>Destination</label>
							<input type="text" name="destination" class="form-control" value="<?php echo @$_POST['destination'];?>">
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
		<div class="col-md-4">
		 <?php
			/**if(isset($_POST['generateBarcode'])) {
				$name = $_POST['name'];
				$luggage=$_POST['luggage'];
				$cost=$_POST['cost'];
				$weight=$_POST['weight'];
				$recipientID=$_POST['recipientID'];
				if($barcodeText != '') {
					echo '<h4>Barcode:</h4>';
					echo '<img class="barcode" alt="'.$name.'" src="barcode.php?text='.$barcodeText.'&codetype='.$barcodeType.'&orientation='.$barcodeDisplay.'&size='.$barcodeSize.'&print='.$printText.'"/>';
				} else {
					
				}
			}
		*/
			?>
		</div>
	</div>		
	<div style="margin:50px 0px 0px 0px;">
				
	</div>
</div>
<?php include('footer.php');?>
