(window.webpackJsonp=window.webpackJsonp||[]).push([[33],{13:function(e,t,n){"use strict";n.d(t,"e",(function(){return a})),n.d(t,"f",(function(){return r})),n.d(t,"d",(function(){return c})),n.d(t,"a",(function(){return l})),n.d(t,"b",(function(){return i})),n.d(t,"c",(function(){return o}));var a=function(e){switch(e){case 1:return"Assigned";case 2:return"Pending";case 3:return"Approved";case 4:return"Rejected"}},r=function(e){switch(e){case 1:return"Assigned";case 2:return"Approved";case 3:return"Rejected"}},c=function(e){return e.toString().toLowerCase().replace(/\s+/g,"-").replace(/[^\w\-]+/g,"").replace(/\-\-+/g,"-").replace(/^-+/,"").replace(/-+$/,"")},l=function(e){return e.substr(0,10)},i=function(){var e=new Date,t=e.getFullYear(),n=e.getMonth()+1,a=e.getDate();return t+"-"+(n=n<10?"0".concat(n):n)+"-"+(a=a<10?"0".concat(a):a)},o=function(){var e=new Date;e.setDate(e.getDate()+1);var t=e.getFullYear(),n=e.getMonth()+1,a=e.getDate();return t+"-"+(n=n<10?"0".concat(n):n)+"-"+(a=a<10?"0".concat(a):a)}},646:function(e,t,n){"use strict";n.r(t);var a=n(0),r=n.n(a),c=n(4),l=n.n(c),i=n(5),o=n.n(i),s=n(18),u=n(13);function m(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,a)}return n}function d(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?m(Object(n),!0).forEach((function(t){f(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):m(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function f(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function p(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(e)))return;var n=[],a=!0,r=!1,c=void 0;try{for(var l,i=e[Symbol.iterator]();!(a=(l=i.next()).done)&&(n.push(l.value),!t||n.length!==t);a=!0);}catch(e){r=!0,c=e}finally{try{a||null==i.return||i.return()}finally{if(r)throw c}}return n}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return b(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(n);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return b(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function b(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,a=new Array(t);n<t;n++)a[n]=e[n];return a}var g=document.getElementById("route").getAttribute("url");t.default=function(){Object(s.f)();var e=p(Object(a.useState)({id:"",title:"",status:"",loading:!0,role:null,image_path:"",url:"http://localhost:8000"}),2),t=e[0],n=e[1],c=p(Object(a.useState)([]),2),i=c[0],m=c[1],f=p(Object(a.useState)([]),2),b=f[0],v=f[1];Object(a.useEffect)((function(){var e=window.location.pathname.replace("/entity-data-view/","");y(e)}),[]);var y=function(e){l.a.get("/entity-info-view/".concat(e)).then((function(t){g=window.location.origin;var a=t.data,r=a.answer,c=a.question,l=a.status,i=a.image_path,o=a.role,s=a.title,u=a.name;m(r),v(c),n((function(t){return d({},t,{id:e,status:l,url:g,image_path:i,role:o,name:u,loading:!1,title:s})}))})).catch((function(e){var t=e.response.data.message;o()("Warning!",t,"error")}))},h=function(e){l.a.post("/entity-data-approve-reject/".concat(e,"/").concat(t.id)).then((function(e){var t=e.data,a=t.message,r=t.status;n((function(e){return d({},e,{status:r,loading:!1})})),o()("Success!",a,"success")})).catch((function(e){var t=e.response.data.message;o()("Warning!",t,"warning")}))};return r.a.createElement(r.a.Fragment,null,r.a.createElement("div",{className:"main-card card mb-1"},r.a.createElement("div",{className:"card-body pb-0 pt-2"},r.a.createElement("h4",null,"Entity Data View")),!t.loading&&r.a.createElement("div",{className:"card-body pb-0 pt-2"},r.a.createElement("div",{className:"row"},r.a.createElement("div",{className:"col-md-3"},r.a.createElement("h5",null,"Title: ",r.a.createElement("b",null,t.name))),r.a.createElement("div",{className:"col-md-4"},r.a.createElement("h5",null,"Title: ",r.a.createElement("b",null,t.title))),r.a.createElement("div",{className:"col-md-3"},"Report Status: ",r.a.createElement("b",null,Object(u.f)(t.status))),r.a.createElement("div",{className:"col-md-2"},r.a.createElement("button",{className:"btn btn-sm btn-success mr-1 float-right",onClick:function(e){return h("accepted")}},"Approve"),r.a.createElement("button",{className:"btn btn-sm btn-danger mr-1 float-right",onClick:function(e){return h("rejected")}},"Reject"))))),r.a.createElement("div",{className:"main-card card"},r.a.createElement("div",{className:"col-md-12 mb-0 pb-0 pt-2 float-left"},b.length>0&&b.map((function(e,n){return r.a.createElement("div",{key:n,className:""},r.a.createElement("div",{className:"mb-2 card p-2",style:{display:"Header"!=e.element&&"none"}},r.a.createElement("div",{className:"text-primary"},e.label)),r.a.createElement("div",{className:"mb-2 card p-2 text-primary",style:{display:"Camera"!=e.element&&"none"}},r.a.createElement("div",{className:"mb-1"},"Q ) ",e.label),r.a.createElement("div",null,r.a.createElement("img",{className:"img-thumbnail",src:"".concat(t.url,"/").concat(i[n]),alt:i[n]}))),r.a.createElement("div",{className:"mb-2 card p-2",style:{display:("Camera"==e.element||"Header"==e.element)&&"none"}},r.a.createElement("div",{className:"mb-1 text-primary"},"Q ) ",e.label),r.a.createElement("div",{className:"text-success"},"A ) ",i[n])))})))))}}}]);