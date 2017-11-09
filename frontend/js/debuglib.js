/**
 *
 * Copyright (c) 2017 MPAT Consortium , All rights reserved.
 * Fraunhofer FOKUS, Fincons Group, Telecom ParisTech, IRT, Lacaster University, Leadin, RBB, Mediaset
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 *
 **/
var TVDebugServerInterface = (function () {
    "use strict";
    var serverUrl = location.protocol + '//' + location.hostname + ':' + 3000;
    var exports = {};

    exports.log = function(message) {
        if (location.hash === "#tvdebug") {
			var xhr = new XMLHttpRequest();
			xhr.open("GET", serverUrl + "/log?message=" + encodeURIComponent(message));
			xhr.send();
        }
    };

    return exports;
})();
