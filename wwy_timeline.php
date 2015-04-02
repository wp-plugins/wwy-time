<div class="wwy_timeline">
<!-- INCLUDE TIMELINER PLUGIN: -->		

<script>
			$(document).ready(function(){

				// timeliner set up
				$('#example3').timeliner({
					containerwidth: 600,
					containerheight: 300,
					timelinewidth: 550,
					timelineheight: 3,
					timelineverticalmargin: 0,
					autoplay: false,
					showtooltiptime: false,
					repeat: false,
					showtotaltime:true,
					timedisplayposition: 'below',
					transition: 'fade'
				});
			$(function() {
			 $( "#datepicker" ).datepicker();
			});
			$(function() {
			 $( "#datepicker2" ).datepicker();
			});
			$('#addslides')[0].reset();
			
		// stop space key from playing the timeline
			$("textarea, input").keydown(function (e) {
				 if (e.keyCode == 32) { 
				   $(this).val($(this).val() + " "); // append '-' to input
				   return false; // return false to prevent space from being added
				 }
			});	
        // hide the play button
		$('#isPlaySelected').click(function () {
			$(".next").toggle(this.checked);
			$(".play").toggle(this.checked);
			$(".pause").toggle(this.checked);
			$(".previous").toggle(this.checked);
			$(".textura").text(function(i, text){
          return text === "Hide Play Button on map" ? "Show Play Button on map" : "Hide Play Button on map";
        })
		});		
		$('#side_play_pause').click(function () {			
			$(".texturka").text(function(i, text){
          return text === "Play" ? "Pause" : "Play";
        })
		});		
		     
		
		$(function(){
				$('#attachmarkers, #addslides, #wwy_publish').validetta({
					customReg : {
						regname : {
							method : /^[\+][0-9]+?$|^[0-9]+?$/,
							errorMessage : 'Custom Reg Error Message !'
						},
						// you can add more
						example : { 
							method : /^[\+][0-9]+?$|^[0-9]+?$/,
							errorMessage : 'Lan mal !'
						}
					},
					realTime : true
				});
		});
		}); 
</script>




<div class="buttonky">
<span class="controlky">External Controlers</span>				
				<div class="wwy_playbut"><button id="side_play_pause" class="side_play_pause" onclick="$('#element3').timeliner.pauseplay()"><span class="texturka">Play</span></button></div>	
				<!--<button class="side_pause" onclick="$('#element3').timeliner.pause()"></button>
				<button class="side_play" onclick="$('#element3').timeliner.play()"></button>-->				
				<button class="side_previous" onclick="$('#element3').timeliner.prev()"></button>
				<button class="side_next" onclick="$('#element3').timeliner.next()"></button>
				<button id="isPlaySelected" class="side_play_pause" /><span class="textura">Hide Play Button on map</button></span>	

			
</div>


<!-- Maps generated -->


			<ul id="example3" class="timeliner">
			
				<li title="Coordinates Gen" lang="2">					  
                <div id="map1" style="width: 600px; height: 400px"></div>
				</li>
				<?php 
				global $wpdb;
				global $current_user;
				get_currentuserinfo();
				$user_id = $current_user->ID;
				$table_add_one = $wpdb->prefix . "wwy_data_one";				
				$row = $wpdb->get_results( "SELECT * FROM wp_wwy_data_one WHERE user_id=$user_id ORDER BY slide_number");
				foreach ( $row as $row ) 
                {					
				
				echo '<li title="' .$row->date . ' ' . $row->caption. '" lang="' .$row->length . '"><div id="' .$row->slide_number . '" style="width: 600px; height: 400px"></div><input type="hidden" id="map_area' . $row->slide_number . '" value="' .$row->map_type. '"></li>';				
				 } ?>
				 <li title="Credits" lang="2">					  
                <div id="map_last" style="width: 600px; height: 400px"></div>
				</li>
			</ul>

<?php 
global $wpdb;
global $current_user;
get_currentuserinfo();
$user_id = $current_user->ID;
$table_add_one = $wpdb->prefix . "wwy_data_one";
$table_add_two = $wpdb->prefix . "wwy_data_two";
    
$row = $wpdb->get_results( "SELECT * FROM wp_wwy_data_one WHERE user_id=$user_id" );		

foreach ( $row as $row ) 
   {		   
echo "<script>
		 $(function(){
		  $('#".$row->slide_number."').vectorMap({    
			markerStyle: {
			  initial: {
				fill: '#f01616',
				stroke: '#fbc533'
			  }
			},    
			markers: [";
			$slide_number_option = $row->slide_number;			
$row = $wpdb->get_results( "SELECT * FROM wp_wwy_data_two WHERE user_id=$user_id AND slide_number_option=$slide_number_option" );		

foreach ( $row as $row) 
                {				
			echo "{latLng: [" . $row->coordx . "," . $row->coordy . "], name: '" . $row->marker_name . "', style: {fill: '#" . $row->color . "'}}," ;      
				}
echo "]
		  });
		});	
     		
	</script>";
   }

?>	

	
<!-- END timeline -->
<div class="right_side">

<?php 
global $wpdb;
global $current_user;
get_currentuserinfo();
$user_id = $current_user->ID;
    
$row = $wpdb->get_results( "SELECT * FROM wp_wwy_data_three WHERE user_id=$user_id" );		

if(isset($_POST['wwy_unpublish'])) {
     $table_add_three = $wpdb->prefix . "wwy_data_three";	
     $user_id = $current_user->ID; 
	 $rows_affected_three = $wpdb->delete( $table_add_three, array( 'user_id' => $user_id ));	 
	}	

foreach ( $row as $row ) 
     { 
	 if ($row->published == 1){
	 ?>
<form action="" method="post">
<?php echo "<input type='hidden' value='1' name='published'>"; ?>
<div><input class="wwy_publish" type="submit" value="Un-Publish" name="wwy_unpublish" /></div>
</form>
<p>Your published project is <?php echo '<a class="wwy-edit-profile" href="'.home_url().'/wwy-published/?pub_id='.$current_user->ID.'">here!</a></p><textarea rows="3" cols="30"><iframe width="100%" height="100%" border="0" style="border:none" src="'.home_url().'/wwy-published/?pub_id='.$current_user->ID.'"></iframe></textarea> Your Project API: ' . $current_user->ID . ''; 
?>

<?php } } 
if (!$row) {
?>

<form action="" method="post" id="wwy_publish">
<input type='hidden' value='1' name='published'>
<input type="text" value="" data-validetta="required,minLength[5]" placeholder="Name of your Project" name="pub_name">
<input class="wwy_publish" type="submit" value="Publish" name="wwy_publish" />
</form>

<?php } ?>

</div>


<div class="slide_fields">
<?php
require('functions.php');
// delete slide's row
wwy_deleteslide();	
	
$row = $wpdb->get_results( "SELECT * FROM wp_wwy_data_one WHERE user_id=$user_id  ORDER BY slide_number");	

echo '<form action="" method="post" enctype="multipart/form-data">';
    echo "<table class='display'>";	
	echo "<th>Slide#</th>";
	//echo "<th>User ID</th>";
	echo "<th>Map</th>";
	echo "<th>Date</th>";
	echo "<th>Length</th>";
	echo "<th>Caption</th>";
	echo "<th>Action</th>";
	echo "<th>Markers</th>";
	if (!$row){ echo "<tr><td colspan='7'>No Slides created!</td></tr>";}  
	
	
    foreach ( $row as $row ) 
    { 
	// delete row
	$warn_del = '"Are you sure to delete this slide?"';    			 
	 echo "<tr>";	  
	 echo "<td>" . $row->slide_number . "</td>";
	// echo "<td>" . $row->user_id . "</td>";
	 echo "<td>" . $row->map_type . "</td>";
	 echo "<td>" . $row->date . "</td>";
	 echo "<td>" . $row->length . "</td>";
	 echo "<td>" . $row->caption . "</td>";	 
	 echo "<td><button type='submit' name='field_id' value='" . $row->field_id . "' class='delete'  onclick='return confirm($warn_del);' placeholder='Delete'><img src='" . plugins_url( 'images/deletebutton.png' , __FILE__ ) . "' /></button></td>";	 	 
	echo '<td><label><input type="radio" name="geo" rel="mapping'.$row->slide_number.'" />Show</label></td>';	
	echo "</tr>";
	} 
	echo "</table>";
	echo "</form>";
	 
// show markers
 
// delete marker's row
wwy_deletemarker();	

$row = $wpdb->get_results( "SELECT * FROM wp_wwy_data_two WHERE user_id=$user_id ORDER BY slide_number_option");	
	
    echo "<table class='display2' >";	
	
	echo "<th>Slide#</th>";
	echo "<th>Coord-X</th>";
	echo "<th>Coord-Y</th>";
	echo "<th>Marker's name</th>";	
	echo "<th>Color</th>";
	echo "<th>Action</th>";	  	
	echo "<th>Dupe-for</th>";	  	
	echo "<tbody class='result'>";
	if (!$row){ echo "<tr><td colspan='7'>No Markers created!</td></tr>";}

    foreach ( $row as $row ) 
    { 
	// delete row
	$warn_del = '"Are you sure to delete this markers?"';   
			
	 echo '<tr class="mapping' . $row->slide_number_option . '">';	
     echo '<form action="" method="post" enctype="multipart/form-data">';	 
	 echo "<td>" . $row->slide_number_option . "</td>";	
	 echo "<td>" . $row->coordx . "</td>";
	 echo "<td>" . $row->coordy . "</td>";
	 echo "<td>" . $row->marker_name . "</td>";	 	 
	 echo "<td>#" . $row->color . "</td>";	 
	 echo "<td><button onclick='return confirm($warn_del);' type='image' value='$row->marker_field_id' class='delete' name='marker_field_id' placeholder='Delete'><img src='" . plugins_url( 'images/deletebutton.png' , __FILE__ ) . "'/></button></td>";	 
	echo "</form>";
	echo '<form action="" method="post" enctype="multipart/form-data">';	 
	echo '<td><input name="coordx" type="hidden" value="' . $row->coordx . '">
			  <input name="coordy" type="hidden" value="' . $row->coordy . '">
			  <input name="marker_name" type="hidden" value="' . $row->marker_name . '">
			  <input name="color" type="hidden" value="' . $row->color . '">
	
	<select data-validetta="required" class="fieldtype" name="slide_number_option2" onchange="this.form.submit()"><option value="">Slide#</option>';
	$row = $wpdb->get_results( "SELECT * FROM wp_wwy_data_one WHERE user_id=$user_id  ORDER BY slide_number"); 
	foreach ( $row as $row ){ echo '<option value="' . $row->slide_number . '">' . $row->slide_number . '</option>'; } 
	
	echo '</option></select>
	</td>';
	echo "</form>";
	echo "</tr>";	
	} 
	echo "</tbody>";
	echo "</table>";
	
	 
?>
<script>
// sort markers
$(document).ready(function(){
$('table.display').delegate('input[type=radio]', 'change', update);
update();

function update() {
    var $lis = $('.result tr'),
        $checked = $('input:checked');
    if ($checked.length) {
        var selector = $checked.map(function () {
            return '.' + $(this).attr('rel');
        }).get().join('');

        $lis.hide().filter(selector).show();

    } else {
        $lis.show();
    }
}
});

</script>

</div>




<?php
require_once('functions.php');

// limit the slides based on the payment selection
require_once('wp-blog-header.php');
	$user_id = $current_user->ID;
	$query = mysql_query( "SELECT * FROM wp_wwy_data_one WHERE user_id=$user_id ");
	$myvarResults = mysql_num_rows($query);
    $wwy_pay = (int)get_option('wwy_pay');	
    $wwy_num = (int)get_option('wwy_num');	
    $wwy_charge = (int)get_option('wwy_charge');  	
	
	$wwy_paypal_btn = wwy_paypal_btn();
	
	$row = $wpdb->get_results( "SELECT * FROM wp_wwy_data_four WHERE user_id=$user_id");
	foreach ( $row as $row ) 
	{
	$payment_status = $row->payment_status; 
    if ($payment_status == 1 and $wwy_pay == 1){	
	wwy_insert_intotb1();
	}
	}	
    if($myvarResults >= $wwy_num and $wwy_pay == 1)
	{
	if($payment_status == 0){
	echo "<div class='wwy_more'>To add more slides, please subscribe for $" . $wwy_charge . ". " . $wwy_paypal_btn . "</div>";
	}
    elseif(!$row )	{
	echo "<div class='wwy_more'>To add more slides, please subscribe for $" . $wwy_charge . ". " . $wwy_paypal_btn . "</div>";
	}
	}  
   if($myvarResults < $wwy_num and !$row or $wwy_pay == "")
   {
   wwy_insert_intotb1();
   }   

   

   wwy_insert_intotb2();
 
   wwy_insert_dubplicatetb2();
   
   wwy_publishing();
   
   // redirect after submit
	  if ('POST' == $_SERVER['REQUEST_METHOD']) {
		  //do processing
		  //303 forces a GET request
		  header("Location: wwy-your-creation", true, 303);
		  exit;
	   }
	   else {
		  //handle bad page visit.
	   }
?>
<div id="field">
<p>Create a Slide</p>
<form action="" method="post" id="addslides">
<span id="submits">
<div><input type="text" placeholder="Slide's #" id="slide_number" name="slide_number" data-validetta="required,number"></div>
<div>
<select name="map_type" class="map_type" data-validetta="required">
    <option value="">Map - Countries</option>
	<option value="world">Whole World</option>
	<option value="usa">USA</option>
    <option value="europe">Europe</option>
    <option value="australia">Australia</option>       
    <option value="south_america">South America</option>     
    <option value="north_america">North America</option>     
    <option value="asia">Asia</option>   
    <option value="africa">Africa</option>   
</select>
</div>
<div><input type="text" id="datepicker" placeholder="Date" name="date" type="date" data-validetta="required"></div>
<div><input type="text" placeholder="Lenght in seconds" id="length" name="length" value="2" data-validetta="number"></div>
<textarea rows="2" cols="10" name="caption" placeholder="Caption"></textarea>
</span>
<div><input type="submit" value="Submit Slide" id="submit" class="button" name="submit"></div>
</form>


<form action="" method="post" id="attachmarkers" >
<span class="append">
<fieldset id="buildyourform">
<legend>Add markers to the Slide!</legend>
<span class="locale" id="submits">
<div><select data-validetta="required" class="fieldtype" name="slide_number_option"><option value="">Slide#</option><?php $row = $wpdb->get_results( "SELECT * FROM wp_wwy_data_one WHERE user_id=$user_id  ORDER BY slide_number"); foreach ( $row as $row ){ echo '<option value="' . $row->slide_number . '">' . $row->slide_number . '</option>'; } ?></select></div>
<div><input type="text" data-validetta="required,number" id="location1" placeholder="Coordinates X" name="coordx"></div>
<div><input type="text" data-validetta="required,number" id="location2" placeholder="Coordinates Y" name="coordy"></div>
<div><input type="text" id="colorpickerField3" value="d40404" placeholder="Color" name="color" maxlength="6" size="6"></div>
<div><input type="text" placeholder="Name" class="fieldname" name="marker_name" data-validetta="maxLength[13]"></div>

<input type='submit' name='attach' id='attach' value='Attach!' ></span>
</span>
</fieldset>

</form>

  <script>  
// onclick markers location generator 
 $(function(){
      var map,
          markerIndex = 0,
          markersCoords = {};

      map = new jvm.WorldMap({         
          markerStyle: {
            initial: {
              fill: 'red'
            }
          },
          container: $('#map1'),
          onMarkerLabelShow: function(e, label, code){
            map.label.text(markersCoords[code].lat.toFixed(2)+' '+markersCoords[code].lng.toFixed(2));
          },
          onMarkerClick: function(e, code){
            map.removeMarkers([code]);
            map.label.hide();
          }
      });
      
      $('#map1').click(function(e){
          var latLng = map.pointToLatLng(e.offsetX, e.offsetY),
              targetCls = $(e.target).attr('class');

          if (latLng && (!targetCls || (targetCls && $(e.target).attr('class').indexOf('jvectormap-marker') === -1))) {
            markersCoords[markerIndex] = latLng;
            map.addMarker(markerIndex, {latLng: [latLng.lat, latLng.lng]});
            markerIndex += 1;
			var x = latLng.lat;
			var y = latLng.lng;
			
			$('#location1').val(x.toFixed(2));
			$('#location2').val(y.toFixed(2));
          }
      });
      $('#map1').bind('');
    });
	// Last slide Credits----------------
	$(function(){$('#map_last').vectorMap({});});
	
	$('#map_last').append( "<div class='credit'>Powered by <?php echo $wwy_credit = get_option('wwy_credit'); ?><br /> <a href='<?php echo home_url(); ?>'>Create your map for free!</a></div>" );
	// end Last slide ----------------
	// caption
	
	
	// end caption
	
	
	
    jQuery(function(){
      var $ = jQuery; 
	    
	<?php 
global $wpdb;
global $current_user;
get_currentuserinfo();
$user_id = $current_user->ID;
$table_add_one = $wpdb->prefix . "wwy_data_one";
$table_add_two = $wpdb->prefix . "wwy_data_two";
    
$row = $wpdb->get_results( "SELECT * FROM wp_wwy_data_one WHERE user_id=$user_id" );		

foreach ( $row as $row ) 
   {  
echo"	  
	    if($('#map_area" . $row->slide_number . "').val() == 'world'){		
		   $('#" . $row->slide_number . "').vectorMap('set', 'focus', 1, 0, 0);		   
		}
		if($('#map_area" . $row->slide_number . "').val() == 'usa') {
		  $('#" . $row->slide_number . "').vectorMap('set', 'focus', 5.8, 0.2, 0.4);
		}
		if($('#map_area" . $row->slide_number . "').val() == 'europe'){		
		   $('#" . $row->slide_number . "').vectorMap('set', 'focus', 4.3, 0.5, 0.3);		   
		}	
        if($('#map_area" . $row->slide_number . "').val() == 'australia') {
		  $('#" . $row->slide_number . "').vectorMap('set', 'focus', ['AU']);
		}
		if($('#map_area" . $row->slide_number . "').val() == 'south_america') {
		  $('#" . $row->slide_number . "').vectorMap('set', 'focus', ['CO','CL']);
		}
		if($('#map_area" . $row->slide_number . "').val() == 'north_america') {
		  $('#" . $row->slide_number . "').vectorMap('set', 'focus', ['MX', 'CA']);
		}
		if($('#map_area" . $row->slide_number . "').val() == 'asia') {
		  $('#" . $row->slide_number . "').vectorMap('set', 'focus', ['RU', 'ID']);
		}
		if($('#map_area" . $row->slide_number . "').val() == 'africa') {
		  $('#" . $row->slide_number . "').vectorMap('set', 'focus', 2.8, 0.5, 0.65);
		}		
		";			
	}
?>	
	
	
		$(".map_type").change(function(){
		if($(this).val() == "world") {
		   $('#map1').vectorMap('set', 'focus', 1, 0, 0);
		}
		if($(this).val() == "usa") {
		  $('#map1').vectorMap('set', 'focus',  5.8, 0.2, 0.4);
		}
		if($(this).val() == "europe") {
		  $('#map1').vectorMap('set', 'focus', 4.3, 0.5, 0.3);
		}
		if($(this).val() == "australia") {
		  $('#map1').vectorMap('set', 'focus', ['AU']);
		}
		if($(this).val() == "south_america") {
		  $('#map1').vectorMap('set', 'focus', ['CO','CL']);
		}
		if($(this).val() == "north_america") {
		  $('#map1').vectorMap('set', 'focus', ['MX', 'CA']);
		}
		if($(this).val() == "asia") {
		  $('#map1').vectorMap('set', 'focus', ['RU', 'ID']);
		}
		if($(this).val() == "africa") {
		  $('#map1').vectorMap('set', 'focus', 2.8, 0.5, 0.65);
		}
	});	
      
      $('#map1').vectorMap({
        map: 'world_mill_en',
        focusOn: {
          x: 0.5,
          y: 0.5,
          scale: 2
        },
        series: {
          regions: [{
            scale: ['#C8EEFF', '#0071A4'],
            normalizeFunction: 'polynomial',
            values: {
              "AF": 16.63,
              "AL": 11.58,
              "DZ": 158.97,
              "AO": 85.81,
              "AG": 1.1,
              "AR": 351.02,
              "AM": 8.83,
              "AU": 1219.72,
              "AT": 366.26,
              "AZ": 52.17,
              "BS": 7.54,
              "BH": 21.73,
              "BD": 105.4,
              "BB": 3.96,
              "BY": 52.89,
              "BE": 461.33,
              "BZ": 1.43,
              "BJ": 6.49,
              "BT": 1.4,
              "BO": 19.18,
              "BA": 16.2,
              "BW": 12.5,
              "BR": 2023.53,
              "BN": 11.96,
              "BG": 44.84,
              "BF": 8.67,
              "BI": 1.47,
              "KH": 11.36,
              "CM": 21.88,
              "CA": 1563.66,
              "CV": 1.57,
              "CF": 2.11,
              "TD": 7.59,
              "CL": 199.18,
              "CN": 5745.13,
              "CO": 283.11,
              "KM": 0.56,
              "CD": 12.6,
              "CG": 11.88,
              "CR": 35.02,
              "CI": 22.38,
              "HR": 59.92,
              "CY": 22.75,
              "CZ": 195.23,
              "DK": 304.56,
              "DJ": 1.14,
              "DM": 0.38,
              "DO": 50.87,
              "EC": 61.49,
              "EG": 216.83,
              "SV": 21.8,
              "GQ": 14.55,
              "ER": 2.25,
              "EE": 19.22,
              "ET": 30.94,
              "FJ": 3.15,
              "FI": 231.98,
              "FR": 2555.44,
              "GA": 12.56,
              "GM": 1.04,
              "GE": 11.23,
              "DE": 3305.9,
              "GH": 18.06,
              "GR": 305.01,
              "GD": 0.65,
              "GT": 40.77,
              "GN": 4.34,
              "GW": 0.83,
              "GY": 2.2,
              "HT": 6.5,
              "HN": 15.34,
              "HK": 226.49,
              "HU": 132.28,
              "IS": 12.77,
              "IN": 1430.02,
              "ID": 695.06,
              "IR": 337.9,
              "IQ": 84.14,
              "IE": 204.14,
              "IL": 201.25,
              "IT": 2036.69,
              "JM": 13.74,
              "JP": 5390.9,
              "JO": 27.13,
              "KZ": 129.76,
              "KE": 32.42,
              "KI": 0.15,
              "KR": 986.26,
              "KW": 117.32,
              "KG": 4.44,
              "LA": 6.34,
              "LV": 23.39,
              "LB": 39.15,
              "LS": 1.8,
              "LR": 0.98,
              "LY": 77.91,
              "LT": 35.73,
              "LU": 52.43,
              "MK": 9.58,
              "MG": 8.33,
              "MW": 5.04,
              "MY": 218.95,
              "MV": 1.43,
              "ML": 9.08,
              "MT": 7.8,
              "MR": 3.49,
              "MU": 9.43,
              "MX": 1004.04,
              "MD": 5.36,
              "MN": 5.81,
              "ME": 3.88,
              "MA": 91.7,
              "MZ": 10.21,
              "MM": 35.65,
              "NA": 11.45,
              "NP": 15.11,
              "NL": 770.31,
              "NZ": 138,
              "NI": 6.38,
              "NE": 5.6,
              "NG": 206.66,
              "NO": 413.51,
              "OM": 53.78,
              "PK": 174.79,
              "PA": 27.2,
              "PG": 8.81,
              "PY": 17.17,
              "PE": 153.55,
              "PH": 189.06,
              "PL": 438.88,
              "PT": 223.7,
              "QA": 126.52,
              "RO": 158.39,
              "RU": 1476.91,
              "RW": 5.69,
              "WS": 0.55,
              "ST": 0.19,
              "SA": 434.44,
              "SN": 12.66,
              "RS": 38.92,
              "SC": 0.92,
              "SL": 1.9,
              "SG": 217.38,
              "SK": 86.26,
              "SI": 46.44,
              "SB": 0.67,
              "ZA": 354.41,
              "ES": 1374.78,
              "LK": 48.24,
              "KN": 0.56,
              "LC": 1,
              "VC": 0.58,
              "SD": 65.93,
              "SR": 3.3,
              "SZ": 3.17,
              "SE": 444.59,
              "CH": 522.44,
              "SY": 59.63,
              "TW": 426.98,
              "TJ": 5.58,
              "TZ": 22.43,
              "TH": 312.61,
              "TL": 0.62,
              "TG": 3.07,
              "TO": 0.3,
              "TT": 21.2,
              "TN": 43.86,
              "TR": 729.05,
              "TM": 0,
              "UG": 17.12,
              "UA": 136.56,
              "AE": 239.65,
              "GB": 2258.57,
              "US": 14624.18,
              "UY": 40.71,
              "UZ": 37.72,
              "VU": 0.72,
              "VE": 285.21,
              "VN": 101.99,
              "YE": 30.02,
              "ZM": 15.69,
              "ZW": 5.57
            }
          }]
        }
      });
    })
				// clear input
			$(document).ready(function(){
				
	        jQuery(function($) {

				  // Save the initial values of the inputs as placeholder text
				  $('.fieldwrapper input, #submits input').attr("data-placeholdertext", function() {
					return this.value;
				  });

				  // Hook up a handler to delete the placeholder text on focus,
				  // and put it back on blur
				  $('.fieldwrapper input, #submits input')
					.delegate('input', 'focus', function() {
					  if (this.value === $(this).attr("data-placeholdertext")) {
						this.value = '';
					  }
					})
					.delegate('input', 'blur', function() {
					  if (this.value.length == 0) {
						this.value = $(this).attr("data-placeholdertext");
					  }
					});

				});

			jQuery('#length').keyup(function () { 
				this.value = this.value.replace(/[^0-9\.]/g,'');
			});
			
						
			
})
  </script>
  <script>
$(document).ready ( 
 function() {
  if ( $.browser.mozilla == true ) {
   alert('For adding markers please use any browser except Firefox');
  }
 }
);
</script>
 </div>
 