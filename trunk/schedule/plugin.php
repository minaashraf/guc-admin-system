<?php

/*
Plugin Name: GUC Schedules
Plugin URI: http://www.galalaly.me/guc-schedule
Description: Beautify your schedule
Version: 0.1
Author: Galal Aly, Hadeer Younis
Author URI: http://www.galalaly.me
License: GPL2
*/
register_activation_hook(__FILE__,'install_guc_schedule');

add_filter('the_content', 'guc_work');
add_action('init', 'generate_schedule');
add_action('init', 'unlock_transcript');
add_action('admin_menu', 'guc_menu');

wp_register_script('guc-colorpicker', WP_PLUGIN_URL.'/gucTables/js/colorpicker.js');
wp_register_style('guc-colorpicker', WP_PLUGIN_URL.'/gucTables/css/colorpicker.css');

wp_enqueue_script('jquery');
wp_enqueue_script('guc-colorpicker');
wp_enqueue_style('guc-colorpicker');

function install_guc_schedule(){
	register_setting('guc-schedule', 'enable');
	update_option('enable', '0');
	register_setting('guc-schedule', 'pdf');
	update_option('pdf', '0');
}

function guc_work($content){
	$test = explode('[guc-schedule]', $content);
	if(count($test) != 1){
		//Our page
		if(get_option('enable') == '1')
		return "Under Construction";
		$return =  <<<HTML
		<h2>Instructions</h2>
		<ol>
			<li>Enter your admin system's username and password below.</li>
			<li>If you would like to import your schedule into your calendar (outlook/google/ical.. etc) then check the output format you would like.</li>
			<li>Choose your colors below then click 'Generate Schedule'.</li>
		</ol>
		<form action="" method="POST">
		<label for="username"><b>Username</b></label><br>
			<input type="text" name="username">
		<label for="password"><b>Password</b></label><br>
			<input type="password" name="password">
<div style="border-top:1px dotted #777;margin:10px 0"></div>
			<input name="ics" type="checkbox" id="ics"/>
			<label style="display:inline-block !important;vertical-align:bottom"for="ics"><b>Generate ICS file?</b></label><br/>
			<input name="csv"type="checkbox" id="csv"/>
			<label for="csv" style="display:inline-block !important;vertical-align:bottom"><b>Generate CSV file?</b></label>
			
			
<div style="border-top:1px dotted #777;margin:10px 0"></div>
	<h1>Lectures</h1>
	<div style="float: left;padding-right:10px;border-right:1px solid #666">
		<label for="input_lectures"><b>Lectures color</b></label><br>
		<div style="display:inline-block"id="lectures_color"></div>
		<input id="input_lectures" name="lecture" type="hidden" value="">
	</div>
	<div style="float: left; margin-top: 10px; margin-left: 10px;">
		<b>Font color: </b>
		<select style="width:150px" name="lectures_font">
			<option value="white">White</option>
 			<option value="yellow">Yellow</option>
  			<option value="black" selected>Black</option>
		</select>
		<br/><br/>
		<input type="checkbox" name="lectures_font_bold"> Bold text?
	</div>
			<br style="clear:both"/>
<div style="border-top:1px dotted #777;margin:10px 0"></div>
	<h1>Tutorials</h1>
	<div style="float: left;padding-right:10px;border-right:1px solid #666">
		<label for="input_tutorials"><b>Tutorials color</b></label><br>
		<div style="display:inline-block"id="tutorials_color"></div>
		<input id="input_tutorials" name="tutorial" type="hidden" value="">
	</div>
	<div style="float: left; margin-top: 10px; margin-left: 10px;"><b>Font color: </b>
		<select style="width:150px" name="tutorials_font">
			<option value="white">White</option>
 			<option value="yellow">Yellow</option>
  			<option value="black" selected>Black</option>
		</select>
		<br/><br/>
		<input type="checkbox" name="tutorials_font_bold"> Bold text?
	</div>

			<br style="clear:both"/>
		<div style="border-top:1px dotted #777;margin:10px 0"></div>
			<h1>Labs</h1>
		
		<div style="float: left;padding-right:10px;border-right:1px solid #666">
		<label for="input_labs"><b>Labs color</b></label><br>
		<div style="display:inline-block"id="labs_color"></div>
		<input id="input_labs" name="lab" type="hidden" value="">
	</div>
	<div style="float: left; margin-top: 10px; margin-left: 10px;"><b>Font color: </b>
		<select style="width:150px"name="labs_font">
			<option value="white">White</option>
 			<option value="yellow">Yellow</option>
  			<option value="black" selected>Black</option>
		</select>
		<br/><br/>
		<input type="checkbox" name="labs_font_bold"> Bold text?
	</div>
			
			
			<br style="clear:both"/>
			
		<div style="border-top:1px dotted #777;margin:10px 0"></div>
			<h1>Free Slots</h1>
		<label for="input_free"><b>Free slots color</b></label><br>
		<div id="free_color"></div>
			<input id="input_free" name="free" type="hidden" value="">
		<label for="input_dayoff"><b>Dayoff color</b></label><br>
		<div id="dayoff_color"></div>
			<input id="input_dayoff" name="dayoff" type="hidden" value="">
			<br><br>
			<input type="submit" value="Generate Schedule">
			<input type="hidden" name="guc_schedule_galal">
		</form>
		<script type="text/javascript">
			jQuery('#lectures_color').ColorPicker({
				color: '#0000ff',
				onShow: function (colpkr) {
					jQuery(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					jQuery(colpkr).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					jQuery('#colorSelector div').css('backgroundColor', '#' + hex);
					jQuery('#input_lectures').val('#'+hex);
				},
				flat: true
			});
			
			jQuery('#tutorials_color').ColorPicker({
				color: '#0000ff',
				onShow: function (colpkr) {
					jQuery(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					jQuery(colpkr).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					jQuery('#colorSelector div').css('backgroundColor', '#' + hex);
					jQuery('#input_tutorials').val('#'+hex);
				},
				flat: true
			});
			jQuery('#labs_color').ColorPicker({
				color: '#0000ff',
				onShow: function (colpkr) {
					jQuery(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					jQuery(colpkr).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					jQuery('#colorSelector div').css('backgroundColor', '#' + hex);
					jQuery('#input_labs').val('#'+hex);
				},
				flat: true
			});
			jQuery('#free_color').ColorPicker({
				color: '#0000ff',
				onShow: function (colpkr) {
					jQuery(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					jQuery(colpkr).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					jQuery('#colorSelector div').css('backgroundColor', '#' + hex);
					jQuery('#input_free').val('#'+hex);
				},
				flat: true
			});
			jQuery('#dayoff_color').ColorPicker({
				color: '#0000ff',
				onShow: function (colpkr) {
					jQuery(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					jQuery(colpkr).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					jQuery('#colorSelector div').css('backgroundColor', '#' + hex);
					jQuery('#input_dayoff').val('#'+hex);
				},
				flat: true
			});
		</script>
HTML;
	$toBe = str_ireplace('[guc-schedule]', $return, $content);
	return $toBe;
	}
	else{
		$check = explode('[guc-transcript]', $content);
		if(count($check) != 1){
			$form = <<<HTML
				<form action="" method="POST">
				<b>Username</b><br>
				<input type="text" name="username"><br>
				<b>Password</b><br>
				<input type="password" name="password"><br><br>
				<input type="submit" value="Unlock Transcript">
				<input type="hidden" name="unlock_transcript_galal">
				</form>		
HTML;
		return str_ireplace('[guc-transcript]', $form, $content);
		}
	}
	return $content;
}

function generate_schedule(){
$data[]=array(array());
$num = 0;
	if(isset($_POST['guc_schedule_galal']) && isset($_POST['username']) && isset($_POST['password'])){
		//Act ba2a
		$username = $_POST['username'];
		$pass = $_POST['password'];
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, 'http://student.guc.edu.eg/Web/Student/Schedule/GroupSchedule.aspx'); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM); 
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8) Gecko/20051111 Firefox/1.5 BAVM/1.0.0');
			curl_setopt($ch, CURLOPT_USERPWD, "$username:$pass");
			
			$contents = curl_exec($ch);
			curl_close($ch);
			$generated = "<table style='font-family:Tahoma; padding:5px; border-collapse:collapse; font-size:14pt; width:auto; border-style:solid; border-color:black; border-width:1px;' align='center' id='student_schedule' border='1'><tr>\n\t<td width='15%'>Day</td>\n\t<td width='15%' align='center'>First Slot</td>\n\t<td width='15%' align='center'>Second Slot</td><td width='15%' align='center'>Third Slot</td><td width='15%' align='center'>Fourth Slot</td><td width='15%' align='center'>Fifth Slot</td></tr>\n";
			if(!(strpos($contents, '##############################################################################') && strpos($contents, 'GUC Administration System') && strpos($contents, 'Alfred Raouf')))
			{
				wp_die("There's an error fetching the schedule. The Admin system might be down or your username/password is/are incorrect.");
			}
			else{
				//work then download
				require_once(WP_PLUGIN_DIR.'/gucTables/simple_html_dom.php');
				$html = str_get_html($contents);
				$days = array('Xrw1', 'Xrw2', 'Xrw3', 'Xrw4', 'Xrw5', 'Xrw6');
				$dayOffs = array('XaltR1','XaltR2','XaltR3','XaltR4','XaltR5','XaltR6');
				$week = array('Saturday','Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday');
				$lecture_id = 1;
				$tut_id = 1;
				$lab_id = 1;
				$day_counter = 0;
				$courses = array();
				$totalNumSlots = 0;
				foreach($days as $dayId){
					$day = $html->find("tr[id=$dayId]");
					if($day!= null){
						$inners = $day[0]->children();
						$DAY = $day[0]->children(0)->children(0)->children(0)->plaintext;
					}
					else
						$inners = array();			
					$slot = 1;
					$i = 0;
					if($i != 0)
							$generated .= "</tr>";
					$generated .= "<tr id='day_$day_counter'>";
					$generated .= "<td>$week[$day_counter]</td>";
					$day_counter++;
					foreach($inners as $tr){
						$i = 1;
						$tables = $tr->children();
						//tables elly gowa el main td
						foreach($tables as $table){
						$location = '';
						$description = '';
							$table->border = '0';
							if(strpos($table->innertext, 'Lab') != false){
								if(!(isset($_POST['lab'])) || strlen(trim($_POST['lab'])) == 0)
									$tdcolor = 'yellow';
								else
									$tdcolor = $_POST['lab'];
								//$table->parent()->bgcolor = $color;
								$bold = isset($_POST['labs_font_bold']);
								if($bold){
									$bold = '<strong>';
									$boldEnd = '</strong>';
								}
								$check = array('white', 'yellow', 'black');
								if(isset($_POST['labs_font']) && in_array($_POST['labs_font'], $check))
									$fontcolor = $_POST['labs_font'];
								else
									$fontcolor = 'black';
								$tds = $table->find('td');
								foreach($tds as $td){
									//Remove the Tutorial Group
									if($td->width == '20'){
										//$td->style = 'display:none';
									}
									else if($td->width == '40'){
										//Get the place
										$location = $td->plaintext;
										//$td->style = 'display:none';
									}
									else{
										$description = $td->plaintext;
										$course = explode(' ', $description);
										$course = trim($course[0] . preg_replace("/[^0-9]/", '', $course[1]));
										if(!isset($courses[$course]))
											$courses[$course] = 1;
										else
											$courses[$course]++;
										$generated .= "<td width='15%' id='lab_$lab_id' bgcolor='$tdcolor'><font size='2' color='$fontcolor'><center>$bold $td->plaintext @ $location $boldEnd</center></font></td>";
										$lab_id++;
									}
								}
								$data[$num][0]=$DAY;
								$data[$num][1]=$slot;
								$data[$num][2]=$description;
								$data[$num][3]=$location;
								$slot+=1;
								$num+=1;
							}
							else if(strpos($table->innertext, 'Lecture') != false){
								$bold = isset($_POST['lectures_font_bold']);
								if($bold){
									$bold = '<strong>';
									$boldEnd = '</strong>';
								}
								$check = array('white', 'yellow', 'black');
								if(isset($_POST['lectures_font']) && in_array($_POST['lectures_font'], $check))
									$fontcolor = $_POST['lectures_font'];
								else
									$fontcolor = 'black';
								$lec = $table->plaintext;
								$lecLocation = explode(')', $lec);
								$lecLocation = $lecLocation[1];
								$lecTitle = explode('(', $lec);
								$lecTitle = $lecTitle[0];
								$course = explode(' ', $lecTitle);
								$course = trim($course[0] . $course[1]);
								if(!isset($courses[$course]))
									$courses[$course] = 1;
								else
									$courses[$course]++;
								$location = $lecLocation;
								$description = $lecTitle;
								if(!(isset($_POST['lecture'])) || strlen(trim($_POST['lecture'])) == 0)
									$tdcolor = 'red';
								else
									$tdcolor = $_POST['lecture'];
								$table->innertext = "<font id='$lecture_id' color='$color' size='2' face='Tahoma'><center>$bold" . $lecTitle . " @ $lecLocation $boldEnd</center></font>";		
								$generated .= "<td width='15%' id='lecture_$lecture_id' bgcolor='$tdcolor'><font size='2' color='$fontcolor'><center>$bold $table->plaintext $boldEnd</center></font></td>";
										$lecture_id++;
								$table->parent()->bgcolor = $color;
								$data[$num][0]=$DAY;
								$data[$num][1]=$slot;
								$data[$num][2]=$description;
								$data[$num][3]=$location;
								$slot+=1;
								$num+=1;
							}
							else if(strpos($table->innertext, 'Tut') != false){
								if(!(isset($_POST['tutorial'])) || strlen(trim($_POST['tutorial'])) == 0)
									$tdcolor = 'blue';
								else
									$tdcolor = $_POST['tutorial'];
//								$table->parent()->bgcolor = $color;
//								$table->parent()->height = '20%';
								$bold = isset($_POST['tutorials_font_bold']);
								if($bold){
									$bold = '<strong>';
									$boldEnd = '</strong>';
								}
								$check = array('white', 'yellow', 'black');
								if(isset($_POST['tutorials_font']) && in_array($_POST['tutorials_font'], $check))
									$fontcolor = $_POST['tutorials_font'];
								else
									$fontcolor = 'black';
								$tds = $table->find('td');
								foreach($tds as $td){
									//Remove the Tutorial Group
									if($td->width == '20'){
										$td->style = 'display:none';
									}
									else if($td->width == '40'){
										//Get the place
										$location = $td->plaintext;
										$td->style = 'display:none';
									}
									else{
										$description = $td->plaintext;
										$course = explode(' ', $description);
										$course = trim($course[0] . preg_replace("/[^0-9]/", '', $course[1]));
										if(!isset($courses[$course]))
											$courses[$course] = 1;
										else
											$courses[$course]++;
										//$td->innertext = "<font color='$color' size='2' face='Tahoma'>$bold" . $td->plaintext . ' @ ' . $location. "$boldEnd</font>";
										$generated .= "<td width='15%' id='tutorial_$tutorial_id' bgcolor='$tdcolor'><font size='2' color='$fontcolor'><center>$bold $td->plaintext @ $location $boldEnd</center></font></td>";
										$tutorial_id++;
									}	
								}
								$data[$num][0]=$DAY;
								$data[$num][1]=$slot;
								$data[$num][2]=$description;
								$data[$num][3]=$location;
								$slot+=1;
								$num+=1;
							}
							else if(strpos($table->innertext, '<font face="Arial">Free</font>') != false){
								if(!(isset($_POST['free'])) || strlen(trim($_POST['free'])) == 0)
									$bgcolor = 'green';
								else
									$bgcolor = $_POST['free'];
//								$table->parent()->bgcolor = $color;
//								$table->bgcolor = $color;
								$slot+=1;
								$totalNumSlots++;
								$generated .= "<td width='15%' bgcolor='$bgcolor'><strong><center>FREE</center></strong></td>";
							}	
						}
					}
				}
				$generated .= "</table>";
				$generatedHtml = str_get_html($generated);
				$counter = 0;
				foreach($dayOffs as $dayOff){
					//echo "DAMN!";
					$test = $html->find("tr[id=$dayOff]",0);
					//echo $test->id;
					if(!(isset($_POST['dayoff']) || strlen(trim($_POST['dayoff'])) == 0))
						$color = 'darkgreen';
					else
						$color = $_POST['dayoff'];
					if($test != null){
						$tr = $generatedHtml->find("tr[id=day_$counter]", 0);
						$td = $tr->first_child();
						$td->outertext = $td->outertext . "<td align='center' colspan='5' bgcolor='$color'><strong>DAY OFF</strong></td>";
						$td->parent()->bgcolor = $color;
					}
					$counter++;
					}
				}
			$csvCheck = isset($_POST['csv']);
			$icsCheck = isset($_POST['ics']);
			if($icsCheck==1)
			{
			
			$ourFileName = (999999999999*microtime())."(ics).ics";
			echo 'ICS File: <a href="/'.$ourFileName.'">download?</a> <small>(simply import as a new calendar)</small><br/>';
			$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
			fclose($ourFileHandle);
			$fh = fopen($ourFileName, 'a');
			fwrite($fh, "BEGIN:VCALENDAR\r\n");
			fwrite($fh, "CALSCALE:GREGORIAN\r\n");
			fwrite($fh, "METHOD:PUBLISH\r\n");
			fwrite($fh, "X-WR-CALNAME:GUC schedule\r\n");
			for($j=0;$j<sizeof($data);$j=$j+1)
			{
				$startMonth = 9;
				$startDay = 18;
				$startTime = "T083000Z";
				$endTime = "T100000Z";
				if($data[$j][0]==="Sunday")
					$startDay = 19;
				else if($data[$j][0]==='Monday')
					$startDay = 20;
				else if($data[$j][0]==='Tuesday')
					$startDay = 21;
				else if($data[$j][0]==='Wednesday')
					$startDay = 22;
				else if($data[$j][0]==='Thursday')
					$startDay = 23;
				
				if($data[$j][1]==2)
				{
					$startTime = "T103000Z";
					$endTime = "T120000Z";
				}
				else if($data[$j][1]==3)
				{
					$startTime = "T121500Z";
					$endTime = "T134500Z";
				}
				else if($data[$j][1]==4)
				{
					$startTime = "T140000Z";
					$endTime = "T153000Z";
				}
				else if($data[$j][1]==5)
				{
					$startTime = "T160000Z";
					$endTime = "T173000Z";
				}
				while(true)
				{
					fwrite($fh,"BEGIN:VEVENT\r\n");
					if($startMonth==9)
						$startMonth = '09';
					$dstart = 'DTSTART:2010'.$startMonth.$startDay.$startTime;
					$dend = 'DTEND:2010'.$startMonth.$startDay.$endTime;
					$des ='DESCRIPTION:'.str_ireplace("\r\n",' ',str_ireplace("\t",'',trim($data[$j][2])));
					$sum ='SUMMARY:'.str_ireplace("\r\n",' ',str_ireplace("\t",'',trim($data[$j][2])));
					$location ='LOCATION:'.str_ireplace("\r\n",' ',str_ireplace("\t",'',trim($data[$j][3])));
					fwrite($fh,"$dstart\r\n");
					fwrite($fh,"$dend\r\n");
					fwrite($fh,"$des\r\n");
					fwrite($fh,"$sum\r\n");
					fwrite($fh,"$location\r\n");
					fwrite($fh,"END:VEVENT\r\n");
				if($startMonth!=10)
					{
						if($startDay+7>30)
						{
							if($startMonth!=12)
							{
								$startDay = ($startDay+7)-30;
								$startMonth++;
							}
							else break;
						}
						else
							$startDay +=7;
					}
					else if($startMonth==10)
					{
						if($startDay+7>31)
						{
							if($startMonth!=12)
							{
								$startDay = ($startDay+7)-31;
								$startMonth++;
							}
							else break;
						}
						else
							$startDay +=7;
					}
				}
			}
			fwrite($fh,"END:VCALENDAR");
			fclose($fh);
			}
			if($csvCheck==1)
			{
			$ourFileName = (999999999999*microtime())."(csv).csv";
			echo 'CSV File: <a href="/'.$ourFileName.'">download?</a> <small>(simply import as a new calendar)</small><br/>';
			$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
			fclose($ourFileHandle);
			$fh = fopen($ourFileName, 'a');
			$csv='"Start Date","End Date","Start Time","End Time","All Day Event","Description","Subject","Location"';
			fwrite($fh, "$csv\r\n");
			for($j=0;$j<sizeof($data);$j=$j+1)
			{
				$startMonth = 9;
				$startDay = 18;
				$startTime = "08:30:00 AM";
				$endTime = "10:00:00 AM";
				if($data[$j][0]==="Sunday")
					$startDay = 19;
				else if($data[$j][0]==='Monday')
					$startDay = 20;
				else if($data[$j][0]==='Tuesday')
					$startDay = 21;
				else if($data[$j][0]==='Wednesday')
					$startDay = 22;
				else if($data[$j][0]==='Thursday')
					$startDay = 23;
				
				if($data[$j][1]==2)
				{
					$startTime = "10:30:00 AM";
					$endTime = "12:00:00 PM";
				}
				else if($data[$j][1]==3)
				{
					$startTime = "12:15:00 PM";
					$endTime = "01:45:00 PM";
				}
				else if($data[$j][1]==4)
				{
					$startTime = "02:00:00 PM";
					$endTime = "03:30:00 PM";
				}
				else if($data[$j][1]==5)
				{
					$startTime = "04:00:00 PM";
					$endTime = "05:30:00 PM";
				}
				while(true)
				{
					$csv = '"'.$startDay.'/'.$startMonth.'/2010","'.$startDay.'/'.$startMonth.'/2010","'.$startTime.'","'.$endTime.'","FALSE","'.str_ireplace("\r\n",' ',str_ireplace("\t",'',trim($data[$j][2]))).'","'.str_ireplace("\r\n",' ',str_ireplace("\t",'',trim($data[$j][2]))).'","'.str_ireplace("\r\n",' ',str_ireplace("\t",'',trim($data[$j][3]))).'"'; 
					fwrite($fh, "$csv\r\n");
				if($startMonth!=10)
					{
						if($startDay+7>30)
						{
							if($startMonth!=12)
							{
								$startDay = ($startDay+7)-30;
								$startMonth++;
							}
							else break;
						}
						else
							$startDay +=7;
					}
					else if($startMonth==10)
					{
						if($startDay+7>31)
						{
							if($startMonth!=12)
							{
								$startDay = ($startDay+7)-31;
								$startMonth++;
							}
							else break;
						}
						else
							$startDay +=7;
					}
				}
			}
			fclose($fh);	
			}	
				//The main table
				$main = $html->find('table[id=scdTbl]');
				$main = $main[0];
				$main->style = "border-width: 1px; border-spacing: 2px; border-style: outset; border-color: black; border-collapse: collapse; background-color: white;";
				//Name and Id
				$student = $html->find('span[id=scdTpLbl]');
				$student = $student[0]->plaintext;
				$studentDetails = explode('-', $student);
				$studentName = $studentDetails[2];
				$studentID = $studentDetails[0] . ' - ' . trim($studentDetails[1]);
				//clean the html
				$html = $html->find('table[id=scdTbl]');
				$html = $html[0]->outertext;
//				require_once(WP_PLUGIN_DIR.'/gucTables/dompdf_config.inc.php');
//				$pdf = new DOMPDF();
//				$pdf->load_html(str_ireplace('Period', 'Slot', $html));
//				$pdf->set_paper('legal', 'landscape');
//				$pdf->render();
//				$pdf->stream("$file_name.pdf");
				echo "<center><h3><font face='Tahoma'>[$studentID] $studentName Schedule</font></h3></center>";
				echo $generatedHtml;
				echo '<center>';
				echo "<ul>";
				echo "<h3>A total of " . count($courses) . " course(s) as:</h3>";
				$free = $totalNumSlots;
				$totalNumSlots = 0;
				foreach($courses as $course=>$numSlots){
					echo "<li>$course has $numSlots slot(s)</li>";
					$totalNumSlots += $numSlots;
				}
				echo "</ul>";
				echo "<h4>a total of $totalNumSlots busy slots and $free free slots.</h4>";
				echo '<small>Powered by Schedule Beautifier</small>';
				echo '</center>';
				die();
			}//end of our if condition
}

function unlock_transcript(){
	if(isset($_POST['unlock_transcript_galal']) && isset($_POST['username']) && isset($_POST['password']) || isset($_POST['stdYrLst'])){
			$ch = curl_init();
			$fields_string = '';
			if((isset($_POST['stdYrLst']))){
				//$fields_string = '';
				//foreach($_POST as $key=>$value) { if(!($key == 'username' || $key == 'password'))$fields_string .= $key.'='.$value.'&'; }
				//trim($fields_string,'&');
				//echo $fields_string;
				foreach($_POST as $key=>$value) { if(!($key == 'username' || $key == 'password'))$fields_string .= urlencode($key).'='.$value.'&'; }
				echo $fields_string;
			}
			$username = $_POST['username'];
			$pass = $_POST['password']; 
			curl_setopt($ch, CURLOPT_URL, 'http://student.guc.edu.eg/external/student/grade/Transcript.aspx'); 
			/* make cURL wait for the proper command */
			curl_setopt ( $ch, CURLOPT_HTTPHEADER, array ( 'Expect:100-continue' ) );
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM); 
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
			curl_setopt($ch, CURLOPT_REFERER, 'http://student.guc.edu.eg/external/student/grade/Transcript.aspx');
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8) Gecko/20051111 Firefox/1.5 BAVM/1.0.0');
			curl_setopt($ch, CURLOPT_USERPWD, "$username:$pass");
			//curl_setopt($ch,CURLOPT_FAILONERROR,true);
			$contents = curl_exec($ch);
			//echo curl_error($ch);
			curl_close($ch);
			if(!(isset($_POST['stdYrLst']))){
				if(!(strpos($contents, 'stdYrLst'))){
					wp_die('There was an error unlocking the transcript. The admin system might be down or your username/password is/are incorrect');
				}
				require_once(WP_PLUGIN_DIR.'/gucTables/simple_html_dom.php');
				$html = str_get_html($contents);
				$form = $html->find('form[id=Form1]', 0);
				$form->action = $_SERVER['REQUEST_URI'];
				$form->innertext = $form->innertext .= "<input type='hidden' value='$username' name='username'><input type='hidden' value='$pass' name='password'>";
				$drpdown = $html->find('select[id=stdYrLst]', 0);
				$drpdown->disabled = null;
				echo $html;
				die();
			}
			echo $contents;
			die();
	}
	//die();
}



function guc_options(){
	?>
	<div class="wrap g-project-style">
		<h2>Schedule Beautifier</h2>
		<form method="post" action="options.php">
		   <?php  settings_fields( 'guc-schedule' ); ?>
		    <h3>Deactivate</h3>
		       <label>Deactivate the beautifier? - 1 yes, 0 no.</label>
		       <input type="text" name="enable" value="<?php echo get_option('enable'); ?>" />
	      	<h3>PDF</h3>
	       		<label>Schedules as PDFs? - 1 yes, 0 no.</label>
	       		<input type="text" name="pdf" value="<?php echo get_option('pdf'); ?>" />
	       		<br><br>
	       		<input type="submit" class="button-primary" value="Save Options" />
		</form>
	</div>
	<?php
}

function guc_menu(){
	add_menu_page( "GUC Schedules", "GUC Schedule", "manage_options", "guc_beautifier", "guc_options");
	add_submenu_page( 'guc_beautifier', 'Schedule Options', 'Options', 'manage_options', 'guc_beautifier', 'guc_options');
}
?>
