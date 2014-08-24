// Based on a code by Pointy presented at http://stackoverflow.com/a/2403206/1307676
// Creates an object with form data.
function getFormData(form) {
    var paramObj = {};
    var serialized = $(form).serializeArray();

    $.each(serialized, function(_, kv) {
        var keys_match = kv.name.match(/^([^\[]+)((\[([^\[]+)\])+)$/);
        if(keys_match) {
            if(typeof paramObj[keys_match[1]] == 'undefined') {
                paramObj[keys_match[1]] = {};
            }
            var pointer = paramObj[keys_match[1]];

            var keys_match_ary = keys_match[2].match(/\[([^\[]+)\]/g);
            $.each(keys_match_ary, function(k, v) {
                var key = v.match(/\[([^\[]+)\]/)[1];
                if(k == keys_match_ary.length - 1) {
                    pointer[key] = kv.value;
                } else {
                    if(typeof pointer[key] == 'undefined') {
                        pointer[key] = {};
                    }
                    pointer = pointer[key];
                }
            });
        } else {
            paramObj[kv.name] = kv.value;
        }
    });

    return paramObj;
}

function getFormData_old(form) {
    var paramObj = {};
    var serialized = $(form).serializeArray();

    $.each(serialized, function(_, kv) {
        if(paramObj.hasOwnProperty(kv.name) && kv.name.match(/\[\]$/)) {
            paramObj[kv.name] = $.makeArray(paramObj[kv.name]);
            paramObj[kv.name].push(kv.value);
        } else {
            paramObj[kv.name] = kv.value;
        }
    });

    // Warning! This will work only with one-dimensional arrays
    // and only if it has named keys.
    $.each(paramObj, function(name, value) {
        var match = name.match(/^([^\[]+)\[([^\]]+)\]$/);
        if(match) {
            if(typeof paramObj[match[1]] == 'undefined') {
                paramObj[match[1]] = {};
            }
            paramObj[match[1]][match[2]] = value;

            delete paramObj[name];
        }
    });

    return paramObj;
}

function test_getFormData() {
    var simple = $('<form><input name="apple" value="jablko" /><input name="orange" value="pomeranč" /></form>')
    console.log("simple:");
    console.log(getFormData(simple));

    var one_dimensional_hash = $('<form><input name="apple[idared]" value="jablko" /><input name="apple[jonatan]" value="jablíčko" /><input name="citrus[orange]" value="pomeranč" /><input name="citrus[lemon]" value="citrón" /></form>')
    console.log("one_dimensional_hash:");
    console.log(getFormData(one_dimensional_hash));

    var two_dimensional_hash = $('<form><input name="apple[idared][rotten]" value="shnilé jablko" /><input name="apple[idared][fresh]" value="čerstvé jablko" /><input name="apple[jonatan]" value="jablíčko" /><input name="citrus[orange]" value="pomeranč" /><input name="citrus[lemon]" value="citrón" /></form>')
    console.log("two_dimensional_hash:");
    console.log(getFormData(two_dimensional_hash));
}
