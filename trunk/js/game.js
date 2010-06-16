function createXmlHttpReqObj() {
	var xmlHttp=new Object();

	if (window.XMLHttpRequest) {
		try {xmlHttp = new XMLHttpRequest();}
		catch(e){xmlHttp = false;}
	}
	else if (window.ActiveXObject) {
		try {xmlHttp = new ActiveXObject("MSXML2.XMLHTTP");}
		catch (e)
		{
			try {xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');}
			catch (e){xmlHttp = false;}
		}
	}

	if(!xmlHttp )
		alert('error creating xml obj!');

	return xmlHttp;
}

var xhttp;
var mode='';
var step;
var timer1, timer2;

function init() {
    xhttp=createXmlHttpReqObj();
    step=20;
    if(xhttp) {
	sendi("init","init");
    }
}

function set_prop(_obj, _name, _value) {
    switch(_name) {
        case 'pid':
            _obj.setAttribute('id', _value);
            break;
        case 'px':
            _obj.style.left=_value;
            break;
        case 'py':
            _obj.style.top=_value;
            break;
        case 'ps':
            _obj.style.width=_value;
            _obj.style.height=_value;
            break;
        case 'pw':
            _obj.style.width=_value;
            break;
        case 'ph':
            _obj.style.height=_value;
            break;
        case 'pb':
            _obj.style.border='2px '+_value+' dotted';
            break;
        case 'ppic':
            _obj.style.backgroundImage = "url('"+_value+"')";
            break;
        case 'pd':
            _obj.lastChild.innerHTML=_value;
            break;
        default:
            _obj.innerHTML=_value;
            break;
    }
}

function parse_mess(_cmd, _param, _data) {
    // alert("parsing. _cmd:"+_cmd+", param:"+_param+", data:"+_data);
    switch(_cmd) {
        case 'setvalue':
            _id=document.getElementById(_param);
            if(_id!=undefined) {
                //alert("setting value"+_param+" to "+_data);
                _id.value=_data;
            } else {
                alert("setval - '"+_param+"' not found");
            }
            break;
        case 'mode':
            switch(_param) {
                case 'set':
                   if(_data!="") {
                       mode=_data;
                   }
                default:
                    break;
            }
            break;
        case 'mess':
            switch(_param) {
                default:
                        alert(_data);
                    break;
            }
            break;
        case 'aas': //and and scroll
            _id=document.getElementById(_param);
            if(_id!=undefined) {
                _id.innerHTML=_id.innerHTML+_data;
                _id.scrollTop=_id.scrollHeight;
            } else {
                alert("addandscrool - '"+_param+"' not found");
            }
            break;
        case 'add':
            
            _id=document.getElementById(_param);
            if(_id!=undefined) {
                _id.innerHTML=_id.innerHTML+_data;
            } else {
                alert("add - '"+_param+"' not found");
            }
            break;
        case 'replace':
            alert("replacing "+_param+" by "+_data);
            _id=document.getElementById(_param);
            if(_id!=undefined) {
				//alert("replacing: "+_data);
                _id.innerHTML=_data;
            } else {
                alert("replace - '"+_param+"' not found");
            }
            break;
        case 'addobj':
            _id=document.getElementById(_param);
            if(_id!=undefined) {

                var n = document.createElement('div');
                var nd = document.createElement('div');
                var ndd = document.createElement('div');

                for(var j=0; j<_data.length; j++) {
                    switch(_data[j].pname) {
                        case 'pselect':
                            if(nd.id!=undefined) {
                                switch(_data[j].pvalue) {
                                    case 'object':
                                        nd.setAttribute('onmousedown', "handleSelectObj(event,'"+nd.id+"')");
                                        break;
                                    case 'coord':
                                    default:
                                        nd.setAttribute('onmousedown', "handleMouseDown(event,'"+nd.id+"')");
                                        break;
                                }
                                
                            }
                            break;
                        case 'pd':
                            ndd.innerHTML=_data[j].pvalue;
                            //set_prop(ndd, "ppdesc", _data[j].pvalue);
                            break;
                        case 'pid':
                            set_prop(ndd, _data[j].pname, _data[j].pvalue+"_desc");
                         //   set_prop(ndd, _data[j].pname, "desc");
                            nd.setAttribute('onmouseover', "handleMouseOver(event,'"+_data[j].pvalue+"')");
                            nd.setAttribute('onmouseout', "handleMouseOut(event,'"+_data[j].pvalue+"')");
                        default:
                            set_prop(nd, _data[j].pname, _data[j].pvalue);
                            break;
                    }
                }
                
                n.style.position='relative';
                nd.style.position='absolute';
                ndd.style.position='relative';

                ndd.style.top=0;//parseInt(nd.style.height)/2;
                ndd.style.left=parseInt(nd.style.width)/2;
                ndd.style.width=150;
                ndd.style.height='auto';
              
                ndd.style.visibility='hidden';
                ndd.style.opacity=0.8;
                ndd.style.zIndex=10;

                ndd.style.Color='#00ff00';
                ndd.style.backgroundColor='#008800';
                ndd.style.border='1px dotted #00aa00';

                nd.appendChild(ndd);
                n.appendChild(nd);
                _id.appendChild(n);
              //  alert(_id.innerHTML);

            } else {
                alert("addobj - '"+_param+"' not found");
            }

            break;
        case 'setpp':
            _id=document.getElementById(_param);
            if(_id!=undefined && _data!=undefined ) {
                for(var j=0; j<_data.length; j++) {
                    set_prop(_id, _data[j].pname, _data[j].pvalue);
                }
            }
            break;
        default:
            alert("unknown command message. cmd:"+_cmd+", param:"+_param+", data:"+_data);
            break;
    }
}

function sendm(_val) {
    if(_val!="") {
        mode=_val;
        sendi('init','');
    }
}

function sendp(_val) {
    sendi('set',_val);
}

function sendv(_cmd,_source_id) {
    _id=document.getElementById(_source_id);
    if(_id!=undefined) {
        _val=_id.value;
        if(_val != "" && _val != undefined) {
            sendi(_cmd, _val);
        } else {
            alert("sendv:'"+_val+"' not found");
        }
    } else {
        alert("sendv:'"+_id+"' not found");
    }
}

function sendw(_cmd,_source_ids) {
    var _ids=_source_ids.split(";");
    var vals=new Array();
    for(var i=0; i<_ids.length; i++) {
        if(_ids[i]!=null) {
            _id=document.getElementById(_ids[i]);
            if(_id!=undefined) {
                _val=_id.value;
                if(_val != "" && _val != null) {
                    vals.push(_val);
                } else {
                    alert("object '"+ids[i]+"' not found or has fail value'");
                }
            }
        }
    }
    if(vals.length>0) {
        sendi(_cmd, vals.join(';')+";");
    }
}

//<mess>
//    <cmd></cmd>
//    <param></param>
//    <data [type=multi]></data>
//</mess>

function recProcedure() {
    var _param, _data, _cmd, _pro, _comment;
    if(xhttp.readyState == 4 && xhttp.status == 200) {
       // alert("REC:"+xhttp.responseText);
        var _xmlDoc = xhttp.responseXML.documentElement;
        var mess_arr = _xmlDoc.getElementsByTagName("m");
        for(var i=0; i<mess_arr.length; i++) {
            _cmd = mess_arr[i].childNodes[0].childNodes[0].nodeValue;
            _param = mess_arr[i].childNodes[1].childNodes[0].nodeValue;
            attr_obj=mess_arr[i].childNodes[2].attributes['type'];
            if( attr_obj != undefined) {
                switch(attr_obj.value) {
                  case 'multi':
                     var prop_array=mess_arr[i].childNodes[2].getElementsByTagName("pp");
                    _data=new Array();
                     for(var j=0; j<prop_array.length; j++) {
                         attr_pname=prop_array[j].attributes['pn'];
                          if(attr_pname != undefined ) {
                             var _prop_obj={pname: attr_pname.value,
                                         pvalue: prop_array[j].childNodes[0].nodeValue};
                             _data.push( _prop_obj );
                          }
                     }
                    break;
                  default:
                    alert('not defined type attribute... stringed!');
                     _data = mess_arr[i].childNodes[2].childNodes[0].nodeValue;
                    break;
                }
            } else {
                
                _data = mess_arr[i].childNodes[2].childNodes[0].nodeValue;
                alert("CMD: "+ _cmd+", PARAM: "+_param+ ", DATA: "+_data);
            }
            parse_mess(_cmd,_param,_data);
        }
        if(mess_arr.length<1) {
            alert("REC:"+xhttp.responseText);
        }
    }
}

function sendi(cmd,value) {
	var sendMessMetaData;
	if( xhttp.readyState == 4 || xhttp.readyState == 0 ) {
		sendMessMetaData = "game.php?mode="+mode+"&cmd="+cmd+"&val="+value;
          //      alert(sendMessMetaData);
                xhttp.open("GET", sendMessMetaData, true);
		xhttp.onreadystatechange = recProcedure;
		xhttp.send(null);
     //           clearTimeout(timer1);
      //          timer2=setTimeout("sendi('refresh','')", 60000);
	}
	else {
		clearTimeout(timer1);
		timer1=setTimeout("sendi(cmd,value)", 10000);
	}
}

function getOffsetSum(elem) {
    var top=0, left=0;
    while(elem) {
        top = top + parseInt(elem.offsetTop);
        left = left + parseInt(elem.offsetLeft);
        elem = elem.offsetParent;
    }
    return {top: top, left: left}
}



function handleSelectObj(_e, _name) {
    var elem = document.getElementById(_name);
    if(elem!=undefined) {
        sendi('select',_name);

    } else alert(_name+' is not defined!');
//    return false;
}

function handleMouseDown(_e, _name) {
    var elem = document.getElementById(_name);
    alert('!');
    if(elem!=undefined) {
        var _divOffset=getOffsetSum(elem);
        if (_e.clientX || _e.clientY) {
            _x =  _e.clientX+document.body.scrollLeft+document.documentElement.scrollLeft-_divOffset.left - 1;
            _y =  _e.clientY+document.body.scrollTop+document.documentElement.scrollTop-_divOffset.top - 1;
            alert('!:'+_x+','+_y);
            sendi('down',_x+";"+_y);
        }
    } else alert(_name+' is not defined!');
//    return false;
}

function handleMouseOver(_e,_name) {
    var elem = document.getElementById(_name+'_desc');
    if(elem!=undefined) {
        elem.style.visibility='visible';
        elem.style.display="block";
    } else alert(_name+' is not defined!');
}

function handleMouseOut(_e,_name) {
    var elem = document.getElementById(_name+'_desc');
    if(elem!=undefined) {
        elem.style.visibility='hidden';
    } else alert(_name+' is not defined!');
}

function showHide(_name) {
	var elem = document.getElementById(_name);

    if(elem!=undefined) {
		if(elem.style.visibility=='visible') {
			elem.style.visibility='hidden';
		} else {
			elem.style.visibility='visible';
		}
    } else alert(_name+' is not defined!');
}