<?php
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$c_email=$_POST["c_email"];
		$c_password=$_POST["c_password"];
		$ckeck_login=fetch_one("select pk_customer_id,c_email,c_phone,c_date_create,c_fullname,c_password from tbl_customer where c_email='$c_email' and c_password='$c_password'");	
		if($ckeck_login==0){
			echo "<script>alert('Sai tên tài khoản hoặc mật khẩu')</script>";
				
		}
		else{
			$_SESSION["dangnhap"]=$c_email;
			$_SESSION["c_fullname"]=$ckeck_login["c_fullname"];
			$_SESSION["c_phone"]=$ckeck_login["c_phone"];
			$_SESSION["c_date_create"]=$ckeck_login["c_date_create"];
			$_SESSION["pk_customer_id"]=$ckeck_login["pk_customer_id"];
			header("location:index.php?controller=cart");	
		}
	}
	include_once("view/view_login.php");
?>