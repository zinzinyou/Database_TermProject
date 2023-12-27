<?
include "config.php";
include "util.php";
?>

<div class="container">

    <?
    $conn = dbconnect($host, $dbid, $dbpass, $dbname);
	mysqli_query($conn, "set autocommit=0");
	mysqli_query($conn, "set session transaction isolation level serializable");
	mysqli_query($conn, "begin");
    
    $student_number = $_POST['student_number'];
    $app_month = $_POST['app_month'];
    $class_code = $_POST['c_code'];
	$payment = $_POST['payment'];
	

    $available_insert = check_id($conn, $student_number);
    if ($available_insert){
    	// 총 결제금액 구하기
		$total_amount=0;
		foreach($class_code as $c_code){
			$query="SELECT * FROM class NATURAL JOIN level WHERE c_code='$c_code'";
			$result=mysqli_query($conn,$query);
			$total_amount += (mysqli_fetch_array($result)['price']);
		}
		// insert data into application table
		$query = "INSERT INTO application (app_month,payment,tot_amount,s_num) VALUES ('$app_month','$payment',$total_amount,$student_number)";
        mysqli_query($conn, $query);
        $app_num = mysqli_insert_id($conn);
        
        // insert the data into buy_item table
		foreach($class_code as $c_code){
			$query="INSERT INTO register (app_num,c_code) VALUES ('$app_num','$c_code')";
			mysqli_query($conn,$query);
		}
		
		mysqli_query($conn,"commit");
        s_msg('수강신청이 완료되었습니다');
        echo "<script>location.replace('register_list.php');</script>";
    }
    else{
    	mysqli_query($conn,"rollback");
        msg('등록되지 않은 수강생입니다.');
    }
    
    mysqli_close($conn);
    ?>

</div>

