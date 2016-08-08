$(function() {
	$.material.init();

	$.getJSON('http://120.26.50.11:9000/sched/order-web/order-cnt', function(data) {
		if(data.total){$('#total-cnt').show().html(data.total);}
		if(data.new){$('#new-cnt').show().html(data.new);}
		if(data.bid){$('#bid-cnt').show().html(data.bid);}
	})

});