//去掉空格
function trim(str){   
    str = str.replace(/^(\s|\u00A0)+/,'');   
    for(var i=str.length-1; i>=0; i--){   
        if(/\S/.test(str.charAt(i))){   
            str = str.substring(0, i+1);   
            break;   
        }   
    }   
    return str;   
}

function showTime(tuanid, time_distance) { 
	this.tuanid = tuanid; 
	//PHP时间是秒，JS时间是微秒 
	this.time_distance = time_distance * 1000; 
	
	this.desc = "抢购结束";
	
	this.preg = "{a}天{b}小时{c}分钟{d}秒";
	
	this.setid = "lefttime_";
} 

showTime.prototype.setTimeShow = function () {
	var timer = document.getElementById(this.setid+this.tuanid);
	if(timer==null || typeof(timer)=="undefined"){ /*alert("找不到设置时间节点！是否将页面代码放在页脚？"); */return false;}
	var str_time; 
	var int_day, int_hour, int_minute, int_second; 
	time_distance = this.time_distance; 
	this.time_distance = this.time_distance - 1000; 
	if (time_distance > 0) { 
		int_day = Math.floor(time_distance / 86400000); 
		time_distance -= int_day * 86400000; 
		int_hour = Math.floor(time_distance / 3600000); 
		time_distance -= int_hour * 3600000; 
		int_minute = Math.floor(time_distance / 60000); 
		time_distance -= int_minute * 60000; 
		int_second = Math.floor(time_distance / 1000); 
		if (int_hour < 10) 
		int_hour = "0" + int_hour; 
		if (int_minute < 10) 
		int_minute = "0" + int_minute; 
		if (int_second < 10) 
		int_second = "0" + int_second; 
		str_time = this.preg.replace('{a}',int_day);
		str_time = str_time.replace('{b}',int_hour);
		str_time = str_time.replace('{c}',int_minute);
		str_time = str_time.replace('{d}',int_second);
		timer.innerHTML = str_time; 
		var self = this; 
		ll = setTimeout(function () { self.setTimeShow(); }, 1000); 
	} else { 
		timer.innerHTML = this.desc;
		if(typeof(ll)!="undefined") clearTimeout(ll);
		return; 
	} 
}
/*
*倒计时
EG:
var st = new showTime(1,3); 
//st.tuanid = 1; //节点id
//st.time_distance = 10000; //毫秒
st.setTimeShow(); 
*/