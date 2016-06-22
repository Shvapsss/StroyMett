/* Template Manager - 2.0.6 | 08 April 2015 | http://www.joomlacontenteditor.net | Copyright (C) 2006 - 2015 Ryan Demmer. All rights reserved | GNU/GPL Version 2 - http://www.gnu.org/licenses/gpl-2.0.html */
var TemplateManager={settings:{},preInit:function(){tinyMCEPopup.requireLangPack();},templateHTML:null,init:function(){var self=this;$('button#insert').click(function(e){self.insert();e.preventDefault();});var ed=tinyMCEPopup.editor,s=ed.selection,n=s.getNode();var src=ed.convertURL(ed.dom.getAttrib(n,'src'));$(document.body).append('<input type="hidden" id="src" value="'+src+'" />');$.Plugin.init();WFFileBrowser.init('#src',{onFileClick:function(e,file){self.selectFile(file);},onFileInsert:function(e,file){self.selectFile(file);},createTemplate:function(){self.createTemplate();},expandable:false});$('#insert').button('disable');},insert:function(){tinyMCEPopup.execCommand('mceInsertTemplate',false,{content:this.getHTML(),selection:tinyMCEPopup.editor.selection.getContent()});tinyMCEPopup.close();},getHTML:function(){return this.templateHTML;},setHTML:function(h){this.templateHTML=tinymce.trim(h);},createTemplate:function(){var content=tinyMCEPopup.editor.getContent();var selection=tinyMCEPopup.editor.selection.getContent();if(selection===''){selection=content;}
var extras=''+'<p>'+' <label for="template_type">'+tinyMCEPopup.getLang('dlg.type','Type')+'</label>'+' <select id="template_type">'+'  <option value="snippet">'+tinyMCEPopup.getLang('templatemanager_dlg.snippet','Snippet')+'</option>'+'  <option value="template">'+tinyMCEPopup.getLang('templatemanager_dlg.template','Template')+'</option>'+' </select>';'</p>';$.Dialog.prompt(tinyMCEPopup.getLang('templatemanager_dlg.new_template','Create Template'),{text:tinyMCEPopup.getLang('dlg.name','Name'),elements:extras,height:180,confirm:function(name){var type=$('#template_type').val();WFFileBrowser.status(tinyMCEPopup.getLang('dlg.message_load','Loading...'),true);var dir=WFFileBrowser.getCurrentDir();$.JSON.request('createTemplate',{'json':[dir,name,type],'data':selection},function(o){WFFileBrowser.load(name);});$(this).dialog('close');}});},selectFile:function(file){$('#insert').addClass('loading');$.JSON.request('loadTemplate',$(file).attr('id'),function(o){if(!o.error){TemplateManager.setHTML(o);}
$('#insert').removeClass('loading').button('enable');});}};TemplateManager.preInit();tinyMCEPopup.onInit.add(TemplateManager.init,TemplateManager);