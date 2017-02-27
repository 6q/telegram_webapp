<?php

class FeedbackNow {

	private static $instance = NULL;

	
	public  $email;
	public  $message;
	public  $imagePath;
	public 	$phpmailer;
	
	private $categories;
	private $haveImage = false;
	private $imageData;	
	private $imagesDirectory;
	

	private function __construct($options){

		if($_SERVER["REQUEST_METHOD"] == "POST"){

			if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
			{
			    if(isset($_POST["categories"]) && isset($_POST["email"]) && isset($_POST["message"]) ){

					$this->categories = $_POST["categories"];
					$this->email      = $_POST["email"];
					$this->message    = $_POST["message"];
					
					if(isset($options["imagesDirectory"]) ){

//						var $lastChar = substr($options["imagesDirectory"], strlen($options["imagesDirectory"])-1,1);
						$this->imagesDirectory = $options["imagesDirectory"];						
						
						if(class_exists("PHPMailer")){
							$this->phpmailer = new PHPMailer;

							if(isset($_POST["imageData"]) && !empty($_POST["imageData"])){
								$this->haveImage = true;
								$this->imageData = $_POST["imageData"];
								$this->upload();								
							}

							$this->buildEmail();
						} else {
							throw new Exception("Sorry, was not possible without instantiating the class phpmailer");
						}
					} else {
						throw new Exception("Fatal exception, undefined config parameters");
					}
			    } else {
			    	throw new Exception("Fatal exception, undefined post parameters");
			    }
			} else{
				throw new Exception("request method is invalid");
			}
		} else {
			throw new Exception("request method is invalid");
		}
	}

	public function getCategories(){
		$categories = array();
		foreach ($this->categories as $key => $value) {
			if($this->categories[$key] == "true")
					$categories[] = $key;
		}
		return $categories;
	}

	public function buildEmail(){

		$categories       = $this->getCategories();
		$stringCategories = @implode(", ", $categories);

		$mail = $this->phpmailer;
		$mail->IsHTML(true);  
		$mail->Subject 	.= "Feedback - Supermatic";

		$mail->Body 	.= '<div style="margin-bottom: 25px;margin-top: 30px;" align="left"><img width="300" src="http://supermatic.pro.gestinet.info/resource/images/logos/logoSupermatic-horitzontal.jpg"></div>';
		$mail->Body 	.= "Se ha registrado un Feedback en la página de Supermatic.<br><br> ";
		$mail->Body 	.= "Categorías: <strong>". $stringCategories . "</strong><br>";
		$mail->Body 	.= "E-mail: <strong>". $this->email . "</strong><br>";
		$mail->Body 	.= "Mensaje: <strong>". $this->message . "</strong><br>";

		if($this->imageData!=""){
			$mail->Body.="Captura de pantalla";
			$mail->addAttachment($this->imagePath, 'captura-de-pantalla.jpg');
		}

	}

	private function upload(){

		if($this->haveImage){
			$name = dechex(rand(0, 99999999999)) . ".jpg";
			$fullpath = $this->imagesDirectory;
			while(file_exists($fullpath . $name)){
				$name = dechex(rand(0, 99999999999)) . ".jpg";
			}
			$pathFile = $fullpath.$name;

			$file = fopen($pathFile, "w") or die("Unable to open file!");

			$img = $this->imageData;			
			$img = str_replace('data:image/jpeg;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			$data = base64_decode($img);

			$content = $data;
			fwrite($file, $content);						
			fclose($file);
			$this->imagePath = $pathFile;
		}
	}

	public static function createSingleton($options){
		if(self::$instance == NULL){
			self::$instance = (new FeedbackNow($options));
		}
		return self::$instance;
	}


}
