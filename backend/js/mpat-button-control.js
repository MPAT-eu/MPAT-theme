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
 * Stefano Miccoli (stefano.miccoli@finconsgroup.com)
 **/
const maxMenuItems = 3;

jQuery(document).ready(function($){
	var dragSrcEl = null;

	var buttons = {
		handleDragStart: function(e) {
			dragSrcEl = $(this);
			e.dataTransfer.effectAllowed = 'move';
			e.dataTransfer.setData('text/html', this.innerHTML);
  			dragSrcEl.css('opacity','0.4');  // this / e.target is the source node.
  		},

  		handleDragOver: function(e) {
  			if (e.preventDefault) {
		    	e.preventDefault(); // Necessary. Allows us to drop.
		    }
		    if (!dragSrcEl.hasClass('drag-button')) return;
		  	e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.
		  	return false;
		},

		handleDragEnter: function (e) {
			if (e.preventDefault) {
		    	e.preventDefault(); // Necessary. Allows us to drop.
		    }
		  if (!dragSrcEl.hasClass('drag-button')) return;
		  $(this).addClass('over');
		},

		handleDragLeave: function(e) {
			if (e.preventDefault) {
		    	e.preventDefault(); // Necessary. Allows us to drop.
		    }
		    if (!dragSrcEl.hasClass('drag-button')) return;
		  	$(this).removeClass('over');  // this / e.target is previous target element.
		},

		handleDrop: function(e) {
	  		if (e.preventDefault) {
		    	e.preventDefault();
		    }
		    if (e.stopPropagation) {
	    		e.stopPropagation();
	    	}
	    	if (!dragSrcEl.hasClass('drag-button')) return;
	    	dragSrcEl.css('opacity','1.0');
	    	if (this!=dragSrcEl[0]){
	    		dragSrcEl.html(this.innerHTML);
	    		this.innerHTML = e.dataTransfer.getData('text/html');
	    	}
	  		return false;
		},

		handleDragEnd: function(e) {
			if (e.preventDefault) {
			    e.preventDefault(); // Necessary. Allows us to drop.
			}
		    if (!dragSrcEl.hasClass('drag-button')) return;
		  	this.style.opacity = '1.0';
		},


		addDragListeners: function($el){
			var that = this;
			$el.each(function(){
				this.addEventListener('dragstart', that.handleDragStart, false);
				this.addEventListener('dragenter', that.handleDragEnter, false);
				this.addEventListener('dragover', that.handleDragOver, false);
				this.addEventListener('dragleave', that.handleDragLeave, false);
				this.addEventListener('drop', that.handleDrop, false);
				this.addEventListener('dragend', that.handleDragEnd, false);
			});
		}
	}

	var menuItems = {
		handleDragStart: function(e) {
			if ($(this).hasClass('placeholder')) return;
			dragSrcEl = $(this).parent();
			e.dataTransfer.effectAllowed = 'move';
			e.dataTransfer.setData('text/html', dragSrcEl.html());
  			dragSrcEl.css('opacity','0.4');  // this / e.target is the source node.
  		},

  		handleDragOver: function(e) {
  			if (e.preventDefault) {
		    	e.preventDefault(); // Necessary. Allows us to drop.
		    }
		    if (!dragSrcEl.hasClass('drag-menu-item')) return;
		  	e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.
		  	return false;
		},

		handleDragEnter: function (e) {
			if (e.preventDefault) {
		    	e.preventDefault(); // Necessary. Allows us to drop.
		    }
		  if (!dragSrcEl.hasClass('drag-menu-item')) return;
		  $(this).parent().addClass('over');
		},

		handleDragLeave: function(e) {
			if (e.preventDefault) {
		    	e.preventDefault(); // Necessary. Allows us to drop.
		    }
		    if (!dragSrcEl.hasClass('drag-menu-item')) return;
		  	 $(this).parent().removeClass('over');  // this / e.target is previous target element.
		},

		handleDrop: function(e) {
			console.log(dragSrcEl);
	  		if (e.preventDefault) {
		    	e.preventDefault();
		    }
		    if (e.stopPropagation) {
	    		e.stopPropagation();
	    	}
	    	dragSrcEl.css('opacity','1.0');
	    	if (!dragSrcEl.hasClass('drag-menu-item')) return;
	    	var par = $(this).parent()
	    	if (par[0]===dragSrcEl[0] || par.hasClass('unused')) return;
	    	if (dragSrcEl.hasClass('unused')){
	    		var new_button = $('<div class="mpat-menu-button"></div>');
	    		var button_bar = $('#button-bar');
	    		new_button.append(button_bar.children().first().remove().children().first());
	    		dragSrcEl.removeClass('unused').append('<div alt="f153" class="dashicons dashicons-dismiss remove-menu-item"></div>').prepend(new_button);
	    		par.before(dragSrcEl);
	    		removeButtons();
	    	} else {
	    		par.before(dragSrcEl);
	    	}
	    	
	  		return false;
		},

		handleDragEnd: function(e) {
			if (e.preventDefault) {
			    e.preventDefault(); // Necessary. Allows us to drop.
			}
		    if (!dragSrcEl.hasClass('drag-menu-item')) return;
		    $('.over').removeClass('over');
		  	$(this).parent().css('opacity','1.0');
		},


		addDragListeners: function($el){
			var that = this;
			$el.each(function(){
				this.addEventListener('dragstart', that.handleDragStart, false);
				this.addEventListener('dragenter', that.handleDragEnter, false);
				this.addEventListener('dragover', that.handleDragOver, false);
				this.addEventListener('dragleave', that.handleDragLeave, false);
				this.addEventListener('drop', that.handleDrop, false);
				this.addEventListener('dragend', that.handleDragEnd, false);
			});
		}

	}

	buttons.addDragListeners($('.drag-button'));
	menuItems.addDragListeners($('.mpat-menu-post'));

	function removeButtons(){
		$('.remove-menu-item').click(function(){
			var list_item = $('<li class="drag-menu-item unused"></li>');
			var par = $(this).parent();
			var post = par.children('.mpat-menu-post').eq(0);
			var button = $('<li></li').append(par.find('.drag-button').eq(0));
			$('#button-bar').prepend(button);
			list_item.append(post);
			par.remove();
			$('.btn_control_pages_list[post-type="'+post.attr('post-type')+'"]').eq(0).append(list_item);
		});
	}
	removeButtons();

});

 function saveButtonControlData(){
	 $ = jQuery;
	 theData = new Array();
	 $('#menu-item-list .drag-menu-item').not('.placeholder').each(function(){
		 var $this = $(this);
		 var button = $this.find('.mpat-button').eq(0).attr('button-name');
		 if ($this.attr('id')=='hide-button'){
			 theData.push({is_hide: true, button_name: button, show: 'true'});
		 } else {
			 var post= $this.find('.mpat-menu-post').eq(0).attr('post-id');
			 theData.push({post_id: post, button_name: button, show: 'true'});
		 }
	 });
	 $('#invis-item-list .drag-menu-item').not('.placeholder').each(function(){
		 var $this = $(this);
		 var button = $this.find('.mpat-button').eq(0).attr('button-name');
		 if ($(this).attr('id')=='hide-button'){
			 theData.push({is_hide: true, button_name: button, show: 'false'});
		 } else {
			 var post= $this.find('.mpat-menu-post').eq(0).attr('post-id');
			 theData.push({post_id: post, button_name: button, show: 'false'});
		 }
	 });

	 console.log(theData);
	 console.log($('#main-menu-button .mpat-button').attr('button-name'));
	 $.ajax({
		 url: ajaxurl,
		 type: 'POST',
		 data: {
			 action: "mpat_save_button_control",
			 items: theData,
			 main_menu_button: $('#main-menu-button .mpat-button').attr('button-name')
		 }
	 }).done(function(){
		 console.log("Data saved");
		 $('#save-notification').stop().show().delay(2000).fadeOut(500);
	 }).fail(function(xhr, status, error) {
		 console.log("Failed to Save");
	 });
 }

