console.log("I'm still here!")

var x = "";

var r = /\\u([\d\w]{4})/gi;
x = x.replace(r, function (match, grp) {
    return String.fromCharCode(parseInt(grp, 16)); } );
x = unescape(x);

embed = document.querySelector('#embed');
// embed.innerHTML = x;

console.log(x);

// console.log(x);