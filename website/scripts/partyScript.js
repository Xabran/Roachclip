    function openPartyDetails(event, partyID)
    {
        //populate details first
        var target = "/ajaxsrc/getpartyinfo.php?ID=" + partyID;
        HttpRequest(target,"detailsPopup");

        //visual setup
        //var coords = findPos(document.getElementById("menu_parties"));
        //var x = coords[0];
        //var y = coords[1];event.clientX
        var x = event.clientX;
        var y = event.clientY;
        document.getElementById("detailsPopup").style.pixelLeft = x-500;
        document.getElementById("detailsPopup").style.pixelTop = y-25;
        document.getElementById("detailsPopup").style.left = (x-500) + "px";
        document.getElementById("detailsPopup").style.top = (y-25) + "px";
        document.getElementById("detailsPopup").style.visibility = "visible";
    }
/*
    function findPos(obj) {
            var curleft = 0;
            var curtop = 0;
            if (obj.offsetParent) {
                    do {
                            curleft += obj.offsetLeft;
                            curtop += obj.offsetTop;
                    } while (obj = obj.offsetParent);
            }
            return [curleft,curtop];
    }

    function point_it(event, source){
	pos_x = event.offsetX?(event.offsetX):event.pageX-source.offsetLeft;
	pos_y = event.offsetY?(event.offsetY):event.pageY-source.offsetTop;
	document.getElementById("cross").style.left = (pos_x-1) ;
	document.getElementById("cross").style.top = (pos_y-15) ;
	document.getElementById("cross").style.visibility = "visible" ;
	document.pointform.form_x.value = pos_x;
	document.pointform.form_y.value = pos_y;
    }

//var isIE = document.all?true:false;
//document.onmousemove = getMousePosition;
function getMousePosition(e) {
  var _x;
  var _y;
  if (!isIE) {
    _x = e.pageX;
    _y = e.pageY;
  }
  if (isIE) {
    _x = event.clientX + document.body.scrollLeft;
    _y = event.clientY + document.body.scrollTop;
  }

posX=_x;
posY=_y;

  return true;
}*/