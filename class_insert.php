<?
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host,$dbid,$dbpass,$dbname);
mysqli_query($conn, "set autocommit=0");
mysqli_query($conn, "set session transaction isolation level serializable");
mysqli_query($conn, "begin");

$class_code=$_POST['class_code'];
$class_day=$_POST['class_day'];
$class_time=$_POST['class_time'];
$class_month=$_POST['class_month'];
$level_name=$_POST['level_name'];
$hall_name=$_POST['hall_name'];
$teacher_num=$_POST['teacher_num'];

$query="INSERT INTO class (c_code, c_day, c_time, c_month,lev_name,h_name,t_num) VALUES ('$class_code','$class_day','$class_time','$class_month','$level_name','$hall_name','$teacher_num')";
$result=mysqli_query($conn,$query);
if(!$result){
	mysqli_query($conn,"rollback");
	msg('Querry Error : '.mysqli_error($conn));
}
else{
	mysqli_query($conn,"commit");
	s_msg('성공적으로 입력되었습니다.');
	echo "<script>location.replace('class_list.php');</script>";
}
mysqli_close($conn);
?>

