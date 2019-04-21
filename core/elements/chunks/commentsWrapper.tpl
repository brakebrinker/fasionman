<div class="comments">
    <!--    <h3 class="title">[[%comments]] (<span id="comment-total">[[+total]]</span>)</h3>-->
   
    <div id="comments-wrapper">
        {if $comments?}
          <ol class="comment-list" id="comments">{$comments}</ol>
        {else}
          <p class="margin-bottom-10">Здесь еще нет комметариев.</p>
        {/if}
    </div>

    <div id="comments-tpanel">
        <div id="tpanel-refresh"></div>
        <div id="tpanel-new"></div>
    </div>
</div>

<!--tickets_subscribed checked-->