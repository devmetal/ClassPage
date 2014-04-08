(function($){
    
    $.fn.accordion = function() {        
        var self = this;
        
        this.find(".item > .content").hide();
        this.find(".item > .title").click(function(){
            var content = $(this).next(".content");
            
            if (content.is(":visible")) {
                content.hide();
                return;
            } else {
                self.find(".content:visible").hide();
                content.show();
            }
        });
        
        return this;
    };
    
}(jQuery));