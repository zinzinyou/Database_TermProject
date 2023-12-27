<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);
mysqli_query($conn, "set autocommit=0");
mysqli_query($conn, "set session transaction isolation level serializable");
mysqli_query($conn, "begin");

$mode = "입력";
$action = "class_insert.php";

if (array_key_exists("class_code", $_GET)) {
	$class_code=$_GET["class_code"];
	$query="SELECT * FROM class where c_code='$class_code'";
	$result=mysqli_query($conn,$query);
	$class=mysqli_fetch_array($result);
	if(!$class){
		mysqli_query($conn,"rollback");
		msg("수업이 존재하지 않습니다.");
	}
	$mode="수정";
	$action="class_modify.php";
}

// DB에서 강사정보 가져오기
$teachers = array();
$query = "select * from teacher";
$result = mysqli_query($conn,$query);
if(!$result){mysqli_query($conn,"rollback");}
while($row=mysqli_fetch_array($result)){
	$teachers[$row['t_num']]=$row['t_name'];
}

// DB에서 홀정보 가져오기
$halls=array();
$query="select * from hall";
$row_index=1;
$result=mysqli_query($conn,$query);
if(!$result){mysqli_query($conn,"rollback");}
while($row=mysqli_fetch_array($result)){
	$halls[$row_index]=$row['h_name'];
	$row_index++;
}

// DB에서 레벨정보 가져오기
$levels=array();
$query="select * from level";
$row_index=1;
$result=mysqli_query($conn,$query);
if(!$result){mysqli_query($conn,"rollback");}
while($row=mysqli_fetch_array($result)){
	$levels[$row_index]=$row['lev_name'];
	$row_index++;
}

// month 정보 배열로 저장
$class_months=array("1","2","3","4","5","6","7","8","9","10","11","12");


?>
    <div class="container">
        <form name="class_form" action="<?=$action?>" method="post" class="fullwidth">
            <h3>개설수업 정보 <?=$mode?></h3>
            <p>
            	<label for="class_code">수업코드</label>
            	<input type="text" maxlength="10" placeholder="수업코드 입력" id="class_code" name="class_code" value="<?=$class['c_code']?>"/>
            </p>
            <p>
                <label for="class_day">수업요일</label>
                <input type="text" placeholder="수업요일 입력" id="class_day" name="class_day" value="<?=$class['c_day']?>"/>
            </p>
            <p>
                <label for="class_time">수업시간</label>
                <input type="text" placeholder="수업시간 입력" id="class_time" name="class_time" value="<?=$class['c_time']?>"/>
            </p>
            <p>
            	<label for="class_month">개설월</label>
            	<select name="class_month" id="class_month">
            		<option value="-1">개설월을 선택하십시오.</option>

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
            <p>
            	<label for="level_name">레벨</label>
            	<select name="level_name" id="level_name">
            		<option value="-1">레벨을 선택하십시오.</option>
            		<?
            			foreach($levels as $lev_name){
            				if($lev_name==$class['lev_name']){
            					echo "<option value='{$lev_name}' selected>{$lev_name}</option>";
            				}
            				else{
            					echo "<option value='{$lev_name}'>{$lev_name}</option>";
            				}
            			}
            		?>
            	</select>
            </p>
            <p>
            	<label for="teacher_num">강사</label>
            	<select name="teacher_num" id="teacher_num">
            		<option value="-1">강사를 선택하십시오.</option>
            		<?
            			foreach($teachers as $t_num=>$t_name){
            				if($t_num==$class['t_num']){
            					echo "<option value='{$t_num}' selected>{$t_name}</option>";
            				}
            				else{
            					echo"<option value='{$t_num}'>{$t_name}</option>";	
            				}
            			}
            		?>
            	</select>
            </p>
            <p>
            	<label for="hall_name">홀</label>
            	<select name="hall_name" id="hall_name">
            		<option value="-1">홀을 선택하십시오.</option>
            		<?
            			foreach($halls as $h_name){
            				if($h_name==$class['h_name']){
            					echo "<option value='{$h_name}' selected>{$h_name}</option>";
            				}
            				else{
            					echo"<option value='{$h_name}'>{$h_name}</option>";	
            				}
            			}
            		?>
            	</select>
            </p>
            <br>

            <p align="center"><button class="button primary large" onclick="javascript:return validate();"><?=$mode?></button></p>
            
			<script>
				function validate(){
					if(document.getElementById("class_code").value==""){
						alert("수업코드를 입력해 주십시오."); return false;
					}
					else if(document.getElementById("class_day").value==""){
						alert("수업요일을 입력해 주십시오.");return false;
					}
					else if(document.getElementById("class_time").value==""){
						alert("수업시간을 입력해 주십시오.");return false;
					}
					else if(document.getElementById("class_month").value==-1){
						alert("개설월을 선택해 주십시오.");return false;
					}
					else if(document.getElementById("level_name").value==-1){
						alert("레벨을 선택해 주십시오.");return false;
					}
					else if(document.getElementById("teacher_num").value==-1){
						alert("강사를 선택해 주십시오.");return false;
					}
					else if(document.getElementById("hall_name").value==-1){
						alert("홀을 선택해 주십시오.");return false;
					}
					return true;
				}
			</script>
        </form>
        <?
        mysqli_query($conn,"commit");
        mysqli_close($conn);
        ?>
    </div>
    
<? 
include("footer.php") 
?>