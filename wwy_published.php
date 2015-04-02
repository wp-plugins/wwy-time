<div class="wwy_timeline wwy_published">
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
		});   
		
</script>

<?php
global $wpdb;
$user_id = wp_specialchars($_GET['pub_id']);
$row = $wpdb->get_results( "SELECT * FROM wp_wwy_data_three WHERE user_id=$user_id");
if (!$row) {
echo "<div class='nopub'>The project doesn't exist or isn't published!</div>";
}else{
 foreach($row as $row){	
	echo "<div class='pub'>" . $row->pub_name . "</div>";	
}
}
?>


<div id="wwy-share">
<a target="_blank" href="https://plus.google.com/share?url=<?php echo home_url(). "/wwy-published/?pub_id=" . $user_id; ?>" id="wwy_share_google_share">
<img src="<?php echo plugins_url( 'images/google.png' , __FILE__ ); ?>" alt="Google+" class="wwy_share" title="Google+"></a>
<a target="_blank" href="http://twitter.com/share?url=<?php echo home_url(). "/wwy-published/?pub_id=" . $user_id; ?>" id="wwy_share_twitter_share"><img src="<?php echo plugins_url( 'images/twitter.png' , __FILE__ ); ?>" alt="Twitter" class="wwy_share" title="Twitter"></a>
<a target="_blank" href="http://www.facebook.com/sharer.php?u=<?php echo home_url(). "/wwy-published/?pub_id=" . $user_id; ?>" id="wwy_share_facebook_share"><img src="<?php echo plugins_url( 'images/facebook.png' , __FILE__ ); ?>" alt="Facebook" class="wwy_share" title="Facebook"></a>
<a href="mailto:?Subject=<?php echo $row->pub_name ;?>&amp;body=<?php echo home_url(). "/wwy-published/?pub_id=" . $user_id; ?>" id="wwy_share_email_share"><img src="<?php echo plugins_url( 'images/email.png' , __FILE__ ); ?>" alt="Email" class="wwy_share" title="Email"></a>
</div>
<div class="buttonky-pub">
<span class="controlky">External Controlers</span>
				
				<div class="wwy_playbut"><button id="side_play_pause" class="side_play_pause" onclick="$('#element3').timeliner.pauseplay()"><span class="texturka">Play</span></button></div>	
				<!--<button class="side_pause" onclick="$('#element3').timeliner.pause()"></button>
				<button class="side_play" onclick="$('#element3').timeliner.play()"></button>-->				
				<button class="side_previous" onclick="$('#element3').timeliner.prev()"></button>
				<button class="side_next" onclick="$('#element3').timeliner.next()"></button>
				<button id="isPlaySelected" class="side_play_pause" /><span class="textura">Hide Play Button on map</button></span>			
</div>

<!-- Maps generated -->
 

	<div id="themap">	
		<div id="specialstuff">			
				
				
			<ul id="example3" class="timeliner">	
				
				<?php 
				global $wpdb;
				global $current_user;
				get_currentuserinfo();
				$user_id = wp_specialchars($_GET['pub_id']);								
				
                $row = $wpdb->get_results( "SELECT * FROM wp_wwy_data_three WHERE user_id=$user_id");
				foreach ( $row as $row ) 
                {					
				$published = $row->published;								
				if ($published == 1){
				
				
				$row = $wpdb->get_results( "SELECT * FROM wp_wwy_data_one WHERE user_id=$user_id ORDER BY slide_number");
				foreach ( $row as $row ) 
                {					
				
				echo '<li title="' .$row->date . ' ' . $row->caption. '" lang="' .$row->length . '"><div id="' .$row->slide_number . '" style="width: 600px; height: 400px"></div><input type="hidden" id="map_area' . $row->slide_number . '" value="' .$row->map_type. '"></li>';				
				 } 
				 }
				 }			 
				 
				 ?>
				 <li title="Credits" lang="2">					  
                <div id="map_last" style="width: 600px; height: 400px"></div>
				</li>
			</ul>
</div>
</div>
<?php 
global $wpdb;
global $current_user;
get_currentuserinfo();
$user_id = wp_specialchars($_GET['pub_id']);
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
<div class="textik">

<span>Please use CTRL + and CTRL - to adjust the size of the map player. Reset CTRL 0<span>
<p><span id="fsstatus"></span></p>
</div>


		<input type="button" value="Go Fullscreen" id="fsbutton" />
		

  <script>  

 
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
			
  </script>
 </div>
 



		



<script>

/* 
Native FullScreen JavaScript API
-------------
Assumes Mozilla naming conventions instead of W3C for now
*/

(function() {
	var 
		fullScreenApi = { 
			supportsFullScreen: false,
			isFullScreen: function() { return false; }, 
			requestFullScreen: function() {}, 
			cancelFullScreen: function() {},
			fullScreenEventName: '',
			prefix: ''
		},
		browserPrefixes = 'webkit moz o ms khtml'.split(' ');
	
	// check for native support
	if (typeof document.cancelFullScreen != 'undefined') {
		fullScreenApi.supportsFullScreen = true;
	} else {	 
		// check for fullscreen support by vendor prefix
		for (var i = 0, il = browserPrefixes.length; i < il; i++ ) {
			fullScreenApi.prefix = browserPrefixes[i];
			
			if (typeof document[fullScreenApi.prefix + 'CancelFullScreen' ] != 'undefined' ) {
				fullScreenApi.supportsFullScreen = true;
				
				break;
			}
		}
	}
	
	// update methods to do something useful
	if (fullScreenApi.supportsFullScreen) {
		fullScreenApi.fullScreenEventName = fullScreenApi.prefix + 'fullscreenchange';
		
		fullScreenApi.isFullScreen = function() {
			switch (this.prefix) {	
				case '':
					return document.fullScreen;
				case 'webkit':
					return document.webkitIsFullScreen;
				default:
					return document[this.prefix + 'FullScreen'];
			}
		}
		fullScreenApi.requestFullScreen = function(el) {
			return (this.prefix === '') ? el.requestFullScreen() : el[this.prefix + 'RequestFullScreen']();
		}
		fullScreenApi.cancelFullScreen = function(el) {
			return (this.prefix === '') ? document.cancelFullScreen() : document[this.prefix + 'CancelFullScreen']();
		}		
	}

	// jQuery plugin
	if (typeof jQuery != 'undefined') {
		jQuery.fn.requestFullScreen = function() {
	
			return this.each(function() {
				var el = jQuery(this);
				if (fullScreenApi.supportsFullScreen) {
					fullScreenApi.requestFullScreen(el);
				}
			});
		};
	}

	// export api
	window.fullScreenApi = fullScreenApi;	
})();

</script>

<script>

// do something interesting with fullscreen support
var fsButton = document.getElementById('fsbutton'),
	fsElement = document.getElementById('specialstuff'),
	fsStatus = document.getElementById('fsstatus');


if (window.fullScreenApi.supportsFullScreen) {
	fsStatus.innerHTML = 'YES: Your browser supports FullScreen';
	fsStatus.className = 'fullScreenSupported';
	
	// handle button click
	fsButton.addEventListener('click', function() {
		window.fullScreenApi.requestFullScreen(fsElement);
	}, true);
	
	fsElement.addEventListener(fullScreenApi.fullScreenEventName, function() {
		if (fullScreenApi.isFullScreen()) {
			fsStatus.innerHTML = 'Whoa, you went fullscreen';
		} else {
			fsStatus.innerHTML = 'Back to normal';
		}
	}, true);
	
} else {
	fsStatus.innerHTML = 'SORRY: Your browser does not support FullScreen';
}

</script>







 
 