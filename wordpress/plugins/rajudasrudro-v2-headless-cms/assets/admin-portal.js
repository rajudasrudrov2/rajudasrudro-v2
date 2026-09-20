(function(){
  'use strict';
  var portal=document.querySelector('[data-rdr-portal]');
  var toggle=document.querySelector('[data-rdr-menu-toggle]');
  var sidebar=document.getElementById('rdr-sidebar');
  if(toggle&&sidebar){toggle.addEventListener('click',function(){var open=sidebar.classList.toggle('is-open');toggle.setAttribute('aria-expanded',open?'true':'false');});}

  document.addEventListener('click',function(event){
    var pick=event.target.closest('[data-rdr-media-pick]');
    var clear=event.target.closest('[data-rdr-media-clear]');
    if(!pick&&!clear)return;
    var field=(pick||clear).closest('[data-rdr-media-field]'); if(!field)return;
    var input=field.querySelector('[data-rdr-media-input]'); var preview=field.querySelector('[data-rdr-media-preview]'); var multiple=field.getAttribute('data-multiple')==='1';
    if(clear){input.value=''; if(preview)preview.innerHTML=''; return;}
    if(!window.wp||!wp.media)return;
    var frame=wp.media({title:multiple?'Select media':'Select media',button:{text:'Use selected media'},multiple:multiple});
    frame.on('select',function(){var selection=frame.state().get('selection');var items=selection.map(function(item){return item.toJSON();});input.value=items.map(function(item){return item.id;}).join(',');if(preview){preview.innerHTML='';items.forEach(function(item){var src=(item.sizes&&item.sizes.thumbnail?item.sizes.thumbnail.url:item.url)||'';if(src){var img=document.createElement('img');img.src=src;img.alt='';preview.appendChild(img);}});}});frame.open();
  });

  function reindex(root){
    var kind=root.getAttribute('data-rdr-repeater');var rows=root.querySelectorAll('[data-rdr-repeater-rows] > .rdr-repeater-row');
    if(kind==='structured'){var key=root.getAttribute('data-key');rows.forEach(function(row,i){row.querySelectorAll('[data-rdr-subfield]').forEach(function(field){field.name='meta['+key+']['+i+']['+field.getAttribute('data-rdr-subfield')+']';});});}
  }
  document.addEventListener('click',function(event){
    var add=event.target.closest('[data-rdr-add]'),remove=event.target.closest('[data-rdr-remove]'),up=event.target.closest('[data-rdr-up]'),down=event.target.closest('[data-rdr-down]');if(!add&&!remove&&!up&&!down)return;
    var root=(add||remove||up||down).closest('[data-rdr-repeater]');if(!root)return;var container=root.querySelector('[data-rdr-repeater-rows]');
    if(add){var kind=root.getAttribute('data-rdr-repeater');var row=document.createElement('div');row.className='rdr-repeater-row'+(kind==='structured'?' rdr-repeater-structured':'');if(kind==='structured'){row.innerHTML='<input type="text" data-rdr-subfield="title" placeholder="Title"><textarea data-rdr-subfield="body" rows="3" placeholder="Details"></textarea><div class="rdr-row-actions"><button type="button" class="rdr-button rdr-button-quiet" data-rdr-up>↑</button><button type="button" class="rdr-button rdr-button-quiet" data-rdr-down>↓</button><button type="button" class="rdr-button rdr-button-quiet" data-rdr-remove>Remove</button></div>';}else{var name=root.getAttribute('data-name');row.innerHTML='<input type="text"><div class="rdr-row-actions"><button type="button" class="rdr-button rdr-button-quiet" data-rdr-up>↑</button><button type="button" class="rdr-button rdr-button-quiet" data-rdr-down>↓</button><button type="button" class="rdr-button rdr-button-quiet" data-rdr-remove>Remove</button></div>';row.querySelector('input').name=name;}container.appendChild(row);reindex(root);row.querySelector('input,textarea').focus();}
    if(remove){var r=remove.closest('.rdr-repeater-row');if(container.children.length>1)r.remove();else r.querySelectorAll('input,textarea').forEach(function(f){f.value='';});reindex(root);}
    if(up){var ru=up.closest('.rdr-repeater-row');if(ru.previousElementSibling)container.insertBefore(ru,ru.previousElementSibling);reindex(root);}
    if(down){var rd=down.closest('.rdr-repeater-row');if(rd.nextElementSibling)container.insertBefore(rd.nextElementSibling,rd);reindex(root);}
  });

  document.querySelectorAll('[data-rdr-unsaved]').forEach(function(form){var dirty=false;var state=form.querySelector('[data-rdr-save-state]');form.addEventListener('input',function(){dirty=true;if(state)state.textContent='Unsaved changes';});form.addEventListener('change',function(){dirty=true;if(state)state.textContent='Unsaved changes';});form.addEventListener('submit',function(){dirty=false;var button=form.querySelector('[data-rdr-save]');if(button){button.disabled=true;button.dataset.original=button.textContent;button.textContent='Saving…';}if(state)state.textContent='Saving';});window.addEventListener('beforeunload',function(e){if(!dirty)return;e.preventDefault();e.returnValue='';});});
})();
