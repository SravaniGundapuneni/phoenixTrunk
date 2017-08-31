;if (typeof app === 'undefined') {
    throw new Error('App is undefined in footer.js');
}

if (typeof CKEDITOR === 'undefined') {
    throw new Error('CKEDITOR is undefined in footer.js');
}

(function($, CKEDITOR, app) {
    // We need to turn off the automatic editor creation first.
    CKEDITOR.disableAutoInline = true;

    var editorFlag = false;

    window.onbeforeunload = function(evt) {
        if (evt && editorFlag) {
            evt.returnValue = "There are some unsaved changes on this page.";
        }
    }

    _initEventHandlers();

    function _activateAccordionNavigationContent() {
        $(document).on(".accordion-navigation").find(".content").each(function() {
            $(this).addClass('active');
        });
    }

    function _activateFirstOpenContent() {
        $(document).on(".accordion-navigation").find(".content").each(function() {
            var $content = $(this);

            $content.removeClass('active');

            if ($content.hasClass('first-open')) {
                $content.addClass('active');
            }
        });
    }

    function _cancelPage() {
        var shouldCancelChanges = confirm("Are you sure you want to cancel the changes?");

        if (shouldCancelChanges) {
            _setEditorFlag(false);
            window.location.reload();
        } else {
            return false;
        }
    }

    function _clickToSave() {
        var url = _getSavePageUrl(),
            options = _getSavePageOptions();

        _setEditorFlag(false);

        _savePage(url, options)
            .done(function() {
                alert('Content saved!');
                window.location.reload();
            });

        _activateFirstOpenContent();
        _updateResponsiveImages();
    }

    function _getContent() {
        return CKEDITOR.instances.editor1.getData();
    }

    function _getContent2() {
        return ''; 
    }

    function _getCurrentLanguageCode() {
        var code = $('body').data('currentlanguagecode'); 
        return code.replace(/[\W_]+/g, ""); // remove non-alphanumeric values
    }

    function _getCurrentPage() {
        return $('#currentPage').val();
    }

    function _getModulePath(moduleItem) {
        var module = $(this).attr('data-module');

        if (moduleItem == '' || moduleItem == 'undefined') {
            modulepath = app.constants.siteroot + 'toolbox/tools/' + module.replace(" ", "-");
        } else {
            modulepath = app.constants.siteroot + 'toolbox/tools/' + module.replace(" ", "-") + '/editItem/' + moduleItem;
        }
    }

    function _getPageID() {
        return $('#pageID').val();
    }

    function _getPageKeyNum() {
        return parseInt($('body').data('pagekeynum'), 10);
    }

    function _getSavePageOptions() {
        return {
            content: _getContent(),
            content2: _getContent2(),
            currentLanguageCode: _getCurrentLanguageCode(),
            currentPage: _getCurrentPage(),
            pageID: _getPageID(),
            pageKeyNum: _getPageKeyNum(),
            siteroot: _getSiteRoot()
        };
    }

    function _getSavePageUrl() {
        return app.pagedata.PAGES_SOCKET;
    }

    function _getSiteRoot() {
        return app.constants.siteroot;
    }

    function _hideEditPage() {
        $('.editPageLi').hide();
    }

    function _initEditor() {
        var editableElements  = document.getElementsByClassName('editable'),
            anEditor = [],
            index,
            editableElementsLength = editableElements.length,
            selection;

        for (index = 0; index < editableElementsLength; index++) {      

             anEditor[index] = CKEDITOR.inline(editableElements[index]);         

             if (anEditor[index]) {
                selection = '.editable:eq(' + index + ')';
                $('.savePageLi').show();
                $(selection).show();
                $(selection).attr('contentEditable',true);
                $(selection).attr('ondragstart','return false;');
                $(selection).attr('ondrop','return false;');
                $(selection).addClass('editorDiv');
            }
        }    
    }

    function _initEventHandlers() {
        _initRunEditor();   
        _initCancelPage();
        _initClickToSave();
        _initPageContent();
    }

    function _initCancelPage() {
        $('.editor-control #cancelPage').on('click', function() {
            _cancelPage();
        });
    }

    function _initClickToSave() {
        $('.editor-control #clickToSave').on('click', function() {
            _clickToSave();
        });
    }

    function _initPageContent() {
        var pageContent = app.pagedata.blocks;

        if (!(pageContent == '')) {
            $('.editable').html(pageContent);
        }
    }

    function _initRunEditor() {
        $('.editor-control #runEditor').on('click', function() {
            _runEditor();
        });
    }

    function _runEditor() {
        _setEditorFlag(true); 
        _updateItemWrappers();
        _activateAccordionNavigationContent();
        _hideEditPage();
        _showCancelPage();
        _initEditor();
    }

    function _savePage(url, options) {
        return $.post(url, options);
    }

    function _setEditorFlag(flag) {
        editorFlag = flag;
    }

    function _showCancelPage() {
        $('.cancelPageLi').show();
    }

    function _updateItemWrappers() {
        $('.widget-item-wrapper').each(function() {
            var $itemWrapper = $(this),
                moduleItem = $itemWrapper.attr('data-id'),
                modulepath = _getModulePath.call(this, moduleItem);

            $itemWrapper.addClass('dlm-edit-window');
            $itemWrapper.prepend('<a href="'+modulepath+'" class="dlm-edit">EDIT</a>');
        });
    }

    function _updateResponsiveImages() {
        $('.editable').find('img').each(function() {
            var $img = $(this);

            if (!($img.hasClass('containerImage'))) {
                $img.addClass('containerImage');
            }
        });
    }
})(jQuery, CKEDITOR, app);