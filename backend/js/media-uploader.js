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
jQuery(document).ready(function($) {
  var currentMediaUploader;

  $(this).on('click', '.mpat-insert-media', function( event ) {

    event.preventDefault();

    var data_type = $(this).data("type") || "" ;
    var data_multiple = $(this).data("multiple") || false;

    var target = document.getElementById( $(this).attr("target") );

    var options = {
      frame:    'post',
      state:    'insert',
      title:    wp.media.view.l10n.addMedia,
      multiple: data_multiple,
      library:  { type : data_type }
    };

    var frame = wp.media(options);

    frame.on('insert', () => {

      var selection = frame.state().get('selection');
      selection.each((attachment) => {

        if (target) {

          if ( attachment.attributes.sizes && attachment.attributes.sizes.large )
            target.value = attachment.attributes.sizes.large.url ;
          else
            target.value = attachment.attributes.url ;

          var changeEvent = new Event('input', { bubbles: true });
          target.dispatchEvent(changeEvent);
        }

      });

    });

    frame.open();


  });

  $(this).on('click', '.add_media', function() {
    currentMediaUploader = jQuery(this);
    return false;
  });
  window.original_send_to_editor = window.send_to_editor;
  window.send_to_editor = function(html) {
    console.log("send to editor: " + html);
    if (currentMediaUploader && currentMediaUploader.attr("target")) {
      var inputField = document.getElementById(currentMediaUploader.attr("target"));

      if(html.indexOf("[audio")===0){
        var urlstart = html.indexOf("http");
        inputField.value = html.substr(urlstart).replace('"][/audio]',"")
      }else if(html.indexOf("[video")===0){
        var urlstart = html.indexOf("http");
        inputField.value = html.substr(urlstart).replace('"][/video]',"")
      }else{
        var $html = $(html);
        if($html.is("img")){
          inputField.value = $html.attr("src");
        }else if($html.is("a")){
          if($html.children("img").length){
            inputField.value = $html.children("img").eq(0).attr("src");
          }else{
            inputField.value = $html.attr("href");
          }
        }
      }
      var changeEvent = new Event('input', { bubbles: true });
      inputField.dispatchEvent(changeEvent);
    } else {
      window.original_send_to_editor(html);
    }
  }
});
