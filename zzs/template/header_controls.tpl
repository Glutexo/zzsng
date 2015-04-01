<?tpl run ?>
<form method="GET" id="language_form">
	<table>
		<tbody>
			<tr>
				<th>{{$LANG['user']}}:</th>
				<td>{{LOGIN}}</td>
			</tr>
			<tr>
				<th>{{$LANG['language']}}:</th>
				<td>
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
				</td>
			</tr>
		</tbody>
	</table>
</form>
<?tpl end ?>