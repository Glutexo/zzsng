<?tpl run ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{{$LANG['lang']}}">
  <head>
    <title>{{PROJECT_NAME}}</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-language" content="{{$LANG['lang']}}" />
    <meta http-equiv="author" content="Glutexo" />
    <link rel="stylesheet" type="text/css" href="{{TEMPLATE_DIR}}zzsng.css">
    <script type="text/javascript" src="{{TEMPLATE_DIR}}jquery.min.js"></script>
    <script type="text/javascript" src="{{TEMPLATE_DIR}}helpers.js"></script>
    <script type="text/javascript" src="{{TEMPLATE_DIR}}form2json.js"></script>
  </head>
  <body>
  <div id="header">
    <h1>{{PROJECT_NAME}}</h1>
    <div id="login">{{LOGIN}}</div>
  </div>
<?tpl end ?>