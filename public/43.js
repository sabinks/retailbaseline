(window.webpackJsonp=window.webpackJsonp||[]).push([[43],{677:function(e,t,r){"use strict";r.r(t);var n=r(0),a=r.n(n),i=r(4),o=r.n(i),c=r(23),l=r(18);r(90);function u(e){return function(e){if(Array.isArray(e))return y(e)}(e)||function(e){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e))return Array.from(e)}(e)||m(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function s(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function f(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?s(Object(r),!0).forEach((function(t){d(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):s(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function d(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}function b(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(e)))return;var r=[],n=!0,a=!1,i=void 0;try{for(var o,c=e[Symbol.iterator]();!(n=(o=c.next()).done)&&(r.push(o.value),!t||r.length!==t);n=!0);}catch(e){a=!0,i=e}finally{try{n||null==c.return||c.return()}finally{if(a)throw i}}return r}(e,t)||m(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function m(e,t){if(e){if("string"==typeof e)return y(e,t);var r=Object.prototype.toString.call(e).slice(8,-1);return"Object"===r&&e.constructor&&(r=e.constructor.name),"Map"===r||"Set"===r?Array.from(r):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?y(e,t):void 0}}function y(e,t){(null==t||t>e.length)&&(t=e.length);for(var r=0,n=new Array(t);r<t;r++)n[r]=e[r];return n}t.default=function(){Object(l.f)();var e=b(Object(n.useState)({loading:!0}),2),t=e[0],r=e[1],i=b(Object(n.useState)([]),2),s=i[0],d=i[1],m=b(Object(n.useState)([]),2),y=m[0],p=m[1];Object(n.useEffect)((function(){o.a.get("/superadmin/client-entity-view").then((function(e){var t=e.data,n=t.all_entity_form,a=t.assigned_clients;if(a){$(".dataTable");if("DataTables_Table_0"==$(".dataTable").attr("id"))$(".dataTable").DataTable().destroy();p(a),d(n),r((function(e){return f({},e,{loading:!1})}));$(".dataTableReact"),$(".dataTableReact").DataTable();$(".dataTables_wrapper .row:first-child>div:first-child").remove(),$(".dataTables_filter").css("float","left")}}))}),[]);return a.a.createElement(a.a.Fragment,null,a.a.createElement("div",{className:"main-card card"},a.a.createElement("div",{className:"card-header"},a.a.createElement("div",{className:"card-title"},"Assign Report Form To Client Company")),a.a.createElement("div",{className:"card-body"},a.a.createElement("div",{className:"table"},!t.loading&&a.a.createElement("table",{className:"table table-striped table-bordered dataTableReact"},a.a.createElement("thead",null,a.a.createElement("tr",null,a.a.createElement("th",null,"Company Name"),a.a.createElement("th",null,"Entity Form View Access"))),a.a.createElement("tbody",null,y&&y.map((function(e,t){return a.a.createElement("tr",{key:t},a.a.createElement("td",null,e.name),a.a.createElement("td",null,a.a.createElement(c.default,{name:e.id,placeholder:"Select Entity Form",value:e.entity_form,options:s,onChange:function(t){return function(e,t){var r=[];y.map((function(n){n.id==parseInt(t)?r.push(f({},n,{entity_form:e?u(e):[]})):r.push(n)}));var n={entity_ids:e?JSON.stringify(e.map((function(e){return e.id}))):JSON.stringify([])};o.a.post("/superadmin/assign-entity-client/".concat(t),n).then((function(e){p(r)})).catch()}(t,e.id)},isMulti:!0})))}))))))))}},90:function(e,t,r){"use strict";r(0),r(6)}}]);