<?
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host,$dbid,$dbpass,$dbname);
mysqli_query($conn, "set autocommit=0");
mysqli_query($conn, "set session transaction isolation level serializable");
mysqli_query($conn, "begin");

$class_code=$_GET['class_code'];

$pid_ret = mysqli_query($conn, "select c_code from register where c_code = '$class_code'");

if(!mysqli_fetch_array($pid_ret)){
	$ret = mysqli_query($conn, "delete from class where c_code = '$class_code'");
	
	if(!$ret)
	{
		mysqli_query($conn,"rollback");
	    msg('Query Error : '.mysqli_error($conn));
	}
	else
	{
		mysqli_query($conn,"commit");
	    s_msg ('성공적으로 취소되었습니다');
	    echo "<meta http-equiv='refresh' content='0;url=class_list.php'>";
	}	
}
else{
	mysqli_query($conn,"rollback");
	s_msg ('이미 신청된 수업이므로 삭제할 수 없습니다.');
    echo "<meta http-equiv='refresh' content='0;url=class_list.php'>";
}
mysqli_close($conn);
?>

