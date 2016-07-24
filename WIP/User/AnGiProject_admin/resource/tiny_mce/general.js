var Tools = {
	each : function(o, cb, s) {
		var n, l;

		if (!o)
			return 0;

		s = s || o;

		if (typeof(o.length) != 'undefined') {
			// Indexed arrays, needed for Safari
			for (n=0, l = o.length; n<l; n++) {
				if (cb.call(s, o[n], n, o) === false)
					return 0;
			}
		} else {
			// Hashtables
			for (n in o) {
				if (o.hasOwnProperty(n)) {
					if (cb.call(s, o[n], n, o) === false)
						return 0;
				}
			}
		}

		return 1;
	}
};

var DOM = {
	get : function(e) {
		if (typeof(e) == 'string')
			e = document.getElementById(e);

		return e;
	},

	create : function(tn, a, h) {
		var n, o = document.createElement(tn);

		if (a) {
			for (n in a) {
				if (typeof(a[n]) != 'function' && a[n] != null) {
					if (n == 'class')
						o.className = a[n];

					o.setAttribute(n, a[n]);
				}
			}
		}

		if (h)
			o.innerHTML = h;

		return o;
	},

	addClass : function(e, c, b) {
		var o;

		e = DOM.get(e);

		if (!e)
			return null;

		o = DOM.removeClass(e, c);

		return e.className = b ? c + (o != '' ? (' ' + o) : '') : (o != '' ? (o + ' ') : '') + c;
	},

	removeClass : function(e, c) {
		e = DOM.get(e);

		if (!e)
			return null;

		c = e.className.replace(new RegExp("(^|\\s+)" + c + "(\\s+|$)", "g"), ' ');

		return e.className = c != ' ' ? c : '';
	},

	hasClass : function(n, c) {
		n = DOM.get(n);

		return (' ' + n.className + ' ').indexOf(' ' + c + ' ') !== -1;
	},

	hide : function(e) {
		DOM.get(e).style.display = 'none';
	},

	show : function(e) {
		DOM.get(e).style.display = 'block';
	}
};

var Examples = {
	toggleView : function(s, h) {
		if (s == 'source' && !DOM.hasClass('example_' + s + '_view', 'syntax')) {
			dp.sh.ClipboardSwf = 'js/syntax/clipboard.swf';
			dp.sh.Highlighter.spaceWidth = 6;
			dp.sh.highlight();
			//CodeHighlighter.init();
			DOM.addClass('example_' + s + '_view', 'syntax');
		}

		DOM.addClass('example_' + s + '_tab', 'toggled');
		DOM.removeClass('example_' + h + '_tab', 'toggled');
		DOM.show('example_' + s + '_view');
		DOM.hide('example_' + h + '_view');

		return false;
	}
};
