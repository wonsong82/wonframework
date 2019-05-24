<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Please Login</title>
<link rel="stylesheet" type="text/css" media="all" href="<?=$this->config->adminContent?>css/screen.css"/>
</head>

<body class="login">
<p class="error"><?=isset($this->req->post['webwon_user_id'])?$this->user->lastError():'';?></p>
<form action="<?=$this->url->url?>" method="post">
<table>
	<tr>
    	<td>Username</td>
        <td><input name="webwon_user_id" autofocus="autofocus" type="text" /></td>
        <td rowspan="2"><button tabindex="3" type="submit">LOGIN</button></td>        
    </tr>
    <tr>
    	<td>Password</td>
        <td><input name="webwon_user_pass" type="password" /></td>        
    </tr>
</table>
</form>
</body>
</html>