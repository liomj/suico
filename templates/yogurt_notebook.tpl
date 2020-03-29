<{include file='db:yogurt_navbar.tpl'}>

<{ if $isanonym!=1 }>
	<div id="yogurt-Notes-form" class="outer">
		<form name="formNoteNew" id="formNoteNew" action="submit_Note.php" method="post"  >
			<p>
				<input type='hidden' name='uid' id='uid' value='<{$uid_owner}>' /><input type='hidden' name='mainform' id='mainform' value='1' />
				<{$token}>
				<textarea name='text' id='text' rows='5' cols='50' ><{$lang_entertext}></textarea>
			</p>

			<p>
				<input type='submit' class='formButton' name='post_Note'  id='post_Note' value='<{$lang_submit}>' />
				<a href="#" name='show_tips'  id='show_tips' value='Notetips'>
					<{$lang_tips}>
				</a> 
			</p>
		</form>
		<div id="xtips" name="xtips" style="width:500px;height:50px;" action="">
		[b]<{$lang_bold}>[/b] => <b><{$lang_bold}></b> | [i]<{$lang_italic}>[/i] => <i><{$lang_italic}></i> | [u]<{$lang_underline}>[/u] => <u><{$lang_underline}></u>
		</div>
</div>
<{ /if}>

<div id="yogurt-Notes-container" class="outer">
			<h2 class="head"><{$section_name}></h2>
<{ if $lang_noNotesyet=="" }>
<{section name=i loop=$Notes}>
			
	<div class="yogurt-Note-details <{cycle values="odd,even"}>">
		<div class="yogurt-Note-avatarandname">
			<p class="yogurt-Note-uname">&nbsp;<a href="index.php?uid=<{$Notes[i].uid}>" ><{$Notes[i].uname}></a>
			</p>
			<{ if $Notes[i].user_avatar=="blank.gif" }><img src="images/noavatar.gif" /> <{ else }><img width="100"  class="avatar_Note" src="../../uploads/<{$Notes[i].user_avatar}>" /> <{/if}>
			<{ if $isOwner==1}>
				<p>
					<form name="delete_Note" method="post" action="delete_Note.php" class="yogurt-Note-form-delete">
						<input value="<{$Notes[i].id}>" type="image" id="Note_idimage" name="Note_idimage" src="images/dele.gif"  />
						<input value="<{$Notes[i].id}>" type="hidden" id="Note_id" name="Note_id"   />
					</form>
					
				</p>
			<{ /if}>
		</div>
		<div class="yogurt-Note-details-texts">
			<p class="yogurt-Note-text"><img src="images/Notes.gif" alt="<{$section_name}>" title="<{$section_name}>" />
				&nbsp;<{$Notes[i].text}>
				<a name="replyform<{$Notes[i].id}>" id="replyform<{$Notes[i].id}>" ></a>
			</p>
			<{ if $isOwner==1}>			
				<div class="yogurt-Note-details-form" >
				  <form  action="submit_Note.php" method="post" name="form_reply_<{$Notes[i].id}>"
					id="form_reply_<{$Notes[i].id}>" class="yogurt-Note-form">
            			<input type="hidden" value="<{$Notes[i].uid}>" name="uid" id="uid"/>
        	    		<textarea name="text" id="text" cols="50" ></textarea>
            	    	<{$token}>
						<div>
							<input type="submit" name="submit" value="<{$lang_answerNote}>" /><input type="reset" name="reset"
							value="<{$lang_cancel}>" class="resetNote"  />
						</div>
	              </form> 
	          	</div>
	       	<{/if}>
        </div>
		<!--
		<{ if $isOwner==1}>
		  <div ><a  class="yogurt-Notes-replyNote">
			<{$lang_answerNote}>
		  </a></div>
		<{ /if}> 
		-->
     </div>
 <{/section}>
 <{ else }>
 <h2><{$lang_noNotesyet}></h2>
 <{ /if }>
		          
	
</div>
<div id="yogurt-navegacao">
	<{$pageNav}>
</div>
<div style="clear:both;width:100%"></div>
<{include file='db:yogurt_footer.tpl'}>