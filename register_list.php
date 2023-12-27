<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

?>

<div class="container">
	<br>
    <table class="table table-striped table-bordered">
        <tr>
            <th>수강신청번호</th>
            <th>수강생명</th>
            <th>수강신청월</th>
            <th>등록방법</th>
            <th>총액</th>
        </tr>
    <?
    $conn = dbconnect($host, $dbid, $dbpass, $dbname);
	mysqli_query($conn, "set autocommit=0");
	mysqli_query($conn, "set session transaction isolation level serializable");
	mysqli_query($conn, "begin");
    
    $query = "select app_num, s_name,app_month, payment, tot_amount from application natural join student";
    $result = mysqli_query($conn, $query);
    if(!$result){
    	mysqli_query($conn,"rollback");
    }
    while($row=mysqli_fetch_array($result)){
        echo "<tr><td><a href='app_list.php?app_num={$row[0]}'>$row[0]</td>";
        echo "<td>$row[1]</td>";
        echo "<td>$row[2]</td>";
        echo "<td>$row[3]</td>";
        echo "<td>$row[4]</td></tr>";
    }
    ?>
    </table>
</div>
    
<?
mysqli_query($conn,"commit");
mysqli_close($conn);

include "footer.php"
?>
