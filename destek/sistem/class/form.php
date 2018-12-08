<?php

class form{
	
	
	public function post($action, $dir = false){
		
		if($dir == false){
						
		
		if(file_exists('post/' .$action . '.php' ))
		{
			
			require_once 'post/' .$action . '.php';
			
			$class_name = 'Post_' . $action;
			
			$Post_Et = new $class_name;
			
			$Post_Et->Controller();
			
		}
		else
		{
			form::alert("Sistem hatası meydana geldi");
		}
		
		}
		else{
						
		if(file_exists('post/js/' .$action . '.php' ))
		{
			require_once 'post/js/' .$action . '.php';
			
			$class_name = 'Post_' . $action;
			
			$Post_Et = new $class_name;
			
			$Post_Et->Controller();
			
		}
		else
		{
			$this->alert("Sistem hatası meydana geldi1");
		}
			
		}
		
	}
	
	public function alert($yazi){
		
		?>
			
			<script>alert("<?=$yazi;?>")</script>
			
		<?php
		
	}
	
	public function basari($yazi){
		
		?>

			<script>sweetAlert("Başarı", "<?=$yazi;?>", "success");</script>
		
		<?php
		
	}
	
	
	public function hata($yazi){
		
		?>

			<script>sweetAlert("Hata", "<?=$yazi;?>", "error");</script>
		
		<?php
		
	}
	
	
	public function location($href, $sure = 2){
		?>
		
	<meta http-equiv="refresh" content="<?=$sure;?>;URL=<?=$href;?>">
		<?php
	}	
	
}
