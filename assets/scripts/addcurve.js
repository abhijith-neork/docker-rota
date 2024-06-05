	function add_curves(){

		var count=$("ul.mylist li:last").children().length;
		var count_first=$("ul.mylist li:first").children().length;
		if(count_first==1){
			$("ul.mylist li:first").find("div").children().eq(0).addClass('fir-img sec-img');
		}else if(count_first==2){
			console.log($("ul.mylist li:first").find("div").children());
			$("ul.mylist li:first").find("div").children().eq(0).addClass('fir-img');
			$("ul.mylist li:first").find("div").children().eq(6).addClass('sec-img');
		}else if(count_first==3){
			$("ul.mylist li:first").find("div").children().eq(0).addClass('fir-img');
			$("ul.mylist li:first").find("div").children().eq(12).addClass('sec-img');
		}
			

		if(count==1){
			$("ul.mylist li:last").find("div").children().eq(0).addClass('thir-img four-img');
		}else if(count==2){
			console.log($("ul.mylist li:last").find("div").children());
			$("ul.mylist li:last").find("div").children().eq(0).addClass('thir-img');
			$("ul.mylist li:last").find("div").children().eq(6).addClass('four-img');
		}else if(count==3){
			$("ul.mylist li:last").find("div").children().eq(0).addClass('thir-img');
			$("ul.mylist li:last").find("div").children().eq(12).addClass('four-img');
		}
			}
			
