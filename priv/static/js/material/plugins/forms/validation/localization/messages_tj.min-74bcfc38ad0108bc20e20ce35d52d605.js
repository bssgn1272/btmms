/*! jQuery Validation Plugin - v1.13.1 - 10/14/2014
 * http://jqueryvalidation.org/
 * Copyright (c) 2014 Jörn Zaefferer; Licensed MIT */
!function(a){"function"==typeof define&&define.amd?define(["jquery","../jquery.validate.min"],a):a(jQuery)}(function(a){a.extend(a.validator.messages,{required:"Ворид кардани ин филд маҷбури аст.",remote:"Илтимос, маълумоти саҳеҳ ворид кунед.",email:"Илтимос, почтаи электронии саҳеҳ ворид кунед.",url:"Илтимос, URL адреси саҳеҳ ворид кунед.",date:"Илтимос, таърихи саҳеҳ ворид кунед.",dateISO:"Илтимос, таърихи саҳеҳи (ISO)ӣ ворид кунед.",number:"Илтимос, рақамҳои саҳеҳ ворид кунед.",digits:"Илтимос, танҳо рақам ворид кунед.",creditcard:"Илтимос, кредит карди саҳеҳ ворид кунед.",equalTo:"Илтимос, миқдори баробар ворид кунед.",extension:"Илтимос, қофияи файлро дуруст интихоб кунед",maxlength:a.validator.format("Илтимос, бештар аз {0} рамз ворид накунед."),minlength:a.validator.format("Илтимос, камтар аз {0} рамз ворид накунед."),rangelength:a.validator.format("Илтимос, камтар аз {0} ва зиёда аз {1} рамз ворид кунед."),range:a.validator.format("Илтимос, аз {0} то {1} рақам зиёд ворид кунед."),max:a.validator.format("Илтимос, бештар аз {0} рақам ворид накунед."),min:a.validator.format("Илтимос, камтар аз {0} рақам ворид накунед.")})});