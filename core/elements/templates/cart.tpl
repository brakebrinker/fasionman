{extends 'file:templates/_base.tpl'}

{block 'content'}

{insert 'file:chunks/titlebar.tpl'}

{'!msCart' | snippet}

<div class="margin-top-40"></div>
{/block}