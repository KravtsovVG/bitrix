$(function(){
	$('[onclick="StartClearCache();"]').on('click',function(){
		StartClearCache();
		$.post('/include/myCache/cache.php',{bfsdhdbrthbxcgzfg_dfgbs234e2f:1},function(msg){console.log(msg);});
	});
});