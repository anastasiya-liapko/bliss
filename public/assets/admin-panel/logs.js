!function(c){function e(e){for(var t,n,r=e[0],a=e[1],l=e[2],o=0,u=[];o<r.length;o++)n=r[o],s[n]&&u.push(s[n][0]),s[n]=0;for(t in a)Object.prototype.hasOwnProperty.call(a,t)&&(c[t]=a[t]);for(p&&p(e);u.length;)u.shift()();return f.push.apply(f,l||[]),i()}function i(){for(var e,t=0;t<f.length;t++){for(var n=f[t],r=!0,a=1;a<n.length;a++){var l=n[a];0!==s[l]&&(r=!1)}r&&(f.splice(t--,1),e=o(o.s=n[0]))}return e}var n={},s={3:0},f=[];function o(e){if(n[e])return n[e].exports;var t=n[e]={i:e,l:!1,exports:{}};return c[e].call(t.exports,t,t.exports,o),t.l=!0,t.exports}o.m=c,o.c=n,o.d=function(e,t,n){o.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},o.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.t=function(t,e){if(1&e&&(t=o(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(o.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)o.d(n,r,function(e){return t[e]}.bind(null,r));return n},o.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(t,"a",t),t},o.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},o.p="";var t=window.webpackJsonp=window.webpackJsonp||[],r=t.push.bind(t);t.push=e,t=t.slice();for(var a=0;a<t.length;a++)e(t[a]);var p=r;f.push([12,0]),i()}({12:function(e,t,n){"use strict";n.r(t);var r=n(0),a=n.n(r),l=n(2),o=n.n(l),u=n(1);function c(){var e="".concat(u.a.getLocation(),"/admin-panel/logs/download-logs");return a.a.createElement("div",{className:"card"},a.a.createElement("div",{className:"card-body"},a.a.createElement("h5",{className:"card-title"},"Архив содержит следующие директории:"),a.a.createElement("ul",{className:"card-text"},a.a.createElement("li",null,"mail — запросы к сервису отправки электронной почты;"),a.a.createElement("li",null,"mfi — запросы к МФО;"),a.a.createElement("li",null,"requests — запросы интернет-магазинов к странице /phone-number;"),a.a.createElement("li",null,"sms.ru — запросы к сервису отправки смс-сообщений;"),a.a.createElement("li",null,"telegram — запросы к Telegram;")),a.a.createElement("a",{href:e,className:"btn btn-primary",download:!0},"Скачать логи")))}n(3);var i=document.getElementById("page-logs");i&&o.a.render(a.a.createElement(c,null),i)},3:function(e,t,n){}});