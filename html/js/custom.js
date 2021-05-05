(function(window, document, $, undefined) {
    "use strict";
    if (!$) {
        return undefined
    }
    var defaults = {
        speed: 330,
        loop: true,
        opacity: "auto",
        margin: [44, 0],
        gutter: 30,
        infobar: true,
        buttons: true,
        slideShow: true,
        fullScreen: true,
        thumbs: true,
        closeBtn: true,
        smallBtn: "auto",
        image: {
            preload: "auto",
            protect: false
        },
        ajax: {
            settings: {
                data: {
                    fancybox: true
                }
            }
        },
        iframe: {
            tpl: '<iframe id="fancybox-frame{rnd}" name="fancybox-frame{rnd}" class="fancybox-iframe" frameborder="0" vspace="0" hspace="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen allowtransparency="true" src=""></iframe>',
            preload: true,
            scrolling: "no",
            css: {}
        },
        baseClass: "",
        slideClass: "",
        baseTpl: '',
        parentEl: "body",
        touch: true,
        keyboard: true,
        focus: true,
        closeClickOutside: true,
        beforeLoad: $.noop,
        afterLoad: $.noop,
        beforeMove: $.noop,
        afterMove: $.noop,
        onComplete: $.noop,
        onInit: $.noop,
        beforeClose: $.noop,
        afterClose: $.noop,
        onActivate: $.noop,
        onDeactivate: $.noop
    };
    var $W = $(window);
    var $D = $(document);
    var called = 0;
    var isQuery = function(obj) {
        return obj && obj.hasOwnProperty && obj instanceof $
    };
    var requestAFrame = function() {
        return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || function(callback) {
            window.setTimeout(callback, 1e3 / 60)
        }
    }();
    var isElementInViewport = function(el) {
        var rect;
        if (typeof $ === "function" && el instanceof $) {
            el = el[0]
        }
        rect = el.getBoundingClientRect();
        return rect.bottom > 0 && rect.right > 0 && rect.left < (window.innerWidth || document.documentElement.clientWidth) && rect.top < (window.innerHeight || document.documentElement.clientHeight)
    };
    var FancyBox = function(content, opts, index) {
        var self = this;
        self.opts = $.extend(true, {
            index: index
        }, defaults, opts || {});
        self.id = self.opts.id || ++called;
        self.group = [];
        self.currIndex = parseInt(self.opts.index, 10) || 0;
        self.prevIndex = null;
        self.prevPos = null;
        self.currPos = 0;
        self.firstRun = null;
        self.createGroup(content);
        if (!self.group.length) {
            return
        }
        self.$lastFocus = $(document.activeElement).blur();
        self.slides = {};
        self.init(content)
    };
    
    $.fancybox = {
        version: "3.0.47-modified",
        defaults: defaults,
        getInstance: function(command) {
            var instance = $('.fancybox-container:not(".fancybox-container--closing"):first').data("FancyBox");
            var args = Array.prototype.slice.call(arguments, 1);
            if (instance instanceof FancyBox) {
                if ($.type(command) === "string") {
                    instance[command].apply(instance, args)
                } else if ($.type(command) === "function") {
                    command.apply(instance, args)
                }
                return instance
            }
            return false
        },
        open: function(items, opts, index) {
            return new FancyBox(items, opts, index)
        },
        close: function(all) {
            var instance = this.getInstance();
            if (instance) {
                instance.close();
                if (all === true) {
                    this.close()
                }
            }
        },
        isTouch: document.createTouch !== undefined && /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent),
        use3d: function() {
            var div = document.createElement("div");
            return window.getComputedStyle(div).getPropertyValue("transform") && !(document.documentMode && document.documentMode <= 11)
        }(),
        getTranslate: function($el) {
            var position, matrix;
            if (!$el || !$el.length) {
                return false
            }
            position = $el.get(0).getBoundingClientRect();
            matrix = $el.eq(0).css("transform");
            if (matrix && matrix.indexOf("matrix") !== -1) {
                matrix = matrix.split("(")[1];
                matrix = matrix.split(")")[0];
                matrix = matrix.split(",")
            } else {
                matrix = []
            }
            if (matrix.length) {
                if (matrix.length > 10) {
                    matrix = [matrix[13], matrix[12], matrix[0], matrix[5]]
                } else {
                    matrix = [matrix[5], matrix[4], matrix[0], matrix[3]]
                }
                matrix = matrix.map(parseFloat)
            } else {
                matrix = [0, 0, 1, 1]
            }
            return {
                top: matrix[0],
                left: matrix[1],
                scaleX: matrix[2],
                scaleY: matrix[3],
                opacity: parseFloat($el.css("opacity")),
                width: position.width,
                height: position.height
            }
        },
        setTranslate: function($el, props) {
            var str = "";
            var css = {};
            if (!$el || !props) {
                return
            }
            if (props.left !== undefined || props.top !== undefined) {
                str = (props.left === undefined ? $el.position().top : props.left) + "px, " + (props.top === undefined ? $el.position().top : props.top) + "px";
                if (this.use3d) {
                    str = "translate3d(" + str + ", 0px)"
                } else {
                    str = "translate(" + str + ")"
                }
            }
            if (props.scaleX !== undefined && props.scaleY !== undefined) {
                str = (str.length ? str + " " : "") + "scale(" + props.scaleX + ", " + props.scaleY + ")"
            }
            if (str.length) {
                css.transform = str
            }
            if (props.opacity !== undefined) {
                css.opacity = props.opacity
            }
            if (props.width !== undefined) {
                css.width = props.width
            }
            if (props.height !== undefined) {
                css.height = props.height
            }
            return $el.css(css)
        },
        easing: {
            easeOutCubic: function(t, b, c, d) {
                return c * ((t = t / d - 1) * t * t + 1) + b
            },
            easeInCubic: function(t, b, c, d) {
                return c * (t /= d) * t * t + b
            },
            easeOutSine: function(t, b, c, d) {
                return c * Math.sin(t / d * (Math.PI / 2)) + b
            },
            easeInSine: function(t, b, c, d) {
                return -c * Math.cos(t / d * (Math.PI / 2)) + c + b
            }
        },
        stop: function($el) {
            $el.removeData("animateID")
        },
        animate: function($el, from, to, duration, easing, done) {
            var self = this;
            var lastTime = null;
            var animTime = 0;
            var curr;
            var diff;
            var id;
            var finish = function() {
                if (to.scaleX !== undefined && to.scaleY !== undefined && from && from.width !== undefined && from.height !== undefined) {
                    to.width = from.width * to.scaleX;
                    to.height = from.height * to.scaleY;
                    to.scaleX = 1;
                    to.scaleY = 1
                }
                self.setTranslate($el, to);
                done()
            };
            var frame = function(timestamp) {
                curr = [];
                diff = 0;
                if (!$el.length || $el.data("animateID") !== id) {
                    return
                }
                timestamp = timestamp || Date.now();
                if (lastTime) {
                    diff = timestamp - lastTime
                }
                lastTime = timestamp;
                animTime += diff;
                if (animTime >= duration) {
                    finish();
                    return
                }
                for (var prop in to) {
                    if (to.hasOwnProperty(prop) && from[prop] !== undefined) {
                        if (from[prop] == to[prop]) {
                            curr[prop] = to[prop]
                        } else {
                            curr[prop] = self.easing[easing](animTime, from[prop], to[prop] - from[prop], duration)
                        }
                    }
                }
                self.setTranslate($el, curr);
                requestAFrame(frame)
            };
            self.animateID = id = self.animateID === undefined ? 1 : self.animateID + 1;
            $el.data("animateID", id);
            if (done === undefined && $.type(easing) == "function") {
                done = easing;
                easing = undefined
            }
            if (!easing) {
                easing = "easeOutCubic"
            }
            done = done || $.noop;
            if (from) {
                this.setTranslate($el, from)
            } else {
                from = this.getTranslate($el)
            }
            if (duration) {
                $el.show();
                requestAFrame(frame)
            } else {
                finish()
            }
        }
    };

    function _run(e) {
        var target = e.currentTarget,
            opts = e.data ? e.data.options : {},
            items = e.data ? e.data.items : [],
            value = "",
            index = 0;
        e.preventDefault();
        e.stopPropagation();
        if ($(target).attr("data-fancybox")) {
            value = $(target).data("fancybox")
        }
        if (value) {
            items = items.length ? items.filter('[data-fancybox="' + value + '"]') : $("[data-fancybox=" + value + "]");
            index = items.index(target)
        } else {
            items = [target]
        }
        $.fancybox.open(items, opts, index)
    }
    $.fn.fancybox = function(options) {
        this.off("click.fb-start").on("click.fb-start", {
            items: this,
            options: options || {}
        }, _run);
        return this
    };
    $(document).on("click.fb-start", "[data-fancybox]", _run)
})(window, document, window.jQuery);
(function($) {
    "use strict";
    var format = function(url, rez, params) {
        if (!url) {
            return
        }
        params = params || "";
        if ($.type(params) === "object") {
            params = $.param(params, true)
        }
        $.each(rez, function(key, value) {
            url = url.replace("$" + key, value || "")
        });
        if (params.length) {
            url += (url.indexOf("?") > 0 ? "&" : "?") + params
        }
        return url
    };
    var media = {
        youtube: {
            matcher: /(youtube\.com|youtu\.be|youtube\-nocookie\.com)\/(watch\?(.*&)?v=|v\/|u\/|embed\/?)?(videoseries\?list=(.*)|[\w-]{11}|\?listType=(.*)&list=(.*))(.*)/i,
            params: {
                autoplay: 1,
                autohide: 1,
                fs: 1,
                rel: 0,
                hd: 1,
                wmode: "transparent",
                enablejsapi: 1,
                html5: 1
            },
            paramPlace: 8,
            type: "iframe",
            url: "//www.youtube.com/embed/$4",
            thumb: "//img.youtube.com/vi/$4/hqdefault.jpg"
        },
        vimeo: {
            matcher: /^.+vimeo.com\/(.*\/)?([\d]+)(.*)?/,
            params: {
                autoplay: 1,
                hd: 1,
                show_title: 1,
                show_byline: 1,
                show_portrait: 0,
                fullscreen: 1,
                api: 1
            },
            paramPlace: 3,
            type: "iframe",
            url: "//player.vimeo.com/video/$2"
        },
        metacafe: {
            matcher: /metacafe.com\/watch\/(\d+)\/(.*)?/,
            type: "iframe",
            url: "//www.metacafe.com/embed/$1/?ap=1"
        },
        dailymotion: {
            matcher: /dailymotion.com\/video\/(.*)\/?(.*)/,
            params: {
                additionalInfos: 0,
                autoStart: 1
            },
            type: "iframe",
            url: "//www.dailymotion.com/embed/video/$1"
        },
        vine: {
            matcher: /vine.co\/v\/([a-zA-Z0-9\?\=\-]+)/,
            type: "iframe",
            url: "//vine.co/v/$1/embed/simple"
        },
        instagram: {
            matcher: /(instagr\.am|instagram\.com)\/p\/([a-zA-Z0-9_\-]+)\/?/i,
            type: "image",
            url: "//$1/p/$2/media/?size=l"
        },
        google_maps: {
            matcher: /(maps\.)?google\.([a-z]{2,3}(\.[a-z]{2})?)\/(((maps\/(place\/(.*)\/)?\@(.*),(\d+.?\d+?)z))|(\?ll=))(.*)?/i,
            type: "iframe",
            url: function(rez) {
                return "//maps.google." + rez[2] + "/?ll=" + (rez[9] ? rez[9] + "&z=" + Math.floor(rez[10]) + (rez[12] ? rez[12].replace(/^\//, "&") : "") : rez[12]) + "&output=" + (rez[12] && rez[12].indexOf("layer=c") > 0 ? "svembed" : "embed")
            }
        }
    };
    $(document).on("onInit.fb", function(e, instance) {
        $.each(instance.group, function(i, item) {
            var url = item.src || "",
                type = false,
                thumb, rez, params, urlParams, o, provider;
            if (item.type) {
                return
            }
            $.each(media, function(n, el) {
                rez = url.match(el.matcher);
                o = {};
                provider = n;
                if (!rez) {
                    return
                }
                type = el.type;
                if (el.paramPlace && rez[el.paramPlace]) {
                    urlParams = rez[el.paramPlace];
                    if (urlParams[0] == "?") {
                        urlParams = urlParams.substring(1)
                    }
                    urlParams = urlParams.split("&");
                    for (var m = 0; m < urlParams.length; ++m) {
                        var p = urlParams[m].split("=", 2);
                        if (p.length == 2) {
                            o[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "))
                        }
                    }
                }
                params = $.extend(true, {}, el.params, item.opts[n], o);
                url = $.type(el.url) === "function" ? el.url.call(this, rez, params, item) : format(el.url, rez, params);
                thumb = $.type(el.thumb) === "function" ? el.thumb.call(this, rez, params, item) : format(el.thumb, rez);
                if (provider === "vimeo") {
                    url = url.replace("&%23", "#")
                }
                return false
            });
            if (type) {
                item.src = url;
                item.type = type;
                if (!item.opts.thumb && !(item.opts.$thumb && item.opts.$thumb.length)) {
                    item.opts.thumb = thumb
                }
                if (type === "iframe") {
                    $.extend(true, item.opts, {
                        iframe: {
                            preload: false,
                            scrolling: "no"
                        },
                        smallBtn: false,
                        closeBtn: true,
                        fullScreen: false,
                        slideShow: false
                    });
                    item.opts.slideClass += " fancybox-slide--video"
                }
            } else {
                item.type = "iframe"
            }
        })
    })
})(window.jQuery);
(function(window, document, $) {
    "use strict";
    var requestAFrame = function() {
        return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || function(callback) {
            window.setTimeout(callback, 1e3 / 60)
        }
    }();
    var pointers = function(e) {
        var result = [];
        e = e.originalEvent || e || window.e;
        e = e.touches && e.touches.length ? e.touches : e.changedTouches && e.changedTouches.length ? e.changedTouches : [e];
        for (var key in e) {
            if (e[key].pageX) {
                result.push({
                    x: e[key].pageX,
                    y: e[key].pageY
                })
            } else if (e[key].clientX) {
                result.push({
                    x: e[key].clientX,
                    y: e[key].clientY
                })
            }
        }
        return result
    };
    var distance = function(point2, point1, what) {
        if (!point1 || !point2) {
            return 0
        }
        if (what === "x") {
            return point2.x - point1.x
        } else if (what === "y") {
            return point2.y - point1.y
        }
        return Math.sqrt(Math.pow(point2.x - point1.x, 2) + Math.pow(point2.y - point1.y, 2))
    };
    var isClickable = function($el) {
        return $el.is("a") || $el.is("button") || $el.is("input") || $el.is("select") || $el.is("textarea") || $.isFunction($el.get(0).onclick)
    };
    var hasScrollbars = function(el) {
        var overflowY = window.getComputedStyle(el)["overflow-y"];
        var overflowX = window.getComputedStyle(el)["overflow-x"];
        var vertical = (overflowY === "scroll" || overflowY === "auto") && el.scrollHeight > el.clientHeight;
        var horizontal = (overflowX === "scroll" || overflowX === "auto") && el.scrollWidth > el.clientWidth;
        return vertical || horizontal
    };
    var hasHorizontalScrollbars = function(el) {
        var overflowX = window.getComputedStyle(el)["overflow-x"];
        var horizontal = (overflowX === "scroll" || overflowX === "auto") && el.scrollWidth > el.clientWidth;
        return horizontal
    };
    var isScrollable = function($el) {
        var rez = false;
        while (true) {
            rez = hasScrollbars($el.get(0));
            if (rez) {
                break
            }
            $el = $el.parent();
            if (!$el.length || $el.hasClass("fancybox-slider") || $el.is("body")) {
                break
            }
        }
        return rez
    };
    var isHorizontalScrollable = function($el) {
        var rez = false;
        while (true) {
            rez = hasHorizontalScrollbars($el.get(0));
            if (rez) {
                break
            }
            $el = $el.parent();
            if (!$el.length || $el.hasClass("fancybox-slider") || $el.is("body")) {
                break
            }
        }
        return rez
    };
    var Guestures = function(instance) {
        var self = this;
        self.instance = instance;
        self.$wrap = instance.$refs.slider_wrap;
        self.$slider = instance.$refs.slider;
        self.$container = instance.$refs.container;
        self.destroy();
        self.$wrap.on("touchstart.fb mousedown.fb", $.proxy(self, "ontouchstart"))
    };
    Guestures.prototype.destroy = function() {
        this.$wrap.off("touchstart.fb mousedown.fb touchmove.fb mousemove.fb touchend.fb touchcancel.fb mouseup.fb mouseleave.fb")
    };
    Guestures.prototype.ontouchstart = function(e) {
        var self = this;
        var $target = $(e.target);
        var instance = self.instance;
        var current = instance.current;
        var $content = current.$content || current.$placeholder;
        self.startPoints = pointers(e);
        self.$target = $target;
        self.$content = $content;
        self.canvasWidth = Math.round(current.$slide[0].clientWidth);
        self.canvasHeight = Math.round(current.$slide[0].clientHeight);
        self.startEvent = e;
        if (e.originalEvent.clientX > self.canvasWidth + current.$slide.offset().left) {
            return true
        }
        if (isClickable($target) || isClickable($target.parent()) || isHorizontalScrollable($target)) {
            return
        }
        if (!current.opts.touch) {
            self.endPoints = self.startPoints;
            return self.ontap()
        }
        if (e.originalEvent && e.originalEvent.button == 2) {
            return
        }
        e.stopPropagation();
        if (!isScrollable($target) || self.instance.opts.touch.vertical) {
            e.preventDefault()
        }
        if (!current || self.instance.isAnimating || self.instance.isClosing) {
            return
        }
        if (!self.startPoints || self.startPoints.length > 1 && !current.isMoved) {
            return
        }
        self.$wrap.off("touchmove.fb mousemove.fb", $.proxy(self, "ontouchmove"));
        self.$wrap.off("touchend.fb touchcancel.fb mouseup.fb mouseleave.fb", $.proxy(self, "ontouchend"));
        self.$wrap.on("touchend.fb touchcancel.fb mouseup.fb mouseleave.fb", $.proxy(self, "ontouchend"));
        self.$wrap.on("touchmove.fb mousemove.fb", $.proxy(self, "ontouchmove"));
        self.startTime = (new Date).getTime();
        self.distanceX = self.distanceY = self.distance = 0;
        self.canTap = false;
        self.isPanning = false;
        self.isSwiping = false;
        self.isZooming = false;
        self.sliderStartPos = $.fancybox.getTranslate(self.$slider);
        self.contentStartPos = $.fancybox.getTranslate(self.$content);
        self.contentLastPos = null;
        if (self.startPoints.length === 1 && !self.isZooming) {
            self.canTap = current.isMoved;
            if (current.type === "image" && (self.contentStartPos.width > self.canvasWidth + 1 || self.contentStartPos.height > self.canvasHeight + 1)) {
                $.fancybox.stop(self.$content);
                self.isPanning = true
            } else {
                $.fancybox.stop(self.$slider);
                self.isSwiping = true
            }
            self.$container.addClass("fancybox-controls--isGrabbing")
        }
        if (self.startPoints.length === 2 && current.isMoved && !current.hasError && current.type === "image" && (current.isLoaded || current.$ghost)) {
            self.isZooming = true;
            self.isSwiping = false;
            self.isPanning = false;
            $.fancybox.stop(self.$content);
            self.centerPointStartX = (self.startPoints[0].x + self.startPoints[1].x) * .5 - $(window).scrollLeft();
            self.centerPointStartY = (self.startPoints[0].y + self.startPoints[1].y) * .5 - $(window).scrollTop();
            self.percentageOfImageAtPinchPointX = (self.centerPointStartX - self.contentStartPos.left) / self.contentStartPos.width;
            self.percentageOfImageAtPinchPointY = (self.centerPointStartY - self.contentStartPos.top) / self.contentStartPos.height;
            self.startDistanceBetweenFingers = distance(self.startPoints[0], self.startPoints[1])
        }
    };
    Guestures.prototype.ontouchmove = function(e) {
        var self = this;
        var angle;
        if (!isScrollable($(e.target)) || self.instance.opts.touch.vertical) {
            e.preventDefault()
        }
        self.newPoints = pointers(e);
        if (!self.newPoints || !self.newPoints.length) {
            return
        }
        self.distanceX = distance(self.newPoints[0], self.startPoints[0], "x");
        self.distanceY = distance(self.newPoints[0], self.startPoints[0], "y");
        angle = Math.abs(Math.atan2(self.distanceY, self.distanceX) * 180 / Math.PI);
        if (angle > 60 && angle < 120) {
            self.$wrap.off("touchmove.fb mousemove.fb", $.proxy(self, "ontouchmove"))
        } else {
            e.preventDefault()
        }
        self.distance = distance(self.newPoints[0], self.startPoints[0]);
        if (self.distance > 0) {
            if (self.isSwiping) {
                self.onSwipe()
            } else if (self.isPanning) {
                self.onPan()
            } else if (self.isZooming) {
                self.onZoom()
            }
        }
    };
    Guestures.prototype.onSwipe = function() {
        var self = this;
        var swiping = self.isSwiping;
        var left = self.sliderStartPos.left;
        var angle;
        if (swiping === true) {
            if (Math.abs(self.distance) > 10) {
                if (self.instance.group.length < 2) {
                    self.isSwiping = "y"
                } else if (!self.instance.current.isMoved || self.instance.opts.touch.vertical === false || self.instance.opts.touch.vertical === "auto" && $(window).width() > 800) {
                    self.isSwiping = "x"
                } else {
                    angle = Math.abs(Math.atan2(self.distanceY, self.distanceX) * 180 / Math.PI);
                    self.isSwiping = angle > 45 && angle < 135 ? "y" : "x"
                }
                self.canTap = false;
                self.instance.current.isMoved = false;
                self.startPoints = self.newPoints
            }
        } else {
            if (swiping == "x") {
                if (!self.instance.current.opts.loop && self.instance.current.index === 0 && self.distanceX > 0) {
                    left = left + Math.pow(self.distanceX, .8)
                } else if (!self.instance.current.opts.loop && self.instance.current.index === self.instance.group.length - 1 && self.distanceX < 0) {
                    left = left - Math.pow(-self.distanceX, .8)
                } else {
                    left = left + self.distanceX
                }
            }
            self.sliderLastPos = {
                top: swiping == "x" ? 0 : self.sliderStartPos.top + self.distanceY,
                left: left
            };
            requestAFrame(function() {
                $.fancybox.setTranslate(self.$slider, self.sliderLastPos)
            })
        }
    };
    Guestures.prototype.onPan = function() {
        var self = this;
        var newOffsetX, newOffsetY, newPos;
        self.canTap = false;
        if (self.contentStartPos.width > self.canvasWidth) {
            newOffsetX = self.contentStartPos.left + self.distanceX
        } else {
            newOffsetX = self.contentStartPos.left
        }
        newOffsetY = self.contentStartPos.top + self.distanceY;
        newPos = self.limitMovement(newOffsetX, newOffsetY, self.contentStartPos.width, self.contentStartPos.height);
        newPos.scaleX = self.contentStartPos.scaleX;
        newPos.scaleY = self.contentStartPos.scaleY;
        self.contentLastPos = newPos;
        requestAFrame(function() {
            $.fancybox.setTranslate(self.$content, self.contentLastPos)
        })
    };
    Guestures.prototype.limitMovement = function(newOffsetX, newOffsetY, newWidth, newHeight) {
        var self = this;
        var minTranslateX, minTranslateY, maxTranslateX, maxTranslateY;
        var canvasWidth = self.canvasWidth;
        var canvasHeight = self.canvasHeight;
        var currentOffsetX = self.contentStartPos.left;
        var currentOffsetY = self.contentStartPos.top;
        var distanceX = self.distanceX;
        var distanceY = self.distanceY;
        minTranslateX = Math.max(0, canvasWidth * .5 - newWidth * .5);
        minTranslateY = Math.max(0, canvasHeight * .5 - newHeight * .5);
        maxTranslateX = Math.min(canvasWidth - newWidth, canvasWidth * .5 - newWidth * .5);
        maxTranslateY = Math.min(canvasHeight - newHeight, canvasHeight * .5 - newHeight * .5);
        if (newWidth > canvasWidth) {
            if (distanceX > 0 && newOffsetX > minTranslateX) {
                newOffsetX = minTranslateX - 1 + Math.pow(-minTranslateX + currentOffsetX + distanceX, .8) || 0
            }
            if (distanceX < 0 && newOffsetX < maxTranslateX) {
                newOffsetX = maxTranslateX + 1 - Math.pow(maxTranslateX - currentOffsetX - distanceX, .8) || 0
            }
        }
        if (newHeight > canvasHeight) {
            if (distanceY > 0 && newOffsetY > minTranslateY) {
                newOffsetY = minTranslateY - 1 + Math.pow(-minTranslateY + currentOffsetY + distanceY, .8) || 0
            }
            if (distanceY < 0 && newOffsetY < maxTranslateY) {
                newOffsetY = maxTranslateY + 1 - Math.pow(maxTranslateY - currentOffsetY - distanceY, .8) || 0
            }
        }
        return {
            top: newOffsetY,
            left: newOffsetX
        }
    };
    Guestures.prototype.limitPosition = function(newOffsetX, newOffsetY, newWidth, newHeight) {
        var self = this;
        var canvasWidth = self.canvasWidth;
        var canvasHeight = self.canvasHeight;
        if (newWidth > canvasWidth) {
            newOffsetX = newOffsetX > 0 ? 0 : newOffsetX;
            newOffsetX = newOffsetX < canvasWidth - newWidth ? canvasWidth - newWidth : newOffsetX
        } else {
            newOffsetX = Math.max(0, canvasWidth / 2 - newWidth / 2)
        }
        if (newHeight > canvasHeight) {
            newOffsetY = newOffsetY > 0 ? 0 : newOffsetY;
            newOffsetY = newOffsetY < canvasHeight - newHeight ? canvasHeight - newHeight : newOffsetY
        } else {
            newOffsetY = Math.max(0, canvasHeight / 2 - newHeight / 2)
        }
        return {
            top: newOffsetY,
            left: newOffsetX
        }
    };
    Guestures.prototype.onZoom = function() {
        var self = this;
        var currentWidth = self.contentStartPos.width;
        var currentHeight = self.contentStartPos.height;
        var currentOffsetX = self.contentStartPos.left;
        var currentOffsetY = self.contentStartPos.top;
        var endDistanceBetweenFingers = distance(self.newPoints[0], self.newPoints[1]);
        var pinchRatio = endDistanceBetweenFingers / self.startDistanceBetweenFingers;
        var newWidth = Math.floor(currentWidth * pinchRatio);
        var newHeight = Math.floor(currentHeight * pinchRatio);
        var translateFromZoomingX = (currentWidth - newWidth) * self.percentageOfImageAtPinchPointX;
        var translateFromZoomingY = (currentHeight - newHeight) * self.percentageOfImageAtPinchPointY;
        var centerPointEndX = (self.newPoints[0].x + self.newPoints[1].x) / 2 - $(window).scrollLeft();
        var centerPointEndY = (self.newPoints[0].y + self.newPoints[1].y) / 2 - $(window).scrollTop();
        var translateFromTranslatingX = centerPointEndX - self.centerPointStartX;
        var translateFromTranslatingY = centerPointEndY - self.centerPointStartY;
        var newOffsetX = currentOffsetX + (translateFromZoomingX + translateFromTranslatingX);
        var newOffsetY = currentOffsetY + (translateFromZoomingY + translateFromTranslatingY);
        var newPos = {
            top: newOffsetY,
            left: newOffsetX,
            scaleX: self.contentStartPos.scaleX * pinchRatio,
            scaleY: self.contentStartPos.scaleY * pinchRatio
        };
        self.canTap = false;
        self.newWidth = newWidth;
        self.newHeight = newHeight;
        self.contentLastPos = newPos;
        requestAFrame(function() {
            $.fancybox.setTranslate(self.$content, self.contentLastPos)
        })
    };
    Guestures.prototype.ontouchend = function(e) {
        var self = this;
        var current = self.instance.current;
        var dMs = Math.max((new Date).getTime() - self.startTime, 1);
        var swiping = self.isSwiping;
        var panning = self.isPanning;
        var zooming = self.isZooming;
        self.endPoints = pointers(e);
        self.$container.removeClass("fancybox-controls--isGrabbing");
        self.$wrap.off("touchmove.fb mousemove.fb", $.proxy(this, "ontouchmove"));
        self.$wrap.off("touchend.fb touchcancel.fb mouseup.fb mouseleave.fb", $.proxy(this, "ontouchend"));
        self.isSwiping = false;
        self.isPanning = false;
        self.isZooming = false;
        if (self.canTap) {
            return self.ontap()
        }
        self.velocityX = self.distanceX / dMs * .5;
        self.velocityY = self.distanceY / dMs * .5;
        self.speed = current.opts.speed || 330;
        self.speedX = Math.max(self.speed * .75, Math.min(self.speed * 1.5, 1 / Math.abs(self.velocityX) * self.speed));
        self.speedY = Math.max(self.speed * .75, Math.min(self.speed * 1.5, 1 / Math.abs(self.velocityY) * self.speed));
        if (panning) {
            self.endPanning()
        } else if (zooming) {
            self.endZooming()
        } else {
            self.endSwiping(swiping)
        }
        return
    };
    Guestures.prototype.endSwiping = function(swiping) {
        var self = this;
        if (swiping == "y" && Math.abs(self.distanceY) > 50) {
            $.fancybox.animate(self.$slider, null, {
                top: self.sliderStartPos.top + self.distanceY + self.velocityY * 150,
                left: self.sliderStartPos.left,
                opacity: 0
            }, self.speedY);
            self.instance.close(true)
        } else if (swiping == "x" && self.distanceX > 50) {
            self.instance.previous(self.speedX)
        } else if (swiping == "x" && self.distanceX < -50) {
            self.instance.next(self.speedX)
        } else {
            self.instance.update(false, true, 150)
        }
    };
    Guestures.prototype.endPanning = function() {
        var self = this;
        var newOffsetX, newOffsetY, newPos;
        if (!self.contentLastPos) {
            return
        }
        newOffsetX = self.contentLastPos.left + self.velocityX * self.speed * 2;
        newOffsetY = self.contentLastPos.top + self.velocityY * self.speed * 2;
        newPos = self.limitPosition(newOffsetX, newOffsetY, self.contentStartPos.width, self.contentStartPos.height);
        newPos.width = self.contentStartPos.width;
        newPos.height = self.contentStartPos.height;
        $.fancybox.animate(self.$content, null, newPos, self.speed, "easeOutSine")
    };
    Guestures.prototype.endZooming = function() {
        var self = this;
        var current = self.instance.current;
        var newOffsetX, newOffsetY, newPos, reset;
        var newWidth = self.newWidth;
        var newHeight = self.newHeight;
        if (!self.contentLastPos) {
            return
        }
        newOffsetX = self.contentLastPos.left;
        newOffsetY = self.contentLastPos.top;
        reset = {
            top: newOffsetY,
            left: newOffsetX,
            width: newWidth,
            height: newHeight,
            scaleX: 1,
            scaleY: 1
        };
        $.fancybox.setTranslate(self.$content, reset);
        if (newWidth < self.canvasWidth && newHeight < self.canvasHeight) {
            self.instance.scaleToFit(150)
        } else if (newWidth > current.width || newHeight > current.height) {
            self.instance.scaleToActual(self.centerPointStartX, self.centerPointStartY, 150)
        } else {
            newPos = self.limitPosition(newOffsetX, newOffsetY, newWidth, newHeight);
            $.fancybox.animate(self.$content, null, newPos, self.speed, "easeOutSine")
        }
    };
    Guestures.prototype.ontap = function() {
        var self = this;
        var instance = self.instance;
        var current = instance.current;
        var x = self.endPoints[0].x;
        var y = self.endPoints[0].y;
        x = x - self.$wrap.offset().left;
        y = y - self.$wrap.offset().top;
        if (instance.SlideShow && instance.SlideShow.isActive) {
            instance.SlideShow.stop()
        }
        if (!$.fancybox.isTouch) {
            if (current.opts.closeClickOutside && self.$target.is(".fancybox-slide")) {
                instance.close(self.startEvent);
                return
            }
            if (current.type == "image" && current.isMoved) {
                if (instance.canPan()) {
                    instance.scaleToFit()
                } else if (instance.isScaledDown()) {
                    instance.scaleToActual(x, y)
                } else if (instance.group.length < 2) {
                    instance.close(self.startEvent)
                }
            }
            return
        }
        if (self.tapped) {
            clearTimeout(self.tapped);
            self.tapped = null;
            if (Math.abs(x - self.x) > 50 || Math.abs(y - self.y) > 50 || !current.isMoved) {
                return this
            }
            if (current.type == "image" && (current.isLoaded || current.$ghost)) {
                if (instance.canPan()) {
                    instance.scaleToFit()
                } else if (instance.isScaledDown()) {
                    instance.scaleToActual(x, y)
                }
            }
        } else {
            self.x = x;
            self.y = y;
            self.tapped = setTimeout(function() {
                self.tapped = null;
                instance.toggleControls(true)
            }, 300)
        }
        return this
    };
    $(document).on("onActivate.fb", function(e, instance) {
        if (instance && !instance.Guestures) {
            instance.Guestures = new Guestures(instance)
        }
    });
    $(document).on("beforeClose.fb", function(e, instance) {
        if (instance && instance.Guestures) {
            instance.Guestures.destroy()
        }
    })
})(window, document, window.jQuery);
(function(document, $) {
    "use strict";
    var SlideShow = function(instance) {
        this.instance = instance;
        this.init()
    };
    $.extend(SlideShow.prototype, {
        timer: null,
        isActive: false,
        $button: null,
        speed: 3e3,
        init: function() {
            var self = this;
            self.$button = $('<button data-fancybox-play class="fancybox-button fancybox-button--play" title="Slideshow (P)"></button>').appendTo(self.instance.$refs.buttons);
            self.instance.$refs.container.on("click", "[data-fancybox-play]", function() {
                self.toggle()
            })
        },
        set: function() {
            var self = this;
            if (self.instance && self.instance.current && (self.instance.current.opts.loop || self.instance.currIndex < self.instance.group.length - 1)) {
                self.timer = setTimeout(function() {
                    self.instance.next()
                }, self.instance.current.opts.slideShow.speed || self.speed)
            } else {
                self.stop()
            }
        },
        clear: function() {
            var self = this;
            clearTimeout(self.timer);
            self.timer = null
        },
        start: function() {
            var self = this;
            self.stop();
            if (self.instance && self.instance.current && (self.instance.current.opts.loop || self.instance.currIndex < self.instance.group.length - 1)) {
                self.instance.$refs.container.on({
                    "beforeLoad.fb.player": $.proxy(self, "clear"),
                    "onComplete.fb.player": $.proxy(self, "set")
                });
                self.isActive = true;
                if (self.instance.current.isComplete) {
                    self.set()
                }
                self.instance.$refs.container.trigger("onPlayStart");
                self.$button.addClass("fancybox-button--pause")
            }
        },
        stop: function() {
            var self = this;
            self.clear();
            self.instance.$refs.container.trigger("onPlayEnd").off(".player");
            self.$button.removeClass("fancybox-button--pause");
            self.isActive = false
        },
        toggle: function() {
            var self = this;
            if (self.isActive) {
                self.stop()
            } else {
                self.start()
            }
        }
    });
    $(document).on("onInit.fb", function(e, instance) {
        if (instance && instance.group.length > 1 && !!instance.opts.slideShow && !instance.SlideShow) {
            instance.SlideShow = new SlideShow(instance)
        }
    });
    $(document).on("beforeClose.fb onDeactivate.fb", function(e, instance) {
        if (instance && instance.SlideShow) {
            instance.SlideShow.stop()
        }
    })
})(document, window.jQuery);
(function(document, $) {
    "use strict";
    var fn = function() {
        var fnMap = [
            ["requestFullscreen", "exitFullscreen", "fullscreenElement", "fullscreenEnabled", "fullscreenchange", "fullscreenerror"],
            ["webkitRequestFullscreen", "webkitExitFullscreen", "webkitFullscreenElement", "webkitFullscreenEnabled", "webkitfullscreenchange", "webkitfullscreenerror"],
            ["webkitRequestFullScreen", "webkitCancelFullScreen", "webkitCurrentFullScreenElement", "webkitCancelFullScreen", "webkitfullscreenchange", "webkitfullscreenerror"],
            ["mozRequestFullScreen", "mozCancelFullScreen", "mozFullScreenElement", "mozFullScreenEnabled", "mozfullscreenchange", "mozfullscreenerror"],
            ["msRequestFullscreen", "msExitFullscreen", "msFullscreenElement", "msFullscreenEnabled", "MSFullscreenChange", "MSFullscreenError"]
        ];
        var val;
        var ret = {};
        var i, j;
        for (i = 0; i < fnMap.length; i++) {
            val = fnMap[i];
            if (val && val[1] in document) {
                for (j = 0; j < val.length; j++) {
                    ret[fnMap[0][j]] = val[j]
                }
                return ret
            }
        }
        return false
    }();
    if (!fn) {
        return
    }
    var FullScreen = {
        request: function(elem) {
            elem = elem || document.documentElement;
            elem[fn.requestFullscreen](elem.ALLOW_KEYBOARD_INPUT)
        },
        exit: function() {
            if (this.isFullscreen()) {
                document[fn.exitFullscreen]()
            }
        },
        toggle: function(elem) {
            if (this.isFullscreen()) {
                this.exit()
            } else {
                this.request(elem)
            }
        },
        isFullscreen: function() {
            return Boolean(document[fn.fullscreenElement])
        },
        enabled: function() {
            return Boolean(document[fn.fullscreenEnabled])
        }
    };
    $(document).on({
        "onInit.fb": function(e, instance) {
            var $container;
            if (instance && !!instance.opts.fullScreen && !instance.FullScreen) {
                $container = instance.$refs.container;
                instance.$refs.button_fs = $('<button data-fancybox-fullscreen class="fancybox-button fancybox-button--fullscreen" title="Full screen (F)"></button>').appendTo(instance.$refs.buttons);
                $container.on("click.fb-fullscreen", "[data-fancybox-fullscreen]", function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    FullScreen.toggle($container[0])
                });
                if (instance.opts.fullScreen.requestOnStart === true) {
                    FullScreen.request($container[0])
                }
            }
        },
        "beforeMove.fb": function(e, instance) {
            if (instance && instance.$refs.button_fs) {
                instance.$refs.button_fs.toggle(!!instance.current.opts.fullScreen)
            }
        },
        "beforeClose.fb": function() {
            FullScreen.exit()
        }
    });
    $(document).on(fn.fullscreenchange, function() {
        var instance = $.fancybox.getInstance();
        var $what = instance ? instance.current.$placeholder : null;
        if ($what) {
            $what.css("transition", "none");
            instance.isAnimating = false;
            instance.update(true, true, 0)
        }
    })
})(document, window.jQuery);
(function(document, $) {
    "use strict";
    var FancyThumbs = function(instance) {
        this.instance = instance;
        this.init()
    };
    $.extend(FancyThumbs.prototype, {
        $button: null,
        $grid: null,
        $list: null,
        isVisible: false,
        init: function() {
            var self = this;
            self.$button = $('<button data-fancybox-thumbs class="fancybox-button fancybox-button--thumbs" title="Thumbnails (G)"></button>').appendTo(this.instance.$refs.buttons).on("touchend click", function(e) {
                e.stopPropagation();
                e.preventDefault();
                self.toggle()
            })
        },
        create: function() {
            var instance = this.instance,
                list, src;
            this.$grid = $('<div class="fancybox-thumbs"></div>').appendTo(instance.$refs.container);
            list = "<ul>";
            $.each(instance.group, function(i, item) {
                src = item.opts.thumb || (item.opts.$thumb ? item.opts.$thumb.attr("src") : null);
                if (!src && item.type === "image") {
                    src = item.src
                }
                if (src && src.length) {
                    list += '<li data-index="' + i + '"  tabindex="0" class="fancybox-thumbs-loading"><img data-src="' + src + '" /></li>'
                }
            });
            list += "</ul>";
            this.$list = $(list).appendTo(this.$grid).on("click touchstart", "li", function() {
                instance.jumpTo($(this).data("index"))
            });
            this.$list.find("img").hide().one("load", function() {
                var $parent = $(this).parent().removeClass("fancybox-thumbs-loading"),
                    thumbWidth = $parent.outerWidth(),
                    thumbHeight = $parent.outerHeight(),
                    width, height, widthRatio, heightRatio;
                width = this.naturalWidth || this.width;
                height = this.naturalHeight || this.height;
                widthRatio = width / thumbWidth;
                heightRatio = height / thumbHeight;
                if (widthRatio >= 1 && heightRatio >= 1) {
                    if (widthRatio > heightRatio) {
                        width = width / heightRatio;
                        height = thumbHeight
                    } else {
                        width = thumbWidth;
                        height = height / widthRatio
                    }
                }
                $(this).css({
                    width: Math.floor(width),
                    height: Math.floor(height),
                    "margin-top": Math.min(0, Math.floor(thumbHeight * .3 - height * .3)),
                    "margin-left": Math.min(0, Math.floor(thumbWidth * .5 - width * .5))
                }).show()
            }).each(function() {
                this.src = $(this).data("src")
            })
        },
        focus: function() {
            if (this.instance.current) {
                this.$list.children().removeClass("fancybox-thumbs-active").filter('[data-index="' + this.instance.current.index + '"]').addClass("fancybox-thumbs-active").focus()
            }
        },
        close: function() {
            this.$grid.hide()
        },
        update: function() {
            this.instance.$refs.container.toggleClass("fancybox-container--thumbs", this.isVisible);
            if (this.isVisible) {
                if (!this.$grid) {
                    this.create()
                }
                this.$grid.show();
                this.focus()
            } else if (this.$grid) {
                this.$grid.hide()
            }
            this.instance.update()
        },
        hide: function() {
            this.isVisible = false;
            this.update()
        },
        show: function() {
            this.isVisible = true;
            this.update()
        },
        toggle: function() {
            if (this.isVisible) {
                this.hide()
            } else {
                this.show()
            }
        }
    });
    $(document).on("onInit.fb", function(e, instance) {
        var first = instance.group[0],
            second = instance.group[1];
        if (!!instance.opts.thumbs && !instance.Thumbs && instance.group.length > 1 && ((first.type == "image" || first.opts.thumb || first.opts.$thumb) && (second.type == "image" || second.opts.thumb || second.opts.$thumb))) {
            instance.Thumbs = new FancyThumbs(instance)
        }
    });
    $(document).on("beforeMove.fb", function(e, instance, item) {
        var self = instance && instance.Thumbs;
        if (!self) {
            return
        }
        if (item.modal) {
            self.$button.hide();
            self.hide()
        } else {
            if (instance.opts.thumbs.showOnStart === true && instance.firstRun) {
                self.show()
            }
            self.$button.show();
            if (self.isVisible) {
                self.focus()
            }
        }
    });
    $(document).on("beforeClose.fb", function(e, instance) {
        if (instance && instance.Thumbs) {
            if (instance.Thumbs.isVisible && instance.opts.thumbs.hideOnClosing !== false) {
                instance.Thumbs.close()
            }
            instance.Thumbs = null
        }
    })
})(document, window.jQuery);
(function(document, window, $) {
    "use strict";
    if (!$.escapeSelector) {
        $.escapeSelector = function(sel) {
            var rcssescape = /([\0-\x1f\x7f]|^-?\d)|^-$|[^\x80-\uFFFF\w-]/g;
            var fcssescape = function(ch, asCodePoint) {
                if (asCodePoint) {
                    if (ch === "\0") {
                        return "�"
                    }
                    return ch.slice(0, -1) + "\\" + ch.charCodeAt(ch.length - 1).toString(16) + " "
                }
                return "\\" + ch
            };
            return (sel + "").replace(rcssescape, fcssescape)
        }
    }
    var currentHash = null;

    function parseUrl() {
        var hash = window.location.hash.substr(1);
        var rez = hash.split("-");
        var index = rez.length > 1 && /^\+?\d+$/.test(rez[rez.length - 1]) ? parseInt(rez.pop(-1), 10) || 1 : 1;
        var gallery = rez.join("-");
        if (index < 1) {
            index = 1
        }
        return {
            hash: hash,
            index: index,
            gallery: gallery
        }
    }

    function triggerFromUrl(url) {
        var $el;
        if (url.gallery !== "") {
            $el = $("[data-fancybox='" + $.escapeSelector(url.gallery) + "']").eq(url.index - 1);
            if ($el.length) {
                $el.trigger("click")
            } else {
                $("#" + $.escapeSelector(url.gallery) + "").trigger("click")
            }
        }
    }

    function getGallery(instance) {
        var opts;
        if (!instance) {
            return false
        }
        opts = instance.current ? instance.current.opts : instance.opts;
        return opts.$orig ? opts.$orig.data("fancybox") : opts.hash || ""
    }
    $(function() {
        setTimeout(function() {
            if ($.fancybox.defaults.hash === false) {
                return
            }
            $(window).on("hashchange.fb", function() {
                var url = parseUrl();
                if ($.fancybox.getInstance()) {
                    if (currentHash && currentHash !== url.gallery + "-" + url.index) {
                        currentHash = null;
                        $.fancybox.close()
                    }
                } else if (url.gallery !== "") {
                    triggerFromUrl(url)
                }
            });
            $(document).on({
                "onInit.fb": function(e, instance) {
                    var url = parseUrl();
                    var gallery = getGallery(instance);
                    if (gallery && url.gallery && gallery == url.gallery) {
                        instance.currIndex = url.index - 1
                    }
                }
            });
            triggerFromUrl(parseUrl())
        }, 50)
    })
})(document, window, window.jQuery);
$.fn.isInViewport = function() {
    var elementTop = $(this).offset().top;
    var elementBottom = elementTop + $(this).outerHeight();
    var viewportTop = $(window).scrollTop();
    var viewportBottom = viewportTop + $(window).height();
    return elementBottom > viewportTop && elementTop < viewportBottom
};
$.fn.requestedFilter = function(country, city, cityName, location, locationName) {
    location = location || "";
    var requestEyes = function(country, city, cityName, location) {
        $.ajax({
            url: "/filters/eyes",
            data: {
                country: country,
                city: city,
                location: location
            },
            type: "POST",
            success: function(result) {
                $.each(result.eyes, function(key, eye) {
                    var $element = $('#eyes-filter .requested-filter[data-filterid="' + eye.element + '"]');
                    $element.replaceWith('<a href="/' + cityName + "/" + eye.name + '-eyes.html">' + $element.html() + "</a>")
                })
            },
            error: function(result) {}
        })
    };
    var requestHairs = function(country, city, cityName, location) {
        $.ajax({
            url: "/filters/hairs",
            data: {
                country: country,
                city: city,
                location: location
            },
            type: "POST",
            success: function(result) {
                $.each(result.hairs, function(key, hair) {
                    var $element = $('#hairs-filter .requested-filter[data-filterid="' + hair.element + '"]');
                    $element.replaceWith('<a href="/' + cityName + "/" + hair.name + '-hair.html">' + $element.html() + "</a>")
                })
            },
            error: function(result) {}
        })
    };
    var requestServices = function(country, city, cityName, location, locationName) {
        if ($("#services-filter .requested-filter").length > 0) {
            $.ajax({
                url: "/filters/services",
                data: {
                    country: country,
                    city: city,
                    location: location
                },
                type: "POST",
                success: function(result) {
                    $.each(result.services, function(key, service) {
                        var $element = $('#services-filter .requested-filter[data-filterid="' + service.element + '"]');
                        if ($element.get(0)) {
                            var slug = [locationName, service.name].filter(Boolean).join("-");
                            $element.replaceWith('<a href="/' + cityName + "/js/" + slug + '.html">' + $element.html() + "</a>")
                        }
                    })
                },
                error: function(result) {}
            })
        }
    };
    $(this).on("click.requested-filter", function() {
        $(this).removeClass("requested-filter");
        $(this).off("click.requested-filter");
        requestEyes(country, city, cityName, location);
        requestHairs(country, city, cityName, location);
        requestServices(country, city, cityName, location, locationName)
    })
};

function ratingStars(that) {
    $(that).find(".rating-stars").each(function() {
        var rating = $(this).data("ratingValue");
        var completeStars = Math.floor(rating / 2);
        var halfStars = rating % 2;
        var emptyStars = 5 - (completeStars + halfStars);
        setCompleteStars(this, completeStars);
        setHalfStars(this, halfStars);
        setEmptyStars(this, emptyStars)
    })
}

function setCompleteStars(that, stars) {
    setStars(that, stars, "fa-star")
}

function setHalfStars(that, stars) {
    setStars(that, stars, "fa-star-half-o")
}

function setEmptyStars(that, stars) {
    setStars(that, stars, "fa-star-o")
}

function setStars(that, stars, type) {
    for (var i = 0; i < stars; i++) {
        $(that).append('<i class="fa ' + type + '"></i>')
    }
}
(function($) {
    $.fn.infiniteScroll = function(options, onSuccess, beforeCall) {
        var defaults = {
            page: 2,
            country: "",
            city: "",
            locations: [],
            services: [],
            eyes: [],
            hairs: [],
            nationalities: [],
            isIndependent: false,
            customFilter: "",
            withVideos: false,
            withSexCam: false,
            escortType: "straight",
            scrollStopped: false,
            scrollBottom: 0,
            url: "",
            nbTopEscorts: 0,
            paginationTops: []
        };
        var plugin = this;
        plugin.settings = {};
        var $element = $(this);
        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
            $(window).scroll(function() {
                if ($(window).scrollTop() >= $(document).height() - $(window).height() - parseInt(plugin.settings.scrollBottom)) {
                    plugin.nextPage(plugin.settings.page, plugin.settings.nbTopEscorts)
                }
            })
        };
        plugin.nextPage = function(page, nbTopEscorts) {
            if (!plugin.settings.scrollStopped) {
                plugin.stopScroll();
                var tops = {
                    tops: []
                };
                if (plugin.settings.paginationTops.length > 0) {
                    tops = {
                        "tops[]": [plugin.settings.paginationTops.pop(), plugin.settings.paginationTops.pop()].filter(function(value) {
                            return value != null && value != ""
                        })
                    }
                }
                $.ajax({
                    url: createUrl(page, nbTopEscorts),
                    type: "POST",
                    data: tops,
                    beforeSend: function(jqXHR, settings) {
                        if (beforeCall && typeof beforeCall === "function") {
                            var thumbnails = beforeCall();
                            settings.url += "/" + thumbnails.join(".")
                        }
                    },
                    success: function(results) {
                        plugin.settings.page++;
                        plugin.startScroll();
                        if (onSuccess && typeof onSuccess === "function") {
                            onSuccess(results, plugin.settings.url)
                        }
                    }
                })
            }
            return false
        };
        plugin.stopScroll = function() {
            plugin.settings.scrollStopped = true
        };
        plugin.startScroll = function() {
            plugin.settings.scrollStopped = false
        };
        var createUrl = function(page, nbTopEscorts) {
            var a = plugin.settings.locations.join(".");
            var b = plugin.settings.services.join(".");
            var c = plugin.settings.eyes.join(".");
            var d = plugin.settings.hairs.join(".");
            var e = plugin.settings.nationalities.join(".");
            var f = plugin.settings.city + "/";
            var independent = plugin.settings.isIndependent;
            var customFilterUrl = plugin.settings.customFilter;
            var withVideos = plugin.settings.withVideos;
            var withSexCam = plugin.settings.withSexCam;
            var independentUrl = "";
            var withVideosUrl = "";
            var withSexCamUrl = "";
            var escortType = plugin.settings.escortType;
            var escortTypeUrl = "";
            if (a !== "") {
                a = "loc." + a + "/"
            }
            if (b !== "") {
                b = "srv." + b + "/"
            }
            if (c !== "") {
                c = "e." + c + "/"
            }
            if (d !== "") {
                d = "h." + d + "/"
            }
            if (e !== "") {
                e = "natl." + e + "/"
            }
            if (independent !== false) {
                independentUrl = "independent/"
            }
            if (withVideos !== false) {
                withVideosUrl = "videos/"
            }
            if (withSexCam !== false) {
                withSexCamUrl = "sexcam/";
                if (plugin.settings.country !== "") {
                    f = plugin.settings.country + "/"
                } else {
                    f = "uk/"
                }
            }
            if (escortType === "trv") {
                escortTypeUrl = "ts/"
            }
            plugin.settings.url = "/" + f + escortTypeUrl + "escorts/" + independentUrl + customFilterUrl + withVideosUrl + withSexCamUrl + a + b + c + d + e + page + "/" + nbTopEscorts;
            return plugin.settings.url
        };
        return plugin.init()
    }
})(jQuery);

function quickView(that) {
    $(that).fancybox({
        baseClass: "quick-view-container",
        loop: false,
        slideShow: false,
        fullScreen: false,
        thumbs: false,
        focus: false,
        touch: {
            vertical: false
        },
        ajax: {
            settings: {
                type: "POST"
            }
        },
        type: "ajax",
        baseTpl: '<div class="fancybox-container" role="dialog" tabindex="-1">' + '<div class="fancybox-bg"></div>' + '<div class="fancybox-infobar">' + '<button data-fancybox-previous class="fancybox-button fancybox-button--left quick-view-button quick-view-button--left" title="Previous"></button>' + '<button data-fancybox-next class="fancybox-button fancybox-button--right quick-view-button quick-view-button--right" title="Next"></button>' + "</div>" + '<div class="quick-view-content">' + '<div class="fancybox-slider-wrap">' + '<div class="fancybox-slider"></div>' + "</div>" + "</div>" + "</div>",
        closeTpl: '<button data-fancybox-close class="fancybox-close-small close-quick-view"></button>',
        afterLoad: function(slide) {
            var nbProfiles = slide.group.length,
                index;
            var fancy = $.fancybox.getInstance();
            var url = fancy.current.src;
            var profile = $(".escort-view-profile").first();
            var country = $(".results").data("country");
            var $video = $(".video-gallery .video-container video");
            ratingStars(slide.$refs.container);
            if (slide.firstRun) {
                profile.delay(300).animate({
                    scrollTop: 300
                }, "slow").animate({
                    scrollTop: 0
                }, "slow")
            }
            index = slide.currIndex || 0;
            fancy.$lastFocus = $('[data-fancybox="ajax"]').get(index);
            if (index >= nbProfiles - 3) {
                $(document).scrollTop($(document).height())
            }
            if (window.ga) {
                ga("global.send", "pageview", url);
                ga(country + ".send", "pageview", url)
            }
            if ($(".description-text").html() && $(".description-text").html().trim().length < 450) {
                $(".description-text").removeClass("closed");
                $(".see-more").remove()
            }
            $video.bind("play", function() {
                var that = this;
                if (window.ga) {
                    ga(country + ".send", "playvideo", $(that).data("escort"), $(that).data("video") + "_" + $(that).data("number"))
                }
                $video.each(function() {
                    if (that !== this) {
                        this.pause()
                    }
                })
            });
            $video.bind("click", function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (this.paused === true) {
                    var playPromise = this.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(function(e) {})
                    }
                } else {
                    this.pause()
                }
            })
        },
        afterClose: function(e) {
            var index = e.currIndex;
            e.$lastFocus = $('[data-fancybox="ajax"]').get(index);
            e.scrollTop = e.$lastFocus.offsetTop + $(window).height() / 4
        }
    })
}
(function($) {
    $.fn.bannerAd = function(options, onCompleteCallback) {
        var banner_ad_defaults = {
            nb_banners_per_page: 1,
            nb_pages_with_banner: 1,
            pages_would_show_banner: 6,
            _pages_with_banners: 0,
            _current_page: 1
        };
        var plugin = this;
        var $element = $(this);
        var setShowered = function() {
            plugin.bannerad_settings._pages_with_banners = constrainPagesWithBanner(plugin.bannerad_settings._pages_with_banners + 1)
        };
        var constrainNumberPagesWithBanner = function(value) {
            if (value > plugin.bannerad_settings.pages_would_show_banner) {
                value = plugin.bannerad_settings.pages_would_show_banner
            }
            return value
        };
        var constrainPagesWithBanner = function(value) {
            if (value > plugin.bannerad_settings.nb_pages_with_banner) {
                value = plugin.bannerad_settings.nb_pages_with_banner
            }
            return value
        };
        var pages_remains = function() {
            return plugin.bannerad_settings.pages_would_show_banner - plugin.bannerad_settings._current_page + 1
        };
        var pages_with_banners_remains = function() {
            return plugin.bannerad_settings.nb_pages_with_banner - plugin.bannerad_settings._pages_with_banners
        };
        var setOnCompleteCallback = function() {
            if (onCompleteCallback && typeof onCompleteCallback === "function") {
                $element.on("bannerAd.complete", function(event, data) {
                    onCompleteCallback(event, data)
                })
            }
        };
        plugin.bannerad_settings = {};
        plugin.init = function() {
            plugin.bannerad_settings = $.extend({}, banner_ad_defaults, options);
            plugin.bannerad_settings.nb_pages_with_banner = constrainNumberPagesWithBanner(plugin.bannerad_settings.nb_pages_with_banner);
            setOnCompleteCallback();
            return plugin
        };
        plugin.hasShowered = function() {
            return plugin.bannerad_settings._pages_with_banners === plugin.bannerad_settings.nb_pages_with_banner
        };
        plugin.mustShow = function() {
            var mustShow = false;
            if (plugin.bannerad_settings._current_page <= plugin.bannerad_settings.pages_would_show_banner) {
                if (pages_remains() === pages_with_banners_remains()) {
                    mustShow = true
                } else {
                    mustShow = Math.round(Math.random()) === 1
                }
            }
            return mustShow
        };
        plugin.drawBanner = function() {
            if (!plugin.hasShowered() && plugin.mustShow()) {
                var banners = $(".bestItem");
                $element.find(".result").eq($(banners[0]).data("position")).before(banners);
                $element.trigger("bannerAd.complete", {
                    banners: banners
                });
                setShowered()
            }
            plugin.bannerad_settings._current_page++
        };
        return plugin.init()
    }
})(jQuery);

function kommons(locationSanitizedName, serviceSanitizedName, eyeColorSanitizedName, hairColorSanitizedName, nationalitySanitizedName, citySanitizedName, city, locations, services, eyes, hairs, nationalities, isIndependent, customFilter, withVideos, withSexCam, escortType, totalEscorts, nbEscortsResult, nbTopEscorts, paginationTops) {
    function getGrid() {
        var grid = $(".grid");
        var gridLength = grid.find(".result").length;
        return grid
    }
    var grid = getGrid();
    var loadingResults = $(".loading-more-results");
    var country = $(".results").data("country");
    grid.find(".result").css("opacity", 0);
    ratingStars(grid);
    quickView(grid.find(".result"));
    $("nav.filters-applied .btn").on("click", function() {
        var cityOrCountry = citySanitizedName;
        var filter = $(this).data("filter-applied").toString();
        var criteria = [locationSanitizedName, serviceSanitizedName, eyeColorSanitizedName, hairColorSanitizedName, nationalitySanitizedName].filter(function(value) {
            return value.toString() !== filter && value.toString() !== ""
        }).join("-");
        if (criteria !== "") {
            criteria += ".html"
        } else if (isIndependent !== false && filter !== "independent") {
            criteria = "independent.html"
        } else if (withVideos !== false && filter !== "withVideos") {
            criteria = "escorts-video.html"
        } else if (withSexCam !== false && filter !== "withSexCam") {
            if (escortType === "trv") {
                criteria = "sexcam-ts.html"
            } else {
                criteria = "sexcam-escorts.html"
            }
            citySanitizedName = country
        }
        if (cityOrCountry === "") {
            cityOrCountry = country
        }
        window.location = "/" + cityOrCountry + "/" + criteria
    });
    $(document).on("click", ".call-escort .watch-escort-btn", function() {
        var $self = $(this);
        if (window.ga) {
            ga("global.send", "event", "clickSource", $self.data("source"), $self.data("escort"));
            ga(country + ".send", "event", "clickSource", $self.data("source"), $self.data("escort"))
        }
    });
    $(document).on("click", ".call-escort .call-escort-btn", function() {
        var $self = $(this);
        if (window.ga) {
            ga("global.send", "event", "call", $self.data("escort"), "regular", $self.data("phone"));
            ga(country + ".send", "event", "call", $self.data("escort"), "regular", $self.data("phone"))
        }
    });
    $(document).on("click", ".call-escort .call-sponsored-escort-btn", function() {
        var $self = $(this);
        if (window.ga) {
            ga("global.send", "event", "call", $self.data("escort"), "sponsored", $self.data("phone"));
            ga(country + ".send", "event", "call", $self.data("escort"), "sponsored", $self.data("phone"))
        }
    });
    $(document).on("click", ".whatsapp-escort .whatsapp-sponsored-escort-btn", function() {
        var $self = $(this);
        if (window.ga) {
            ga("global.send", "event", "whatsapp", $self.data("escort"), "sponsored", $self.data("phone"));
            ga(country + ".send", "event", "whatsapp", $self.data("escort"), "sponsored", $self.data("phone"))
        }
    });
    grid.bannerAd({
        pages_would_show_banner: 1
    });
    grid.drawBanner();
    grid.masonry({
        itemSelector: ".grid-item",
        columnWidth: ".grid-sizer",
        gutter: ".gutter-sizer",
        percentPosition: true
    });
    grid.find(".result").animate({
        opacity: 1
    });
    grid.masonry("layout");
    if (grid.children(".result").length >= 12 && totalEscorts !== nbEscortsResult) {
        scrollBottom = $("main").height() - $(".results").height() + $("footer").height();
        grid.infiniteScroll({
            country: country,
            city: city,
            locations: locations,
            services: services,
            eyes: eyes,
            hairs: hairs,
            nationalities: nationalities,
            isIndependent: isIndependent,
            customFilter: customFilter,
            withVideos: withVideos,
            withSexCam: withSexCam,
            escortType: escortType,
            scrollStopped: grid.children(".result").length < 12,
            scrollBottom: scrollBottom,
            nbTopEscorts: nbTopEscorts,
            paginationTops: paginationTops
        }, function(results, url) {
            var newResults = $(results).css("opacity", 0);
            newResults.removeClass("main-load");
            if (newResults.length < 12) {
                grid.stopScroll()
            }
            if (!grid.isInViewport()) {
                $(window).scrollTop($(".grid").height() + $(".grid").offset().top)
            }
            grid.append(newResults).imagesLoaded(function() {
                newResults.animate({
                    opacity: 1
                });
                grid.masonry("appended", newResults);
                grid.masonry("layout")
            });
            ratingStars(newResults.filter(".result"));
            quickView($(".grid").find(".result"));
            if ($.fancybox.getInstance()) {
                $.fancybox.getInstance().createGroup(newResults.filter(".result"));
                $.fancybox.getInstance().updateControls(true)
            }
            loadingResults.addClass("hidden");
            grid.drawBanner();
            if (window.ga) {
                ga("global.send", "pageview", url);
                ga(country + ".send", "pageview", url)
            }
        }, function() {
            loadingResults.removeClass("hidden");
            var thumbnails = [];
            grid.find(".result-photo").slice(-36).each(function() {
                thumbnails.push($(this).data("thumbnail-hash"))
            });
            return thumbnails
        })
    }
    var $infoServices = $(".info-services");
    $("#info-services").on("mouseenter", function() {
        var infoOffset = $(this).offset();
        var infoHeight = $(this).height();
        var infoWidth = $(this).width();
        $infoServices.removeClass("hidden").offset({
            top: infoOffset["top"] + infoHeight,
            left: infoOffset["left"] + infoWidth
        })
    }).on("mouseleave", function() {
        $infoServices.offset({
            top: 0,
            left: 0
        }).addClass("hidden")
    }).on("touchstart", function() {
        var infoOffset = $(this).offset();
        var infoHeight = $(this).height();
        var infoWidth = $(this).width();
        if ($infoServices.hasClass("hidden")) {
            $infoServices.removeClass("hidden").offset({
                top: infoOffset["top"] + infoHeight,
                left: infoOffset["left"] + infoWidth
            })
        } else {
            $infoServices.offset({
                top: 0,
                left: 0
            }).addClass("hidden")
        }
    });
    $(document).on("scroll", function() {
        if (!$infoServices.hasClass("hidden")) {
            $infoServices.offset({
                top: 0,
                left: 0
            }).addClass("hidden")
        }
    });
    $(document).on("click", ".see-more", function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(".description-text").removeClass("closed");
        $(this).remove()
    });
    $(document).on("click", ".see-more-videos", function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(".video-gallery .video-container").removeClass("hidden");
        $(this).remove()
    })
}
$.fn.report = function(selector, modal, content) {
    var $content = $(modal).find(content);
    var getReportModal = function() {
        $(this).focusin();
        $(modal).addClass("report");
        $(modal).on("hidden.bs.modal", function(e) {
            $(this).removeClass("report");
            $content.empty()
        });
        $(modal).on("hide.bs.modal", function(e) {
            $(selector).parent().parent().find(".btn").prop("disabled", false);
            $(selector).focus()
        });
        $content.empty();
        $.ajax({
            url: "/report-problem",
            type: "GET",
            success: function(result) {
                drawWidget(result)
            }
        })
    };
    var doReport = function() {
        var data = {
            country: $(selector).data("country"),
            ad: $(selector).data("ad"),
            type: $(selector).data("type"),
            reason: $(this).val(),
            others: $(".other-reasons").val(),
            email: $("#email").val()
        };
        $.ajax({
            url: "/report-problem",
            type: "POST",
            data: data,
            success: function(result) {
                $content.empty();
                $content.append(result)
            }
        })
    };
    var drawWidget = function(result) {
        $content.append(result);
        $content.find("button").each(function() {
            if (!$(this).hasClass("close")) {
                if ($(this).val() !== "OTHERS") {
                    $(this).addClass("report-option")
                } else {
                    $(this).addClass("others-option");
                    $(".other-option-text button").val($(this).val())
                }
                $(".other-option-text button").addClass("report-option")
            }
        });
        $(modal).modal();
        $(selector).parent().parent().find(".btn").prop("disabled", true);
        $(selector).prop("disabled", false);
        $(".report-option").on("click", doReport);
        $(".others-option").on("click", function() {
            $(".buttons-panel").slideToggle();
            $(".other-option-text").slideToggle()
        })
    };
    $(this).on("click", selector, getReportModal)
};



function fakeCardReadMore() {
    if ($(".bestItem .bestItem-content").length > 0) {
        var hasToggled = false;
        $(window).scroll(function(event) {
            if (!hasToggled) {
                $(".bestItem").addClass("read-more");
                $(".grid").masonry();
                hasToggled = true;
                $(".bestItem.read-more .read-more a").click(function() {
                    $(this).parents(".bestItem").removeClass("read-more");
                    $(".grid").masonry();
                    return false
                })
            }
        })
    }
}