<?tpl run ?>
	<div class="form">
		<form action="index.php?section=terms" method="POST">
			<div><h2>{{$LANG['add_term']}}</h2></div>
			<div>
				<table>
					<tr><td>{{$LANG['term']}}:</td><td><input class="long_text"<?tpl if just $TERM_TO_EDIT ?><?tpl else ?> accesskey="a"<?tpl end ?> type="text" name="term" /></td></tr>
					<tr><td>{{$LANG['metadata']}}:</td><td><input class="long_text"<?tpl if just $TERM_TO_EDIT ?><?tpl else ?> accesskey="s"<?tpl end ?> type="text" name="metadata" /></td></tr>
					<tr><td>{{$LANG['translation']}}:</td><td><input class="long_text"<?tpl if just $TERM_TO_EDIT ?><?tpl else ?> accesskey="d"<?tpl end ?> type="text" name="translation" /></td></tr>
					<tr><td>{{$LANG['comment']}}:</td><td><textarea class="long_text"<?tpl if just $TERM_TO_EDIT ?><?tpl else ?> accesskey="f"<?tpl end ?> name="comment"></textarea></td></tr>
					<tr><td colspan="2" class="submit">
						<input type="hidden" name="lesson" value="{{LESSON_ID}}" />
						<input<?tpl if just $TERM_TO_EDIT ?><?tpl else ?> accesskey="z"<?tpl end ?> type="submit" name="add" value="{{$LANG['add']}}" />
					</td></tr>
				</table>
			</div>
		</form>
	</div>
<?tpl end ?>