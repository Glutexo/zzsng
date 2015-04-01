<?tpl run ?>
		<div id="footer">
			<?tpl if just $LINKS ?>
			::
			<?tpl each $LINKS ?>
				<a href="{{$LINKS[$@k]['href']}}" style="{{$LINKS[$@k]['style']}}">{{$LINKS[$@k]['text']}}</a> ::
			<?tpl end ?>
			<?tpl end ?>
		</div>
	</body>
</html>
<?tpl end ?>