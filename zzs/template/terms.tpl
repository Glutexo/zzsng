<?tpl run ?>
	<?tpl if just $TERM_TO_EDIT ?>
		<?tpl include 'terms_edit.tpl' ?>
	<?tpl end ?>
	<?tpl if just $TERMS ?>
		<?tpl include 'terms_list.tpl' ?>
	<?tpl end ?>
	<?tpl if just $LESSON_ID ?>
		<?tpl include 'terms_add.tpl' ?>
	<?tpl end ?>
<?tpl end ?>