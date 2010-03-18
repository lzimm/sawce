<?php

class Debug {

	public static function getFunctions($class) {
		if (file_exists($GLOBALS['cfg']['basedir'] . 'libs/' . $class . '.php')) {
			$file = file_get_contents($GLOBALS['cfg']['basedir'] . 'libs/' . $class . '.php');
			$file = ereg_replace("[\n\r\t ]+", " ", $file);
			
			$file = substr($file, strpos($file, '{') + 1);
			$file = strrev($file);
			$file = substr($file, strpos($file, '}') + 1);
			$file = strrev($file);
			
			ereg("(public [^;{]* {)*", $file, $regs);
			
			echo "<pre>";
			print_r($regs);
			echo "<pre>";
		} else {
			throw new Exception("Class not found");
		}
	}

}

?>