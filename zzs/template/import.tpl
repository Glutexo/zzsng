<?tpl run ?>
	<div class="form">
		<form enctype="multipart/form-data" action="index.php" method="POST">
			<div><h2>{{$LANG['import_title']}}</h2></div>
			<div>
				<table>
					<tr><td>{{$LANG['lesson_name']}}:</td><td><input type="text" name="name" /></td></tr>
					<tr>
						<td>{{$LANG['language']}}:</td>
						<td>
							<select name="language">
								<?tpl each $LANGUAGES ?>
									<option value="{{$LANGUAGES[$@k]['id']}}"<?tpl if just $LANGUAGES[$@k]['default'] ?> selected="selected"<?tpl end ?>>{{$LANGUAGES[$@k]['name']}}</option>
								<?tpl end ?>
							</select>
						</td>
					</tr>
					<tr><td>{{$LANG['import_file']}}:</td><td><input type="file" name="file" /></td></tr>
					<tr><td colspan="2" class="submit">
						<input type="hidden" name="section" value="import" />
						<input type="submit" name="import" value="{{$LANG['start_import']}}" />
					</td></tr>
				</table>
			</div>
		</form>
	</div>
<?tpl end ?>