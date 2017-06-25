<?php 
 /**   
    * 加载Xml文件
    /**   
    * 如果Xml为字符串的话可以用下面这个
    方法，后面的使用方法一样   
    * $xml = simplexml_load_string  
    * $xml = simplexml_load_file("url.xml") 
    */   
    if (file_exists('url.xml')) 
    { 
      $xml = simplexml_load_file('url.xml'); 
    } 
    else 
    { 
      exit('Error.'); 
    } 
    
    var_dump($xml);
?>