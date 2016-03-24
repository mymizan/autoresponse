<?php
	$this->assign('title','Autoresponse | Topics');
	$this->assign('nav','topics');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script("scripts/app/topics.js").wait(function(){
		$(document).ready(function(){
			page.init();
		});
		
		// hack for IE9 which may respond inconsistently with document.ready
		setTimeout(function(){
			if (!page.isInitialized) page.init();
		},1000);
	});
</script>

<div class="container">

<h1>
	<i class="icon-th-list"></i> Topics
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Search..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="topicCollectionTemplate">
		<table class="collection table table-bordered table-hover">
		<thead>
			<tr>
				<th id="header_Id">Id<% if (page.orderBy == 'Id') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Keyword">Keyword<% if (page.orderBy == 'Keyword') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Reply">Reply<% if (page.orderBy == 'Reply') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_TotalHits">Total Hits<% if (page.orderBy == 'TotalHits') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('id')) %>">
				<td><%= _.escape(item.get('id') || '') %></td>
				<td><%= _.escape(item.get('keyword') || '') %></td>
				<td><%= _.escape(item.get('reply') || '') %></td>
				<td><%= _.escape(item.get('totalHits') || '') %></td>
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="topicModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="idInputContainer" class="control-group">
					<label class="control-label" for="id">Id</label>
					<div class="controls inline-inputs">
						<span class="input-xlarge uneditable-input" id="id"><%= _.escape(item.get('id') || '') %></span>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="keywordInputContainer" class="control-group">
					<label class="control-label" for="keyword">Keyword</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="keyword" placeholder="Keyword" value="<%= _.escape(item.get('keyword') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="replyInputContainer" class="control-group">
					<label class="control-label" for="reply">Reply</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="reply" placeholder="Reply" value="<%= _.escape(item.get('reply') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="totalHitsInputContainer" class="control-group">
					<label class="control-label" for="totalHits">Total Hits</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="totalHits" placeholder="Total Hits" value="<%= _.escape(item.get('totalHits') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deleteTopicButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deleteTopicButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete Topic</button>
						<span id="confirmDeleteTopicContainer" class="hide">
							<button id="cancelDeleteTopicButton" class="btn btn-mini">Cancel</button>
							<button id="confirmDeleteTopicButton" class="btn btn-mini btn-danger">Confirm</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="topicDetailDialog">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icon-edit"></i> Edit Topic
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="topicModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" >Cancel</button>
			<button id="saveTopicButton" class="btn btn-primary">Save Changes</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="topicCollectionContainer" class="collectionContainer">
	</div>

	<p id="newButtonContainer" class="buttonContainer">
		<button id="newTopicButton" class="btn btn-primary">Add Topic</button>
	</p>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
