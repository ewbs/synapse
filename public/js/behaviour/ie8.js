/* 
 * JS pour les versions plus ancienne du navigateur Internet Explorer
 * ... en considérant que IE est bel et bien un navigateur ...
 */



/*
 * isArray n'existe pas pour IE8
 */
Array.isArray = function (obj) {
    return Object.prototype.toString.call(obj) === "[object Array]";
};