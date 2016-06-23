        var tips; var theTop = 80; var old = theTop;
        function initFloatTips() {
			oa = document.getElementById("floatTips");
			if(oa==null || typeof(oa)=='undefined'){
				 return false;
			}
			document.getElementById("floatTips").style.display = "block"; 
            tips = document.getElementById('floatTips');
            moveTips();
        };
        function moveTips() {
            var tt = 50;
            if (window.innerHeight) {
                pos = window.pageYOffset
            }
            else if (document.documentElement && document.documentElement.scrollTop) {
                pos = document.documentElement.scrollTop
            }
            else if (document.body) {
                pos = document.body.scrollTop;
            }
            pos = pos - tips.offsetTop + theTop;
            pos = tips.offsetTop + pos / 10;
            if (pos < theTop) pos = theTop;
            if (pos != old) {
                tips.style.top = pos + "px";
                tt = 10;
            }
            old = pos;
            setTimeout(moveTips, tt);
        }

        /*function $(objID) {
            return document.getElementById(objID);
        }*/
		
        function hidechat(){
                document.getElementById("floatTips").style.display = "none";
        }
		
        window.onload = initFloatTips;
		
		// <![CDATA[
		function bookmark(){
			var title=document.title
			var url=document.location.href
			if (window.sidebar) window.sidebar.addPanel(title, url,"");
			else if( window.opera && window.print ){
			var mbm = document.createElement('a');
			mbm.setAttribute('rel','sidebar');
			mbm.setAttribute('href',url);
			mbm.setAttribute('title',title);
			mbm.click();}
			//else if( document.all ) window.external.AddFavorite( url, title);
		}
		// ]]>