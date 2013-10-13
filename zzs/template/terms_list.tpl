<?tpl run ?>
	<div><h2>{{LIST_TITLE}}</h2></div>
	<div>
		<form action="index.php?section=terms" method="POST">
			<input type="hidden" name="lesson" value="{{LESSON_ID}}" />

			<table>
			<thead>
				<th><input type="submit" name="sort[order]" value="{{$LANG['order']}}" title="{{$LANG['sort_by_order']}}" /></th>
                <th><input type="submit" name="sort[term]" value="{{$LANG['term']}}" title="{{$LANG['sort_by_term']}}" /></th>
				<th>{{$LANG['metadata']}}</th>
				<th>{{$LANG['translation']}}</th>
				<th>{{$LANG['comment']}}</th>
				<th>&nbsp;</th>
			</thead>
			<tbody>
			<?tpl each $TERMS ?>
				<tr id="term_{{$TERMS[$@k]['id']}}">
					<td class="selection unimplemented" style="display: none;">
						<input type="checkbox" id="selection_{{$TERMS[$@k]['id']}}" name="selection[{{$TERMS[$@k]['id']}}][]" value="selected" />
					</td>
                    <td class="short_text order">
						<input type="text" class="small_number" name="order[{{$TERMS[$@k]['id']}}]" value="{{$TERMS[$@k]['order']}}" />.
					</td>
					<td class="short_text term">
						<label for="selection_{{$TERMS[$@k]['id']}}">{{$TERMS[$@k]['term']}}</label>
					</td>
					<td class="short_text metadata">{{TERMS[$@k]['metadata']}}</td>
					<td class="short_text translation">{{$TERMS[$@k]['translation']}}</td>
					<td class="long_text comment">{{$TERMS[$@k]['comment']}}</td>
					<td class="action">
						<input type="submit" name="edit[{{$TERMS[$@k]['id']}}]" value="{{$LANG['edit']}}" />
					</td>
					<td class="action">
						<input type="submit" name="delete[{{$TERMS[$@k]['id']}}]" value="{{$LANG['delete']}}" />
					</td>
				</tr>
			<?tpl end ?>
			</tbody>
			<tfoot>
				<tr>
					<td style="display: none;">&nbsp;</td>
					<td>
						<input type="submit" name="save_order" value="Uložit pořadí" />
					</td>
					<td colspan="6">&nbsp;</td>
				</tr>
			</tfoot>
			</table>
		</form>
	</div>
<?tpl end ?>