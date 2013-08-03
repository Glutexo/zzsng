<?tpl run ?>
	<div class="form">
		<form action="index.php?section=lessons" method="POST">
			<div><h2>{{EDIT_TITLE}}</h2></div>
			<div>
				<table>
					<tr>
						<td>{{$LANG['lesson_name']}}:</td>
						<td><input accesskey="a" class="long_text" type="text" name="name" value="{{$LESSON_TO_EDIT['name']}}" /></td>
					</tr>
					<tr>
						<td>{{$LANG['language']}}:</td>
						<td>
							<select accesskey="s" name="language">
								<?tpl each $LANGUAGES ?>
									<option value="{{$LANGUAGES[$@k]['id']}}"<?tpl if eq $LANGUAGES[$@k]['id'] $LESSON_TO_EDIT['language'] ?> selected="selected"<?tpl end ?>>{{$LANGUAGES[$@k]['name']}}</option>
								<?tpl end ?>
							</select>
						</td>
					</tr>
					<tr><td colspan="2" class="submit">
						<input type="hidden" name="done" value="1" />
						<input accesskey="z" type="submit" name="edit[{{$LESSON_TO_EDIT['id']}}]" value="{{$LANG['edit']}}" />
					</td></tr>
				</table>
			</div>
		</form>
	</div>
<?tpl end ?>