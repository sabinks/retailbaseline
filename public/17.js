(window.webpackJsonp=window.webpackJsonp||[]).push([[17],{22:function(t,e,n){t.exports=n(341)},341:function(t,e,n){var r=function(t){"use strict";var e=Object.prototype,n=e.hasOwnProperty,r="function"==typeof Symbol?Symbol:{},a=r.iterator||"@@iterator",o=r.asyncIterator||"@@asyncIterator",i=r.toStringTag||"@@toStringTag";function c(t,e,n,r){var a=e&&e.prototype instanceof f?e:f,o=Object.create(a.prototype),i=new x(r||[]);return o._invoke=function(t,e,n){var r="suspendedStart";return function(a,o){if("executing"===r)throw new Error("Generator is already running");if("completed"===r){if("throw"===a)throw o;return L()}for(n.method=a,n.arg=o;;){var i=n.delegate;if(i){var c=b(i,n);if(c){if(c===u)continue;return c}}if("next"===n.method)n.sent=n._sent=n.arg;else if("throw"===n.method){if("suspendedStart"===r)throw r="completed",n.arg;n.dispatchException(n.arg)}else"return"===n.method&&n.abrupt("return",n.arg);r="executing";var f=l(t,e,n);if("normal"===f.type){if(r=n.done?"completed":"suspendedYield",f.arg===u)continue;return{value:f.arg,done:n.done}}"throw"===f.type&&(r="completed",n.method="throw",n.arg=f.arg)}}}(t,n,i),o}function l(t,e,n){try{return{type:"normal",arg:t.call(e,n)}}catch(t){return{type:"throw",arg:t}}}t.wrap=c;var u={};function f(){}function s(){}function m(){}var h={};h[a]=function(){return this};var p=Object.getPrototypeOf,d=p&&p(p(_([])));d&&d!==e&&n.call(d,a)&&(h=d);var y=m.prototype=f.prototype=Object.create(h);function v(t){["next","throw","return"].forEach((function(e){t[e]=function(t){return this._invoke(e,t)}}))}function g(t){var e;this._invoke=function(r,a){function o(){return new Promise((function(e,o){!function e(r,a,o,i){var c=l(t[r],t,a);if("throw"!==c.type){var u=c.arg,f=u.value;return f&&"object"==typeof f&&n.call(f,"__await")?Promise.resolve(f.__await).then((function(t){e("next",t,o,i)}),(function(t){e("throw",t,o,i)})):Promise.resolve(f).then((function(t){u.value=t,o(u)}),(function(t){return e("throw",t,o,i)}))}i(c.arg)}(r,a,e,o)}))}return e=e?e.then(o,o):o()}}function b(t,e){var n=t.iterator[e.method];if(void 0===n){if(e.delegate=null,"throw"===e.method){if(t.iterator.return&&(e.method="return",e.arg=void 0,b(t,e),"throw"===e.method))return u;e.method="throw",e.arg=new TypeError("The iterator does not provide a 'throw' method")}return u}var r=l(n,t.iterator,e.arg);if("throw"===r.type)return e.method="throw",e.arg=r.arg,e.delegate=null,u;var a=r.arg;return a?a.done?(e[t.resultName]=a.value,e.next=t.nextLoc,"return"!==e.method&&(e.method="next",e.arg=void 0),e.delegate=null,u):a:(e.method="throw",e.arg=new TypeError("iterator result is not an object"),e.delegate=null,u)}function E(t){var e={tryLoc:t[0]};1 in t&&(e.catchLoc=t[1]),2 in t&&(e.finallyLoc=t[2],e.afterLoc=t[3]),this.tryEntries.push(e)}function w(t){var e=t.completion||{};e.type="normal",delete e.arg,t.completion=e}function x(t){this.tryEntries=[{tryLoc:"root"}],t.forEach(E,this),this.reset(!0)}function _(t){if(t){var e=t[a];if(e)return e.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length)){var r=-1,o=function e(){for(;++r<t.length;)if(n.call(t,r))return e.value=t[r],e.done=!1,e;return e.value=void 0,e.done=!0,e};return o.next=o}}return{next:L}}function L(){return{value:void 0,done:!0}}return s.prototype=y.constructor=m,m.constructor=s,m[i]=s.displayName="GeneratorFunction",t.isGeneratorFunction=function(t){var e="function"==typeof t&&t.constructor;return!!e&&(e===s||"GeneratorFunction"===(e.displayName||e.name))},t.mark=function(t){return Object.setPrototypeOf?Object.setPrototypeOf(t,m):(t.__proto__=m,i in t||(t[i]="GeneratorFunction")),t.prototype=Object.create(y),t},t.awrap=function(t){return{__await:t}},v(g.prototype),g.prototype[o]=function(){return this},t.AsyncIterator=g,t.async=function(e,n,r,a){var o=new g(c(e,n,r,a));return t.isGeneratorFunction(n)?o:o.next().then((function(t){return t.done?t.value:o.next()}))},v(y),y[i]="Generator",y[a]=function(){return this},y.toString=function(){return"[object Generator]"},t.keys=function(t){var e=[];for(var n in t)e.push(n);return e.reverse(),function n(){for(;e.length;){var r=e.pop();if(r in t)return n.value=r,n.done=!1,n}return n.done=!0,n}},t.values=_,x.prototype={constructor:x,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=void 0,this.done=!1,this.delegate=null,this.method="next",this.arg=void 0,this.tryEntries.forEach(w),!t)for(var e in this)"t"===e.charAt(0)&&n.call(this,e)&&!isNaN(+e.slice(1))&&(this[e]=void 0)},stop:function(){this.done=!0;var t=this.tryEntries[0].completion;if("throw"===t.type)throw t.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var e=this;function r(n,r){return i.type="throw",i.arg=t,e.next=n,r&&(e.method="next",e.arg=void 0),!!r}for(var a=this.tryEntries.length-1;a>=0;--a){var o=this.tryEntries[a],i=o.completion;if("root"===o.tryLoc)return r("end");if(o.tryLoc<=this.prev){var c=n.call(o,"catchLoc"),l=n.call(o,"finallyLoc");if(c&&l){if(this.prev<o.catchLoc)return r(o.catchLoc,!0);if(this.prev<o.finallyLoc)return r(o.finallyLoc)}else if(c){if(this.prev<o.catchLoc)return r(o.catchLoc,!0)}else{if(!l)throw new Error("try statement without catch or finally");if(this.prev<o.finallyLoc)return r(o.finallyLoc)}}}},abrupt:function(t,e){for(var r=this.tryEntries.length-1;r>=0;--r){var a=this.tryEntries[r];if(a.tryLoc<=this.prev&&n.call(a,"finallyLoc")&&this.prev<a.finallyLoc){var o=a;break}}o&&("break"===t||"continue"===t)&&o.tryLoc<=e&&e<=o.finallyLoc&&(o=null);var i=o?o.completion:{};return i.type=t,i.arg=e,o?(this.method="next",this.next=o.finallyLoc,u):this.complete(i)},complete:function(t,e){if("throw"===t.type)throw t.arg;return"break"===t.type||"continue"===t.type?this.next=t.arg:"return"===t.type?(this.rval=this.arg=t.arg,this.method="return",this.next="end"):"normal"===t.type&&e&&(this.next=e),u},finish:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var n=this.tryEntries[e];if(n.finallyLoc===t)return this.complete(n.completion,n.afterLoc),w(n),u}},catch:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var n=this.tryEntries[e];if(n.tryLoc===t){var r=n.completion;if("throw"===r.type){var a=r.arg;w(n)}return a}}throw new Error("illegal catch attempt")},delegateYield:function(t,e,n){return this.delegate={iterator:_(t),resultName:e,nextLoc:n},"next"===this.method&&(this.arg=void 0),u}},t}(t.exports);try{regeneratorRuntime=r}catch(t){Function("r","regeneratorRuntime = r")(r)}},645:function(t,e,n){"use strict";n.r(e);var r=n(22),a=n.n(r),o=n(0),i=n.n(o),c=n(4),l=n.n(c),u=n(6),f=n(5),s=n.n(f);function m(t){return(m="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function h(t,e,n,r,a,o,i){try{var c=t[o](i),l=c.value}catch(t){return void n(t)}c.done?e(l):Promise.resolve(l).then(r,a)}function p(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}function d(t,e){return(d=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t})(t,e)}function y(t,e){return!e||"object"!==m(e)&&"function"!=typeof e?v(t):e}function v(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}function g(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(t){return!1}}function b(t){return(b=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)})(t)}var E=function(t){!function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&d(t,e)}(w,t);var e,n,r,o,c,f,E=(e=w,function(){var t,n=b(e);if(g()){var r=b(this).constructor;t=Reflect.construct(n,arguments,r)}else t=n.apply(this,arguments);return y(this,t)});function w(){var t;return function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,w),(t=E.call(this)).state={formData:null,form:null,formDataInputValues:null,filterformInputs:[],role:null},t.handleDeleteFormDatum=t.handleDeleteFormDatum.bind(v(t)),t}return n=w,(r=[{key:"handleDeleteFormDatum",value:function(t,e,n){t.preventDefault(),s()({title:"Warning!",text:"Are you sure you wish to delete this form data?",icon:"warning",dangerMode:!0}).then((function(t){t&&l.a.delete("/entities-forms/".concat(e,"/entities-form-data/").concat(n)).then((function(t){window.location.reload()})).catch((function(t){console.log(t.response)}))}))}},{key:"componentDidMount",value:(c=a.a.mark((function t(){var e,n,r,o;return a.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.prev=0,e=this.props.match.params.form,t.next=4,l.a.get("/entities-forms/".concat(e,"/entities-form-data"));case 4:n=t.sent,r=n.data.form,n&&(o=r.inputs.filter((function(t){if("Header"!==t.element)return t})),n.data.formData.forEach((function(t){return t.input_datas.forEach((function(t){if(!r.inputs.find((function(e){return e.field_name===t.name})))return t.value="";if(t.name.indexOf("dropdown_")>-1)r.inputs.find((function(e){return e.field_name===t.name})).options.map((function(e){if(e.value==t.value)return t.value=e.text}));else{if(t.name.indexOf("tags_")>-1)return t.value.map((function(e,n){t.value[n]=e.text})),t.value;if(t.name.indexOf("checkboxes_")>-1)r.inputs.find((function(e){return e.field_name===t.name})).options.map((function(e){return t.value.map((function(n,r){e.key==n&&(t.value[r]=e.text)})),t.value}));else{if(!(t.name.indexOf("radiobuttons_")>-1))return t.value;r.inputs.find((function(e){return e.field_name===t.name})).options.map((function(e){return"string"!=typeof t.value&&t.value.map((function(n){e.key==n&&(t.value=e.text)})),t.value}))}}}))})),this.setState({formData:n.data.formData,form:r,filterformInputs:o,role:n.data.role}),$(".dataTable").DataTable(),$(".dataTables_wrapper .row:first-child>div:first-child").remove(),$(".dataTables_filter").css("float","left")),t.next=12;break;case 9:t.prev=9,t.t0=t.catch(0),console.log("Error",t.t0);case 12:case"end":return t.stop()}}),t,this,[[0,9]])})),f=function(){var t=this,e=arguments;return new Promise((function(n,r){var a=c.apply(t,e);function o(t){h(a,n,r,o,i,"next",t)}function i(t){h(a,n,r,o,i,"throw",t)}o(void 0)}))},function(){return f.apply(this,arguments)})},{key:"render",value:function(){var t=this,e=this.state,n=e.formData,r=e.form,a=e.filterformInputs,o=e.role;return i.a.createElement(i.a.Fragment,null,i.a.createElement("div",{className:"main-card mb-3 card"},i.a.createElement("div",{className:"card-header"},o&&i.a.createElement(i.a.Fragment,null,"Field Staff"==o?i.a.createElement(i.a.Fragment,null,"View, add, edit or delete the Entities Tracking Form data Listed for ",r.form_title,"}"):i.a.createElement(i.a.Fragment,null,"View the Entities Tracking Form data Listed for  ",i.a.createElement("b",null,i.a.createElement("em",null,'"',r.form_title,'"')),"  of  ",i.a.createElement("b",null,r.clients[0].company_name)," "))),i.a.createElement("div",{className:"card-body"},r&&i.a.createElement(i.a.Fragment,null,"Field Staff"==o&&i.a.createElement(u.b,{className:"btn btn-primary btn-sm mr-3",to:"/entities-form/".concat(r.id,"/entities-form-data/create")},"Create a New Entities Tracking Form Datum"),i.a.createElement("a",{className:"btn btn-primary btn-sm mr-3",href:"/entities-form/"},"Go To Entities tracking Form List")),i.a.createElement("div",{className:"table-responsive"},n&&i.a.createElement("table",{className:"table table-stripedfalse table-bordered dataTable"},i.a.createElement("thead",null,i.a.createElement("tr",null,i.a.createElement("th",null,"Form Filler Name"),i.a.createElement("th",null,"Region"),i.a.createElement("th",null,"Point (coordinate)"),a.map((function(t,e){return i.a.createElement("th",{key:e,dangerouslySetInnerHTML:{__html:t.label}})})),"Field Staff"==o&&i.a.createElement("th",null,"Action"))),i.a.createElement("tbody",null,n.map((function(e,n){return i.a.createElement("tr",{key:"tr"+n},i.a.createElement("td",null,e.form_filler.name," "),i.a.createElement("td",null,e.region.name," "),i.a.createElement("td",null,e.latitude,i.a.createElement("br",null),e.longitude," "),e.input_datas.map((function(t,e){return i.a.createElement("td",{key:"td"+e},"object"==m(t.value)&&t.value?t.value.map((function(t,e){return i.a.createElement("span",{key:e},t," ",i.a.createElement("br",null))})):null,"string"==typeof t.value?t.name.indexOf("camera_")>-1?i.a.createElement("img",{className:"img-fluid",src:t.value,alt:""}):t.value:null)})),"Field Staff"==o&&i.a.createElement("td",null,i.a.createElement("div",{className:"btn-group"},i.a.createElement("a",{href:"#!",className:"btn btn-primary btn-sm"},i.a.createElement("i",{className:"fa fa-eye"})),i.a.createElement(u.b,{className:"btn btn-secondary btn-sm",to:"/entities-form/".concat(r.id,"/entities-form-data/").concat(e.id)},i.a.createElement("i",{className:"fa fa-pencil"})),i.a.createElement("a",{href:"#!",onClick:function(n){return t.handleDeleteFormDatum(n,r.id,e.id)},className:"btn btn-danger btn-sm"},i.a.createElement("i",{className:"fa fa-trash-o"})))))}))))))))}}])&&p(n.prototype,r),o&&p(n,o),w}(o.Component);e.default=E}}]);