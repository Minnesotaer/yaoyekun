<?php
//获取json值
function getParamFromjson(){
	return json_decode(I('param','','strip_tags'),true);
}

?>