/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready( function(){
    $('.collapse-link_2').on('click', function(){
        var $BOX_PANEL = $(this).closest('.x_panel_2'),
            $ICON = $(this).find('i:first'),
            $BOX_CONTENT = $BOX_PANEL.find('.x_content_2:first');
            
        $ICON.toggleClass("fa-plus fa-minus");
        $BOX_CONTENT.toggle();
    });
});


