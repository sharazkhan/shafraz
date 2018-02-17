/*
 * Globalize Culture ja-JP
 *
 * http://github.com/jquery/globalize
 *
 * Copyright Software Freedom Conservancy, Inc.
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * This file was generated by the Globalize Culture Generator
 * Translation: bugs found in this file need to be fixed in the generator
 */

(function( window, undefined ) {

var Globalize;

if ( typeof require !== "undefined"
	&& typeof exports !== "undefined"
	&& typeof module !== "undefined" ) {
	// Assume CommonJS
	Globalize = require( "globalize" );
} else {
	// Global variable
	Globalize = window.Globalize;
}

Globalize.addCultureInfo( "ja-JP", "default", {
	name: "ja-JP",
	englishName: "Japanese (Japan)",
	nativeName: "??? (??)",
	language: "ja",
	numberFormat: {
		NaN: "NaN (???)",
		negativeInfinity: "-8",
		positiveInfinity: "+8",
		percent: {
			pattern: ["-n%","n%"]
		},
		currency: {
			pattern: ["-$n","$n"],
			decimals: 0,
			symbol: "�"
		}
	},
	calendars: {
		standard: {
			days: {
				names: ["???","???","???","???","???","???","???"],
				namesAbbr: ["?","?","?","?","?","?","?"],
				namesShort: ["?","?","?","?","?","?","?"]
			},
			months: {
				names: ["1?","2?","3?","4?","5?","6?","7?","8?","9?","10?","11?","12?",""],
				namesAbbr: ["1","2","3","4","5","6","7","8","9","10","11","12",""]
			},
			AM: ["??","??","??"],
			PM: ["??","??","??"],
			eras: [{"name":"??","start":null,"offset":0}],
			patterns: {
				d: "yyyy/MM/dd",
				D: "yyyy'?'M'?'d'?'",
				t: "H:mm",
				T: "H:mm:ss",
				f: "yyyy'?'M'?'d'?' H:mm",
				F: "yyyy'?'M'?'d'?' H:mm:ss",
				M: "M'?'d'?'",
				Y: "yyyy'?'M'?'"
			}
		},
		Japanese: {
			name: "Japanese",
			days: {
				names: ["???","???","???","???","???","???","???"],
				namesAbbr: ["?","?","?","?","?","?","?"],
				namesShort: ["?","?","?","?","?","?","?"]
			},
			months: {
				names: ["1?","2?","3?","4?","5?","6?","7?","8?","9?","10?","11?","12?",""],
				namesAbbr: ["1","2","3","4","5","6","7","8","9","10","11","12",""]
			},
			AM: ["??","??","??"],
			PM: ["??","??","??"],
			eras: [{"name":"??","start":null,"offset":1867},{"name":"??","start":-1812153600000,"offset":1911},{"name":"??","start":-1357603200000,"offset":1925},{"name":"??","start":60022080000,"offset":1988}],
			twoDigitYearMax: 99,
			patterns: {
				d: "gg y/M/d",
				D: "gg y'?'M'?'d'?'",
				t: "H:mm",
				T: "H:mm:ss",
				f: "gg y'?'M'?'d'?' H:mm",
				F: "gg y'?'M'?'d'?' H:mm:ss",
				M: "M'?'d'?'",
				Y: "gg y'?'M'?'"
			}
		}
	}
});

}( this ));