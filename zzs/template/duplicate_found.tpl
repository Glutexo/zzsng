<?tpl run ?>
{{MESSAGE}}
<form class="inline" action="?section=terms#term_{{TERM_ID}}" method="POST">
	<input type="hidden" name="lesson" value="{{LESSON_ID}}" />
	<input type="submit" value="{{$LANG['show']}}" />
</form>
<?tpl end ?>