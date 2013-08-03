<?tpl run ?>
	<div class="form">
		<form action="index.php?section=terms" method="POST">
			<div><h2>{{EDIT_TITLE}}</h2></div>
			<div>
				<table>
					<tr><td>{{$LANG['term']}}:</td><td><input class="long_text" accesskey="a" type="text" name="term" value="{{$TERM_TO_EDIT['term']}}" /></td></tr>
					<tr><td>{{$LANG['metadata']}}:</td><td><input class="long_text" accesskey="s" type="text" name="metadata" value="{{$TERM_TO_EDIT['metadata']}}" /></td></tr>
					<tr><td>{{$LANG['translation']}}:</td><td><input class="long_text" accesskey="d" type="text" name="translation" value="{{$TERM_TO_EDIT['translation']}}" /></td></tr>
					<tr><td>{{$LANG['comment']}}:</td><td><textarea class="long_text" accesskey="f" name="comment">{{$TERM_TO_EDIT['comment']}}</textarea></td></tr>
					<tr><td class="submit" colspan="2">
						<input type="hidden" name="done" value="1" />
						<input type="hidden" name="lesson" value="{{LESSON_ID}}" />
						<input type="submit" accesskey="z" name="edit[{{$TERM_TO_EDIT['id']}}]" value="{{$LANG['edit']}}" />
					</td></tr>
				</table>
			</div>
		</form>
	</div>
<?tpl end ?>