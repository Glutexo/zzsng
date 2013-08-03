<?tpl run ?>
	<div class="form">
		<form action="index.php?section=languages" method="POST">
			<div><h2>{{$LANG['add_language']}}</h2></div>
			<div>
				{{$LANG['name']}}: <input type="text" name="name" />
				<input type="submit" name="add" value="{{$LANG['add']}}" />
			</div>
		</form>
	</div>
<?tpl end ?>