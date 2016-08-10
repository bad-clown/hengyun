$(function() {
	$.material.init();
	if(window._global) {return}
	window._global = {};

	_global.badge = function() {
		$.getJSON('http://120.26.50.11:9000/sched/order-web/order-cnt', function(data) {
			if(data.total){$('#total-cnt').show().html(data.total);}
			if(data.new){$('#new-cnt').show().html(data.new);}
			if(data.bid){$('#bid-cnt').show().html(data.bid);}
		})
	}
	_global.Digit = function(n) {
		return n < 10 ? "0"+n : n;
	}
	_global.FormatTime = function(n) {
		var nS= new Date(parseInt(n) * 1000),
			year=_global.Digit(nS.getFullYear()),
			month=_global.Digit(nS.getMonth()+1),
			date=_global.Digit(nS.getDate()),
			hour=_global.Digit(nS.getHours()),
			minute=_global.Digit(nS.getMinutes());
		return year+"年"+month+"月"+date+"日 "+hour+":"+minute;
	}
	_global.badge();

});