    $(function() {
        //var $topbar = $('.topbar');
        //var html = '<div class="search"><input type="text" class="search-text" name="search" value="" placeholder="搜索"  /><i class="glyphicon glyphicon-search sou" ></i></div>';
        //$topbar.append(html);
        var roleType = $('.username a span');
        switch (roleType.text()) {
            case 'admin':
                roleType.text('管理员')
                break;
            case 'sched':
                roleType.text('交易员')
                break;
            case 'finance':
                roleType.text('财务')
                break;
            case 'manager':
                roleType.text('经理')
                break;
        }


    });