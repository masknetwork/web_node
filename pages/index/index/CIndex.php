<?
  class CIndex
  {
	  function CIndex($db, $template)
	  {
		  $this->kern=$db;
		  $this->template=$template;
	  }
	  
	  function showLoginModal()
	  {
		  $this->template->showModalHeader("modal_login", "Login", "act", "login");
		?>
           
           <div style="padding-left:30px; padding-right:30px;">
           <div class="row"><span style="padding-left:12px">Username</span></div>
           <input class="form-control" id="txt_user" value=""></input>
           <br>
           <div class="row"><span style="padding-left:12px">Password</span></div>
           <input class="form-control" id="txt_pass" type="password" value=""></input>
           </div>
           <br>
           
        <?
		$this->template->showModalFooter("Login");
	}
	
	function showSignupModal()
	  {
		  $this->template->showModalHeader("modal_signup", "New Account", "act", "signup");
		?>
           
           <div style="padding-left:30px; padding-right:30px;">
          
           <div class="row"><span style="padding-left:12px">Username</span></div>
           <input class="form-control" id="txt_user" value=""></input>
           <br>
          
           <div class="row"><span style="padding-left:12px">Email</span></div>
           <input class="form-control" id="txt_email" type="password" value=""></input>
           <br>
           
           <div class="row"><span style="padding-left:12px">Password</span></div>
           <input class="form-control" id="txt_pass_1" type="password" value=""></input>
           <br>
           
           <div class="row"><span style="padding-left:12px">Retype Password</span></div>
           <input class="form-control" id="txt_pass_2" type="password" value=""></input>
           
           </div>
           <br>
           
        <?
		$this->template->showModalFooter("Signup");
	}
  }
?>