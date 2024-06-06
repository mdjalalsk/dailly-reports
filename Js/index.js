
/*
* variable declared
* js has 3 type variable  var,let,const
*/
var num =5;
var num=10;
// var is global scope.redeclared and reassign possible.
console.log(num)

let nameName="jalal";
let age;
age=30;
console.log(age);
// let is block scope.redeclared not accept and reassign possible.
// let age=25;
// when redeclared let we face syntaxError
//SyntaxError: Identifier 'age' has already been declared


const address="Mirpur";
// const address="Mirpur";
// let is block scope.redeclared not accept and reassign not accept.
// when redeclared let we face syntaxError
//SyntaxError: Identifier 'age' has already been declared

console.log(address)
