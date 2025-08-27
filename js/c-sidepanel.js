!function () {
	"use strict";
	var e = function (e) {
		var t = this;
		this.isOpen, this.closeButton, this.openedFrom, this.init = function () {
			if (this.config = Object.assign({
				name: "sidepanel",
				panel: "",
				trigger: "",
				manualOpen: !1,
				onInit: function () {
				},
				onUpdate: function () {
				},
				onOpen: function () {
				},
				onOpened: function () {
				},
				onClose: function () {
				},
				onClosed: function () {
				},
				closeBtn: !0,
				closeText: "",
				alignment: "right",
				bodyClass: "--side-panel-active",
				extraBodyClass: "",
				elOpenClass: "--is-open",
				elOpenedClass: "--is-opened",
				animateSite: !1,
				disableScroll: !0
			}, e || {}), this.panel = this.config.panel, this.trigger = this.config.trigger, rey.validation.isString(this.panel) && (this.panel = document.querySelector(this.panel)), rey.validation.isString(this.trigger) && (this.trigger = document.querySelectorAll(this.trigger)), this.panel && this.trigger) {
				if (this.panel.hasAttribute("data-sidepanel")) {
					var i = JSON.parse(this.panel.getAttribute("data-sidepanel") || "{}");
					rey.validation.isEmptyObject(i) || Object.keys(i).forEach((e => {
						t.config[e] = i[e]
					}))
				}
				return this.alignment = this.panel.getAttribute("data-align") || this.config.alignment, this.ensurePanel(), this.addCloseButton(), this.triggerEvent(), this.config.onInit.call(this), this
			}
		}, this.update = function (e) {
			e && e.call(this), this.triggerEvent(), this.ensurePanel(), this.config.onUpdate.call(this)
		}, this.triggerEvent = function () {
			this.config.manualOpen || (rey.validation.isNodeList(this.trigger) ? this.trigger.forEach((e => {
				t.handleTriggerEvent(e)
			})) : this.handleTriggerEvent(this.trigger))
		}, this.handleTriggerEvent = function (e) {
			e && e.addEventListener("click", (e => {
				if (e.preventDefault(), this.isOpen) return this.close();
				this.open()
			}))
		}, this.ensurePanel = function () {
			this.panel.classList.contains("rey-sidePanel") || this.panel.classList.add("rey-sidePanel")
		}, this.addCloseButton = function () {
			if (this.config.closeBtn && !this.panel.querySelector(".rey-sidePanel-close")) {
				var e = this.panel.children[0];
				if (rey.validation.isString(this.config.closeBtn)) {
					var i = this.panel.querySelector(this.config.closeBtn);
					i && (e = i)
				}
				var s = rey.frontend.svgIcon.get("close");
				s += rey.frontend.svgIcon.get("arrow-classic");
				var n = document.createElement("button");
				n.classList.add("btn", "__arrClose", "rey-sidePanel-close", "--invisible"), n.setAttribute("aria-label", reyParams.core.js_params.panel_close_text);
				var o = `<span class="__icons">${s}</span>`;
				this.config.closeText && (o = `<span class="__close-text">${this.config.closeText}</span>` + o), n.innerHTML = o, e.appendChild(n), n.addEventListener("click", (function (e) {
					e.preventDefault(), t.close()
				})), this.closeButton = n
			}
		}, this.open = function () {
			parseInt(reyParams.popv_selector) || this.isOpen || (this.__isBlock || (this.panel.style.display = "block", this.__isBlock = !0), this.transitionDuration || (this.transitionDuration = rey.dom.getNumberProperty(this.panel, "--transition-duration", 400)), this.config.onOpen.call(this), rey.frontend.panels.init(this.close.bind(this)), rey.frontend.overlay.open("site"), this.config.disableScroll && rey.frontend.scroll.disable(), requestAnimationFrame((function () {
				t.toggleClasses(!0)
			})), this.isOpen = !0)
		}, this.close = function () {
			this.isOpen && (this.config.onClose.call(this), rey.frontend.panels.reset(), rey.frontend.overlay.close(), this.config.disableScroll && rey.frontend.scroll.enable(), requestAnimationFrame((function () {
				t.toggleClasses(!1)
			})), this.isOpen = !1)
		}, this.toggleClasses = function (e) {
			rey.elements.body.classList.toggle(this.config.bodyClass, e), rey.elements.body.classList.toggle(`${this.config.bodyClass}--${this.alignment}`, e), this.config.extraBodyClass && (rey.validation.isArray(this.config.extraBodyClass) ? rey.elements.body.classList.toggle(...this.config.extraBodyClass, e) : rey.elements.body.classList.toggle(this.config.extraBodyClass, e)), this.panel.classList.toggle(this.config.elOpenClass, e), rey.validation.isNodeList(this.trigger) ? this.trigger.forEach((i => {
				i && i.classList.toggle(t.config.elOpenClass, e)
			})) : this.trigger && this.trigger.classList.toggle(this.config.elOpenClass, e), this.config.animateSite && rey.elements.body.classList.toggle("--side-animated", e), setTimeout((() => {
				t.onTransitionEnd()
			}), this.transitionDuration), rey.hooks.doAction("toggle_sidepanel", e, t)
		}, this.onTransitionEnd = function () {
			this.isOpen ? (this.config.onOpened.call(this), this.panel.classList.add(this.config.elOpenedClass), void 0 !== rey.trapFocus && rey.trapFocus(this.panel)) : (this.config.onClosed.call(this), this.panel.classList.remove(this.config.elOpenedClass), void 0 !== rey.removeTrapFocus && (rey.validation.isNodeList(this.trigger) ? rey.removeTrapFocus(this.openedFrom) : this.trigger && rey.removeTrapFocus(this.trigger)), this.openedFrom = null)
		}, this.init()
	};
	rey.components.sidePanel = function () {
		return new e(...arguments)
	}
}()
