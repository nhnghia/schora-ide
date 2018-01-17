<?php
	session_start();
	$_SESSION['time'] = time();
	header('Content-type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Symbolic ChOReography Analysis</title>
<style type="text/css">
body{
	height: 100%;
}
#menuBar {
	top: 0px;
	width: 100%;
	height: 30px;
	margin: 0px;
}

#wait{
	position: absolute;
	top: 10px;
	heigth: 30px;
	left: 60px;
	visibility: hidden;
}

#output {
	margin: 5px;
}

#editor {
	width: 100%;
	height: 100%;
}

#waiting {
	position: absolute;
	z-index: 100;
}

h2 {
	font-size: 14pt;
}

input[type="button"]{
	cursor: pointer;
}

#menuBar input[type="button"] {
	background: white;
	height: 20px;
	border: 1px solid white;
}

#menuBar input[type="button"]:hover:enabled {
	border: 1px solid blue;
}

#menuBar input[type="button"]:active:enabled {
	border: 1px solid green;
}

#menuBar #runBtn {
	background-image: url(images/run.png);
	background-color: transparent;
	background-position: 0px 2px;
	background-repeat: no-repeat;
	width: 60px;
}

#OKBtn {
	width: 50px;
	float: right;
	margin: 10px
}
</style>
<link rel="stylesheet" href="jqwidgets/styles/jqx.base.css"
	type="text/css" />
<script type="text/javascript" src="scripts/gettheme.js"></script>
<script type="text/javascript" src="scripts/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
<script type="text/javascript" src="jqwidgets/jqxsplitter.js"></script>
<script type="text/javascript" src="jqwidgets/jqxtabs.js"></script>
<script type="text/javascript" src="jqwidgets/jqxbuttons.js"></script>
<script type="text/javascript" src="jqwidgets/jqxscrollbar.js"></script>
<script type="text/javascript" src="jqwidgets/jqxpanel.js"></script>
<script type="text/javascript" src="jqwidgets/jqxtree.js"></script>
<script type="text/javascript" src="jqwidgets/jqxexpander.js"></script>
<script type="text/javascript" src="jqwidgets/jqxwindow.js"></script>
<script type="text/javascript" src="jqwidgets/jqxdata.js"></script>
<script type="text/javascript" src="jqwidgets/jqxexpander.js"></script>
 <script type="text/javascript" src="jqwidgets/jqxmenu.js"></script>

<script src="images/tool.js"></script>
<script src="images/viz.js"></script>
<script src="ace/ace.js" type="text/javascript" charset="utf-8"></script>

</head>
<body>

	<div id="menuBar">
		<input type="button" value="Run" id="runBtn" /> 
		<span style="float: right">
			<input type="button" value="Examples"id="exampleBtn" />
			<input type="button" value="Help"id="helpBtn" /> 
			<input type="button" value="About" id="aboutBtn" />
		</span>
	</div>
	
     <div id='jqxMenu'>
     
     </div>
	
	
	<div id="wait"><img src="images/wait.gif"></div>
	
	<div id="container">
		<div>
			<div id="top">
				<div id="editorDiv">
					<div id="editor">/* This script show a very simple example of 4 components: 
    - a choreography "spec" with two roles: requestor (a) and responder (b)
    - two local specifications: "client" & "seller"
    - a component "impl" is a composition of "client" and "seller"
*/

//Declare components here. They respect either DOT language or ChorD language.
DECLARATIONS
	component spec STG
		// list of states. The first state is initial of STG
		1; 2; 3;
		// list of transitions
		1 -> 2 [label="request[a,b].&lt;x&gt;"];
		2 -> 3 [label="[x>0] response[b,a]."];
		2 -> 3 [label ="[x<=0] error[b,a]."];
	end component
	
	component client STG
		1; 2; 3;
		1 -> 2 [label="request[a,b]!&lt;x1&gt;"];
		2 -> 3 [label="response[b,a]?"];
	end component
	
    component seller chorD
        request[a,b]?&lt;y1&gt; ; response[b,a]!
    end component
    
	component impl chorD
		client || seller
    end component
    
//List of commands
COMMANDS
	//display STG of components
	showSTG spec client seller impl
	//check if the implementation "impl" conforms to the specification "shop"
    conformance impl spec </div>
				</div>
				<div>
					<div id="right"  class="jqx-hideborder jqx-hidescrollbars">
						<ul>
							<li style="margin-left: 30px;">Outline</li>
						</ul>
						<div style="border: none; overflow: auto" class="jqx-hideborder jqx-hidescrollbars" id = "outline">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div>
			<div class="jqx-hideborder jqx-hidescrollbars" id="bottom">
				<ul>
					<li style="margin-left: 30px;">Output</li>
					<li canselect='false' >
						<img alt="Expand" src="images/minus.png" id="collapseImg">
					</li>
				</ul>
				<div style="border: none; overflow: auto" id='output'></div>
				<div></div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function() {
			var theme = getDemoTheme();
			var h = $(document).height() - 50;
			
			$(window).resize(function(){
				var h = $(document).height() - 50;
				$("#container").height(h)
				editor.resize();
			});
			
			$("#bottom").resize(function(){
				var h = $("#bottom").height();
				$("#bottom").jqxTabs.height(h);
				console.log(h);
			});
			$('#container').jqxSplitter({
				height : h,
				width  : '100%',
				orientation : 'horizontal',
				theme : theme,
				panels : [ {
					size : '70%',
				}, {
					size : '30%'
				} ]
			});
			$('#top').jqxSplitter({
				width : '100%',
				theme : theme,
				panels : [ {
					size : '70%',
					collapsible : false
				}, {
					size : '30%'
				} ]
			});
			$("#bottom").jqxTabs({
				theme : theme,
				height : '100%',
				width : '100%'
			});
			$("#right").jqxTabs({
				theme : theme,
				height : '100%',
				width : '100%'
			});
			$('#outline').jqxTree({
				theme : theme,
				height : '100%',
				width : '100%'
			});

			$('#aboutDlg').jqxWindow({
				height : 'auto',
				isModal : true,
				width : 300,
				theme : theme
			});
			$('#aboutDlg').jqxWindow('close');
			
			$('#helpDlg').jqxWindow({
				height : 'auto',
				isModal : true,
				width : 300,
				theme : theme
			});
			$('#helpDlg').jqxWindow('close');
			
			//menu
			var exampleData = [];
			$.ajax({
	    		url		: "examples/list.php",
	    		type    : "get",
	    		dataType: "xml",
	    		cache   : false, 
	    		error:function(xhr, status, error){
	    			$("#output").html("ERROR: <pre>This browser does not support SChorA. Please update it to the newest version.</pre> <pre>ReferenceError: Cannot load examples list, " + status + "</pre>");
	    		},
	    		success: function(data){
	    			var str = "";
	    			var id = 0;
	    			try{
		    			$(data).find("a").each(function(){
		    				var title = $(this).attr("title");
		    				var text  = $(this).text();
		    				str += "<li id=\"" + id + "\">" + title + "</li>";
		    				exampleData.push(text);
		    				id ++;
		    			});
		    			
		    			$("#jqxMenu").html("<ul>" + str + "</ul>");
		    			
		    			var contextMenu = $("#jqxMenu").jqxMenu({ width: '120px', autoOpenPopup: false, mode: 'popup', theme: theme });
		    			$("#exampleBtn").on('click', function (event) {
		    			    var scrollTop = $("#exampleBtn").position().top + $("#exampleBtn").height() + 10;
		                    var scrollLeft = $("#exampleBtn").position().left;
		                    contextMenu.jqxMenu('open', scrollLeft, scrollTop);
		                    return false;
		                });
		    			
		    			$('#jqxMenu').on('itemclick', function (event){
    					    var id = $(event.args).attr("id");
    					    editor.setValue($.trim(exampleData[id]));
    					    editor.gotoLine(0);
    					    //console.log(exampleData[id]);
    					});
		    			
	    			}catch (err){
	    				$("#output").html("ERROR: " + err);
	    			}
	    		},
	    		statusCode: {
	    			404: function(){
	    				$("#output").html("ERROR: page not found!");
	    			},
	    		},
	    	});
			
			
		});

		var editor = ace.edit("editor");
		editor.setTheme("ace/theme/eclipse");
		editor.setShowPrintMargin(false);
		editor.getSession().setMode("ace/mode/cora");
		editor.getSession().setUseWrapMode(true);
		
		editor.on("change", function(e) {
			updateOutline();
			return true;
		});

		//when cursor change, update outline: select item corresponding with the current component
		/*
		editor.getSession().selection.on('changeCursor', function(e) {
			selectOutlineItem();
			return true;
		});
		*/
		
		var isCollapse = false;
		$('#bottom').on('tabclick', function (e){
			if ($("div[id^='commands']").length == 0)
				return;
			
			var id = e.args.item;
			if (id != 1)
				return;
			var event;
			isCollapse = ! isCollapse;
			if (isCollapse){
				event = "collapse";
				 $('#collapseImg').attr('src','images/plus.png');
			}else{
				event = "expand";
				$('#collapseImg').attr('src','images/minus.png');
			}
			
			//get list of div with id starting by 'commands'
            $("div[id^='commands']").each(function() {
                $(this).jqxExpander(event);
            });
		}); 
		
		$('#top').on('resize', function(e) {
			editor.resize();
		});

		//tree outline
		$('#outline').on('select', function(event) {
			var args = event.args;
			var item = $('#outline').jqxTree('getItem', args.element);
			editor.gotoLine(item.value);
		});

		//update outline
		var outlineData = [];
		function updateOutline() {
			var session = editor.getSession();
			var n = session.getLength();
			//get list of tokens at each line

			var data = [];
			var isComponent = 0;

			for (i = 0; i < n; i++) {
				var tokens = session.getTokens(i);
				var len = tokens.length;
				for (j = 0; j < len; j++) {
					if (tokens[j].type == "keyword") {
						if (tokens[j].value == "DECLARATIONS") {
							var obj = {
								"id" : "1",
								"text" : tokens[j].value,
								"value" : i + 1,
								"parentid" : "-1"
							};
							data.push(obj);
						} else if (tokens[j].value == "COMMANDS") {
							var obj = {
								"id" : "2",
								"text" : tokens[j].value,
								"value" : i + 1,
								"parentid" : "-1"
							};
							data.push(obj);
						} else if (tokens[j].value == "component") {
							if (isComponent == 0) {
								//get next token => name of component
								isComponent = 1;
							} else if (isComponent == 2) { //OK, I get its name
								isComponent = 0; //end component
							}
						}
					} else if (isComponent == 1
							&& tokens[j].type == "identifier") {
						isComponent = 2;
						var obj = {
							"id" : "1-" + i,
							"text" : tokens[j].value,
							"value" : i + 1,
							"parentid" : "1"
						};
						data.push(obj);
					}
				}
			}
			//console.log("OK");
			if (outlineData.compare(data) == true)
				return;

			outlineData = data;
			//update to outline div
			if (outlineData.length > 0) {
				// prepare the data
				var source = {
					datatype : "json",
					datafields : [ {
						name : 'id'
					}, {
						name : 'parentid'
					}, {
						name : 'text'
					}, {
						name : 'value'
					} ],
					id : 'id',
					localdata : outlineData
				};
				// create data adapter.
				var dataAdapter = new $.jqx.dataAdapter(source);
				// perform Data Binding.
				dataAdapter.dataBind();  
				var records = dataAdapter.getRecordsHierarchy('id', 'parentid',
						'items', [ {
							name : 'text',
							map : 'label'
						} ]);
				$('#outline').jqxTree({
					source : records
				});
				$('#outline').jqxTree('expandAll');
			}
		}
		
		
		//When cursor of editor change ==> select outlineItem correspond
		function selectOutlineItem(){
			if (outlineData == null)
				return;
			if (outlineData.length == 0)
				return;
			
			var row = editor.selection.getCursor().row;
			var n = outlineData.length;
			var item = null;
			for (i=0; i<n; i++)
				if (row >= outlineData[i].value){
					item = outlineData[i];
				}
			if (item == null)
				return;
			console.log(item);
			($("#" + item.id)[0]);
		}
		//firt time
		setTimeout(function(){
			updateOutline();	
		}, 1000);
		
		
		
		$("#aboutBtn").click(function() {
			$('#aboutDlg').jqxWindow('open');
		});

		$("#helpBtn").click(function() {
			$('#helpDlg').jqxWindow('open');
		});
		
		function  setWait(ob){
	    	if (ob){
	    		$("#runBtn").prop("disabled", true);
	    		$("#wait").css("visibility", "visible");
	    	}else{
	    		$("#wait").css("visibility", "hidden");
	    		
	    		setTimeout(function (){
	    			$("#runBtn").prop("disabled", false);
	    		}, 2000);
	    	}
	    }
		
		
		$("#runBtn").click(function(){
			setWait(true);
	    	$("#output").html("");	//clear output
	    	$("#error").html("");	//clear output
	    	
	    	$.ajax({
	    		url     : "php/get.php",
	    		type    : "post",
	    		data    : {"script" : editor.getValue()},
	    		dataType: "xml",
	    		cache   : false, 
	    		error:function(xhr, status, error){
	    			$("#output").html("ERROR: " + status + "<br/><pre>" + error.message + "</pre>");
	    		},
	    		success: function(data){
	    			var str = "";
	    			$("#output").html("printing output...");
	    			try{
		    			$(data).find("cmd").each(function(){
		    				
		    				var title = $(this).attr("title");
		    				var type  = $(this).attr("type");
		    				var text  = $(this).text();
	
		    				str += '<div id = "commands"><div>' + title + "</div><div>";
		    				if (type == "dot"){
		    					try{
			    					var graph = Viz(text, "svg");
		    						var d = graph.indexOf("<svg");
		    						graph = graph.substr(d);
		    						str += "<center>" + graph + "</center>";
		    					}catch(err){
		    						str += "<pre>This browser does not support to generate graph. Please update it to the newest version<pre>"
		    						str += "<pre>" + err +"</pre>";
		    						str += "<pre>The graph in DOT format:\n" + text + "</pre>";
		    					}
		    				}else{
		    					str += "<pre>" + text + "</pre>";
		    				}
		    				str += "</div></div>";
			    			$("#output").html(str);
			    			
			    			//convert outputs to Expanders
			    			$("#output").ready(function () {
			    	            // Create jqxExpander
			    	            var theme = getDemoTheme();
			    	            //get list of div with id starting by 'commands'
			    	            $("div[id^='commands']").each(function() {
			    	                $(this).jqxExpander({ width: '100%', theme: theme });
			    	                //console.log($(this));
			    	            });
			    	        });
		    			});
	    			}catch (err){
	    				$("#output").html("ERROR: " + err);
	    			}
	    		},
	    		statusCode: {
	    			404: function(){
	    				$("#output").html("ERROR: page not found!");
	    			},
	    			default: {
	    				
	    			}
	    		},
	    		complete:function(jq, txt){
	    			setWait(false);
	    			
	    		}
	    	});
		});
	</script>
	
	<div id="helpDlg" style="display: none">
		<div>Help</div>
		<div>
			<p>You can see document of SChorA  
			<a href="http://schora.lri.fr" target="_blank">here</a>.</p>
			<p>or email to <a href="mailto:huunghia.nguyen@me.fr">huunghia.nguyen@me.fr</a></p>
			<input type="button" value="OK" id="OKBtn"
				onclick="$('#helpDlg').jqxWindow('close');">
		</div>
	</div>

	<div id="aboutDlg" style="display: none">
		<div>About us</div>
		<div>
      <p>SChorA IDE version 0.1</p>
			<p>SChorA is created by <a href="mailto:huunghia.nguyen@me.com">Huu Nghia NGUYEN</a>
			as a tool supported to his Ph.D. thesis
			under the supervision of <a href="http://pagesperso-systeme.lip6.fr/Pascal.Poizat/">Pascal POIZAT</a> and 
			<a href="https://www.lri.fr/~zaidi">Fatiha ZAIDI</a>.</p>
			<p>SChorA is available under GPL term</p>
			<p>SChorA uses:
				<a href="http://www.jqwidgets.com">jQWidgets</a>,
				<a href="http://ace.ajax.org/">Ace</a>,
				<a href="https://github.com/mdaines/viz.js">viz.js</a>,
				<a href="http://jquery.com">jQuery</a>
				<a href="http://rise4fun.com/z3">Z3 SMT Solver</a>
			</p>
			<input type="button" value="OK" id="OKBtn"
				onclick="$('#aboutDlg').jqxWindow('close');">
		</div>
	</div>

</body>
</html>
