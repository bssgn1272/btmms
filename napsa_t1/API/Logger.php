<?php

class Logger{
	function Logger(){
	}
	function log($string,$file) {
        $prelog = date("Y-m-d H:i:s") . " [LOG] " . $_SERVER['PHP_SELF']
                . " : " . print_r($string, true);
        $handle = fopen($file, 'a');
        fwrite($handle, "$prelog\n");
        fclose($handle);
    }

}

?>
