<?tpl run ?>
	<div class="form">
		<form enctype="multipart/form-data" action="index.php" method="POST">
			<div><h2>{{$LANG['bulk_title']}}</h2></div>
			<div>
				<table>
                    <tr><td colspan="2">
                        <input type="radio" name="create_new_lesson" value="1" id="create_new_lesson_1" checked="checked" />
                        <label for="create_new_lesson_1">{{$LANG['create_new_lesson']}}</label>
                        <input type="radio" name="create_new_lesson" value="0" id="create_new_lesson_0" />
                        <label for="create_new_lesson_0">{{$LANG['insert_to_existing_lesson']}}</label>
                    </td></tr>
					<tr class="create_new_lesson_1"><td>{{$LANG['new_lesson_name']}}:</td><td><input type="text" name="name" class="long_text" /></td></tr>
                    <tr class="create_new_lesson_0">
                        <td>{{$LANG['existing_lesson']}}:</td>
                        <td>
                            <select name="lesson" disabled="disabled">
                                <?tpl each $LESSONS ?>
                                <option value="{{$LESSONS[$@k]['id']}}">{{$LESSONS[$@k]['name']}} ({{$LESSONS[$@k]['language']}})</option>
                                <?tpl end ?>
                            </select>
                        </td>
                    </tr>
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
					<tr>
                        <td>{{$LANG['data']}}:</td>
                        <td><textarea class="data" name="data"></textarea></td>
                    </tr>
					<tr><td colspan="2" class="submit">
						<input type="hidden" name="section" value="import" />
						<input type="submit" name="import" value="{{$LANG['start_import']}}" />
					</td></tr>
				</table>
			</div>
		</form>
	</div>
<script type="text/javascript" src="zzs/template/bulk.js"></script>
<?tpl end ?>