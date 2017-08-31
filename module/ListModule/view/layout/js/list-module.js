/* ===================================================
 * list-module.js
 *
 * ===================================================
 * Copyright 2013 TravelClick, Inc.
 *
 * List Module js handlers to manage list module items
 * ========================================================== */
!function($) {
    var listTable;

    function getItemsPerPage()
    {
        var navbar = $('#page-navigation-bar'),
            itemsPerPageAttr = navbar.attr('data-items-per-page'),
            itemsPerPage = parseInt(itemsPerPageAttr||'20');

        return itemsPerPage;
    }

    function showEditListPage(page, event) {
        var itemsPerPage = getItemsPerPage(),
            rows = $('#list-wrapper > tbody > tr'),
            start,
            i;

        if (rows.length) {
            rows.css({display:'none'});
        }

        start = (parseInt(page)-1)*itemsPerPage;

        for (i=start; i<start+itemsPerPage; ++i) {
            $(rows[i]).css({display:'table-row'});
        }
    }

    function initializeEditListNavigation() {
        var itemsPerPage = getItemsPerPage(),
            rows = $('#list-wrapper > tbody > tr'),
            lastPage = Math.ceil(rows.length / itemsPerPage),
            urlHash = document.URL.substr(document.URL.indexOf('#')+1),
            urlPageHash = (urlHash.match(/page\-\d+/g)||[null]).shift(),
            pageHash = (urlPageHash||'page-1').replace('page-',''),
            page = (pageHash>1&&pageHash<lastPage?pageHash:1);

        $('#page-navigation-bar').pagination({
            items: rows.length,
            itemsOnPage: itemsPerPage,
            cssStyle: 'light-theme',
            onPageClick: showEditListPage,
            currentPage: page
        });

        showEditListPage( page , null );
    }

    $(document).ready(function() {

        var flag = false;

        /**
         * Initialize the pagination for lists
         */
        setTimeout(function(){ initializeEditListNavigation(); }, 100);

        /**
         * Attach the language switcher
         */
        $('.editItemHeader .editItemLangSelect select').change(function(){
            var langCode = this.value;
        });

        String.prototype.toFirstLower = function () {
            return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toLowerCase() + txt.substr(1);});
        };
        
        function toggleReorder(){  
          flag = !flag;  
          return flag;  
        }
        
        $("li.editListOption").each(function(){
            if ($(this).attr("data-action") === "orderListItems")
                $(this).hide();
        });
        
        $("#empty-trash").click(function(){
                var confirmEmpty = confirm("Are you sure you want to remove all trashed items for this module?"),
                    postEmpty;

                if (confirmEmpty === true) {
                    postEmpty = $.post(emptyTrashUrl, {});

                    postEmpty.done(function( data ) {
                        $('#notifications').prepend('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> The trashed items have been removed.</div>');
                        
                        location.reload();                                
                    });              
                }

                return false;
            }
        );

        /**
         * Attach the update status calls
         */
        $('.editListOptionsList .editListOption').click(function(){
            var status = $(this).attr('data-action'),
                phoenixForm = $('#editBox .editListContent form');

            phoenixForm.find('input[name="action"]').val(status);
            
            if (status === "toggleReorder"){
                if (toggleReorder()) {
                    $(this).find('img').attr("src",toolboxIncludeUrl+"module/ListModule/view/layout/img/editItem-toggleReorderOn.png");
                    $("[name=example_length]").val('-1');
                    $("li.editListOption").each(function(){
                        if($(this).attr("data-action") === "orderListItems")
                            $(this).show();
                    });
                    $( "#sortable" ).sortable({ disabled: false });
                } else {
                    $(this).find('img').attr("src",toolboxIncludeUrl+"module/ListModule/view/layout/img/editItem-toggleReorder.png");
                    $("[name=example_length]").val('10');
                    $("li.editListOption").each(function(){
                        if($(this).attr("data-action") === "orderListItems")
                            $(this).hide();
                    });
                    $( "#sortable" ).sortable({ disabled: true });
                }
                $("[name=example_length]").change();

                return false;
            }
            
            if (status === "orderListItems"){
                var itemData = {};

                $('#sortable tr').each(function(){
                   itemData[$(this).attr("data-itemId")] = $(this).index();
                });
                
                if(module_name === "Hotels")
                    module_name = "phoenixProperties";
                
                $(".alert-success").alert('close');
                $(".alert-success").alert('close');
                
                $.ajax({
                    type: "POST",
                    url: _site_root + 'sockets/'+module_name.toFirstLower()+'/reorderitems',
                    data: {'itemsList':JSON.stringify(itemData)}, 
                    type: "POST",
                    dataType: "json",
                    success: function(data){
                        $('#notifications').prepend('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> The items have been reordered.</div>');
                    },
                    error: function(data){
                        $('#notifications').prepend('<div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> A problem has been occurred while reordering items.</div>');
                    }
                }).done(function( data ) {
                    location.reload();
                });
            } else {
                phoenixForm.submit();
            }
            showWait();
        });

        /**
         * Attach the update status calls
         */
        $('.editItemOptionsList .editItemOption').click(function(){
            var status = $(this).attr('data-action'),
                phoenixForm = $('#editBox .editItemContent form');

            phoenixForm.find('input[name="action"]').val(status);
            phoenixForm.submit();
        });
    });

}(window.jQuery);