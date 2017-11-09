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
/**
 * Use KeyEvent - Constants when available
 */
KeyEvent = window['KeyEvent'] || {};

KeyEvent.VK_ENTER 	= KeyEvent.VK_ENTER 	|| 13;
KeyEvent.VK_BACK 	= KeyEvent.VK_BACK 		|| 461;

KeyEvent.VK_LEFT 	= KeyEvent.VK_LEFT 		|| 37;
KeyEvent.VK_UP 		= KeyEvent.VK_UP 		|| 38;
KeyEvent.VK_RIGHT 	= KeyEvent.VK_RIGHT 	|| 39;
KeyEvent.VK_DOWN 	= KeyEvent.VK_DOWN 		|| 40;

KeyEvent.VK_RED 	= KeyEvent.VK_RED 		|| 403;
KeyEvent.VK_GREEN 	= KeyEvent.VK_GREEN 	|| 404;
KeyEvent.VK_YELLOW 	= KeyEvent.VK_YELLOW 	|| 405;
KeyEvent.VK_BLUE 	= KeyEvent.VK_BLUE 		|| 406;

KeyEvent.VK_0 = KeyEvent.VK_0 || 48;
KeyEvent.VK_1 = KeyEvent.VK_1 || 49;
KeyEvent.VK_2 = KeyEvent.VK_2 || 50;
KeyEvent.VK_3 = KeyEvent.VK_3 || 51;
KeyEvent.VK_4 = KeyEvent.VK_4 || 52;
KeyEvent.VK_5 = KeyEvent.VK_5 || 53;
KeyEvent.VK_6 = KeyEvent.VK_6 || 54;
KeyEvent.VK_7 = KeyEvent.VK_7 || 55;
KeyEvent.VK_8 = KeyEvent.VK_8 || 56;
KeyEvent.VK_9 = KeyEvent.VK_9 || 57;

KeyEvent.VK_PLAY 	= KeyEvent.VK_PLAY		|| 415;
KeyEvent.VK_PAUSE 	= KeyEvent.VK_PAUSE 	|| 19;
KeyEvent.VK_STOP 	= KeyEvent.VK_STOP 		|| 413;

KeyEvent.VK_TOBEGIN 	= KeyEvent.VK_TOBEGIN 		|| 423;
KeyEvent.VK_TOEND 		= KeyEvent.VK_TOEND 		|| 425;
KeyEvent.VK_FAST_FWD 	= KeyEvent.VK_FAST_FWD 		|| 417;
KeyEvent.VK_REWIND 		= KeyEvent.VK_REWIND 		|| 412;
