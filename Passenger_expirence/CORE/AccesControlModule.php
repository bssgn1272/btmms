<?php
require 'Configs.php';
require 'Logger.php';
require 'barcode.php';
class AccesControlModule{
        private $logger ;
        private $data ; 
/**this is ths constructor class that is first called when the class is called
 * */
	function AccesControlModule(){

		//access control module control
	$this->logger = new Logger();

    $this->logger->log("in the constructor of the Access Control module",Configs::INFO);

/**	$this->data = file_get_contents('php://input');
	$this->logger->log("the data received is ".$this->data,Configs::INFO);
	
*/
	} 
     

	/** used to generate barcode */

function generateBarcodeImage($date){
             $this->logger->log("about to generate Barcode",Configs::INFO);
              $generator = new barcode_generator();
               
	     /* Output directly to standard output. */
	          $format = Configs::FORMAT;
	          $this->logger->log("format to use ".$format,Configs::INFO);
              $symbology = 'code-39-ascii';
	          $data =$date;
	          $options ;
  //            $generator->output_image($format, $symbology, $data, $options);
                   $this->logger->log("calling the generator",Configs::INFO);

/**Create bitmap image. */
$IMAGEPATH = "../images/";

            $image = $generator->render_image($symbology, $data, $options);
	      imagepng($image,$IMAGEPATH."/".$data);

	       $this->logger->log("barcode returned".print_r($generator->encode_and_calculate_size($symbology, $data, $options),true),Configs::INFO);
            imagedestroy($image);
return $image;


	}

function getBarcode($date){
             $this->logger->log("about to generate Barcode",Configs::INFO);
              $generator = new barcode_generator();
               
	     /* Output directly to standard output. */
	          $format = Configs::FORMAT;
	          $this->logger->log("format to use ".$format,Configs::INFO);
              $symbology = 'code-39-ascii';
	          $data =$date;
	          $options;
            $barcode = $generator->encode_and_calculate_size($format, $symbology, $data, $options);
                   $this->logger->log("calling the generator",Configs::INFO);

/* Create bitmap image. */
           
	       $this->logger->log("barcode returned".print_r($barcode,true),Configs::INFO);
              
  return $barcode;


	}


}
$server = new AccesControlModule();
//$server->generateBarcodeImage();
//print_r($server->getBarcode());



?>
