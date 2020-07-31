if(document.getElementById("datepicker")) {
    $(function () {
        $("#datepicker, #datepicker2").datepicker();
    });
}
/*
 *    setup listeners on class blue, pink and admin
 */
function buttonSetup (button) {

    function makeItHappenDown(x,buttonDown) {
        return function(){
            x.className=buttonDown;
        }
    }
    function makeItHappenUp(x,buttonUp) {
        return function(){
            x.className=buttonUp;
        }
    }

    if (document.getElementsByClassName(button+"_up")) {
        let a = document.getElementsByClassName(button+"_up");
        let x;
        for (let i = 0; i < a.length; ++i) {
            x = a[i];
console.log(x);
            x.addEventListener("mousedown", makeItHappenDown(x,button+"_down"), false);
            x.addEventListener("mouseup", makeItHappenUp(x,button+"_up"), false);
        }
    }
}

window.onload = function () {
    buttonSetup("del");
    buttonSetup("blue");
    buttonSetup("book");
}