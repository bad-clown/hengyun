$(function() {
	$.material.init();
	if(window._global) {return}
	window._global = {};
	_global.namespace = function(str) {
		var arr = str.split('.'),
			o = _global;
		for (i = (arr[0] == '_global') ? 1 : 0; i < arr.length; i++) {
			o[arr[i]] = o[arr[i]] || {};
			o = o[arr[i]];
		}
	}

	_global.namespace('Sched');

	_global.Sched = {
		status : {
			100 : "新发布",
			200 : "待确认",
			300 : "待派车",
			400 : "待提货",
			500 : "在途中",
			600 : "已送达",
			700 : "已完成",
			800 : "已拒绝",
			900 : "已过期",
			1000 : "已失效",
			1100 : "已取消"
		},
		priceType : {
			0 : '单价',
			1 : '一口价'
		}
	}
	window.Sched = _global.Sched;

	_global.namespace('PageTotal');
	_global.PageTotal = {
		init : function(obj,d,actPage) {
			this.obj = $(obj),
			this.current = actPage, 	//当前页
			this.pageCount = 10, 		//每页显示的数据量
			this.total = d.pageCnt, 	//总共的页码
			this.first = 1, 			//首页
			this.last = 0, 				//尾页
			this.pre = 0, 				//上一页
			this.next = 0, 				//下一页
			this.getData(this.current, this.total)
		},
		getData: function(n, t) {
			this.obj.empty();
			if (n == null) {n = 1;}
			this.current = n;
			this.page();
		},
		getPages: function() {
			this.last = this.total;
			this.pre = this.current - 1 <= 0 ? 1 : (this.current - 1);
			this.next = this.current + 1 >= this.total ? this.total : (this.current + 1);
		},
		page: function() {
			this.obj.empty();
			var x = 4;
			this.getPages();

			if(this.total > x) {
				var index = this.current <= Math.ceil(x / 2) ? 1 : (this.current) >= this.total - Math.ceil(x / 2) ? this.total - x : (this.current - Math.ceil(x / 2));

				var end = this.current <= Math.ceil(x / 2) ? (x + 1) : (this.current + Math.ceil(x / 2)) >= this.total ? this.total : (this.current + Math.ceil(x / 2));
			}
			else {
				var index = 1;

				var end = this.total;
			}
			if (this.current > 1) {
				this.obj.append("<li class='prev'><a href='javascript:;' data-page='"+(this.current - 1)+"'>«</a></li>");
			}
			else if(this.current == 1){
				this.obj.append("<li class='prev disabled'><span>«</span></li>");
			}

			for (var i = index; i <= end; i++) {
				if (i == this.current) {
					this.obj.append("<li class='active'><a href='javascript:;' data-page='"+(this.current)+"'>"+i+"</a></li>");
				} else {
					this.obj.append("<li><a href='javascript:;' data-page='"+i+"'>"+i+"</a></li>");
				}
			}

			if (this.current < end) {
				this.obj.append("<li class='next'><a href='javascript:;' data-page='"+(this.current + 1)+"'>»</a></li>");
			}
			else if(this.current == end){
				this.obj.append("<li class='next disabled'><span>»</span></li>");
			}
		}
	};
	window.PageTotal = _global.PageTotal;

	_global.Digit = function(n) {
		return n < 10 ? "0"+n : n;
	};
	_global.FormatTime = function(n, t) {
		var t = t || 0;
		var nS= new Date(parseInt(n) * 1000),
			year=_global.Digit(nS.getFullYear()),
			month=_global.Digit(nS.getMonth()+1),
			date=_global.Digit(nS.getDate()),
			hour=_global.Digit(nS.getHours()),
			minute=_global.Digit(nS.getMinutes());
		if(t) {
			return year+"-"+month+"-"+date+" "+hour+":"+minute+":00";
		}
		else {
			return year+"年"+month+"月"+date+"日 "+hour+":"+minute;
		}
	};
	_global.badge = function() {
		$.getJSON($_Path+'/sched/order-web/order-cnt', function(data) {
			if(data.total){$("#total-cnt",parent.document).show().html(data.total);}else{$("#total-cnt",parent.document).hide().html("");}
			if(data.new){$("#new-cnt",parent.document).show().html(data.new);}else{$("#new-cnt",parent.document).hide().html("");}
			if(data.bid){$("#bid-cnt",parent.document).show().html(data.bid);}else{$("#bid-cnt",parent.document).hide().html("");}
			if(data.trans){$("#trans-cnt",parent.document).show().html(data.trans);}else{$("#trans-cnt",parent.document).hide().html("");}
		})
	};
});
