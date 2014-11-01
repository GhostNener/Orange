
	/*errormsg successmsg   msgbox*/
	function alertmsg(id,msg,intime,outtime){
			id='#'+id;
			$(id).stop();
			$(id).children('.msgbox').html(msg);
			$(id).fadeIn(intime);
			setTimeout(function(){
				$(id).fadeOut(outtime);
			},outtime);
			
		}
	function showerrormsg(msg,intime,outtime){
		alertmsg('errormsg',msg,intime,outtime);
		}
	function showsuccessmsg(msg,intime,outtime){
			alertmsg('successmsg',msg,intime,outtime);
		}