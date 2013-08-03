<?tpl run ?>
	<div class="form">
		<form action="index.php?section=lessons" method="POST">
			<div><h2>{{$LANG['add_lesson']}}</h2></div>
			<div>
				<table>
					<tr>
						<td>{{$LANG['lesson_name']}}</td>
						<td><input<?tpl if just $LESSON_TO_EDIT ?><?tpl else ?> accesskey="a"<?tpl end ?> type="text" class="long_text" name="name" /></td>
					</tr>
					<tr>
						<td>{{$LANG['language']}}:</td>
						<td>
							<select<?tpl if just $LESSON_TO_EDIT ?><?tpl else ?> accesskey="s"<?tpl end ?> name="language">
								<?tpl each $LANGUAGES ?>
									<option value="{{$LANGUAGES[$@k]['id']}}"<?tpl if just $LANGUAGES[$@k]['default'] ?> selected="selected"<?tpl end ?>>{{$LANGUAGES[$@k]['name']}}</option>
								<?tpl end ?>
							</select>
						</td>
					</tr>
					<tr><td colspan="2" class="submit">
						<input<?tpl if just $LESSON_TO_EDIT ?><?tpl else ?> accesskey="z"<?tpl end ?> type="submit" name="add" value="{{$LANG['add']}}" />
					</td></tr>
				</table>
			</div>
		</form>
	</div>
<?tpl end ?>