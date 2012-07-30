/*****************************************/
// Name: Javascript Textarea BBCode Markup Editor
// Version: 1.3
// Author: Balakrishnan
// Last Modified Date: 25/jan/2009
// License: Free
// URL: http://www.corpocrat.com
/******************************************/

var textarea;
var content;
var root = '';
var imgDir = root + '/js/bbeditor/images';

$(document).ready(function(){
	$('link').last().after('<link href="' + root + '/js/bbeditor/styles.css" rel="stylesheet" type="text/css">');	
	$('textarea.ed').each(function() {
    	edToolbar($(this).attr('id'));
  	});
});

function fedToolbar(obj) {
	//alert('tjo');
	//$('textarea#' + obj).before('<h1>tralala</h1>');
}

function edToolbar(obj) {
	//alert('tjo');
	//$('textarea#' + obj).before('<div class="toolbar"><img class="button" src="' + root + '/js/bbeditor/images/bold.gif" name="btnBold" title="Bold" onClick="doAddTags("[b]","[/b]","' + obj + '")"><img class="button" src="' + root + '/js/bbeditor/images/italic.gif" name="btnItalic" title="Italic" onClick="doAddTags("[i]","[/i]","' + obj + '")"><img class="button" src="' + root + '/js/bbeditor/images/underline.gif" name="btnUnderline" title="Underline" onClick="doAddTags("[u]","[/u]","' + obj + '")"><img class="button" src="' + root + '/js/bbeditor/images/link.gif" name="btnLink" title="Insert URL Link" onClick="doURL("' + obj + '")"><img class="button" src="' + root + '/js/bbeditor/images/picture.gif" name="btnPicture" title="Insert Image" onClick="doImage("' + obj + '")"><img class="button" src="' + root + '/js/bbeditor/images/ordered.gif" name="btnList" title="Ordered List" onClick="doList("[LIST=1]","[/LIST]","' + obj + '")"><img class="button" src="' + root + '/js/bbeditor/images/unordered.gif" name="btnList" title="Unordered List" onClick="doList("[LIST]","[/LIST]","' + obj + '")"><img class="button" src="' + root + '/js/bbeditor/images/quote.gif" name="btnQuote" title="Quote" onClick="doAddTags("[quote]","[/quote]","' + obj + '")"><img class="button" src="' + root + '/js/bbeditor/images/code.gif" name="btnCode" title="Code" onClick="doAddTags("[code]","[/code]","' + obj + '")"></div>');
	
	var $toolbar = $('<div />');
	$toolbar.attr('class', 'toolbar');
	
	var $btnBold = $('<img />').attr('src', imgDir + '/bold.gif').attr('name', 'btnBold').attr('title', 'fet').click(function(){doAddTags('<span class="bb-bold>"', '</span>', obj);});
	
	var $btnItalic = $('<img />').attr('src', imgDir + '/italic.gif').attr('name', 'btnItalic').attr('title', 'kursiv').click(function(){doAddTags('<span class="bb-italic">','</span>',obj);});	

	var $btnUline = $('<img />').attr('src', imgDir + '/underline.gif').attr('name', 'btnUnderline').attr('title', 'understruken').click(function(){doAddTags('<span class="bb-underline">','</span>',obj);});

	var $btnLink = $('<img />').attr('src', imgDir + '/link.gif').attr('name', 'btnLink').attr('title', 'klistra in url').click(function(){doURL(obj);});

	var $btnPicture = $('<img />').attr('src', imgDir + '/picture.gif').attr('name', 'btnPicture').attr('title', 'klistra in bildurl').click(function(){doImage(obj);});

	var $btnList = $('<img />').attr('src', imgDir + '/ordered.gif').attr('name', 'btnList').attr('title', 'sorterad lista').click(function(){doList('<ul class="bb-ulist">', '</ul>', obj);});
	var $btnUList = $('<img />').attr('src', imgDir + '/unordered.gif').attr('name', 'btnList').attr('title', 'osorterad lista').click(function(){doList('<ol class="bb-olist">', '</ol>', obj);});
	var $btnQuote = $('<img />').attr('src', imgDir + '/quote.gif').attr('name', 'btnQuote').attr('title', 'citera').click(function(){doAddTags('<blockquote class="bb-quote">', '</blockquote>', obj);});
	
	
	$toolbar.append($btnBold);	
	$toolbar.append($btnItalic);		
	$toolbar.append($btnUline);
	$toolbar.append($btnLink);
	$toolbar.append($btnPicture);
	$toolbar.append($btnList);
	$toolbar.append($btnUList);
	$toolbar.append($btnQuote);	
	
	
	$('textarea#' + obj).before($toolbar);
	
	/*
    document.write("<div class=\"toolbar\">");
	document.write("<img class=\"button\" src=\"" + root + "/js/bbeditor/images/bold.gif\" name=\"btnBold\" title=\"Bold\" onClick=\"doAddTags('[b]','[/b]','" + obj + "')\">");
    document.write("<img class=\"button\" src=\"" + root + "/js/bbeditor/images/italic.gif\" name=\"btnItalic\" title=\"Italic\" onClick=\"doAddTags('[i]','[/i]','" + obj + "')\">");
	document.write("<img class=\"button\" src=\"" + root + "/js/bbeditor/images/underline.gif\" name=\"btnUnderline\" title=\"Underline\" onClick=\"doAddTags('[u]','[/u]','" + obj + "')\">");
	document.write("<img class=\"button\" src=\"" + root + "/js/bbeditor/images/link.gif\" name=\"btnLink\" title=\"Insert URL Link\" onClick=\"doURL('" + obj + "')\">");
	document.write("<img class=\"button\" src=\"" + root + "/js/bbeditor/images/picture.gif\" name=\"btnPicture\" title=\"Insert Image\" onClick=\"doImage('" + obj + "')\">");
	document.write("<img class=\"button\" src=\"" + root + "/js/bbeditor/images/ordered.gif\" name=\"btnList\" title=\"Ordered List\" onClick=\"doList('[LIST=1]','[/LIST]','" + obj + "')\">");
	document.write("<img class=\"button\" src=\"" + root + "/js/bbeditor/images/unordered.gif\" name=\"btnList\" title=\"Unordered List\" onClick=\"doList('[LIST]','[/LIST]','" + obj + "')\">");
	document.write("<img class=\"button\" src=\"" + root + "/js/bbeditor/images/quote.gif\" name=\"btnQuote\" title=\"Quote\" onClick=\"doAddTags('[quote]','[/quote]','" + obj + "')\">"); 
  	document.write("<img class=\"button\" src=\"" + root + "/js/bbeditor/images/code.gif\" name=\"btnCode\" title=\"Code\" onClick=\"doAddTags('[code]','[/code]','" + obj + "')\">");
    document.write("</div>");
	*/
	//document.write("<textarea id=\""+ obj +"\" name = \"" + obj + "\" cols=\"" + width + "\" rows=\"" + height + "\"></textarea>");
				}

function doImage(obj)
{
textarea = document.getElementById(obj);
var url = prompt('Enter the Image URL:','http://');
var scrollTop = textarea.scrollTop;
var scrollLeft = textarea.scrollLeft;

if (url != '' && url != null) {

	if (document.selection) 
			{
				textarea.focus();
				var sel = document.selection.createRange();
				sel.text = '<img src="' + url + '" alt="" title="" />';
			}
   else 
    {
		var len = textarea.value.length;
	    var start = textarea.selectionStart;
		var end = textarea.selectionEnd;
		
        var sel = textarea.value.substring(start, end);
	    //alert(sel);
		var rep = '<img src="' + url + '" alt="" title="" />';
        textarea.value =  textarea.value.substring(0,start) + rep + textarea.value.substring(end,len);
		
			
		textarea.scrollTop = scrollTop;
		textarea.scrollLeft = scrollLeft;
	}
}

}

function doURL(obj)
{
textarea = document.getElementById(obj);
var url = prompt('Enter the URL:','http://');
var scrollTop = textarea.scrollTop;
var scrollLeft = textarea.scrollLeft;

if (url != '' && url != null) {

	if (document.selection) 
			{
				textarea.focus();
				var sel = document.selection.createRange();
				
			if(sel.text==""){
					sel.text = '<a href="' + url + '">' + url + '</a>';
					} else {
					sel.text = '<a href="' + url + '">' + sel.text + '</a>';
					}			

				//alert(sel.text);
				
			}
   else 
    {
		var len = textarea.value.length;
	    var start = textarea.selectionStart;
		var end = textarea.selectionEnd;
		
        var sel = textarea.value.substring(start, end);
		
		if(sel==""){
				var rep = '<a href="' + url + '">' + url + '</a>';
				} else
				{
				var rep = '<a href="' + url + '">' + sel + '</a>';
				}
	    //alert(sel);
		
        textarea.value =  textarea.value.substring(0,start) + rep + textarea.value.substring(end,len);
		
			
		textarea.scrollTop = scrollTop;
		textarea.scrollLeft = scrollLeft;
	}
 }
}

function doAddTags(tag1,tag2,obj)
{
textarea = document.getElementById(obj);
	// Code for IE
		if (document.selection) 
			{
				textarea.focus();
				var sel = document.selection.createRange();
				//alert(sel.text);
				sel.text = tag1 + sel.text + tag2;
			}
   else 
    {  // Code for Mozilla Firefox
		var len = textarea.value.length;
	    var start = textarea.selectionStart;
		var end = textarea.selectionEnd;
		
		
		var scrollTop = textarea.scrollTop;
		var scrollLeft = textarea.scrollLeft;

		
        var sel = textarea.value.substring(start, end);
	    //alert(sel);
		var rep = tag1 + sel + tag2;
        textarea.value =  textarea.value.substring(0,start) + rep + textarea.value.substring(end,len);
		
		textarea.scrollTop = scrollTop;
		textarea.scrollLeft = scrollLeft;
		
		
	}
}

function doList(tag1,tag2,obj){
textarea = document.getElementById(obj);
// Code for IE
		if (document.selection) 
			{
				textarea.focus();
				var sel = document.selection.createRange();
				var list = sel.text.split('\n');
		
				for(i=0;i<list.length;i++) 
				{
				list[i] = '<li>' + list[i] + '</li>';
				}
				//alert(list.join("\n"));
				sel.text = tag1 + '\n' + list.join("\n") + '\n' + tag2;
			} else
			// Code for Firefox
			{

		var len = textarea.value.length;
	    var start = textarea.selectionStart;
		var end = textarea.selectionEnd;
		var i;
		
		var scrollTop = textarea.scrollTop;
		var scrollLeft = textarea.scrollLeft;

		
        var sel = textarea.value.substring(start, end);
	    //alert(sel);
		
		var list = sel.split('\n');
		
		for(i=0;i<list.length;i++) 
		{
		list[i] = '<li>' + list[i] + '</li>';
		}
		//alert(list.join("<br>"));
        
		
		var rep = tag1 + '\n' + list.join("\n") + '\n' +tag2;
		textarea.value =  textarea.value.substring(0,start) + rep + textarea.value.substring(end,len);
		
		textarea.scrollTop = scrollTop;
		textarea.scrollLeft = scrollLeft;
 }
}