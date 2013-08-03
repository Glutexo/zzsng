<?tpl run ?>
	<div class="form">
		<form action="index.php?section=languages" method="POST">
			<div><h2>{{EDIT_TITLE}}</h2></div>
			<div>
				{{$LANG['name']}}: <input type="text" name="name" />
				<input type="hidden" name="done" value="1" />
				<input type="submit" name="edit[{{$LANGUAGE_TO_EDIT['id']}}]" value="{{$LANG['edit']}}" />
			</div>
		</form>
	</div>
<?tpl end ?>