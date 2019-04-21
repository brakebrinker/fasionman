<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie ie8" lang="ru"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="ru">
<!--<![endif]-->

<head>
  {insert 'file:chunks/meta.tpl'}
</head>

<body class="boxed">
  <div id="wrapper">

    {insert 'file:chunks/header.tpl'}
    
    {block 'content'}{/block}

    {insert 'file:chunks/footer.tpl'}
    
  </div>

  <!-- Java Script ================================================== -->
  {insert 'file:chunks/scripts.tpl'}

</body>
</html>