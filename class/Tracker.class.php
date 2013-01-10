<?PHP

class Tracker{

    static function tSQL($query, $start, $end){
        if(!Tracker::_trackable()) return;
        
        $backtrace = debug_backtrace();
        $bt = array();
        for($x = 1; $x < 10 && $x < count($backtrace); $x++){
            $bt[] = array(
                    'file' => $backtrace[$x]['file'], 
                    'line' => $backtrace[$x]['line'], 
                    'function' => $backtrace[$x]['function'] );
        }
        $backtrace = null;
        
        if(!isset($_SESSION['Tracker'])) $_SESSION['Tracker'] = array();
        
        if(isset($_SESSION['Tracker'][0]) 
            && $_SESSION['Tracker'][0]['url'] == $_SERVER['REQUEST_URI']
            && !$_SESSION['Tracker'][0]['end']
            && $_SESSION['Tracker'][0]['start'] >= microtime(true) - 5 ){
            // page already started, add a log
            $_SESSION['Tracker'][0]['sql'][] = array(
                                                    'query' => $query,
                                                    'start' => $start,
                                                    'end' => $end,
                                                    'bt' => $bt);
        }else{
            $new_page = array(
                            'url' => $_SERVER['REQUEST_URI'],
                            'start' =>  microtime(true),
                            'end' => false,
                            'sql' => array(array(
                                        'query' => $query,
                                        'start' => $start,
                                        'end' => $end,
                                        'bt' => $bt))
                            );
            array_unshift($_SESSION['Tracker'], $new_page);
        }
        
        if(count($_SESSION['Tracker']) >= 5){
            array_pop($_SESSION['Tracker']);    
        }
    }
    
    static function rawOut(){
        if(!Tracker::_trackable()) return '';
        
        if(!isset($_SESSION['Tracker'])) return 'Nothing Tracked';
        
        return print_r($_SESSION['Tracker'], true);
    }
    
    static function htmlOut(){
        if(!Tracker::_trackable()) return '';
        
        if(!isset($_SESSION['Tracker']) || !is_array($_SESSION['Tracker'])) return;
        
        $hl = new SQLHighlighter();
        if(!$_SESSION['Tracker'][0]['end']) $_SESSION['Tracker'][0]['end'] = microtime(true);
        
        $total_time_current = ceil(($_SESSION['Tracker'][0]['end'] - $_SESSION['Tracker'][0]['start']) * 10000) / 10;
        $sqlcount = count($_SESSION['Tracker'][0]['sql']);
        
        $html = <<<EOT
<div id="smt" style="position: absolute; width: 100%; top: 0; left: 0; background: white; border-bottom: 1px solid gray; box-shadow: 3px 3px 2px #888; display: none;">
        <div class="container-fluid" style="background: black; color: white; height: 22px; overflow: hidden;">
            <div class="row-fluid">
                <div class="span3">
                    <h5 style="margin:0;">Ultimate Tracker Bar</h5>
                </div>
                <div class="span7">
                    <div id="smt_performance" style="font-size: 0.9em;">Code: {$total_time_current}ms - Queries: {$sqlcount} - </div>
                </div>
                <div class="span1" style="text-align: right;">
                    <a id="smt_display_content" href="#"><i class="icon-chevron-down icon-white"></i></a>
                </div>
            </div>
        </div>
    <div id="smt_content" style="display: none; padding: 0.5em 1em;">
        <ul class="nav nav-tabs" id="smt_tabs">
EOT;

        foreach($_SESSION['Tracker'] as $k => $page){
            $mydate = date('H:i:s', $page['start']);
            if($k == 0){
                $html .= "<li class=\"active\"><a href=\"#smt_tab{$k}\" data-toggle=\"tab\">{$page['url']} <small>{$mydate}</small></a></li>";
            }else{
                $html .= "<li><a href=\"#smt_tab{$k}\" data-toggle=\"tab\">{$page['url']} <small>{$mydate}</small></a></li>";
            }
        }
        
        
        $html .= '</ul><div class="tab-content">';
        
        foreach($_SESSION['Tracker'] as $k => $page){
            $mydate = date('r', $page['start']);
            
            if($k == 0){
                $html .= "<div class=\"tab-pane active\" id=\"smt_tab{$k}\">";
            }else{
                $html .= "<div class=\"tab-pane\" id=\"smt_tab{$k}\">";
            }
            
            $html .= "<div style='height: 22em; overflow: auto;'>";
            
            $sqlhtml = '';
            $sqltotal = 0;
            $sqlcount = count($page['sql']);
            foreach($page['sql'] as $k => $qry){
                $sqltotal += $qry['end'] - $qry['start'];
                $time = round($qry['end'] - $qry['start'], 4);
                $query = nl2br($hl->highlight($qry["query"]));
                $bt = '';
                foreach($qry['bt'] as $trace)
                    $bt .= '<span style="color: blue;">' . basename($trace['file']) . "</span> > {$trace['function']} ({$trace['line']}) <br/>";
                    
                $sqlhtml .= <<<EOT
                    <div style="margin: 0 0 0 2em;">
                        <span style="font-weight:bold; float:left;">Query {$k}: ({$time}s)</span>
                        <p style="margin: 0 0 0 11em; padding:0;">{$query}</p>
                        <p style="margin: 0 0 0 13em; padding:0.5em; font-style:italic; background: #DDD; ">{$bt}</p>
                    </div>
EOT;
            }
            $sqltotal = round($sqltotal, 4);
            $html .= "<h5>Total Querys: {$sqlcount} - {$sqltotal}s</h5>" . $sqlhtml . "</div></div>";
        }
        
        $html .= <<<EOT
        </div> 
    </div>
</div>
<script language="javascript" type="text/javascript">
$(function(){
    
    $('#smt_display_content').click(function(e){
        e.preventDefault();
        if($(this).find('i').hasClass('icon-chevron-down')){
            $(this).find('i').removeClass('icon-chevron-down').addClass('icon-chevron-up');
        }else{
            $(this).find('i').removeClass('icon-chevron-up').addClass('icon-chevron-down');
        }
        $('#smt_content').toggle();
    });
    
    if(window.performance){
        var pb = $('#smt_performance');
        
        if(window.performance.navigation && window.performance.navigation.redirectCount > 0){
            pb.append("Redirects: " + window.performance.navigation.redirectCount + " - ");
        }
        
        if(window.performance.timing){
            var t = window.performance.timing;
            pb.append(
                $("<a href='#' style='color: white;'>Page Load: " + (t.responseEnd - t.requestStart) + "ms</a>").click(function(e){
                    e.preventDefault();
                    if(typeof __profiler === 'function') { __profiler(); }
                })
            );
        }
        
        if(window.performance.memory){
            pb.append(" - Memory: <span id='smt_memory'>" + (window.performance.memory.totalJSHeapSize / 1000) + "kb</span>");
        }
    }
    
    function smt_save_state(){
        if($("#smt").is(":visible")){
            createCookie('smt_show','true',365);
        }else{
            createCookie('smt_show','false',365);
        }
    }
    
    function smt_restore_state(){
        if(readCookie('smt_show') == 'true'){
            $("#smt").show();
            $('body').css('margin-top', '22px'); // for bar
        }else{
            $("#smt").hide();
            $('body').css('margin-top', ''); // for bar
        }
    }
    
    smt_restore_state();
    
    // konami for smt
    var state = 0, konami = [38,38,40,40,37,39,37,39,66,65]; 
    $(window).keydown(function(e){   
        if ( e.keyCode == konami[state] ) state++;  
        else state = 0;  
        if ( state == 10 ) { 
            $("#smt").toggle();
            smt_save_state();
            smt_restore_state();
        }
    });
});

function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    
    document.cookie = name+"="+value+expires+"; path=/";
}
    
function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name,"",-1);
}

// From https://github.com/kaaes/timing
var __profiler = window.__profiler || function() {
	var order = ['navigationStart', 'redirectStart', 'redirectStart', 'redirectEnd', 'fetchStart', 'domainLookupStart', 'domainLookupEnd', 'connectStart', 'secureConnectionStart', 'connectEnd', 'requestStart', 'responseStart', 'responseEnd', 'unloadEventStart', 'unloadEventEnd', 'domLoading', 'domInteractive', 'msFirstPaint', 'domContentLoadedEventStart', 'domContentLoadedEventEnd', 'domContentLoaded', 'domComplete', 'loadEventStart', 'loadEventEnd'],
		cssReset = 'font-size:14px;line-height:1em;z-index:999;text-align:left;font-family:Helvetica,Calibri,Arial,sans-serif;text-shadow:none;box-shadow:none;display:inline-block;color:#222;font-weight:normal;border:none;margin:0;padding:0;background:none;',
		maxTime = 0,
		barHeight = 20,
		timeLabelWidth = 50,
		nameLabelWidth = 150,
		textSpace = timeLabelWidth + nameLabelWidth,
		spacing = 1.25,
		unit = 1,
		times = {};

	function getPerfObjKeys(obj) {
		var keys = Object.keys(obj);
		return keys.length ? keys : Object.keys(Object.getPrototypeOf(obj));
	}

	function setUnit(canvas) {
		return (canvas.width - textSpace) / maxTime;
	}

	function setSections() {
		return Array.prototype.indexOf ? [{
			name: 'network',
			color: [224, 84, 63],
			start: order.indexOf('navigationStart'),
			end: order.indexOf('connectEnd')
		}, {
			name: 'server',
			color: [255, 188, 0],
			start: order.indexOf('requestStart'),
			end: order.indexOf('responseEnd')
		}, {
			name: 'browser',
			color: [16, 173, 171],
			start: order.indexOf('unloadEventStart'),
			end: order.indexOf('loadEventEnd')
		}] : [];
	}

	function createContainer() {
		var container = document.createElement('div');
		document.body.appendChild(container);
		container.style.cssText = cssReset + 'width:95%;position:fixed;margin:0 auto;top:20px;left:20px;background:#FFFDF2;background:rgba(255,253,242,.95);padding:10px;box-shadow:0 0 10px 5px rgba(0,0,0,.5),0 0 0 10px rgba(0,0,0,.5); border-radius:1px';
		return container;
	}

	function createHeader(container, sections) {
		var c = document.createElement('div'),
			h = document.createElement('h1'),
			b = document.createElement('button'),
			sectionStr = '/ ';

		for(var i = 0, l = sections.length; i < l; i++) {
			sectionStr += '<span style="color:rgb('+sections[i].color.join(',')+')">'+sections[i].name+'</span> / ';
		}				

		h.innerHTML = 'Page Load Time Breakdown ' + sectionStr;
		h.style.cssText = cssReset + 'font-size:24px;margin:10px 0;width:auto';

		b.innerHTML = 'close';
		b.style.cssText = cssReset + 'float:right;background:#333;color:#fff;border-radius:10px;padding:3px 10px;font-size:12px;line-height:130%;width:auto';
		b.onclick = function(e){
			b.onclick = null;
			container.parentNode.removeChild(container);
		}; // DOM level 0 used to avoid implementing this twice for IE & the rest 

		c.appendChild(h);
		c.appendChild(b);

		return c;
	}

	function createInfoLink() {
		var a = document.createElement('a');
		a.href = 'http://kaaes.github.com/timing/info.html';
		a.target = '_blank';
		a.innerHTML = 'What does it all mean?';
		a.style.cssText = cssReset + 'color:#1D85B8';
		return a;
	}

	function notSupportedInfo() {
		var p = document.createElement('p');
		p.innerHTML = 'Navigation Timing API is not supported by your browser';
		return p;
	}

	function createChart(container, data, sections) {
		var time, blockStart, blockEnd, item, eventName, options,
			omit = [], drawFns = [], preDraw,
			fontString = "12px Arial",
			canvas, context,
			canvasCont = document.createElement('div'),
			infoLink = createInfoLink(),
			dataObj = findSectionEdges(data, sections);

		canvas = document.createElement('canvas');
		canvas.width = parseInt(window.getComputedStyle(container).width, 10) - 20;
		context = canvas.getContext('2d');
		context.font = fontString; // needs to be set here for proper text measurement...

		unit = setUnit(canvas);

		preDraw = prepareDraw.bind(this, canvas, dataObj, canvas.width);

		for (var name in dataObj) {
			item = dataObj[name];
			blockStart = name.indexOf('Start');
			blockEnd = -1;
			if (blockStart > -1) {
				eventName = name.substr(0, blockStart);
				blockEnd = order.indexOf(eventName + 'End');
			}
			if (blockStart > -1 && blockEnd > -1) {
				item.label = eventName;
				drawFns.push(preDraw('block', [eventName + 'Start', eventName + 'End', eventName], item));
				omit.push(eventName + 'End');
			}
			else if (omit.indexOf(name) < 0) {
				item.label = name;
				drawFns.push(preDraw('point', [name], item));
			}
		}

		canvas.height = spacing * barHeight * drawFns.length; 
		context.font = fontString; // setting canvas height resets font, has to be re-set

		drawFns.forEach(function(draw) {
			draw(context);
			context.translate(0, Math.round(barHeight * spacing));
		})

		canvasCont.appendChild(canvas);
		canvasCont.appendChild(infoLink);

		return canvasCont;
	}

	function findSectionEdges(dataArr, sections) {
		var data = {}, start, end, i, j, len, flen, sectionOrder, filtered;
		dataArr.forEach(function(el) {
			data[el[0]] = { time : el[1] };
		});
		for (i = 0, len = sections.length; i < len; i++) {
			start = sections[i].start;
			end = sections[i].end;

			sectionOrder = order.slice(start, end + 1);
			filtered = sectionOrder.filter(function(el){
				return data.hasOwnProperty(el);
			});
			filtered.sort(function(a, b){
				return data[a].time - data[b].time;
			})
			start = filtered[0];
			end = filtered[filtered.length-1];			

			for(j = 0, flen = filtered.length; j < flen; j++) {
				var item = filtered[j];
				if(data[item]) {					
					data[item].color = sections[i].color;
					data[item].barStart = data[start].time;
					data[item].barEnd = data[end].time;
				}
			}
		}
		return data;
	}

	function prepareDraw(canvas, data, barWidth, mode, eventName, options) {
		var opts = {
			color : options.color,
			sectionData : [options.barStart,  options.barEnd],
			eventData : eventName.map(function(el){ return data[el] && typeof data[el].time !== 'undefined' ? data[el].time : el; }),
			label : options.label
		}
		return drawBar(mode, canvas, barWidth, opts);
	}

	function drawBar(mode, canvas, barWidth, options) {
		var start, stop, width, timeLabel, metrics,		
			color = options.color,
			sectionStart = options.sectionData[0],
			sectionStop = options.sectionData[1],
			nameLabel = options.label,
			context = canvas.getContext('2d');

		if (mode === 'block') {
			start = options.eventData[0];
			stop = options.eventData[1];
			timeLabel = start + '-' + stop;
		} else {
			start = options.eventData[0];
			timeLabel = start;
		}
		timeLabel += 'ms';

		metrics = context.measureText(timeLabel);
		if(metrics.width > timeLabelWidth) {
			timeLabelWidth = metrics.width + 10;
			textSpace = timeLabelWidth + nameLabelWidth;
			unit = setUnit(canvas);
		}

		return function(context) {
			if(mode === 'block') {
				width = Math.round((stop - start) * unit);
				width = width === 0 ? 1 : width;
			} else {
				width = 1;
			}

			// row background
			context.strokeStyle = 'rgba(' + color[0] + ',' + color[1] + ',' + color[2] + ',.3)';
			context.lineWidth = 1;
			context.fillStyle = 'rgba(255,255,255,.99)';
			context.fillRect(0, 0, barWidth - textSpace, barHeight);
			context.fillStyle = 'rgba(' + color[0] + ',' + color[1] + ',' + color[2] + ',.1)';
			context.fillRect(0, 0, barWidth - textSpace, barHeight);
			context.strokeRect(.5, .5, Math.round(barWidth - textSpace -1), Math.round(barHeight));

			// section bar
			context.shadowColor = 'white';
			context.fillStyle = 'rgba(' + color[0] + ',' + color[1] + ',' + color[2] + ',.3)';
			context.fillRect(Math.round(unit * sectionStart), 0, Math.round(unit * (sectionStop - sectionStart)), barHeight);

			// event marker
			context.fillStyle = 'rgb(' + color[0] + ',' + color[1] + ',' + color[2] + ')';
			context.fillRect(Math.round(unit * start), 0, width, barHeight);

			// label
			context.fillText(timeLabel, barWidth - textSpace + 10, 2 * barHeight / 3);
			context.fillText(nameLabel, barWidth - textSpace + timeLabelWidth + 10, 2 * barHeight / 3);
		}
	}

	function getData() {
		if (!window.performance) {
			return;
		}
		var data = window.performance,
			timeData = data.timing,
			times = {},
			start = timeData.navigationStart || 0,
			events = getPerfObjKeys(timeData),
			sortable = [];
			duration = 0;

		events.forEach(function(e) {
			if (timeData[e] && timeData[e] > 0) {
				duration = timeData[e] - start;
				sortable.push([e, duration]);
				if (duration > maxTime) {
					maxTime = duration;
				}
			}
		});

		sortable.sort(function(a, b) {
			return a[1] !== b[1] ? a[1] - b[1] : order.indexOf(a[0]) - order.indexOf(b[0]);
		});

		sortable.forEach(function(el) {
			times[el[0]] = el[1];
		});

		return sortable;
	}

	(function show() {
		var container = createContainer(),
			data = getData(),
			sections = setSections();			
		container.appendChild(createHeader(container, sections));		
		container.appendChild(data && sections.length ? createChart(container, data, sections) : notSupportedInfo());
	})();
};
//if(typeof __profiler === 'function') { __profiler(); }
__profiler.scriptLoaded = true;

</script>
EOT;
        
        return $html;
        //return '<pre>' . Tracker::rawOut() . '</pre>';
    }

    static function _trackable(){
        if(!isset($_SERVER['REQUEST_URI'])) return false; // cli helper
        
        return (isset($_SESSION['user']) && isset($_SESSION['superuser']));
    }

}


class SQLHighlighter {
  /*
    protected $colors - key order is important because of highlighting < and >
    chars and not encoding them to &lt; and &gt;
  */
  protected $colors = Array('chars' => 'grey', 'keywords' => 'green; font-weight: bold', 'joins' => 'gray', 'functions' => 'violet', 'constants' => 'red');
  /*
    lists are not complete.
  */
  protected $words = Array (
  'keywords' =>
    array('SELECT', 'UPDATE', 'INSERT', 'DELETE', 'REPLACE', 'INTO', 'CREATE', 'ALTER', 'TABLE', 'DROP', 'TRUNCATE', 'FROM',
    'ADD', 'CHANGE', 'COLUMN', 'KEY',
    'WHERE', 'ON', 'CASE', 'WHEN', 'THEN', 'END', 'ELSE', 'AS', 
    'USING', 'USE', 'INDEX', 'CONSTRAINT', 'REFERENCES', 'DUPLICATE',
    'LIMIT', 'OFFSET', 'SET', 'SHOW', 'STATUS', 
    'BETWEEN', 'AND', 'IS', 'NOT', 'OR', 'XOR', 'INTERVAL', 'TOP',
    'GROUP BY', 'ORDER BY', 'DESC', 'ASC', 'COLLATE', 'NAMES', 'UTF8', 'DISTINCT', 'DATABASE',
    'CALC_FOUND_ROWS', 'SQL_NO_CACHE', 'MATCH', 'AGAINST', 'LIKE', 'REGEXP', 'RLIKE',
    'PRIMARY', 'AUTO_INCREMENT', 'DEFAULT', 'IDENTITY', 'VALUES', 'PROCEDURE', 'FUNCTION', 
        'TRAN', 'TRANSACTION', 'COMMIT', 'ROLLBACK', 'SAVEPOINT', 'TRIGGER', 'CASCADE',
        'DECLARE', 'CURSOR', 'FOR', 'DEALLOCATE'
    ),
  'joins' => array('JOIN', 'INNER', 'OUTER', 'FULL', 'NATURAL', 'LEFT', 'RIGHT'),
  'chars' => '/([\\.,\\(\\)<>:=`]+)/i',
  'functions' => array(
    'MIN', 'MAX', 'SUM', 'COUNT', 'AVG', 'CAST', 'COALESCE', 'CHAR_LENGTH', 'LENGTH', 'SUBSTRING',
    'DAY', 'MONTH', 'YEAR', 'DATE_FORMAT', 'CRC32', 'CURDATE', 'SYSDATE', 'NOW', 'GETDATE',
    'FROM_UNIXTIME', 'FROM_DAYS', 'TO_DAYS', 'HOUR', 'IFNULL', 'ISNULL', 'NVL', 'NVL2',
    'INET_ATON', 'INET_NTOA', 'INSTR', 'FOUND_ROWS',
    'LAST_INSERT_ID', 'LCASE', 'LOWER', 'UCASE', 'UPPER',
    'LPAD','RPAD','RTRIM','LTRIM',
    'MD5','MINUTE', 'ROUND',
    'SECOND', 'SHA1', 'STDDEV', 'STR_TO_DATE', 'WEEK'),
   'constants' => '/(\'[^\']*\'|[0-9]+)/i'
  );

  /* 
    $colors must be blank or 
    Array('chars' => '', 'keywords' => '', 'joins' => '', 'functions' => '', 'constants' => '')
  */
  function __construct($colors = 0) {
    if ($colors) $this->colors = $colors;
  }

  public function highlight($sql)
  {
    $sql = str_replace('\\\'', '\\&#039;', $sql);
    foreach($this->colors as $key=>$color)
    {
      if (in_array($key, Array('constants', 'chars'))) {
        $regexp = $this->words[$key];
      }
      else {
        $regexp = '/\\b(' . join("|", $this->words[$key]) . ')\\b/i';
      }
      $sql = preg_replace($regexp, '<span style="color:'.$color."\">$1</span>", $sql);
    }
    return $sql;
  }
}