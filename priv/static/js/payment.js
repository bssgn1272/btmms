  function checkform()
    {
			var f = document.forms["theform"].elements;
			var cansubmit = true;

			for (var i = 0; i < f.length; i++) {
				if (f[i].value.length == 0) cansubmit = false;
			}

			if (cansubmit) {
				document.getElementById('submitbutton').disabled = false;
			}
			else {
			document.getElementById('submitbutton').disabled = 'disabled';
		}
    }
	function checkBankform()
    {
			var f = document.forms["theBankform"].elements;
			var cansubmit = true;

			for (var i = 0; i < f.length; i++) {
				if (f[i].value.length == 0) cansubmit = false;
			}

			if (cansubmit) {
				document.getElementById('Bsubmitbutton').disabled = false;
			}
			else {
			document.getElementById('Bsubmitbutton').disabled = 'disabled';
		}
    }
	
	 function checkAirtelform()
    {
			var f = document.forms["theAirtelform"].elements;
			var cansubmit = true;

			for (var i = 0; i < f.length; i++) {
				if (f[i].value.length == 0) cansubmit = false;
			}

			if (cansubmit) {
				document.getElementById('Asubmitbutton').disabled = false;
			}
			else {
			document.getElementById('Asubmitbutton').disabled = 'disabled';
		}
    }
	function checkMtnform()
    {
			var f = document.forms["theMtnform"].elements;
			var cansubmit = true;

			for (var i = 0; i < f.length; i++) {
				if (f[i].value.length == 0) cansubmit = false;
			}

			if (cansubmit) {
				document.getElementById('Msubmitbutton').disabled = false;
			}
			else {
			document.getElementById('Msubmitbutton').disabled = 'disabled';
		}
    }
	
	 /* Next And Previous Buttons */
			$('.btnNext').on('click', function(e){
				e.preventDefault();
			$('.nav-tabs > .active').next('li').find('a').trigger('click');
		});

			$('.btnPrevious').click(function(){
			$('.nav-tabs > .active').prev('li').find('a').trigger('click');
		});
	/* End */
	
	

		$('input[type=radio]').click(function(){
		$('.area').hide();
		$('#' + $(this).val()).show();
		});
	
		
	/*login link */	
		  $('.tab a').on('click', function (e) {
		  e.preventDefault();
		  
		  $(this).parent().addClass('active');
		  $(this).parent().siblings().removeClass();
		  
		  
		  var href = $(this).attr('href');
		  $('.forms > form').hide();
		  $(href).fadeIn(500);
		});
	/*end*/	
	
	
	
	


