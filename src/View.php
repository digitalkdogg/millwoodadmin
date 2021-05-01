<?php
class View {
	public $html;
	function __construct ($file, $var = null) {
		
		include $file;
		$doc = new DOMDocument();
		$doc -> loadHTMLFile($file);
		//echo $doc->saveHTML();
	
	}
}
?>