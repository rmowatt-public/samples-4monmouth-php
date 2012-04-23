FM.Faq = {
	Root : {
		populateedit : function(e) {
			var pars = {
						id : e.target.id.split("qa_edit_")[1],
						action : 'popedit'
					}
			FM.doAjax('/root/ajaxupdatefaq', $H(pars).toQueryString(),
					FM.Faq.Root.populateeditCallback );
		},
		
		populateeditCallback : function(transport) {			
			faq = transport.responseText.evalJSON();
			$('question').value = faq.question;
			$('answer').value = faq.answer;
			$('active').checked = (faq.active == 1) ? true : false;
			//alert(faq.id)
			$('id').value = faq.id;
			jQuery("#fieldset-questioninfo h4").html("Edit : FAQ #"+faq.id);
			window.location ="#form";
		},
		
		deletefaq : function(e) {
			var pars = {
						id : e.target.id.split("qa_delete_")[1],
						action : 'delete'
					}
			if(confirm('Are you sure you want to delete this question '+pars.id+'?')) {
				FM.doAjax('/root/ajaxupdatefaq', $H(pars).toQueryString(),
						FM.Faq.Root.deletefaqCallback );
			}
			jQuery("#q_"+pars.id +" , #a_"+pars.id).remove();
		},
		
		deletefaqCallback : function(transport) {
			if(transport.responseText != 'false') {
				$('del_'+transport.responseText).remove();
				FM.ajaxStatus('Question Deleted');
			}
		},
		
		clearForm : function(e){
			$("faqForm").reset();			
			jQuery("#fieldset-questioninfo h4").html("");
			$("id").value = "";
			return false;
		}
	}
}