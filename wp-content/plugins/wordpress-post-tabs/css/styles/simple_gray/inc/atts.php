<?php 
require_once( dirname ( dirname ( dirname( dirname(__FILE__) ) ) ). '/core/wpts-config.php');
$html='<tr valign="top"> 
<td scope="row"><label for="location">'.__('Tabs Location','wptspro').'</label></td> 
<td>
<select name="location" >
<option value="top">'.__('Top','wptspro').'</option>
<option value="bottom">'.__('Bottom','wptspro').'</option>
<option value="left">'.__('Left','wptspro').'</option>
<option value="right">'.__('Right','wptspro').'</option>
</select>
</td> 
</tr> 

<tr valign="top">
<td scope="row"><label for="width">'.__('Width of the Tab Container','wptspro').'</label></td>
<td><input type="text" name="width" id="width" class="regular-text code" value="" /></td>
</tr>

<tr valign="top"> 
<td scope="row"><label for="effect">'.__('Select Effect','wptspro').'</label></td> 
<td>
<select name="effect" >
<option value="">'.__('No Effect','wptspro').'</option>
<option value="fade">'.__('fade','wptspro').'</option>
<option value="blind">'.__('blind','wptspro').'</option>
<option value="bounce">'.__('bounce','wptspro').'</option>
<option value="clip">'.__('clip','wptspro').'</option>
<option value="drop">'.__('drop','wptspro').'</option>
<option value="explode">'.__('explode','wptspro').'</option>
<option value="fold">'.__('fold','wptspro').'</option>
<option value="highlight">'.__('highlight','wptspro').'</option>
<option value="pulsate">'.__('pulsate','wptspro').'</option>
<option value="scale">'.__('scale','wptspro').'</option>
<option value="shake">'.__('shake','wptspro').'</option>
<option value="slide">'.__('slide','wptspro').'</option>
<option value="2">'.__('slide down','wptspro').'</option>
</option>
</select>
</td> 
</tr> 

<tr valign="top"> 
<td scope="row"><label for="easing">'.__('Easing','wptspro').'</label></td> 
<td>
<select name="easing" >
<option value="">'.__('linear','wptspro').'</option>
<option value="swing">'.__('swing','wptspro').'</option>
<option value="easeInQuad">'.__('easeInQuad','wptspro').'</option>
<option value="easeOutQuad">'.__('easeOutQuad','wptspro').'</option>
<option value="easeInOutQuad">'.__('easeInOutQuad','wptspro').'</option>
<option value="easeInCubic">'.__('easeInCubic','wptspro').'</option>
<option value="easeOutCubic">'.__('easeOutCubic','wptspro').'</option>
<option value="easeInOutCubic">'.__('easeInOutCubic','wptspro').'</option>
<option value="easeInQuart">'.__('easeInQuart','wptspro').'</option>
<option value="easeOutQuart">'.__('easeOutQuart','wptspro').'</option>
<option value="easeInOutQuart">'.__('easeInOutQuart','wptspro').'</option>
<option value="easeInQuint">'.__('easeInQuint','wptspro').'</option>
<option value="easeOutQuint">'.__('easeOutQuint','wptspro').'</option>
<option value="easeInOutQuint">'.__('easeInOutQuint','wptspro').'</option>
<option value="easeInExpo">'.__('easeInExpo','wptspro').'</option>
<option value="easeOutExpo">'.__('easeOutExpo','wptspro').'</option>
<option value="easeInOutExpo">'.__('easeInOutExpo','wptspro').'</option>
<option value="easeInSine">'.__('easeInSine','wptspro').'</option>
<option value="easeOutSine">'.__('easeOutSine','wptspro').'</option>
<option value="easeInOutSine">'.__('easeInOutSine','wptspro').'</option>
<option value="easeInCirc">'.__('easeInCirc','wptspro').'</option>
<option value="easeOutCirc">'.__('easeOutCirc','wptspro').'</option>
<option value="easeInOutCirc">'.__('easeInOutCirc','wptspro').'</option>
<option value="easeInElastic">'.__('easeInElastic','wptspro').'</option>
<option value="easeOutElastic">'.__('easeOutElastic','wptspro').'</option>
<option value="easeInOutElastic">'.__('easeInOutElastic','wptspro').'</option>
<option value="easeInBack">'.__('easeInBack','wptspro').'</option>
<option value="easeOutBack">'.__('easeOutBack','wptspro').'</option>
<option value="easeInOutBack">'.__('easeInOutBack','wptspro').'</option>
<option value="easeInBounce">'.__('easeInBounce','wptspro').'</option>
<option value="easeOutBounce">'.__('easeOutBounce','wptspro').'</option>
<option value="easeInOutBounce">'.__('easeInOutBounce','wptspro').'</option>
</option>
</select>
</td> 
</tr> 

<tr valign="top">
<td scope="row"><label for="effectduration">'.__('Duration of the Effect','wptspro').'</label></td>
<td><input type="text" name="effectduration" id="effectduration" class="regular-text code" value="" /></td>
</tr>

<tr valign="top"> 
<td scope="row"><label for="onhover">'.__('Tab transition On Hover','wptspro').'</label></td> 
<td><input name="onhover" type="checkbox" id="onhover" value="1" /></td> 
</tr> 

<tr valign="top"> 
<td scope="row"><label for="showtitle">'.__('Show Title Attribute for Tab','wptspro').'</label></td> 
<td><input name="showtitle" type="checkbox" id="showtitle" value="1" /></td> 
</tr> 

<tr valign="top"> 
<td scope="row"><label for="targetblank">'.__('Open Tab External Links in New Window','wptspro').' </label></td> 
<td><input name="targetblank" type="checkbox" id="targetblank" value="1" /></td> 
</tr>

<tr valign="top"> 
<td scope="row"><label for="auto">'.__('Enable Auto Rotate Tabs','wptspro').'</label></td> 
<td><input name="auto" type="checkbox" id="prevnext" value="1" /></td> 
</tr>

<tr valign="top"> 
<td scope="row"><label for="prevnext">'.__('Enable Prev, Next navigation','wptspro').'</label></td> 
<td><input name="prevnext" type="checkbox" id="prevnext" value="1" /></td> 
</tr> 

<tr valign="top">
<td scope="row"><label for="prevtext">'.__('\'Prev\' navigation text','wptspro').'</label></td>
<td><input type="text" name="prevtext" id="prevtext" class="regular-text code" value="" /></td>
</tr>

<tr valign="top">
<td scope="row"><label for="nexttext">'.__('\'Next\' navigation text','wptspro').'</label></td>
<td><input type="text" name="nexttext" id="nexttext" class="regular-text code" value="" /></td>
</tr>';
print($html);
?>
