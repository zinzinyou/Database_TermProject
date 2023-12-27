<?
	include "header.php";
	include "config.php";    //데이터베이스 연결 설정파일
	include "util.php";
	
	$class_months=array("1","2","3","4","5","6","7","8","9","10","11","12");
?>
<div class="container">
	<form name="app_search" action="app_list.php" method="POST">
		<label for='app_number' style="font-size:1.5em;line-height:2em;">수강신청번호로 조회하기</label>
		<p align='left'> 수강신청번호 입력: <input type='text' name='app_number'></p>
		<input type='submit' class='button small' value=조회>
	</form>
	<br><br><br>
	<form name="app_search_by_sname" action="app_list2.php" method="POST">
		<label for='s_name' style="font-size:1.5em;line-height:2em;">수강생 이름으로 조회하기</label>
		<p align='left'> 수강생 이름 입력: <input type='text' name='s_name'></p>
		<p align='left'> 수강신청 월 입력:
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
		<input type='submit' class='button small' value=조회>
	</form>
</div>

<?
	include "footer.php";
?>

