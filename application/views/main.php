<?php $this->load->view('header') ?>
<script>
	function updatePosts() {
   $.ajax({
        url: 'posts',
        cache: false,
        success: function(html){
           $('#allposts').html(html);
          }
    });
 window.setTimeout(updatePosts, 1000);

}   updatePosts();
	</script>
	
	<script>
	function updateFriends() {
   $.ajax({
        url: 'get_friends',
        cache: false,
        success: function(html){
           $('#amigos').html(html);
          }
    });
 window.setTimeout(updateFriends, 1000);

}   updateFriends();
	</script>
		<script>
	$(document).ready(function(){
    $("#searchfriend").keyup (function postinput(){
		$('#amigos').hide();
		$('#searchedfriends').show();
        var matchvalue = $(this).val(); // this.value
		if (matchvalue == ""){
			$('#amigos').show();
		$('#searchedfriends').hide();
		}
        $.ajax({ 
            url: 'search_friend',
            data: { nickname: matchvalue },
			cache: false,
            type: 'post'
        }).done(function(responseData) {
             $('#searchedfriends').html(responseData);
        }).fail(function() {
            $('#searchedfriends').html(responseData);
        });
    });
}); 
	</script>
	<script>
	function addDropDown() {
    document.getElementById("addFriendDropdown").classList.toggle("show");
}

// Close the dropdown menu if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('#addIcon') && !event.target.matches('#addfriendsearch')
	  && !event.target.matches('#friendrequests')) {

    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
	</script>
	<script>
	$(document).ready(function(){
    $("#addfriendsearch").keyup (function postinput(){
        var matchvalue = $(this).val(); // this.value
		if (matchvalue.length >= 2){
        $.ajax({ 
            url: 'search_user',
            data: { nickname: matchvalue },
			cache: false,
            type: 'post'
        }).done(function(responseData) {
             $('#friendsresults').html(responseData);
        }).fail(function() {
            $('#friendsresults').html(responseData);
        });
		}
    });
}); 
	</script>
	
	<script>
	function updateRequests() {
   $.ajax({
        url: 'friend_requests',
        cache: false,
        success: function(html){
           $('#friendrequests').html(html);
          }
    });
 window.setTimeout(updateRequests, 1000);

}   updateRequests();
	</script>
	<script>
	function checkRequests() {
   $.ajax({
        url: 'friend_requests',
        cache: false,
        success: function(check){
			if (check != false){
           $('#addIcon').attr('src', "<?php echo base_url(); ?>assets/img/addfriendallerted.png");
			}else{
		  $('#addIcon').attr('src', "<?php echo base_url(); ?>assets/img/addfriend.png");
			}
          }
    });
 window.setTimeout(checkRequests, 1000);

}   checkRequests();
	</script>
	
	
  <div class="navbar" style="margin-left:25px;margin-right:25px;">
    <div class="navbar-inner" style="margin-top:10px;">
      <div class="container-fluid" >
        <a class="brand" href="#" name="top">NearFriends</a>
          <ul class="nav">
            <li><a href="#"><i class="icon-home"></i> <strong> <?php echo $nickname ?> </strong></a></li>
            <li class="divider-vertical"></li>
          </ul>
          <div class="btn-group pull-right">
			<img id="addIcon" src="<?php echo base_url(); ?>assets/img/addfriend.png" style="width:30px;height:30px;margin-right:10px;" 
			onclick="addDropDown();"/>
              <a class="btn" href="<?php echo base_url() ?>/index.php/login/logout_user"><i class="icon-share"></i> Logout</a>
          </div>
      </div>
      <!--/.container-fluid -->
    </div>
    <!--/.navbar-inner -->
		  <div id="addFriendDropdown" class="dropdown-content">
			<div id="friendrequests">
				</div>
				<div style="text-align:center;"><input id="addfriendsearch" type="text" name="q" size="7" maxlength="30"></div>
				<div id="friendsresults">
				</div>
			</div>
  </div>
  <!--/.navbar -->

  <div class="container" >

    <!-- Left Column -->
    <div class="span5 offset1" >
	
      <!-- All posts -->
      <div class="row well userInfo" style="height:500px;">		
        <div id="allposts" class="span2 userInfoSpan2" style="width:97%;height:100%;overflow:scroll;overflow-x: hidden;position:relative;word-wrap: break-word;">

        </div>
		
      </div>


      <!-- Message Box -->
      <div class="row well">
        <textarea class="span4" id="txtNewMessage" name="txtNewMessage"
                  placeholder="Type in your message" rows="5" maxlength="320"></textarea>
        <h6 class="pull-right"><span id="spanNumChars">320</span> characters remaining</h6>
        <button id="btnPost" class="btn btn-info">Post New Message</button>
      </div>

      </div> <!-- End Left Column -->

      <!-- Right Column -->
      <div class="span4 offset1">
      <div class="row">
          <h4>Friends:</h4>
            <div id="otherMessages">
                  <div  id="amigos" class="otherPost well">
                    <div class="otherAvatar">
                    </div>
                    <div class="otherPostInfo">
                      <div class="otherPostBody"><p></p></div>
                      <div class="otherPostDate"><p class="pull-right"></p></div>
                    </div>
                  </div>
				  
				  <div  id="searchedfriends" class="otherPost well" style="display:none;">
                    <div class="otherAvatar">
                    </div>
                    <div class="otherPostInfo">
                      <div class="otherPostBody"><p></p></div>
                      <div class="otherPostDate"><p class="pull-right"></p></div>
                    </div>
                  </div>
				  
            </div>
          <div  id="amigos" class="otherPost well">
               <div id="tfheader">
				<div id="tfnewsearch" >
					<img src="<?php echo base_url(); ?>assets/img/lupa.png" style="width:40px;height:40px;" /><input id="searchfriend" type="text" class="tftextinput" name="q" size="7" maxlength="120">
				</div>
			<div class="tfclear"></div>   
           </div>
      </div><!-- row -->
      </div><!-- End Right Column -->

  </div>
  </div>

<?php $this->load->view('footer') ?>