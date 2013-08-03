<?tpl run ?>
	<div><h2>{{$LANG['language_list']}}</h2></div>
	<div>
		<form action="index.php?section=languages" method="POST">
			<table>
			<thead>
				<tr>
					<th>{{$LANG['name']}}</th>
					<th>{{$LANG['lesson_count']}}</th>
					<th colspan="3">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
			<?tpl each $LANGUAGES ?>
				<tr>
					<td>
						{{$LANGUAGES[$@k]['name']}}
						<?tpl if just $LANGUAGES[$@k]['default'] ?>
							({{$LANG['default_language']}})
						<?tpl end ?>
					</td>
					<td class="number">{{$LANGUAGES[$@k]['lesson_count']}}</td>
					<td class="control"><input type="submit" name="edit[{{$LANGUAGES[$@k]['id']}}]" value="{{$LANG['edit']}}" <?tpl if eq $LANGUAGES[$@k]['id'] 0 ?>disabled="disabled" <?tpl end ?>/></td>
					<td class="control"><input type="submit" name="delete[{{$LANGUAGES[$@k]['id']}}]" value="{{$LANG['delete']}}" <?tpl if eq $LANGUAGES[$@k]['id'] 0 ?>disabled="disabled" <?tpl end ?>/></td>
					<td class="control"><input type="submit" name="set_default[{{$LANGUAGES[$@k]['id']}}]" value="{{$LANG['set_as_default']}}" <?tpl if just $LANGUAGES[$@k]['default'] ?>disabled="disabled" <?tpl end ?>/></td>
				</tr>
			<?tpl end ?>
			</tbody>
			</table>
		</form>
	</div>
<?tpl end ?>