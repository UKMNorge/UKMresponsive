window.fbAsyncInit = function() {
	// init the FB JS SDK
	FB.init({
	  appId      : '1391407634407445',      // App ID from the app dashboard
	  channelUrl : '//mariusmandal.no', 	// Channel file for x-domain comms
	  status     : true,                    // Check Facebook Login status
	  xfbml      : false                     // Look for social plugins on the page
	});
	// Additional initialization code such as adding Event Listeners goes here
};

// Load the SDK asynchronously

jQuery(document).ready(function(){
	(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
 }(document, 'script', 'facebook-jssdk'));
});