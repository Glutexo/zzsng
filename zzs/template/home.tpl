<?tpl run ?>
<div class="form">
	<div>
        <h2>{{$LANG['examination']}}</h2>
		<form action="?section=exam" method="POST">
            <h3>{{$LANG['exam_lesson']}}</h3>
            <div>
                <select name="lesson[]" multiple="multiple" size="20">
                <?tpl each $LESSONS ?>
                    <option value="{{$LESSONS[$@k]['id']}}">{{$LESSONS[$@k]['name']}} ({{$LESSONS[$@k]['language']}})</option>
                <?tpl end ?>
                </select>
            </div>
            <div>
              <input type="checkbox" id="invert" name="invert" value="1" />
              <label for="invert">{{$LANG['invert']}}</label>
            </div>

            <h3>{{$LANG['order']}}</h3>
            <div>
                <input type="radio" id="random_1" name="random" value="1" checked="checked" />
                <label for="random_1">{{$LANG['random']}}</label>
            </div>
            <div>
                <input type="radio" id="random_0" name="random" value="0" />
                <label for="random_0">{{$LANG['sequence']}}</label>
            </div>

            <h3>{{$LANG['action']}}</h3>
            <div>
                <input type="submit" name="run" value="{{$LANG['start_exam']}}" />
            </div>
		</form>
	</div>
</div>
<?tpl end ?>