<{include file="db:yogurt_navbar.tpl"}>

<{ if ($showForm==1) }>
    <div id="yogurt-album-form" class="outer">
      <h2 class="head">
        <{$lang_formtitle}>
      </h2>
      <form name="form_picture"  id="form_picture" action="submit.php" method="post"  enctype="multipart/form-data">
        <p class="odd"><{$token}><label class="yogurt-album-label-alert"><{$lang_youcanupload}></label></p>
        <p class="even"><label class="yogurt-album-label-alert"><{$lang_nb_pict}> <{$lang_max_nb_pict}></label></p>
        <p class="odd"><label class="yogurt-album-label" for="sel_photo"><{$lang_selectphoto}></label><input type='hidden' name='MAX_FILE_SIZE' value='<{$maxfilebytes}>'><input type='file' name='sel_photo' id='sel_photo'><input type='hidden' name='xoops_upload_file[]' id='xoops_upload_file[]' value='sel_photo'> </p>
        <p class="even"> <label class="yogurt-album-label" for="caption"><{$lang_caption}>:
        <input type='text' name='caption' id='caption' size='35' maxlength='55' value=''> </p>
        <p class="foot"><input type='submit' class='formButton' name='submit_button'  id='submit_button' value='<{$lang_uploadpicture}>'></p>
      </form>
   </div>
<{ /if}>
<div id="yogurt-album-container" class="outer">
  <h2 class="head">
  <{if $isOwner}>
    <{$lang_mysection}>
  <{else}>
    <{$owner_uname}><{$section_name}>
  <{/if}>
  </h2>
  <{ if $lang_nopicyet=="" }>
    <{section name=i loop=$pics_array}>
      <{ if (($pics_array[i].private == 0) || ($isOwner==1)) }>
        <div  class="yogurt-album-picture <{ cycle values="odd,even"}>">
        <form action="delpicture.php" method="post" id="deleteform" class="yogurt-album-formquick">
                <input type="hidden" value="<{$pics_array[i].cod_img}>" name="cod_img">
                <{$token}>
                <input name="submit" type="image" alt="<{$lang_delete}>" title="<{$lang_delete}>" src="assets/images/dele.gif">
        </form>
        <form action="editdesc.php" method="post" id="editform" class="yogurt-album-formquick">
                <input type="hidden" value="<{$pics_array[i].cod_img}>" name="cod_img">
                <{$token}>
                <input name="submit" type="image" alt="<{$lang_editdesc}>" title="<{$lang_editdesc}>" src="assets/images/edit.gif">
        </form>
        <form action="avatar.php" method="post" id="setavatar" class="yogurt-album-formquick">
                <input type="hidden" value="<{$pics_array[i].cod_img}>" name="cod_img">
                <{$token}>
                <input name="submit" type="image" alt="<{$lang_avatarchange}>" title="<{$lang_avatarchange}>" src="assets/images/avatar.gif">
        </form>
        <form action="private.php" method="post" id="setprivate" class="yogurt-album-formquick">
                <input type="hidden" value="<{$pics_array[i].cod_img}>" name="cod_img">
                <{$token}>
             <{ if $pics_array[i].private == 1}>
                <input type="hidden" value="0" name="private">
                <input name="submit" type="image" alt="<{$lang_unsetprivate}>" title="<{$lang_unsetprivate}>" src="assets/images/unlock.gif">
             <{else}>
                <input type="hidden" value="1" name="private">
                <input name="submit" type="image" alt="<{$lang_setprivate}>" title="<{$lang_setprivate}>" src="assets/images/lock.gif">
            <{ /if }>
         </form>
        <{ if ($pics_array[i].private == 1) }>
          <p><span class="yogurt-album-private"> Private </span></p>
        <{ /if }>
        <p class="yogurt-album-picture-img"><a href="<{$xoops_url}>/uploads/resized_<{$pics_array[i].url}>" rel="lightbox[album]" title="<{$pics_array[i].desc}>">
          <img class="thumb" src="<{$xoops_url}>/uploads/thumb_<{$pics_array[i].url}>" rel="lightbox" title="<{$pics_array[i].desc}>">
        </a></p>
        <p id="yogurt-album-picture-desc"><{$pics_array[i].desc}></p>
        </div>
      <{ /if }>
    <{/section}>
  <{ else }>
     <h2 id="yogurt-album-nopic"><{$lang_nopicyet}></h2>
  <{ /if }>
</div>
<{ if $navegacao!='' }>
  <div id="yogurt-navegacao"><{$navegacao}></div>
<{ /if}>
<div style="clear:both;width:100%"></div>
<{include file="db:yogurt_footer.tpl"}>
