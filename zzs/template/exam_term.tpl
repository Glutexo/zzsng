<?tpl run ?>
<div class="form">
	<div><h2>{{TITLE}}</div>
	<div>
		<form action="?section=exam" method="POST">
			<table>
				<tr><td>{{$LANG['term']}}:</td><td><span class="term">{{$TERM['term']}}</span></td></tr>
				<?tpl if just $TERM['metadata'] ?>
					<tr><td>{{$LANG['metadata']}}:</td><td><span class="term">{{$TERM['metadata']}}</span></td></tr>
				<?tpl end ?>
				<?tpl if just $TERM['translation'] ?>
					<tr><td>{{$LANG['translation']}}:</td><td><span class="term">{{$TERM['translation']}}</span></td></tr>
				<?tpl end ?>
				<?tpl if just $TERM['comment'] ?>
					<tr><td>{{$LANG['comment']}}:</td><td><span class="term">{{$TERM['comment']}}</span></td></tr>
				<?tpl end ?>
				<tr><td colspan="2" class="submit">
					<?tpl if just $LESSON_A ?><input type="hidden" name="lesson" value="{{$LESSON_A['id']}}" /><?tpl end ?>
					<input type="hidden" name="term" value="{{$TERM['id']}}" />
					<?tpl if just $REVEAL ?>
						<input type="submit" accesskey="z" name="next[hit]" value="{{$LANG['knew']}}" />
						<input type="submit" accesskey="x" name="next[mistake]" value="{{$LANG['did_not_know']}}" />
					<?tpl else ?>
						<input type="submit" accesskey="z" name="reveal" value="{{$LANG['reveal']}}" />
					<?tpl end ?>
				</td></tr>
			</table>
		</form>
	</div>
</div>
<?tpl end ?>