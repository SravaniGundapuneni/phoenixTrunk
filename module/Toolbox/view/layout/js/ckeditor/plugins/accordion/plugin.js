/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

CKEDITOR.plugins.add( 'accordion',
{
	init: function( editor )
	{
            editor.ui.addButton( 'Accordion',
            {
                    label: 'Insert Accordion',
                    command: 'accordionDialog',
                    icon: this.path + 'images/template11.gif'
            } );
            editor.addCommand( 'accordionDialog', new CKEDITOR.dialogCommand( 'accordionDialog' ) );
            CKEDITOR.dialog.add( 'accordionDialog', function( editor )
            {
                return {
                            title : 'Accordion Properties',
                            minWidth : 400,
                            minHeight : 200,
                            contents :
                            [
                                {
                                    id : 'accordion',
                                    label : 'Settings',
                                    elements :
                                    [
                                        {
                                            type : 'text',
                                            id : 'txtTotalAccordion',
                                            label : 'No of Accordions:',
                                            style : 'width:100px;',
                                            maxLength : 2,
                                            'default' : '3',
                                            validate : CKEDITOR.dialog.validate.notEmpty( 'No of Accordions can not be zero or null!' )
                                        },
                                        {
                                            type : 'checkbox',
                                            id : 'chkFirstAccordionOpen',
                                            label : 'First Accordion Open',
                                            'default' : true
                                        }
                                    ]
                                }
                            ],
                            onOk: function() {
                                    var ta = this.getContentElement('accordion', 'txtTotalAccordion').getValue();
                                    var firstOpen = "";
                                    if(this.getContentElement('accordion', 'chkFirstAccordionOpen').getValue())
                                    {
                                        firstOpen = " first-open";
                                    }

                                    var strAccordion = '<dl class="accordion" data-accordion>';
                                    var totalAccordion = parseInt(ta);
                                    for(var i=1; i <= totalAccordion; i++)
                                    {
                                        if(i == 1)
                                            strAccordion = strAccordion + '<dd class="accordion-navigation"><a href="#panel'+i+'a">Accordion '+i+'</a><div id="panel'+i+'a" class="content '+firstOpen+' active">Panel '+i+'. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div></dd>';
                                        else
                                            strAccordion = strAccordion + '<dd class="accordion-navigation"><a href="#panel'+i+'a">Accordion '+i+'</a><div id="panel'+i+'a" class="content active">Panel '+i+'. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div></dd>';
                                    }
                                    strAccordion = strAccordion + '</dl>';
                                    editor.insertHtml( strAccordion );
                            }
                };
            });
	}
} );