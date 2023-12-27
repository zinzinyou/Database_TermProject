<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수
?>

<?
$conn = dbconnect($host,$dbid,$dbpass,$dbname);
mysqli_query($conn, "set autocommit=0");
mysqli_query($conn, "set session transaction isolation level serializable");
mysqli_query($conn, "begin");
$selected_month=$_POST['month_select'];
$query = "SELECT * FROM (class natural join level natural join teacher) join hall using(h_name) WHERE c_month='$selected_month' ORDER BY c_month DESC,lev_name";
$result = mysqli_query($conn, $query);
if(!$result){mysqli_query($conn,"rollback");}
?>

<div class="container">
	<h3><?echo"$selected_month"?>월 개설수업</h2>
    <table class="table table-striped table-bordered">
        <tr>
            <th>No.</th>
            <th>수업코드</th>
            <th>수업요일</th>
            <th>수업시간</th>
            <th>개설월</th>
            <th>레벨</th>
            <th>강사</th>
            <th>홀</th>
            <th>기능</th>
        </tr>
        <?
        $row_index = 1;
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>{$row_index}</td>";
            echo "<td><a href='class_view.php?class_code={$row['c_code']}'>{$row['c_code']}</a></td>";
            echo "<td>{$row['c_day']}</td>";
            echo "<td>{$row['c_time']}</td>";
            echo "<td>{$row['c_month']}</td>";
            echo "<td>{$row['lev_name']}</td>";
            echo "<td>{$row['t_name']}</td>";
            echo "<td>{$row['h_name']}</td>";
            echo "<td width='17%'>
                <a href='class_form.php?class_code={$row['c_code']}'><button class='button primary small'>수정</button></a>
                <a href='class_delete.php?class_code={$row['c_code']}'><button class='button danger small'>삭제</button></a>
                </td>";
            echo "</tr>";
            $row_index++;
        }
        ?>
    </table>
    	

</div>


<? 
mysqli_query($conn,"commit");
mysqli_close($conn);

include("footer.php") ?>
