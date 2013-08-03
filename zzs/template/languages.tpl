<?tpl run ?>
	<?tpl if just $LANGUAGE_TO_EDIT ?>
		<?tpl include 'languages_edit.tpl' ?>
	<?tpl end ?>
	<?tpl if just $LANGUAGES ?>
		<?tpl include 'languages_list.tpl' ?>
	<?tpl end ?>
	<?tpl include 'languages_add.tpl' ?>
<?tpl end ?>