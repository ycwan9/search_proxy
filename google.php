<!DOCTYPE html>
<html>
<head>
<title>goo <?php if (array_key_exists("q",$_GET)){ echo $_GET["q"];} ?></title>
<meta name="generator" content="Bluefish 2.2.5" >
<meta name="author" content="ycwan9" >
<meta name="date" content="2015-10-11T18:03:25+0800" >
<meta name="copyright" content="">
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8">
<meta http-equiv="content-style-type" content="text/css">
<meta http-equiv="expires" content="0">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<style>
#goo_form{
font-size: 2em;
}
#goo_text{
width: 60%;
}
#goo_search{
width: 20%;
}
@media (max-device-width: 400px){
#goo_text{
width: 100%;
}
#goo_search{
width: 100%;
}
}
</style>
</head>
<body>
<form method="get" id="goo_form">
<input type="text" name="q" id="goo_text" value="<?php if (array_key_exists("q",$_GET)){ echo $_GET["q"];} ?>" />
<input type="submit" name="search" id="goo_search" value="search" />
</form>
<hr />
<?php
if (array_key_exists("q",$_GET)){
	$header = array("Accept-Language"=>"zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3");
	$url = "https://ajax.googleapis.com/ajax/services/search/web?v=1.0&rsz=8&q=".urlencode($_GET["q"]);
	if (array_key_exists("start",$_GET)){
		$url = $url."&start=".$_GET["start"];
		//page control
	}
	if (array_key_exists("hl",$_GET)){
		$url = $url."&hl=".$_GET["hl"];
		//set language
	}
	//var_dump($url);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url) or die("url");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1) or die("RETURNTRANSFER");
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header) or die("head");
	//curl_setopt($ch, CURLOPT_REFERER, /* Enter the URL of your site here */);
	$rep = curl_exec($ch);
	curl_close($ch);
	//var_dump($rep);
	// now, process the JSON string
	$json = json_decode($rep, true);
	//var_dump($json);
	//print result
	foreach ($json["responseData"]["results"] as $i){
		echo "<a href=\"",$i["unescapedUrl"],"\" ><h2>",$i["title"],"</h2></a>\n";
		echo "<i>",$i["visibleUrl"],"</i>&nbsp;&nbsp;<a href=\"",$i["cacheUrl"],"\" >Cache</a>\n";
		echo "<p>",$i["content"],"</p><hr />\n";
	}
	echo "<p><i>",$json["responseData"]["cursor"]["resultCount"]," in ",$json["responseData"]["cursor"]["searchResultTime"]," sec.</i></p>\n";
	//prev & next 
	if (array_key_exists("start",$_GET)){
		$no_start = str_replace("&start=".$_GET["start"], "", $_SERVER["REQUEST_URI"]);
		echo "<a href=\"",$no_start,"&start=",strval(intval($_GET["start"]-8)),"\" >&lt;prev</a>\n";
		echo strval($json["responseData"]["cursor"]["currentPageIndex"]);
		echo "\n<a href=\"",$no_start,"&start=",strval(intval($_GET["start"]+8)),"\" >&gt;next</a>\n";
	}
	else{
		$no_start =  $_SERVER["REQUEST_URI"];
		echo "\n<a href=\"",$_SERVER["REQUEST_URI"],"&start=8","\" >&gt;next</a>\n";
	}
	echo "<p>responseDetails: ",$json["responseDetails"],"<br />responseStatus: ",strval($json["responseStatus"]),"</p>\n";
}
else{
	echo "hello";
}
?>

</body>
</html>

