// SpryAutoSuggest.js - version 0.5 - Spry Pre-Release 1.5
//
// Copyright (c) 2006. Adobe Systems Incorporated.
// All rights reserved.
//
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions are met:
//
//   * Redistributions of source code must retain the above copyright notice,
//     this list of conditions and the following disclaimer.
//   * Redistributions in binary form must reproduce the above copyright notice,
//     this list of conditions and the following disclaimer in the documentation
//     and/or other materials provided with the distribution.
//   * Neither the name of Adobe Systems Incorporated nor the names of its
//     contributors may be used to endorse or promote products derived from this
//     software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
// AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
// IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
// ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
// LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
// CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
// SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
// INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
// CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
// ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
// POSSIBILITY OF SUCH DAMAGE.

var Spry;
if (!Spry) Spry = {};
if (!Spry.Widget) Spry.Widget = {};

Spry.Widget.BrowserSniff = function()
{
	var b = navigator.appName.toString();
	var up = navigator.platform.toString();
	var ua = navigator.userAgent.toString();

	this.mozilla = this.ie = this.opera = r = false;
	var re_opera = /Opera.([0-9\.]*)/i;
	var re_msie = /MSIE.([0-9\.]*)/i;
	var re_gecko = /gecko/i;
	var re_safari = /safari\/([\d\.]*)/i;

	if (ua.match(re_opera)) {
		r = ua.match(re_opera);
		this.opera = true;
		this.version = parseFloat(r[1]);
	} else if (ua.match(re_msie)) {
		r = ua.match(re_msie);
		this.ie = true;
		this.version = parseFloat(r[1]);
	} else if (ua.match(re_safari)) {
		this.safari = true;
		this.version = 1.4;
	} else if (ua.match(re_gecko)) {
		var re_gecko_version = /rv:\s*([0-9\.]+)/i;
		r = ua.match(re_gecko_version);
		this.mozilla = true;
		this.version = parseFloat(r[1]);
	}
	this.windows = this.mac = this.linux = false;

	this.Platform = ua.match(/windows/i) ? "windows" :
					(ua.match(/linux/i) ? "linux" :
					(ua.match(/mac/i) ? "mac" :
					ua.match(/unix/i)? "unix" : "unknown"));
	this[this.Platform] = true;
	this.v = this.version;

	if (this.safari && this.mac && this.mozilla) {
		this.mozilla = false;
	}
};

Spry.is = new Spry.Widget.BrowserSniff();

Spry.Widget.AutoSuggest = function(region, suggestRegion, dataset, field, options)
{
	options = options || {};
	this.options = {};

	if (!this.isBrowserSupported())
		return;

	this.region = document.getElementById(region);
	if (!this.region)
		return;

	this.textElement = Spry.Widget.Utils.getFirstChildWithNodeNameAtAnyLevel(this.region, "INPUT");
	this.textElement.setAttribute('AutoComplete', 'off');
	this.suggestRegion = document.getElementById(suggestRegion);
	this.timerID = null;
	if (typeof dataset == "string"){
		this.dataset = window[dataset];
	}else{
		this.dataset = dataset;
	}
	this.field = field;
	if (typeof field == 'string' && field.indexOf(',') != -1)
	{
		field = field.replace(/\s*,\s*/ig, ',');
		this.field = field.split(',');
	}

	this.showSuggestClass = 'showSuggestClass';
	this.hideSuggestClass = 'hideSuggestClass';
	this.hoverSuggestClass = 'hoverSuggestClass';
	this.minCharsType = false;
	this.containsString = false;
	this.loadFromServer = false;
	this.urlParam = '';
	this.suggestionIsVisible = false;
	this.stopFocus = false;
	this.hasFocus = false;

	Spry.Widget.Utils.setOptions(this, options);
	Spry.Widget.Utils.setOptions(this.options, options);

	var self = this;
	// adding listeners to the text input to catch the text changes
	this._notifyKeyUp = function(e){ self.handleKeyUp(e)}
	this._notifyFocus = function(e){ if (self.stopFocus){ self.handleKeyUp(e);} self.hasFocus = true; self.stopFocus = false;}
	this._notifyMDown = function(e){ self.clickInList = true;}
	Spry.Widget.Utils.addEventListener(this.textElement, "keydown", this._notifyKeyUp, false); 
	Spry.Widget.Utils.addEventListener(this.textElement, "focus", this._notifyFocus, false);
	Spry.Widget.Utils.addEventListener(this.textElement, "drop", this._notifyKeyUp, false);
	Spry.Widget.Utils.addEventListener(this.textElement, "dragdrop", this._notifyKeyUp, false);
	
	// on opera the blur is triggered before onclick
	if (Spry.is.opera){
		this._notifyBlur = function(e) { setTimeout(function(){if (!self.clickInList){ self.showSuggestions(false); }else{ self.stopFocus = true; self.textElement.focus();} self.clickInList = false; self.hasFocus = false;}, 100)}
	}else{
		this._notifyBlur = function(e) { if (!self.clickInList){ self.showSuggestions(false); }else{ self.stopFocus = true; self.textElement.focus();} self.clickInList = false; self.hasFocus = false;}
	}
	Spry.Widget.Utils.addEventListener(this.textElement, "blur", this._notifyBlur, false);

	// we listen on the suggest region too
	Spry.Widget.Utils.addEventListener(this.suggestRegion, "mousedown", this._notifyMDown, false);
	
	// when data is changing we will decide if we will have to show the suggestions
	this.dataset.addObserver(
	{
		onDataChanged: function(el)
		{
				var data = el.getData();
				var val = self.getValue();
				if (data && (!self.minCharsType || val.length >= self.minCharsType) && (data.length > 1 || (data.length == 1 && self.childs[0] && self.childs[0].attributes.getNamedItem("spry:suggest").value != self.getValue())))
				{
					self.showSuggestions(true);
					return;
				}
				self.showSuggestions(false);
		}
	});
	
	// define some notification functions used later for each row in the list
	this._notifyNodeMOver = function(e, node)
	{ 
		var l = self.childs.length;
		for (var i=0; i<l; i++)
			if (self.childs[i] != node && Spry.Widget.Utils.hasClassName(self.childs[i], self.hoverSuggestClass))
			{
				Spry.Widget.Utils.removeClassName(self.childs[i], self.hoverSuggestClass);
				break;
			}
	};
	this._notifyNodeClick = function(e, value) {if (value){self.setValue(value);}};

	// prepare the suggest region
	Spry.Widget.Utils.makePositioned(this.suggestRegion);
	Spry.Widget.Utils.addClassName(this.suggestRegion, this.hideSuggestClass);

	// Set up an observer so we can attach our click behaviors whenever
	// the region is regenerated.
	var regionID = Spry.Widget.Utils.getElementID(suggestRegion);
	this._notifyDataset = { onPostUpdate: function() {
			self.attachClickBehaviors();
	}, onPreUpdate: function(){
			self.removeClickBehaviours();
	}};
	Spry.Data.Region.addObserver(regionID,this._notifyDataset);

	// clean up the widget when on page unload
	// Spry.Widget.Utils.addEventListener(window, 'beforeunload', function(){self.destroy()}, false);

	// make the first computation in case the textfield is not empty
	this.attachClickBehaviors();
	this.handleKeyUp(null);
	this.showSuggestions(false);
};

Spry.Widget.AutoSuggest.prototype.isBrowserSupported = function()
{
	return Spry.is.ie && Spry.is.v >= 5 && Spry.is.windows
		||
	Spry.is.mozilla && Spry.is.v >= 1.4
		||
	Spry.is.safari
		||
	Spry.is.opera && Spry.is.v >= 9;
};

Spry.Widget.AutoSuggest.prototype.getValue = function()
{
	if (!this.textElement)
		return '';
	return this.textElement.value;
};

Spry.Widget.AutoSuggest.prototype.setValue = function(str)
{
	if (!this.textElement)
		return;
	this.textElement.value = str;
	this.showSuggestions(false);
};

Spry.Widget.AutoSuggest.prototype.focus = function()
{
	if (!this.textElement)
		return;
	this.textElement.focus();
};

Spry.Widget.AutoSuggest.prototype.showSuggestions = function(doShow)
{
	if (this.region && this.isVisibleSuggestion() != doShow)
	{
		if (doShow && this.hasFocus)
				Spry.Widget.Utils.addClassName(this.region, this.showSuggestClass);
		else
				Spry.Widget.Utils.removeClassName(this.region, this.showSuggestClass);
	}
	this.suggestionIsVisible = Spry.Widget.Utils.hasClassName(this.region, this.showSuggestClass);
};

Spry.Widget.AutoSuggest.prototype.isVisibleSuggestion = function()
{
	return this.suggestionIsVisible;
};

Spry.Widget.AutoSuggest.prototype.handleKeyUp = function(e)
{
	if (this.timerID)
	{
		clearTimeout(this.timerID);
		this.timerID = null;
	}

	// If the user hit the escape key, hide the auto suggest menu!
	if (e && Spry.Widget.Utils.isSpecialKey(e))
	{
		this.handleSpecialKeys(e);
		return;
	}
	var self = this;
	if (!this.loadFromServer)
		this.timerID = setTimeout(function() { self.timerID = null; self.filterDataSet()}, 200);
	else
		this.timerID = setTimeout(function() { self.timerID = null; self.loadDataSet()}, 200); 
};

Spry.Widget.AutoSuggest.prototype.scrollVisible = function(el)
{
	if (typeof this.scrolParent == 'undefined')
	{
		var currEl = el;
		this.scrolParent = false;
		while	(!this.scrolParent)
		{
			var overflow = Spry.Widget.Utils.getStyleProp(currEl, 'overflow');
			if (!overflow || overflow.toLowerCase() == 'scroll')
			{
					this.scrolParent = currEl;
					break;
			}
			if (currEl == this.region) 
				break;
			
			currEl = currEl.parentNode;
		}
	}

	if (this.scrolParent != false)
	{
		var h = parseInt(Spry.Widget.Utils.getStyleProp(this.scrolParent, 'height'), 10);
		if (el.offsetTop < this.scrolParent.scrollTop)
			this.scrolParent.scrollTop = el.offsetTop;
		else if (el.offsetTop + el.offsetHeight > this.scrolParent.scrollTop + h)
		{
			// the 5 pixels make the latest option more visible.
			this.scrolParent.scrollTop = el.offsetTop + el.offsetHeight - h + 5;
			if (this.scrolParent.scrollTop < 0)
				this.scrolParent.scrollTop = 0;	
		}

	}
};

Spry.Widget.AutoSuggest.prototype.handleSpecialKeys = function(e){
 	switch (e.keyCode)
	{
		case 40: // Down key  
 		case 38: // Up Key
			if (!(this.childs.length > 0) || !this.getValue())
				return;	

			var prev = this.childs.length-1;
			var next = false;
			var found = false;
			var data = this.dataset.getData();
			if (this.childs.length > 1 || (data && data.length == 1 && this.childs[0] && this.childs[0].attributes.getNamedItem('spry:suggest').value != this.getValue()))
			{
				this.showSuggestions(true);
			}
			else
				return;	
			
			for (var k=0; k < this.childs.length; k++)
			{
				if (next)
				{
					Spry.Widget.Utils.addClassName(this.childs[k], this.hoverSuggestClass);
					this.scrollVisible(this.childs[k]);
					break;
				}
				if (Spry.Widget.Utils.hasClassName(this.childs[k], this.hoverSuggestClass))
				{
					Spry.Widget.Utils.removeClassName(this.childs[k], this.hoverSuggestClass);
					found = true;
					if (e.keyCode == 40)
					{
						next = true;
						continue;
					}
					else
					{
						Spry.Widget.Utils.addClassName(this.childs[prev], this.hoverSuggestClass);
						this.scrollVisible(this.childs[prev]);
						break;
					}
				}
				prev = k;
			}
			if (!found || (next && k == this.childs.length))
			{
				Spry.Widget.Utils.addClassName(this.childs[0], this.hoverSuggestClass);
				this.scrollVisible(this.childs[0]);
			}
			Spry.Widget.Utils.stopEvent(e);
			break;
		case 27: // ESC key
			this.showSuggestions(false);
			break;
		case 13: //Enter Key
			if (!this.isVisibleSuggestion()) 
				return;
			for (var k=0; k < this.childs.length; k++)
				if (Spry.Widget.Utils.hasClassName(this.childs[k], this.hoverSuggestClass))
				{
					var attr = this.childs[k].attributes.getNamedItem('spry:suggest');
					if (attr){
						this.setValue(attr.value);
						this.handleKeyUp(null);
					}
					// stop form submission
					Spry.Widget.Utils.stopEvent(e);
					return false;
				}
			break;
		case 9: //Tab Key
			this.showSuggestions(false);
	}
	return;
};

Spry.Widget.AutoSuggest.prototype.filterDataSet = function()
{
	var contains = this.containsString;
	var columnName = this.field;
	var val = this.getValue();

	if (this.previousString && this.previousString == val)
		return;

	this.previousString = val;

	if (!val || (this.minCharsType && this.minCharsType > val.length))
	{
		this.dataset.filter(function(ds, row, rowNumber) { return null; });
		this.showSuggestions(false);
		return;
	}

	var regExpStr = Spry.Widget.Utils.escapeRegExp(val);

	if (!contains)
	 	regExpStr = "^" + regExpStr;

	var regExp = new RegExp(regExpStr, "ig");
	
	if (this.maxListItems > 0)
		this.dataset.maxItems = this.maxListItems;

	var filterFunc = function(ds, row, rowNumber)
	{
		if (ds.maxItems >0  && ds.maxItems <= ds.data.length)
			return null;

		if (typeof columnName == 'object')
		{
			var l = columnName.length;
			for (var i=0; i < l; i++)
			{
				var str = row[columnName[i]];
				if (str && str.search(regExp) != -1)
					return row;
			}
		}
		else
		{
			var str = row[columnName];
			if (str && str.search(regExp) != -1)
				return row;
		}
		return null; 
	};

	this.dataset.filter(filterFunc);
	var data = this.dataset.getData();
	if (data && (!this.minCharsType || val.length >= this.minCharsType) && (data.length > 1 || (data.length == 1 && this.childs[0] && this.childs[0].attributes.getNamedItem('spry:suggest').value != val ))){
		this.showSuggestions(true);
		return;
	}
	this.showSuggestions(false);
};

Spry.Widget.AutoSuggest.prototype.loadDataSet = function()
{
	this.dataset.cancelLoadData();
	this.dataset.useCache = false;
	
	var val = this.getValue();
	if (!val || (this.minCharsType && this.minCharsType > val.length))
	{
		this.showSuggestions(false);
		return;
	}
	
	if (this.previousString && this.previousString == val)
	{
		var data = this.dataset.getData();
		if (data && (data.length > 1 || (data.length == 1 && this.childs[0].attributes.getNamedItem("spry:suggest").value != val))){
			this.showSuggestions(true);
		}else{
			this.showSuggestions(false);
		}
		return;
	}

	this.previousString = val;

	var url = Spry.Widget.Utils.addReplaceParam(this.dataset.url, this.urlParam, val);
	this.dataset.setURL(url);
	this.dataset.loadData();
};

Spry.Widget.AutoSuggest.prototype.addMouseListener =  function(node, value)
{
	var self = this;
	Spry.Widget.Utils.addEventListener(node, "click", function(e){ return self._notifyNodeClick(e, value); self.handleKeyUp(null);}, false); 
	Spry.Widget.Utils.addEventListener(node, "mouseover", function(e){ Spry.Widget.Utils.addClassName(node, self.hoverSuggestClass); self._notifyNodeMOver(e, node)}, false); 
	Spry.Widget.Utils.addEventListener(node, "mouseout", function(e){ Spry.Widget.Utils.removeClassName(node, self.hoverSuggestClass); self._notifyNodeMOver(e, node)}, false); 
};
Spry.Widget.AutoSuggest.prototype.removeMouseListener =  function(node, value)
{
	var self = this;
	Spry.Widget.Utils.removeEventListener(node, "click", function (e){ self._notifyNodeClick(e, value); self.handleKeyUp(null);}, false); 
	Spry.Widget.Utils.removeEventListener(node, "mouseover", function(e){ Spry.Widget.Utils.addClassName(node, self.hoverSuggestClass); self._notifyNodeMOver(e, node)}, false); 
	Spry.Widget.Utils.removeEventListener(node, "mouseout", function(e){ Spry.Widget.Utils.removeClassName(node, self.hoverSuggestClass); self._notifyNodeMOver(e, node)}, false); 
};
Spry.Widget.AutoSuggest.prototype.attachClickBehaviors =  function()
{
	var self = this;
	var valNodes = Spry.Utils.getNodesByFunc(this.region, function(node)
	{
		if (node.nodeType == 1) /* Node.ELEMENT_NODE */
		{
			var attr = node.attributes.getNamedItem("spry:suggest");
			if (attr){
				self.addMouseListener(node, attr.value);
				return true;
			}
		}
		return false;
	});
	this.childs = valNodes;
};
Spry.Widget.AutoSuggest.prototype.removeClickBehaviours = function()
{
	var self = this;
	var valNodes = Spry.Utils.getNodesByFunc(this.region, function(node)
	{
		if (node.nodeType == 1) /* Node.ELEMENT_NODE */
		{
			var attr = node.attributes.getNamedItem("spry:suggest");
			if (attr){
				self.removeMouseListener(node, attr.value);
				return true;
			}
		}
		return false;
	});
};
Spry.Widget.AutoSuggest.prototype.destroy = function(){
	this.removeClickBehaviours();
	Spry.Data.Region.removeObserver(Spry.Widget.Utils.getElementID(this.suggestRegion),this._notifyDataset);
	Spry.Widget.Utils.removeEventListener(this.textElement, "keydown", this._notifyKeyUp, false); 
	Spry.Widget.Utils.removeEventListener(this.textElement, "focus", this._notifyFocus, false);
	Spry.Widget.Utils.removeEventListener(this.textElement, "drop", this._notifyKeyUp, false);
	Spry.Widget.Utils.removeEventListener(this.textElement, "dragdrop", this._notifyKeyUp, false);
	Spry.Widget.Utils.removeEventListener(this.suggestRegion, "mousedown", this._notifyMDown, false);
	Spry.Widget.Utils.removeEventListener(this.textElement, "blur", this._notifyBlur, false);
	for (var k in this){
		if (typeof this[k] != 'function'){
			try { delete this.textElement; } catch(err) {}
		}
	}
};
//////////////////////////////////////////////////////////////////////
//
// Spry.Widget.Utils
//
//////////////////////////////////////////////////////////////////////
if (!Spry.Widget.Utils)	Spry.Widget.Utils = {};

Spry.Widget.Utils.specialSafariNavKeys = ",63232,63233,63234,63235,63272,63273,63275,63276,63277,63289,";
Spry.Widget.Utils.specialCharacters = ",9,13,27,38,40,";
Spry.Widget.Utils.specialCharacters += Spry.Widget.Utils.specialSafariNavKeys;

Spry.Widget.Utils.isSpecialKey = function (ev)
{
	return Spry.Widget.Utils.specialCharacters.indexOf("," + ev.keyCode + ",") != -1;
};
Spry.Widget.Utils.getElementID = function(el)
{
	if (typeof el == 'string' && el)
		return el;
	return el.getAttribute('id');
};
Spry.Widget.Utils.addReplaceParam = function(url, param, paramValue)
{
	var uri ='';
	var qstring = '';
	var i = url.indexOf('?')
	if ( i != -1)
	{
		uri = url.slice(0, i);
		qstring = url.slice(i+1);
	}
	else 
		uri = url;

	// the list of parameters
	qstring = qstring.replace('?', '');
	var arg = qstring.split("&");

	// prevent malicious use
	if (param.lastIndexOf('/') != -1)
		param = param.slice(param.lastIndexOf('/')+1);

	// remove param from the list
	for (i=0; i < arg.length ;i++)
	{
		var k = arg[i].split('=');
		if ( (k[0] && k[0] == decodeURI(param)) || arg[i] == decodeURI(param))
			arg[i] = null;
	}

	arg[arg.length] = encodeURI(param) + '=' + encodeURI(paramValue);
	qstring = '';
	// reconstruct the qstring
	for (i=0; i < arg.length; i++)
		if (arg[i])
			qstring += '&'+arg[i];

	// remove the first &
	qstring = qstring.slice(1);

	url = uri + '?' + qstring;
	return url;
};

Spry.Widget.Utils.addClassName = function(ele, clssName)
{
	if (!ele) return;

	if (!ele.className) ele.className = '';

	if (!ele || ele.className.search(new RegExp("\\b" + clssName + "\\b")) != -1)
	  return;

	ele.className += ' ' + clssName;
};

Spry.Widget.Utils.removeClassName = function(ele, className)
{
	if (!ele) return;	

	if (!ele.className)
	{
		ele.className = '';
		return;
	}
	ele.className = ele.className.replace(new RegExp("\\s*\\b" + className + "\\b", "g"), '');
};

Spry.Widget.Utils.hasClassName = function (ele, className)
{
	if (!ele || !className)
		return false;

	if (!ele.className)
		ele.className = '';

	return ele.className.search(new RegExp("\\s*\\b" + className + "\\b")) != -1;
};

Spry.Widget.Utils.addEventListener = function(el, eventType, handler, capture)
{
	try
	{
		if (el.addEventListener)
			el.addEventListener(eventType, handler, capture);
		else if (el.attachEvent)
			el.attachEvent("on" + eventType, handler, capture);
	}catch (e) {}
};

Spry.Widget.Utils.removeEventListener = function(el, eventType, handler, capture)
{
	try
	{
		if (el.removeEventListener)
			el.removeEventListener(eventType, handler, capture);
		else if (el.detachEvent)
			el.detachEvent("on" + eventType, handler, capture);
	}catch (e) {}
};

Spry.Widget.Utils.stopEvent = function(ev)
{
	ev.cancelBubble = true;
	ev.returnValue = false;

	try
	{
		this.stopPropagation(ev);
	}catch (e){}
	try{
		this.preventDefault(ev);
	}catch(e){}
};

/**
 * Stops event propagation
 * @param {Event} ev the event
 */
Spry.Widget.Utils.stopPropagation = function(ev)
{
	if (ev.stopPropagation)
		ev.stopPropagation();
	else
		ev.cancelBubble = true;
};

/**
 * Prevents the default behavior of the event
 * @param {Event} ev the event
 */
Spry.Widget.Utils.preventDefault = function(ev)
{
	if (ev.preventDefault)
		ev.preventDefault();
	else
		ev.returnValue = false;
};

Spry.Widget.Utils.setOptions = function(obj, optionsObj, ignoreUndefinedProps)
{
	if (!optionsObj)
		return;
	for (var optionName in optionsObj)
	{
		if (typeof ignoreUndefinedProps != 'undefined' && ignoreUndefinedProps && typeof optionsObj[optionName] == 'undefined')
			continue;
		obj[optionName] = optionsObj[optionName];
	}
};

Spry.Widget.Utils.firstValid = function()
{
	var ret = null;
	for (var i=0; i < Spry.Widget.Utils.firstValid.arguments.length; i++)
		if (typeof Spry.Widget.Utils.firstValid.arguments[i] != 'undefined')
		{
			ret = Spry.Widget.Utils.firstValid.arguments[i];
			break;
		}

	return ret;
};

Spry.Widget.Utils.camelize = function(stringToCamelize)
{
  var oStringList = stringToCamelize.split('-');
	var isFirstEntry = true;
	var camelizedString = '';

	for(var i=0; i < oStringList.length; i++)
	{
		if(oStringList[i].length>0)
		{
			if(isFirstEntry)
			{
				camelizedString = oStringList[i];
				isFirstEntry = false;
			}
			else
			{
				var s = oStringList[i];
				camelizedString += s.charAt(0).toUpperCase() + s.substring(1);
			}
		}
	}

	return camelizedString;
};

Spry.Widget.Utils.getStyleProp = function(element, prop)
{
	var value;
	var camel = Spry.Widget.Utils.camelize(prop);
	try
	{
		value = element.style[camel];
		if (!value)
		{
			if (document.defaultView && document.defaultView.getComputedStyle)
			{
				var css = document.defaultView.getComputedStyle(element, null);
				value = css ? css.getPropertyValue(prop) : null;
			}
			else
				if (element.currentStyle)
					value = element.currentStyle[camel];
		}
	}
	catch (e) {}

	return value == 'auto' ? null : value;
};
Spry.Widget.Utils.makePositioned = function(element)
{
	var pos = Spry.Widget.Utils.getStyleProp(element, 'position');
	if (!pos || pos == 'static')
	{
		element.style.position = 'relative';

		// Opera returns the offset relative to the positioning context, when an
		// element is position relative but top and left have not been defined
		if (window.opera)
		{
			element.style.top = 0;
			element.style.left = 0;
		}
	}
};
Spry.Widget.Utils.escapeRegExp = function(rexp)
{
	return rexp.replace(/([\.\/\]\[\{\}\(\)\\\$\^\?\*\|\!\=\+\-])/g, '\\$1');
};
Spry.Widget.Utils.getFirstChildWithNodeNameAtAnyLevel = function(node, nodeName)
{
	var elements  = node.getElementsByTagName(nodeName);
	if (elements)
		return elements[0];
	
	return null;
};
