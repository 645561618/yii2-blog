/*3.9*/
var MSIE=(navigator.appName=="Microsoft Internet Explorer");var NOTHING=eval("undefined");var MN="images/mn/";var CLRBL="#003366";var CLRWT="#ffffff";
function SimpleHtmlTree(trPnlId,mnStl,icnStl,chk){
	var ROOT=new Object();var that=this;var crrt=null;if(isEmpty(mnStl)){mnStl="images/mn";}var useIcn=(isEmpty(icnStl))?false:true;var useChk=(isEmpty(chk))?false:true;var BLNK,BR,FO,FC,FE,FL,L;BLNK=mnStl+"blnk.gif";BR=mnStl+"br.gif";if(useIcn){FO=icnStl+"fo.gif";FC=icnStl+"fc.gif";FE=icnStl+"fe.gif";FL=icnStl+"fl.gif";L=icnStl+"l.gif";}
	trPnl=$(trPnlId);var mbd;if(!trPnl){err("invalid treePanel");}else{mbd=crtDiv();trPnl.appendChild(mbd);trPnl.style.fontSize="12px";}
	this.add=function(nd,sel){
		if(isEmpty(nd)){err("invalid tree node.");}
		if(!useIcn){unDisplay(nd.getIcn().style);}
		if(!mbd.hasChildNodes()){bldM(nd,"headSingle");}else{
			var mbdC=mbd.childNodes;
			var mbdCN=mbdC.length;
			if(mbdCN==1){bldM(mbdC[0],"headHasNext");}else{bldM(mbdC[mbdCN-1],"middle");}
			bldM(nd,"last");
		}
		mbd.appendChild(nd);
		var prev=nd.getPrev();
		var hasPrev=(prev!=null)?true:false;
		if(hasPrev){bldCnL(prev,true);}
		if(sel){that.sel(nd);}
	}
	this.sel=function(nd){
		if(nd!=crrt){if(nd!=null){hlt(nd);}if(crrt!=null){unHlt(crrt);}crrt=nd;}
		if(crrt!=null){crrt.expand();crrt.lnkClick();}
	}
	this.remove=function(nd,selPn){
		if(isEmpty(nd)){err("invalid node.");}alert
		var lv=nd.level;
		if(lv!=0){
			if(!nd.getNext()){
				var prev=nd.getPrev();
				if(prev){
					var m=prev.m;
					if(m=="tp"){shwM(prev,"cp");}
					else if(m=="tm"){shwM(prev,"cm");}
					else if(m=="t"){shwM(prev,"c");}
					bldCnL(prev,false);
				}else{
					var pn=nd.getPn();
					var m=pn.m;
					if(m=="tm" || m=="tp"){shwM(pn,"t");}/*变化有规律*/
					else if(m=="cm" || m=="cp"){shwM(pn,"c");}
					else if(m=="hcm" || m=="hcp"){shwM(pn,"hc");}
					else if(m=="hm" || m=="hp"){shwM(pn,"blnk");}
					shwI(pn,"FE");
					pn.hsChld=false;
				}
			}
			if(selPn){that.sel(nd.getPn());}
			nd.parentNode.removeChild(nd);
		}else{
			var cnt=mbd.childNodes.length;
			if(cnt==2){
				var prv=nd.getPrev();
				var m,tnd;
				tnd=(prv!=null)?prv:nd.getNext();
				m=tnd.m;
				if(m=="hcm"||m=="cm"){shwM(tnd,"hm");}
				else if(m=="hcp"||m=="cp"){shwM(tnd,"hp");}
				else if(m=="hc"||m=="c"){shwM(tnd,"blnk");}
				if(prv){bldCnL(prv,false);}
			}else if(cnt>2){
				var prv=nd.getPrev();
				var nxt=nd.getNext();
				var m;
				if(!prv){
					m=nxt.m;
					if(m=="tm"){shwM(nxt,"hcm");}
					else if(m=="tp"){shwM(nxt,"hcp");}
					else if(m=="t"){shwM(nxt,"hc");}
				}else if(!nxt){
					m=prv.m;
					if(m=="tm"){shwM(prv,"cm");}
					else if(m=="tp"){shwM(prv,"cp");}
					else if(m=="t"){shwM(prv,"c");}
					bldCnL(prv,false);
				}
			}
			mbd.removeChild(nd);
			crrt=null;
		}
	}
	this.getCurrentNode=function(){return crrt;}
	var bldM=function(nd,t){
		var m;
		if(t=="middle"){
			if(nd.hsChld){
				if(nd.fld){
					shwM(nd,"tm");
				}else{shwM(nd,"tp");}
			}else{shwM(nd,"t");}
		}else if(t=="last"){
			if(nd.hsChld){
				if(nd.fld){
					shwM(nd,"cm");
				}else{shwM(nd,"cp");}
			}else{shwM(nd,"c");}
		}else if(t=="headHasNext"){
			if(nd.hsChld){
				if(nd.fld){
					shwM(nd,"hcm");
				}else{shwM(nd,"hcp");}
			}else{shwM(nd,"hc");}
		}else{/*headSingle*/
			if(nd.hsChld){
				if(nd.fld){
					shwM(nd,"hm");
				}else{shwM(nd,"hp");}
			}else{shwM(nd,"blnk");}		
		}
	}
	var shwM=function(nd,m){nd.m=m;nd.getMn().src=mnStl+"/"+m+".gif"}
	var shwI=function(nd,i){nd.icn=i;nd.getIcn().src=eval(i);}
	var bldCnL=function(nd,f){var icn=BR;if(!f){icn=BLNK;}var l=eval(nd.level);drawL(nd,l);function drawL(nd,level){var cs=nd.getChild();for(var i=0;i<cs.length;i++){cs[i].getElm(level).src=icn;drawL(cs[i],level);}}}
	this.getIcnBase=function(){return icnStl;}
	this.getMnBase=function(){return mnStl;}
	this.getNodes=function(){return mbd.childNodes;}
	this.getNodesCount=function(){return this.getNodes().length;}
	this.createTreeNode=function(id,text,type,url,target,icn,clkHdl,ldHdl){
		if(isEmpty(id)){err("invalid parameter id.");}if(isEmpty(text)){err("invalid parameter text.");}if(isEmpty(type)){err("invalid parameter type.");}
		var pnl=crtDiv();
		var spcPnl=crtSpn();
		var mn=crtImg();
		var sdPnl=crtSpn();
		var chkBx=crtImg();
		var icn=crtImg();
		var lnk=crtAnchr(text);
		pnl.style.whiteSpace="nowrap";
		lnkstl=lnk.style;
		lnkstl.fontSize="12px";
		lnkstl.verticalAlign="top";
		lnkstl.textDecoration="none";
		lnkstl.color=CLRBL;
		lnkstl.backgroundcolr=CLRWT;
		lnkstl.height="0px";lnkstl.paddingLeft="2px";lnkstl.paddingRight="2px";lnkstl.paddingTop="2px";lnkstl.paddingBottom="0px";
		if(MSIE){lnkstl.verticalAlign="top";}else{lnkstl.verticalAlign="bottom";}
		var chldP=crtDiv();
		pnl.appendChild(spcPnl);pnl.appendChild(mn);pnl.appendChild(sdPnl);sdPnl.appendChild(chkBx);sdPnl.appendChild(icn);sdPnl.appendChild(lnk);pnl.appendChild(chldP);
		/*param*/
		pnl.id=id;
		if(MSIE){lnk.innerText=text;}else{lnk.textContent=text;}
		if(type.toUpperCase()!="D"){type="L"}else{type="D"}
		pnl.type=type;
		if(!isEmpty(url)){pnl.url=url;}
		if(!isEmpty(target)){pnl.target=target;}
		if(!isEmpty(icn)){pnl.icn=icn;}
		pnl.level="0";pnl.hsChld=false;pnl.m="";pnl.fld=false;pnl.icn="";
		var isLd=(typeof(ldHdl)=="function")?true:false;pnl.ld=isLd;
		if(isLd){pnl.load=function(){ldHdl(pnl);};pnl.hsChld=true;}pnl.chk=false;
		/**/
		/*inner function*/
		pnl.getSpcPnl=function(){return spcPnl;}
		pnl.getMn=function(){return mn;}
		pnl.getSdPnl=function(){return sdPnl;}
		pnl.getElm=function(i){return spcPnl.childNodes[i];}
		pnl.getId=function(){return pnl.id;}
		pnl.getChkBx=function(){return chkBx;}
		pnl.getIcn=function(){return icn;}
		pnl.getLnk=function(){return lnk;}
		pnl.getText=function(){var txt;var lnk=pnl.getLnk();if(MSIE){txt=lnk.innerText;}else{txt=lnk.textContent;}return txt;}
		pnl.setText=function(txt){var txt;var lnk=pnl.getLnk();if(MSIE){lnk.innerText=txt;}else{lnk.textContent=txt;}}
		pnl.getNext=function(){return pnl.nextSibling;};
		pnl.getPrev=function(){return pnl.previousSibling;};
		pnl.getM=function(){return pnl.m;}
		pnl.getChldP=function(){return chldP;}
		pnl.getChild=function(){return chldP.childNodes;}
		pnl.getCount=function(){return chldP.childNodes.length;}
		pnl.getPn=function(){return pnl.parentNode.parentNode;}
		pnl.getTarget=function(){return pnl.target;}
		pnl.getUrl=function(){return pnl.url;}
		pnl.shwIcn=function(icn){pnl.icn=icn;pnl.getIcn().src=eval(icn);}
		pnl.getLevel=function(){return pnl.level;}
		pnl.fold=function(flag){
			if(!pnl.hsChld){return;}
			var m=pnl.getM();
			if(flag){/*需展开*/
				if(!pnl.fld){
					if(!pnl.ld || pnl.getChldP().hasChildNodes()){
						if(m=="tp"){shwM(pnl,"tm");}
						else if(m=="cp"){shwM(pnl,"cm");}
						else if(m=="hcp"){shwM(pnl,"hcm");}
						else if(m=="hp"){shwM(pnl,"hm");}
						shwI(pnl,"FO");
						display(pnl.getChldP().style);
						pnl.fld=true;
					}else{
						if(pnl.icn=="FL"){return;}
						shwI(pnl,"FL");
						pnl.load();
						/* if childnodes count is zero,will be a problem  if(!pnl.getChldP().hasChildNodes()){
							pnl.hsChld=false;pnl.fld=false;
							var m=pnl.m;
							if(m=="tp"){shwM(pnl,"t");}
							else if(m=="cp"){shwM(pnl,"c");}
							else if(m=="hcp"){shwM(pnl,"hc");}
							shwI(pnl,"FE");
						}*/
					}
				}
			}else{
				if(pnl.fld){//alert(pnl.fld);
					if(m=="tm"){shwM(pnl,"tp");}
					else if(m=="cm"){shwM(pnl,"cp");}
					else if(m=="hcm"){shwM(pnl,"hcp");}
					else if(m=="hm"){shwM(pnl,"hp");}
					shwI(pnl,"FC");
					unDisplay(pnl.getChldP().style);
					pnl.fld=false;
				}
			}
			//pnl.onAfterFold(flag);
		}
		pnl.onAfterFold=function(flag){alert(1);}
		var updtL=function(nd,pn){/*build level and spaces*/
			var tmpP=pn;
			var pLevel=eval(tmpP.level);
			var dLevel=pLevel+1;
			var spcs=new Array();var spc;
			spcs[0]=(tmpP.getNext())?BR:BLNK;
			for(var i=1;i<=pLevel;i++){
				tmpP=tmpP.getPn();
				if(tmpP.getNext()){spc=BR;}else{spc=BLNK;}
				spcs[i]=spc;
			}
			function updateLevel(tn){
				var spcPnl=tn.getSpcPnl();
				tn.level=eval(tn.level)+dLevel;
				for(var j=0;j<=pLevel;j++){var spcEm=crtImg();spcEm.src=spcs[j];spcPnl.insertBefore(spcEm,spcPnl.firstChild);}
			}
			function updateLevelEach(tn){
				updateLevel(tn);
				var childs=tn.getChild();
				if(childs.length==0) return;
				for(var i=0;i<childs.length;i++){
					updateLevelEach(childs[i]);
				}
			}
			updateLevelEach(nd);
		}
		pnl.add=function(nd,sel){
			var hsChld=(pnl.getChldP().hasChildNodes())?true:false;
			pnl.getChldP().appendChild(nd);
			updtL(nd,pnl);
			bldM(nd,"last");
			if(!hsChld){
				if(!pnl.ld){
					if(!pnl.hsChld){
						pnl.hsChld=true;
						pnl.fld=true;
						var m=pnl.getM();
						if(m!=""){
							/*change from nochild to haschild minus*/
							if(m=="t"){shwM(pnl,"tm");}
							else if(m=="c"){shwM(pnl,"cm");}
							else if(m=="hc"){shwM(pnl,"hcm");}
							else if(m=="blnk"){shwM(pnl,"hm");}
						}
						shwI(pnl,"FO");
					}
				}else{
					var m=pnl.getM();
					pnl.fld=true;
					if(m=="tp"){shwM(pnl,"tm");}
					else if(m=="cp"){shwM(pnl,"cm");}
					else if(m=="hcp"){shwM(pnl,"hcm");}
					shwI(pnl,"FO");
				}
			}else{
				var prev=nd.getPrev();
				bldM(prev,"middle");
				bldCnL(prev,true);
			}
			if(sel){that.sel(nd);}
		}
		pnl.expand=function(){
			var l=eval(pnl.level);
			var tn=pnl;
			for(var i=0;i<l;i++){
				tn=tn.getPn();
				if(!tn.fld){tn.fold(true);}
			}
		}
		pnl.insert=function(nd,sel){
			if(pnl.level!=0){
				bldM(nd,"middle");
				bldCnL(nd,true);
				updtL(nd,pnl.getPn());
			}else{
				var m=pnl.m;
				if(m=="hcm"||m=="hcp"||m=="hc"){bldM(pnl,"middle");bldM(nd,"headHasNext");}
				else if(m=="blnk"||m=="hm"||m=="hp"){bldM(pnl,"last");bldM(nd,"headHasNext")}
				else{bldM(nd,"middle");}
				bldCnL(nd,true);
			}
			pnl.parentNode.insertBefore(nd,pnl);
			if(sel){that.sel(nd);}
		}
		pnl.changeChk=function(chk){
			pnl.chk=chk;
			pnl.getChkBx().src=mnStl+"/"+chk.toLowerCase()+".gif";
		}
		pnl.simpleCheck=function(){
			var chk=pnl.chk;
			if(chk=="unchk"){
				pnl.changeChk("chk");
			}else{
				pnl.changeChk("unchk");
			}
		}
		pnl.flexCheck=function(){
			var chk=pnl.chk;
			if(chk=="unchk"){
				pnl.changeChk("chk");
				chkChild(pnl,"chk");
				chkParent(pnl);
			}else if(chk=="chk"||chk=="chkds"){
				pnl.changeChk("unchk");
				chkChild(pnl,"unchk");
				chkParent(pnl);
			}
			function chkChild(tn,status){
				alert(tn);
				var childs=tn.getChild();
				var count=childs.length;var tmp;
				for(var i=0;i<count;i++){
					tmp=childs[i];
					if(tmp.chk!=status){
						tmp.changeChk(status);
					}
					chkChild(tmp,status);
				}
			}
			function chkParent(tn){
				var level=eval(tn.level);
				var tmp,count;
				tmp=tn;
				for(var i=level-1;i>=0;i--){
					var j,childs,status,childsCount,chkCount,halfChkCount;
					tmp=tmp.getPn();
					childs=tmp.getChild();
					childsCount=childs.length;
					chkCount=0;
					halfChkCount=0;
					for(j=0;j<childsCount;j++){
						status=childs[j].chk;
						if(status=="chk"){
							chkCount++;
						}else if(status=="chkds"){
							halfChkCount++;
						}
					}
					if(chkCount==childsCount){
						tmp.changeChk("chk");
					}else if(chkCount>0||halfChkCount>0){
						/*or (checkNodeCount+halfCheckNodeCount)>0*/tmp.changeChk("chkds");
					}else if((chkCount+halfChkCount)==0){t
						mp.changeChk("unchk");
					}
				}
			}
		}
		/*init*/
		if(!useChk){unDisplay(chkBx.style);}else{pnl.changeChk("unchk");}
		if(!useIcn){unDisplay(icn.style);}else{if(type=="D"){pnl.shwIcn("FE");}else{pnl.shwIcn("L");}}
		/*event*/
		pnl.mnClick=function(){if(!pnl.hsChld){return;}if(pnl.fld){pnl.fold(false);}else{pnl.fold(true);}}
		mn.onclick=function(){pnl.mnClick();}
		pnl.lnkClick=function(){/*保存单击函数*/if(typeof(clkHdl)=="function"){clkHdl(pnl);}}
		lnk.onclick=function(){that.sel(pnl);}
		chkBx.onclick=function(){if(eval(chk)==1){pnl.simpleCheck();}else{pnl.flexCheck();}}
		return pnl;
	}
	var mdClr=function(nd,txtColor,bgColor){
		var link=nd.getLnk();
		var stl=link.style;
		stl.color=txtColor;
		stl.backgroundColor=bgColor;
	}
	var hlt=function(nd){mdClr(nd,CLRWT,CLRBL);}
	var unHlt=function(nd){mdClr(nd,CLRBL,CLRWT);}
	ROOT.getChild=function(){return mbd.childNodes;}
	this.getChecked=function(){
		if(!useChk){err("check property is not inited.");}
		var nds=new Array();
		var n=0;
		getCheckedNodes(ROOT);
		return nds;
		function getCheckedNodes(node){
			var count;
			var nodes=node.getChild();
			return nodes;
			count=nodes.length;
			for(var i=0;i<count;i++){
				var childNode=nodes[i];
				if(childNode.chk=="chk"){
					nds[n]=childNode;
					n++;
				}
				getCheckedNodes(childNode);
			}
		}
	}
	this.loadXmlFromFile=function(filePath,async,cutinNode,clickHandler,appendChildNodesHandler,autoLoading){
		var xmlCurrentNode,nodeType,xmlNodeList;
		if(!MSIE){
			var xmlDom=document.implementation.createDocument("", "", null);
			xmlDom.async=async;
			xmlDom.load(filePath);
			if(async){
				xmlDom.onload=function(){convertXmlToTree(xmlDom.childNodes[0],cutinNode);}
			}else{
				convertXmlToTree(xmlDom.childNodes[0],cutinNode);
			}
		}else{
			var xmlDom = new ActiveXObject("Microsoft.XMLDOM");
			xmlDom.async=async;
			xmlDom.load(filePath);
			if(xmlDom.async){xmlDom.onreadystatechange=function(){if(xmlDom.readyState==4){operateXmlDom(xmlDom);}}
			}else{operateXmlDom(xmlDom);}
			function operateXmlDom(xmlDom){
				if(xmlDom.parseError.errorCode!=0){alert("An Error Occurred:"+xmlDom.parseError.reason);err(xmlDom.parseError.reason);}
				convertXmlToTree(xmlDom.documentElement,cutinNode);
			}
		}
		function convertXmlToTree(xmlDom,cutinNode){
			var xmlNodeList,node,parentNode,parentTreeNode;
			xmlNodeList=xmlDom.childNodes;
			for(var i=0;i<xmlNodeList.length;i++){
				var tmpNode,id,text,type,linkhref,linktarget,iconprefix,expand;
				node=xmlNodeList[i];
				if(!MSIE&&node.nodeType!=1){continue;}
				id=node.getAttribute("id");
				text=node.getAttribute("text");
				type=node.getAttribute("type");
				linkhref=node.getAttribute("linkhref");
				linktarget=node.getAttribute("linktarget");
				//iconprefix=node.getAttribute("iconprefix");
				if(type!="LOAD"){tmpNode=that.createTreeNode(id,text,type,linkhref,linktarget,iconprefix,clickHandler);}else{tmpNode=that.createTreeNode(id,text,"D",linkhref,linktarget,iconprefix,clickHandler,appendChildNodesHandler);}
				if(!isEmpty(cutinNode)){cutinNode.add(tmpNode);}else{that.add(tmpNode);}
				if(autoLoading&&type=="LOAD"){tmpNode.fold(true);}
				convertXmlToTree(node,tmpNode);
			}
		}
	}
	this.shrinkChildsOf=function(node){shrink(node);}
	function shrink(node){
		var childs=node.getChild();
		var childsCount=childs.length;
		for(var i=0;i<childsCount;i++){
			var child=childs[i];
			child.fold(false);
			shrink(child);
		}
	}
	this.getRoot=function(){return ROOT;}
}
function $(id){return document.getElementById(id);}
function isEmpty(s){return (s==null||s==NOTHING||s=="")?true:false;}
function err(msg){throw new Error(msg);}
function crtElmt(tg){return document.createElement(tg);}
function crtDiv(){return crtElmt("DIV");}
function crtSpn(){return crtElmt("SPAN");}
function crtImg(){var img=crtElmt("IMG");var stl=img.style;stl.height="16px";if(MSIE){stl.verticalAlign="top";}return img;}
function crtAnchr(txt){var anchr=crtElmt("A");anchr.href="javascript:void(0)";if(MSIE){anchr.innerText=txt;}else{anchr.textContent=txt;}return anchr;}
function collapse(obj,status){var style=obj.style;var display=style.display;if(status && display=="none"){style.display="block";}else if(!status && (display=="" || display=="block")){style.display="none";}}
function display(stl){stl.display="block";}
function unDisplay(stl){stl.display="none";}
