(window.webpackJsonp=window.webpackJsonp||[]).push([[20],{13:function(e,t,n){"use strict";n.d(t,"e",(function(){return r})),n.d(t,"f",(function(){return a})),n.d(t,"d",(function(){return o})),n.d(t,"a",(function(){return i})),n.d(t,"b",(function(){return c})),n.d(t,"c",(function(){return l}));var r=function(e){switch(e){case 1:return"Assigned";case 2:return"Pending";case 3:return"Approved";case 4:return"Rejected"}},a=function(e){switch(e){case 1:return"Assigned";case 2:return"Approved";case 3:return"Rejected"}},o=function(e){return e.toString().toLowerCase().replace(/\s+/g,"-").replace(/[^\w\-]+/g,"").replace(/\-\-+/g,"-").replace(/^-+/,"").replace(/-+$/,"")},i=function(e){return e.substr(0,10)},c=function(){var e=new Date,t=e.getFullYear(),n=e.getMonth()+1,r=e.getDate();return t+"-"+(n=n<10?"0".concat(n):n)+"-"+(r=r<10?"0".concat(r):r)},l=function(){var e=new Date;e.setDate(e.getDate()+1);var t=e.getFullYear(),n=e.getMonth()+1,r=e.getDate();return t+"-"+(n=n<10?"0".concat(n):n)+"-"+(r=r<10?"0".concat(r):r)}},21:function(e,t,n){var r,a,o;a=[],void 0===(o="function"==typeof(r=function(){return function e(t,n,r){var a,o,i=window,c="application/octet-stream",l=r||c,u=t,s=!n&&!r&&u,d=document.createElement("a"),f=function(e){return String(e)},b=i.Blob||i.MozBlob||i.WebKitBlob||f,m=n||"download";if(b=b.call?b.bind(i):Blob,"true"===String(this)&&(l=(u=[u,l])[0],u=u[1]),s&&s.length<2048&&(m=s.split("/").pop().split("?")[0],d.href=s,-1!==d.href.indexOf(s))){var p=new XMLHttpRequest;return p.open("GET",s,!0),p.responseType="blob",p.onload=function(t){e(t.target.response,m,c)},setTimeout((function(){p.send()}),0),p}if(/^data:([\w+-]+\/[\w+.-]+)?[,;]/.test(u)){if(!(u.length>2096103.424&&b!==f))return navigator.msSaveBlob?navigator.msSaveBlob(h(u),m):w(u);l=(u=h(u)).type||c}else if(/([\x80-\xff])/.test(u)){for(var y=0,v=new Uint8Array(u.length),g=v.length;y<g;++y)v[y]=u.charCodeAt(y);u=new b([v],{type:l})}function h(e){for(var t=e.split(/[:;,]/),n=t[1],r=("base64"==t[2]?atob:decodeURIComponent)(t.pop()),a=r.length,o=0,i=new Uint8Array(a);o<a;++o)i[o]=r.charCodeAt(o);return new b([i],{type:n})}function w(e,t){if("download"in d)return d.href=e,d.setAttribute("download",m),d.className="download-js-link",d.innerHTML="downloading...",d.style.display="none",document.body.appendChild(d),setTimeout((function(){d.click(),document.body.removeChild(d),!0===t&&setTimeout((function(){i.URL.revokeObjectURL(d.href)}),250)}),66),!0;if(/(Version)\/(\d+)\.(\d+)(?:\.(\d+))?.*Safari\//.test(navigator.userAgent))return/^data:/.test(e)&&(e="data:"+e.replace(/^data:([\w\/\-\+]+)/,c)),window.open(e)||confirm("Displaying New Document\n\nUse Save As... to download, then click back to return to this page.")&&(location.href=e),!0;var n=document.createElement("iframe");document.body.appendChild(n),!t&&/^data:/.test(e)&&(e="data:"+e.replace(/^data:([\w\/\-\+]+)/,c)),n.src=e,setTimeout((function(){document.body.removeChild(n)}),333)}if(a=u instanceof b?u:new b([u],{type:l}),navigator.msSaveBlob)return navigator.msSaveBlob(a,m);if(i.URL)w(i.URL.createObjectURL(a),!0);else{if("string"==typeof a||a.constructor===f)try{return w("data:"+l+";base64,"+i.btoa(a))}catch(e){return w("data:"+l+","+encodeURIComponent(a))}(o=new FileReader).onload=function(e){w(this.result)},o.readAsDataURL(a)}return!0}})?r.apply(t,a):r)||(e.exports=o)},678:function(e,t,n){"use strict";n.r(t);var r=n(0),a=n.n(r),o=n(4),i=n.n(o),c=n(18),l=n(6),u=n(13),s=n(5),d=n.n(s),f=n(21),b=n.n(f);function m(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function p(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function y(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(e)))return;var n=[],r=!0,a=!1,o=void 0;try{for(var i,c=e[Symbol.iterator]();!(r=(i=c.next()).done)&&(n.push(i.value),!t||n.length!==t);r=!0);}catch(e){a=!0,o=e}finally{try{r||null==c.return||c.return()}finally{if(a)throw o}}return n}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return v(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(n);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return v(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function v(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}t.default=function(){Object(c.f)();var e=y(Object(r.useState)({entity_form_list:[]}),2),t=e[0],n=e[1],o=y(Object(r.useState)(!1),2),s=(o[0],o[1]);return Object(r.useEffect)((function(){i.a.get("/clients/entity-form/assigned-list").then((function(e){var t=e.data.entity_form_list;if(t){$(".dataTable");if("DataTables_Table_0"==$(".dataTable").attr("id"))$(".dataTable").DataTable().destroy();n((function(e){return function(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?m(Object(n),!0).forEach((function(t){p(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):m(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}({},e,{entity_form_list:t,loading:!1})}));$(".dataTableReact"),$(".dataTableReact").DataTable();$(".dataTables_wrapper .row:first-child>div:first-child").remove(),$(".dataTables_filter").css("float","left")}}))}),[]),a.a.createElement(a.a.Fragment,null,a.a.createElement("div",{className:"main-card card mb-1"},a.a.createElement("div",{className:"card-header"},a.a.createElement("div",{className:"card-title"},"Entity Form Listing")),a.a.createElement("div",{className:"card-body"},a.a.createElement("div",{className:"table"},a.a.createElement("table",{className:"table table-striped table-bordered dataTableReact"},a.a.createElement("thead",null,a.a.createElement("tr",null,a.a.createElement("th",null,"Form Title"),a.a.createElement("th",null,"Assigned Date"),a.a.createElement("th",null,"Action"))),a.a.createElement("tbody",null,t.entity_form_list&&t.entity_form_list.map((function(e,n){return a.a.createElement("tr",{key:n},a.a.createElement("td",null,a.a.createElement(l.b,{to:"/client/entity-form/".concat(e.id)},e.form_title)),a.a.createElement("td",null,Object(u.a)(e.pivot.created_at)),a.a.createElement("td",null,a.a.createElement("button",{title:"Download entity report",className:"btn btn-success btn-sm",onClick:function(n){return r=e.id,s(!0),void i.a.post("/generate-entity-form-report/".concat(r),{file_type:"csv"}).then((function(e){var n=e.data,a="".concat(Object(u.d)(t.entity_form_list.filter((function(e){return e.id==r}))[0].form_title),"_report.csv");b()(n,a),s(!1)})).catch((function(e){var t=e.response.data.message;d()("Warning!",t,"error"),s(!1)}));var r}},a.a.createElement("i",{className:"fa fa-save"}))))}))))))))}}}]);