/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function(){
    
    $(document).ready(function (){
       
        // Find matches
        var mql = window.matchMedia("(orientation: portrait)");

        // If there are matches, we're in portrait
        if(mql.matches) {  
            // Portrait orientation
            $(".home-features").removeClass("medium-4").addClass("medium-6");
            $(".home-whatson").removeClass("medium-4").addClass("medium-12");
            $(".home-offers").removeClass("medium-8").addClass("medium-6");
            $(".home-location").removeClass("medium-8").addClass("medium-12");
        } else {  
            // Landscape orientation
            $(".home-features").removeClass("medium-6").addClass("medium-4");
            $(".home-whatson").removeClass("medium-12").addClass("medium-4");
            $(".home-offers").removeClass("medium-6").addClass("medium-8");
            $(".home-location").removeClass("medium-12").addClass("medium-8");
        }

        // Add a media query change listener
        mql.addListener(function(m) {
                if(m.matches) {
                    // Changed to portrait
                    $(".home-features").removeClass("medium-4").addClass("medium-6");
                    $(".home-whatson").removeClass("medium-4").addClass("medium-12");
                    $(".home-offers").removeClass("medium-8").addClass("medium-6");
                    $(".home-location").removeClass("medium-8").addClass("medium-12");
                }
                else {
                    // Changed to landscape
                    $(".home-features").removeClass("medium-6").addClass("medium-4");
                    $(".home-whatson").removeClass("medium-12").addClass("medium-4");
                    $(".home-offers").removeClass("medium-6").addClass("medium-8");
                    $(".home-location").removeClass("medium-12").addClass("medium-8");
                }
        });
        
    });
});