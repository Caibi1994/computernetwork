
	jQuery(document).ready(function($) {
		$(".zan").click(function(e){
			var $i=$(".zan i"), $b=$("<b>").text("已收藏"), n=parseInt($i.text());
			$b.css({
				"bottom":0,
				"z-index":0,
			});
			$i.text(n+1);
			$(".zan").append($b);
			$b.animate({"bottom":100,"opacity":0},1000,function(){$b.remove();});
			var d = setInterval(function(){
				clearInterval(d);
				if($(".zan b").length == 1){
					$.post("",{zan:$i.text()})
				}
			},1000)
			e.stopPropagation();
		});
	});


