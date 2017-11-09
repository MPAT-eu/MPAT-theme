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
 * AUTHORS:
 * Miggi Zwicklbauer (miggi.zwicklbauer@fokus.fraunhofer.de)
 * Thomas Tröllmich  (thomas.troellmich@fokus.fraunhofer.de)
 *
 **/
var password_input = document.getElementById('password-input');
var password_form = document.getElementById('password-form');

document.addEventListener("keydown",function(e){
    if (e.keyCode === KeyEvent.VK_ENTER){
        password_form.submit();
        return;
    }
    if (e.keyCode === KeyEvent.VK_BACK){
        password_input.value = password_input.value.substr(0,password_input.value.length-1);
        return;
    }
    for (var i = 0;i<=9;i++){
        if (e.keyCode === KeyEvent["VK_"+i]){

            password_input.value+=i.toString();
            return;
        }
    }
    return false;
});