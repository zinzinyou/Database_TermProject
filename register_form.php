<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";  
?>
<div class="container">
    <?
    $conn = dbconnect($host, $dbid, $dbpass, $dbname);
	mysqli_query($conn, "set autocommit=0");
	mysqli_query($conn, "set session transaction isolation level serializable");
	mysqli_query($conn, "begin");
    
    $query = "select * from (class natural join level natural join teacher) join hall using(h_name) order by c_month desc, lev_name;";
    $result = mysqli_query($conn, $query);
    if (!$result) {
    	mysqli_query($conn,"rollback");
        die('Query Error : ' . mysqli_error());
    }
    $payments=array("입금","카드");
    $class_months=array("1","2","3","4","5","6","7","8","9","10","11","12");
    ?>
    <form name='register' action='register.php' method='post'>
    	<p align='left'> 수강생 번호 입력: <input type='text' id='student_number' name='student_number'></p>
    	<p> 등록월 선택: 
	    	<select name="app_month" id="app_month" style="width:85%">
	            <option value="-1">등록월을 선택하십시오.</option>
				<?
					foreach($class_months as $c_month){
						if($c_month==$class['c_month']){
							echo "<option value='{$c_month}' selected>{$c_month}월</option>";
						}
						else{
							echo "<option value='{$c_month}'>{$c_month}월</option>";
						}
					}
				?>
	        </select>
        </p>
   
    	<p> 등록 방법 선택 :
    		<select name="payment" id="payment" style="width:85%">
    			<option value="-1">등록방법을 선택하십시오.</option>
    			<?
    				foreach($payments as $payment){
    					echo "<option value='{$payment}'>{$payment}</option>";
    				}
    			?>
    		</select>
    	</p>
    	<table class='table table-striped table-bordered'>
    		<tr>
	            <th>No.</th>
	            <th>수업코드</th>
	            <th>수업요일</th>
	            <th>수업시간</th>
	            <th>개설월</th>
	            <th>레벨</th>
	            <th>강사</th>
	            <th>홀</th>
	            <th>선택</th>
    		</tr>
    		<?
    		$row_index=1;
    		while($row=mysqli_fetch_array($result)){
                echo "<tr>";
                echo "<td>{$row_index}</td>";
                echo "<td>{$row['c_code']}</td>";
                echo "<td>{$row['c_day']}</td>";
                echo "<td>{$row['c_time']}</td>";
                echo "<td>{$row['c_month']}</td>";
                echo "<td>{$row['lev_name']}</td>";
                echo "<td>{$row['t_name']}</td>";
                echo "<td>{$row['h_name']}</td>";
                echo "<td width='17%'>
                    <input type='checkbox' name=c_code[] value='{$row['c_code']}'>
                    </td>";
                echo "</tr>";
                $row_index++;
    		}
    		?>
    	</table>

        <p align="center"><button class="button primary large" onclick="javascript:return validate();">신청</button></p>

		<script>
			function validate(){
				if(document.getElementById("student_number").value==""){
					alert("수강생번호를 입력해 주십시오."); return false;
				}
				else if(document.getElementById("app_month").value==-1){						
					alert("등록월을 선택해 주십시오.");return false;
				}
				else if(document.getElementById("payment").value==-1){
					alert("등록방법을 선택해 주십시오.");return false;
				}
				return true;
			}
		</script>
    </form>
</div>
<? 
mysqli_query($conn,"commit");
mysqli_close($conn);

include("footer.php") 
?>