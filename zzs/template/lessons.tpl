<?tpl run ?>
	<?tpl if just $LESSON_TO_EDIT ?>
		<?tpl include 'lessons_edit.tpl' ?>
	<?tpl end ?>
	<?tpl if just $LESSONS ?>
		<?tpl include 'lessons_list.tpl' ?>
	<?tpl end ?>
	<?tpl include 'lessons_add.tpl' ?>
<?tpl end ?>