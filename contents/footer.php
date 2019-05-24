</div><!--#wrap-->

<div id="footer">

	<div id="slogan" class="agaramond">Pure, Natural, New Zealand Food</div>

	<div id="contact">
    	<?php $c = $this->Contact->getContacts(); ?>
        Tel: +<?=$c[0]['phone']?> (NZ) &nbsp;&nbsp;&nbsp;
        +<?=$c[1]['phone']?> (CN) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Email: <a href="mailto:<?=$c[0]['email']?>"><?=$c[0]['email']?></a> 
        ©2012 Oravida Ltd.  All rights reserved. 
    </div><!--#contact-->
    
    <div id="footer-links">
    	<a href="<?=$this->Config->site_url?>/legal-statement">Legal Statement</a>  |  
        <a href="<?=$this->Config->site_url?>/privacy-policy">Privacy Policy</a>    
       
    </div>     
    
</div>

<script type="text/javascript"> Cufon.now();</script>
</body>
</html>