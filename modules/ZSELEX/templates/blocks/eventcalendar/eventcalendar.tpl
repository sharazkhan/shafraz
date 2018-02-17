

   
        <style type="text/css">
            /*margin and padding on body element
              can introduce errors in determining
              element position and are not recommended;
              we turn them off as a foundation for YUI
              CSS treatments. */
           
        </style>
        
       {* <link rel="stylesheet" type="text/css" href="modules/ZSELEX/javascript/yahoocalendar/fonts-min.css" /> *}
        <link rel="stylesheet" type="text/css" href="modules/ZSELEX/javascript/yahoocalendar/calendar.css" />
        <script type="text/javascript" src="modules/ZSELEX/javascript/yahoocalendar/yahoo-dom-event.js"></script>
        <script type="text/javascript" src="modules/ZSELEX/javascript/yahoocalendar/calendar-min.js"></script>


        <!--begin custom header content for this example-->
        <style type="text/css">
            #cal1Container {
                margin:1em;
            }

            #caleventlog {
                float:left;
                width:35em;
                margin:1em;
                background-color:#eee;
                border:1px solid #000;
            }
            #caleventlog .bd {
                overflow:auto;
                height:20em;
                padding:5px;
            }
            #caleventlog .hd {
                background-color:#aaa;
                border-bottom:1px solid #000;
                font-weight:bold;
                padding:2px;
            }
            #caleventlog .entry {
                margin:0;	
            }
            
        </style>

        <!--end custom header content for this example-->

  

    <div class="yui-skin-sam">

        <!--BEGIN SOURCE CODE FOR EXAMPLE =============================== -->

        <script type="text/javascript">
            YAHOO.namespace("example.calendar");

            YAHOO.example.calendar.init = function() {
                var eLog = YAHOO.util.Dom.get("evtentries");
                var eCount = 1;

                function logEvent(msg) {
                    eLog.innerHTML = '<pre class="entry"><strong>' + eCount + ').</strong> ' + msg + '</pre>' + eLog.innerHTML;
                    eCount++;
                }

                function dateToLocaleString(dt, cal) {
                    var wStr = cal.cfg.getProperty("WEEKDAYS_LONG")[dt.getDay()];
                    var dStr = dt.getDate();
                    var mStr = cal.cfg.getProperty("MONTHS_LONG")[dt.getMonth()];
                    var yStr = dt.getFullYear();
                    return (wStr + ", " + dStr + " " + mStr + " " + yStr);
                }

                function mySelectHandler(type,args,obj) {
                   
                    var selected = args[0];
                    var baseurl = ''; 
                       
                    var selDate = this.toDate(selected[0]);
                    var d= String(selected);
                    var n=d.replace(",","-");
                    var m=n.replace(",","-");
                    var eventdate = m;
                    document.getElementById('eventdate').value = m;
                  //  getEvents(); 
                    //alert(m);
                    baseurl = document.location.pnbaseURL;
                    //alert(baseurl); exit();
                    var url = "eventperdate/date/" + eventdate;
                    window.open(url, '_blank');
			 
                    //logEvent("SELECTED: " + dateToLocaleString(selDate, this));
                };

                function myDeselectHandler(type, args, obj) {
                    var deselected = args[0];
                    var deselDate = this.toDate(deselected[0]);

                    logEvent("DESELECTED: " + dateToLocaleString(deselDate, this));
                };

                YAHOO.example.calendar.cal1 = new YAHOO.widget.Calendar("cal1","cal1Container");

                YAHOO.example.calendar.cal1.selectEvent.subscribe(mySelectHandler, YAHOO.example.calendar.cal1, true);
                YAHOO.example.calendar.cal1.deselectEvent.subscribe(myDeselectHandler, YAHOO.example.calendar.cal1, true);

                YAHOO.example.calendar.cal1.render();
            }

            YAHOO.util.Event.onDOMReady(YAHOO.example.calendar.init);
           
        </script>

        <div id="cal1Container"></div>
       
        <div style="clear:both"></div>

        <!--END SOURCE CODE FOR EXAMPLE =============================== -->


        <!--MyBlogLog instrumentation-->
        <script type="text/javascript" src="http://track2.mybloglog.com/js/jsserv.php?mblID=2007020704011645"></script>

    </div>


