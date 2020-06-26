/*!
 * numbro.js language configuration
 * language : Hebrew
 * locale : IL
 * author : Eli Zehavi : https://github.com/eli-zehavi
 */
(function(){"use strict";var a={langLocaleCode:"he-IL",cultureCode:"he-IL",delimiters:{thousands:",",decimal:"."},abbreviations:{thousand:"אלף",million:"מליון",billion:"בליון",trillion:"טריליון"},currency:{symbol:"₪",position:"prefix"},defaults:{currencyFormat:",4 a"},formats:{fourDigits:"4 a",fullWithTwoDecimals:"₪ ,0.00",fullWithTwoDecimalsNoCurrency:",0.00",fullWithNoDecimals:"₪ ,0"}};
// CommonJS
"undefined"!=typeof module&&module.exports&&(module.exports=a),
// Browser
"undefined"!=typeof window&&window.numbro&&window.numbro.culture&&window.numbro.culture(a.cultureCode,a)}).call("undefined"==typeof window?this:window);