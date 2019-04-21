<form id="comment-form"  action="" method="post" class="add-comment well">
  <div id="comment-preview-placeholder"></div>
  <input type="hidden" name="thread" value="{$thread}"/>
  <input type="hidden" name="parent" value="0"/>
  <input type="hidden" name="id" value="0"/>
  <fieldset>

    <div  class="form-group">
      <label for="comment-name">{'ticket_comment_name' | lexicon}:</label>
      <input type="text" name="name" value="{$name}" id="comment-name" class="form-control"/>
      <span class="error"></span>
    </div>

<!--
    <div>
      <label>Rating:</label>
      <span class="rate">
      <span class="star"></span>
      <span class="star"></span>
      <span class="star"></span>
      <span class="star"></span>
      <span class="star"></span>
      </span>
      <div class="clearfix"></div>
    </div>
-->

    <div class="form-group margin-top-20">
      <label for="comment-email">{'ticket_comment_email' | lexicon}: <span>*</span></label>
      <input type="text" name="email" value="{$email}" id="comment-email" class="form-control"/>
      <span class="error"></span>
    </div>

    <div class="form-group">
      <label for="comment-editor">Review: <span>*</span></label>
      <textarea name="text" id="comment-editor" cols="40" rows="3" class="form-control"></textarea>
    </div>
    {if $_modx->user.id > 0}
      <div class="form-group">
        <label for="comment-captcha" id="comment-captcha">{$captcha}</label>
        <input type="text" name="captcha" value="" id="comment-captcha" class="form-control" />
        <span class="error"></span>
      </div>
    {/if}

  </fieldset>

  <div class="form-actions">
    <input type="button" class="btn btn-default preview button color" value="{'ticket_comment_preview' | lexicon}"
           title="Ctrl + Enter"/>
    <input type="submit" class="btn btn-primary submit button color" value="{'ticket_comment_save' | lexicon}"
           title="Ctrl + Shift + Enter"/>
    <span class="time"></span>
  </div>
  <div class="clearfix"></div>

</form>