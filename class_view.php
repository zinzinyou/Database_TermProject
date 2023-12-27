<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);
mysqli_query($conn, "set autocommit=0");
mysqli_query($conn, "set session transaction isolation level serializable");
mysqli_query($conn, "begin");

if (array_key_exists("class_code",$_GET)){
	$class_code=$_GET["class_code"];
	$query = "SELECT * FROM (class NATURAL JOIN level NATURAL JOIN teacher) JOIN hall using(h_name) WHERE c_code='$class_code'";
	$result=mysqli_query($conn,$query);
	if(!$result){
		mysqli_query($conn,"rollback");
	}
	$class=mysqli_fetch_assoc($result);
	if(!$class){
		mysqli_query($conn,"rollback");
		msg("수업이 존재하지 않습니다.");
	}
}
?>
    <div class="container fullwidth">
        <h3>개설수업 정보 상세 보기</h3>
		<p>
			<label for="class_code">수업 코드</label>
			<input readonly type="text" id="class_code" name="class_code" value="<?= $class['c_code'] ?>"/>
		</p>
		<p>
			<label for="level_name">레벨</label>
			<input readonly type="text" id="level_name" name="level_name" value="<?= $class['lev_name'] ?>"/>
		</p>
		<p>
			<label for="class_month">개설월</label>
			<input readonly type="text" id="class_month" name="class_month" value="<?= $class['c_month'] ?>"/>
		</p>
		<p>
			<label for="class_day">수업 요일</label>
			<input readonly type="text" id="class_day" name="class_day" value="<?= $class['c_day'] ?>"/>
		</p>
		<p>
			<label for="class_time">수업 시간</label>
			<input readonly type="text" id="class_time" name="class_time" value="<?= $class['c_time'] ?>"/>
		</p>
		<p>
			<label for="price">수강료</label>
			<input readonly type="text" id="price" name="price" value="<?= $class['price'] ?>"/>
		</p>
		<p>
			<label for="teacher_name">강사</label>
			<input readonly type="text" id="teacher_name" name="teacher_name" value="<?= $class['t_name'] ?>"/>
		</p>
		<p>
			<label for="hall_name">홀</label>
			<input readonly type="text" id="hall_name" name="hall_name" value="<?= $class['h_name'] ?>"/>
		</p>
		<p>
			<label for="hall_floor">홀 위치</label>
			<input readonly type="text" id="hall_floor" name="hall_floor" value="<?= $class['h_floor'] ?>"/>
		</p>
    </div>
    <div class="container">
    	<table class="table table-striped table-bordered">
	    	<p>
	    		<label for="student_list">수강생 목록</label>
	    	</p>
			<tr>
				<th>No.</th>
				<th>수강생코드</th>
				<th>수강생명</th>
				<th>전화번호</th>
			</tr>
			<?
			$query = "SELECT * FROM register NATURAL JOIN application NATURAL JOIN student WHERE c_code='$class_code'";
	        $result = mysqli_query($conn, $query);
	        if(!$result){
	        	mysqli_query($conn,"rollback");
	        }
	        
	        $row_num = mysqli_num_rows($result);
	        for($row_index=1;$row_index<=$row_num;$row_index++){
	            $row= mysqli_fetch_array($result);
	            echo "<tr>";
	            echo "<td>{$row_index}</td>";
	            echo "<td>{$row['s_num']}</td>";
	            echo "<td>{$row['s_name']}</td>";
	            echo "<td>{$row['s_phone']}</td>";
	            echo "</tr>";
	        }
			?>
		</table>
    </div>
<? 
mysqli_query($conn,"commit");
mysqli_close($conn);

include "footer.php" ?>