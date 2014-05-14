<?php /* Smarty version 2.6.27, created on 2014-05-13 10:44:34
         compiled from admin/login.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Insert title here</title>
</head>
<body>
  <?php if ($this->_tpl_vars['massage']): ?>
  	<h2><?php echo $this->_tpl_vars['massage']; ?>
</h2>
  <?php endif; ?>
  <form action="/admin/index/" method="post" name="loginForm">
    <table summary="ログインフォーム">
      <thead></thead><tfoot></tfoot>
      <tbody>
        <tr>
          <th>管理者メールアドレス</th>
          <td>
            <input type="text" name="email">
          </td>
        </tr>
        <tr>
          <th>管理者パスワード</th>
          <td>
            <input type="password" name="encrypted_password">
          </td>
        </tr>
      </tbody>
    </table>
    <input type="hidden" name="login_flg" value="1">
    <input type="submit" value="ログイン">
  </form>
</body>
</html>