// Disables the lesson picker if a new lesson shall be created.
// Disables the name and language picker if the terms shall be added to an existing lesson.
var radios = document.getElementsByName("create_new_lesson");
for(i in radios) radios[i].onchange = function() {
    var lesson = document.getElementsByName("lesson")[0],
        name = document.getElementsByName("name")[0],
        language = document.getElementsByName("language")[0];

    if(this.value === "0") { // Add to an existing lesson.
        lesson.disabled = false;
        name.disabled = true;
        language.disabled = true;
    } else { // Create a new lesson.
        lesson.disabled = true;
        name.disabled = false;
        language.disabled = false;
    }
};