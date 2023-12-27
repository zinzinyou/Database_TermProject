<?
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host,$dbid,$dbpass,$dbname);
mysqli_query($conn, "set autocommit=0");
mysqli_query($conn, "set session transaction isolation level serializable");
mysqli_query($conn, "begin");

$class_code=$_POST['class_code'];
$hall_name=$_POST['hall_name'];
$teacher_num=$_POST['teacher_num'];

$result=mysqli_query($conn,"UPDATE class SET h_name='$hall_name', t_num='$teacher_num' WHERE c_code='$class_code'");


if(!$result)
{
	mysqli_query($conn,"rollback");
    msg('Query Error : '.mysqli_error($conn));
}
else
{
	mysqli_query($conn,"commit");
    s_msg ('성공적으로 수정 되었습니다');
    echo "<script>location.replace('class_list.php');</script>";
}
mysqli_close($conn);
?>

