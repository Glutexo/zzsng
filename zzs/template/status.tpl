<?tpl run ?>
	<?tpl if just $NOTICE ?>
		<?tpl each $NOTICE ?><div class="notice">{{$NOTICE[$@k]}}</div><?tpl end ?>
	<?tpl end ?>
	<?tpl if just $WARNING ?>
		<?tpl each $WARNING ?><div class="warning">{{$WARNING[$@k]}}</div><?tpl end ?>
	<?tpl end ?>
	<?tpl if just $ERROR ?>
		<?tpl each $ERROR ?><div class="error">{{$ERROR[$@k]}}</div><?tpl end ?>
	<?tpl end ?>
<?tpl end ?>