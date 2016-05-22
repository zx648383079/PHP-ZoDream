//package	jquery-1.8.3.min.js



   /*
    * 选项卡
    *
    * 16:46 2014/1/14	Zhanghong
    */

    (function() {

      /* 元素 */ {

        var tabControl              = jQuery('div.tab-control');
        var tabControl_tab          = jQuery('div.tab-control div.tab');
        var tabControl_tab_ul       = jQuery('div.tab-control div.tab ul');
        var tabControl_tab_prev     = jQuery('div.tab-control div.tab input.prev');
        var tabControl_tab_next     = jQuery('div.tab-control div.tab input.next');
        var tabControl_tab_find     = jQuery('div.tab-control div.tab input.find');
        var tabControl_tabFind      = jQuery('div.tab-control div.tab-find');
        var tabControl_tabFind_form = jQuery('div.tab-control div.tab-find form');
        var tabControl_tabFind_ul   = jQuery('div.tab-control div.tab-find ul');
        var tabControl_tabFind_text = jQuery('div.tab-control div.tab-find input.text');
        var tabControl_main         = jQuery('div.tab-control div.main');

      }

      /* 函数 */ {

        var position = function(p/* NUMBER position */) {

          for(var $ = jQuery(tabControl_tab_ul).find('li'), w = jQuery(tabControl_tab_ul).width(), i = 0; $[i]; i++, p += 140) {

            $[i].style.cssText = '';

            //负偏移
            if(p < 0) {

              $[i].style.marginLeft = (-140 < p ? p : -140) + 'px';

            }

            //正偏移
            if(p < w && w < p + 140) {

              $[i].style.marginRight = '-140px';

            }

          }

        };

        var offset = function(p/* NUMBER position */) {

          var $ = jQuery(tabControl_tab_ul).find('li');

          if(p === undefined) {

            //滚动量
            for(var i = 0, j = 0; $[i].style.marginLeft; i++) {

              j += parseInt($[i].style.marginLeft);

            }

          } else if($[p].style.marginLeft) {

            //负偏移
            for(var i = p, j = 0; $[i].style.marginLeft; i++) {

              j += parseInt($[i].style.marginLeft || 0);

            }

          } else {

            //正偏移
            for(var i = 0, j = 140 - jQuery(tabControl_tab_ul).width(); i != p; i++) {

              j += parseInt($[i].style.marginLeft || 0) + 140;

            }

          }

          return j;

        };

        var scroll = function(p/* Number param */) {

          var $ = jQuery(tabControl_tab_ul).find('li');

          //时钟
          var i = window.setInterval(function() {

            var a = offset();
            var b = offset($.length - 1);

            //缓动
            if(p > 0) {

              position(a - a / 10);

            } else {

              position(a - (b + 4) / 10);

            }

          }, 20);

          var clear = function() {

            //清除时钟
            window.clearInterval(i);

            //清除事件
            jQuery(tabControl).unbind('mouseup', clear);

            //向前状态
            jQuery(tabControl_tab_prev).attr('class', 'prev' + ($[0].style.marginLeft ? ' scroll' : ''));

            //向后状态
            jQuery(tabControl_tab_next).attr('class', 'next' + (offset($.length - 1) > 5 ? ' scroll' : ''));

          };

          jQuery(tabControl).mouseup(clear);

        };

        var append = function(i/* String index */, t/* String tab */, h/* String href */, r/* String reload */) {

          var $1 = jQuery(tabControl_tab_ul).find('li');
          var $2 = jQuery(tabControl_main)  .find('iframe');

          for(var $ = -1, j = 0; $1[j]; j++) {

            if($1[j].getAttribute('index') == i) {
              $ = j;
            }

            $1[j].className = 'visited';
            $2[j].className = 'visited';

          }

          if($ > -1) {

            $1[$].className = 'hover';
            $2[$].className = '';

            if($2[$].getAttribute('reload') == '1') {
			
			  //重新加载
              $2[$].contentWindow.location.reload(true);

			}

          } else if(t) {

            jQuery(tabControl_tab_ul)    .append('<li index="' + i + '" tab="' + t + '" class="hover">' + t + '<a href="javascript:;">关闭</a></li>');
            jQuery(tabControl_tabFind_ul).append('<li index="' + i + '" tab="' + t + '">' + t + '<a href="javascript:;">关闭</a></li>');
            jQuery(tabControl_main)      .append('<iframe src="' + h + '" scrolling="auto" frameborder="0" reload="' + r + '"></iframe>');

          }

          change();

        };

        var remove = function(i/* String index */) {

          var $1 = jQuery(tabControl_tab_ul)    .find('li');
          var $2 = jQuery(tabControl_tabFind_ul).find('li');
          var $3 = jQuery(tabControl_main)      .find('iframe');

          for(var j = 0; $1[j]; j++) {

            if($1[j].getAttribute('index') == i) {

              //删除
              $1[j].parentNode.removeChild($1[j]);
              $2[j].parentNode.removeChild($2[j]);
              $3[j].parentNode.removeChild($3[j]);

              //替换
              if($1[j].className == 'hover') {
                append($1[j ? j - 1 : j + 1].getAttribute('index'));
              }

            }

          }

          change();

        };

      }

      /* 方法 */ {

        var change = function(e/* OBJECT event */) {

          var $ = jQuery(tabControl_tab_ul).find('li');

          if(!$.length) {
            return;
          }

          //状态
          if($.length * 140 > jQuery(tabControl_tab_ul).width()) {

            jQuery(tabControl_tab).attr('class', 'tab');

          } else {

            jQuery(tabControl_tab).attr('class', 'tab simple');

          }

          //位置
          for(var i = 0; $[i]; i++) {

            if($[i].className == 'hover') {

              var a = offset(), b = offset(i), c = offset($.length - 1);

              if($[i].style.marginLeft) {
                position(b - a);
              } else if(b > 0) {
                position(a - b);
              } else if(c < 0) {
                position(a - c);
              } else {
                position(a);
              }

              break;

            }

          }

          //向前状态
          jQuery(tabControl_tab_prev).attr('class', 'prev' + ($[0].style.marginLeft ? ' scroll' : ''));

          //向后状态
          jQuery(tabControl_tab_next).attr('class', 'next' + (offset($.length - 1) > 5 ? ' scroll' : ''));

          //改变尺寸
          jQuery(tabControl_main).css('height', (jQuery(document).height() - 160) + 'px');

        };

        var tab = function(e/* OBJECT event */) {

          var t = e.target;

          if(t.tagName == 'LI') {

            append(t.getAttribute('index'));

            jQuery(tabControl_tabFind).attr('class', 'tab-find hidden');

          }

          if(t.innerHTML == '关闭') {

            remove(t.parentNode.getAttribute('index'));

            jQuery(tabControl_tabFind).attr('class', 'tab-find hidden');

          }

        };

        var roll = function(e/* OBJECT event */) {

          var t = e.target;

          if(t.className == 'prev scroll') {
            scroll(1);
          }

          if(t.className == 'next scroll') {
            scroll(-1);
          }

        };

        var find = function(e/* OBJECT event */) {

          //查找
          for(var $ = jQuery(tabControl_tabFind_ul).find('li'), v = jQuery(tabControl_tabFind_text).val(), i = 0; $[i]; i++) {

            $[i].className = (v && $[i].getAttribute('tab').toLowerCase().indexOf(v.toLowerCase()) > -1);

          }

          //背景
          jQuery(tabControl_tabFind_form).attr('class', v ? 'visited' : '');

        };

        var find_hover = function(e/* OBJECT event */) {

          //背景
          jQuery(tabControl_tabFind_form).attr('class', 'hover');

        };

        var find_over = function(e/* OBJECT event */) {

          try {

            var t = e.target;

            if(t.className == 'find') {

              //显示
              jQuery(tabControl_tabFind).attr('class', 'tab-find');

              //背景
              jQuery(tabControl_tabFind_form).attr('class', jQuery(tabControl_tabFind_text).val() ? 'hover' : '');

            } else {

              for(t; t; t = t.parentNode) {

                if(t.className.indexOf('tab-find') > -1) {
                  return;
                }

              }

            }

          } catch(ex) {

            //隐藏
            jQuery(tabControl_tabFind).attr('class', 'tab-find hidden');

          }

        };

        var find_submit = function(e/* OBJECT event */) {

          //查找
          for(var $ = jQuery(tabControl_tabFind_ul).find('li'), v = jQuery(tabControl_tabFind_text).val(), i = 0; $[i]; i++) {

            if(v && $[i].getAttribute('tab').toLowerCase().indexOf(v.toLowerCase()) > -1) {

              append($[i].getAttribute('index'));

              jQuery(tabControl_tabFind).attr('class', 'tab-find hidden');

              return false;

            }

          }

          return false;

        };

      }

      /* 绑定 */ {

        //改变尺寸
        jQuery(window).resize(change);

        //切换标签
        jQuery(tabControl).mousedown(tab);

        //滚动标签
        jQuery(tabControl).mousedown(roll);

        //搜索标签
        jQuery(tabControl_tabFind_text).keyup(find);

        //搜索背景
        jQuery(tabControl_tabFind_text).keydown(find_hover);

        //搜索提交
        jQuery(tabControl_tabFind_form).submit(find_submit);

        //搜索显隐
        jQuery(document).mouseover(find_over);

      }

      /* 初始化 */ {

        TabControlAppend = append;
        TabControlRemove = remove;

        change();

      }

    })();
