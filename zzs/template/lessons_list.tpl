<?tpl run ?>
	<div><h2>{{$LANG['lesson_list']}}</h2></div>
	<div>
			<table>
			<thead>
				<th>{{$LANG['name']}}</th>
				<th>
                    {{$LANG['language']}}
                    <form method="GET" id="language_form">
                        <input type="hidden" name="section" value="{{SECTION}}" />
                        <select name="language" onchange="document.getElementById('language_form').submit();">
                        <?tpl each $LANGUAGES ?>
                            <?tpl if eq $LANGUAGE $LANGUAGES[$@k]['id'] ?>
                            <option value="{{$LANGUAGES[$@k]['id']}}" selected="selected">{{$LANGUAGES[$@k]['name']}}</option>
                            <?tpl else ?>
                            <option value="{{$LANGUAGES[$@k]['id']}}">{{$LANGUAGES[$@k]['name']}}</option>
                            <?tpl end ?>
                        <?tpl end ?>
                        </select>
                    </form>
                </th>
				<th>{{$LANG['term_count']}}</th>
				<th colspan="2">&nbsp;</th>
			</thead>
			<tbody>
			<?tpl each $LESSONS ?>
				<tr>
					<td class="selection unimplemented" style="display: none;">
						<input type="checkbox" id="selection_{{$LESSONS[$@k]['id']}}" name="selection[{{$LESSONS[$@k]['id']}}][]" value="selected" />
					</td>
					<td class="short_text name">
						<label for="selection_{{$LESSONS[$@k]['id']}}">{{$LESSONS[$@k]['name']}}</label>
					</td>
					<td>{{$LESSONS[$@k]['language']}}</td>
					<td class="number">{{$LESSONS[$@k]['term_count']}}</td>
					<td class="action"><form action="index.php?section=lessons" method="POST">
						<input type="submit" name="edit[{{$LESSONS[$@k]['id']}}]" value="{{$LANG['edit']}}" />
					</form></td>
					<td class="action"><form action="index.php?section=lessons" method="POST">
						<input type="submit" name="delete[{{$LESSONS[$@k]['id']}}]" value="{{$LANG['delete']}}" />
					</form></td>
					<td class="action"><form action="index.php?section=lessons" method="POST">
						<input type="submit" name="duplicate[{{$LESSONS[$@k]['id']}}]" value="{{$LANG['duplicate']}}" />
					</form></td>
					<td class="action"><form action="index.php?section=terms" method="POST">
						<input type="hidden" name="lesson" value="{{$LESSONS[$@k]['id']}}" />
						<input type="submit" value="{{$LANG['terms']}}" />
					</form></td>
				</tr>
			<?tpl end ?>
			</tbody>
			</table>
		</form>
	</div>	
	<div class="unimplemented" style="display: none;">
		<div><h2>{{$LANG['selected_items_action']}}</h2></div>
		<div>
			<input type="submit" name="" value="{{$LANG['edit']}}" />
			<input type="submit" name="" value="{{$LANG['delete']}}" />
			<input type="submit" name="" value="{{$LANG['duplicate']}}" />
			<input type="submit" name="" value="{{$LANG['terms']}}" />
		</div>
	</div>
<?tpl end ?>