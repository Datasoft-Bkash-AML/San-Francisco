"use strict";
if (void 0 !== rey || void 0 !== window.rey) console.error('Rey needs the "rey" global variable and will override the existing one.'), rey = {components: {}}; else var rey = {components: {}};
var ReyTheme = function () {
	var e = this, t = this;

	function i() {
	}

	this.validation = {}, this.util = {}, this.dom = {}, this.___ = {}, this.vars = {}, this.legacy = {}, this.headerHeight = null, this.elements = {
		html: document.querySelector(":root"),
		body: document.body,
		header: document.querySelector(".rey-siteHeader:not(.--hfx-spacer)"),
		footer: document.querySelector(".rey-siteFooter"),
		site_overlay: document.querySelector(".rey-overlay--site"),
		siteWrapper: document.querySelector(".rey-siteWrapper"),
		sitePreloader: document.getElementById("rey-site-preloader")
	}, this.getElement = function (t) {
		return e.elements[t] ? e.elements[t] : t
	}, "undefined" == typeof reyParams && (console.log("`reyParams` is an essential variable to Rey and must be properly loaded at all times. In case you are loading the JavaScript delayed, make sure to exclude `rey-script`."), window.reyParams = {}), this.params = "object" == typeof reyParams && reyParams ? reyParams : {}, this.validation.isArray = Array.isArray || function (e) {
		return "[object Array]" === toString.call(e)
	}, this.validation.isNodeList = function (e) {
		return e instanceof NodeList
	}, this.validation.isEmpty = function (e) {
		return e.length = 0
	}, this.validation.isNull = function (e) {
		return null === e
	}, this.validation.typeOf = function (e, t) {
		return typeof t === e
	}, this.validation.isBoolean = function (e) {
		return !0 === e || !1 === e
	}, this.util.slice = function (e, t, i) {
		return Array.prototype.slice.call(e, t, i)
	}, this.util.apply = function (t) {
		return t.bind.apply(t, [null].concat(e.util.slice(arguments, 1)))
	}, this.util.isPositionAtBottomEdge = function (e, i) {
		return t.dom.offset(e).top > window.innerHeight * ((i || 80) / 100)
	}, this.validation.isFunction = this.util.apply(this.validation.typeOf, "function"), this.validation.isString = this.util.apply(this.validation.typeOf, "string"), this.validation.isUndefined = this.util.apply(this.validation.typeOf, "undefined"), this.validation.isJSON = function (e) {
		try {
			return JSON.parse(e) && !!e
		} catch (e) {
			return !1
		}
	}, this.validation.isObject = function (t) {
		return !e.validation.isNull(t) && e.validation.typeOf("object", t)
	}, this.validation.isEmptyObject = function (e) {
		return 0 === Object.keys(e).length
	}, this.validation.matches = function (t, i) {
		return Element.prototype.matches || (Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector), e.validation.isHTMLElement(t) && t.matches.call(t, i)
	}, this.validation.testSelector = function (e) {
		document.querySelector("*");
		try {
			document.querySelector(e)
		} catch (e) {
			return !1
		}
		return !0
	}, this.validation.isInstanceOf = function (e, t) {
		if (null === e) return !1;
		let i = e.__proto__;
		for (; null !== i;) {
			if (i.constructor.name === t) return !0;
			i = i.__proto__
		}
		return !1
	}, this.validation.isHTMLElement = function (t) {
		return void 0 !== window.elementor && window.elementor.$preview && window.elementor.$preview.length ? e.validation.isInstanceOf(t, "HTMLElement") : t instanceof HTMLElement
	}, this.validation.matchYoutubeUrl = function (e) {
		var t = /^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
		return !!e.match(t) && e.match(t)[1]
	}, this.validation.isValidURL = function (e) {
		try {
			return new URL(e), !0
		} catch (e) {
			return !1
		}
	}, this.validation.isJQuery = function (e) {
		return "object" == typeof e && null != e && null != e.jquery
	}, this.util.debounce = function (e, t, i) {
		var s, r, n, o, a, l = function () {
			var d = Date.now - o;
			d < t && d >= 0 ? s = setTimeout(l, t - d) : (s = null, i || (a = e.apply(n, r), s || (n = r = null)))
		};
		return function () {
			n = this, r = arguments, o = Date.now;
			var d = i && !s;
			return s || (s = setTimeout(l, t)), d && (a = e.apply(n, r), n = r = null), a
		}
	}, this.util.wait = {
		timeout: 400, styles: function (e) {
			if (!0 === rey.___.lazyStylesheets) return e();
			setTimeout(rey.util.wait.styles, this.timeout, ...arguments)
		}
	}, this.util.getRandomInt = function (e) {
		return Math.floor(Math.random() * Math.floor(e))
	}, this.util.minTwoDigits = function (e) {
		return (e < 10 ? "0" : "") + e
	}, this.util.ls = {
		set: function (e, t, i) {
			if (e && t && i) {
				var s = {value: t, expires_at: (new Date).getTime() + i / 1};
				localStorage.setItem(e.toString(), JSON.stringify(s))
			}
		}, get: function (e) {
			if (!e) return null;
			var t = JSON.parse(localStorage.getItem(e.toString()));
			if (null !== t) {
				if (!(null !== t.expires_at && t.expires_at < (new Date).getTime())) return t.value;
				localStorage.removeItem(e.toString())
			}
			return null
		}
	}, this.util.expiration = {
		min: 6e4,
		hr: 36e5,
		day: 864e5,
		week: 6048e5,
		month: 18144e6,
		year: 217728e6
	}, this.util.raf = function (e) {
		return requestAnimationFrame(e)
	}, this.util.simpleHash = function (e) {
		let t = 0;
		for (let i = 0; i < e.length; i++) t = (t << 5) - t + e.charCodeAt(i), t |= 0;
		return t.toString(16)
	}, this.util.getUrlVars = function (t) {
		var i, s = {};
		e.validation.isUndefined(t) && (t = window.location.href);
		var r = t.slice(t.indexOf("?") + 1).split("&");
		if (1 === r.length && t === r[0]) return s;
		for (var n = 0; n < r.length; n++) s[(i = r[n].split("="))[0]] = i[1];
		return s
	}, this.util.setUrlVars = function (t, i) {
		if (t = t || {}, e.validation.isUndefined(i) && (i = window.location.href), e.validation.isEmptyObject(t)) return i;
		var s = "?";
		return -1 !== i.indexOf("?") && (s = "&"), i + s + e.util.serialize(t)
	}, this.util.serialize = function (t, i) {
		var s, r = [];
		for (s in t) if (t.hasOwnProperty(s)) {
			var n = i ? i + "[" + s + "]" : s, o = t[s];
			r.push(null !== o && "object" == typeof o ? e.util.serialize(o, n) : encodeURIComponent(n) + "=" + encodeURIComponent(o))
		}
		return r.join("&")
	}, this.util.getFormData = function (e, t) {
		t = t || "input, textarea, select";
		var i = {};
		return e.querySelectorAll(t).forEach((e => {
			var t = e.getAttribute("name");
			t && (i[t] = e.value)
		})), i
	}, this.util.versionCompare = function (e, t) {
		if (typeof e + typeof t != "stringstring") return !1;
		for (var i = e.split("."), s = t.split("."), r = 0, n = Math.max(i.length, s.length); r < n; r++) {
			if (i[r] && !s[r] && parseInt(i[r]) > 0 || parseInt(i[r]) > parseInt(s[r])) return 1;
			if (s[r] && !i[r] && parseInt(s[r]) > 0 || parseInt(i[r]) < parseInt(s[r])) return -1
		}
		return 0
	}, this.util.noop = i, this.util.csv = function (e) {
		return this.validation.isArray(e) ? e.map((e => JSON.stringify(e))).join(",") : e
	}, this.util.getNumber = function (e) {
		return t.validation.isString(e) ? parseInt(e.replace(/\D/g, "")) : e
	};
	var s = function (t, s) {
		var r = this;
		this.ok = 0, this.err = 0, this.elem, this.opts = {
			onComplete: i,
			onProgress: i,
			onLoad: i,
			onError: i
		}, this.init = function () {
			if (e.validation.isFunction(s) ? this.opts.onComplete = s : this.opts = Object.assign(this.opts, s || {}), t) {
				if (e.validation.isJQuery(t)) {
					if (1 === t.length) this.elem = [t.get(0)]; else if (t.length > 1) {
						var i = [];
						for (let e = 0; e < t.length; e++) i.push(t[e]);
						this.elem = i
					}
				} else if (t.nodeName && "IMG" == t.nodeName) this.elem = [t]; else if (e.validation.isString(t)) {
					var n = document.querySelector(t);
					n && (this.elem = n.getElementsByTagName("img"))
				} else null == t.length && ("IMG" == t.nodeName ? this.elem = [t] : this.elem = t.getElementsByTagName("img"));
				if (!this.elem) return this.opts.onComplete();
				if (this.total = this.elem.length, !this.total) return this.opts.onComplete();
				Object.keys(this.elem).forEach((e => {
					var t = r.elem[e];
					if (t.complete) return r.onload(t);
					t.onload = r.onload(t), t.onerror = r.onerror(t)
				}))
			}
		}, this.isDone = function () {
			var t = (this.ok + this.err) / this.total;
			((1 === t ? this.opts.onComplete : this.opts.onProgress) || e.util.noop)(t, {
				failed: r.err,
				loaded: r.ok,
				total: r.total
			})
		}, this.onload = function (e) {
			r.ok++, r.opts.onLoad(e), r.isDone()
		}, this.onerror = function (e) {
			r.err++, r.opts.onError(e), r.isDone()
		}, this.init()
	};
	this.util.imagesLoaded = function () {
		new s(...arguments)
	}, this.util.alreadyLoaded = function (e) {
		return !e || !!e.hasAttribute("data-loaded") || void e.setAttribute("data-loaded", "")
	}, this.dom.hasClass = function (e, t) {
		return e.classList.contains(t)
	}, this.dom.addClass = function (t, i) {
		e.getElement(t).classList.add(...i.split(" "))
	}, this.dom.removeClass = function (t, i) {
		e.getElement(t).classList.remove(...i.split(" "))
	}, this.dom.setProperty = function (e, t, i) {
		i || (i = document.documentElement), i.style.setProperty(e, t)
	}, this.dom.setProperties = function (e, t) {
		Object.keys(e).forEach((i => {
			t.style.setProperty("--" + i, e[i])
		}))
	}, this.dom.getProperty = function (e, t, i) {
		return t || (t = document.documentElement), !0 === i ? getComputedStyle(t).getPropertyValue(e) : t.style.getPropertyValue(e)
	}, this.dom.offset = function (e) {
		let t = 0, i = 0;
		for (; null !== e;) t += e.offsetTop, i += e.offsetLeft, e = e.offsetParent;
		return {top: t, left: i}
	}, this.dom.wrap = function (e, t) {
		e.parentNode.insertBefore(t, e), t.appendChild(e)
	}, this.dom.wrapWithMarkup = function (e, t, i) {
		e.outerHTML = t + e.outerHTML + i
	}, this.dom.children = function (t, i) {
		if (e.validation.testSelector(":scope")) return t.querySelectorAll(e.dom.addInnerClassesToSelector(i, ":scope > "));
		var s = t ? e.util.slice(t.children) : [];
		return i ? s.filter((function (t) {
			return e.validation.matches(t, i)
		})) : s
	}, this.dom.getNodeIndex = function (e) {
		for (var t = e.parentNode.children, i = t.length; i--;) if (t[i] == e) return i
	}, this.dom.getSiblings = function (e, t) {
		const i = [];
		let s;
		s = t ? rey.dom.children(e.parentNode, t) : e.parentNode.children;
		for (const t of s) t !== e && i.push(t);
		return i
	}, this.dom.parents = function (e, t) {
		let i = [];
		for (; e.parentNode !== document.body;) e.matches(t) && i.push(e), e = e.parentNode;
		return i
	}, this.dom.parentsUntil = function (t, i, s) {
		const r = [];
		for (; t && t !== document && (!i || !e.validation.matches(t, i)); t = t.parentNode) s ? e.validation.matches(t, s) && r.push(t) : r.push(t);
		return r
	}, this.dom.prevAll = e => {
		const t = [];
		let i = e.parentNode.firstElementChild;
		for (; i !== e;) t.push(i), i = i.nextElementSibling;
		return t
	}, this.dom.notChildOf = (e, t) => Array.from(document.querySelectorAll(e)).filter((e => !e.closest(t))), this.dom.contains = function (t, i) {
		var s = e.validation.isJQuery(i) ? i[0] : i;
		return t.contains(s)
	}, this.dom.getCssStyle = function (e, t) {
		var i = getComputedStyle(e);
		if (rey.validation.isString(t)) return i[t] ? i[t] : "";
		var s = {};
		return t.forEach((e => {
			s[e] = i[e] ? i[e] : ""
		})), s
	}, this.dom.getNodeListArray = function (e, t) {
		if (t = t || document, !e) return [t];
		var i = [];
		return e.length ? rey.validation.isJQuery(e) || rey.validation.isObject(e) ? i = Object.values(e).filter((e => rey.validation.isHTMLElement(e))) : rey.validation.isArray(e) && (i = e) : i = [e], i
	}, this.dom.getNumberProperty = function (e, t, i) {
		i = i || 0;
		var s = getComputedStyle(e);
		if (!s) return i;
		var r = s.getPropertyValue(t);
		return r ? isNaN(r) ? i : parseInt(r) : i
	}, this.dom.addEventListener = function (t, i, s) {
		(s = s || document).addEventListener(t, (function (t) {
			e.validation.isString(t.detail) ? i(t, t.detail) : e.validation.isArray(t.detail) && i(t, ...t.detail)
		}))
	}, this.dom.trigger = function (e, t, i) {
		(t = t || document).dispatchEvent(new CustomEvent(e, {detail: i}))
	}, this.dom.addInnerClassesToSelector = function (e, t, i) {
		return e.split(",").map((function (e) {
			return (t || "") + e.trim() + (i || "")
		})).join(",")
	}, this.dom.insertHtml = function (e, t, i) {
		e.insertAdjacentHTML(t, i)
	}, this.dom.createElementFromHTML = function (e, i) {
		var s = document.createElement("template");
		if (!t.validation.isString(e)) return e;
		var r = e.trim();
		s.innerHTML = r;
		var n = s.content.firstChild;
		return !t.validation.isEmptyObject(i || {}) && Object.keys(i).forEach((e => {
			n.setAttribute(e, i[e])
		})), n
	}, this.dom.delegate = function (e, t, i, s) {
		t.split(" ").forEach((t => e.addEventListener(t, (function (e) {
			let t = e.target.closest(i);
			e.target && t && (e.initiator = t, s(e))
		}))))
	}, this.dom.createEl = function (e, i) {
		var s = Object.assign({target: document, class: "", attributes: {}, text: "", appendTo: !1}, i || {}),
			r = s.target.createElement(e);
		return s.class && t.dom.addClass(r, s.class), s.text && (r.textContent = s.text), s.attributes && Object.keys(s.attributes).length && Object.keys(s.attributes).forEach((e => {
			r.setAttribute(e, s.attributes[e])
		})), s.appendTo && s.appendTo.append(r), r
	}, this.dom.normalizeCollection = function (e) {
		if (t.validation.isString(e)) e = document.querySelectorAll(e); else if (e instanceof HTMLElement) e = [e]; else if (e.__proto__ && e.__proto__.constructor.name && 0 === e.__proto__.constructor.name.toLowerCase().indexOf("html")) e = [e]; else if (t.validation.isJQuery(e)) if (1 === e.length) e = [e.get(0)]; else {
			if (!(e.length > 1)) return [];
			var i = [];
			for (let t = 0; t < e.length; t++) i.push(e[t]);
			e = i
		}
		return e
	}, this.dom.empty = function (e) {
		for (; e.firstChild && e.removeChild(e.firstChild);) ;
	}, this.dom.isHidden = function (e) {
		return null === e.offsetParent
	}, this.jquery = {}, this.jquery.addEventListener = function (e, i, s) {
		t.validation.isUndefined(jQuery) || jQuery(s || document).on(e, i)
	}, this.jquery.trigger = function (e, i, s) {
		t.validation.isUndefined(jQuery) || jQuery(i).trigger(e, s || [])
	}, this.animation = {}, this.animation.slideUp = function (e, t) {
		var i = e.style.display;
		"none" !== i && ("" === i && 0 === e.offsetHeight && 0 === e.offsetWidth || (t = t || 500, e.style.transitionProperty = "height, margin, padding", e.style.transitionDuration = t + "ms", e.style.height = e.offsetHeight + "px", e.offsetHeight, e.style.overflow = "hidden", e.style.height = 0, e.style.paddingTop = 0, e.style.paddingBottom = 0, e.style.marginTop = 0, e.style.marginBottom = 0, setTimeout((() => {
			e.style.display = "none", e.style.removeProperty("height"), e.style.removeProperty("padding-top"), e.style.removeProperty("padding-bottom"), e.style.removeProperty("margin-top"), e.style.removeProperty("margin-bottom"), e.style.removeProperty("overflow"), e.style.removeProperty("transition-duration"), e.style.removeProperty("transition-property")
		}), t)))
	}, this.animation.slideDown = function (t, i, s) {
		i = i || 500, t.style.removeProperty("display");
		let r = e.dom.getCssStyle(t, "display");
		"none" === r && (r = "block"), t.style.display = r;
		let n = t.offsetHeight;
		t.style.overflow = "hidden", t.style.height = 0, t.style.paddingTop = 0, t.style.paddingBottom = 0, t.style.marginTop = 0, t.style.marginBottom = 0, t.offsetHeight, t.style.transitionProperty = "height, margin, padding", t.style.transitionDuration = i + "ms", t.style.height = n + "px", t.style.removeProperty("padding-top"), t.style.removeProperty("padding-bottom"), t.style.removeProperty("margin-top"), t.style.removeProperty("margin-bottom"), setTimeout((() => {
			t.style.removeProperty("height"), t.style.removeProperty("overflow"), t.style.removeProperty("transition-duration"), t.style.removeProperty("transition-property"), s && s()
		}), i)
	}, this.animation.slideToggle = function (e, i) {
		return i = i || 500, "none" === t.dom.getCssStyle(e, "display") ? t.animation.slideDown(e, i) : t.animation.slideUp(e, i)
	}, this.animation.fadeOut = function (e, t) {
		e.style.opacity = 1, function i() {
			(e.style.opacity -= .1) < 0 ? (e.style.display = "none", t && t()) : requestAnimationFrame(i)
		}()
	}, this.animation.fadeIn = function (e, t, i) {
		e.style.opacity = 0, e.style.display = t || "block", function t() {
			var s = parseFloat(e.style.opacity);
			(s += .1) > 1 ? i && i() : (e.style.opacity = s, requestAnimationFrame(t))
		}()
	}, this.log = function (e) {
		this.params.debug && (rey.validation.isObject(e) ? ("undefined" !== e.script && e.script && console.log(e.script + " is undefined"), "undefined" !== e.message && e.message && console.log(e.message)) : rey.validation.isString(e) && console.log(e))
	}, this.hooks = new function () {
		var e = {
			removeFilter: function (t, i) {
				return "string" == typeof t && s("filters", t, i), e
			}, applyFilters: i, applyFilter: i, addFilter: function (t, i, s, n) {
				return "string" == typeof t && "function" == typeof i && r("filters", t, i, s = parseInt(s || 10, 10), n), e
			}, removeAction: function (t, i) {
				return "string" == typeof t && s("actions", t, i), e
			}, doAction: function () {
				var t = Array.prototype.slice.call(arguments), i = t.shift();
				return "string" == typeof i && n("actions", i, t), e
			}, doActionDeprecated: function () {
				var i = Array.prototype.slice.call(arguments), s = i.shift();
				return "string" == typeof s && (-1 !== Object.keys(t.actions).indexOf(s) && console.error(`${s} is deprecated and will be removed in a future update. Please use another hook.`), n("actions", s, i)), e
			}, addAction: function (t, i, s, n) {
				return "string" == typeof t && "function" == typeof i && (s = parseInt(s || 10, 10), -1 !== t.indexOf(" ") ? t.split(" ").forEach((e => {
					r("actions", e, i, s, n)
				})) : r("actions", t, i, s, n)), e
			}, storage: function () {
				return t
			}
		}, t = {actions: {}, filters: {}};

		function i() {
			var t = Array.prototype.slice.call(arguments), i = t.shift();
			return "string" == typeof i ? n("filters", i, t) : e
		}

		function s(e, i, s, r) {
			if (t[e][i]) if (s) {
				var n, o = t[e][i];
				if (r) for (n = o.length; n--;) {
					var a = o[n];
					a.callback === s && a.context === r && o.splice(n, 1)
				} else for (n = o.length; n--;) o[n].callback === s && o.splice(n, 1)
			} else t[e][i] = []
		}

		function r(e, i, s, r, n) {
			var o = {callback: s, priority: r, context: n}, a = t[e][i];
			a ? (a.push(o), a = function (e) {
				for (var t, i, s, r = 1, n = e.length; r < n; r++) {
					for (t = e[r], i = r; (s = e[i - 1]) && s.priority > t.priority;) e[i] = e[i - 1], --i;
					e[i] = t
				}
				return e
			}(a)) : a = [o], t[e][i] = a
		}

		function n(e, i, s) {
			var r = t[e][i];
			if (!r) return "filters" === e && s[0];
			var n = 0, o = r.length;
			if ("filters" === e) for (; n < o; n++) s[0] = r[n].callback.apply(r[n].context, s); else for (; n < o; n++) r[n].callback.apply(r[n].context, s);
			return "filters" !== e || s[0]
		}

		return e
	}, this.frontend = {}, this.frontend.scroll = new function () {
		this.isDisabled = !1, this.cssClass = "--no-scroll", this.disable = function () {
			this.isDisabled || (e.elements.body.classList.add(this.cssClass), this.isDisabled = !0)
		}, this.enable = function () {
			this.isDisabled && (e.elements.body.classList.remove(this.cssClass), this.isDisabled = !1)
		}
	}, this.frontend.overlay = new function () {
		var e = this;
		this.keepOpen = null, this._createdSite = null, this._opened = null, this._classes = {}, this.open = function (t, i) {
			if (this._opened) {
				if (t === this._opened) return;
				this.close()
			}
			this.type = t || "header", rey.validation.isString(this.type) || (t = rey.elements.header && rey.dom.contains(rey.elements.header, t) ? "header" : "site"), this.options = Object.assign({
				id: "",
				cssClass: "",
				darken: !1,
				scroll: !1,
				click: !0
			}, i || {}), this._classes = {}, this._classes.main = this.type + "-overlay--is-opened", this.options.darken && (this._classes.darken = "--overlay-darken"), this.options.click || (this._classes.nox = "--no-x"), this.options.id && (this._classes.src = "--src-" + this.options.id, this._classes.osrc = "--o-src-" + this.options.id), this.options.cssClass && this.options.cssClass.split(" ").forEach(((t, i) => {
				e._classes[i] = t
			})), this.toggleClasses(!0), this._opened = this.type, this.options.scroll && rey.frontend.scroll.disable()
		}, this.toggleClasses = function (t) {
			Object.keys(e._classes).forEach((i => {
				rey.elements.body.classList.toggle(e._classes[i], t)
			}))
		}, this.closeAll = this.close, this.close = function () {
			this.keepOpen || this._opened && (this.toggleClasses(!1), this._opened = null, rey.frontend.scroll.enable())
		}, this.isOpened = function (e) {
			return e ? this._opened === e : this._opened
		}
	}, this.frontend.svgIcon = new function () {
		var e = this;
		this.__svgs = {}, this.__fetches = {}, this.customID, this.svgHolder = document.getElementById("rey-svg-holder"), this.get = function (i, s) {
			if (i) return s ? (e.addToTarget(i, s), this.getTargetSelector()) : (setTimeout((() => {
				t.frontend.inView({
					target: `svg[data-icon-placeholder="${i}"]`, cb: function () {
						e.replacePlaceholder(i)
					}, once: !0
				})
			}), 1e3), `<svg style="width:1px;height:1px;display:inline-block;" data-icon-placeholder="${i}"></svg>`)
		}, this.addToTarget = function (i, s) {
			if (this.customID = (new Date).getTime(), !this.__svgs[i]) return this.fetch(i, (function () {
				return e.addToTarget(i, s)
			}));
			if (s) {
				var r = t.dom.createElementFromHTML(this.__svgs[i], {"data-svg-id": this.customID});
				s.appendChild(r)
			}
		}, this.getTargetSelector = function () {
			var e = this.customID;
			return this.customID = null, `svg[data-svg-id="${e}"]`
		}, this.replacePlaceholder = function (t) {
			if (!this.__svgs[t]) return this.fetch(t, (function () {
				e.replacePlaceholder(t)
			}));
			var i = this.__svgs[t] || !1;
			if (i) {
				var s = document.querySelectorAll(`svg[data-icon-placeholder="${t}"]`);
				s.length && s.forEach((e => {
					e.insertAdjacentHTML("afterend", i), e.remove()
				}))
			}
		}, this.fetch = function (i, s) {
			var r = function (t) {
				e.__svgs[i] = t, s.call(e, t, i)
			};
			if (t.params.svg_icons[i]) return r(t.params.svg_icons[i]);
			if (!this.__fetches[i]) {
				if (this.svgHolder) {
					var n = this.svgHolder.querySelector(`svg[data-icon-id="${i}"]`);
					if (n) return r(n.outerHTML)
				}
				var o = t.params.svg_icons_path.toString().replace("%%icon%%", i);
				return this.__fetches[i] = o, t.ajax.request("get_svg_icon", {
					ss: `svg_icon_${i}`,
					data: {id: i},
					cb: function (e) {
						var t = e && e.success ? e.data : "";
						r(t)
					}
				})
			}
		}, this.getArrows = function (e) {
			var t = this.get("arrow-long"), i = '<div class="rey-arrowSvg rey-arrowSvg--%%s%%">', s = "</div>",
				r = {prev: i.replace("%%s%%", "left") + t + s, next: i.replace("%%s%%", "right") + t + s};
			return e ? r[e] : r
		}
	}, this.frontend.inView = function (e) {
		if (!(e = Object.assign({
			target: [],
			cb: i,
			onHide: i,
			once: !0,
			offset: .2,
			inTab: !1,
			addClass: !0,
			toggleClass: !1,
			cssProp: !1,
			cssPropTarget: !1,
			ratioTolerance: !1,
			delay: !1,
			disableHideDownwards: !1,
			root: null,
			rootMargin: "0px 0px 0px 0px",
			name: ""
		}, e)).target) return;
		e.target = rey.dom.normalizeCollection(e.target);
		var t = e.target.length;
		if (!t) return;
		if (!("IntersectionObserver" in window)) {
			for (var s = 0; s < e.target.length; s++) e.cb(e.target[s], s);
			return
		}
		var r = t > 0, n = 0, o = {rootMargin: e.rootMargin, threshold: e.offset};
		if (e.root && (o.root = e.root), !1 !== e.delay && (o.delay = e.delay), r) {
			var a = e.target[0].getAttribute("data-viewport-offset");
			a && (o.threshold = a)
		}
		const l = new IntersectionObserver((function (i, s) {
			i.forEach(((i, o) => {
				var a = !0 === i.isIntersecting;
				if (e.ratioTolerance && i.boundingClientRect.height / 2 - e.ratioTolerance <= i.intersectionRect.height && i.boundingClientRect.height / 2 + e.ratioTolerance > i.intersectionRect.height && (a = !0), e.cssProp) {
					var l = i.target;
					e.cssPropTarget && (l = e.cssPropTarget), l.style.setProperty("--in", a ? 1 : 0)
				}
				if (e.toggleClass && i.target.classList.toggle("rey-inView", a), a) n++, e.cb.call(this, i, o, n), e.addClass && !e.toggleClass && i.target.classList.add("rey-inView"), e.once && (s.unobserve(i.target), r ? n === t && s.disconnect() : s.disconnect()); else {
					if (e.disableHideDownwards && i.boundingClientRect.top < 0 && !i.isIntersecting) return;
					e.onHide.call(this, i.target, o)
				}
			}))
		}), o);
		if (r) for (s = 0; s < t; s++) l.observe(e.target[s]); else l.observe(e.target);
		return e.inTab && document.addEventListener("visibilitychange", (function (t) {
			e.target.classList.toggle("rey-inView", "visible" === document.visibilityState)
		})), l
	}, this.frontend.panels = {
		keepOpen: null, active: null, init: function (e) {
			this.active && !this.keepOpen && (this.active(), this.reset()), this.active = e
		}, reset: function () {
			this.active = null, this.keepOpen = null
		}, closeActive: function () {
			this.active && !this.keepOpen && this.active()
		}, can: function () {
			return !e.validation.isNull(this.active)
		}
	}, this.frontend.gridData = function (e) {
		var t, i = Object.assign({
			data: null,
			container: null,
			grid: null,
			items: [],
			grid_selector: "ul.products",
			item_selector: "li.product"
		}, e || {});
		if (i.data) {
			var s = i.data;
			i.data = document.createElement("div"), i.data.innerHTML = s
		}
		if (i.container) t = i.container; else {
			if (!i.grid) return i;
			t = i.grid
		}
		return void 0 === t || (i.grid || (i.grid = t.querySelector(i.grid_selector)), i.grid && (i.items = i.grid.querySelectorAll(i.item_selector))), i
	}, this.frontend.firstInteraction = function () {
		const e = () => {
			t.___.firstInteractionHappened || (t.___.firstInteractionHappened = !0, t.hooks.doAction("first_interaction", this), document.dispatchEvent(new CustomEvent("rey/first_interaction", {detail: {rey: this}})))
		};
		reyParams.delay_final_js_event ? window.addEventListener(reyParams.delay_final_js_event, (() => {
			e()
		})) : (["mousemove", "keydown", "click", "touchstart"].forEach((t => {
			document.body.addEventListener(t, (t => {
				t.isTrusted && e()
			}), {once: !0})
		})), window.addEventListener("scroll", (t => {
			e()
		}), {once: !0}))
	}, this.frontend.firstInteraction(), function () {
		var e = function (e, t) {
			e.target.dispatchEvent(new CustomEvent(t, {detail: e.target, bubbles: !0, cancelable: !0}))
		}, t = !0, i = {x: 0, y: 0}, s = {x: 0, y: 0}, r = {
			touchstart: function (e) {
				i = {x: e.touches[0].pageX, y: e.touches[0].pageY}
			}, touchmove: function (e) {
				t = !1, s = {x: e.touches[0].pageX, y: e.touches[0].pageY}
			}, touchend: function (r) {
				if (t) e(r, "fc"); else {
					var n = s.x - i.x, o = Math.abs(n), a = s.y - i.y, l = Math.abs(a);
					Math.max(o, l) > 20 && e(r, o > l ? n < 0 ? "swl" : "swr" : a < 0 ? "swu" : "swd")
				}
				t = !0
			}, touchcancel: function (e) {
				t = !1
			}
		};
		for (var n in r) document.addEventListener(n, r[n], !1)
	}(), this.assets = {
		__lazyAssets: [],
		__loadedScripts: [],
		__loadedStyles: [],
		obj: {styles: {}, scripts: {}}
	}, this.assets.lazyAssets = function (t, i) {
		const s = void 0 !== i;
		if (e.validation.isEmptyObject(t)) s && i(); else {
			var r = JSON.stringify(t);
			-1 === e.assets.__lazyAssets.indexOf(r) ? (e.assets.__lazyAssets.push(JSON.stringify(t)), t.styles && !e.validation.isEmptyObject(t.styles) && e.assets.loadMultipleStyles(t.styles), !t.scripts || e.validation.isEmptyObject(t.scripts) ? s && i() : e.assets.loadMultipleScripts(t.scripts).then((function () {
				s && i()
			}))) : s && i()
		}
	}, this.assets.loadMultipleScripts = function (t) {
		var i = [];
		return Object.keys(t).forEach((function (s, r) {
			-1 === e.assets.__loadedScripts.indexOf(s) && -1 === window.reyScripts.indexOf(s) && (document.getElementById(s + "-js") || (window.reyScripts.push(s), e.assets.__loadedScripts.push(s), i.push(function (t, i) {
				return new Promise((function (s, r) {
					var n = document.createElement("script");
					-1 === t.indexOf("ver=") && (t += "?ver=" + e.params.core.v), n.src = t, n.id = i + "-js", n.async = !1, n.onload = function () {
						s(t)
					}, n.onerror = function () {
						r(t)
					}, e.elements.body.appendChild(n)
				}))
			}(t[s], s))))
		})), Promise.all(i)
	}, this.assets.loadMultipleStyles = function (i) {
		var s = document.getElementById("wp-custom-css");
		s || (s = document.getElementById("reycore-inline-styles")), Object.keys(i).map((r => {
			var n = i[r];
			if (-1 === e.assets.__loadedStyles.indexOf(r)) {
				if (void 0 !== window.reyStyles && e.validation.isArray(window.reyStyles)) for (var o = 0; o < window.reyStyles.length; o++) if (-1 !== window.reyStyles[o].indexOf(r)) return;
				if (-1 === n.indexOf("ver=") && (n += "?ver=" + e.params.core.v), !document.querySelector(`link[id="${r}-css"]:not([data-lazy-href]):not([${t.params.lazy_attribute}])`)) {
					const e = document.createElement("link");
					e.id = r + "-css", e.rel = "stylesheet", e.type = "text/css", e.href = n, s ? document.head.contains(s) ? document.head.insertBefore(e, s) : (console.info("The integrity of the <head> tag seems to be invalid. Please carefully check the Head tag for tags which are not allowed (such as div, p, span etc., basically non metadata tags)."), document.body.prepend(e)) : document.head.appendChild(e)
				}
				void 0 !== window.reyStyles && void 0 !== window.reyStyles[1] && window.reyStyles[1].push(r), e.assets.__loadedStyles.push(r)
			}
		}))
	}, this.ajax = {__queue: {}, __responses: {}}, this.ajax.request = function (e, s) {
		var r = t.params.core.ajax_queue;
		if ((e || !t.validation.isUndefined(s.url)) && !t.validation.isEmptyObject(s)) {
			s = Object.assign({ss: !1, data: {}, params: {}, cb: i}, s);
			var n = !1, o = !1;
			if (t.params.core.r_ajax_debug && (s.ss = !1), t.vars.logged_in && (s.ss = !1), s.ss) {
				r = !1;
				var a = [`rey_ss_${t.params.site_id || 0}`, `${"string" == typeof s.ss ? s.ss : e}`];
				t.params.lang && a.push(t.params.lang), o = a.join("_"), "refresh" in s.data && s.data.refresh && sessionStorage.removeItem(o), n = sessionStorage.getItem(o)
			}
			var l = JSON.stringify({name: e, args: s}).replace(/\s/g, ""), d = function (e) {
				delete t.ajax.__queue[l], t.ajax.__responses[l] = e
			}, c = {
				url: t.params.core.r_ajax_url.toString().replace("%%endpoint%%", e),
				complete: i,
				progress: i,
				error: function (e) {
					s.cb({
						success: !1,
						data: {error: this.statusText || "Request failed.", the_error: e}
					}), console.error("Request failed!", this)
				},
				formData: {_nonce: t.params.core.r_ajax_nonce, "reycore-ajax-data": s.data},
				options: {
					method: "POST",
					cache: !1,
					headers: {"content-type": "application/x-www-form-urlencoded;charset=UTF-8"}
				}
			};
			if (c.options = Object.assign(c.options, s.params), !1 === c.options.cache && (c.options.headers["cache-control"] = "no-cache, no-store, max-age=0"), s.formData && (c.formData = Object.assign(c.formData, s.formData)), t.params.lang && (c.formData.lang = t.params.lang), c.success = function (i) {
				if (!i || i && !i.success) throw console.log("Response: ", i), new Error("Failed response");
				if (!i.data) throw new Error("No registered actions data!");
				if (void 0 === i.data[e]) throw new Error("No data in " + e + "!");
				!n && o && void 0 !== i.data[e] && void 0 !== i.data[e].data && void 0 === i.data[e].data.errors && sessionStorage.setItem(o, JSON.stringify(i));
				var r = i.data[e], a = function () {
					s.cb(r), t.hooks.doAction("reycore/ajax_response", e, i)
				};
				if (!(!1 in r)) {
					var l = r.data;
					if (l && void 0 !== l.errors) return "string" == typeof l.errors ? console.error(l.errors) : Object.keys(l.errors).forEach((function (e) {
						console.error(e)
					})), void a();
					if ("transient" in r && rey.hooks.doAction("ajax/flush_transients/names", r), "assets" in r && r.assets) {
						var d = r.assets;
						if (d && "styles" in d && Object.keys(d.styles).length && t.assets.loadMultipleStyles(d.styles), d && "scripts" in d && Object.keys(d.scripts).length) {
							var c = !1;
							return void t.assets.loadMultipleScripts(d.scripts).then((function () {
								c || (c = !0, t.hooks.doAction("reycore/ajax_response/assets"), a())
							}))
						}
					}
					a()
				}
			}, n && s.ss) return c.success(JSON.parse(n));
			if (void 0 !== t.ajax.__queue[l]) {
				const e = () => {
					setTimeout((() => {
						var i = t.ajax.__responses[l];
						i ? (c.success.call(this, i), c.complete.call(this, i)) : e()
					}), 1e3)
				};
				return e()
			}
			if (!r || t.validation.isEmptyObject(t.ajax.__queue)) {
				var u = new XMLHttpRequest;
				u.open(c.options.method, c.url);
				var h = function (e) {
					return rey.validation.isJSON(e) ? JSON.parse(e || "{}") : e
				};
				return u.onload = function () {
					var e = h(this.response);
					this.status >= 200 && this.status < 400 ? c.success.call(this, e) : c.error.call(this, e), c.complete.call(this, e), d(e)
				}, u.onerror = function () {
					var e = h(this.response);
					c.error.call(this, e), c.complete.call(this, e), d(e)
				}, u.onabort = function () {
				}, u.onprogress = function () {
					c.progress.call(this)
				}, Object.keys(c.options.headers).forEach((e => {
					u.setRequestHeader(e, c.options.headers[e])
				})), u.send(t.util.serialize(c.formData)), t.ajax.__queue[l] = u, u
			}
			setTimeout((function () {
				return t.ajax.__queue = {}, t.ajax.request(e, s)
			}), 50)
		}
	}, this.ajax.url = function (e, s) {
		if (e && !t.validation.isEmptyObject(s)) {
			s = Object.assign({
				method: "GET",
				data: {},
				params: {},
				cb: i,
				headers: {"Content-Type": "application/x-www-form-urlencoded"}
			}, s);
			var r = new XMLHttpRequest;
			return r.open(s.method, e), r.onload = function () {
				this.status >= 200 && this.status < 400 ? s.cb.call(this, "success") : s.cb.call(this, "error")
			}, r.onerror = function () {
				s.cb.call(this, "error")
			}, Object.keys(s.headers).forEach((e => {
				r.setRequestHeader(e, s.headers[e])
			})), r.send(s.data), r
		}
	}, this.trapFocusHandlers = {}, this.trapFocus = function (e, t = null) {
		var i = Array.from(e.querySelectorAll("summary, a[href], button:enabled, [tabindex]:not([tabindex^='-']), [draggable], area, input:not([type=hidden]):enabled, select:enabled, textarea:enabled, object, iframe"));
		if (i.length) {
			var s = i[0], r = i[i.length - 1];
			t || (t = s), this.removeTrapFocus(), this.trapFocusHandlers.focusin = t => {
				t.target !== e && t.target !== r && t.target !== s || document.addEventListener("keydown", this.trapFocusHandlers.keydown)
			}, this.trapFocusHandlers.focusout = e => {
				document.removeEventListener("keydown", this.trapFocusHandlers.keydown)
			}, this.trapFocusHandlers.keydown = function (t) {
				t.code && "TAB" === t.code.toUpperCase() && (t.target !== r || t.shiftKey || (t.preventDefault(), s.focus()), t.target !== e && t.target !== s || !t.shiftKey || (t.preventDefault(), r.focus()))
			}, document.addEventListener("focusout", this.trapFocusHandlers.focusout), document.addEventListener("focusin", this.trapFocusHandlers.focusin), t.focus(), "INPUT" === t.tagName && ["search", "text", "email", "url"].includes(t.type) && t.value && t.setSelectionRange(0, t.value.length)
		}
	}, this.removeTrapFocus = function (e = null) {
		document.removeEventListener("focusin", this.trapFocusHandlers.focusin), document.removeEventListener("focusout", this.trapFocusHandlers.focusout), document.removeEventListener("keydown", this.trapFocusHandlers.keydown), e && e.focus()
	}, this.vars.logged_in = this.elements.body.classList.contains("logged-in"), this.vars.page_id = parseInt(this.elements.body.getAttribute("data-id") || 0), this.vars.elementor_edit_mode = document.body.classList.contains("rey-elementor-edit-mode"), this.vars.customizer_preview = this.elements.body.classList.contains("customizer-preview-mode"), this.vars.is_edit_mode = this.vars.elementor_edit_mode || this.vars.customizer_preview, this.vars.container_size = parseInt(this.elements.html.getAttribute("data-container") || 1440), this.vars.is_rtl = !!this.elements.body.classList.contains("rtl"), this.vars.headerIsFixed = !1, this.vars.is_global_section_mode = this.elements.body.classList.contains("single-rey-global-sections"), this.vars.is_touch_device = function () {
		return "ontouchstart" in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0
	}, this.vars.breakpoints = this.hooks.applyFilters("rey/breakpoints", {
		mobile: "(max-width: 767px)",
		tablet: "(min-width: 768px) and (max-width: 1024px)",
		desktop: "(min-width: 1025px)"
	}), this.refresh = () => {
		this.vars.is_touch = this.vars.is_touch_device(), this.vars.is_mobile = window.matchMedia(this.vars.breakpoints.mobile).matches, this.vars.is_tablet = window.matchMedia(this.vars.breakpoints.tablet).matches, this.vars.is_desktop = window.matchMedia(this.vars.breakpoints.desktop).matches, this.vars.is_desktop_touch = window.matchMedia(this.vars.breakpoints.desktop).matches && this.vars.is_touch, this.vars.adminBar = this.elements.body.classList.contains("admin-bar") ? this.vars.is_desktop ? 32 : 46 : 0, this.vars.device = this.vars.is_mobile ? "mobile" : this.vars.is_tablet ? "tablet" : !!this.vars.is_desktop && "desktop", this.vars.mobileClickEvent = reyParams.mobile_click_event || (this.vars.is_touch ? "touchstart" : "click"), this.vars.mobileClickEventParams = "touchstart" === this.vars.mobileClickEvent ? {passive: !0} : {}, this.vars.device && this.elements.body.setAttribute("data-rey-device", this.vars.device)
	}, this.refresh(), this.___.lastDevice = this.vars.device, window.addEventListener("resize", this.util.debounce((() => {
		this.refresh(), window.dispatchEvent(new CustomEvent("rey/window/resize")), this.___.lastDevice !== this.vars.device && window.dispatchEvent(new CustomEvent("rey/window/breakpoint")), this.___.lastDevice = this.vars.device
	}), 300)), this.___.youTubeApiLoaded = !1, this._extend = function () {
		String.prototype.replaceAll || (String.prototype.replaceAll = function (e, t) {
			return "[object regexp]" === Object.prototype.toString.call(e).toLowerCase() ? this.replace(e, t) : this.replace(new RegExp(e, "g"), t)
		})
	}, this._extend(), this.template || (void 0 !== window._rey_template ? this.template = window._rey_template : this.template = function () {
		return function () {
			return ""
		}
	})
};
Object.assign(rey, new ReyTheme), document.addEventListener(reyParams.delay_forced_js_event || "DOMContentLoaded", (e => {
	rey.hooks.addAction("first_interaction", (function () {
		var e;
		!function () {
			if (rey.elements.header) {
				var e, t = function () {
					e || rey.elements.header.classList.contains("header-pos--fixed") && (rey.elements.header.classList.remove("--loading-fixed-desktop", "--loading-fixed-tablet", "--loading-fixed-mobile"), e = !0);
					var t = rey.elements.header.cloneNode(!0);
					t.style.visibility = "hidden", t.style.pointerEvents = "none", t.classList.remove("--fixed-shrinking", "header-pos--fixed", "--scrolled", "--shrank"), rey.elements.header.after(t), rey.headerHeight = t.offsetHeight, document.documentElement.style.setProperty("--header-default--height", rey.headerHeight + "px"), t.remove()
				};
				t(), window.addEventListener("resize", rey.util.debounce(t, 500)), window.addEventListener("rey/refresh_header", rey.util.debounce(t, 500))
			}
		}(), (e = document.querySelectorAll("video[data-lazy-video], iframe[data-lazy-video]")).length && rey.frontend.inView({
			target: e,
			cb: function (e) {
				e.target.setAttribute("src", e.target.getAttribute("data-lazy-video"))
			},
			once: !0
		}), function () {
			if (reyParams && reyParams.theme_js_params && reyParams.theme_js_params.embed_responsive) {
				var e = document.querySelectorAll(reyParams.theme_js_params.embed_responsive.elements.join(","));
				e.length && (rey.assets.loadMultipleStyles({"rey-embed-responsive": reyParams.theme_js_params.embed_responsive.src}), e.forEach((e => {
					e.closest(".embed-responsive") || e.closest(".wp-block-embed") || rey.dom.wrapWithMarkup(e, '<div class="embed-responsive embed-responsive-16by9">', "</div>")
				})))
			}
		}(), document.addEventListener("keydown", (e => {
			27 == e.keyCode && rey.frontend.panels.can() && (rey.frontend.panels.closeActive(), rey.frontend.overlay.close())
		})), document.querySelectorAll(".rey-overlay:not(.--no-close,.--no-js-close)").forEach((e => {
			e.addEventListener("click", (e => {
				e.preventDefault(), rey.frontend.panels.closeActive(), rey.frontend.overlay.close()
			}))
		})), rey.hooks.doActionDeprecated("reytheme/init")
	}));
	var t = e => {
		reyParams.log_events && console.log(`Fired "rey-DOMContentLoaded" after "${e}" event.`), document.dispatchEvent(new CustomEvent("rey-DOMContentLoaded", {
			detail: {
				event: e,
				logEvents: reyParams.log_events
			}
		}))
	};
	reyParams.log_events && console.log(`Started Rey on "${e.type}" event.`), reyParams.delay_js_dom_event && "DOMContentLoaded" === e.type ? document.addEventListener(reyParams.delay_js_dom_event, (() => {
		t(reyParams.delay_js_dom_event)
	})) : t(e.type)
})), "undefined" != typeof jQuery && function (e) {
	e.rey || (e.rey = {}), e.rey.helpers = new function () {
		var t = this;
		this.methods = {
			isEmpty: "validation.isEmpty",
			isNull: "validation.isEmpty",
			typeOf: "validation.typeOf",
			isFunction: "validation.isFunction",
			isString: "validation.isString",
			isUndefined: "validation.isUndefined",
			isObject: "validation.isObject",
			isEmptyObject: "validation.isEmptyObject",
			matches: "validation.matches",
			testSelector: "validation.testSelector",
			isInstanceOf: "validation.isInstanceOf",
			isHTMLElement: "validation.isHTMLElement",
			slice: "util.slice",
			apply: "util.apply",
			raf: "util.raf",
			children: "dom.children",
			addSvgIcon: "frontend.getSvgIcon",
			getSvgArrows: "frontend.svgIcon.getArrows",
			getUrlVars: "util.getUrlVars",
			inView: "frontend.inView",
			frontendAjax: "ajax.request",
			lazyAssets: "assets.lazyAssets",
			loadMultipleScripts: "assets.loadMultipleScripts",
			loadMultipleStyles: "assets.loadMultipleStyles",
			params: "params",
			isArray: "validation.isArray",
			matchYoutubeUrl: "validation.matchYoutubeUrl",
			isValidURL: "validation.isValidURL",
			setProperty: "dom.setProperty",
			getProperty: "dom.getProperty",
			debounce: "util.debounce",
			ls: "util.ls",
			expiration: "util.expiration",
			getRandomInt: "util.getRandomInt",
			minTwoDigits: "util.minTwoDigits",
			addFilter: "hooks.addFilter",
			removeFilter: "hooks.removeFilter",
			applyFilter: "hooks.applyFilters",
			log: "log",
			youTubeApiLoaded: "___.youTubeApiLoaded",
			logged_in: "vars.logged_in",
			page_id: "vars.page_id",
			elementor_edit_mode: "vars.elementor_edit_mode",
			customizer_preview: "vars.customizer_preview",
			is_edit_mode: "vars.is_edit_mode",
			$sitePreloader: "vars.$sitePreloader",
			$container_size: "vars.$container_size",
			is_rtl: "vars.is_rtl",
			is_global_section_mode: "vars.is_global_section_mode",
			is_touch_device: "vars.is_touch_device",
			is_touch: "vars.is_touch_device",
			is_mobile: "vars.is_mobile",
			is_tablet: "vars.is_tablet",
			is_desktop: "vars.is_desktop",
			is_desktop_touch: "vars.is_desktop_touch",
			adminBar: "vars.adminBar"
		}, Object.keys(this.methods).forEach((e => {
			var i = t.methods[e], s = rey;
			i.split(".").forEach((e => {
				s = s[e]
			})), rey.validation.isFunction(s) ? t[e] = function (...t) {
				return console.log(`$.rey.${e} function is deprecated. Please use rey.${i} instead.`), s(...t)
			} : rey.validation.isObject(s) ? t[e] = function () {
				return console.log(`$.rey.${e} object is deprecated. Please use rey.${i} instead.`), s
			} : rey.validation.isBoolean(s) && (t[e] = function () {
				return console.log(`$.rey.${e} var is deprecated. Please use rey.${i} instead.`), s
			})
		})), this.elements = {
			$body: e(rey.elements.body),
			$header: e(rey.elements.header),
			$footer: e(rey.elements.footer),
			$site_overlay: e(rey.elements.site_overlay),
			$siteWrapper: e(rey.elements.siteWrapper),
			$sitePreloader: e(rey.elements.sitePreloader)
		}, this.headerOverlayOpened = function () {
			return "header" === rey.frontend.overlay.isOpened()
		}, this.overlay = rey.frontend.overlay.close, this.doScroll = {
			disable: function () {
				rey.frontend.scroll.disable()
			}, enable: function () {
				rey.frontend.scroll.enable()
			}
		}
	}, e.reyHelpers = e.rey.helpers, e.reyCoreHelpers = e.rey.helpers
}(jQuery)
