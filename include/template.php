<?php
/* ------------------------------------------------------------------------- *\
            Template.php version 0.7 - Template system for PHP4
** ------------------------------------------------------------------------- **
   Author:  Michal "Wejn" Safranek <wejn@svamberk.net>
   Date:    20020629212100
   
   I will appreciate any suggestions, improvements, bugreports.
** ------------------------------------------------------------------------- **
   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License version 2
   as published by the Free Software Foundation.
   
   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.
   
   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
\* ------------------------------------------------------------------------- */

if(! isset($GLOBALS["__template_php_cee03356354b3bc6c844ddf78ece2bff__"])):
$GLOBALS["__template_php_cee03356354b3bc6c844ddf78ece2bff__"] = 'loaded';

define("TemplateMagicValue", "cee03356354b3bc6c844ddf78ece2bff");

/* Main class - used for Template manipulation(s) */
class Template{

/* ------------------------------------------------------------------------- *\
   This part of variables may be modified at your will.
   Only a few things you have to ensure in order to keep Template
   working properly.
   1) opentag & closetag MUST be carefully chosen to be unique
      in whole Template file. Otherwise it will crew your Template
	  completely, yielding strangle parsing errors, strange error
	  contexts. (known bug: '<?tpl X <?tpl' == '<?tpl X ?>')
   2) openvar & closevar MUST be unique in whole Template file
      AND must differ from opentag, closetag.
\* ------------------------------------------------------------------------- */

var $opentag = "<?tpl"; // opening tag
var $closetag = "?>"; // closing tag
var $openvar = "{{"; // var pfx
var $closevar = "}}"; // var sfx
var $undefined_subst = ""; // subst. for undefined variables
var $hard_max_loops = 1000; // maximum iterations per loop
var $fail_if_include_fails = true; // fail execute() when tpl-include fails?
var $usecache = true; // use caching
var $cachedir = "cache/"; // where to put compiled templates
// var $includedir (see __construct) // where are templates located
var $include_try_cwd_first = false; // try to open template from cwd first
var $include_try_cwd_last = false; // try to open template from cwd last
var $max_include_recursion = 10; // at most 10 levels of include recursion ...

/* ------------------------------------------------------------------------- *\
   STOP! STOP! STOP! STOP! STOP! STOP! STOP! STOP! STOP! STOP! STOP! STOP!
** ------------------------------------------------------------------------- **

   Do NOT try to modify code below this line unless you exactly
   know what you're doing!

** ------------------------------------------------------------------------- **
   STOP! STOP! STOP! STOP! STOP! STOP! STOP! STOP! STOP! STOP! STOP! STOP!
\* ------------------------------------------------------------------------- */

var $raw = ""; // raw content of the file
var $parsed = array(); // parsed content of the file
var $errors = array(); // errors during parse
var $errorctx = array(); // error context - text after error
var $preparsed = ""; // preparsed tree, stored in eval()-friendly form
var $output = ""; // executed output from the template
var $b_load = false; // loaded?
var $b_parsed = false; // parsed?
var $variables = array(); // variables registered in this template
var $_operators = array(
	'eq' => '$z = ($x == $y);', 'neq' => '$z = ($x != $y);',
	'lt' => '$z = ($x < $y);', 'gt' => '$z = ($x > $y);',
	'le' => '$z = ($x <= $y);', 'ge' => '$z = ($x >= $y);',
	'odd' => '$z = ($x % 2);', 'even' => '$z = !($x % 2);',
	'ismod' => '$z = !($x % $y);', 'isnmod' => '$z = ($x % $y);',
	/* arithmetic ops: */
	'+' => '$z = $x + $y;', '-' => '$z = $x - $y;',
	'*' => '$z = $x * $y;', '/' => '$z = $x / $y;',
	'dec' => '$z = $x - 1;', 'inc' => '$z = $x + 1;',
	'just' => '$z = $x;', 'ret' => '$z = $x;',
	'%' => '$z = $x % $y;', 'mod' => '$z = $x % $y;',
	'.' => '$z = $x . $y;', 'concat' => '$z = $x . $y;',
); // All operators and their meaning ... (internal variable)
var $_unary_operators = array(
	'odd' => 1, 'even' => 1,
	/* arithmetic unary ops: */
	'dec' => 1, 'inc' => 1, 'just' => 1, 'ret' => 1,
); // Which of them are unary? (internal variable)
var $_loop_index = 0; // Iternal variable, current loop index
var $_loop_key = 0; // Iternal variable, current each-loop key
var $_loop_val = 0; // Iternal variable, current each-loop value
var $_temp_variables = array(); // Internal variable, temporary variables

/* Glutexo: A constructor for assigning non-trivial values */

function __construct() {
	$this->includedir = master_config::APPLICATION . master_config::TEMPLATE_DIR; // where are templates located
}

/* Internal function, checks if file but NOT dir exists */
function _check_file($file){
	while(@is_link($file)){
		$file = @readlink($file);
	}
	if(@is_dir($file)) return false;
	return @is_file($file);
}

/* Internal function, checks and opens files but NOT dirs */
function _check_open($file, $mode = "r"){
	while(@is_link($file)){
		$file = @readlink($file);
	}
	if(@is_dir($file)) return false;
	return @fopen($file, $mode);
}

/* Internal function, takes care of file to absolute path translation */
function _get_real_file($file){
	$n = false;

	if($this->include_try_cwd_first && $this->_check_file($file))
		$n = $file;

	if(! $n && $this->_check_file($this->includedir . '/' . $file))
		$n = $this->includedir . '/' . $file;

	if(! $n && $this->include_try_cwd_last && $this->_check_file($file))
		$n = $file;

	return $n;
}

/* Internal function, takes care of loading file into $raw */
function _read_file($file){
	$fd = $this->_check_open($file, "r");
	if(!$fd){
		$this->raw = "";
		$this->errors[] = "Unable to open Template file!";
		return false;
	}
	$this->raw = @fread($fd, filesize($file));
	$this->preparsed = "";
	$this->output = "";
	$this->parsed = array();
	$this->b_load = true;
	$this->b_parsed = false;
	$this->errors = array();
	$this->errorctx = array();
	@fclose($fd);
	return true;
}

/* Internal function, takes care of precompiling $raw to $parsed */
function _make_tree(){
	if($this->b_parsed) return true;
	$pieces = "";
	$level = 0;
	$istag = false;
	$else = false;
	if(! $this->_split_pattern($this->raw, $pieces)) return false;
	$this->preparsed = "\$this->parsed = array(";
	$ok = $this->_process($this->preparsed, $pieces, $istag, $level, $else);
	if($ok && $level){
			$ok = false;
			$this->errors[] = "Unexpected EOF: level = $level !";
	}
	if(! $ok){
		/* Dump error context */
		$k = 5;
		while($k-- && list(, $v) = each($pieces)){
			$this->errorctx[] = $istag ? $this->opentag." ".$v." ".
				$this->closetag : $v;
			$istag = ! $istag;
		}
		return false;
	}
	$this->preparsed .= '); return true;';
	$this->raw = "";
	return true;
}

/* Internal function, splits raw into pieces */
function _split_pattern(&$what, &$to){
	/* FIXME: There's minor problem - this construction splits also
	   strings like '<?tpl X <?tpl' as if they were '<?tpl X ?>' */
	$to = preg_split("/".preg_quote($this->opentag)."[ \t]+|[ \t]+".
		preg_quote($this->closetag)."/", $what);
	return true;
}

/* Internal function, translates '::xxx::' into '<?tpl var xxx ?>' */
function _fixup_vars(&$what, &$to){
	$to = preg_replace("/".preg_quote($this->openvar)."(.*)".
		preg_quote($this->closevar)."/U", $this->opentag." var \\1 ".
		$this->closetag, $what);
	return true;
}

/* Internal function, escapes string for placing into '' */
function _escape_string($x){
	return preg_replace("/('|\\\\)/", "\\\\\\1", $x);
}

/* Internal function, verifies expression for validity ... */
function _verify_expr($expr){
	$z = preg_split('/[ \t]+/', $expr, -1, PREG_SPLIT_NO_EMPTY);
	/* Ignore 'not' operator - once */
	while($z[0] == 'not'){ array_shift($z); }
	/* No operator there? */
	if(count($z) < 1){
		$this->errors[] = "Unable to parse expresion!";
		return false;
	}
	/* Correct operator? */
	if(! isset($this->_operators[$z[0]])){
		$this->errors[] = "Invalid operator while validating expression!";
		return false;
	}
	/* Correct param count and type? */
	$unary = isset($this->_unary_operators[$z[0]]);
	if(count($z) != (($unary) ? 2 : 3)){
		$this->errors[] = "Invalid arg count to operator while validating".
			" expression!";
		return false;
	}
	if(! $this->_verify_var($z[1]) ||
			(! $unary && ! $this->_verify_var($z[2]))){
		$this->errors[] = "Passed argument(s) ain't proper variable(s)!";
		return false;
	}
	return true;
}

/* Internal function, returns constuctor for correct expression */
function _generate_expr($expr){
	$neg = "false";
	$z = preg_split('/[ \t]+/', $expr, -1, PREG_SPLIT_NO_EMPTY);
	/* Ignore 'not' operator - once */
	while($z[0] == 'not'){
		array_shift($z);
		$neg = ($neg == "false") ? "true" : "false";
	}
	return "new TplIntExpr($neg, '".$z[0]."', ".$this->_generate_var($z[1]).
		", ".$this->_generate_var(isset($z[2]) ? $z[2] : null).")";
}

/* Internal function, slices expr to pieces - parsed & unparsed */
function _slice_var($expr, &$parsed, &$unparsed){
	$regexp = "/^(".
		"[-0-9][0-9]*(\\.[0-9]+)?". // number
		"|".
		"'(\\\\(\\\\|')|[^\\\\']+)*'". // string
		"|".
		"\\$[@_a-zA-Z][a-zA-Z0-9_]*". // variable
		")(.*)$/";
	$parsed = preg_replace($regexp, "\\1", $expr);
	$unparsed = preg_replace($regexp, "\\5", $expr);
}

/* Internal function, verifies if expr is variable ... */
function _verify_var($expr){
	$var = false;
	$bracket = false;
	if(substr($expr, 0, 1) == '$'){
		$var = true;
	}
	while($expr != ''){
		$this->_slice_var($expr, $p, $expr);
		if(! $var && $expr != '') return false;
		if($bracket){
			if($expr == ']') return true;
			if(substr($expr, 0, 2) != '][') return false;
			$expr = substr($expr, 2);
		}else{
			if($expr == '') return true;
			if(substr($expr, 0, 1) != '[') return false;
			$expr = substr($expr, 1);
			$bracket = true;
		}
	}
}

/* Internal function, returns constructor for correct variable */
function _generate_var($expr){
	if(substr($expr, 0, 1) == '$'){
		$bracket = false;
		$var = "";
		$b_var = false;
		$idx = array();
		while($expr != ''){
			$this->_slice_var($expr, $p, $expr);
			if($b_var){
				$idx[] = $p;
			}else{
				$b_var = true;
				$var = $p;
			}
			if($bracket){
				if($expr == ']') break;
				$expr = substr($expr, 2);
			}else{
				if($expr == '') break;
				$expr = substr($expr, 1);
				$bracket = true;
			}
		}
		$code = "new TplIntIndirect('".substr($var, 1)."', array(";
		reset($idx);
		foreach($idx as $v){
			$code .= $this->_generate_var($v) . ", ";
		}
		$code .= "))";
		return $code;
	}else{
		return "new TplIntDirect($expr)";
	}
}

/* Internal function, takes care of recursive precompiling to $raw */
function _process(&$out, &$pieces, &$istag, &$level, &$gelse){
	$elop = "";
	$else = false;
	while(list($k, $v) = each($pieces)){
		if($istag){
			$istag = ! $istag;
			$tag = preg_replace("/[ \t].*/", "", $v);
			$args = preg_replace("/^[^ \t]*[ \t]?/", "", $v);
			switch($tag){

			/* ----------------------------------------------------------- *\
			   Variable substitution. Can be replaced with ::x:: shortcut
			   inside 'if, unless, run, while, until, each, run, loop'
			   statements.
			\* ----------------------------------------------------------- */
				case 'var':
					if(substr($args, 0, 1) != '$') $args = '$' . $args;
					if(! $this->_verify_var($args)){
						$this->errors[] = "Invalid variable!";
						return false;
					}
					$out .= "new TplVariable(".$this->_generate_var($args).
						"), ";
					break;

			/* ----------------------------------------------------------- *\
			   Variable assign. Can assing only variables with '_'
			   prepended - to avoid unauthorized modification.
			   Note: variables assigned this way are temporary for
			   current execute() call.
			\* ----------------------------------------------------------- */
				case 'bind':
					$args = $args . ' ret $@i';
				case 'set':
					$nm = preg_replace("/[ \t].*/", "", $args);
					$s_expr = preg_replace("/^[^ \t]*[ \t]?/", "", $args);
					if(substr($nm, 0, 1) == '$') $nm = substr($nm, 1);
					if(substr($nm, 0, 1) != '_'){
						$this->errors[] = "Can't assign non-volatile ".
							"variable!";
						return false;
					}
					if(! preg_match("/^[@_a-zA-Z][a-zA-Z0-9_]*$/", $nm)){
						$this->errors[] = "Invalid temp-variable!";
						return false;
					}
					if(! $this->_verify_expr($s_expr))
						return false;
					$out .= "new TplSet($nm, ".$this->_generate_expr($s_expr).
						"), ";
					break;

			/* ----------------------------------------------------------- *\
			   Run, Unless and If statement modifiers. If and Unless takes
			   (boolean) expression.
			   * Run is always executed. Purpose - variable shortcut
			     activation;
			   * If is executed when given expression renders true;
			   * Unless is executed when given expression renders false;
			\* ----------------------------------------------------------- */
				case 'run':
					if($args != ""){
						$this->errors[] = "Run has no parameters!";
						return false;
					}
					$args = "eq 0 1";
				case 'unless':
					$args = "not " . $args;
				case 'if':
					if($tag != 'run') $else = true;
					while(1){
						if(! $this->_verify_expr($args))
							return false;
						$out .= "new TplIf(".$this->_generate_expr($args).
							", array(";
						$level++;
						if(! $this->_process($out, $pieces, $istag, $level,
								$else))
							return false;
						$out .= ")), ";
						if($else){
							$else = false;
							$args = "not " . $args;
						}else{
							break;
						}
					}
					break;

			/* ----------------------------------------------------------- *\
			   Until and while loops. Both takes (boolean) expression.
			   * While loops until hardcoded limit is reached or expression
			     renders false;
			   * Until loops until hardcoded limit is reached or expression
			     renders true;
			\* ----------------------------------------------------------- */
				case 'until':
					$args = "not " . $args;
				case 'while':
					if(! $this->_verify_expr($args))
						return false;
					$out .= "new TplWhile(".$this->_generate_expr($args).
						", array(";
					$level++;
					if(! $this->_process($out, $pieces, $istag, $level, $else))
						return false;
					$out .= ")), ";
					break;
					
			/* ----------------------------------------------------------- *\
			   Each and Loop loops. Both takes var name as argument.
			   * Each iterates over all indexes of given array (or once in
			     case of scalar).
			   * Loop iterates n-times where n is integer value of given
			     variable.
			\* ----------------------------------------------------------- */
				case 'each':
					$elop = "TplEach";
				case 'loop':
					if($elop == "")
						$elop = "TplLoop";
					if(! $this->_verify_var($args)){
						$this->errors[] = "Invalid variable!";
						return false;
					}
					$out .= "new $elop(".$this->_generate_var($args).
						", array(";
					$level++;
					if(! $this->_process($out, $pieces, $istag, $level, $else))
						return false;
					$out .= ")), ";
					break;
					
			/* ----------------------------------------------------------- *\
			   Include another template. Arg is 'variable', so there's
			   possibility to include template based on variable content.
			\* ----------------------------------------------------------- */
				case 'include':
					if(! $this->_verify_var($args)){
						$this->errors[] = "Invalid variable!";
						return false;
					}
					$out .= "new TplInclude(".$this->_generate_var($args).
						"), ";
					break;
					
			/* ----------------------------------------------------------- *\
			   End of current section/block and 'else' statement for 'if'.
			\* ----------------------------------------------------------- */
				case 'else':
					if($args != ""){
						$this->errors[] = "Else has no parameters!";
						return false;
					}
					if(! $gelse){
						$this->errors[] = "Misplaced else directive!";
						return false; /* 'parse error' */
					}
					$level--;
					return true;
				case 'end':
					if($args != ""){
						$this->errors[] = "End has no parameters!";
						return false;
					}
					$gelse = false;
					if(! $level){
						$this->errors[] = "Unexpected end!";
						return false; /* 'parse error' */
					}
					$level--;
					return true;
					
			/* ----------------------------------------------------------- *\
			   Default opcode - signal failure as we don't accept bad ops.
			\* ----------------------------------------------------------- */
				default:
					$this->errors[] = "Invalid opcode: '$tag'!";
					return false;
			}
		}else{
			$istag = ! $istag;
			if($level){
			/* ----------------------------------------------------------- *\
			   Variables delimited with '::' mixed with static text.
			\* ----------------------------------------------------------- */
				$temp = "";
				$l = 0;
				$it = false;
				$e = false;
				if(! $this->_fixup_vars($v, $temp)) return false;
				if(! $this->_split_pattern($temp, $temp)) return false;
				if(! $this->_process($out, $temp, $it, $l, $e))
					return false;
			}else{
			/* ----------------------------------------------------------- *\
			   Static text, just add constructor for it.
			\* ----------------------------------------------------------- */
				$out .= "new TplStatic('".$this->_escape_string($v)."'), ";
			}
		}
	}
	return true; /* no job to do - end of template */
}

/* Internal function, takes care of cache loading ... */
function _read_cache($name){
	$n = md5($name);
	clearstatcache();
	if(! $this->_check_file($this->cachedir."/".$n)) return false;
	if(@filemtime($name) >= @filemtime($this->cachedir."/".$n)) return false;
	$this->parsed = "bleah";
	$this->parsed = @unserialize(implode("", @file($this->cachedir."/".$n)));
	if(gettype($this->parsed) != 'array') return false;
	return true;
}

/* Internal function, takes care of cache writing ... */
function _write_cache($name){
	$n = md5($name);
	$fp = $this->_check_open($this->cachedir."/".$n, "w");
	if(!$fp) return false;
	if(@fputs($fp, serialize($this->parsed)) == -1) return false;
	@fclose($fp);
	return true;
}

/* Takes care of loading and precompiling template */
function load($file){
	$file = $this->_get_real_file($file);
	if($file === false){
		$this->errors[] = "Unable to open Template file!";
		return false;
	}
	if(! $this->usecache || ! $this->_read_cache($file)){
		if(! $this->_read_file($file)) return false;
		if(! $this->_make_tree()) return false;
		if(! @eval($this->preparsed)){
			$this->errors[] = "Error evaluating parsed content!";
			return false;
		}
		if($this->usecache) $this->_write_cache($file);
	}
	$this->b_parsed = true;
	$this->preparsed = "";
	return true;
}

/* Registers template's variable */
function reg($key, $val, $nonvar = false){
	$addon = $nm = "";
	if($nonvar){
		/* Generate unique var name ... */
		$nm = TemplateMagicValue.md5($addon.$key);
		while(isset($GLOBALS[$nm])){
			$addon .= chr(rand(1, 250));
			$nm = TemplateMagicValue.md5($addon.$key);
		}
		$GLOBALS[$nm] = $val;
	}else{
		$nm = $val;
	}
	/* If we're overwriting variable created by reg(), free it! */
	if(ereg("^".TemplateMagicValue, (isset($this->variables[$key]) ? $this->variables[$key] : null))){
		unset($GLOBALS[$this->variables[$key]]);
	}
	/* Finally, register! :) */
	$this->variables[$key] = $nm;
	return true;
}

/* Test for variable existence */
function var_exists($name){
	return (isset($this->variables[$name]) &&
		isset($GLOBALS[$this->variables[$name]]));
}

/* Test for variable type */
function var_type($name){
	if(substr($name, 0, 1) == '_' && isset($this->_temp_variables[$name]))
		return "temp";
	if(! $this->var_exists($name)) return "undef";
	switch($name){
		case '@index':
		case '@i':
		case '@iteration':
		case '@k':
		case '@v':
			return "magic";
		default:
			break;
	}
	return gettype($this->lookup($name));
}

/* Perform variable lookup */
function lookup($name, $indexes = array()){
	if(substr($name, 0, 1) == '_' && isset($this->_temp_variables[$name]))
		return $this->_temp_variables[$name];
	if(!is_array($indexes)) $indexes = array();
	// Fix 'magical' variables
	switch($name){
		case '@index':
		case '@i':
		case '@iteration':
			return $this->_loop_index;
		case '@k':
			return $this->_loop_key;
		case '@v':
			return $this->_loop_val;
		default:
			break;
	}
	if($this->var_exists($name)){
		// FIXME: More efficient way? There should be one!
		$var = $GLOBALS[$this->variables[$name]];
		foreach($indexes as $i){
			if(is_array($var)){
				$var = (isset($var[$i]) ? $var[$i] : null);
			}else{
				$this->errors[] = "Tried to dereference non-array ($name) ".
					"with (sub-)index $i, ret: undef!";
				return $this->undefined_subst;
			}
		}
		return $var;
	}else{
		return $this->undefined_subst;
	}
}

/* Execute Template - generate content */
function execute(){
	if(! $this->b_parsed){
		$this->errors[] = "Template not loaded / parsed!";
		return false;
	}
	if(! $this->_exec($this->parsed)) return false;
	$this->_temp_variables = array();
	return true;
}

/* Internal function - perform execution on given array */
function _exec(&$arr){
	foreach($arr as $a){
		if(! $a->execute($this)) return false;
	}
	return true;
}

/* Internal function - sets current loop index */
function _set_loop_index($i){
	$this->_loop_index = $i;
}

/* Internal function - sets current each-loop key */
function _set_loop_key($k){
	$this->_loop_key = $k;
}

/* Internal function - sets current each-loop val */
function _set_loop_val($v){
	$this->_loop_val = $v;
}

/* Output Template - return content */
function out(){
	return $this->output;
}

}; /* class Template */

/* ------------------------------------------------------------------------- *\
   Here the magic begins. Classes defined below are responsible for doing
   all the work after parsing. You don't need to worry about them unless
   you're developer (and you're trying to extend Template).
\* ------------------------------------------------------------------------- */

/* Internal class - variable - number || string */
class TplIntDirect{
	var $value;
	function TplIntDirect($value){ $this->value = $value; }
	function evaluate(&$tpl){ return $this->value; }
};

/* Internal class - variable - variable substitution */
class TplIntIndirect{
	var $value;
	var $indexes;
	function TplIntIndirect($value, $indexes){
		$this->value = $value;
		$this->indexes = $indexes;
	}
	function evaluate(&$tpl){
		$i = array();
		foreach($this->indexes as $idx){
			$i[] = $idx->evaluate($tpl);
		}
		return $tpl->lookup($this->value, $i);
	}
};

/* Internal class - expression */
class TplIntExpr{
	var $neg;
	var $op;
	var $x;
	var $y;
	function TplIntExpr($neg, $op, $x, $y){
		$this->neg = $neg;
		$this->op = $op;
		$this->x = $x;
		$this->y = $y;
	}
	function _unboolize($var){
		if($var === true) return 1;
		if($var === false) return 0;
		return $var;
	}
	function evaluate(&$tpl){
		$x = $this->x->evaluate($tpl);
		$y = $this->y->evaluate($tpl);
		$z = false; // XXX: Should be configurable?
		if(isset($tpl->_operators[$this->op])){
			@eval($tpl->_operators[$this->op]);
		}
		return $this->_unboolize(($this->neg) ? (! $z) : ($z));
	}
};

/* Variable substitution class */
class TplVariable{
	var $var;
	function TplVariable($var){ $this->var = $var; }
	function execute(&$tpl){
		$tpl->output .= $this->var->evaluate($tpl);
		return true;
	}
};

/* If condition class */
class TplIf{
	var $cond;
	var $content;
	function TplIf($cond, $content){
		$this->cond = $cond;
		$this->content = $content;
	}
	function execute(&$tpl){
		if($this->cond->evaluate($tpl)){
			return $tpl->_exec($this->content);
		}else{
			return true;
		}
	}
};

/* While cycle class */
class TplWhile{
	var $cond;
	var $content;
	function TplWhile($cond, $content){
		$this->cond = $cond;
		$this->content = $content;
	}
	function execute(&$tpl){
		$iteration = 0;
		$max = $tpl->hard_max_loops;
		$tpl->_set_loop_index($iteration);
		while($max && $this->cond->evaluate($tpl)){
			if(! $tpl->_exec($this->content)) return false;
			$max--;
			$iteration++;
			$tpl->_set_loop_index($iteration);
		}
		return true;
	}
};

/* Loop cycle class */
class TplLoop{
	var $times;
	var $content;
	function TplLoop($times, $content){
		$this->times = $times;
		$this->content = $content;
	}
	function execute(&$tpl){
		$num = $this->times->evaluate($tpl);
		$max = $tpl->hard_max_loops;
		$iteration = 0;
		while($max && $num){
			$tpl->_set_loop_index($iteration);
			if(! $tpl->_exec($this->content)) return false;
			$max--;
			$num--;
			$iteration++;
		}
		return true;
	}
};

/* Each cycle class */
class TplEach{
	var $variable;
	var $content;
	function TplEach($variable, $content){
		$this->variable = $variable;
		$this->content = $content;
	}
	function execute(&$tpl){
		$ar = $this->variable->evaluate($tpl);
		if(! is_array($ar)){
			$ar = array($ar);
		}
		$max = $tpl->hard_max_loops;
		foreach($ar as $i => $j){
			if(! $max) break;
			$tpl->_set_loop_index($i);
			$tpl->_set_loop_key($i);
			$tpl->_set_loop_val($ar[$i]);
//			if(! $tpl->_exec($this->content, $iteration)) return false;
			if(! $tpl->_exec($this->content)) return false;
			$max--;
		}
		return true;
	}
};

/* Include other template class */
class TplInclude{
	var $name;
	function TplInclude($name){ $this->name = $name; }
	function execute(&$tpl){
		$fl = $this->name->evaluate($tpl);
		$t = new Template;
		$t->variables = $tpl->variables;
		$t->_operators = $tpl->_operators;
		$t->_unary_operators = $tpl->_unary_operators;
		$t->opentag = $tpl->opentag;
		$t->closetag = $tpl->closetag;
		$t->openvar = $tpl->openvar;
		$t->closevar = $tpl->closevar;
		$t->hard_max_loops = $tpl->hard_max_loops;
		$t->usecache = $tpl->usecache;
		$t->cachedir = $tpl->cachedir;
		$t->includedir = $tpl->includedir;
		$t->include_try_cwd_first = $tpl->include_try_cwd_first;
		$t->include_try_cwd_last = $tpl->include_try_cwd_last;
		$t->max_include_recursion = $tpl->max_include_recursion - 1;
		$t->_loop_index = $tpl->_loop_index;
		$t->_loop_key = $tpl->_loop_key;
		$t->_loop_val = $tpl->_loop_val;
		$t->_temp_variables = $tpl->_temp_variables;
		if($tpl->max_include_recursion < 1){
			$tpl->errors[] = "Recursion gone too deep, stopped at ($fl)!";
			if($tpl->fail_if_include_fails) return false;
			return true;
		}
		if(! $t->load($fl)){
			reset($t->errors);
			foreach($t->errors as $i){
				$tpl->errors[] = "included($fl): $i";
			}
			//$tpl->errors[] = "Failed to load included template ($fl)!";
			if($tpl->fail_if_include_fails) return false;
			return true;
		}
		if(! $t->execute()){
			//$tpl->errors[] = "Failed to execute included template ($fl)!";
			reset($t->errors);
			foreach($t->errors as $i){
				$tpl->errors[] = "included($fl): $i";
			}
			if($tpl->fail_if_include_fails) return false;
			return true;
		}
		$tpl->output .= $t->out();
		return true;
	}
};

/* Static text class */
class TplStatic{
	var $value;
	function TplStatic($value){ $this->value = $value; }
	function execute(&$tpl){
		$tpl->output .= $this->value;
		return true;
	}
};

/* Variable assign class */
class TplSet{
	var $name;
	var $expr;
	function TplSet($name, $expr){
		$this->name = $name;
		$this->expr = $expr;
	}
	function execute(&$tpl){
		$tpl->_temp_variables[$this->name] = $this->expr->evaluate($tpl);
		return true;
	}
};

endif; // if(! isset($GLOBALS["....."])

/* vim: set cindent noexpandtab sw=4 ts=4 tw=0 : */
?>
