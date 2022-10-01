<?php

use PHPMailer\PHPMailer\PHPMailer;

include_once("controller/cart.php");
include('libs/phpqrcode/qrlib.php');
require_once 'PHPMAILER/Exception.php';
require_once 'PHPMAILER/PHPMailer.php';
require_once 'PHPMAILER/SMTP.php';

// $mail =  new PHPMailer();
// $mail->isSMTP();
// $mail->Host = 'smtp.gmail.com';
// $mail->SMTPAuth = true;
// $mail->Username = '';
// $mail->Password = '';
// $mail->SMTPSecure = 'ssl';
// $mail->Port = 587;
// $mail->setFrom('');
// $mail->addAddress('');
// $mail->isHTML(true);
// // $mail->addAttachment('/var/tmp/file.tar.gz'); 
// $mail->Subject = 'Test';
// $mail->Body = 'TEST';
// $mail->send();

$act = isset($_GET["act"]) ? $_GET["act"] : "";
switch ($act) {
	case 'add':
		$id = isset($_GET["id"]) ? $_GET["id"] : 0;
		$product = fetch_one("select * from tbl_product where pk_product_id=$id");
		cart_add($product, $id);
		header("location:index.php?controller=cart");
		break;
	case 'delete':
		$id = isset($_GET["id"]) ? $_GET["id"] : 0;
		cart_delete($id);
		header("location:index.php?controller=cart");
		break;
	case 'destroy':
		cart_destroy();
		header("location:index.php?controller=cart");
		break;
		// Xử lý giỏ hàng	
	case 'bill':
		$f = "visit.php";

		if (!file_exists($f)) {
			touch($f);
			$handle =  fopen($f, "w");
			fwrite($handle, 0);
			fclose($handle);
		}



		function getUsernameFromEmail($email)
		{
			$find = '@';
			$pos = strpos($email, $find);
			$username = substr($email, 0, $pos);
			return $username;
		}

		if (isset($_SESSION["pk_customer_id"]))
			$c_date_create = date("y/m/d");
		$pk_customer_id = $_SESSION["pk_customer_id"];

		//$insert_id=fetch_one("select pk_transaction_id tbl_transaction order by pk_transaction_id desc limit 0,1");
		//$fk_transaction_id=$insert_id["pk_transaction_id"];
		$insert_id = fetch_one("select pk_transaction_id from tbl_transaction order by pk_transaction_id desc limit 0,1");
		$fk_transaction_id = $insert_id["pk_transaction_id"];

		execute("insert into tbl_transaction (fk_customer_id,c_date_create) values($pk_customer_id,'$c_date_create')");

		$fk_transaction_id += 1;

		$mangc_number = [];
		$i = 0;

		//echo $insert_id["pk_transaction_id"];
		foreach ($_SESSION["cart"] as $product) {
			$i++;
			$c_number = $product["number"];
			$c_price = $product["c_price"];
			$c_name = $product["c_name"];
			$fk_product_id = $product["pk_product_id"];
			$mangc_number[] = "Tên SP $i: $c_name - SL $i: $c_number - Giá $i: $c_price";

			execute("insert into tbl_order(fk_transaction_id,fk_product_id,c_number,c_price,c_date_create) values($fk_transaction_id,$fk_product_id,$c_number,$c_price,'$c_date_create')");
			//echo "insert into tbl_order(fk_transaction_id,fk_product_id,c_number,c_price,c_date_create) values($fk_transaction_id,$fk_product_id,$c_number,$c_price,'$c_date_create')";	
		}

		$tempDir = 'temp/';
		$total = number_format(cart_total());
		$email =  $_SESSION["dangnhap"];
		$ngaygiao = $_SESSION["c_date_create"];
		$tenkh = $_SESSION["c_fullname"];
		$dienthoai =  $_SESSION["c_phone"];

		$filename = getUsernameFromEmail($email);
		// $codeContents = 'mail:'.$email; 
		$item = implode("\n", $mangc_number);

		// $item = print_r($item1);



		$codeContents = "Xin chào anh/chị: $tenkh,\nAnh chị vui lòng kiểm tra các thông tin sau.\nNgày tạo đơn: $ngaygiao,\nEmail đặt hàng:$email,\nSố điện thoại nhận hàng:$dienthoai,\nGiá tiền: $total.Đ, \nCác sản phẩm sau:\n$item\n";

		// alert 
		echo "<script type='text/javascript'>alert('$codeContents');</script>";


		QRcode::png($codeContents, $tempDir . '' . $filename . '.png', QR_ECLEVEL_L, 5);
		$to = '';

		// Sender 
		$from = 'minhman10092000@gmail.com';
		$fromName = 'DuyThuy';

		// Email subject 
		$subject = 'Bạn Đã Đặt Một Đơn Hàng Thành Công';

		// Attachment file 
		$file = "temp/$filename.png";

		// Email body content 
		$htmlContent = ' 
									<h3>Cảm ơn bạn đã đặt hàng của chúng tôi</h3> 
									<p>Mời bạn kiểm tra thông tin thông qua mã QR Code bên dưới.</p> 
								';

		// Header for sender info 
		$headers = "From: $fromName" . " <" . $from . ">";

		// Boundary  
		$semi_rand = md5(time());
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

		// Headers for attachment  
		$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

		// Multipart boundary  
		$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
			"Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";

		// Preparing attachment 
		if (!empty($file) > 0) {
			if (is_file($file)) {
				$message .= "--{$mime_boundary}\n";
				$fp =    @fopen($file, "rb");
				$data =  @fread($fp, filesize($file));

				@fclose($fp);
				$data = chunk_split(base64_encode($data));
				$message .= "Content-Type: application/octet-stream; name=\"" . basename($file) . "\"\n" .
					"Content-Description: " . basename($file) . "\n" .
					"Content-Disposition: attachment;\n" . " filename=\"" . basename($file) . "\"; size=" . filesize($file) . ";\n" .
					"Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
			}
		}
		$message .= "--{$mime_boundary}--";
		$returnpath = "-f" . $from;

		$total_price = cart_total();


		// Send email 
		$mail = @mail($to, $subject, $message, $headers, $returnpath);

		// echo "<script>alert('Đặt hàng thành công!')</script>";
		$link_payment = "https://www.nganluong.vn/button_payment.php?receiver=nguyenvandung23041995@gmail.com&product_name=" . $fk_transaction_id . "&price=" . $total_price . "&return_url=http://localhost/cnm/index.php?controller=cart&comments=1234";

		header("location:" . $link_payment);
		cart_destroy();
		break;
}
//cập nhật số lượng trong giỏ hàng
if (isset($_GET["id"]) && isset($_GET["number"])) {
	//echo $_GET["id"]."<>".$_GET["number"];
	//		die("staop");
	$product = fetch_one("select * from tbl_product where pk_product_id=" . $_GET["id"]);

	if ($_GET["number"] > 0) {
		$_SESSION["cart"][$_GET["id"]] = array(
			'pk_product_id' => $_GET["id"],
			'c_name' => $product['c_name'],
			'c_img' => $product['c_img'],
			'number' => $_GET["number"],
			'c_price' => $product['c_price']
		);
	} else {
		cart_delete($_GET["id"]);
	}
}


include_once("view/view_cart.php");
