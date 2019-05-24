<?php
// Name : Image Model
// Desc : Handles all the images within the site
namespace app\module;
final class ImageModel extends \app\engine\Model{
	
	//
	// DB Structure
	public function __construct($registry){
		parent::__construct($registry);
		
		// Original Image
		$f = $this->table('image')->field('o_src', 'text');
		$f->regex = '#\.png|\.jpg|\.gif|\.jpeg$#i';
		$f = $this->table('image')->field('o_width', 'int');		
		$f = $this->table('image')->field('o_height', 'int');
		
		// Resized Image
		$f = $this->table('image')->field('src', 'text');
		$f->regex = '#\.png|\.jpg|\.gif|\.jpeg$#i';
		$f = $this->table('image')->field('width', 'int');//Finalized Image Width	
		$f = $this->table('image')->field('height', 'int');
		
		// Thumbnail
		$f = $this->table('image')->field('t_src', 'text');
		$f->regex = '#\.png|\.jpg|\.gif|\.jpeg$#i';
		$f = $this->table('image')->field('t_width', 'int');		
		$f = $this->table('image')->field('t_height', 'int');
		
		
		$f = $this->table('imagemeta')->field('imageid', 'pkey');
		$f->key = 'index';
		$f = $this->table('imagemeta')->field('rw', 'int');//Resized Width from Original before crop
		$f = $this->table('imagemeta')->field('rh', 'int');
		$f = $this->table('imagemeta')->field('sx', 'int');//Topleft Crop PosX
		$f = $this->table('imagemeta')->field('sy', 'int');
		$f = $this->table('imagemeta')->field('ex', 'int');//BottomRight Crop PosY
		$f = $this->table('imagemeta')->field('ey', 'int');
				
		$f = $this->table('imagemeta')->field('t_rw', 'int');
		$f = $this->table('imagemeta')->field('t_rh', 'int');
		$f = $this->table('imagemeta')->field('t_sx', 'int');
		$f = $this->table('imagemeta')->field('t_sy', 'int');	
		$f = $this->table('imagemeta')->field('t_ex', 'int');
		$f = $this->table('imagemeta')->field('t_ey', 'int');				
	}
}
?>