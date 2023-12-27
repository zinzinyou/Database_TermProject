<?
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host,$dbid,$dbpass,$dbname);
mysqli_query($conn, "set autocommit=0");
mysqli_query($conn, "set session transaction isolation level serializable");
mysqli_query($conn, "begin");
$app_num = $_GET['app_num'];


$ret = mysqli_query($conn, "DELETE FROM register WHERE app_num=$app_num");
if(!$ret){
	mysqli_query($conn,"rollback");
	msg('Query Error : '.mysqli_error($conn));
}
else{
	$ret = mysqli_query($conn, "DELETE FROM application WHERE app_num=$app_num");	
	if(!$ret){
		mysqli_query($conn,"rollback");
		msg('Query Error : '.mysqli_error($conn));
	}
	else{
		mysqli_query($conn,"commit");
		s_msg ('성공적으로 삭제되었습니다');
		echo "<meta http-equiv='refresh' content='0;url=app_search.php'>";
	}
}

mysqli_close($conn);
?>