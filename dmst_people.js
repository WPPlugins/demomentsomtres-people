/*
 * DeMomentSomTres People 
 * http://demomentsomtres.com
 * Tab generation and management
 */
jQuery(document).ready(function(){
    jQuery('div.dmst_people').each(function(){
        jQuery(document.createElement('ul')).addClass("dmst_people_tabs").appendTo(this);
        jQuery(document.createElement('div')).addClass("dmst_people_tabcontents").appendTo(this);
        jQuery(this).find('h2.department').each(function(){
            var tabs=jQuery(this).parent().find('ul.dmst_people_tabs');
            var li=jQuery(document.createElement('li'));
            li.addClass('inactive');
            jQuery(this).appendTo(li);
            li.appendTo(tabs);
            tabs.find('li:first').addClass('active').removeClass('inactive');
        });
        jQuery(this).find('div.panelDepartment').each(function(){
            var panels=jQuery(this).parent().find('div.dmst_people_tabcontents');
            var imatges=jQuery(document.createElement('div'));
            var textos=jQuery(document.createElement('div'));
            var clear=jQuery(document.createElement('div'));
            var ampleTextos=jQuery(this).width()-675;
            imatges.addClass("images");
            textos.addClass("texts");
            textos.width(ampleTextos);
            clear.addClass("clear");
            jQuery(this).find('a.people_image').appendTo(imatges);
            jQuery(this).find('div.people_description').appendTo(textos);
            textos.find('div.people_description').addClass('inactive');
            textos.find('div.people_description:first').addClass('active').removeClass('inactive');
            imatges.find('a.people_image').addClass('inactive');
            imatges.find('a.people_image:first').removeClass('inactive');
            imatges.find('a.people_image:first').addClass('active');
            imatges.appendTo(jQuery(this));
            textos.appendTo(jQuery(this));
            clear.appendTo(jQuery(this));
            jQuery(this).appendTo(panels);
            jQuery(this).addClass('inactive');
        });
        jQuery('div.dmst_people_tabcontents div:first').addClass('active').removeClass('inactive');
    });
    jQuery('ul.dmst_people_tabs li a').click(function(e){
        var contentLocation = jQuery(this).attr('href');
        if(contentLocation.charAt(0)=="#"){
            e.preventDefault();
            jQuery(this).parent().parent().addClass('active').removeClass('inactive').siblings().removeClass('active').addClass('inactive');
            jQuery(contentLocation).addClass('active').removeClass('inactive').siblings().removeClass('active').addClass('inactive');
        }        
    });
    jQuery('div.dmst_people_tabcontents a.people_image').click(function(e){
        var contentLocation = jQuery(this).attr('href');
        if(contentLocation.charAt(0)=="#"){
            e.preventDefault();
//            var position=jQuery(this).position();
//            var textPosition=jQuery(contentLocation).parent().position();
            var offset=jQuery(this).offset();
            var divOffset=jQuery(this).parent().offset();
            var divMarge=jQuery(this).parent().css('margin-top');
            var marge=offset.top-divOffset.top+14;
//            var textOffset=jQuery(contentLocation).parent().offset();
            jQuery(this).addClass('active').removeClass('inactive').siblings().removeClass('active').addClass('inactive');
//            jQuery(contentLocation).parent().css('top',position.top).css('left',textPosition.left).css('position','absolute').css('margin-top','5px');
//            jQuery(contentLocation).addClass('active').removeClass('inactive').siblings().removeClass('active').addClass('inactive');
//            jQuery(contentLocation).parent()
//                .css('top',position.top)
//                .css('left',textPosition.left)
//                .css('position','absolute')
//                .css('margin-top','5px');
//            jQuery(contentLocation).addClass('active')
//                .removeClass('inactive')
//                .siblings()
//                .removeClass('active')
//                .addClass('inactive');
            jQuery(contentLocation).parent()
                .css('margin-top',marge)
//                .css('top',position.top)
//                .css('left',textPosition.left)
//                .css('position','absolute')
//                .css('margin-top','5px');
            jQuery(contentLocation).addClass('active')
                .removeClass('inactive')
                .siblings()
                .removeClass('active')
                .addClass('inactive');

        }        
    });
});
//    tab.click(function(e) {
//			
//        //Get Location of tab's content
//        var contentLocation = $(this).attr('href') + "Tab";
//			
//        //Let go if not a hashed one
//        if(contentLocation.charAt(0)=="#") {
//			
//            e.preventDefault();
//			
//            //Make Tab Active
//            tab.removeClass('active');
//            $(this).addClass('active');
//				
//            //Show Tab Content & add active class
//            $(contentLocation).show().addClass('active').siblings().hide().removeClass('active');
//				
//        } 
//    });
//}); 
