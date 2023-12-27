<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수
?>
<div class="container fullwidth">
	<?
		$conn = dbconnect($host, $dbid, $dbpass, $dbname);
		mysqli_query($conn, "set autocommit=0");
		mysqli_query($conn, "set session transaction isolation level serializable");
		mysqli_query($conn, "begin");
		
		if($_POST['app_number']){
			$app_num=$_POST['app_number'];	
		} else{
			$app_num=$_GET['app_num'];
		}
		
		$query = "SELECT * FROM (register NATURAL JOIN application NATURAL JOIN student) WHERE app_num=$app_num";
		$result = mysqli_query($conn, $query);
	    if (!$result) {
	    	mysqli_query($conn,"rollback");
	        die('Query Error : ' . mysqli_error());
	    }
		$application = mysqli_fetch_array($result);
		if (!$application){
			mysqli_query($conn,"rollback");
			msg("수강신청 이력이 없습니다.");
		}

	?>
	<h3>수강신청 내역</h3>
	
	<p>
		<label for="app_num">수강신청 번호</label>
		<input readonly type="text" id="app_num" name="app_num" value="<?=$application['app_num'] ?>">
	</p>
	<p>
		<label for="s_num">수강생 번호</label>
		<input readonly type="text" id="s_num" name="s_num" value="<?=$application['s_num'] ?>">
	</p>
	<p>
		<label for="s_name">수강생 이름</label>
		<input readonly type="text" id="s_name" name="s_name" value="<?=$application['s_name'] ?>">
	</p>
	<p>
		<label for="app_month">수강신청월</label>
		<input readonly type="text" id="app_month" name="app_month" value="<?=$application['app_month'] ?>">
	</p>
	<p>
		<label for="payment">등록방법</label>
		<input readonly type="text" id="payment" name="payment" value="<?=$application['payment'] ?>">
	</p>
	<p>
		<label for="tot_amount">총등록금액</label>
		<input readonly type="text" id="tot_amount" name="tot_amount" value="<?=$application['tot_amount'] ?>">
	</p>
</div>

<div class="container">
	<table class="table table-striped table-bordered">
		<p>
			<label for="list">신청수업목록</label>
		</p>
		<tr>
			<th>No.</th>
			<th>수업코드</th>
			<th>수업요일</th>
			<th>레벨</th>
			<th>강사</th>
			<th>가격</th>
		</tr>
		<?
		$query = "SELECT * FROM (application NATURAL JOIN register NATURAL JOIN class NATURAL JOIN teacher NATURAL JOIN level) WHERE app_num=$app_num";
        $result = mysqli_query($conn, $query);
        if(!$result){mysqli_query($conn,"rollback");}
        
        $row_num = mysqli_num_rows($result);
        for($row_index=1;$row_index<=$row_num;$row_index++){
            $row= mysqli_fetch_array($result);
            echo "<tr>";
            echo "<td>{$row_index}</td>";
            echo "<td><a href='class_view.php?class_code={$row['c_code']}'>{$row['c_code']}</a></td>";
            echo "<td>{$row['c_day']}</td>";
            echo "<td>{$row['lev_name']}</td>";
            echo "<td>{$row['t_name']}</td>";
            echo "<td>{$row['price']}</td>";
            echo "</tr>";
        }
		?>
	</table>
	<p align='center'>
		<a href="javascript:void(0);" onclick="cancelApplication(<?=$application['app_num']?>);">
			<button class='button danger large'>수강신청 취소</button>
		</a>
	</p>
	<?
	mysqli_query($conn,"commit");
	?>
</div>

<script>
    function cancelApplication(appNum) {
        var confirmCancel = confirm('수강신청을 취소하시겠습니까?');
        if (confirmCancel) {
            window.location.href = 'app_delete.php?app_num=' + appNum;
        } else {
			//do nothing if user cancels.
        }
    }
</script>

<? 
mysqli_close($conn);
include "footer.php"
?>