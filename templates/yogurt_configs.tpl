<{include file="db:yogurt_navbar.tpl"}>



<{ if $isanonym!=1 && $isOwner==1}>

<form action="submit_configs.php" method="POST" id="form_configs" name="form_configs" class="outer">
<h2><{$lang_whocan}></h2>
<{ if $allow_pictures!=-1 }>
<fieldset class="myconfigs-fieldset" name="pictures" id="pictures">
<legend class="myconfigs-legend" for="pictures"><{$lang_configpictures}></legend>

<p><input type="radio" name="pic" id="pic0" value="0" <{ if $pic==0 }>checked<{/if}>><label for="pic0"><{$lang_everyone}></label></p>
<p> <input type="radio" name="pic" id="pic1" value="1" <{ if $pic==1}>checked<{/if}>><label for="pic1"><{$lang_only_users}></label></p>
 <p> <input type="radio" name="pic" id="pic2" value="2" <{ if $pic==2 }>checked<{/if}>> <label for="pic2"><{$lang_only_friends}></label></p>
 <p><input type="radio" name="pic" id="pic3" value="3" <{ if $pic==3 }>checked<{/if}>> <label for="pic3"><{$lang_only_me}></label></p>

</fieldset><br><{ /if }><{ if $allow_videos!=-1 }>
<fieldset class="myconfigs-fieldset" name="videos" id="videos">
<legend class="myconfigs-legend" for="videos"><{$lang_configvideos}></legend>

<p> <input type="radio" name="vid" id="vid0" value="0" <{ if $vid==0 }>checked<{/if}>> <label for="vid0"><{$lang_everyone}></label></p>
 <p> <input type="radio" name="vid" id="vid1" value="1"<{ if $vid==1 }>checked<{/if}>><label for="vid1"><{$lang_only_users}></label></p>
 <p> <input type="radio" name="vid" id="vid2" value="2"<{ if $vid==2 }>checked<{/if}>><label for="vid2"><{$lang_only_friends}></label></p>
  <p><input type="radio" name="vid" id="vid3" value="3"<{ if $vid==3 }>checked<{/if}>><label for="vid3"><{$lang_only_me}></label></p>

</fieldset><br><{ /if }><{ if $allow_tribes!=-1 }>
<fieldset class="myconfigs-fieldset" name="tribes" id="tribes">
<legend class="myconfigs-legend" for="tribes"><{$lang_configtribes}></legend>

<p>  <input type="radio" name="tribes" id="tribes0" value="0" <{ if $tri==0 }>checked<{/if}>><label for="tribes0"><{$lang_everyone}></label></p>
<p><input type="radio" name="tribes" id="tribes1" value="1" <{ if $tri==1 }>checked<{/if}>>  <label for="tribes1"><{$lang_only_users}></label></p>
<p><input type="radio" name="tribes" id="tribes2" value="2" <{ if $tri==2 }>checked<{/if}>>  <label for="tribes2"><{$lang_only_friends}></label></p>
<p><input type="radio" name="tribes" id="tribes3" value="3" <{ if $tri==3 }>checked<{/if}>>  <label for="tribes3"><{$lang_only_me}></label></p>

</fieldset><br><{ /if }>
<{ if $allow_notes!=-1 }>
<fieldset class="myconfigs-fieldset" name="Notes" id="Notes">
<legend class="myconfigs-legend" for="Notes"><{$lang_configNotes}></legend>

<p><input type="radio" name="Notes" id="Notes0" value="0" <{ if $scr==0 }>checked<{/if}>>  <label for="Notes0"><{$lang_everyone}></label></p>
<p><input type="radio" name="Notes" id="Notes1" value="1" <{ if $scr==1 }>checked<{/if}>>  <label for="Notes1"><{$lang_only_users}></label></p>
<p><input type="radio" name="Notes" id="Notes2" value="2" <{ if $scr==2 }>checked<{/if}>>  <label for="Notes2"><{$lang_only_friends}></label></p>
<p><input type="radio" name="Notes" id="Notes3" value="3" <{ if $scr==3 }>checked<{/if}>>  <label for="Notes3"><{$lang_only_me}></label></p>

</fieldset><br>
<!--<fieldset name="sendNotes" id="sendNotes">
<legend for="sendNotes"><{$lang_configsendNotes}></legend>

<p><input type="radio" name="sendNotes" id="sendNotes0" value="0" <{ if $sscr==0 }>checked<{/if}>>  <label for="sendNotes0"><{$lang_everyone}></label></p>
<p><input type="radio" name="sendNotes" id="sendNotes1" value="1" <{ if $sscr==1 }>checked<{/if}>>  <label for="sendNotes1"><{$lang_only_users}></label></p>
<p><input type="radio" name="sendNotes" id="sendNotes2" value="2" <{ if $sscr==2 }>checked<{/if}>>  <label for="sendNotes2"><{$lang_only_friends}></label></p>
<p><input type="radio" name="sendNotes" id="sendNotes3" value="3" <{ if $sscr==3 }>checked<{/if}>>  <label for="sendNotes3"><{$lang_only_me}></label></p>

</fieldset><br>--><{ /if }><{ if $allow_friends!=-1 }>
<fieldset  class="myconfigs-fieldset" name="friends" id="friends">
<legend class="myconfigs-legend" for="friends"><{$lang_configfriends}></legend>

  <p><input type="radio" name="friends" id="friends0" value="0" <{ if $fri==0 }>checked<{/if}>><label for="friends0"><{$lang_everyone}></label></p>
  <p><input type="radio" name="friends" id="friends1" value="1" <{ if $fri==1 }>checked<{/if}>><label for="friends1"><{$lang_only_users}></label></p>
  <p><input type="radio" name="friends" id="friends2" value="2" <{ if $fri==2 }>checked<{/if}>><label for="friends2"><{$lang_only_friends}></label></p>
  <p><input type="radio" name="friends" id="friends3" value="3" <{ if $fri==3 }>checked<{/if}>><label for="friends3"><{$lang_only_me}></label></p>

</fieldset><br><{ /if }>

<fieldset class="myconfigs-fieldset" name="profileContact" id="profileContact">
<legend class="myconfigs-legend" for="profileContact"><{$lang_configprofilecontact}></legend>

<p><input type="radio" name="profileContact" id="contact0" value="0" <{ if $pcon==0 }>checked<{/if}>>  <label for="contact0"><{$lang_everyone}></label></p>
<p><input type="radio" name="profileContact" id="contact1" value="1" <{ if $pcon==1 }>checked<{/if}>>  <label for="contact1"><{$lang_only_users}></label></p>
<p><input type="radio" name="profileContact" id="contact2" value="2" <{ if $pcon==2 }>checked<{/if}>>  <label for="contact2"><{$lang_only_friends}></label></p>
<p><input type="radio" name="profileContact" id="contact3" value="3" <{ if $pcon==3 }>checked<{/if}>>  <label for="contact3"><{$lang_only_me}></label></p>


</fieldset><br>
<fieldset  class="myconfigs-fieldset" name="gen" id="gen">
<legend class="myconfigs-legend" for="gen"><{$lang_configprofilegeneral}></legend>

<p><input type="radio" name="gen" id="gen0" value="0" <{ if $pgen==0 }>checked<{/if}>>  <label for="gen0"><{$lang_everyone}></label></p>
<p><input type="radio" name="gen" id="gen1" value="1" <{ if $pgen==1 }>checked<{/if}>>  <label for="gen1"><{$lang_only_users}></label></p>
<p><input type="radio" name="gen" id="gen2" value="2" <{ if $pgen==2 }>checked<{/if}>>  <label for="gen2"><{$lang_only_friends}></label></p>
<p><input type="radio" name="gen" id="gen3" value="3" <{ if $pgen==3 }>checked<{/if}>>  <label for="gen3"><{$lang_only_me}></label></p>

</fieldset><br>
<fieldset  class="myconfigs-fieldset" name="stat" id="stat">
<legend class="myconfigs-legend" for="stat"><{$lang_configprofilestats}></legend>

<p><input type="radio" name="stat" id="stat0" value="0" <{ if $psta==0 }>checked<{/if}>>  <label for="stat0"><{$lang_everyone}></label></p>
<p><input type="radio" name="stat" id="stat1" value="1" <{ if $psta==1 }>checked<{/if}>>  <label for="stat1"><{$lang_only_users}></label></p>
<p><input type="radio" name="stat" id="stat2" value="2" <{ if $psta==2 }>checked<{/if}>>  <label for="stat2"><{$lang_only_friends}></label></p>
<p><input type="radio" name="stat" id="stat3" value="3" <{ if $psta==3 }>checked<{/if}>>  <label for="stat3"><{$lang_only_me}></label></p>

</fieldset><br>
<{ if $allow_audios!=-1 }>
<fieldset  class="myconfigs-fieldset" name="audio" id="audio">
<legend class="myconfigs-legend" for="audio"><{$lang_configaudio}></legend>

<p><input type="radio" name="aud" id="aud0" value="0" <{ if $psta==0 }>checked<{/if}>>  <label for="aud0"><{$lang_everyone}></label></p>
<p><input type="radio" name="aud" id="aud1" value="1" <{ if $psta==1 }>checked<{/if}>>  <label for="aud1"><{$lang_only_users}></label></p>
<p><input type="radio" name="aud" id="aud2" value="2" <{ if $psta==2 }>checked<{/if}>>  <label for="aud2"><{$lang_only_friends}></label></p>
<p><input type="radio" name="aud" id="aud3" value="3" <{ if $psta==3 }>checked<{/if}>>  <label for="aud3"><{$lang_only_me}></label></p>

</fieldset><br>
<{ /if }>
<p><input type="submit" name="button" id="button"></p>
<{$token}>
</form>



<{ /if}>

<{include file="db:yogurt_footer.tpl"}>
