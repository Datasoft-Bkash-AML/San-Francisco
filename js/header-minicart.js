!function (e) {
	"use strict";
	var t = {
		statusDefault: "init",
		iName: "mini-cart",
		debug: !1,
		somethingChanged: !0,
		__cart_count: 0,
		triggerOpen: !1,
		triggerFromClick: !1,
		isOpen: !1,
		hasBeenOpened: !1,
		lockQuantityUpdate: !1,
		assetsFragmentName: "_cart_assets_",
		cartButtonSelector: ".js-rey-headerCart",
		cartPanelSelector: ".js-rey-cartPanel",
		init: function () {
			var t;
			if ((this.$cartPanel = e(this.cartPanelSelector), this.cartTrigger = document.querySelectorAll(this.cartButtonSelector), this.cartPanel = document.querySelector(this.cartPanelSelector), this.cartPanel) && (this.cartTrigger.forEach((e => {
				"A" === e.tagName && (t = !0)
			})), !t)) {
				this.status = this.statusDefault;
				var a = {
					name: "mini-cart",
					trigger: this.cartTrigger,
					panel: this.cartPanel,
					closeBtn: ".rey-cartPanel-header",
					extraBodyClass: "--cart-active",
					manualOpen: !0,
					onClose: this.close
				};
				return reyParams.header_cart_panel && reyParams.header_cart_panel.close_text && (a.closeText = reyParams.header_cart_panel.close_text), this.SP = rey.components.sidePanel(a), this.handleEmptySession(), this.events(), rey.hooks.doAction("minicart/init", this), this
			}
		},
		events: function () {
			e(document).on("click", this.cartButtonSelector, (function (e) {
				e.preventDefault();
				var a = this;
				rey.vars.is_global_section_mode || rey.elements.body.classList.contains("woocommerce-cart") || rey.elements.body.classList.contains("woocommerce-checkout") || (t.loaderTimeout = setTimeout((() => {
					a.classList.add("--loading")
				}), 200), t.triggerOpen = !0, t.triggerFromClick = !0, t.open(), t.SP.openedFrom = a)
			})), this.cartPanel.addEventListener("click", (function (e) {
				e.target.closest(".rey-cartPanel-continue button") && (e.preventDefault(), t.closePanel())
			}));
			const a = () => {
				setTimeout((() => {
					this.lockQuantityUpdate = !1
				}), 1e3)
			}, r = r => {
				var n = r.target.closest("input.qty");
				if (n && "" !== n.value && !this.lockQuantityUpdate) {
					this.lockQuantityUpdate = !0, n.style.setProperty("--qty-len", (n.value.length || 1) + 1);
					var o = e(n), c = o.closest(".woocommerce-mini-cart-item"), s = e("a.remove", c),
						i = parseFloat(o.attr("max") || 0);
					r.preventDefault(), i && parseFloat(o.val()) > i && o.val(i), rey.components.block(c[0]), e.ajax({
						type: "POST",
						url: reyParams.wc_ajax_url.toString().replace("%%endpoint%%", "rey_update_minicart"),
						data: {
							cart_item_key: s.data("cart_item_key"),
							cart_item_qty: o.val(),
							security: reyParams.ajax_nonce
						},
						success: function (r) {
							if (r && r.fragments) {
								if (e.each(r.fragments, (function (t) {
									e(t).fadeTo("400", "0.6").addClass("--no-click")
								})), e.each(r.fragments, (function (t, a) {
									e(t).replaceWith(a), e(t).stop(!0).css("opacity", "1").removeClass("--no-click")
								})), "undefined" != typeof wc_cart_fragments_params && wc_cart_fragments_params.fragment_name) {
									var n = wc_cart_fragments_params.cart_hash_key,
										o = wc_cart_fragments_params.fragment_name;
									sessionStorage.setItem(o, JSON.stringify(r.fragments)), r.cart_hash && (localStorage.setItem(n, r.cart_hash), sessionStorage.setItem(n, r.cart_hash), Cookies.set("woocommerce_cart_hash", r.cart_hash), sessionStorage.setItem("wc_cart_created", (new Date).getTime()))
								}
								t.__cart_count = r.fragments && void 0 !== r.fragments._count_ ? r.fragments._count_ : 0, t.updateCountAttribute(), a(), e(document.body).trigger("wc_fragments_loaded"), rey.hooks.doAction("minicart/updated", t), document.dispatchEvent(new CustomEvent("reycore/minicart/updated", {detail: {MC: t}}))
							} else {
								s.attr("href") && (window.location = s.attr("href"))
							}
						},
						error: function () {
							a(), e("<p><small>" + reyParams.cannot_update_cart + "</small></p>").appendTo(c), setTimeout((function () {
								window.location.reload()
							}), 1e3)
						},
						dataType: "json"
					})
				}
			};
			this.cartPanel.addEventListener("keyup", rey.util.debounce(r, 800)), this.cartPanel.addEventListener("change", r), e(document).on("click", ".woocommerce-mini-cart-item a.remove", (function (t) {
				e(this).closest(".woocommerce-mini-cart-item").addClass("--loading")
			})), e(document.body).on("added_to_cart removed_from_cart wc_fragments_refreshed updated_wc_div wc_fragments_loaded", (function (e) {
				t.refreshScroll()
			})), e(document.body).on("adding_to_cart", (function (e) {
				t.status = "adding", t.triggerOpen = !0
			})), e(document.body).on("added_to_cart", ((e, a, r, n) => {
				if (t.status = "added", "yes" !== wc_add_to_cart_params.cart_redirect_after_add) {
					var o = !(void 0 === n || !n.length) && n[0];
					if (o && o.hasAttribute("data-checkout")) return rey.hooks.removeAction("minicart/open"), void (window.location = o.getAttribute("data-checkout"));
					if (t.__cart_count = void 0 !== a._count_ ? a._count_ : 0, o && o.classList.contains("--prevent-open-cart") || "cart" !== reyParams.after_add_to_cart) t.updateCountAttribute(); else if (rey.elements.body.classList.contains("woocommerce-cart")) {
						var c = document.querySelector(".woocommerce-cart-form");
						c && window.scrollTo(0, rey.dom.offset(c).top)
					} else t.activateTab(), t.open(), t.updateCountAttribute()
				}
			})), e(document.body).on("removed_from_cart", (function (e, a) {
				t.status = "removed", void 0 !== a._count_ && (t.__cart_count = a._count_, a._count_ || t.emptyGs(), t.updateCountAttribute())
			})), e(document.body).on("wc_fragments_loaded", (function (e) {
				if (t.log(":: LOADED FRAGMENTS"), reyParams.wpch) {
					var a = document.querySelector(".__cart-count");
					a && (t.__cart_count = parseInt(a.textContent), t.updateCountAttribute())
				}
				t.somethingChanged = !0, document.querySelectorAll(".woocommerce-mini-cart-item").forEach((e => {
					if (!e.querySelector("a.remove")) {
						var t = e.querySelector(".cartBtnQty-controls");
						t && t.classList.add("--disabled-controls")
					}
				}))
			})), e(document.body).on("wc_fragments_refreshed", (function (e) {
				t.log(":: REFRESHED FRAGMENTS");
				var a = t.getFragments();
				t.__cart_count = void 0 !== a._count_ ? a._count_ : 0, t.willRefreshFragments = !1, (t.triggerFromClick || "cart" === reyParams.after_add_to_cart) && t.open()
			})), e(document.body).on("wc_cart_button_updated", (function (e, t) {
				t.next(".added_to_cart").remove()
			})), rey.hooks.addAction("minicart/open", (function () {
				t.triggerOpen = !0, t.open()
			})), e(".__tab", t.$cartPanel).on("click", (function () {
				var a = e(this).attr("data-item");
				t.activateTab(a)
			})), e(document).on("click", '.rey-cartPanel .coupon button[name="apply_coupon"]', (function (e) {
				e.preventDefault(), t.apply_coupon()
			})), e(document).on("click", ".rey-cartPanel .woocommerce-remove-coupon", (function (e) {
				e.preventDefault(), t.remove_coupon(this)
			}))
		},
		updateCountAttribute: function (e) {
			document.querySelectorAll("[data-rey-cart-count]").forEach((e => {
				e.setAttribute("data-rey-cart-count", this.__cart_count)
			})), e && document.querySelectorAll(".__cart-count").forEach((e => {
				e.textContent = this.__cart_count
			}))
		},
		getFragments: function () {
			return "undefined" == typeof wc_cart_fragments_params ? {} : wc_cart_fragments_params.fragment_name ? JSON.parse(sessionStorage.getItem(wc_cart_fragments_params.fragment_name) || "{}") : {}
		},
		handleEmptySession: function () {
			if (reyParams.wpch && "undefined" != typeof wc_cart_fragments_params && wc_cart_fragments_params.fragment_name && !sessionStorage.getItem(wc_cart_fragments_params.fragment_name) && !localStorage.getItem(wc_cart_fragments_params.cart_hash_key) && !Cookies.get("woocommerce_cart_hash")) return t.__cart_count = 0, t.updateCountAttribute(!0);
			setTimeout((() => {
				"undefined" != typeof wc_cart_fragments_params && wc_cart_fragments_params.fragment_name && !sessionStorage.getItem(wc_cart_fragments_params.fragment_name) && localStorage.getItem(wc_cart_fragments_params.cart_hash_key) && (console.log('Trigger "wc_fragments_refreshed".'), e(document.body).trigger("wc_fragments_refreshed"))
			}), 100)
		},
		/*open: function () {
			if ("undefined" == typeof wc_cart_fragments_params) return console.log("Cart Fragments script is not loaded. Probably a 3rd party plugin has disabled it."), t.__openPanel();
			t.log(":: CART-OPEN");
			var a = t.getFragments();
			if (rey.validation.isEmptyObject(a)) e(document.body).trigger("wc_fragment_refresh"); else if (!t.willRefreshFragments || t.hasBeenOpened) {
				if (t.triggerOpen) {
					if (t.triggerOpen = !1, !a || !(t.assetsFragmentName in a)) return t.__openPanel();
					rey.assets.lazyAssets(a[t.assetsFragmentName], (function () {
						rey.hooks.doAction("minicart/assets_ready", t), document.dispatchEvent(new CustomEvent("rey/minicart/assets_ready", {detail: {MC: t}})), setTimeout((function () {
							t.__openPanel(), t.cartPanel.removeAttribute("data-lazy-hidden")
						}), 200)
					}))
				}
			} else e(document.body).trigger("wc_fragment_refresh")
		},*/
        open: function () {
            if ("undefined" == typeof wc_cart_fragments_params) return console.log("Cart Fragments script is not loaded. Probably a 3rd party plugin has disabled it."), t.__openPanel();
            t.log(":: CART-OPEN");
			// var a = t.getFragments();
            rey.assets.lazyAssets('{}', (function () {
				t.cartPanel.addStyle("{display:block}")
                rey.hooks.doAction("minicart/assets_ready", t), document.dispatchEvent(new CustomEvent("rey/minicart/assets_ready", {detail: {MC: t}})), setTimeout((function () {
                    t.__openPanel(), t.cartPanel.removeAttribute("data-lazy-hidden")
                    t.cartPanel.addStyle("{display:block}")
                }), 200)
            }));

			t.cartPanel.addStyle("{display:block}")

        },
		__openPanel: function () {
			this.SP.open(), clearTimeout(t.loaderTimeout), this.cartTrigger.forEach((e => {
				e.classList.remove("--loading")
			})), rey.hooks.doAction("minicart/opened", t), this.refreshScroll(), this.emptyGs(), this.isOpen = !0, this.hasBeenOpened = !0, this.assetsLoaded = !0, this.triggerFromClick = !1
		},
		closePanel: function () {
			t.SP.close(), t.close()
		},
		close: function () {
			t.isOpen = !1, t.somethingChanged = !1, t.status = t.statusDefault, t.log(":: CART-CLOSE"), rey.hooks.doAction("minicart/close", t)
		},
		activateTab: function (t) {
			t = t || "cart", e(".__tab", this.$cartPanel).removeClass("--active"), e('.__tab[data-item="' + t + '"]', this.$cartPanel).addClass("--active"), e(".__tab-content", this.$cartPanel).removeClass("--active"), e('.__tab-content[data-item="' + t + '"]', this.$cartPanel).addClass("--active"), rey.hooks.doAction("minicart/tab", t, this)
		},
		apply_coupon: function () {
			var t = e(".rey-toggleCoupon", this.$cartPanel), a = e("#coupon_code", this.$cartPanel),
				r = e(".rey-toggleCoupon-response", this.$cartPanel), n = a.val(),
				o = {security: reyParams.header_cart_panel.apply_coupon_nonce, coupon_code: n};
			t.addClass("--loading"), e.ajax({
				type: "POST",
				url: woocommerce_params.wc_ajax_url.toString().replace("%%endpoint%%", "apply_coupon"),
				data: o,
				dataType: "html"
			}).done((function (n) {
				r.empty(), r.html(n), t.removeClass("--loading"), a.val(""), setTimeout((() => {
					e(document.body).trigger("wc_fragment_refresh")
				}), 3e3)
			}))
		},
		remove_coupon: function (t) {
			var a = e(t), r = (e(".rey-toggleCoupon", this.$cartPanel), e("#coupon_code", this.$cartPanel)),
				n = e(".rey-toggleCoupon-response", this.$cartPanel), o = a.attr("data-coupon"),
				c = e(".minicart-total-row.coupon-" + o, this.$cartPanel),
				s = {security: reyParams.header_cart_panel.remove_coupon_nonce, coupon: o};
			c.addClass("--loading"), e.ajax({
				type: "POST",
				url: woocommerce_params.wc_ajax_url.toString().replace("%%endpoint%%", "remove_coupon"),
				data: s,
				dataType: "html"
			}).done((function (t) {
				n.empty(), n.html(t), c.removeClass("--loading"), r.val(""), setTimeout((() => {
					e(document.body).trigger("wc_fragment_refresh")
				}), 3e3)
			}))
		},
		emptyGs: function () {
			var a = e(".rey-emptyMiniCartGs");
			if (a.length && !a.html()) {
				var r = a.attr("data-gsid");
				if (r) return t.emptyGsContent ? a.append(t.emptyGsContent) : void rey.ajax.request("get_empty_minicart_gs_content", {
					ss: !0,
					data: {gsid: r},
					params: {cache: !1},
					cb: function (e) {
						a.append(e.data), t.emptyGsContent = e.data
					}
				})
			}
		},
		refreshScroll: function () {
			var e = this.cartPanel.querySelector(".woocommerce-mini-cart");
			e && "undefined" != typeof SimpleScrollbar && SimpleScrollbar.initEl(e)
		},
		log: function (e) {
			this.debug && console.log(e)
		}
	};
	document.addEventListener("rey-DOMContentLoaded", (function (e) {
		rey.components.minicart = t.init()
	})), e(document.body).on("wc_fragments_refresh_empty", (function () {
		reyParams.header_cart_panel.cart_fragment_tweak ? t.willRefreshFragments = !0 : e(document.body).trigger("wc_fragment_refresh")
	})), e(document.body).on("adding_to_cart wc_fragments_refreshed", (function () {
		t.willRefreshFragments = !1
	}))
}(jQuery)
