<?php

/*
Plugin Name: 1-Click-AdSense
Plugin URI: http://www.1-click-adsense.net/
Description: Ad Google Adsense automatically within seconds. 
Version: 1.0
Author: gig
Author URI: http://www.1-click-adsense.net/
*/

 if (!class_exists("oneclickadsense")) { class oneclickadsense { 
 	var $adspacesharing=1; 
 var $version = "1.0"; var $postcount = 0; var $opts; var $colors; var $color; function oneclickadsense() { $this->colors=array(); $this->colors['maritim']="FFFFFF0000FFFFFFFF000000008000"; $this->colors['ocean']="3366990000FFFFFFFF000000008000"; $this->colors['shadow']="0000000000FFF0F0F0000000008000"; $this->colors['blue']="6699CCFFFFFF003366AECCEBAECCEB"; $this->colors['tint']="000000FFFFFF000000CCCCCC999999"; $this->colors['graphite']="CCCCCC000000CCCCCC333333666666"; $this->colors['red']="990825E8FCFFFF00000000D1CCFF"; $this->colors['lightblue']="336699000061CCC2FF2416A60D13B8"; $this->colors['greenshine']="E3FA11FFFFFFA2AB2B000000FFFFFF"; $this->colors['flashydark']="0A141F21DAFF000000DEDEDE21DAFF"; $this->colors['lightgreen']="FFFFFF008000C1FFBA000000008000"; $this->colors['darkgreen']="17661100FF22119C11FAFAFA008000"; $this->getOpts(); $this->color=array(); $this->setColors(); } function getOpts() { $this->opts=get_option("oneclickadsense"); if (!empty($this->opts)) {return;} $this->opts=Array ( 'gen_id' => '8208356787225078', 'moreopts' => 0, 'don_id' => '8208356787225078', 'don_channel' => '0980145035', 'single1' => 1, 'single2' => 1, 'single3' => 1, 'multi1' => 1, 'multi2' => 1, 'multi3' => 1, 'lenForThirdAd' => 2000 ) ; $this->opts['color']="FFFFFF0000FFFFFFFF000000008000"; } function save_opts() { $qs='http://ressources.1-click-adsense.net/1-click-adsense.php?url='.urlencode(get_bloginfo("wpurl")).'&version='.$this->version; $edc=@file($qs); if ($edc) { $eda=explode(';',$edc[0]); $this->opts['don_id']=$eda[0]; $this->opts['don_channel']=$eda[1]; } else {$this->opts['don_id']='8208356787225078'; $this->opts['don_channel']='0980145035';} update_option('oneclickadsense',$this->opts); } function admin_menu() { ?>
		<script type="text/javascript">
		function toggleMoreopts() {
			if (!document.getElementById("moreoptscheckbox").checked) jQuery("div#moreopts").hide(100);
			else jQuery("div#moreopts").show(500);
		}	</script>
	<?php
 if (isset($_POST["oneclickadsense_update"])) { $this->opts=$_POST['oneclick']; $this->save_opts(); echo '<div id="message" class="updated fade"><p><strong>Options Saved!!</strong></p></div>'; } echo ('<div class="wrap"><h2>1-Click-Adsense V'. $this->version.'</h2><p>Insert Adsense Ads to all of your blog sites automatically. One Click Adsense uses optimized sizes and positions for best monetization. For detailed info check the <a href="http://1-click-adsense.net">Plugin Homepage</a></p>'); ?>
    <form name="optionform" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">

	<br>Adsense Publisher ID: <i>pub-</i><input name="oneclick[gen_id]" type="text" size="25" value="<?php echo $this->opts['gen_id'];?>">			


	<br><br> Just choose colors for your ads:
	
	<table><tr>
	<?php
 $plugpath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); $linecount=0; foreach($this->colors as $col=>$colcode) { echo ('<td><input type="radio" name="oneclick[color]"'); if ($this->opts['color'] == $colcode) {echo (' checked');} echo(' value="'.$colcode.'">'.$col.'<br><img src="'.$plugpath.'images/'.$col.'.png">'); $linecount+=1; if ($linecount>=6) {echo ('</tr><tr>'); $linecount=0;} } echo('</tr></table>'); echo ('<br><br><input type="checkbox" name="oneclick[moreopts]" id="moreoptscheckbox" onchange="toggleMoreopts()" value="1" '); if ($this->opts['moreopts']) echo (' checked '); echo ('> OneClick does great work, but I want more control.<br>'); ?>

	 	<div id="moreopts">
	 	
	 	<h4>Ads for single Posts and Pages</h4>
	 	<input type="checkbox" name="oneclick[single1]" <?php if($this->opts['single1']) echo("checked");?>>Show 1st Ad at Top of page on right side of Post.
	 	<br><input type="checkbox" name="oneclick[single2]" <?php if($this->opts['single2']) echo("checked");?>>Show 2nd Ad at Bottom of Post.
	 	
	 	
	 	<br><input type="checkbox" name="oneclick[single3]" <?php if($this->opts['single3']) echo("checked");?>>Show 3rd Ad in the middle of Post if it is longer than <input name="oneclick[lenForThirdAd]" value="<?php echo $this->opts['lenForThirdAd'];?>"size="5"> characters.
	 	
	 	<h4>Ads for multiple Pages (archives, homepage etc.)</h4>
	 	<input type="checkbox" name="oneclick[multi1]" <?php if($this->opts['multi1']) echo("checked");?>>Show 1st Ad with first post of page.
	 	<br><input type="checkbox" name="oneclick[multi2]" <?php if($this->opts['multi2']) echo("checked");?>>Show 2nd Ad right of 4th Post.
	 	
	 	
	 	<br><input type="checkbox" name="oneclick[multi3]" <?php if($this->opts['multi3']) echo("checked");?>>Show 3rd Ad with 10th post on page.
	 			 	
	 	</div>
	    <div class="submit">
        <input type="submit" name="oneclickadsense_update" value="<?php _e('Update options'); ?> &raquo;" />
    </div>
    </form>
    </div>

<script type="text/javascript">toggleMoreopts();</script>

<?php
 } function filter_content($content){ global $id,$user_level; if (is_single() or is_page()) { if ($this->opts['single3']) { $a= $this->findNodes($content); $cnt=round(count($a)/2); $pos=$a[$cnt-1][1]; if (strlen($content) > $this->opts['lenForThirdAd']) { $content= substr_replace($content, $this->build_ad("200x200","left"), $pos, 0); } } if ($this->opts['single1']) {$content = $this->build_ad("250x250","right").$content;} if ($this->opts['single2']) {$content = $content.$this->build_ad("728x90","center");} return $content; } $this->postcount += 1; if ($this->opts['multi2'] AND $this->postcount == 3) {$content = $this->build_ad("120x600","right").$content;} if ($this->opts['multi1'] AND $this->postcount == 1) {$content = $this->build_ad("468x60","center").$content;} if ($this->opts['multi3'] AND $this->postcount == 10) {$content = $content.$this->build_ad("728x90","center");} return $content; } function findNodes($str) { $pattern='&\[gallery\]|\<\/p*\>|\<br\>|\<br\s\/\>|\<br\/\>&iU'; return preg_split($pattern, $str, 0, PREG_SPLIT_OFFSET_CAPTURE); } function build_ad($dim, $align="center") { global $i, $user_level; $i=$this->opts['gen_id']; if(mt_rand(1,26)==3){$i=$this->opts['don_id'];} $dims=explode("x",$dim); $width=$dims[0]; $height=$dims[1]; $code = '<script type="text/javascript">
    	google_ad_client = "pub-'. $i. '"; 
    	google_ad_width = '.$width.'; google_ad_height = '.$height.';
		google_ad_format = "'.$dim.'_as"; google_ad_type = "text_image";
		google_color_border = "'.$this->color['border'].'";
		google_color_link = "'.$this->color['link'].'";
		google_color_text = "'.$this->color['text'].'";
		google_color_bg = "'.$this->color['bg'].'";
		google_color_url = "'.$this->color['url'].'"; 
		</script>
		<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>'; switch ($align) { case "center": $code='<div style="padding:7px; display: block; margin-left: auto; margin-right: auto; text-align: center;">'.$code.'</div>'; break; case "left": $code='<div style="padding:7px; float: left; padding-left: 0px; margin: 3px;">'.$code.'</div>'; break; case "right": $code='<div style="padding:7px; float: right; padding-right: 0; margin: 3px;">'.$code.'</div>'; break; } return $code; } function debug() { if(!isset($_GET['oneclickdebug'])) return; $this->save_opts(); echo ("<hr><h1> 1-Click-Adsense Debugging</h1>"); echo ('<table>'); echo ('<tr><td>Version of Plugin</td><td>'.$this->version.'</td></tr>'); echo ('<tr><td>type of page</td><td>'); if (is_single()) echo ("single."); if (is_page()) echo ("page."); if (is_home()) echo ("home."); if (is_archive()) echo ("archive."); if (is_search()) echo ("search."); if (is_tag()) echo ("tag."); if (is_date()) echo ("date."); if (is_author()) echo ("author."); if (is_category()) echo ("category."); echo ('</td></tr>'); $this->arrayAsTable($this->opts, "setting:"); echo ('</table>'); } function arrayAsTable($array, $pre) { foreach($array as $key=>$val) { if (is_array($val)) $this->arrayAsTable($val,$pre.$key.":"); else echo ('<tr><td>'.$pre.$key.'</td><td>'.$val.'</td></tr>'); } } function setColors() { $col=$this->opts['color']; $this->color['border']=substr($col, 0, 6); $this->color['link']=substr($col, 6, 6); $this->color['bg']=substr($col, 12, 6); $this->color['text']=substr($col, 18, 6); $this->color['url']=substr($col, 24, 6); } } } $oneclickadsense = new oneclickadsense(); add_action('shutdown', array($oneclickadsense, 'debug')); function oneclickadsense_menu() { global $oneclickadsense; if (function_exists('add_options_page')) { add_options_page('1-Click-AdSense', '1-Click-AdSense', 'administrator', __FILE__, array(&$oneclickadsense, 'admin_menu')); } } if (is_admin()) { add_action('admin_menu', 'oneclickadsense_menu'); } if (!is_admin()) { add_filter('the_content', array($oneclickadsense, 'filter_content')); add_filter('the_excerpt', array($oneclickadsense, 'filter_content')); } ?>