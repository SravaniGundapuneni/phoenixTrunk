<div class="editItemOptionsList" id="<?= $abId ?>">
    <div id="edit-delete" class="editItemOption" onclick="phoenixUpdateStatus(document.<?php echo $this->form->getName();?>, 'trash'); showWait(); return false;" onmouseover="this.className='editItemOption editItemOptionOver'" onmouseout="this.className='editItemOption'">
        <img src="<?php echo $this->toolboxIncludeUrl;?>module/ListModule/view/layout/img/editItem-trash.png">
        <span>Trash</span>
    </div>
    <div id="edit-cancel" class="editItemOption" onclick="window.location = '<?php echo $this->url($this->toolboxRoute);?>'; showWait(); return false;" onmouseover="this.className='editItemOption editItemOptionOver'" onmouseout="this.className='editItemOption'">
        <img src="<?php echo $this->toolboxIncludeUrl;?>module/ListModule/view/layout/img/editItem-cancel.png">
        <span>Cancel</span>
    </div>
    <div id="edit-archive" class="editItemOption" onclick="phoenixUpdateStatus(document.<?php echo $this->form->getName();?>, 'archive'); showWait(); return false;" onmouseover="this.className='editItemOption editItemOptionOver'" onmouseout="this.className='editItemOption'">
        <img src="<?php echo $this->toolboxIncludeUrl;?>module/ListModule/view/layout/img/editItem-archive.png">
        <span>Archive</span>
    </div>
    <div id="edit-draft" class="editItemOption" onclick="phoenixUpdateStatus(document.<?php echo $this->form->getName();?>, 'draft'); showWait(); return false;" onmouseover="this.className='editItemOption editItemOptionOver'" onmouseout="this.className='editItemOption'">
        <img src="<?php echo $this->toolboxIncludeUrl;?>module/ListModule/view/layout/img/editItem-draft.png">
        <span>Save as draft</span>
    </div>
    <div id="edit-save" class="editItemOption" onclick="phoenixUpdateStatus(document.<?php echo $this->form->getName();?>, 'save'); showWait(); return false;" onmouseover="this.className='editItemOption editItemOptionOver'" onmouseout="this.className='editItemOption'">
        <img src="<?php echo $this->toolboxIncludeUrl;?>module/ListModule/view/layout/img/editItem-save.png">
        <span>Save</span>
    </div>
</div>
