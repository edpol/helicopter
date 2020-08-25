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
//console.log(x);
            x.addEventListener("mousedown", makeItHappenDown(x,button+"_down"), false);
            x.addEventListener("mouseup", makeItHappenUp(x,button+"_up"), false);
        }
    }
}

/** Splits a camel-case or Pascal-case variable name into individual words.
 * @param {string} s
 * @returns {string}
 */
function splitWords(s) {
    let re, match, output = [];
    // re = /[A-Z]?[a-z]+/g
    re = /([A-Za-z]?)([a-z]+)/g;

    /*
    matches example: "oneTwoThree"
    ["one", "o", "ne"]
    ["Two", "T", "wo"]
    ["Three", "T", "hree"]
    */

    match = re.exec(s);
    while (match) {
        // output.push(match.join(""));
        output.push([match[1].toUpperCase(), match[2]].join(""));
        match = re.exec(s);
    }
    let result;
    result = output.join(" ");
    return result;
}

function delElement(node) {
    node.parentNode.remove();
}

function appendLabel(target, count, RPH){
console.log(RPH);
    let label  = document.createElement('label');
    label.classList.add("label2");
    label.setAttribute('for', target + RPH + count);
    let text1  = document.createTextNode(splitWords(target) + ' #' + (count + 1) + ': ');
    label.appendChild(text1);
    return label;
}

function addSpecialNeed(node) {
console.log("Hello 1");

console.log(node.parentNode);
    let parent = node.parentNode;
    let count = parent.querySelectorAll("p").length;
    let p = document.createElement("p");
    parent.insertBefore(p, node);

    let RPH = document.querySelectorAll(".RPH").length;
    RPH--;

    let target = "SpecialNeed";
    let label = appendLabel(target, count, RPH);
    p.appendChild(label);

    let input  = document.createElement('input');
    input.setAttribute('id', target + RPH + count);
    input.setAttribute('type', 'text');
    input.setAttribute('name', target + '[' + RPH +'][' + count + '][Code]');
    p.appendChild(input);

    target = "SpecialNeed_";
    label = appendLabel(target, count, RPH);
    let text = document.createTextNode('\t\t');
    p.appendChild(text);
    p.appendChild(label);

    input  = document.createElement('input');
    input.setAttribute('id', target + RPH + count);
    input.setAttribute('type', 'text');
    input.setAttribute('name', 'SpecialNeed' + '[' + RPH +'][' + count + '][_]');
    p.appendChild(input);

    let button = document.createElement('button');
    button.setAttribute('class', 'del_up');
    button.setAttribute('type',  'button');
    button.setAttribute('name',  'SpecialNeed' + RPH + count);
    let blank = document.createTextNode(' ');
    p.appendChild(blank);
    text = document.createTextNode('x');
    p.appendChild(button);
    button.appendChild(text);
    button.addEventListener("click", function(e){p.remove()}, false);
}

function addElement(node){
    // get parent ID
    let parent = node.parentNode;
    let target = parent.getAttribute('id');

    // we'll pace the new p tage before the span tag
    let span  = parent.querySelector(".add_up" );
    let plist = parent.querySelectorAll("p");
    let count = plist.length;

    let p      = document.createElement("p");

    parent.insertBefore(p, span);

    let label = appendLabel(target, count, '');
    p.appendChild(label);

    let input  = document.createElement('input');
    input.setAttribute('id', target + count);
    input.setAttribute('type', 'text');
    input.setAttribute('name', target + '[]');
    p.appendChild(input);

    let button = document.createElement('button');
    let attributes = ["class",  "type",   "name"];
    let values     = ["del_up", "button", "ContactDetail"];
    for (let i = 0; i < attributes.length; i++) {
        button.setAttribute(attributes[i], values[i])
    }

    let blank = document.createTextNode(' ');
    p.appendChild(blank);
    let text = document.createTextNode('x');
    p.appendChild(button);
    button.appendChild(text);
    button.addEventListener("click", function(e){p.remove()}, false);
}

function addEventListeners(e) {
    e.preventDefault();
    let addButtons = document.getElementsByClassName("add_up");
    let delButtons = document.getElementsByClassName("del_up");
    let i;

    // add an event listener to all of the buttons of class add_up
    for(i=0; i<addButtons.length; i++) {
        // addButton[0],1 and 2 use addElement
        if(i>2){
console.log('Add Listener to SpecialNeed Add ' , i);
console.log(addButtons[i]);
            addButtons[i].addEventListener("click", function (e) {addSpecialNeed(this)}, false);
        }else {
console.log('Add Listener to AddButton ' , i);
console.log(addButtons[i]);
            addButtons[i].addEventListener("click", function (e) {addElement(this)}, false);
        }
    }
    for(i=0; i<delButtons.length; i++) {
        delButtons[i].addEventListener("click", function(e){delElement(this)}, false);
    }

    // // add an event listener to all of the buttons of class del_up
    // for(i=0; i<delContactDetail.length; i++) {
    //     delContactDetail[i].addEventListener("click", function(e){delElement1(this)}, false);
    // }
    // // add an event listener to all of the buttons of class del_up
    // for(i=0; i<delSpecialNeed.length; i++) {
    //     delSpecialNeed[i].addEventListener("click", function(e){delElement2(this)}, false);
    // }


}

window.onload = function () {
    buttonSetup("del");
    buttonSetup("blue");
    buttonSetup("book");
    buttonSetup("add");
    addEventListeners(event);
}
