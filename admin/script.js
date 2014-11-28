
var files;
var currentPostTags = new Array();
var newPostTags = new Array();
function ShowPanel(panelName)
{
	$("div.main>div>section").removeClass("show");
	$("aside>ul>li").removeClass("panelSelected");
	$("#"+panelName).addClass("show");
}
function editPost()
{
	$.ajax('getPost.php?id='+postId).done(function(result){
		$('#title').val(result['title']);
		var content = result['content'];

		content = content.replace('__IMAGES__', "content/images");

		tinyMCE.activeEditor.setContent(content);
		$("#postSectionTitle").html("Edit Post #"+postId);

		currentPostTags = new Array();
		newPostTags = new Array();
		$("#tagSelected").empty();
		$.ajax('getTags.php?id='+postId).done(function(result){
			if(result)
			{
				for(var i = 0; i < result.length; i++)
				{
					addTag(result[i]['name']);
					currentPostTags.push(result[i]['name']);
				}
			}
		});
	});
}
function deletePost()
{
	$.ajax('deletePost.php?id='+$(this).attr('data')).done(function(result){
		$("tr[data_id="+result+"]").remove();
		if(postId == result)
		{
			$("#newPostForm")[0].reset();
			$("#postSectionTitle").html("New Post");
		}
	});
}
function sendTag(tagName)
{
	if(tagName)
	{
		$.ajax('sendTag.php?name='+tagName).done(function(result){
			if(result)
			{
				$("#tagAvailable").append('<a class="tag">'+result+"</a> ");
				$("#addTagForm")[0].reset();
				addTag(result);
			}
		});
	}
}
function deleteTag()
{
	$.ajax('deletePost.php?id='+$(this).attr('data')).done(function(result){
		$("tr[data_id="+result+"]").remove();
		if(postId == result)
		{
			$("#newPostForm")[0].reset();
			$("#postSectionTitle").html("New Post");
		}
	});
}
function addTag(tagName)
{
	if(newPostTags.indexOf(tagName) < 0)
	{
		newPostTags.push(tagName);
		$("#tagSelected").append('<a data='+tagName+' class="tag">'+tagName+"</a> ");
		$("#tagSelected>[data="+tagName+"]").on('click', removeTag);
	}
}
function removeTag()
{
	var tagName = $(this).html();
	newPostTags.splice(newPostTags.indexOf(tagName), 1);
	$(this).remove();
}
$(function(){

	//tinyMCE
	tinymce.init({
		selector:'#tinyMCE',
		plugins: [
			"advlist autolink autosave link image lists charmap preview hr anchor pagebreak",
			"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime nonbreaking",
			"table contextmenu directionality emoticons template textcolor paste textcolor colorpicker textpattern"
		],

		toolbar1: "newdocument | cut copy paste searchreplace | undo redo | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | link unlink anchor image code",
		toolbar2: "bullist numlist | outdent indent blockquote | insertdatetime preview | forecolor backcolor | styleselect formatselect fontselect fontsizeselect",
		toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | fullscreen | ltr rtl | visualchars visualblocks nonbreaking pagebreak restoredraft",

		menubar: false,
		toolbar_items_size: 'small',

		min_height : 200,
		max_height : 500,
		height : 200,
		setup : function(ed) {
			ed.on('init', function(ed) {
				if(postId > 0)
				{
					editPost();
				}
			});
		}
	});

	//menu
	$("aside>ul>li").on("click", function(e){
		var panelName = $(this).html();
		ShowPanel(panelName);
		$(this).addClass("panelSelected");
	});

	//posts
	$("#submitPost").on("click", function(e)
	{
		if(postId > 0)
		{
			var title = $('#title').val();
			var content = tinyMCE.activeEditor.getContent();
			content = content.replace("content/images", '__IMAGES__');
			$("tr[data_id="+postId+"]>td:nth-child(2)").html(title);
			console.log('update');
			$.ajax('sendPost.php?id=' + postId, {method: 'post', data: {'title': title, 'content':  content}}).done(function(result){
				console.log('update tags');
				var tags = new Object();
				tags.toAdd = new Array();
				tags.toDelete = new Array();
				for(var i = 0; i < newPostTags.length; i++)
				{
					if(currentPostTags.indexOf(newPostTags[i])<0)
					{
						tags.toAdd.push(newPostTags[i]);
					}
				}
				for(var i = 0; i < currentPostTags.length; i++)
				{
					if(newPostTags.indexOf(currentPostTags[i])<0)
					{
						tags.toDelete.push(currentPostTags[i]);
					}
				}
				$.ajax('editTagRelationship.php?id=' + postId, {method: 'post', data: {'tags': JSON.stringify(tags)}
				});
			});
		}
		else
		{
			var title = $('#title').val();
			var content = tinyMCE.activeEditor.getContent();
			content = content.replace("content/images", '__IMAGES__');
			console.log('add');
			$.ajax('sendPost.php', {method: 'post', data: {'title': title, 'content':  content}}).done(function(result){
				alert(result['message']);
				$("#postSectionTitle").html("Edit Post #"+result['postID']);
				$.ajax('getPost.php?id='+result['postID']).done(function(result){
					var tags = new Array();
					tags['toAdd'] = new Array();
					//newPostTags;
					$.ajax('editTagRelationship.php?id='+result['postID'], {method: 'POST', data: {'tags': JSON.stringify(tags)}});
					$("#postsTable>tbody").prepend('<tr data_id="'+result['id']+'"><td>'+result['id']+'</td><td>'+result['title']+'</td><td>'+result['pseudo']+'</td><td>'+result['date']+'</td><td><button class="edit" type="button" data="'+result['id']+'">Edit</button></td><td><button class="delete" type="button" data="'+result['id']+'">X</button></td></tr>');
					$("button.edit[data="+result['id']+"]").on("click", editPost);
					$("button.delete[data="+result['id']+"]").on("click", deletePost);
				});
			});
		}
	});
	$("button.edit").on("click", function(e){
		postId = $(this).attr('data');
		editPost();
	});
	$("button.delete").on("click", deletePost);
	$("#newPost").on("click", function(e)
	{
		postId = 0;
		tinyMCE.activeEditor.setContent('');
		$("#postSectionTitle").html("New Post");
		$('#title').val('');
	});
	if(postId > 0)
	{
		$("#postSectionTitle").html("Edit Post #"+postId);
	}
	else
	{
		$("#postSectionTitle").html("New Post");
	}
	//templates
	$(".template").on("click", function(e){
		$(".template").removeClass("selected");
		$(this).addClass("selected");
		$.ajax('changeTemplate.php?templateName='+$("p:first-child", $(this)).html()).done(function(result){
		});
	});
	//images 
	function prepareUpload(event)
	{
		files = event.target.files;
	} 
	$('input[type=file]').on('change', prepareUpload);
	 
	$("#uploadImageForm").submit(function(event) {

		event.preventDefault();
		if(files)
		{
			var data = new FormData();
			data.append('file', files[0]);
			$("#uploadButton").html("Uploading...");
			$("#uploadButton").attr("disabled", "");
			$.ajax('uploadImage.php', {
				type: 'POST',
				data: data,
				cache: false,
				dataType: 'json',
				processData: false,
				contentType: false
			}).done(function(result){
				$(".imageSelect, #Images>div").append('<img src="content/images/'+result+'"/>');
				$("#uploadImageForm")[0].reset();
				$("#uploadButton").html("Envoyer");
				$("#uploadButton").removeAttr("disabled");
			});
		}
	});
	$("#uploadImageForm2").submit(function(event) {

		event.preventDefault();
		if(files)
		{
			var data = new FormData();
			data.append('file', files[0]);
			$("#uploadButton2").html("Uploading...");
			$("#uploadButton2").attr("disabled", "");
			$.ajax('uploadImage.php', {
				type: 'POST',
				data: data,
				cache: false,
				dataType: 'json',
				processData: false,
				contentType: false
			}).done(function(result){
				$(".imageSelect, #Images>div").append('<img src="content/images/'+result+'"/>');
				$("#uploadImageForm2")[0].reset();
				$("#uploadButton2").html("Envoyer");
				$("#uploadButton2").removeAttr("disabled");
			});
		}
	});
	// tags
	$("#sendTag").on("click", function(){
		sendTag($("#tagName").val());
	});
	$("#sendTag").on("click", function(){
		sendTag($("#tagName").val());
	});
	$("#tagAvailable>.tag").on("click", function()
	{
		addTag($(this).html());
	});
});
