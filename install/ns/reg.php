<?php 
namespace app\engine\datatype; 
final class Registry extends \app\engine\Controller{ 
    public function __construct(){ 
        new \app\engine\datatype\Loader(); 
        new \app\engine\datatype\Loader();         
        $ns = '\\app\\engine\\datatype\\'; 
        $ns = '\\app\\engine\\datatype\\data\\';         
        $d = new \MySQL();         
        $d = new \app\engine\datatype\MySQL();     
    }     
} 
final class Registry2 extends \app\engine\datatype\Controller{} 
?>

 