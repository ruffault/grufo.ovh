/* ***** BEGIN LICENSE BLOCK *****
 * This file is part of DotClear.
 * Copyright (c) 2004 Olivier Meunier and contributors. All rights
 * reserved.
 *
 * DotClear is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * DotClear is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with DotClear; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * ***** END LICENSE BLOCK ***** */
 
function str2url(str,encoding)
{
	str = str.toUpperCase();
	str = str.toLowerCase();
	
	str = str.replace(/[������]/g,'a');
	str = str.replace(/[�]/g,'c');
	str = str.replace(/[������]/g,'o');
	str = str.replace(/[����]/g,'e');
	str = str.replace(/[����]/g,'i');
	str = str.replace(/[�����]/g,'u');
	str = str.replace(/[�]/g,'n');
	
	str = str.replace(/[^a-z0-9_\s-]/g,'');
	str = str.replace(/[\s]+/g,' ');
	str = str.replace(/[ ]/g,'-');
	return str;
}