/*! jquery.rs.carousel-min.js | 1.0.2 | 2013-06-22 | http://richardscarrott.github.com/jquery-ui-carousel/ */
(function(a){"use strict";var b=a.rs.carousel.prototype;a.widget("rs.carousel",a.rs.carousel,{options:{pause:8e3,autoScroll:!1},_create:function(){b._create.apply(this),this.options.autoScroll&&(this._bindAutoScroll(),this._start())},_bindAutoScroll:function(){if(!this.autoScrollInitiated){var b=this.eventNamespace;this.element.bind("mouseenter"+b,a.proxy(this,"_stop")).bind("mouseleave"+b,a.proxy(this,"_start")),this.autoScrollInitiated=!0}},_unbindAutoScroll:function(){var a=this.eventNamespace;this.element.unbind("mouseenter"+a).unbind("mouseleave"+a),this.autoScrollInitiated=!1},_start:function(){var a=this;this._stop(),this.interval=setInterval(function(){a.next()},this.options.pause)},_stop:function(){clearInterval(this.interval)},_setOption:function(a,c){b._setOption.apply(this,arguments),("autoScroll"===a||"pause"===a)&&(c?(this._bindAutoScroll(),this._start()):(this._unbindAutoScroll(),this._stop()))},destroy:function(){this._stop(),b.destroy.apply(this)}})});